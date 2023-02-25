<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('semester_id', session('semester')->id)->get();

        return view('items.index', compact('items'));
    }

    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();

        $item = Item::create([
            'semester_id' => session('semester')->id,
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        $item->update([
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function searchAjax($query = null)
    {
        $items = Item::select(
                'items.id',
                'items.name',
                'items.amount',
            )
            ->where('semester_id', session('semester')->id)
            ->whereEncrypted('name', 'like', "%$query%")
            ->get();

        return response()->json($items);
    }
}
