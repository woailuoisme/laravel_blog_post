<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        'id'=>$this->id,
        'user_id'=> $this->user_id,
        'total_price'=> $this->total_price,
        'products_count'=> $this->productsCount(),
        'products'=>  OrderProductResource::collection($this->products)
    ];;
    }
}
