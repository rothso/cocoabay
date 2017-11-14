<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicensePlateRequest;
use App\LicensePlate;

class LicensePlateController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response('Submit');
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

        $request->user()->plates()->save($plate);
        return response('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param LicensePlate $plate
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(LicensePlate $plate)
    {
        $this->authorize('view', $plate);

        return response($plate->tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  LicensePlate $plate
     */
    public function edit(LicensePlate $plate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LicensePlateRequest $request
     * @param LicensePlate $plate
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(LicensePlateRequest $request, LicensePlate $plate)
    {
        $this->authorize('update', $plate);

        $plate->update($request->all());
        return response('Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LicensePlate $plate
     */
    public function destroy(LicensePlate $plate)
    {
        //
    }
}
