<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'discount_price',
        'category_id',
    ];
    protected $appends=[
        'product_image'
    ];

    protected $hidden=[
      'media'
    ];

    public function getProductImageAttribute(){
        $image=  $this->getMedia('product.image')->last();
        if($image){
            $Url = $image->getUrl();
        }else{
            $Url=null;
        }
        return $Url;
    }


    public function video()
    {
        return $this->hasmany(video::class);
    }

    public function comments(){
        return $this->hasMany(comment::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function teacher(){
        return $this->belongsTo(teacher::class);
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

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function videos(){
        return $this->hasManyThrough(video::class,Chapter::class);
    }
}

