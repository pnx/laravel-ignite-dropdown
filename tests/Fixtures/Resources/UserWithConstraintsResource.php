<?php

namespace Tests\Fixtures\Resources;

class UserWithConstraintsResource extends UserResource
{
    public static $search = [ 'name', 'email' ];

    public function defaultQuery($query)
    {
        return $query->where('email', 'LIKE', '%@example.net');
    }
}
