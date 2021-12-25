<?php

namespace AdDirector\Facades;

use Illuminate\Support\Facades\Facade;

class AdDirector extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ad-director';
    }
}
