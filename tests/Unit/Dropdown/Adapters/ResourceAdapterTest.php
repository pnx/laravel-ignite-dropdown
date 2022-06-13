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
            ["name" => "Hasad Yang","email" => "hasadyang3669@hotmail.com","phone" => "1-741-219-7257","address" => "6190 Semper Road","city" => "Krems an der Donau, Austria","zip" => "874293"],
            ["name" => "Gisela Tate","email" => "giselatate1439@google.edu","phone" => "1-828-925-7342","address" => "759-2777 Nibh. Rd.","city" => "Linz, Austria","zip" => "351172"],
            ["name" => "Nash Holcomb","email" => "nashholcomb@hotmail.com","phone" => "1-523-175-9889","address" => "Ap #165-1061 Sit Avenue","city" => "Chile Chico, Aisén","zip" => "35030"],
            ["name" => "Akeem Francis","email" => "akeemfrancis1490@google.com","phone" => "(893) 202-9108","address" => "Ap #701-6880 Cum St.","city" => "Naga, Visayas","zip" => "56-66"],
            ["name" => "Kennedy Turner","email" => "kennedyturner6044@protonmail.net","phone" => "(416) 204-1616","address" => "711-3034 Diam. Street","city" => "Aizwal, Mizoram","zip" => "7855-5092"],
            ["name" => "Shad Brady","email" => "shadbrady6019@hotmail.org","phone" => "(411) 927-5922","address" => "Ap #494-8640 Eros. Road","city" => "Iquitos, Loreto","zip" => "368892"],
            ["name" => "Dacey Rasmussen","email" => "daceyrasmussen@outlook.ca","phone" => "1-566-524-2915","address" => "539-6169 In St.","city" => "Launceston, Tasmania","zip" => "368674"],
            ["name" => "Raphael Estrada","email" => "raphaelestrada3992@yahoo.org","phone" => "1-335-501-5466","address" => "2682 Elit Road","city" => "Märsta, Stockholms län","zip" => "4232"],
            ["name" => "Germane Morgan","email" => "germanemorgan2700@outlook.net","phone" => "1-121-701-8270","address" => "Ap #651-9106 Arcu. Av.","city" => "Puerto Carreño, Vichada","zip" => "25423"],
            ["name" => "Amal Frank","email" => "amalfrank285@hotmail.couk","phone" => "(937) 201-8527","address" => "8066 Laoreet, St.","city" => "Belfast, Ulster","zip" => "7143"]
        ]);
    }

    public function test_options()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ResourceAdapter('user-with-email-search');

        $this->assertEquals(User::all()->keyBy('id'), $adapter->options('', null));

        // Find John by email.
        $result = $adapter->options('123', null);
        $this->assertCount(1, $result);
        $this->assertEquals($user1->id, $result->first()->id);

        $result = $adapter->options('Doe', null);
        $this->assertCount(2, $result);
        $this->assertEquals($user1->id, $result[$user1->id]->id);
        $this->assertEquals($user2->id, $result[$user2->id]->id);

        $result = $adapter->options('Jane D', null);
        $this->assertCount(1, $result);
        $this->assertEquals($user2->id, $result->first()->id);
    }

    public function test_options_with_constraints()
    {
        $this->seedUsers();

        $adapter = new ResourceAdapter('user-with-constraints');
        $expected = User::where('name', 'Frances Goodwin')->get()->keyBy('id');
        $result = $adapter->options('Frances', null);

        $this->assertCount(1, $result);
        $this->assertEquals($expected, $result);
    }

    public function test_options_with_order()
    {
        $this->seedUsers();

        $user1 = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);
        $user2 = User::create([ 'name' => 'Jane Doe', 'email' => 'jane@example.com' ]);

        $adapter = new ResourceAdapter('user-with-order');
        $expected = User::orderBy('name', 'desc')->get()->keyBy('id');

        $this->assertEquals($expected, $adapter->options('', null));
    }

    public function test_options_key()
    {
        $user = User::create([ 'name' => 'John Doe', 'email' => 'john.123@example.com' ]);

        $adapter = new ResourceAdapter('user');
        $options = $adapter->options('', null);
        $result = $options->get($user->id);
        $this->assertNotNull($result);
        $this->assertEquals($user->toArray(), $result->toArray());
        $this->assertNull($options->get('invalid_value'));
    }

    public function test_options_key_custom_id()
    {
        $contact = Contact::create([ 'name' => 'Ebba Ullrich', 'address' => '5842 Easy Centre', 'city' => 'Colorado, Virginia', 'zip' => '23232-1470', 'phone' => '(434) 336-1206', 'email' => 'xebbax@example.com' ]);

        $adapter = new ResourceAdapter('contact-with-email-id');
        $options = $adapter->options('', null);
        $result = $options->get($contact->email);
        $this->assertNotNull($result);
        $this->assertEquals($contact->toArray(), $result->toArray());
        $this->assertNull($options->get('invalid_value'));
    }

    public function test_first()
    {
        $user1 = User::create([ 'name' => 'First', 'email' => 'first@example.com' ]);
        $user2 = User::create([ 'name' => 'Second', 'email' => 'second@example.com' ]);

        $adapter = new ResourceAdapter('user');

        $this->assertEquals('First', $adapter->first()->name);
    }

    public function test_value()
    {
        $user = User::create([ 'name' => 'Arthur Pearce', 'email' => 'arthur@pearce.com' ]);

        $adapter = new ResourceAdapter('user');
        $this->assertEquals($user->id, $adapter->value($user));
    }

    public function test_value_with_custom_id()
    {
        $contact = Contact::create([ "name" => "Buffy Gross", "address" => "Ap #946-4820 Iaculis Rd.", "city" => "Itanagar, Piura", "zip" => "48980", "phone" => "1-424-835-7727", "email" => "in.cursus@aol.edu"]);

        $adapter = new ResourceAdapter('contact-with-email-id');
        $this->assertEquals($contact->email, $adapter->value($contact));
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
