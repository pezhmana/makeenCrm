<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->user_id);
        $product = Product::find($this->product_id);
        return [
            'id'=>$this->id,
            'date'=>$this->created_at,
            'user_name'=>$user->name.' '.$user->last_name,
            'product_name'=>$product->name,
            'teacher_name'=>Teacher::where('id',$product->teacher_id)->first()->name,
            'price'=>$this->sum
        ];
    }
}
