<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Mentor;
use App\Mentee;
use App\Module;
use App\Http\Requests\StoreSessionRequest;
use App\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


use App\Mail\SessionCreatedMentor;
use App\Mail\SessionCreatedMentee;

class SessionController extends Controller
{
    //
    public function index()
    {
        // Logic to fetch and display all sessions
        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $assignments = DB::table('mappings')->get();
        $mentorIds = $assignments->pluck('mentorname_id')->toArray();
        $menteeIds = $assignments->pluck('menteename_id')->toArray();

        $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $sessions = Session::where('mentorname_id', $mentorIds)->get();

        $session = DB::table('sessions')->get();

        $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');


        return view('mentor.sessions.index', compact('menteenames', 'mentornames', 'modulenames','sessions','assignments','session','sessionTitles'));
        //return view('mentor.sessions.index');
    }

    /*
    public function store(Request $request)
    {
        // Validate the request data
        /*

        // Insert the new session into the database
        DB::table('sessions')->insert([
            'session_name' => $request->session_name,
            'session_date' => $request->session_date,
            'session_duration' => $request->session_duration,
            'mentorname_id' => auth()->user()->id, // Assuming the mentor is the logged-in user
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }*/
    public function store(Request $request)

    {
        
        $session =Session::create([
            'sessiondatetime' => $request->sessiondatetime,
            'sessionlink' => $request->sessionlink,
            'session_title' => $request->session_title,
            'session_duration_minutes' => $request->session_duration_minutes,
            'modulename_id' => $request->modulename_id,
            'mentorname_id' => $request->mentorname_id,
            'menteename_id' => $request->menteename_id,
        ]);
            

            $mentor = Mentor::findOrFail($request->mentorname_id);
            $mentee = Mentee::findOrFail($request->menteename_id);

            Mail::to($mentor->email)->send(new SessionCreatedMentor($session));
            Mail::to($mentee->email)->send(new SessionCreatedMentee($session));



        $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $assignments = DB::table('mappings')->get();
        $mentorIds = $assignments->pluck('mentorname_id')->toArray();
        $menteeIds = $assignments->pluck('menteename_id')->toArray();

        $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $sessions=Session::where('mentorname_id',$mentorIds)->get();
        $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');

        //return view('mentor.sessions.index', compact('menteenames', 'mentornames', 'modulenames','sessions'));
        return view('mentor.sessions.index', compact('menteenames', 'mentornames', 'modulenames', 'sessions', 'assignments','sessionTitles'));

        //return redirect()->route('mentor.sessions.index');
    }
    public function edit($id)
    {
        // Fetch a single session record
        $session = DB::table('sessions')->where('id', $id)->first();
        
        // Check if the session exists
        if (!$session) {
            return redirect()->route('sessions.index')->with('error', 'Session not found');
        }

        // Return the view with the session data
        return view('sessions.index', ['session' => $session]);
    }


    // Method to update the session details
    public function update(Request $request, $id)
    {
        // Extract the request data directly without validation
        $sessiondatetime = $request->input('sessiondatetime');
        $sessionlink = $request->input('sessionlink');
        $session_title = $request->input('session_title');
        $session_duration_minutes = $request->input('session_duration_minutes');

        $done = $request->input('done') === 'yes' ? 1 : 0; // Assuming 1 is for done and 0 is for not done

        // Update the session in the database
        DB::table('sessions')
            ->where('id', $id)
            ->update([
                'sessiondatetime' => $sessiondatetime,
                'sessionlink' => $sessionlink,
                'session_title' => $session_title,
                'session_duration_minutes' => $session_duration_minutes,
                'done' => $done,
                'updated_at' => Carbon::now(), // Update timestamp
            ]);

            $session = DB::table('sessions')->where('id', $id)->first();

            
            $modulenames = Module::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $mentornames = Mentor::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

            $menteenames = Mentee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $assignments = DB::table('mappings')->get();
            $mentorIds = $assignments->pluck('mentorname_id')->toArray();
            $menteeIds = $assignments->pluck('menteename_id')->toArray();
            $sessions = Session::where('mentorname_id', $mentorIds)->get();
            $mentornames = Mentor::whereIn('id', $mentorIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $menteenames = Mentee::whereIn('id', $menteeIds)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
            $sessionTitles = DB::table('sessions')->pluck('session_title', 'id');

        // Redirect back to the session list or another route
        // return redirect()->route('sessions.index')->with('success', 'Session updated successfully');
        return view('mentor.sessions.index', compact('menteenames', 'mentornames', 'modulenames','sessions','session','assignments','sessionTitles'));
    }


    public function destroy($id)
    {
        // Delete the session from the database
        DB::table('sessions')->where('id', $id)->delete();

        // Redirect back to the session list or another route
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully');
    }

    public function uploadRecording(Request $request)
    {
        // Validate the request
        $request->validate([
            'selectSession' => 'required',
            'recordingFile' => 'required|file|mimes:mp3,mp4,wav,m4a|max:25600',
        ]);

        // Handle the uploaded file
        $file = $request->file('recordingFile');
        $filePath = 'recordings/' . uniqid() . '_' . $file->getClientOriginalName();

        // Upload the file to S3
        $path = Storage::disk('s3')->put($filePath, file_get_contents($file));

        if ($path) {
            // Correctly construct the S3 URL based on the bucket's region and endpoint
            //$s3Url = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $filePath;

            //$s3Url = Storage::disk('s3')->url($filePath);
            $bucket = env('AWS_BUCKET');
        $region = env('AWS_DEFAULT_REGION');
        $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
        $fileUrl = $baseUrl . $filePath;

            
            DB::table('session_recordings')->insert([
                'session_type' => $request->input('selectSession'),
                'file_path' => $fileUrl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('sessions')
            ->where('id', $request->input('selectSession'))
            ->update(['file_path' => $fileUrl]);

            return redirect()->back()->with('success', 'Recording uploaded and saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to upload recording.');
        }

            // Save the file URL to the session_recordings table
            // DB::table('session_recordings')->insert([
            //     'session_type' => $request->input('selectSession'),
            //     'file_path' => $s3Url, // Store the URL here
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);

            // // Update the sessions table with the file URL
            // DB::table('sessions')
            //     ->where('id', $request->input('selectSession'))
            //     ->update(['file_path' => $s3Url]); // Store the URL here

        //     return redirect()->back()->with('success', 'Recording uploaded and saved successfully.');
        // } else {
        //     return redirect()->back()->with('error', 'Failed to upload recording.');
        }
    }

    // public function uploadRecording(Request $request)
    // {
    //     $user_id=auth()->user()->id;
    //     if (isset($user_id)) {
    //         // code...
    //         $paadhar=$request->file('paadhar')->store('documents','s3');
    //         Storage::disk('s3')->setVisibility($paadhar,'public');
    //         //StudentProfile::where('user_id',$user_id)->update([
    //        //'paadhar' =>$url
    //     //]);
    //         $url=Storage::disk('s3')->url($paadhar);
    //         $profile=StudentProfile::where('user_id',$user_id)->first();
    //         $profile->paadhar=$url;
    //         $profile->save();
    //         $doc_id=DB::table('document_student')->insert(array('documents_id'=>'2','user_id'=>$user_id,'profile_id'=>$profile->id));
    //         return redirect()->back()->with('message','Parents Aadhar card uploaded successfully');
    //     }
    //     else{
    //         return redirect()->back()->with('message','Unable to upload');
    //     }
    //     //$request->aadhar_card->storeAs('logos','aadhar.png');
    // }
