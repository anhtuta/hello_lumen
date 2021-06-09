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
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            return response()->json(["code" => 404003, "message" => "Lyric doesn't exist!"], 404);
        } else {
            $type = 'text/plain';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($filePath, 200, $headers);
            return $response;
        }
    }
}