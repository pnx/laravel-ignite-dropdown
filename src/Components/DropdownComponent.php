<?php

namespace Ignite\Components;

use Illuminate\Support\Collection;
use Livewire\Component;

use Ignite\Traits\ReceivesValidationEvents;
use Ignite\Dropdown\AdapterFactory;
use Ignite\Contracts\DropdownAdapter;

class DropdownComponent extends Component
{
    use ReceivesValidationEvents;

    /**
     * Input name
     */
    public string $name;

    /**
     * The selected value
     */
    public $value;

    /**
     * Search string
     *
     * @var string
     */
    public string $search = '';

    /**
     * True if the dropdown menu is open.
     *
     * @var bool
     */
    public bool $menu_open = false;

    /**
     * True if dropdown value is required, false if optional.
     */
    public bool $required;

    public $adapter_type;

    public $adapter_options;

    /**
     * Placeholder string for the input.
     */
    public string $placeholder = 'ignite::dropdown.placeholder';

    /**
     * Translation string for when no search results are found.
     */
    public string $no_results = 'ignite::dropdown.no-results';

    /**
     * View script to render the dropdown menu.
     */
    protected string $component_view = 'ignite-dropdown::component';

    /**
     * View script to render the placeholder text when no item is selected.
     */
    protected string $placeholder_view = 'ignite-dropdown::placeholder';

    protected $max_results = null;

    protected function getListeners()
    {
        return $this->validation_listeners;
    }

    /**
     * Initialize component
     *
     * @var string $name
     * @var mixed $value
     * @var string $no_results
     * @var string $placeholder
     * @var bool $required
     * @return void
     */
    public function mount($name, array $adapter, $value = null, string $no_results = null,
        string $placeholder = null, bool $required = false)
    {
        $this->adapter_type = array_shift($adapter);
        $this->adapter_options = $adapter;

        $this->name = $name;
        $this->required = $required;
        $this->options = $this->options($this->search);

        // Set selected option of value are present.
        if ($value) {
            $this->select($value);
        }
        // Otherwise, select first option if required flag is set.
        else if ($this->required) {
            $value = $this->value($this->options->first());
            $this->select($value);
        }

        if (strlen($placeholder) > 0) {
            $this->placeholder = $placeholder;
        }

        if (strlen($no_results) > 0) {
            $this->no_results = $no_results;
        }
    }

    /**
     * Get selected item property
     *
     * @return mixed
     */
    public function getSelectedProperty()
    {
        return $this->value !== null ? $this->option($this->value) : null;
    }

    public function getAdapterProperty() : DropdownAdapter
    {
        return AdapterFactory::make($this->adapter_type, $this->adapter_options);
    }

    /**
     * Called when search input is updating.
     */
    public function updatingSearch($value)
    {
        $this->options = $this->options($value);

        // Make sure the menu is open.
        $this->menu_open = true;
    }

    /**
     * Open the dropdown menu
     */
    public function open()
    {
        $this->reset('search');
        $this->updatingSearch($this->search);
    }

    /**
     * Close the dropdown menu
     */
    public function close()
    {
        $this->reset('search');
        $this->menu_open = false;
    }

    /**
     * Called when a item is selected.
     */
    public function select($value = null)
    {
        $this->value = $this->option($value) ? $value : null;

        // Notify any parent component.
        $this->emitUp('dropdown-select', $this->name, $this->value);

        $this->close();
    }

    /**
     * Returns true if there is an option selected. false otherwise.
     *
     * @return bool
     */
    public function hasSelection() : bool
    {
        return $this->value !== null;
    }

    /**
     * Get the dropdown icon.
     *
     * @return string|null
     */
    public function getIcon() : ?string
    {
        return config('ignite.select.icon', null);
    }

    /**
     * Get an option's value.
     *
     * @params int $index   the array index of the option in $options collection
     * @params mixed $option the actual item in the collection.
     * @return mixed
     */
    public function value($option)
    {
        return $this->adapter->value($option);
    }

    /**
     * Get an option by it's value
     *
     * @return mixed
     */
    public function option($value)
    {
        return $this->adapter->option($value);
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @return array
     */
    public function options(string $search) : Collection
    {
        return $this->adapter->options($search, $this->max_results);
    }

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option)
    {
        return $this->adapter->renderOption($option);
    }

    /**
     * Render the currently selected option
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    public function renderSelectedOption($option)
    {
        return $this->renderOption($option);
    }

    /**
     * Determain if selection should be rendered or if a placeholder should be rendered.
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    public function renderSelection()
    {
        // If we are searching, do not render anything here.
        if (strlen($this->search) > 0) {
            return null;
        }

        // Render placeholder if there is no selected item.
        if (!$this->hasSelection()) {
            $content = view($this->placeholder_view, [ 'placeholder' => $this->placeholder ]);
        } else {
            $content = $this->renderSelectedOption($this->selected);
        }

        return view('ignite-dropdown::selection-container')
            ->with('content', $content);
    }

    /**
     * Render the component
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view($this->component_view);
    }
}
