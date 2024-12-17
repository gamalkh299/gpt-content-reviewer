<?php

namespace gamalkh\GptContentReviewer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \gamalkh\GptContentReviewer\GptContentReviewer
 */
class GptContentReviewer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \gamalkh\GptContentReviewer\GptContentReviewer::class;
    }
}
