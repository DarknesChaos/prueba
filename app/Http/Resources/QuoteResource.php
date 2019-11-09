<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'car_brand' => $this->car_brand,
            'car_model' => $this->car_model,
            'car_year' => $this->car_year,
            'car_price' => $this->car_price,
            'date' => Carbon::parse($this->date)->format('d-m-Y'),
        ];
    }
}
