<?php
namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Mentor;
use App\Mentee;
use App\Moduleresourcebank;

use Illuminate\Support\Facades\Redirect;


class KnowledgebankController extends Controller
{
	public function index()
	{
		$resources=Moduleresourcebank::all();
		
		return view('mentee.knowledgebank.index',compact('resources'));
	}
}
