<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon; 


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function menteeshow()
    {
        return view('auth.menteereg');  
    }

    public function registerMentee(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:25|regex:/^[A-Za-z\s\.]+$/',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|regex:/^[6789][0-9]{9}$/',
            'date' => 'required|date',
            'skills' => 'required|string',
            'interested_skills' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        try {
            // Start the database transaction
            DB::beginTransaction();

            // Insert user data into the users table
            DB::table('users')->insert([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert mentee details into the mentees table
            DB::table('mentees')->insert([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'dob' => $request->input('date'),
                'skilss' => $request->input('skilss'),
                'interestedskills' => $request->input('interested_skills'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->back()->with('success', 'Mentee registered successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            // Return back with error message
            return redirect()->back()->with('error', 'Registration failed. Error: ' . $e->getMessage());
        }
    }

    public function mentorshow()
    {
        return View('auth.mentorreg');
    }

    public function mentorreg(Request $request)
    {
        // Collect form data directly from the request
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $companyname = $request->input('companyname');
        $skills = $request->input('skills');
        $password = $request->input('password');

        // Prepare input data for debugging or checking
        // $inputData = [
        //     'name' => $name,
        //     'email' => $email,
        //     'mobile' => $mobile,
        //     'companyname' => $companyname,
        //     'skills' => $skills,
        //     'password' => $password,
        // ];

        // You can use dd() or var_dump to inspect the input data
        // return $inputData; // Debugging step: Remove this after confirming the data

        // Start the database transaction
        try {
            // Insert user into users table
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                // 'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert mentor details into mentors table
            DB::table('mentors')->insert([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'companyname' => $companyname,
                'skills' => $skills,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Commit the transaction after successful insert
            DB::commit();

            return ('registered successfully');
            // Return success message or redirect
            // return redirect()->back()->with('success', 'Mentor registered successfully!');
        } catch (\Exception $e) {

            return 'Registration failed. Error: ' . $e->getMessage();

            // Rollback transaction if there's an error
            DB::rollBack();

            // Log the exception or return an error message
            // Log::error($e->getMessage()); // Optional: Log the error message
            // return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}




