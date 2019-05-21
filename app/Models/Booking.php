<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Booking
 * @package App\Models
 */
class Booking extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['cleaner_id', 'date', 'city_id', 'customer_id'];

    public function city(): HasOne
    {
        return $this->hasOne('App\Models\City', 'city_id', 'id');
    }

    public function cleaner(): HasOne
    {
        return $this->hasOne('App\Models\Cleaner', 'cleaner_id', 'id');
    }

    public function customer(): HasOne
    {
        return $this->hasOne('App\Models\Customer', 'customer_id', 'id');
    }

}
