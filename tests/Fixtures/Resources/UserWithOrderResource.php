<?php

namespace Tests\Fixtures\Resources;

class UserWithOrderResource extends UserResource
{
    public static $search = [ 'name' ];

    public function defaultQuery($query)
    {
        return $query->orderBy('name', 'desc');
    }
}
