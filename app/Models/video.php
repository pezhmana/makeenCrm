<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class video extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable=[
        'chapter_id',
        'title',
        'url'
    ];

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }
}
