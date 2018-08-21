<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'title', 'content', 'filename',
        'created_at',
    ];
}
