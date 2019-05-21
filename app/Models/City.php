<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class City
 * @package App\Models
 */
class City extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'zip_code'];

    /**
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * @return BelongsToMany
     */
    public function cleaners() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\Cleaner', 'city_cleaners', 'cleaner_id', 'city_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo('App\Models\Booking', 'city_id', 'id');
    }

    /**
     * @return string
     */
    public function getRouteKeyName() {
        return 'city';
    }
}
