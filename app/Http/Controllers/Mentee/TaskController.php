<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
use App\Mentor;
use App\Mentee;
use App\AssignTask;
use App\Http\Requests\StoreAssignTaskRequest;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class TaskController extends Controller
{
    //
    use MediaUploadingTrait;
    public function index()
    {

        //$mentorEmail = Auth::user()->email;
        //$mentor = Mentor::where('email', $mentorEmail)->first();
        /*
        if (!$mentor) {
            return  'Mentor not found.';
        }*/
        //$tasks = AssignTask::where('mentor_id', $mentor->id)->get();

        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
        if (!$mentee) {
            return  'Mentee not found.';
        }
        //return $mentee;

        $tasks = AssignTask::where('mentee_id', $mentee->id)->get();
        $unsubmittedTasks = AssignTask::where('mentee_id', $mentee->id)
                             ->whereNull('task_response')
                             ->get();
        $submittedTasks = AssignTask::where('mentee_id', $mentee->id)
                            ->whereNotNull('task_response')
                            ->get();
        
        //return $unsubmittedTasks;
        return view('mentee.tasks.index',compact('mentee','tasks','unsubmittedTasks','submittedTasks'));
    }
    /*
    public function submit(Request $request)
{
    try {
        // Retrieve the task by ID
        $task = AssignTask::findOrFail($request->task_id);
        
        // Update the task response
        $task->task_response = $request->task_response;
        
        // Initialize an array to collect any failed uploads
        $failedUploads = [];

        // Handle attachments if needed
        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    // Store the attachment using Spatie MediaLibrary
                    $media = $task->addMedia($file)->toMediaCollection('attachments', 's3');
                } catch (\Exception $e) {
                    // If an error occurs, add the file to the failed uploads array
                    $failedUploads[] = $file->getClientOriginalName();
                }
            }
        }

        // Update CKEditor media if it exists
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        // Save the task
        $task->save();

        // Check if there were any failed uploads
        if (!empty($failedUploads)) {
            // Log the failed uploads (or handle as needed)
            \Log::error('Failed to upload the following files:', $failedUploads);
            // Optionally, you could return a message or redirect with the failed uploads
            return redirect()->back()->withErrors(['Failed to upload some files. Please try again.']);
        }

        // Retrieve the mentee information
        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
        if (!$mentee) {
            return 'Mentee not found.';
        }

        // Retrieve all tasks for the mentee
        $tasks = AssignTask::where('mentee_id', $mentee->id)->get();

        // Return the view with updated data
        return view('mentee.tasks.index', compact('mentee', 'tasks'));
    } catch (\Exception $e) {
        // Handle any unexpected errors here
        \Log::error('Error submitting task:', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['An unexpected error occurred. Please try again.']);
    }
}*/
/*
public function submit(Request $request)
{
    try {
        // Retrieve the task by ID
        $task = AssignTask::findOrFail($request->task_id);
        
        // Update the task response
        $task->task_response = $request->task_response;
        
        // Initialize an array to collect any failed uploads
        $failedUploads = [];

        // Handle attachments if needed
        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    // Store the attachment using Spatie MediaLibrary
                    $media = $task->addMedia($file)->toMediaCollection('attachments', 's3');
                } catch (\Exception $e) {
                    // If an error occurs, add the file to the failed uploads array
                    $failedUploads[] = $file->getClientOriginalName();
                }
            }
        }

        // Update CKEditor media if it exists
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        // Save the task
        $task->save();

        // Check if there were any failed uploads
        if (!empty($failedUploads)) {
            // Log the failed uploads (or handle as needed)
            \Log::error('Failed to upload the following files:', $failedUploads);
            // Return a response indicating failure
            return response()->json(['success' => false, 'message' => 'Failed to upload some files. Please try again.'], 422);
        }

        // Retrieve the mentee information
        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
        if (!$mentee) {
            return response()->json(['success' => false, 'message' => 'Mentee not found.'], 404);
        }

        // Retrieve all tasks for the mentee
        $tasks = AssignTask::where('mentee_id', $mentee->id)->get();

        // Return a success response with updated data
        return response()->json(['success' => true, 'data' => ['mentee' => $mentee, 'tasks' => $tasks]], 200);

    } catch (\Exception $e) {
        // Handle any unexpected errors here
        \Log::error('Error submitting task:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.'], 500);
    }
}
*/

public function submit(Request $request)
{
    try {
        // Retrieve the task by ID
        $task = AssignTask::findOrFail($request->task_id);
        
        // Update the task response
        $task->task_response = $request->task_response;
        
        // Initialize arrays for failed uploads and file URLs
        $failedUploads = [];
        $fileUrls = [];

        // Handle attachments if needed
        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    // Store the attachment using Spatie MediaLibrary to S3
                    $media = $task->addMedia($file)->toMediaCollection('attachments', 's3');
                    
                    // Get the URL of the uploaded file on S3
                    $fileUrl = $media->getFullUrl(); // This retrieves the full URL including the S3 domain
                    $fileUrls[] = $fileUrl;
                } catch (\Exception $e) {
                    // If an error occurs, add the file to the failed uploads array
                    $failedUploads[] = $file->getClientOriginalName();
                    Log::error('Failed to upload file: ' . $file->getClientOriginalName());
                }
            }
        }

        // Update CKEditor media if it exists
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $task->id]);
        }

        // Save the task
        $task->save();

        // Check if there were any failed uploads
        if (!empty($failedUploads)) {
            // Log the failed uploads
            Log::error('Failed to upload the following files:', $failedUploads);
            // Return a response indicating failure
            return response()->json(['success' => false, 'message' => 'Failed to upload some files. Please try again.'], 422);
        }

        // Retrieve the mentee information
        $menteeEmail = Auth::user()->email;
        $mentee = Mentee::where('email', $menteeEmail)->first();
        if (!$mentee) {
            return response()->json(['success' => false, 'message' => 'Mentee not found.'], 404);
        }

        // Retrieve all tasks for the mentee
        $tasks = AssignTask::where('mentee_id', $mentee->id)->get();

        // Prepare data to include file URLs
        $data = [
            'mentee' => $mentee,
            'tasks' => $tasks,
            'file_urls' => $fileUrls // Include file URLs in the response
        ];

        // Return a success response with updated data
        //return response()->json(['success' => true, 'data' => $data], 200);
        //return view('mentee.tasks.index', compact('mentee', 'tasks'));
        return redirect()->route('menteetasks.index')->with(compact('mentee', 'tasks'));

    } catch (\Exception $e) {
        // Handle any unexpected errors here
        Log::error('Error submitting task:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.'], 500);
    }
}
public function storeCKEditorImages(Request $request)
    {
        

        $model         = new AssignTask();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    
}

    
