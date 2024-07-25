<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Spatie\MediaLibrary\HasMedia;
>>>>>>> 45c2223632ead2a1e24d3a0cf0fc260c4b32357d
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
<<<<<<< HEAD
    use HasFactory,InteractsWithMedia;
=======
    use HasFactory ,InteractsWithMedia;
>>>>>>> 45c2223632ead2a1e24d3a0cf0fc260c4b32357d
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

    public function Labels()
    {
        return $this->morphToMany(Label::class, 'labelables');
    }

    public function getPostimageAttribute()
    {
       $image = $this->getMedia('post'.'image')->last();
       if($image){
           $Url = $image->getUrl();
       }else{
           $Url = null;
       }
       return $Url;

    }
}
