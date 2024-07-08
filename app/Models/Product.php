<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'price',
        'type',
        'status',
        'category_id'
    ];


    public function video()
    {
        return $this->hasmany(video::class);
    }

    public function comments(){
        return $this->morphMany(comment::class,'commentable');
    }

    public function order()
    {
        return $this->belongsTo(order::class);
    }

    public function teacher(){
        return $this->hasOne(teacher::class);
    }


//    public function category()
//    {
//        return $this->belongsTo(category::class);


        public  function categories()
        {
            return $this->morphToMany(Category::class, 'categoryable');

        }
    }

