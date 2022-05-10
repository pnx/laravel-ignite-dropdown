<?php

namespace Tests\Unit\Dropdown;

use Tests\TestCase;
use Ignite\Contracts\DropdownAdapter;
use Ignite\Dropdown\AdapterFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class AdapterFactoryTest extends TestCase
{
    public function test_make_with_named_arguments()
    {
        $args = ['type' => 'array', 'options' => ['One', 'Two']];

        $adapter = AdapterFactory::make(...$args);
        $this->assertTrue($adapter instanceof DropdownAdapter);
    }

    public function test_make_with_positional_arguments()
    {
        $args = [ 'array', ['One', 'Two'] ];

        $adapter = AdapterFactory::make(...$args);
        $this->assertTrue($adapter instanceof DropdownAdapter);
    }

    public function test_make_with_unknown_adapter()
    {
        $this->expectException(BindingResolutionException::class);
        $args = [ 'invalid', ['One', 'Two'] ];

        $adapter = AdapterFactory::make(...$args);
        $this->assertTrue($adapter instanceof DropdownAdapter);
    }

    public function test_make_with_non_string_as_first_argument_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $args = [ null ];

        $adapter = AdapterFactory::make(...$args);
        $this->assertTrue($adapter instanceof DropdownAdapter);
    }

    public function test_make_with_no_arguments_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $args = [];

        $adapter = AdapterFactory::make(...$args);
        $this->assertTrue($adapter instanceof DropdownAdapter);
    }
}
