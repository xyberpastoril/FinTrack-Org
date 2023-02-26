<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fee\StoreFeeRequest;
use App\Http\Requests\Fee\UpdateFeeRequest;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::where('semester_id', session('semester')->id)->get();

        return view('fees.index', compact('fees'));
    }

    public function store(StoreFeeRequest $request)
    {
        $validated = $request->validated();

        $fee = Fee::create([
            'semester_id' => session('semester')->id,
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ]);

        return redirect()->route('fees.index')->with('success', 'Fee created successfully.');
    }

    public function edit(Fee $fee)
    {
        return view('fees.edit', compact('fee'));
    }

    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $validated = $request->validated();

        $fee->update([
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ]);

        return redirect()->route('fees.index')->with('success', 'Fee updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('fees.index')->with('success', 'Fee deleted successfully.');
    }
}
