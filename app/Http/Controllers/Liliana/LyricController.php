<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LyricController extends Controller
{
    public function getLyricByFileName(Request $request)
    {
        if (!$request->file) {
            return 'Error: file cannot be empty!';
        }

        return $request->file;

        // $lyricFolder = env('LL_LYRIC_FOLDER', '');
        // $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;
        // if (!file_exists($filePath)) {
        //     return "File doesn't exist!";
        // } else {
        //     $fileContent = file_get_contents($filePath);
        //     return $fileContent;
        // }
    }
}