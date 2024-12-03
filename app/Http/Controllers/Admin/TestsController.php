<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyTestRequest;
use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Test;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Chapter;
use App\Module;

class TestsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            // $query = Test::with(['course', 'lesson'])->select(sprintf('%s.*', (new Test)->table));
            $query = Test::with(['course', 'lesson'])
            ->join('chapters', 'tests.chapter_id', '=', 'chapters.id') // Join with chapters table
            ->select(
                'tests.id',
                'tests.title',
                'tests.description',
                'tests.is_published',
                'chapters.chaptername as chaptername', // Fetch chapter name
                'tests.created_at',
                'tests.updated_at'
            );

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'test_show';
                $editGate      = 'test_edit';
                $deleteGate    = 'test_delete';
                $crudRoutePart = 'tests';

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
            $table->addColumn('course_title', function ($row) {
                return $row->course ? $row->course->title : '';
            });

            $table->addColumn('lesson_title', function ($row) {
                return $row->lesson ? $row->lesson->title : '';
            });

            // Add chapter name column
        $table->addColumn('chaptername', function ($row) {
            return $row->chaptername ? $row->chaptername : '';
        });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('is_published', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_published ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'course', 'lesson', 'is_published']);

            return $table->make(true);
        }

        $courses = Course::get();
        $lessons = Lesson::get();
        $chapters = Chapter::get(); // Fetch chapters for dropdown or other uses

        return view('admin.tests.index', compact('courses', 'lessons','chapters'));
    }

    public function create()
    {
        abort_if(Gate::denies('test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$courses = Course::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');

        //$lessons = Lesson::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tests.create', compact('modules', 'chapters'));
    }

    public function store(StoreTestRequest $request)
    {
        $test = Test::create($request->all());

        return redirect()->route('admin.tests.index');
    }

    public function edit(Test $test)
    {
        abort_if(Gate::denies('test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courses = Course::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lessons = Lesson::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $modules = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $chapters = Chapter::pluck('chaptername', 'id')->prepend(trans('global.pleaseSelect'), '');


        $test->load('course', 'lesson'); 

        return view('admin.tests.edit', compact('modules', 'chapters', 'test'));
    }

    // public function update(UpdateTestRequest $request, Test $test)
    // {
    //     $test->update($request->all());

    //     return redirect()->route('admin.tests.index');
    // }


    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        // Prepare data for the update
        $updateData = [
            'module_id' => $request->input('module_id'),
            'chapter_id' => $request->input('chapter_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_published' => $request->has('is_published') ? 1 : 0,
            'updated_at' => now(), // Ensure timestamps are handled correctly
        ];

        // Update the test using Query Builder
        $affected = DB::table('tests')
            ->where('id', $id)
            ->update($updateData);

        // Check if the update was successful
        if ($affected) {
            return redirect()->route('admin.tests.index')
                ->with('success', 'Test updated successfully.');
        } else {
            return redirect()->route('admin.tests.index')
                ->with('error', 'Failed to update the test.');
        }
    }


    public function show(Test $test)
    {
        abort_if(Gate::denies('test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->load('course', 'lesson');

        return view('admin.tests.show', compact('test'));
    }

    public function destroy(Test $test)
    {
        abort_if(Gate::denies('test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $test->delete();

        return back();
    }

    public function massDestroy(MassDestroyTestRequest $request)
    {
        $tests = Test::find(request('ids'));

        foreach ($tests as $test) {
            $test->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
