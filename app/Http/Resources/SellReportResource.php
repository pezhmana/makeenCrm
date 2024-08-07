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
        $from = $request->from;
        $to = $request->to;
        if(is_null($request->from)){
            $from = '2022-01-01';
        }
        if(is_null($to)){
            $to = '2026-01-01';
        }
        return[
            'id'=>$this->id,
            'product_name'=>$this->name,
            'teacher_name'=>Teacher::find($this->teacher_id)->name,
            'order_count'=>$this->orders()->whereBetween('created_at', [$from, $to])->count(),
            'order_sum'=>$this->orders()->whereBetween('created_at', [$from, $to])->sum('sum')

        ];
    }
}
