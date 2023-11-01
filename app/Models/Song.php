<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Note: trước đây 1 số field dùng camelCase vì code theo base cũ từ Java Spring,
 * nhưng Lumen thì lại dùng snake_case nên phải convert từ snake_case -> camelCase
 * bằng method chẳng hạn getImageUrlAttribute().
 * Tuy nhiên sau 1 thời gian thấy việc convert này lằng nhắng quá nên từ giờ quyết
 * định dùng toàn bộ snake_case cho các field của model để trùng với cột trong database
 */
class Song extends Model
{
    // we'll allow the web app to fill data to any column on the table
    // protected $guarded = [];

    protected $table = 'll_song';

    const CREATED_AT = 'created_date';

    // The attributes that should be visible in arrays
    // protected $visible = ['title', 'artist', 'formatCreatedDate', 'createdDate'];

    // The attributes that should be hidden for arrays
    // We can use either visible or hidden
    protected $hidden = ['is_deleted'];

    // Thêm các customField, cần định nghĩa các field này bằng method getCustomFieldNameAttribute
    protected $appends = array('formatCreatedDate');

    // Dù trong db khai báo cột này kiểu INT, nhưng Lumen vẫn ép sang kiểu String, cần phải ép lại sang int
    protected $casts = [
        'song_of_the_year' => 'integer',
    ];

    // Because Lumen will convert object to JSON in snake_case,
    // we have to create getter to convert it to camelCase
    // (Using $hidden to hide snake_case attributes)
    // I don't know any other solution to convert to camelCase!!!
    public function getFormatCreatedDateAttribute()
    {
        // Using $this->created_date is fine, but I don't know why
        // using $this->file_name is not working???
        $date = strtotime($this->attributes['created_date']);
        return date('Y/m/d', $date);
    }
}
