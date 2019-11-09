<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'quotes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name', 'customer_email', 'customer_address', 'car_brand', 'car_model', 'car_year', 'car_price', 'date', 'isPaid'
    ];

    public function deposits()
    {
        return $this->belongsToMany('App\Deposits', 'payments', 'quote_id', 'deposit_id');
    }
}
