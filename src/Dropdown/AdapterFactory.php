<?php

namespace Ignite\Dropdown;

use Ignite\Contracts\DropdownAdapter;
use InvalidArgumentException;

class AdapterFactory
{
    /**
     *
     * @throws InvalidArgumentException
     * @throws Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function make(...$args) : DropdownAdapter
    {
        if (array_key_exists('type', $args)) {
            $type = $args['type'];
            unset($args['type']);
        } else {
            $type = array_shift($args);
            if (!is_string($type)) {
                throw new InvalidArgumentException("First argument must be a string.");
            }
        }

        switch ($type) {
        case 'resource' :
            return new Adapters\ResourceAdapter(...$args);
        case 'enum':
            return new Adapters\EnumAdapter(...$args);
        case 'model':
            return new Adapters\ModelAdapter(...$args);
        case 'array':
            return new Adapters\ArrayAdapter(...$args);
        default :
            return app()->make($type, $args);
        };
    }
}
