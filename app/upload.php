<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class upload extends Model
{
    //
    public $table = 'upload';
    protected $primaryKey = 'id';
    protected $dates = ['created_at', 'updated_at'];

    protected $filltable = [
        'id', 'filename'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
