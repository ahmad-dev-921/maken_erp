<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
   public function login(Request $request)
{
    $validation = Validator::make($request->all(), [
        'email'    => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validation->fails()) {
        return back()->withErrors($validation)->withInput();
    }

    $user = User::where('email', $request->email)
                ->orWhere('name', $request->email)
                ->first();

    if (! $user) {
        return back()->withErrors(['email' => 'User not found'])->withInput();
    }

    if (! Hash::check($request->password, $user->password)) {
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Start the session
    Auth::login($user);
    $request->session()->regenerate();

    return redirect('/dashboard');
}
     public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
