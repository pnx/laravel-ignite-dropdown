<?php

namespace Ignite;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

abstract class SimpleDropdownComponent extends AbstractDropdownComponent
{
    /**
     * What array key to use for the value.
     *
     * @var string
     */
    protected string $value_field = 'value';

    /**
     * What array key to use for the name.
     *
     * @var string
     */
    protected string $name_field = 'name';

    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option)
    {
        return $option[$this->value_field];
    }

    /**
     * Get an option by it's value
     *
     * @return mixed
     */
    public function option($value)
    {
        return $this->getAllOptions()->where($this->value_field, $value)->first();
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @return array
     */
    public function options(string $search) : Collection
    {
        $options = $this->getAllOptions();

        if (strlen($search) > 0) {
            $options = $options->filter(function ($v, $k) use ($search) {
                return Str::contains(Str::lower($v[$this->name_field]), Str::lower($search));
            });
        }

        return $options;
    }

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option)
    {
        return $option[$this->name_field];
    }

    /**
     * Render the currently selected option
     *
     * @return \Illuminate\Contracts\View\View|null
     */
    public function renderSelectedOption($option)
    {
        return $option[$this->name_field];
    }

    /**
     * Get the unfiltered options.
     */
    abstract protected function getAllOptions() : Collection;
}
