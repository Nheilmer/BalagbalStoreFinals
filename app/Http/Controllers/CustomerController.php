<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('orders')->get();
        $products = \App\Models\Product::with('category')->get();
        $categories = \App\Models\Category::all();

        return view('adminDashboard', compact('customers', 'products', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:customers|max:100',
            'email' => 'required|email|unique:customers|max:100',
            'password_hash' => 'required|string|min:6',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $orders = $customer->orders()->with('orderDetails.product')->get();
        return view('customers.show', compact('customer', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $user = $customer->user;

        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone,
        ]);

        $user->update([
            'username' => $request->username,
            'email'    => $request->email,
        ]);

        return redirect()->back()->with('success', 'Customer updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function history($id)
    {
        $customer = Customer::with([
            'orders.orderDetails.product'
        ])->findOrFail($id);

        return response()->json([
            'orders' => $customer->orders
        ]);
    }
}
