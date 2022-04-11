<?php

namespace Ignite;

use Livewire\Component;
use Livewire\Wireable;
use Illuminate\Support\Collection;

abstract class DropdownComponent extends AbstractDropdownComponent
{
    /**
     * View script to render a single option
     */
    protected string $option_view = '';

    /**
     * View script to render the selected option.
     */
    protected string $selected_view = '';

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option)
    {
        return view($this->option_view, [ 'item' => $option ]);
    }

    /**
     * Render the currently selected option
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    public function renderSelectedOption($option)
    {
        if (strlen($this->selected_view) > 0) {
            return view($this->selected_view, [ 'item' => $option ]);
        }
        return $this->renderOption($option);
    }
}
