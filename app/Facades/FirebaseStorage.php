<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FirebaseStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'firebase.storage';
    }
}
