<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepositCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($deposit){
                return [
                    'id' => $deposit->id,
                    'description' => $deposit->description,
                    'total' => $deposit->total,
                    'date' => Carbon::parse($deposit->date)->format('d-m-Y'),
                ];
            })
        ];
    }
}
