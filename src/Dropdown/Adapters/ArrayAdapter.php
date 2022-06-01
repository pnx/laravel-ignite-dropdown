<?php

namespace Ignite\Dropdown\Adapters;

use Ignite\Contracts\DropdownAdapter;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ArrayAdapter implements DropdownAdapter
{
    protected Collection $options;

    public function __construct(array $options, bool $as_set = false)
    {
        $options = collect($options);

        if ($as_set) {
            $options = $options->values()
                ->combine($options->values());
        }

        $this->options = $options;
    }

    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option)
    {
        $result = $this->getAllOptions()->search($option);
        return $result !== false ? $result : null;
    }

    /**
     * Return the first option.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->options->first();
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @return array
     */
    public function options(string $search, ?int $limit) : Collection
    {
        $options = $this->getAllOptions();

        if (strlen($search) > 0) {
            $options = $options->filter(function ($v, $k) use ($search) {
                return Str::contains(Str::lower($v), Str::lower($search));
            });
        }

        if ($limit !== null) {
            $options = $options->take($limit);
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
        return $option;
    }

    /**
     * Render an selected option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderSelectedOption($option)
    {
        return $option;
    }

    /**
     * Get the unfiltered options.
     */
    protected function getAllOptions() : Collection
    {
        return $this->options;
    }
}
