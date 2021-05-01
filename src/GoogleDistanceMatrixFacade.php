<?php

namespace Mybit\GoogleDistanceMatrix;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mybit\GoogleDistanceMatrix\GoogleDistanceMatrix
 */
class GoogleDistanceMatrixFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-distance-matrix';
    }
}
