<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'full_name'=>$this->name.' '.$this->last_name,
            'phone'=>$this->phone,
            'orders_count' => $this->orders->count(),
            'orders_sum'=>$this->orders->sum('sum'),
            'latest_order'=>$this->orders->sortByDesc('created_at')->first()->created_at,
        ];
    }
}
