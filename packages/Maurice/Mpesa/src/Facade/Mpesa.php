<?php

namespace Maurice\Mpesa\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Mpesa
 *
 * @author  Maurice Kuria <developermaurice@gmail.com>
 */
class Mpesa extends Facade
{
    /**
     * Get the registered facade.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mpesa';
    }
}
