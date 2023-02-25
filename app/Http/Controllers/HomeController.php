<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $semesters = Semester::all();

        return view('home', [
            'semesters' => $semesters,
        ]);
    }

    public function setSemester(Request $request)
    {
        $semester = Semester::find($request->semester_id);

        if (!$semester) {
            return redirect()->route('home')->with('error', 'Semester not found');
        }

        session(['semester' => $semester]);

        $successMessage = "Semester successfully set to ";

        if($semester->semester == 1) {
            $successMessage .= "First Semester ";
        } else {
            $successMessage .= "Second Semester ";
        }

        $successMessage .= $semester->year . " - " . ($semester->year + 1);

        return redirect()->route('home')->with('success', $successMessage);
    }
}
