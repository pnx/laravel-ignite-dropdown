<?php

namespace Tests\Fixtures\Enum;

enum SizeEnumFixture : string
{
    case S = "Small";
    case M = "Medium";
    case L = "Large";

    /**
     * For the display value in dropdowns, we want to show the "value" (Small, Medium, Large)
     */
    public function display() : string
    {
        return $this->value;
    }

    /**
     * For the actual value (stored in for example a database), we want to use the "name" (S, M, L)
     */
    public function value() : string
    {
        return $this->name;
    }
}
