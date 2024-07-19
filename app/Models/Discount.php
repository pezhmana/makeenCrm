<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'Amount',
        'Code' ,
        'From' ,
        'To' ,
        'Percent' ,
        'Category_id' ,
        'order_id' ,
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
