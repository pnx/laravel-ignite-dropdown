<?php

namespace Tests\Unit\Dropdown\Adapters;

use Tests\TestCase;
use Tests\Fixtures\Models\User;
use Tests\Fixtures\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ignite\Dropdown\Adapters\ModelAdapter;

class ModelAdapterTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_getters()
    {
        $adapter = new ModelAdapter(User::class);


        $this->assertNull($adapter->getValueField());
        $this->assertEquals('name', $adapter->getDisplayField());
        $this->assertEmpty($adapter->getOrderBy());
        $this->assertEquals(['name', 'email'], $adapter->getSearchableColumns());
        $this->assertTrue($adapter->getModel() instanceof User);
    }

    public function test_options()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class);

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

    public function test_options_with_override()
    {
        $this->seedContacts();

        $contact1 = Contact::create([ 'name' => 'Aswad Aguinaga', 'address' => '4978 Cozy Pioneer Promenade', 'city' => 'Vulcan, New York', 'zip' => '13820-2456', 'phone' => '(315) 849-1696', 'email' => 'aswad1@example.com' ]);

        $adapter = new ModelAdapter(Contact::class, null, 'name', [ 'email' ]);

        $this->assertEquals(Contact::all(), $adapter->options('', null));

        // Find Aswad by email.
        $result = $adapter->options('aswad1', null);
        $this->assertCount(1, $result);
        $this->assertEquals($contact1->id, $result->first()->id);

        // We should not find any Jane.
        $result = $adapter->options('Jane', null);
        $this->assertEmpty($result);
    }

    public function test_options_with_order()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class, null, 'name', [], 'name');

        $this->assertEquals(User::orderBy('name')->get(), $adapter->options('', null));
    }


    public function test_option()
    {
        $user = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);

        $adapter = new ModelAdapter(User::class);
        $result = $adapter->option($user->id);
        $this->assertNotNull($result);
        $this->assertEquals($user->toArray(), $result->toArray());
        $this->assertNull($adapter->option('invalid_value'));
    }

    public function test_option_with_column_override()
    {
        $contact = Contact::create([ 'name' => 'Ebba Ullrich', 'address' => '5842 Easy Centre', 'city' => 'Colorado, Virginia', 'zip' => '23232-1470', 'phone' => '(434) 336-1206', 'email' => 'xebbax@example.com' ]);

        $adapter = new ModelAdapter(Contact::class, 'email');
        $result = $adapter->option($contact->email);
        $this->assertNotNull($result);
        $this->assertEquals($contact->toArray(), $result->toArray());
        $this->assertNull($adapter->option('invalid_value'));
    }

    public function test_value()
    {
        $user = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class);
        $this->assertEquals($user->id, $adapter->value($user));
    }

    public function test_value_with_override()
    {
        $user = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class, 'email');
        $this->assertEquals($user->email, $adapter->value($user));
    }

    public function test_renderOption()
    {
        $user = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class);
        $this->assertEquals('Jane Doe', (string) $adapter->renderOption($user));
    }

    public function test_renderOption_with_override()
    {
        $user = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ModelAdapter(User::class, null, 'email');
        $this->assertEquals('jane@example.com', (string) $adapter->renderOption($user));
    }
}
