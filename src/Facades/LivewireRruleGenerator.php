<?php

namespace Remeritus\LivewireRruleGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Remeritus\LivewireRruleGenerator\LivewireRruleGenerator
 */
class LivewireRruleGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'livewire-rrule-generator';
    }
}
