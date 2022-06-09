<?php

namespace Tests\Fixtures\Enum;

enum DescriptionEnum : string
{
    case APPLE  = 'Apple';
    case PEAR   = 'Pear';
    case ORANGE = 'Orange';

    public function description()
    {
        switch($this) {
        case self::APPLE: return "A red apple";
        case self::PEAR: return "A pear, delicous fruit.";
        case self::ORANGE: return "An orange, also a color.";
        }
        return $this->value;
    }
}
