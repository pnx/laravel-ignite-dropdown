<?php

namespace Ignite\Contracts;

interface HasThumbnail
{
    /**
     * render the object's thumbnail.
     *
     * NOTE: Make sure to escape html from user input as this
     * string is rendered unescaped in views.
     *
     * @return string
     */
    public function renderThumbnail() : string;
}
