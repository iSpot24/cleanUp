<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Cleaner
 * @package App\Models
 */
class Cleaner extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone_number'];

    /**
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * @return BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\City','city_cleaners', 'cleaner_id', 'city_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo('App\Models\Booking', 'city_id', 'id');
    }


}
