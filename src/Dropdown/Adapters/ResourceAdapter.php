<?php

namespace Ignite\Dropdown\Adapters;

use Ignite\Contracts\HasTitle;
use Ignite\Contracts\HasSubtitle;
use Ignite\Contracts\HasThumbnail;
use Ignite\Contracts\DropdownAdapter;
use Ignite\Resource;
use Ignite\ResourceManager;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ResourceAdapter implements DropdownAdapter
{
    /**
     * The resource.
     */
    protected Resource $resource;

    /**
     * Constructor.
     */
    public function __construct(string $resource)
    {
        $this->resource = app()->make(ResourceManager::class)->make($resource);
    }

    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option)
    {
        $id = $this->resource::$id;
        return $option->getAttribute($id);
    }

    /**
     * Return the first option.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->resource->first();
    }

    /**
     * Get an option by it's value
     *
     * @return mixed
     */
    public function option($value)
    {
        return $this->resource::where($this->resource::$id, $value)->first();
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @param string $search
     * @param int|null $limit
     * @return array
     */
    public function options(string $search, ?int $limit) : Collection
    {
        $query = $this->resource::query();

        if (strlen($search) > 0) {
            $query->where(function($q) use ($search) {
                foreach($this->resource::$search as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }

        return $query->get();
    }

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option)
    {
        return view('ignite-dropdown::resource.option', [
            'resource' => $this->resource->new($option)
        ]);
    }

    /**
     * Render an selected option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderSelectedOption($option)
    {
        return view('ignite-dropdown::resource.selected', [
            'resource' => $this->resource->new($option)
        ]);
    }
}
