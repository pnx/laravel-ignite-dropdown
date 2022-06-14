<?php

namespace Ignite\Dropdown\Adapters;

use Ignite\Contracts\DropdownAdapter;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class EnumAdapter implements DropdownAdapter
{
    /**
     * Enum class.
     */
    protected string $class;

    /**
     * Constructor.
     */
    public function __construct($enum_class)
    {
        $this->class = $enum_class;
    }

    /**
     * Get an option's value.
     *
     * @return mixed
     */
    public function value($option)
    {
        if (!($option instanceof $this->class)) {
            $option = $this->enumerate()->get($option);
        }
        return $this->getValue($option);
    }

    /**
     * Return the first option.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->enumerate()->first();
    }

    /**
     * Get an option by it's value.
     *
     * @return mixed
     */
    public function option($value)
    {
        return $this->enumerate()->get($value);
    }

    /**
     * Get options filtered by user provided search text.
     *
     * @return array
     */
    public function options(string $search, ?int $limit) : Collection
    {
        $options = $this->enumerate();

        if (strlen($search) > 0) {
            $options = $options->filter(function ($v, $k) use ($search) {
                $found_in_value = Str::contains(Str::lower($this->getDisplay($v)), Str::lower($search));

                if (!$found_in_value && $this->hasDescription()) {
                    return Str::contains(Str::lower($v->description()), Str::lower($search));
                }
                return $found_in_value;
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
        if (!($option instanceof $this->class)) {
            $option = $this->class::from($option);
        }

        return view('ignite-dropdown::enum.option', [
            'name' => $this->getDisplay($option),
            'description' => $this->hasDescription() ? $option->description() : false
        ]);
    }

    /**
     * Render an selected option item.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function renderSelectedOption($option)
    {
        if (!($option instanceof $this->class)) {
            $option = $this->class::from($option);
        }

        return __($this->getDisplay($option));
    }

    /**
     * Check if the enum class has description method.
     */
    protected function hasDescription()
    {
        return method_exists($this->class, 'description');
    }

    /**
     * Enumerate all the cases.
     */
    protected function enumerate()
    {
        return collect($this->class::cases())
            ->mapWithKeys(function($item) { return [ $item->name => $item ]; });
    }

    /**
     * Get the display value
     */
    protected function getDisplay($option)
    {
        if ($option instanceof \UnitEnum) {
            if (method_exists($option, 'display')) {
                return $option->display();
            }

            return $option->name;
        }

        return null;
    }

    /**
     * Get the enum value.
     */
    protected function getValue($option)
    {
        if (is_object($option) && method_exists($option, 'value')) {
            return $option->value();
        }

        if ($option instanceof \BackedEnum) {
            return $option->value;
        }

        // For unit enums, value and name are the same.
        if ($option instanceof \UnitEnum) {
            return $option->name;
        }
        return null;
    }
}
