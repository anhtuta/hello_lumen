<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    // we'll allow the web app to fill data to any column on the table
    // protected $guarded = [];

    protected $table = 'song';

    const CREATED_AT = 'created_date';

    // The attributes that should be visible in arrays
    // protected $visible = ['title', 'artist', 'formatCreatedDate', 'createdDate'];

    // The attributes that should be hidden for arrays
    // We can use either visible or hidden
    protected $hidden = ['file_name', 'image_name', 'image_url', 'is_deleted'];

    protected $appends = array('formatCreatedDate', 'fileName', 'imageName', 'imageUrl');

    public function getFormatCreatedDateAttribute()
    {
        // Using $this->created_date is fine, but I don't know why
        // using $this->file_name is not working???
        $date = strtotime($this->attributes['created_date']);
        return date('Y/m/d', $date);
    }

    // Because Lumen will convert object to JSON in snake_case,
    // we have to create getter to convert it to camelCase
    // (Using $hidden to hide snake_case attributes)
    // I don't know any other solution to convert to camelCase!!!
    public function getFileNameAttribute() {
        return $this->attributes['file_name'];
    }

    public function getImageNameAttribute() {
        return $this->attributes['image_name'];
    }

    public function getImageUrlAttribute() {
        return $this->attributes['image_url'];
    }
}
