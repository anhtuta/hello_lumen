<?php

namespace App\Http\Dto;

use Illuminate\Contracts\Support\Arrayable;

class FileDto implements Arrayable
{
    public $name;
    public $last_modified;
    public $size;

    public function __construct($name = '', $last_modified = '', $size = '')
    {
        $this->name = $name;
        $this->last_modified = $last_modified;
        $this->size = $size;
    }

    public function toArray()
    {
        return array(
            "name" => $this->name,
            "last_modified" => $this->last_modified,
            "size" => $this->size
        );
    }
}
