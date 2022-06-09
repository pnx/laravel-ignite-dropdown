<?php

namespace Tests\Unit\Dropdown\Adapters;

use Tests\TestCase;
use Tests\Fixtures\Enum\BackedEnum;
use Tests\Fixtures\Enum\UnitEnum;
use Tests\Fixtures\Enum\DescriptionEnum;
use Tests\Fixtures\Enum\SizeEnum;
use Ignite\Dropdown\Adapters\EnumAdapter;

class EnumAdapterTest extends TestCase
{
    public function test_options_unit()
    {
        $adapter = new EnumAdapter(UnitEnum::class);

        $expected = [
            'Hearts' => UnitEnum::Hearts,
            'Diamonds' => UnitEnum::Diamonds,
            'Clubs' => UnitEnum::Clubs,
            'Spades' => UnitEnum::Spades
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['Diamonds' => UnitEnum::Diamonds], $adapter->options('ds', null)->toArray());
    }

    public function test_options_backed()
    {
        $adapter = new EnumAdapter(BackedEnum::class);

        $expected = [
            'SE' => BackedEnum::SE,
            'FI' => BackedEnum::FI,
            'IN' => BackedEnum::IN,
            'PL' => BackedEnum::PL,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['SE' => BackedEnum::SE], $adapter->options('S', null)->toArray());
    }

    public function test_options_with_display()
    {
        $adapter = new EnumAdapter(SizeEnum::class);

        $expected = [
            'S' => SizeEnum::S,
            'M' => SizeEnum::M,
            'L' => SizeEnum::L,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['S' => SizeEnum::S], $adapter->options('all', null)->toArray());
    }

    public function test_options_with_description()
    {
        $adapter = new EnumAdapter(DescriptionEnum::class);

        $expected = [
            'APPLE' => DescriptionEnum::APPLE,
            'PEAR' => DescriptionEnum::PEAR,
            'ORANGE' => DescriptionEnum::ORANGE,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['ORANGE' => DescriptionEnum::ORANGE], $adapter->options('Color', null)->toArray());
    }

    public function test_options_with_limit()
    {
        $adapter = new EnumAdapter(BackedEnum::class);

        $expected = [
            'SE' => BackedEnum::SE,
            'FI' => BackedEnum::FI
        ];

        $this->assertEquals($expected, $adapter->options('', 2)->toArray());
    }

    public function test_first_unit()
    {
        $adapter = new EnumAdapter(UnitEnum::class);

        $this->assertEquals(UnitEnum::Hearts, $adapter->first());
    }

    public function test_first_backed()
    {
        $adapter = new EnumAdapter(BackedEnum::class);

        $this->assertEquals(BackedEnum::SE, $adapter->first());
    }

    public function test_first_display()
    {
        $adapter = new EnumAdapter(SizeEnum::class);

        $this->assertEquals(SizeEnum::S, $adapter->first());
    }

    public function test_value_unit()
    {
        $adapter = new EnumAdapter(UnitEnum::class);

        $this->assertEquals('Hearts', $adapter->value('Hearts'));
        $this->assertEquals('Clubs', $adapter->value(UnitEnum::Clubs));
    }

    public function test_value_backed()
    {
        $adapter = new EnumAdapter(BackedEnum::class);

        $this->assertEquals('Sweden', $adapter->value('SE'));
        $this->assertEquals('Finland', $adapter->value(BackedEnum::FI));
    }

    public function test_value_size()
    {
        $adapter = new EnumAdapter(SizeEnum::class);

        $this->assertEquals('L', $adapter->value('L'));
        $this->assertEquals('M', $adapter->value(SizeEnum::M));
    }
}
