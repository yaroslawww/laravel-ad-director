<?php

namespace AdDirector\Facades;

use Illuminate\Support\Facades\Facade;

class AdGPT extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ad-gpt-manager';
    }
}
