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

        return match($type) {
            'resource' => new Adapters\ResourceAdapter(...$args),
            'model' => new Adapters\ModelAdapter(...$args),
            'array' => new Adapters\ArrayAdapter(...$args),
            default => app()->make($type, $args)
        };
    }
}
