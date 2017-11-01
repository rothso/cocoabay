<?php

namespace App\Policies;

use App\User;
use App\LicensePlate;
use Illuminate\Auth\Access\HandlesAuthorization;

class LicensePlatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the licensePlate.
     *
     * @param  \App\User  $user
     * @param  \App\LicensePlate  $licensePlate
     * @return mixed
     */
    public function view(User $user, LicensePlate $licensePlate)
    {
        return $user->id === $licensePlate->id;
    }

    /**
     * Determine whether the user can update the licensePlate.
     *
     * @param  \App\User  $user
     * @param  \App\LicensePlate  $licensePlate
     * @return mixed
     */
    public function update(User $user, LicensePlate $licensePlate)
    {
        return $user->id === $licensePlate->id;
    }

    /**
     * Determine whether the user can delete the licensePlate.
     *
     * @param  \App\User  $user
     * @param  \App\LicensePlate  $licensePlate
     * @return mixed
     */
    public function delete(User $user, LicensePlate $licensePlate)
    {
        //
    }
}
