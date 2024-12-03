<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function quizdetails()
{
    $mentor = auth()->user();

            $mentorid = DB::table('mentors')
            ->where('email', $mentor->email)->first();

            $mentorid = $mentorid->id;


      // Get the mapped mentee using query builder
        $mappedMentee = DB::table('mappings')
            ->where('mentorname_id', $mentorid)
            ->first();

            // return [
            //     'mappedMentee' => $mappedMentee,
            //     'mentorid' => $mentorid
            // ];

    // Fetch completed quizzes count
    $completedQuizzes = DB::table('quiz_results')
    ->join('users', 'quiz_results.user_id', '=', 'users.id')
    ->join('mentees', 'users.email', '=', 'mentees.email')
    ->join('mappings', 'mentees.id', '=', 'mappings.menteename_id')
    ->join('modules', 'quiz_results.module_id', '=', 'modules.id')
    ->whereNull('modules.deleted_at') // Exclude deleted modules
    ->where('mappings.mentorname_id', $mentorid) // Filter by the mapped mentor
    ->where('mappings.menteename_id', $mappedMentee->menteename_id) // Ensure the mentee is mapped
    ->whereNotNull('quiz_results.score') // Completed quizzes
    ->count();

           

    //  return $completedQuizzes;

    // Fetch total quizzes count
    $testsCount = DB::table('tests')
        ->join('modules', 'tests.module_id', '=', 'modules.id')
        ->whereNull('modules.deleted_at') // Exclude deleted modules
        ->count();

    // Calculate pending quizzes
    $pendingQuizzes = $testsCount - $completedQuizzes;


    // Fetch completed quizzes per module
    $modulewiseComplete = DB::table('quiz_results')
    ->join('users', 'quiz_results.user_id', '=', 'users.id')
    ->join('mentees', 'users.email', '=', 'mentees.email')
    ->join('mappings', 'mentees.id', '=', 'mappings.menteename_id')
    ->join('modules', 'quiz_results.module_id', '=', 'modules.id')
    ->whereNull('modules.deleted_at') // Exclude deleted modules
    ->where('mappings.mentorname_id', $mentorid) // Filter by logged-in mentor
    ->where('mappings.menteename_id', $mappedMentee->menteename_id) // Filter by mapped mentee
    ->whereNotNull('quiz_results.score') // Only completed quizzes
    ->count();


    // Fetch total quizzes count per module
    $totalQuizzes = DB::table('questions')
    ->join('tests', 'questions.test_id', '=', 'tests.id')
    ->join('modules', 'tests.module_id', '=', 'modules.id')
    ->whereNull('modules.deleted_at') // Exclude deleted modules
    ->where('questions.mcq', 'yes') // Filter questions where mcq = 'yes'
    ->select('modules.name as module_name', DB::raw('COUNT(DISTINCT tests.id) as total_tests')) // Count distinct tests for each module
    ->groupBy('modules.name')
    ->pluck('total_tests', 'module_name'); // Associative array with module_name as key and total_tests as value

    // return $totalQuizzes;

    $modulewisePending = [];
    foreach ($totalQuizzes as $module => $total) {
        $completed = $modulewiseComplete[$module] ?? 0; // Default to 0 if no completed quizzes
        $modulewisePending[$module] = $total - $completed; // Calculate pending quizzes
    }

    // $modulewisePending = DB::table('tests')
    // ->join('modules', 'tests.module_id', '=', 'modules.id') // Join with modules to get module context
    // ->leftJoin('quiz_results', 'quiz_results.test_id', '=', 'tests.id') // Left join with quiz_results to find if there is a score
    // ->leftJoin('users', 'quiz_results.user_id', '=', 'users.id')
    // ->leftJoin('mentees', 'users.email', '=', 'mentees.email')
    // ->leftJoin('mappings', 'mentees.id', '=', 'mappings.menteename_id')
    // ->join('questions', 'questions.test_id', '=', 'tests.id') // Join with questions to access mcq
    // ->whereNull('modules.deleted_at') // Exclude deleted modules
    // ->where('questions.mcq', 'yes') // Filter questions where mcq = 'yes'
    // ->where('mappings.mentorname_id', $mentorid) // Filter by logged-in mentor
    // ->where('mappings.menteename_id', $mappedMentee->menteename_id) // Filter by mapped mentee
    // ->whereNull('quiz_results.score') // Only pending quizzes (score is null)
    // ->groupBy('modules.name')
    // ->select('modules.name as module_name', DB::raw('COUNT(DISTINCT tests.id) as pending_tests')) // Count pending quizzes
    // ->pluck('pending_tests', 'module_name'); // Associative array with module_name as key and pending_tests as value




    // return $modulewisePending;


    // Calculate module-wise pending quizzes
    // $modulewisePending = DB::table('tests')
    // ->join('modules', 'tests.module_id', '=', 'modules.id') // Join with modules
    // ->join('questions', 'tests.id', '=', 'questions.test_id') // Join with questions table
    // ->whereNull('modules.deleted_at') // Exclude deleted modules
    // ->where('questions.mcq', 'yes') // Include only questions where mcq = 1
    // ->distinct() // Ensure only unique module IDs are counted
    // ->count('tests.module_id'); // Count unique module IDs



        // return $modulewisePending;



   // Prepare data for the view
   $modules = [];
   foreach ($totalQuizzes as $module => $total) {
       $completed = $modulewiseComplete[$module] ?? 0; // Get completed quizzes or 0 if not found
       $pending = $total - $completed; // Calculate pending quizzes
   
       $modules[] = [
           'name' => $module,
           'completed' => $completed, // Completed quizzes count
           'pending' => $pending, // Pending quizzes count
           'score' => method_exists($this, 'calculateScore') ? $this->calculateScore($module) : 0, // Calculate score if method exists
           'chapters' => method_exists($this, 'getChaptersForModule') ? $this->getChaptersForModule($module) : [] // Get chapters if method exists
       ];
   }
   
  

    // Calculate overall completed and pending quizzes
    $overallCompletedQuizzes = $completedQuizzes;
    $overallPendingQuizzes = $totalQuizzes->sum() - $overallCompletedQuizzes;

    // return [
    //     'modules' => $modules,
    //     'overall_completed_quizzes' => $overallCompletedQuizzes,
    //     'overall_pending_quizzes' => $overallPendingQuizzes,
    //     'total_quizzes' => $totalQuizzes->sum(),
    //     'completedQuizzes' => $completedQuizzes,
    //     'pendingQuizzes' => $pendingQuizzes,

    // ];

    // Pass data to the view
    return view('mentor.quiz.quizdetails', [
        'completedQuizzes' => $completedQuizzes,
        'pendingQuizzes' => $pendingQuizzes,
        'modulewiseComplete' => $modulewiseComplete,
        'totalQuizzes' => $totalQuizzes,
        'modulewisePending' => $modulewisePending,
        'modules' => $modules, // Add this if needed in the view
        'overallCompletedQuizzes' => $overallCompletedQuizzes,
        'overallPendingQuizzes' => $overallPendingQuizzes,
    ]);
}


    private function calculateScore($module)
    {
        // Implement your logic to calculate the score for the given module
        return rand(50, 100); // Placeholder
    }

    private function getChaptersForModule($module)
    {
        // Implement your logic to retrieve chapters for the given module
        return [
            ['name' => 'Chapter 1', 'status' => 'Completed', 'score' => 80],
            ['name' => 'Chapter 2', 'status' => 'Pending', 'score' => 0],
            // Add more chapters as needed
        ];
    }

    // public function mentorquiz(Request $request, $chapter_id)
    // {
    //     // // Get the logged-in mentor details
    //         $mentor = auth()->user();

    //         $mentorid = DB::table('mentors')
    //         ->where('email', $mentor->email)->first();

    //         $mentorid = $mentorid->id;



    //     // // Get the mapped mentee using query builder
    //     $mappedMentee = DB::table('mappings')
    //         ->where('mentorname_id', $mentorid)
    //         ->first();

    //         // return $mappedMentee ;

            

    //     if (!$mappedMentee) {
    //         return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    //     }

    //     // // Get the mentee details using query builder
    //     $mentee = DB::table('mentees')
    //         ->where('id', $mappedMentee->menteename_id)
    //         ->first();


    //     if (!$mentee) {
    //         return redirect()->back()->with('error', 'Mentee not found.');
    //     }

    //     // // Get chapter details using query builder
    //     $chapter = DB::table('chapters')
    //         ->where('id', $chapter_id)
    //         ->first();

            
    //         $moduleid = $chapter->module_id;

    //         // return $moduleid;

    //     if (!$chapter) {
    //         return redirect()->back()->with('error', 'Chapter not found.');
    //     }

    //     // // Fetch discussion questions for this chapter (assuming `mcq` is set to 0 for discussion points)
    //     // $discussionQuestions = DB::table('questions')
    //     //     ->where('chapter_id', $chapter_id)
    //     //     ->where('mcq', 0)
    //     //     ->get();

    //     // // Fetch quiz questions for this chapter (assuming `mcq` is set to 1 for quiz questions)
    //     // // $quizQuestions = DB::table('questions')
    //     // //     ->where('chapter_id', $chapter_id)
    //     // //     ->where('mcq', 1)
    //     // //     ->get();


    //     $user = DB::table('users')
    //     ->where('email',$mentee->email )
    //     ->first();

    //     $user =$user->id;


    //         $maxResult = DB::table('quiz_results')
    //             ->where('user_id', $user)
    //             ->where('module_id', $moduleid)
    //             ->orderBy('score', 'desc')
    //             ->first();

    //             // if (!$maxResult) {
    //             //     $maxResult = (object) ['score' => null]; // Default object with null score
    //             // }


    //      // Return the view with the necessary data
    //     return view('mentor.modules.quiz', compact( 'mentor','maxResult','user','mappedMentee','chapter','mentee'));
    // }


public function showmentorquiz(Request $request, $chapter_id)
{
    // Get the logged-in mentor
    $mentor = auth()->user();

    $chapterId = $chapter_id;

    // Fetch mentor ID based on the logged-in mentor's email
    $mentorDetails = DB::table('mentors')
        ->where('email', $mentor->email)
        ->first();

    if (!$mentorDetails) {
        return redirect()->back()->with('error', 'Mentor details not found.');
    }

    $mentorId = $mentorDetails->id;

    // Get the mapped mentee for the mentor
    $mappedMentee = DB::table('mappings')
        ->where('mentorname_id', $mentorId)
        ->whereNull('deleted_at') // Ensure mapping is not deleted
        ->first();

    if (!$mappedMentee) {
        return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    }

    // Get mentee details
    $mentee = DB::table('mentees')
        ->where('id', $mappedMentee->menteename_id)
        ->first();

    if (!$mentee) {
        return redirect()->back()->with('error', 'Mentee details not found.');
    }

    // Get the chapter details
    $chapter = DB::table('chapters')
        ->where('id', $chapter_id)
        ->first();

    if (!$chapter) {
        return redirect()->back()->with('error', 'Chapter not found.');
    }

    // Retrieve the module ID from the chapter
    $moduleId = $chapter->module_id;

    // Get the user ID linked to the mentee email
    $menteeUser = DB::table('users')
        ->where('email', $mentee->email)
        ->first();

    if (!$menteeUser) {
        return redirect()->back()->with('error', 'Mentee user account not found.');
    }

    $userId = $menteeUser->id;

    // Fetch the maximum quiz result for the mentee in the module
    // $maxResult = DB::table('quiz_results')
    //     ->where('user_id', $userId)
    //     ->where('module_id', $moduleId)
    //     ->orderBy('score', 'desc')
    //     ->first();

    $maxResult = DB::table('quiz_results')
    ->where('user_id', $userId)
    ->where('module_id', $moduleId)
    ->orderBy('score', 'desc')
    ->select('score', 'attempts') // Specify the columns you want to retrieve
    ->first();


    // Fetch discussion answers for the selected chapter and mapped mentee
    $discussionAnswers = DB::table('discussion_answers')
    ->join('questions', 'discussion_answers.question_id', '=', 'questions.id')
    ->join('tests', 'questions.test_id', '=', 'tests.id') // Assuming questions link to tests
    ->where('tests.chapter_id', $chapterId) // Use tests to filter by chapter
    ->where('discussion_answers.menteename_id', $mappedMentee->menteename_id)
    ->whereNull('discussion_answers.deleted_at') // Exclude soft-deleted answers
    ->select('discussion_answers.*', 'questions.question_text')
    ->get();


    // If no discussion answers exist, initialize an empty collection
    if ($discussionAnswers->isEmpty()) {
        $discussionAnswers = collect();
    }

    // Return the view with the necessary data
    return view('mentor.modules.quiz', compact(
        'mentor',
        'maxResult',
        'userId',
        'mappedMentee',
        'chapter',
        'mentee',
        'discussionAnswers'
    ));
}

    public function storeMentorReply(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'mentorsreply' => 'required|string|max:65535',
        ]);

        // Update the mentor's reply using Query Builder
        $updated = DB::table('discussion_answers')
            ->where('id', $id)
            ->update(['mentorsreply' => $request->input('mentorsreply'), 'updated_at' => now()]);

        // Check if the update was successful
        if ($updated) {
            return redirect()->back()->with('success', 'Reply submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit the reply. Please try again.');
        }
    }



    // public function mentorquiz(Request $request)
    // {
    //     $chapterId = $request->chapter_id;
    //     $menteeId = $request->mentee_id; // Assuming mentee_id is passed in the request

    //     // Retrieve the logged-in mentor details (Assuming you are using Auth)
    //     $mentorEmail = Auth::user()->email;

    //     // Fetch the mentor's details
    //     $mentor = DB::table('mentors')
    //         ->where('email', $mentorEmail)
    //         ->first();
        
    //     // Retrieve the mapped mentee information for the logged-in mentor
    //     $mappedMentee = DB::table('mappings')
    //         ->where('mentorname_id', $mentor->id)
    //         ->whereNull('deleted_at')
    //         ->first();
        
    //     // Fetch mentee details using menteename_id from the mapped table if a mapping exists
    //     $menteeDetails = null;
    //     if ($mappedMentee) {
    //         $menteeDetails = DB::table('mentees')
    //             ->where('id', $mappedMentee->menteename_id)
    //             ->first();
    //     }

    //     // return $menteeDetails;
       

    //     // Retrieve chapter details using Query Builder
    //     $chapter = DB::table('chapters')->where('id', $chapterId)->first();

    //     // Retrieve quiz tests for the chapter using Query Builder
    //     $tests = DB::table('tests')
    //         ->where('chapter_id', $chapterId)
    //         ->get()
    //         ->map(function ($test) {
    //             $test->questions = DB::table('questions')
    //                 ->where('test_id', $test->id)
    //                 ->get()
    //                 ->map(function ($question) {
    //                     $question->options = DB::table('options')
    //                         ->where('question_id', $question->id)
    //                         ->get();
    //                     return $question;
    //                 });
    //             return $test;
    //         });

    //     // Retrieve mentee details using Query Builder
    //     // $mentee = DB::table('mentees')->where('id', $menteeId)->first();


       

        
    //     // Fetch quiz results for the mentee from the quiz_results table
    //     $quizResult = DB::table('quiz_results')
    //         ->where('user_id', $menteeId)
    //         ->where('module_id', $chapter->module_id)
    //         ->first();

    //     $quizDetails = [
    //         'score' => $quizResult ? $quizResult->score : 0, // Score from quiz_results table
    //         'total' => $quizResult ? $quizResult->total_points : 0, // Total points from quiz_results table
    //         'attempts' => $quizResult ? $quizResult->attempts : 0, // Attempts from quiz_results table
    //         // 'chapters_completed' => DB::table('chapter_completions')
    //         //     ->where('mentee_id', $menteeId)
    //         //     ->count(), // Example to fetch completed chapters
    //         // 'review' => DB::table('quiz_submissions')
    //         //     ->where('mentee_id', $menteeId)
    //         //     ->where('chapter_id', $chapterId)
    //         //     ->get()
    //         //     ->map(function ($submission) {
    //         //         $question = DB::table('questions')->where('id', $submission->question_id)->first();
    //         //         $correctAnswer = DB::table('options')
    //         //             ->where('question_id', $question->id)
    //         //             ->where('is_correct', true)
    //         //             ->first();
    //         //         $yourAnswer = DB::table('options')->where('id', $submission->option_id)->first();
    //         //         return [
    //         //             'question' => $question->question_text,
    //         //             'correct_answer' => $correctAnswer ? $correctAnswer->option_text : 'N/A',
    //         //             'your_answer' => $yourAnswer ? $yourAnswer->option_text : 'N/A',
    //         //             'is_correct' => $yourAnswer && $yourAnswer->id == $correctAnswer->id,
    //         //         ];
    //         //     }),
    //     ];

    //     // Pass the data to the view
    //     return view('mentor.modules.quiz', compact('chapter', 'tests','menteeDetails' , 'quizDetails'));
    // }
}
