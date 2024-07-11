<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    protected $fillable =[
      'name',
    ];

   public function posts(){
       return $this->morphedByMany(Post::class,'labelables')->withTimestamps();
   }

   public function products(){
       return $this->morphedByMany(Product::class,'labelables')->withTimestamps();
   }

    public function users(){
        return $this->morphedByMany(User::class,'labelables')->withTimestamps();
    }
}
