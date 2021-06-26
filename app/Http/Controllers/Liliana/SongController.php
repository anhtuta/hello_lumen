<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Log;

class SongController extends Controller
{
    public function getSongs(Request $request)
    {
        $size = $request->size;

        $sortBy = $request->sortBy ? $request->sortBy : 'updated_at';
        if ($sortBy == 'createdDate') $sortBy = 'created_date';

        $sortOrder = $request->sortOrder ? $request->sortOrder : 'DESC';

        $songs = Song::where('is_deleted', '0')
            ->orderBy($sortBy, $sortOrder)
            ->paginate($size);
        return $songs;
    }

    public function getAllSongs(Request $request)
    {
        $sortBy = $request->sortBy ? $request->sortBy : 'listens';
        if ($sortBy == 'createdDate') $sortBy = 'created_date';

        $sortOrder = $request->sortOrder ? $request->sortOrder : 'DESC';

        $songs = Song::where('is_deleted', '0')
            ->orderBy($sortBy, $sortOrder)
            ->get();
        return $songs;
    }

    public function getSongById($id)
    {
        $song = Song::find($id);
        return response()->json($song);
    }

    private function getFilePathByFileName($fileName)
    {
        $mp3Folder = env('LL_MP3_FOLDER', '');
        $listOfFolders = scandir($mp3Folder);
        $totalFolder = count($listOfFolders);
        $path = '';

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

    public function getSongByFile(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $path = $this->getFilePathByFileName($request->file);
        if (!$path) {
            return response()->json(["code" => 404001, "message" => "Song doesn't exist!"], 404);
        } else {
            $type = 'audio/mp3';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($path, 200, $headers);
            return $response;
        }
    }

    public function getPictureByFile(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $filePath = $pictureFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            return response()->json(["code" => 404002, "message" => "Picture doesn't exist!"], 404);
        } else {
            $type = 'image/png';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($filePath, 200, $headers);
            return $response;
        }
    }

    private function savePicture($pictureBase64, $imageName)
    {
        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $fp = fopen($pictureFolder . DIRECTORY_SEPARATOR . $imageName, "w+");
        fwrite($fp, base64_decode($pictureBase64));
        fclose($fp);
    }

    private function saveMp3File($file, $type, $fileName)
    {
        $mp3Folder = env('LL_MP3_FOLDER', '');
        $file->move($mp3Folder . DIRECTORY_SEPARATOR . $type, $fileName);   // Laravel thì dùng method storeAs nhé!
    }

    public function createSong(Request $request)
    {
        DB::enableQueryLog();
        $title = $request->title;
        $artist = $request->artist;
        $pictureBase64 = $request->pictureBase64;
        $album = $request->album;
        $type = $request->type;
        $file = $request->file('file'); // or using $request->file; is OK
        $fileName = $artist . " - " . $title . ".mp3";
        $imageName = $artist . " - " . $title . ".jpg";

        $song = Song::where('title', $title)
            ->where('artist', $artist)
            ->first();

        Log::info(DB::getQueryLog()); // Show results of log

        if (!$song) {
            $song = new Song();
            $song->listens = 0;
        } else if ($song->is_deleted == 0) {
            return response()->json(["code" => 400004, "message" => "Error: This song has already existed!"], 400);
        }

        $this->saveMp3File($file, $type, $fileName);

        if (isset($pictureBase64)) {
            $song->image_name = $imageName;
            $song->image_url = "/api/song/picture?file=" . $imageName;
            $this->savePicture($pictureBase64, $imageName);
        }

        $song->title = $title;
        $song->artist = $artist;
        $song->album = $album;
        $song->type = $type;
        $song->file_name = $fileName;
        $song->is_deleted = 0;
        $song->save();

        return response()->json(["code" => 200000, "message" => "New song has been created!"]);
    }

    public function deleteSong($id)
    {
        // Check if exist song
        $song = Song::find($id);
        if (!$song) {
            return response()->json(["code" => 404003, "message" => "Error: Song not found!"], 404);
        }

        // Delete file associated with this song
        $filePath = $this->getFilePathByFileName($song->file_name);
        if (!unlink($filePath)) {
            return response()->json(["code" => 400000, "message" => "Error: Cannot delete a file!"], 400);
        }

        // Delete this song in database by updating is_deleted column
        DB::table('song')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);
        return response()->json(["code" => 200000, "message" => "Song has been deleted!"]);
    }

    public function updateListens(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $songs = DB::select("SELECT * FROM song WHERE file_name = ?", [$request->file]);

        if (!$songs || sizeof($songs) == 0) {
            return response()->json(["code" => 404001, "message" => "Song doesn't exist!"], 404);
        } else {
            $newListens = $songs[0]->listens + 1;
            $id = $songs[0]->id;
            DB::update("UPDATE song SET listens = ? WHERE id = ?", [$newListens, $id]);
            return response()->json([
                "code" => 200000,
                "message" => "Updated listens: " . $songs[0]->title .
                    " (" . $songs[0]->artist . "): " . $newListens
            ]);
        }
    }

    public function getAllTypes()
    {
        $types = DB::select("SELECT DISTINCT type FROM song");
        return response()->json($types);
    }

    public function uploadSong(Request $request)
    {
    }
}
