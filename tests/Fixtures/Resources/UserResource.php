<?php

namespace Tests\Fixtures\Resources;

use Ignite\Resource;
use Tests\Fixtures\Models\User;

class UserResource extends Resource
{
    public static $model = User::class;
}
