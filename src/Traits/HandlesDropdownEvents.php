<?php

namespace Ignite\Traits;

use Illuminate\Support\Str;

trait HandlesDropdownEvents
{
    protected $dropdown_listeners = [
        'dropdown-select' => 'onDropdownSelected'
    ];

    public function onDropdownSelected($field, $value)
    {
        $method = 'set' . Str::ucfirst($field);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $this->fill([ $field => $value ]);
        }
    }
}
