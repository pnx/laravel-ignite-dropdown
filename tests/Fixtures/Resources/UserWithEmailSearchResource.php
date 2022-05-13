<?php

namespace Tests\Fixtures\Resources;

class UserWithEmailSearchResource extends UserResource
{
    public static $search = [ 'name', 'email' ];
}
