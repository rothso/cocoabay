<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicensePlateRequest;
use App\LicensePlate;

class LicensePlateController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LicensePlateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LicensePlateRequest $request)
    {
        $plate = new LicensePlate($request->all());
        $plate->tag = uniqid();
        $plate->image = uniqid();

        $request->user()->plates()->save($plate);
        return response('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  LicensePlate $licensePlate
     * @return \Illuminate\Http\Response
     */
    public function show(LicensePlate $licensePlate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  LicensePlate $licensePlate
     * @return \Illuminate\Http\Response
     */
    public function edit(LicensePlate $licensePlate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LicensePlateRequest $request
     * @param LicensePlate $plate
     * @return \Illuminate\Http\Response
     */
    public function update(LicensePlateRequest $request, LicensePlate $plate)
    {
        $plate->update($request->all());
        return response('Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LicensePlate $licensePlate
     * @return \Illuminate\Http\Response
     */
    public function destroy(LicensePlate $licensePlate)
    {
        //
    }
}
