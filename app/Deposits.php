<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    /**
 * The database table used by the model
 *
 * @var string
 */
    protected $table = 'deposits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'total', 'date'
    ];

    public function quotes()
    {
        return $this->belongsToMany('App\Quote', 'payments', 'deposit_id','quote_id');
    }
}
