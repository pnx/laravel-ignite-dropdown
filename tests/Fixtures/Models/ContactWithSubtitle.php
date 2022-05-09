<?php

namespace Tests\Fixtures\Models;

use Ignite\Contracts\HasSubtitle;

class ContactWithSubtitle extends Contact implements HasSubtitle
{
    public $table = 'contacts';

    public function getSubtitle() : string
    {
        return sprintf("Email: %s, Phone: %s", $this->email, $this->phone);
    }
}
