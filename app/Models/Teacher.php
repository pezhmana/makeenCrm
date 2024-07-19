<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable=[
        'id' ,
        'name' ,
        'expertise' ,
        'description' ,
        'instagram' ,
        'telegram',
        'message'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
