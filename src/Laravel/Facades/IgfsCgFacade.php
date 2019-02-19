<?php

namespace PagOnline\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class IgfsCgFacade.
 */
class IgfsCgFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'igfscg';
    }
}
