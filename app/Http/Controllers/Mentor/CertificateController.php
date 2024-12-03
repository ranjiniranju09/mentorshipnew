<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function generateCertificate()
    {
        // Get the logged-in mentor
        $mentor = auth()->user();
        
        // Fetch the mapped mentee details for the logged-in mentor
        $mapping = DB::table('mappings')
                     ->where('mentor_email', $mentor->email)
                     ->first();
        
        if (!$mapping) {
            return redirect()->back()->with('error', 'No mapped mentee found for this mentor.');
        }

        // Fetch the mentee details
        $mentee = DB::table('mentees')
                    ->where('id', $mapping->mentee_id)
                    ->first();

        if (!$mentee) {
            return redirect()->back()->with('error', 'Mentee details not found.');
        }

        // Fetch the course details (You may have a course table or hardcode it)
        $course = DB::table('courses')
                    ->where('mentee_id', $mentee->id)
                    ->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course details not found.');
        }

        // Generate certificate data
        $certificateData = [
            'name' => $mentee->name,
            'course' => $course->title,
            'date' => now()->format('F d, Y') // Format as desired
        ];

        // Pass the data to the view
        return view('certificate', $certificateData);
    }
}
