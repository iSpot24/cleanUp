<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Customer
 * @package App\Models
 */
class Customer extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone_number'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo('App\Models\Booking', 'city_id', 'id');
    }

}
