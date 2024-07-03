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
        'status'
    ];
    public function video()
    {
        return $this->hasmany(video::class);
    }
}

