<?php

namespace Tests\Fixtures\Resources;

class UserWithSubtitleResource extends UserWithTitleResource
{
    public function subtitle()
    {
        return "Email: {$this->email}";
    }
}
