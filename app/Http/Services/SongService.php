<?php

namespace App\Http\Services;

use Exception;

class SongService
{
    public static function getFilePathByFileName($fileName = '')
    {
        if ($fileName == '') throw new Exception('filename cannot be null or empty');
        $mp3Folder = env('LL_MP3_FOLDER', '');
        $listOfFolders = scandir($mp3Folder);
        $totalFolder = count($listOfFolders);
        $path = null;

        for ($i = 0; $i < $totalFolder; $i++) {
            if ($listOfFolders[$i] != '.' && $listOfFolders[$i] != '..') {
                $filePath = $mp3Folder . DIRECTORY_SEPARATOR . $listOfFolders[$i] . DIRECTORY_SEPARATOR . $fileName;
                if (file_exists($filePath)) {
                    $path = $filePath;
                    break;
                }
            }
        }

        return $path;
    }

    public static function savePicture($picture_base64, $pictureName)
    {
        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $fp = fopen($pictureFolder . DIRECTORY_SEPARATOR . $pictureName, "w+");
        fwrite($fp, base64_decode($picture_base64));
        fclose($fp);
    }

    public static function removePicture($pictureName)
    {
        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $picturePath = $pictureFolder . DIRECTORY_SEPARATOR . $pictureName;
        if (file_exists($picturePath) && !unlink($picturePath)) {
            return true;
        }
        return false;
    }

    public static function saveMp3File($file, $type, $fileName)
    {
        $mp3Folder = env('LL_MP3_FOLDER', '');
        $file->move($mp3Folder . DIRECTORY_SEPARATOR . $type, $fileName);   // Laravel thì dùng method storeAs nhé!
    }
}
