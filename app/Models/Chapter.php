<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id',
        'title'
    ];

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function videos(){
        return $this->hasMany(video::class);
    }

    public function getVideoAttribute(){
        $videos=[];
       $video =$this->videos()->get();
       foreach ($video as $v){
           $videos[]=[
               'id'=>$v->id,
             'title'=>$v->title
           ];
       }
       return $videos;

    }

    protected $appends=[
        'video'
    ];
}
