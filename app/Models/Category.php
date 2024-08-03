<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable=[
      'name',
        'categoryable_id',
        "categoryable_type"
    ];

    protected $hidden=[
        'media'
    ];


    public function getCategoryIconAttribute(){
        $image=  $this->getMedia('category.icon')->last();
        if($image){
            $Url = $image->getUrl();
        }else{
            $Url=null;
        }
        return $Url;
    }

    protected $appends=[
        'category_icon'
    ];
    public function products(){
        return $this->morphedByMany(Product::class , 'categoryable');
    }

    public function posts(){
        return $this->morphedByMany(Post::class , 'categoryable');
    }

    public function category(){
        return $this->MorphToMany(Category::class , 'categoryable');
    }

    public function categories(){
        return $this->morphedByMany(Category::class , 'categoryable');
    }

    public function ProductOrder(): array
    {
        $data = [];
        $products = $this->products()->get();
        foreach ($products as $product) {
            $data[] = $product->orders->count();
        }
        return $data;
    }

}
