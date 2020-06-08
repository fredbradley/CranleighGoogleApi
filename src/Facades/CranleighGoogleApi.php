<?php

namespace fredbradley\CranleighGoogleApi\Facades;

use Illuminate\Support\Facades\Facade;

class CranleighGoogleApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cranleighgoogleapi';
    }
}
