<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Teacher extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia,SoftDeletes;
    protected $fillable=[
        'id' ,
        'name' ,
        'expertise' ,
        'description' ,
        'instagram' ,
        'telegram',
        'message'
    ];
protected $dates = ['deleted_at'];
    protected $appends=[
        'teacher_image'
    ];

    protected $hidden=[
        'media'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function getTeacherImageAttribute(){
        $image=  $this->getMedia('teacher.image')->last();
        if($image){
            $Url = $image->getUrl();
        }else{
            $Url=null;
        }
        return $Url;
    }
}
