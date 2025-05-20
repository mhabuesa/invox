<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home()
    {
        return redirect('/dashboard');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
}
