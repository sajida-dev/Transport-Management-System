<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //

    public function loginDisplay(Request $requst)

    {
        return view('admin.login');
    }


    public function loginSubmit(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('/admin/dashboard');
        }

        // Authentication failed...
        return ['email' => 'Invalid credentials'];
    }

    public function logout()
    {

        Auth::logout();

        return redirect('/login');
    }
}
