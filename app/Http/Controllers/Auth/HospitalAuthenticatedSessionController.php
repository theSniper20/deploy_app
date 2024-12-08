<?php

namespace App\Http\Controllers\Auth;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HospitalAuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.hospital-login');  // Assuming you have a hospital login view
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');  // Redirect to hospital dashboard or home page
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
