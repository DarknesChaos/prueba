<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuoteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($quote){
                return [
                    'id' => $quote->id,
                    'customer_name' => $quote->customer_name,
                    'customer_email' => $quote->customer_email,
                    'customer_address' => $quote->customer_address,
                    'car_brand' => $quote->car_brand,
                    'car_model' => $quote->car_model,
                    'car_year' => $quote->car_year,
                    'car_price' => $quote->car_price,
                    'date' => Carbon::parse($quote->date)->format('d-m-Y'),
                    'isPaid' => ($quote->isPaid != 0) ? true : false,
                    'deposits' => $quote->deposits,
                ];
            })
        ];
    }
}
