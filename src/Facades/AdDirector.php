<?php

namespace AdDirector\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string configurationScript(?string $manager = null)
 */
class AdDirector extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ad-director';
    }
}
