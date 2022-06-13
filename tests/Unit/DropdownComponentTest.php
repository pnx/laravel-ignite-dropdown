<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Livewire\Livewire;
use Ignite\Components\DropdownComponent;

class DropdownComponentTest extends TestCase
{
    public function test_mount_with_value()
    {
        $array = [
            'One',
            'Two'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'value' => 1, 'adapter' => [ 'array', $array ] ])
            ->assertSet('name', 'dropdown')
            ->assertSet('value', 1)
            ->assertSet('selected', 'Two')
            ->assertEmitted('dropdown-select', 'dropdown', 1);
    }

    public function test_mount_with_value_and_max_results()
    {
        $array = [
            'One',
            'Two'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'value' => 1, 'adapter' => [ 'array', $array ], 'max_results' => 1 ])
            ->assertSet('name', 'dropdown')
            ->assertSet('value', 1)
            ->assertSet('selected', 'Two')
            ->assertEmitted('dropdown-select', 'dropdown', 1);
    }

    public function test_mount_with_invalid_value()
    {
        $array = [
            'One',
            'Two'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'value' => 999, 'adapter' => [ 'array', $array ] ])
            ->assertSet('name', 'dropdown')
            ->assertSet('value', null)
            ->assertSet('selected', null)
            ->assertEmitted('dropdown-select', 'dropdown', null);
    }

    public function test_mount()
    {
        $array = [
            'One',
            'Two'
        ];

        Livewire::test(DropdownComponent::class, [
                'name' => 'dropdown',
                'required' => true,
                'no_results' => 'no-results-custom',
                'placeholder' => 'Custom placeholder',
                'adapter' => [ 'array', $array ]
            ])
            ->assertSet('required', true)
            ->assertSet('no_results', 'no-results-custom')
            ->assertSet('placeholder', 'Custom placeholder');
    }

    public function test_required_selects_first_option()
    {
        $array = [
            'One',
            'Two'
        ];

        Livewire::test(DropdownComponent::class, [
                'name' => 'dropdown',
                'required' => true,
                'adapter' => [ 'array', $array ]
            ])
            ->assertSet('required', true)
            ->assertSet('value', 0)
            ->assertSet('selected', 'One');
    }

    public function test_select()
    {
        $array = [
            'one' => 'One',
            'two' => 'Two'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'my-name', 'adapter' => [ 'array', $array ] ])
            ->call('select', 'one')
            ->assertSet('selected', 'One')
            ->assertSet('value', 'one')
            ->assertEmitted('dropdown-select', 'my-name', 'one')
            ->call('select', 999)
            ->assertSet('selected', null)
            ->assertSet('value', null)
            ->assertEmitted('dropdown-select', 'my-name', null);
    }

    public function test_options_search()
    {
        $array = [
            1 => 'One',
            2 => 'Two'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'adapter' => [ 'array', $array ] ])
            ->set('search', 'tw')
            ->assertSet('options', collect([2 => 'Two']));
    }

    public function test_options_max_results()
    {
        $array = [
            1 => 'One',
            2 => 'Two',
            3 => 'Three'
        ];

        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'adapter' => [ 'array', $array ], 'max_results' => 2 ])
            ->set('search', '')
            ->assertSet('max_results', 2)
            ->assertSet('options', collect([1 => 'One', 2 => 'Two']));
    }

    public function test_open_close()
    {
        Livewire::test(DropdownComponent::class, ['name' => 'dropdown', 'adapter' => [ 'array', [] ] ])
            // Test open
            ->assertSet('menu_open', false)
            ->assertSet('search', '')
            ->call('open')
            ->assertSet('menu_open', true)
            // Test close
            ->call('close')
            ->assertSet('search', '')
            ->assertSet('menu_open', false)
            // Test open when search string are updated.
            ->set('search', 'some-string')
            ->assertSet('menu_open', true)
            ->assertSet('search', 'some-string')
            // Test that search is cleared on close.
            ->call('close')
            ->assertSet('search', '')
            ->assertSet('menu_open', false);
    }
}
