<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'user_id',
        'short',
        'description',
        'time',
        ];

    public function comments(){
        return $this->morphMany(comment::class,'commentable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->morphToMany(Category::class , 'categoryable');
    }
}
