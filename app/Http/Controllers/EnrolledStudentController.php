<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Student\ImportStudentsRequest;
use App\Models\Student;
use App\Imports\StudentsImport;
use App\Models\EnrolledStudent;
use Maatwebsite\Excel\Facades\Excel;

class EnrolledStudentController extends Controller
{
    public function index()
    {
        $students = EnrolledStudent::where('semester_id', session('semester')->id)->get();
        return view('students.enrolled.index', compact('students'));
    }

    public function import(ImportStudentsRequest $request)
    {
        $validated = $request->validated();

        Excel::import(new StudentsImport, $validated['file']);

        return redirect()->route('students.enrolled.index')->with('success', 'Students imported successfully.');
    }
}
