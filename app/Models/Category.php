<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable=[
      'name',

        'categoryable_id',
        "categoryable_type"
    ];

    public function products(){
        return $this->morphedByMany(Product::class , 'categoryable');
    }

    public function posts(){
        return $this->morphedByMany(Post::class , 'categoryable');
    }

    public function category(){
        return $this->MorphToMany(Category::class , 'categoryable');
    }

    public function categories(){
        return $this->morphedByMany(Category::class , 'categoryable');
    }


}
