<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{

    /**
     * Show the terms of service.
     *
     * @return \Illuminate\Http\Response
     */
    public function tos()
    {
        return view('tos');
    }
}
