<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageOrder extends Model
{

    protected $table = 'll_landing_page_order';

    const CREATED_AT = 'order_date';

    // protected $appends = array('formatCreatedDate');

    // public function getFormatCreatedDateAttribute()
    // {
    //     $date = strtotime($this->attributes['created_date']);
    //     return date('Y/m/d', $date);
    // }

}
