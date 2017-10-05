<?php

namespace App\Http\Controllers;

use App\DriversLicense;
use App\EyeColor;
use App\HairColor;
use App\Http\Requests\StoreLicense;
use Auth;
use Storage;

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
        $user = Auth::user();

        // Wrap in optional() in case license is null; missing properties will return null
        // instead of throwing a "getting non-property of null object" exception.
        $license = optional($user->license);

        // Enums for the dropdown menus
        $eyeColors = EyeColor::pluck('name', 'id');
        $hairColors = HairColor::pluck('name', 'id');

        return view('dmv.license.create', compact('user', 'license', 'eyeColors', 'hairColors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLicense $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLicense $request)
    {
        $user = Auth::user();

        if ($user->license()->exists())
            return $this->update($request);

        $license = new DriversLicense;
        $license->fill($this->prepareParams($request));
        $license->photo = $request->file('photo')->store('license/photos', 'public'); // TODO: extract logic
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
        $license = Auth::user()->license()->firstOrFail();

        // If user uploaded a new photo
        if ($request->photo != null) {
            Storage::disk('public')->delete($license->photo);
            $license->photo = $request->file('photo')->store('license/photos', 'public');
        }

        $license->update($this->prepareParams($request));

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
