<?php

use App\Helpers\Optional;

// Backports the Laravel 5.5 optional() helper
if (!function_exists('optional')) {
    /**
     * Provide access to optional objects.
     *
     * @param  mixed $value
     * @return mixed
     */
    function optional($value)
    {
        return new Optional($value);
    }
}