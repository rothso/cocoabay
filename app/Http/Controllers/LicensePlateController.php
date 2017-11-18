<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicensePlateRequest;
use App\LicensePlate;
use App\User;
use Illuminate\Support\Facades\Request;

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
        return view('dmv.plate.create');
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

        return redirect()
            ->route('plate.show', $plate)
            ->with('success', 'Vehicle registered successfully!');
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

        return view('dmv.plate.show', compact('plate'));
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
