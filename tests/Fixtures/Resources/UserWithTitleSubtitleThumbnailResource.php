<?php

namespace Tests\Fixtures\Resources;

class UserWithTitleSubtitleThumbnailResource extends UserWithTitleResource
{
    public function subtitle()
    {
        return "Email: {$this->email}";
    }

    public function thumbnail()
    {
        return sprintf('<img src="https://gravatar.com/%s" />', md5($this->id));
    }
}
