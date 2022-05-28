<?php

namespace App\Http\Services;

class LyricService
{
    public static function saveLyricFile($file)
    {
        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $file->move($lyricFolder, $file->getClientOriginalName());
    }

    public static function saveLyricFileFromUrl($url = '', $filename = '')
    {
        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        return file_put_contents($lyricFolder . DIRECTORY_SEPARATOR . $filename, file_get_contents($url));
    }
}
