<?php

namespace Roles\Facades;

use Illuminate\Support\Facades\Facade;

class Package extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'roles';
    }
}
