<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
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
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty! Please insert file in body's request"], 404);
        }

        if($file->getSize() > MAX_LYRIC_SIZE) {
            return response()->json(["code" => 401001, "message" => "Error: Max lyric file size is 1MB"], 400);
        }

        LyricService::saveLyricFile($file);
        $result->res(200000, "Lyric has been uploaded and saved in server!");
        return response()->json($result);
    }
}
