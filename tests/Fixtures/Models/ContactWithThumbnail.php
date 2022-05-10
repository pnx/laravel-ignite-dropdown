<?php

namespace Tests\Fixtures\Models;

use Ignite\Contracts\HasThumbnail;

class ContactWithThumbnail extends Contact implements HasThumbnail
{
    public $table = 'contacts';

    public function renderThumbnail() : string
    {
        // Fake some avatar service (like gravatar)
        return sprintf('<img src="http://exampleimageservice.com/id/%s" />', md5($this->email));
    }
}
