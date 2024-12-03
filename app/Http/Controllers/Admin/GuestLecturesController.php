<?php

namespace App\Http\Controllers\Admin;

use App\GuestLecture;
use App\Guestspeaker;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyGuestLectureRequest;
use App\Http\Requests\StoreGuestLectureRequest;
use App\Http\Requests\UpdateGuestLectureRequest;
use App\Mentee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestSessionNotification;

class GuestLecturesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('guest_lecture_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GuestLecture::with(['speaker_name', 'invited_mentees'])->select(sprintf('%s.*', (new GuestLecture)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'guest_lecture_show';
                $editGate      = 'guest_lecture_edit';
                $deleteGate    = 'guest_lecture_delete';
                $crudRoutePart = 'guest-lectures';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('guessionsession_title', function ($row) {
                return $row->guessionsession_title ? $row->guessionsession_title : '';
            });
            $table->addColumn('speaker_name_speakername', function ($row) {
                return $row->speaker_name ? $row->speaker_name->speakername : '';
            });

            $table->editColumn('invited_mentees', function ($row) {
                $labels = [];
                foreach ($row->invited_mentees as $invited_mentee) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $invited_mentee->name);
                }

                return implode(' ', $labels);
            });

            $table->editColumn('guest_session_duration', function ($row) {
                return $row->guest_session_duration ? GuestLecture::GUEST_SESSION_DURATION_RADIO[$row->guest_session_duration] : '';
            });
            $table->editColumn('platform', function ($row) {
                return $row->platform ? GuestLecture::PLATFORM_SELECT[$row->platform] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'speaker_name', 'invited_mentees']);

            return $table->make(true);
        }

        $guestLectures = GuestLecture::with('speaker')->get();
        $guestspeakers = Guestspeaker::get();
        $mentees       = Mentee::get();

        return view('admin.guestLectures.index', compact('guestspeakers', 'mentees','guestLectures'));
    }

    public function create()
    {
        abort_if(Gate::denies('guest_lecture_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_mentees = Mentee::pluck('name', 'id');

        return view('admin.guestLectures.create', compact('invited_mentees', 'speaker_names'));
    }

    public function store(StoreGuestLectureRequest $request)
    {
       $guestLecture = GuestLecture::create($request->all());

    // Attach invited mentees
         $guestLecture->invitedMentees()->sync($request->input('invited_mentees', []));

    // Load invited mentees and speaker
        $guestLecture->load('invitedMentees', 'speaker');

    // Send email to invited mentees
        foreach ($guestLecture->invitedMentees as $mentee) {
            Mail::to($mentee->email)->send(new GuestSessionNotification($guestLecture));
        }

        return redirect()->route('admin.guest-lectures.index');
    }

    public function edit(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speaker_names = Guestspeaker::pluck('speakername', 'id')->prepend(trans('global.pleaseSelect'), '');

        $invited_mentees = Mentee::pluck('name', 'id');

        $guestLecture->load('speaker_name', 'invited_mentees');

        return view('admin.guestLectures.edit', compact('guestLecture', 'invited_mentees', 'speaker_names'));
    }

    public function update(UpdateGuestLectureRequest $request, GuestLecture $guestLecture)
    {
        $guestLecture->update($request->all());
        $guestLecture->invited_mentees()->sync($request->input('invited_mentees', []));

        return redirect()->route('admin.guest-lectures.index');
    }

    public function show(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->load('speaker_name', 'invited_mentees');

        return view('admin.guestLectures.show', compact('guestLecture'));
    }

    public function destroy(GuestLecture $guestLecture)
    {
        abort_if(Gate::denies('guest_lecture_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $guestLecture->delete();

        return back();
    }

    public function massDestroy(MassDestroyGuestLectureRequest $request)
    {
        $guestLectures = GuestLecture::find(request('ids'));

        foreach ($guestLectures as $guestLecture) {
            $guestLecture->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
