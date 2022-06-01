<?php

namespace Ignite\Contracts;

use Illuminate\Support\Collection;

interface DropdownAdapter
{
    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option);

    /**
     * Get an option by it's value
     *
     * @return mixed
     */
    public function option($value);

    /**
     * Return the first option.
     *
     * @return mixed
     */
    public function first();

    /**
     * Get options filtered by user provided search text.
     *
     * The collection returned should have the option id as key and the option as value.
     *
     * @param string $search
     * @param int|null $limit
     * @return array
     */
    public function options(string $search, ?int $limit) : Collection;

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option);

    /**
     * Render an selected option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderSelectedOption($option);
}
