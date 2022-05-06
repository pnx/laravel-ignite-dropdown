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
     * Get options filtered by user provided search text.
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
}
