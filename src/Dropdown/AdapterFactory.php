<?php

namespace Ignite\Dropdown;

use Ignite\Contracts\DropdownAdapter;

class AdapterFactory
{
    public static function make(string $type, $args = []) : DropdownAdapter
    {
        return match($type) {
            'model' => new Adapters\ModelAdapter(...$args),
            'array' => new Adapters\ArrayAdapter(...$args),
            default => app()->make($type, $args)
        };
    }
}
