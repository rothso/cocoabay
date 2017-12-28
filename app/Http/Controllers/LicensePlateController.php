<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicensePlateRequest;
use App\LicensePlate;
use App\LicensePlateStyle;
use Illuminate\Http\Request;

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
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $plates = $request->user()->plates;
        return view('dmv.plate.index', compact('plates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $styles = LicensePlateStyle::select(['id', 'name', 'image'])->get()->keyBy('id')->map(function ($style) {
            return e($style->name) . ' <img src="' . asset($style->image) . '">';
        });
        return view('dmv.plate.create', compact('styles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LicensePlateRequest $request
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
