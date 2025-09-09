<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the login form for regular users
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the login form for owners
     */
    public function showOwnerLoginForm()
    {
        return view('auth.owner');
    }
}
