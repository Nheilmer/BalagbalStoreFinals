<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return view('adminDashboard', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully!');
    }
}
