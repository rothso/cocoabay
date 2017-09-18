<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLicense;
use Auth;
use App\DriversLicense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO bind model to support editing

        // Enums for the dropdown menus
        $eyeColors = DB::table('eye_colors')->pluck('name', 'id');
        $hairColors = DB::table('hair_colors')->pluck('name', 'id');

        return view('dmv.license.create', compact('eyeColors', 'hairColors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLicense $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLicense $request)
    {
        if (Auth::user()->license()->exists())
            return $this->update($request);

        $license = new DriversLicense;
        $license->fill($this->prepareParams($request));
        $license->setAttribute('expires_at', Carbon::now()->addDays(90));
        $license->user()->associate(Auth::user());
        $license->save();

        $request->session()->flash('alert-success', 'License successfully created!');
        return redirect('dmv');
    }

    /**
     * Update the user's license in storage.
     *
     * @param StoreLicense $request
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLicense $request)
    {
        Auth::user()->license()->firstOrFail()->update($this->prepareParams($request));

        $request->session()->flash('alert-success', 'License details successfully updated!');
        return redirect('dmv');
    }

    /**
     * Prepare the request inputs for storage.
     *
     * @param StoreLicense $request
     * @return array
     */
    private function prepareParams(StoreLicense $request)
    {
        $request->merge(['height_in' => $request->height_in + 12 * $request->height_ft]);
        return $request->except('height_ft');
    }
}
