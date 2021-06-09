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

    // Update offset:
    // Đầu tiên tạo 1 file tạm, sau đó đọc từng dòng của file gốc cần update offset
    // ghi sang file tạm đó, và check nếu dòng nào có offset thì update giá trị đó.
    // Xong xuôi thì xóa file gốc đi, rồi đổi tên file tạm thành tên file gốc đó
    public function updateOffset(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }
        if (!$request->offset || $request->offset == 0) {
            return;
        }

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $originalFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;

        // create a temp file
        $tempFileName = 'temp_' . time();
        $tempFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $tempFileName;
        $tempFile = fopen($tempFilePath, 'w');

        // read every line from original file, then write them to temp file.
        // If found a line that contains offset, update it
        if ($originalFile = fopen($originalFilePath, "r")) {
            while(!feof($originalFile)) {
                $line = fgets($originalFile);
                if(str_contains($line, '[offset:')) {
                    $line = "[offset:" . $request->offset . "]" . PHP_EOL;
                }
                fwrite($tempFile, $line);
            }
            fclose($originalFile);
            fclose($tempFile);
        }

        // delete original file
        if(!unlink($originalFilePath)) {
            return response()->json(["code" => 400000, "message" => "Error: Cannot delete a file!"], 400);
        }

        // rename temp file to original file name
        rename($tempFilePath, $originalFilePath);

        return 'Updated!';
    }
}
