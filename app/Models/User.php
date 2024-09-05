<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia

{
<<<<<<< HEAD
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia,SoftDeletes;
=======
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia ,SoftDeletes;
>>>>>>> 53a7ba753775a5b5a5d63c0a970ace637da4ca85

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'new_password',
        'phone',
        'gender',
        'birth_date',
        'last_name',
        'code'

    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'full_name',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function userProduct()
    {
        return $this->hasManyThrough(Order::class, Product::class);
    }

    public function orderProducts(): array
    {
        $data = [];
        $orders = $this->orders()->orderByDesc('created_at')->get();

        foreach ($orders as $order) {
            $products = $order->product()
            ->withAvg('comments', 'rating')
                ->with('categories')
                ->withCount('orders')
                ->get();

            foreach ($products as $product) {
                $teacher = Teacher::find($product->teacher_id);
                if ($teacher) {
                    $product->teacher_name = $teacher->name;
                }
                $data[] = $product;
            }
        }

        return $data;
    }


    public function Labels()
    {
        return $this->morphToMany(Label::class, 'labelables')->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->last_name;
    }

    protected $appends=[
        'full_name'
    ];

    public function labelProducts(): array
    {
        $data = [];
        $user = auth()->user();
        $label = Label::where('name','favorite')->first()->id;
        $favoriteLabels = DB::table('labelables')
            ->where('user_id',$user->id)
            ->where('label_id',$label)->get()->toArray();
        foreach ($favoriteLabels as $favoriteLabel) {
            $product = Product::withAvg('comments', 'rating')
                ->with('categories')
                ->withCount('orders')
                ->find($favoriteLabel->labelables_id);
            if ($product) {
                $teacher = Teacher::find($product->teacher_id);
                if ($teacher) {
                    $product->teacher_name = $teacher->name;
                }
                $data[] = $product;
            }
        }

        return $data;
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function videos(){
        return $this->belongsToMany(video::class)->withTimestamps();
    }

    public function compelete(){
        $complete=0;
        $unComplete=0;
        $user=auth()->user();
        $orders =$user->orders()->get();
        $products=[];
        foreach ($orders as $order) {
            $products[] = $order->product;
        }
        foreach ($products as $product){
           $video_count =  $product->videos()->count();
//           dd($pr);
           $uservideo_count= DB::table('user_video')
               ->where('product_id',$product->id)->where('user_id',$user->id)
               ->count();
//           dd($uservideo_count);
           if($uservideo_count == $video_count ){
               $complete +=1;
           }else{
               $unComplete +=1;
           }

        }
        return [
            'complete'=>$complete,
            'uncomplete'=>$unComplete];
    }


}
