<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'price',
        'type',
        'status',
        'discount_price',
        'category_id',
    ];


    public function video()
    {
        return $this->hasmany(video::class);
    }

    public function comments(){
        return $this->morphMany(comment::class,'commentable');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function teacher(){
        return $this->hasOne(teacher::class);
    }





    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');

    }







    public function Labels()
    {
        return $this->morphToMany(Label::class, 'labelables');

    }

    public function ratings(){
        return $this->hasMany(rating::class);
    }
}

