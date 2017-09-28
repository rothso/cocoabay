<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DmvController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // TODO show splash when unauthenticated
        $this->middleware('auth');
    }

    /**
     * Show the DMV's offerings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $license = Auth::user()->license()->first();
        return view('dmv.index')->with('license', $license);
    }
}
