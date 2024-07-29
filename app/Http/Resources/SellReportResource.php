<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return[
            'id'=>$this->id,
            'product_name'=>$this->name,
            'teacher_name'=>Teacher::where('id',$this->teacher_id)->first()->name,
            'order_count'=>$this->orders()->whereBetween('created_at', [$request->from, $request->to])->count(),
            'order_sum'=>$this->orders()->whereBetween('created_at', [$request->from, $request->to])->sum('sum')

        ];
    }
}
