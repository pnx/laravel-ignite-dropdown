<?php

namespace Ignite\Traits;

use Illuminate\Support\Str;

trait HandlesDropdownEvents
{
    protected function getListeners()
    {
        $listeners = [
            'dropdown-select' => 'onDropdownSelected'
        ];

        return array_merge($this->listeners, $listeners);
    }

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
