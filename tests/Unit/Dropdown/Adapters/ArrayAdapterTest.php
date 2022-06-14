<?php

namespace Tests\Unit\Dropdown\Adapters;

use Tests\TestCase;
use Ignite\Dropdown\Adapters\ArrayAdapter;

class ArrayAdapterTest extends TestCase
{
    public function test_options()
    {
        $array = [
            'en' => 'English',
            'sv' => 'Swedish',
        ];

        $adapter = new ArrayAdapter($array);

        $this->assertEquals($array, $adapter->options('', null)->toArray());
        $this->assertEquals($array, $adapter->options('ish', null)->toArray());
        $this->assertEquals(['en' => 'English'], $adapter->options('Eng', null)->toArray());
        $this->assertEquals(['sv' => 'Swedish'], $adapter->options('w', null)->toArray());
        $this->assertEquals([], $adapter->options('x', null)->toArray());
    }

    public function test_options_with_set()
    {
        $array = [
            'English' => 'English',
            'Swedish' => 'Swedish',
        ];

        $adapter = new ArrayAdapter($array, true);

        $this->assertEquals($array, $adapter->options('', null)->toArray());
        $this->assertEquals($array, $adapter->options('ish', null)->toArray());
        $this->assertEquals(['English' => 'English'], $adapter->options('Eng', null)->toArray());
        $this->assertEquals(['Swedish' => 'Swedish'], $adapter->options('w', null)->toArray());
        $this->assertEquals([], $adapter->options('x', null)->toArray());
    }

    public function test_options_with_limit()
    {
        $array = [
            'One',
            'Two',
            'Three',
        ];

        $adapter = new ArrayAdapter($array);

        $this->assertEquals(['One', 'Two'], $adapter->options('', 2)->toArray());
    }

    public function test_key()
    {
        $adapter = new ArrayAdapter([
            'Small',
            'Medium',
            'Large'
        ]);

        $options = $adapter->options('', null);
        $this->assertEquals('Medium', $options->get(1));
        $this->assertNull($options->get('Extra large'));
    }

    public function test_key_assoc()
    {
        $adapter = new ArrayAdapter([
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
        ]);

        $options = $adapter->options('', null);
        $this->assertEquals('Published', $options->get('published'));
        $this->assertNull($options->get('rewoked'));
    }

    public function test_first()
    {
        $adapter = new ArrayAdapter([
            'Small',
            'Medium',
            'Large'
        ]);

        $this->assertEquals('Small', $adapter->first());
    }

    public function test_option()
    {
        $adapter = new ArrayAdapter([
            'Small',
            'Medium',
            'Large'
        ]);

        $this->assertEquals('Large', $adapter->option(2));
    }

    public function test_option_assoc()
    {
        $adapter = new ArrayAdapter([
            'S' => 'Small',
            'M' => 'Medium',
            'L' => 'Large'
        ]);

        $this->assertEquals('Medium', $adapter->option('M'));
    }

    public function test_value()
    {
        $adapter = new ArrayAdapter([
            'Biography',
            'Cooking',
            'History',
            'Math',
            'Travel'
        ]);

        $this->assertEquals(1, $adapter->value('Cooking'));
        $this->assertNull($adapter->value('Crafts'));
    }

    public function test_value_assoc()
    {
        $array = [
            'username' => 'Name',
            'phone' => 'Phone',
            'email' => 'Mail',
            'role_id' => 'Role'
        ];

        $adapter = new ArrayAdapter($array);
        $this->assertEquals('email', $adapter->value('Mail'));
        $this->assertNull($adapter->value('Password'));
    }

    public function test_value_with_set()
    {
        $array = [
            'key1' => 'Name',
            'key2' => 'Phone',
            'Mail',
            'Role'
        ];

        $adapter = new ArrayAdapter($array, true);
        $this->assertEquals('Phone', $adapter->value('Phone'));
        $this->assertNull($adapter->value('Password'));
    }

    public function test_render()
    {
        $adapter = new ArrayAdapter([]);
        $this->assertEquals('Phone', $adapter->renderOption('Phone'));
        $this->assertEquals('Phone', $adapter->renderSelectedOption('Phone'));
    }
}
