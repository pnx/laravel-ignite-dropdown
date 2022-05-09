<?php

namespace Ignite\Contracts;

interface HasTitle
{
    /**
     * Get the objects subtitle.
     *
     * @return string
     */
    public function getTitle() : string;
}
