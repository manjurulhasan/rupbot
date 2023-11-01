<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        if (auth::check()) {
            return redirect('/dashboard');
        } else {
            return redirect('/login');
        }
    }
}
