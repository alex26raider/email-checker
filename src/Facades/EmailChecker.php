<?php

namespace Alex26raider\EmailChecker\Facades;

use Illuminate\Support\Facades\Facade;

class EmailChecker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Alex26raider\EmailChecker\EmailChecker';
    }
}
