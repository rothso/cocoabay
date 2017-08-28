<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // The client always sends the same data regardless of whether it wants to create or update
        $validation = $this->validator($request->all());

        // Abort if the data doesn't conform to the database schema
        if ($validation->fails()) {
            $errors = $validation->getMessageBag();

            if ($errors->has('password')) {
                return response($errors->first('password'), 422);
            }

            // At least one of the context parameters failed validation. TODO: Report to Sentry.
            return response('One or more invalid request parameters (script broken?).'
                . 'The issue has been automatically reported.', 422);
        }

        // This lets us determine if we need to make a new record or update an existing one
        $record = User::where('uuid', $request->uuid);

        // If this is a new valid UUID, register the user with a new account
        if (!$record->exists()) {
            event(new Registered($record = $this->create($request->all())));
            return response('Success!');
        }

        // This shouldn't never happen, but it's harmless, so don't abort. The LSL script probably
        // needs to check an edge case we didn't consider. In that event, report a warning to Sentry
        // so someone can know to patch the script for the future.
        if (!$record->where('username', $request->username)->exists()) {
            // TODO: report [warning] "record mismatch" to Sentry
        }

        // The user is already registered and is resubmitting a new password
        $user = $record->first();
        $user->name = $request->name; // resync the user's display name in case they had changed it
        $user->password = Hash::make($request->password);
        $user->save();

        return response('Success!');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $uniqueExceptSelf = Rule::unique('users')->ignore($data['uuid'], 'uuid');

        return Validator::make($data, [
            'uuid' => ['required', 'uuid', $uniqueExceptSelf],
            'username' => ['required', 'string', 'max:255', $uniqueExceptSelf],
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
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
