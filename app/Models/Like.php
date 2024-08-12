<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable=[
        'which',
        'comment_id'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function Comment()
    {
        return $this->belongsToMany(Comment::class);
    }

}


