<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceUrl extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'device_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['short_code', 'device_type', 'long_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'short_code', 'created_at', 'updated_at'];

    /**
     * Get the short url that owns the device url
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shortUrl()
    {
        return $this->belongsTo('App\Models\ShortUrl', 'short_code', 'short_code');
    }
}
