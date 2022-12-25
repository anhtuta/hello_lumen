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
            return response()->json(["code" => 404000, "message" => "Error: \"file\" param cannot be empty!"], 404);
        }

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            return "Lyric doesn't exist!";
        } else {
            $type = 'text/plain';
            $headers = ['Content-Type' => $type];
            return new BinaryFileResponse($filePath, 200, $headers);
        }
    }

    public function downloadLyricFile(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: \"file\" param cannot be empty!"], 404);
        }

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            return "Lyric doesn't exist!";
        } else {
            return response()->download($filePath);
        }
    }

    // Update offset:
    // Đầu tiên tạo 1 file tạm, sau đó đọc từng dòng của file gốc cần update offset
    // ghi sang file tạm đó, và check nếu dòng nào có offset thì update giá trị đó.
    // Xong xuôi thì xóa file gốc đi, rồi đổi tên file tạm thành tên file gốc đó
    public function updateOffset(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404001, "message" => "Error: \"file\" param cannot be empty!"], 400);
        }
        if (!$request->offset || $request->offset == 0) {
            return response()->json(["code" => 404002, "message" => "Error: \"offset\" param is not valid!"], 400);
        }

        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $originalFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $request->file;

        // create a temp file
        $tempFileName = 'temp_' . time();
        $tempFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $tempFileName;
        $tempFile = fopen($tempFilePath, 'w');
        $content = "";
        $isContainsOffset = false;

        // Travel to every line of file, if found a line that contains offset, update it
        // If travel to the end and found no line contains offset, create offset line
        if ($originalFile = fopen($originalFilePath, "r")) {
            while (!feof($originalFile)) {
                $line = fgets($originalFile);
                if (str_contains($line, '[offset:')) {
                    $line = "[offset:" . $request->offset . "]" . PHP_EOL;
                    $isContainsOffset = true;
                }
                $content .= $line;
            }

            if (!$isContainsOffset) {
                // There is no line that contains offset, create offset line
                $content = "[offset:" . $request->offset . "]" . PHP_EOL . $content;
            }

            fwrite($tempFile, $content);
            fclose($originalFile);
            fclose($tempFile);
        }

        // delete original file
        if (!unlink($originalFilePath)) {
            return response()->json(["code" => 400000, "message" => "Error: Cannot delete a file!"], 400);
        }

        // rename temp file to original file name
        rename($tempFilePath, $originalFilePath);

        return 'Updated!';
    }

    /**
     * Solution này cũng giống Java: edit file xong bị lỗi tên file UTF-8
     * Ref: https://youtu.be/l1eDU1U49Fw
     * Update: cái lỗi này là do chạy code Java, PHP để thêm date ở local, sau đó upload lyric file
     * lên hosting server. Nếu cứ chạy code php trên hosting server để sửa file thì sẽ ko bị lỗi nữa!
     */
    public function addDateToLyric(Request $request)
    {
        // $lyricFolder = '/Users/anhtu/Documents/MyProjects/Lyrics';
        $lyricFolder = env('LL_LYRIC_FOLDER', '');
        $files = scandir($lyricFolder);
        $cnt = 0;
        $total = 0;
        foreach ($files as $filename) {
            if (str_ends_with($filename, '.trc') || str_ends_with($filename, '.lrc')) {
                $total++;
                // $dateModified = filemtime($lyricFolder . DIRECTORY_SEPARATOR . $filename);
                // print_r($filename . ' (' . date('Y-m-d', $dateModified) . '), ');
                if ($this->checkAndAddDateToLyric($lyricFolder, $filename)) {
                    $cnt++;
                }
            }
        }

        return 'Added date to ' . $cnt . '/' . $total . ' lyric files';
    }

    /**
     * Check if a lyric file contains date or not.
     * If not: add date and return true.
     * If contains: no nothing and return false.
     * Note: solution này cũng ko hoạt động được: file bị edit sẽ bị lỗi (có ký tự BOM)
     */
    private function checkAndAddDateToLyric($lyricFolder = '', $filename = '')
    {
        $originalFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $filename;

        // create a temp file
        $tempFileName = 'temp_' . time();
        $tempFilePath = $lyricFolder . DIRECTORY_SEPARATOR . $tempFileName;
        $tempFile = fopen($tempFilePath, 'w');
        $content = "";
        $isContainsDate = false;

        // Travel to every line of file, if found a line that contains date, ignore the file and return
        // If travel to the end and found no line contains date, create date line
        if ($originalFile = fopen($originalFilePath, "r")) {
            while (!feof($originalFile)) {
                $line = fgets($originalFile);
                if (str_contains($line, '[date:')) {
                    $isContainsDate = true;
                    break;
                }
                $content .= $line;
            }

            if (!$isContainsDate) {
                $content .= PHP_EOL . "[date:" . $this->getDateModified($originalFilePath) . "]";
                fwrite($tempFile, $content);
            }

            fclose($originalFile);
            fclose($tempFile);
        }

        if (!$isContainsDate) {
            // delete original file
            unlink($originalFilePath);

            // rename temp file to original file name
            rename($tempFilePath, $originalFilePath);
            return true;
        } else {
            // ignore the file, delete temp file and return
            unlink($tempFilePath);
            return false;
        }
    }

    private function getDateModified($filePath = '')
    {
        return date('Y-m-d', filemtime($filePath));
    }
}
