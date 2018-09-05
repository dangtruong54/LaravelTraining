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

    public function user(){
        return $this->hasOne('App\user', 'id', 'user_id');
    }
}
