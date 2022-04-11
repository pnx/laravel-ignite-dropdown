<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Tests\Fixtures\SimpleDropdownFixture;
use Livewire\Livewire;

class DropdownComponentTest extends TestCase
{
    public function test_mount_with_value()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'dropdown', 'value' => 1 ])
            ->assertSet('name', 'dropdown')
            ->assertSet('value', 1)
            ->assertSet('selected', ['k' => 1, 'v' => 'One']);
    }


    public function test_mount_with_invalid_value()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'dropdown', 'value' => 999 ])
            ->assertSet('name', 'dropdown')
            ->assertSet('value', null)
            ->assertSet('selected', null);
    }

    public function test_mount()
    {
        Livewire::test(SimpleDropdownFixture::class, [
                'name' => 'dropdown',
                'required' => true,
                'no_results' => 'no-results-custom',
                'placeholder' => 'Custom placeholder'
            ])
            ->assertSet('required', true)
            ->assertSet('no_results', 'no-results-custom')
            ->assertSet('placeholder', 'Custom placeholder');
    }

    public function test_required_selects_first_option()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'dropdown', 'required' => true])
            ->assertSet('required', true)
            ->assertSet('value', 1)
            ->assertSet('selected', ['k' => 1, 'v' => 'One']);
    }

    public function test_select()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'my-name' ])
            ->call('select', 2)
            ->assertSet('selected', ['k' => 2, 'v' => 'Two'])
            ->assertSet('value', 2)
            ->assertEmitted('dropdown-select', 'my-name', 2)
            ->call('select', 999)
            ->assertSet('selected', null)
            ->assertSet('value', null)
            ->assertEmitted('dropdown-select', 'my-name', null);
    }

    public function test_options_filter()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'dropdown' ])
            ->set('search', 'tw')
            ->assertSet('options', collect([1 => ['k' => 2, 'v' => 'Two']]));
    }

    public function test_open_close()
    {
        Livewire::test(SimpleDropdownFixture::class, ['name' => 'dropdown' ])
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
