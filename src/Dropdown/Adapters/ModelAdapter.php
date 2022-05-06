<?php

namespace Ignite\Dropdown\Adapters;

use Ignite\Contracts\DropdownAdapter;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ModelAdapter implements DropdownAdapter
{
    /**
     * The model.
     */
    protected Model $model;

    /**
     * Field to use as value in the model, default is primary key.
     */
    protected ?string $value_field;

    /**
     * Field to use to display the item.
     */
    protected string $display_field;

    /**
     * Columns in the model to use for searching, if empty, fillable array is used.
     */
    protected array $columns;

    /**
     * Columns used to order the dropdown list.
     */
    protected array $orderBy;

    /**
     * Constructor.
     */
    public function __construct(string $model, string $value_field = null, string $display_field = 'name', array $columns = [], $orderBy = null)
    {
        $this->model = new $model;
        $this->value_field = $value_field;
        $this->display_field = $display_field;
        $this->columns = $columns;

        if (!is_array($orderBy)) {
            $orderBy = [ $orderBy ];
        }

        $this->orderBy = $orderBy;
    }

    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option)
    {
        // Use value field if present.
        if (strlen($this->value_field) > 0) {
            return $option->{$this->value_field};
        }

        // Default to primary key.
        return $option->getKey();
    }

    /**
     * Get an option by it's value
     *
     * @return mixed
     */
    public function option($value)
    {
        // Use value field if present.
        if (strlen($this->value_field) > 0) {
            return $this->model->where($this->value_field, $value)->first();
        }

        // Default to primary key.
        return $this->model->find($value);
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @return array
     */
    public function options(string $search, ?int $limit) : Collection
    {
        $query = $this->model->query();

        if (strlen($search) > 0) {
            $query->orWhere(function($q) use ($search) {
                foreach($this->getSearchableColumns() as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        foreach($this->orderBy as $k => $v) {
            if (is_string($k)) {
                $query->orderBy($k, $v);
            } else {
                $query->orderBy($v);
            }
        }

        return $query->get();
    }

    /**
     * The the columns that should applied to search.
     */
    public function getSearchableColumns()
    {
        // Use columns variable if set.
        if (count($this->columns) > 0) {
            return $this->columns;
        }

        // Fallback to models fillable columns.
        return $this->model->getFillable();
    }

    /**
     * Render an option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderOption($option)
    {
        return $option->{$this->display_field};
    }
}