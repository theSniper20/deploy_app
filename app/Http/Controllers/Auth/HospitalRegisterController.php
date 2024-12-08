<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Hash;

class HospitalRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.hospital-register');
    }

    public function register(Request $request)
    {
        // Validate the input, including the new fields
        $request->validate([
            'name' => 'required|string|max:255|unique:hospitals',
            'email' => 'required|email|unique:hospitals',
            'password' => 'required|string|min:8|confirmed',
            'description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
        ]);
        // Create a new hospital record
        Hospital::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password , //Hash::make($request->password),
            'description' => $request->description,
            'address' => $request->address,
        ]);
        return redirect()->route('hospital.login')->with('success', 'Registration successful! Please log in.');
    }
}

