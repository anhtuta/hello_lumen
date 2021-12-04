<?php

namespace App\Http\Services;

class LyricService
{
    public static function saveLyricFile($file)
    {
        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $file->move($lyricFolder, $file->getClientOriginalName());
    }
}
