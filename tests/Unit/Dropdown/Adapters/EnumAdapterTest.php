<?php

namespace Tests\Unit\Dropdown\Adapters;

use Tests\TestCase;
use Tests\Fixtures\Enum\BackedEnumFixture;
use Tests\Fixtures\Enum\UnitEnumFixture;
use Tests\Fixtures\Enum\DescriptionEnumFixture;
use Tests\Fixtures\Enum\SizeEnumFixture;
use Ignite\Dropdown\Adapters\EnumAdapter;

class EnumAdapterTest extends TestCase
{
    public function test_options_unit()
    {
        $adapter = new EnumAdapter(UnitEnumFixture::class);

        $expected = [
            'Hearts' => UnitEnumFixture::Hearts,
            'Diamonds' => UnitEnumFixture::Diamonds,
            'Clubs' => UnitEnumFixture::Clubs,
            'Spades' => UnitEnumFixture::Spades
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['Diamonds' => UnitEnumFixture::Diamonds], $adapter->options('ds', null)->toArray());
    }

    public function test_options_backed()
    {
        $adapter = new EnumAdapter(BackedEnumFixture::class);

        $expected = [
            'SE' => BackedEnumFixture::SE,
            'FI' => BackedEnumFixture::FI,
            'IN' => BackedEnumFixture::IN,
            'PL' => BackedEnumFixture::PL,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['SE' => BackedEnumFixture::SE], $adapter->options('S', null)->toArray());
    }

    public function test_options_with_display()
    {
        $adapter = new EnumAdapter(SizeEnumFixture::class);

        $expected = [
            'S' => SizeEnumFixture::S,
            'M' => SizeEnumFixture::M,
            'L' => SizeEnumFixture::L,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['S' => SizeEnumFixture::S], $adapter->options('all', null)->toArray());
    }

    public function test_options_with_description()
    {
        $adapter = new EnumAdapter(DescriptionEnumFixture::class);

        $expected = [
            'APPLE' => DescriptionEnumFixture::APPLE,
            'PEAR' => DescriptionEnumFixture::PEAR,
            'ORANGE' => DescriptionEnumFixture::ORANGE,
        ];

        $this->assertEquals($expected, $adapter->options('', null)->toArray());
        $this->assertEquals(['ORANGE' => DescriptionEnumFixture::ORANGE], $adapter->options('Color', null)->toArray());
    }

    public function test_options_with_limit()
    {
        $adapter = new EnumAdapter(BackedEnumFixture::class);

        $expected = [
            'SE' => BackedEnumFixture::SE,
            'FI' => BackedEnumFixture::FI
        ];

        $this->assertEquals($expected, $adapter->options('', 2)->toArray());
    }

    public function test_first_unit()
    {
        $adapter = new EnumAdapter(UnitEnumFixture::class);

        $this->assertEquals(UnitEnumFixture::Hearts, $adapter->first());
    }

    public function test_first_backed()
    {
        $adapter = new EnumAdapter(BackedEnumFixture::class);

        $this->assertEquals(BackedEnumFixture::SE, $adapter->first());
    }

    public function test_first_display()
    {
        $adapter = new EnumAdapter(SizeEnumFixture::class);

        $this->assertEquals(SizeEnumFixture::S, $adapter->first());
    }

    public function test_value_unit()
    {
        $adapter = new EnumAdapter(UnitEnumFixture::class);

        $this->assertEquals('Hearts', $adapter->value('Hearts'));
        $this->assertEquals('Clubs', $adapter->value(UnitEnumFixture::Clubs));
    }

    public function test_value_backed()
    {
        $adapter = new EnumAdapter(BackedEnumFixture::class);

        $this->assertEquals('Sweden', $adapter->value('SE'));
        $this->assertEquals('Finland', $adapter->value(BackedEnumFixture::FI));
    }

    public function test_value_size()
    {
        $adapter = new EnumAdapter(SizeEnumFixture::class);

        $this->assertEquals('L', $adapter->value('L'));
        $this->assertEquals('M', $adapter->value(SizeEnumFixture::M));
    }
}
