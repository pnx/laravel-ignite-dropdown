<?php

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'zip',
        'city',
        'phone',
        'email'
    ];
}
