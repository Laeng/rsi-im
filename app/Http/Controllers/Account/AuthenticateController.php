<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticateController
{
    public function login(Request $request)
    {
        return Inertia::render('login', []);
    }
}
