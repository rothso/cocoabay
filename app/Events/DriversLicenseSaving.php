<?php

namespace App\Events;

use App\DriversLicense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class DriversLicenseSaving
{
    use InteractsWithSockets, SerializesModels;

    public $license;

    /**
     * Create a new event instance.
     *
     * @param DriversLicense $license
     */
    public function __construct(DriversLicense $license)
    {
        $this->license = $license;
    }
}
