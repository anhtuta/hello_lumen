<?php

namespace App\Http\Dto;

use Illuminate\Contracts\Support\Arrayable;

class SongMeta implements Arrayable
{
    public $title;
    public $artist;

    public function __construct($title = '', $artist = '')
    {
        $this->title = $title;
        $this->artist = $artist;
    }

    public function toArray()
    {
        return array(
            "title" => $this->title,
            "artist" => $this->artist,
        );
    }
}
