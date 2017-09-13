<?php

namespace App\Http\Controllers;

use Auth;
use App\EyeColors;
use App\HairColors;
use App\DriversLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $eyeColors = EyeColors::pluck('name', 'id');
        $hairColors = HairColors::pluck('name', 'id');

        return view('license.create', compact('eyeColors', 'hairColors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        // TODO: Handle duplicate licenses
        $this->makeLicense($request->all());

        return response('Success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Get a validator for an incoming license request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:MALE,FEMALE',
            'height_ft' => 'required|integer|min:0|max:7',
            'height_in' => 'required|integer|min:0|max:11',
            'weight' => 'required|integer|min:0|max:400',
            'eye_color' => 'required|exists:eye_colors,id', // foreign key
            'hair_color' => 'required|exists:hair_colors,id', // foreign key
            'address' => 'required|string'
        ])->setAttributeNames(['dob' => 'date of birth']);
    }


    /**
     * Create a new license after validation.
     *
     * @param  array $data
     */
    protected function makeLicense(array $data)
    {
        $license = new DriversLicense;
        $license->dob = $data['dob'];
        $license->gender = $data['gender'];
        $license->height_in = $data['height_in'] + 12 * $data['height_ft'];
        $license->weight_lb = $data['weight'];
        $license->eye_color_id = $data['eye_color'];
        $license->hair_color_id = $data['hair_color'];
        $license->address = $data['address'];
        $license->user()->associate(Auth::user());
        $license->save();
    }
}
