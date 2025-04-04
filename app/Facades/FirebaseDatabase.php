<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FirebaseDatabase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'firebase.database';
    }
}
