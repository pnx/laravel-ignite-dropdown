<?php

namespace Tests;

use Ignite\DropdownServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            DropdownServiceProvider::class,
        ];
    }
}
