<?php

namespace Tests\Fixtures\Models;

use Tests\Database\Factories\UserFactory;

use Ignite\Contracts\HasTitle;

use Illuminate\Database\Eloquent\Model;

class UserWithTitle extends Model implements HasTitle
{
    public $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email'
    ];

    public function getTitle() : string
    {
        return sprintf("%s (%s)", $this->name, $this->email);
    }
}
