<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    /**
     * Show the instructions for registering in-game.
     *
     * @return \Illuminate\Http\Response
     */
    public function showInstructions()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {
            // TODO: Helpful message for the client & internal logging for the server
            return response('One or more invalid request parameters', 422);
        }

        event(new Registered($user = $this->create($request->all())));

        return response('Success!');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'uuid' => 'required|uuid|unique:users',
            'username' => 'required|string|unique:users|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'uuid' => $data['uuid'],
            'username' => $data['username'],
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
