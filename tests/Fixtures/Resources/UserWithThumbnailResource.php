<?php

namespace Tests\Fixtures\Resources;

class UserWithThumbnailResource extends UserWithTitleResource
{
    public function thumbnail()
    {
        return '<img src="https://gravatar.com/8187d1e9fb0" />';
    }
}
