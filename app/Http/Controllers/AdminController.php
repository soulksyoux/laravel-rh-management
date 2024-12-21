<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function home() {
        if (!Auth::user()->can('admin')) {
            abort(403, 'You are not authorized to access this page');
        }

        return view('home');
    }
}
