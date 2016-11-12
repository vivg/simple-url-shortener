<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'short_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['short_code'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['short_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'updated_at'];


    /**
     * get short url attribute
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getShortUrlAttribute()
    {
        return url($this->short_code);
    }


    /**
     * get the long urls for the short url
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urls()
    {
        return $this->hasMany('App\Models\DeviceUrl', 'short_code', 'short_code');
    }

}