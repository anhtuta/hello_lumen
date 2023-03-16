<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Dto\FileDto;
use App\Http\Services\LyricService;
use Illuminate\Http\Request;

define("MAX_LYRIC_SIZE", 1 * 1024 * 1024);  // 1MB

class AdminLyricController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Upload a lyric file to lyric folder. If this file exists, the override
    public function uploadLyricFile(Request $request)
    {
        $result = new Result();
        $file = $request->file('file');

        if (!isset($file)) {
            return response()->json([
                "code" => 404000,
                "message" => "Error: file cannot be empty! Please insert file in body's request"
            ], 404);
        }

        if ($file->getSize() > MAX_LYRIC_SIZE) {
            return response()->json(["code" => 401001, "message" => "Error: Max lyric file size is 1MB"], 400);
        }

        LyricService::saveLyricFile($file);
        $result->res("Lyric has been uploaded and saved in server!");
        return $this->jsonResponse($result);
    }

    /**
     * Currently don't have pagination yet! This function will return all files in lyric folder
     */
    public function searchLyricFiles(Request $request)
    {
        $result = new Result();
        $fileDtoList = array();
        $name = $request->name;

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $files = scandir($lyricFolder);
        $total = 0;
        foreach ($files as $filename) {
            if (str_ends_with($filename, '.trc') || str_ends_with($filename, '.lrc')) {
                // stripos('abc', 'abc') = 0 => bị sai
                if (isset($name) && $name != '' && !str_contains(strtolower($filename), strtolower($name))) continue;
                $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $filename;
                $total++;
                // $dateModified = date('Y-m-d H:i', filemtime($filePath));
                // $size = UtilsService::humanFilesize(filesize($filePath));
                $fileDto = new FileDto($filename, filemtime($filePath), filesize($filePath));
                array_push($fileDtoList, $fileDto);
            }
        }

        $meta = array("total" => $total);
        $result->successRes($fileDtoList, $meta);
        return $this->jsonResponse($result);
    }
}
