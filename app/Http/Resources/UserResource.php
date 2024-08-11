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
        $orders_count = $this->orders ? $this->orders->count() : 0;
        $orders_sum = $this->orders ? $this->orders->sum('sum') : 0;
        $order = $this->orders  && $this->orders->isNotEmpty() ? $this->orders->sortByDesc('created_at')->first()->created_at : null;
        return [
            'id'=>$this->id,
            'full_name'=>$this->name.' '.$this->last_name,
            'phone'=>$this->phone,
            'orders_count' => $orders_count,
            'orders_sum'=>$orders_sum,
            'latest_order'=>$order,
        ];
    }
}
