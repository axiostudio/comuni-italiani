<?php

namespace Axiostudio\Comuni;

use Illuminate\Support\Facades\Facade;

class ComuniFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'comuni';
    }
}
