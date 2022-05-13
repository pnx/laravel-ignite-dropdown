<?php

namespace Tests\Fixtures\Resources;

use Ignite\Resource;
use Tests\Fixtures\Models\Contact;

class ContactResource extends Resource
{
    public static $model = Contact::class;
}
