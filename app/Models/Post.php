<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory ,InteractsWithMedia;
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
