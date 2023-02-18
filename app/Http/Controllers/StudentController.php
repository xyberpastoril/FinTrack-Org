<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Student\ImportStudentsRequest;
use App\Models\Student;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    public function import(ImportStudentsRequest $request)
    {
        $validated = $request->validated();

        Excel::import(new StudentsImport, $validated['file']);

        return redirect()->route('students.index')->with('success', 'Students imported successfully.');
    }
}
