<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $appID = config('services.kanye_api.id');
        return view('welcome', compact('appID'));
    }
}
