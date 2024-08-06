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
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia,SoftDeletes;

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
        $orders = $this->orders()->get();
        foreach ($orders as $order) {
            $data[] = $order->product;
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
            $product = Product::find($favoriteLabel->labelables_id);
            if ($product) {
                $data[] = $product;
            }
        }

        return $data;
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

}
