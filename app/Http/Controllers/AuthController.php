<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'message' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        // Get the default "Customer" role
        $role = \App\Models\Role::firstOrCreate(
            ['name' => 'Customer'],
            ['description' => 'Default role for new users']
        );

        $user = \App\Models\User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'name' => $data['firstname'] . ' ' . $data['lastname'],
            'password' => bcrypt($data['password']),
            'role_id' => $role->id, // now guaranteed to exist
        ]);


        // Create linked Customer profile
        $user->customer()->create([
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'address' => $data['address'] ?? null,
            'phone_number' => $data['phone'] ?? null,
        ]);

        // Log in the new user
        Auth::login($user);

        // Redirect to dashboard
        return redirect('/dashboard');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|min:6'
        ]);

        // Update user table fields
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->name = $data['firstname'] . ' ' . $data['lastname'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        // Update customer table fields
        $customer->first_name = $data['firstname'];
        $customer->last_name = $data['lastname'];
        $customer->date_of_birth = $data['birthdate'] ?? null;
        $customer->phone_number = $data['phone'] ?? null;
        $customer->address = $data['address'] ?? null;

        $customer->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
