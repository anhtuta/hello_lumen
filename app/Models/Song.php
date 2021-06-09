<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Song extends Model
{
    // we'll allow the web app to fill data to any column on the table
    protected $guarded = [];

    protected $table = 'song';

}
