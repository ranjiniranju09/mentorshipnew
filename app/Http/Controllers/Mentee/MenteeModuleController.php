<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\QuizSubmittedNotification;
use App\Mail\QuizSubmittedToMentee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Mentor;
use App\Mentee;
use App\AssignTask;
use Illuminate\Support\Facades\Redirect;
use App\Module;
use App\Chapter;
use App\Subchapter;
use App\Models\DiscussionAnswer;
use App\Test;
use App\Question;
use App\QuestionOption;
use App\QuizResult;
use App\Moduleresourcebank;
use App\Ticketcategory;
use App\TicketDescription;
use PhpParser\Node\Stmt\Return_;

class MenteeModuleController extends Controller
{
	public function index()
    {
    	$modules=Module::all();
    	return view('mentee.modules.index',compact('modules'));
    }

    public function showchapters(Request $request)
    {
        // Retrieve the module ID from the request
        $module_id = $request->query('module_id');


        
        // Check if the module ID exists and retrieve the module
        $module = Module::find($module_id);

        if (!$module) {
            // If the module is not found, redirect back with an error message
            return redirect()->back()->with('error', 'Module not found.');
        }

        // Fetch all chapters related to the module
        $chapters = Chapter::where('module_id', $module_id)->get();

        // Attach MCQ check to each chapter
        $chapters->map(function ($chapter) {
            // Check if the chapter has associated MCQs through the tests
            $chapter->has_mcq = Question::whereIn('test_id', function ($query) use ($chapter) {
                $query->select('id')
                    ->from('tests')
                    ->where('chapter_id', $chapter->id);
            })
            ->where('mcq', 'yes')
            ->exists();
            return $chapter;

        
        });

        $loggedInEmail = Auth::user()->email;

        $mentee = Mentee::where('email', $loggedInEmail)->first();

        // return [
        //     'mentee' => $mentee,
        //     '$loggedEmail' => $loggedInEmail,
        // ];

        $user = DB::table('users')
        ->where('email',$mentee->email )
        ->first();

        $user =$user->id;

        $maxResult = DB::table('quiz_results')
                ->where('user_id', $user)
                ->where('module_id', $module_id)
                ->orderBy('score', 'desc')
                ->first();

                if (!$maxResult) {
                    $maxResult = (object) ['score' => null]; // Default object with null score
                }

                // return $maxResult->score;



        // If no chapters found, return a message to the view
        // if ($chapters->isEmpty()) {
        //     return view('mentee.modules.chapters', compact('module','loggedEmail','$mentee','user','maxResult'))
        //         ->with('message', 'No chapters available for this module.');
        // }

        // Pass the module and chapters to the view
        return view('mentee.modules.chapters', compact('chapters', 'module','loggedInEmail','mentee','user','maxResult'));
    }

    

    // public function showChapters($moduleId)
    // {
    //     $module = Module::with('chapters.tests.questions')->findOrFail($moduleId);
    //     $chapters = $module->chapters; // Ensure this fetches related chapters correctly.

    //     return view('mentee.modules.chapters', compact('module', 'chapters'));
    // }

    public function subchaptercontent(Request $request)
    {

    	$chapter_id = $request->query('chapter_id');


    	$current_subchapter_id = $request->query('chapter_id');
    	// Get all subchapters of the chapter
    	$subchapters = Subchapter::where('chapter_id', $chapter_id)->get();
    	// Get the current subchapter
    	$current_subchapter = Chapter::find($current_subchapter_id);
    	$previousSubchapter='';
    	$nextSubchapter='';
    	/*
    	// Find previous and next subchapters
    	$previousSubchapter = Chapter::where('chapter_id', $chapter_id)
                                    ->where('id', '<', $current_subchapter_id)
                                    ->orderBy('id', 'desc')
                                    ->first();
         return     $previousSubchapter;                      
    	$nextSubchapter = Chapter::where('chapter_id', $chapter_id)
                                ->where('id', '>', $current_subchapter_id)
                                ->orderBy('id')
                                ->first();


		*/

        $moduleresources = Moduleresourcebank::where('chapterid_id', $chapter_id)->get();
                        
        return view('mentee.modules.subchapters', compact('subchapters', 'current_subchapter', 'previousSubchapter', 'nextSubchapter','moduleresources'));

    }

    

    // public function viewquiz(Request $request)
    // {
    //     $chapterId = $request->chapter_id;
    //     $chapter = Chapter::with(['tests.questions.options', 'discussionQuestions'])->findOrFail($chapterId);

    //     $tests = $chapter->tests->filter(function ($test) {
    //         return $test->questions->some(function ($question) {
    //             return $question->options->isNotEmpty();
    //         });
    //     });

    //     return view('mentee.modules.viewquiz', compact('chapter', 'tests'));
    // }
    public function getDiscussionQuestions($chapterId)
    {
        // Fetch the chapter details with an alias for 'chaptername' as 'title' for clarity
        $chapter = DB::table('chapters')
            ->select('id', 'chaptername as title', 'module_id') // Map 'chaptername' to 'title'
            ->where('id', $chapterId)
            ->first();

            $chapter = Chapter::findOrFail($chapterId);
            $module = $chapter->module;  // Assuming the Chapter model has a relationship to Module
        
        // If the chapter does not exist, return a 404 error
        if (!$chapter) {
            abort(404, 'Chapter not found');
        }

        // Fetch discussion questions where mcq = 'no' for the chapter
        $discussionQuestions = DB::table('questions')
            ->join('tests', 'questions.test_id', '=', 'tests.id') // Join with tests table to filter by chapter_id
            ->where('tests.chapter_id', $chapterId)
            ->where('questions.mcq', 'no') // Only non-MCQ questions
            ->select('questions.id', 'questions.question_text') // Select relevant fields
            ->orderBy('questions.id') // Order questions by ID (optional)
            ->get();

        // Pass chapter and discussion questions to the view
        return view('mentee.modules.viewdiscussion', [
            'chapter' => $chapter, // Chapter details
            'module' => $module,
            'discussionQuestions' => $discussionQuestions // Non-MCQ discussion questions
        ]);
    }

    // public function discussionanswerstore(Request $request)
    // {
    //     // Validate the input
    //     $validatedData = $request->validate([
    //         'answers.*.discussion_answer' => 'required|string',
    //         'answers.*.question_id' => 'required|exists:questions,id',
    //     ]);

    //     // Save each discussion answer
    //     foreach ($validatedData['answers'] as $answer) {
    //         DiscussionAnswer::create([
    //             'discussion_answer' => $answer['discussion_answer'],
    //             'question_id' => $answer['question_id'],
    //         ]);
    //     }

    //     return view('mentee.modules.chapters');        
    //     // return redirect()->back()->with('success', 'Discussion answers submitted successfully!');
    // }



    public function discussionanswerstore(Request $request)
    {
        $modules = Module::all();  // Get all modules
    
        $module_id = $request->input('module_id');
        $chapter_id = $request->query('chapter_id');
    
        // Validate the input
        $validatedData = $request->validate([
            'answers.*.discussion_answer' => 'required|string',
            'answers.*.question_id' => 'required|exists:questions,id',
        ]);
    
        // Fetch the logged-in user's email
        $loggedInEmail = Auth::user()->email;
    
        // Retrieve the menteename_id from the mentee table
        $mentee = DB::table('mentees')->where('email', $loggedInEmail)->first();
    
        if (!$mentee) {
            return redirect()->back()->withErrors(['error' => 'Mentee not found. Please ensure your account is correctly mapped.']);
        }
    
        $menteenameId = $mentee->id;
    
        // Prepare the data for bulk insertion
        $insertData = [];
        foreach ($validatedData['answers'] as $answer) {
            // You can prepare the insert data here as needed
            $insertData[] = [
                'discussion_answer' => $answer['discussion_answer'],
                'question_id' => $answer['question_id'],
                'menteename_id' => $menteenameId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        // Insert data into the database
        if (!empty($insertData)) {
            DB::table('discussion_answers')->insert($insertData);
        }
    
        // Fetch the module based on module_id
        $module = Module::find($module_id);
    
        if (!$module) {
            return redirect()->back()->withErrors(['error' => 'Module not found.']);
        }
    
        // Fetch the chapters related to the module
        $chapters = Chapter::where('module_id', $module_id)->get();
    
        // Pass both modules, module, and chapters to the view
        return view('mentee.modules.chapters', compact('modules', 'module', 'chapters'))
        ->with('success', 'Your answers have been submitted successfully!');
    
    }
    

    public function viewquiz(Request $request)
    {
    	$chapterId=$request->chapter_id;
    	$chapter = Chapter::findOrFail($chapterId);

    	$tests = Test::where('chapter_id', $chapterId)->with('questions.options')->get();
    	//return $tests;
    	return view('mentee.modules.viewquiz',compact('chapter','tests'));
    }

    public function submitQuiz(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'module_id' => 'required|exists:modules,id',
        ]);
    
        $score = 0;
        $totalPoints = 0;
    
        // Retrieve chapter_id and module_id
        $chapterId = $request->input('chapter_id');
        $moduleId = $request->input('module_id');
    
        // Calculate score
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = str_replace('question_', '', $key);
                $selectedOptionId = $value;
    
                $question = Question::find($questionId);
    
                if (!$question) {
                    return redirect()->back()->with('error', 'Question not found.');
                }
    
                $correctOption = $question->options()->where('is_correct', true)->first();
    
                if (!$correctOption) {
                    return redirect()->back()->with('error', 'No correct option found for question ID: ' . $questionId);
                }
    
                $totalPoints += $question->points;
    
                if ($correctOption->id == $selectedOptionId) {
                    $score += $question->points;
                }
            }
        }
    
        // Save quiz result
        $quizResult = QuizResult::create([
            'user_id' => auth()->user()->id,
            'module_id' => $moduleId,
            'score' => $score,
            'attempts' => 1,
            'total_points' => $totalPoints,
        ]);
    
        // Fetch mapping data
        $result = DB::table('mappings')
            ->join('mentees', 'mappings.menteename_id', '=', 'mentees.id')
            ->join('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
            ->select(
                'mentees.email as mentee_email',
                'mentees.id as mentee_id',
                'mentees.name as mentee_name',
                'mentors.email as mentor_email',
                'mentors.name as mentor_name',
                'mappings.mentorname_id'
            )
            ->where('mentees.email', auth()->user()->email)
            ->first();
    
        if (!$result) {
            return redirect()->back()->with('error', 'No mentor found for this mentee!');
        }
    
        $mentor = \App\Mentor::find($result->mentorname_id);
    
        // Send notifications
        Mail::to($result->mentor_email)->send(new QuizSubmittedNotification($result, $mentor, $quizResult));
        Mail::to($result->mentee_email)->send(new QuizSubmittedToMentee($result, $score));
    
        // Redirect back with success message
        return redirect()
            ->route('viewquiz', ['chapter_id' => $chapterId])
            ->with('success', 'Quiz submitted successfully! Your score is: ' . $score);
    }
    



    


    public function menteetickets()
    {
        //$tickets=TicketDescription::all();
        $tickets = TicketDescription::where('user_id', Auth::id())->get();
        //return $tickets;
        return view('mentee.tickets.index' ,compact('tickets'));
    }
    public function ticketscreate()
    {
        //$ticket_categories=Ticketcategory::all();
        $ticket_categories = Ticketcategory::pluck('category_description', 'id');
        return view('mentee.tickets.create',compact('ticket_categories'));
    }
    public function ticketstore(Request $request)
    {
        //return $request;
        $ticketDescription = new TicketDescription();
        $ticketDescription->ticket_category_id = $request->ticket_category_id;
        $ticketDescription->ticket_description = $request->ticket_description;
        $ticketDescription->user_id = $request->user_id;
        $ticketDescription->save();
        //return redirect()->back()->with('success', 'Ticket created successfully!');
        return redirect()->route('mentee.tickets')->with('success', 'Ticket created successfully!');


    }
    
}