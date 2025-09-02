<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'supplier'])->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('items.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|integer|exists:categories,id',
            'supplier_id'    => 'required|integer|exists:suppliers,id',
            'description'    => 'nullable|string',
            'stock'          => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')
                         ->with('success', 'Item has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'supplier', 'transactions']);
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('items.edit', compact('item', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|integer|exists:categories,id',
            'supplier_id'    => 'required|integer|exists:suppliers,id',
            'description'    => 'nullable|string',
            'stock'          => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
                         ->with('success', 'Item has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
                         ->with('success', 'Item has been deleted successfully.');
    }
}