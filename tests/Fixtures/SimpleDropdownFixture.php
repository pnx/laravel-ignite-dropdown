<?php

namespace Tests\Fixtures;

use Ignite\SimpleDropdownComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SimpleDropdownFixture extends SimpleDropdownComponent
{
    protected string $value_field = 'k';

    protected string $name_field = 'v';

    protected function getAllOptions() : Collection
    {
        return collect([
            ["k" => 1, "v" => "One"],
            ["k" => 2, "v" => "Two"],
            ["k" => 3, "v" => "Three"]
        ]);
    }
}
