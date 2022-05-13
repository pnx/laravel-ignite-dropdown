<?php

namespace Tests\Unit\Dropdown\Adapters;

use Tests\TestCase;
use Tests\Fixtures\Models\User;
use Tests\Fixtures\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ignite\Dropdown\Adapters\ResourceAdapter;
use Ignite\ResourceManager;

class ResourceAdapterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        app()->make(ResourceManager::class)
            ->setNamespace('Tests\\Fixtures\\Resources');
    }

    public function seedUsers()
    {
        User::insert([
            [ 'name' => 'Frances Goodwin', 'email' => 'hwalker@example.net'],
            [ 'name' => 'Sarina Mraz', 'email' => 'gunnar27@example.org'],
            [ 'name' => 'Fiona Schmitt V', 'email' => 'elias.batz@example.net'],
        ]);
    }

    public function seedContacts()
    {
        Contact::insert([
            [ 'name' => 'Japera Trost', 'address' => '6039 Green Nook', 'city' => 'Shingle Springs, Idaho', 'zip' => '83206-4308', 'phone' => '(208) 362-6240', 'email' => 'trost@example.com' ],
            [ 'name' => 'Stanko Jarman', 'address' => '2803 Old Line', 'city' => 'Yellow Dog, Idaho', 'zip' => '83889-4521', 'phone' => '(208) 564-7514', 'email' => 'yellowstanko@example.com' ],
            [ 'name' => 'Avenall Coates', 'address' => '4008 Rocky Mews', 'city' => 'Oquawka, South Dakota', 'zip' => '57726-1540', 'phone' => '(605) 402-8834', 'email' => 'avenall.coates123@example.com' ]
        ]);
    }

    public function test_options()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ResourceAdapter('user-with-email-search');

        $this->assertEquals(User::all(), $adapter->options('', null));

        // Find John by email.
        $result = $adapter->options('123', null);
        $this->assertCount(1, $result);
        $this->assertEquals($user1->id, $result->first()->id);

        $result = $adapter->options('Doe', null);
        $this->assertCount(2, $result);
        $this->assertEquals($user1->id, $result[0]->id);
        $this->assertEquals($user2->id, $result[1]->id);

        $result = $adapter->options('Jane D', null);
        $this->assertCount(1, $result);
        $this->assertEquals($user2->id, $result->first()->id);
    }

    public function test_options_with_order()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ResourceAdapter('user-with-order');
        $expected = User::orderBy('name', 'desc')->get();

        $this->assertEquals($expected, $adapter->options('', null));
    }

    public function test_option()
    {
        $user = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);

        $adapter = new ResourceAdapter('user');
        $result = $adapter->option($user->id);
        $this->assertNotNull($result);
        $this->assertEquals($user->toArray(), $result->toArray());
        $this->assertNull($adapter->option('invalid_value'));
    }

    public function test_option_with_custom_id()
    {
        $contact = Contact::create([ 'name' => 'Ebba Ullrich', 'address' => '5842 Easy Centre', 'city' => 'Colorado, Virginia', 'zip' => '23232-1470', 'phone' => '(434) 336-1206', 'email' => 'xebbax@example.com' ]);

        $adapter = new ResourceAdapter('contact-with-email-id');
        $result = $adapter->option($contact->email);
        $this->assertNotNull($result);
        $this->assertEquals($contact->toArray(), $result->toArray());
        $this->assertNull($adapter->option('invalid_value'));
    }

    public function test_value()
    {
        $user = User::create([ 'name' => 'Arthur Pearce', 'email' => 'arthur@pearce.com' ]);

        $adapter = new ResourceAdapter('user');
        $this->assertEquals($user->id, $adapter->value($user));
    }

    public function test_value_with_custom_id()
    {
        $user = User::create([ 'name' => 'Lelio Aonghuis', 'email' => 'lelio@aonghuis.com' ]);

        $adapter = new ResourceAdapter('contact-with-email-id');
        $this->assertEquals($user->email, $adapter->value($user));
    }

    public function test_renderOption()
    {
        $user = User::create([ 'id' => 1, 'name' => 'Gronw Biermann', 'email' => 'gronw@biermann.com' ]);

        $adapter = new ResourceAdapter('user');
        $this->assertStringContainsString('1', (string) $adapter->renderOption($user));
    }

    public function test_renderOption_with_custom_title()
    {
        $user = User::create([ 'id' => 1, 'name' => 'Zola Gladwin', 'email' => 'zola@gladwin.com' ]);

        $adapter = new ResourceAdapter('user-with-title');
        $this->assertStringContainsString('Zola Gladwin', (string) $adapter->renderOption($user));
    }

    public function test_renderOption_with_subtitle()
    {
        $user = User::create([ 'id' => 1, 'name' => 'Pjetër Sydnie', 'email' => 'pjeter@sydnie.com' ]);

        $adapter = new ResourceAdapter('user-with-subtitle');
        $result = (string) $adapter->renderOption($user);
        $this->assertStringContainsString('Pjetër Sydnie', $result);
        $this->assertStringContainsString('Email: pjeter@sydnie.com', $result);
    }

    public function test_renderOption_with_thumbnail()
    {
        $user = User::create([ 'id' => 1, 'name' => 'Lumusi Bastien', 'email' => 'lumusi@bastien.com' ]);

        $adapter = new ResourceAdapter('user-with-thumbnail');
        $result = (string) $adapter->renderOption($user);
        $this->assertStringContainsString('Lumusi Bastien', $result);
        $this->assertStringContainsString('<img src="https://gravatar.com/8187d1e9fb0" />', $result);
    }

    public function test_renderOption_with_title_subtitle_thumbnail()
    {
        $user = User::create([ 'id' => 1, 'name' => 'Janine Franco', 'email' => 'janine@franco.com' ]);

        $adapter = new ResourceAdapter('user-with-title-subtitle-thumbnail');
        $result = (string) $adapter->renderOption($user);
        $this->assertStringContainsString('Janine Franco', $result);
        $this->assertStringContainsString('Email: janine@franco.com', $result);
        $this->assertStringContainsString('<img src="https://gravatar.com/c4ca4238a0b923820dcc509a6f75849b" />', $result);
    }

}
