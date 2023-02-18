<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DegreeProgram;
use App\Imports\DegreeProgramImport;
use App\Http\Requests\DegreeProgram\ImportDegreeProgramsRequest;
use Maatwebsite\Excel\Facades\Excel;

class DegreeProgramController extends Controller
{
    public function index()
    {
        $degreePrograms = DegreeProgram::all();
        return view('degree-programs.index', compact('degreePrograms'));
    }

    public function import(ImportDegreeProgramsRequest $request)
    {
        $validated = $request->validated();

        Excel::import(new DegreeProgramImport, $validated['file']);

        return redirect()->route('degreePrograms.index')->with('success', 'Degree Programs imported successfully.');
    }
}
