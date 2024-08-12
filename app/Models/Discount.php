<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'amount',
        'code' ,
        'from' ,
        'to' ,
        'percent' ,
        'name'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function getStatusDiscountAttribute(){
        return is_null($this->deleted_at);
    }
    protected $appends=[
      'Status_discount'
    ];
}
