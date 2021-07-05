<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Common\Result;
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
        $result = new Result();
        $result->successRes($song);
        return $result;  // return (new Result())->successRes($song);
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
        $result = new Result();
        if (!$request->file) {
            $result->res(404000, "Error: file cannot be empty!");
            return response()->json($result, $result->getStatus());
        }

        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $filePath = $pictureFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            $result->res(404002, "Picture doesn't exist!");
            return response()->json($result, $result->getStatus());
        } else {
            $type = 'image/png';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($filePath, 200, $headers);
            return $response;
        }
    }

    private function savePicture($pictureBase64, $pictureName)
    {
        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $fp = fopen($pictureFolder . DIRECTORY_SEPARATOR . $pictureName, "w+");
        fwrite($fp, base64_decode($pictureBase64));
        fclose($fp);
    }

    private function removePicture($pictureName)
    {
        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $picturePath = $pictureFolder . DIRECTORY_SEPARATOR . $pictureName;
        if (!unlink($picturePath)) {
            return true;
        }
        return false;
    }

    private function saveMp3File($file, $type, $fileName)
    {
        $mp3Folder = env('LL_MP3_FOLDER', '');
        $file->move($mp3Folder . DIRECTORY_SEPARATOR . $type, $fileName);   // Laravel thì dùng method storeAs nhé!
    }

    /**
     * Create song. Note: nếu song đã có trong database nhưng tạm thời đang bị xóa: khôi phục lại,
     * đồng thời xóa picture cũ đi và tạo picture mới (làm như vậy để đổi tên picture,
     * tránh bị cache phía browser)
     */
    public function createSong(Request $request)
    {
        DB::enableQueryLog();
        $result = new Result();
        $title = $request->title;
        $artist = $request->artist;
        $pictureBase64 = $request->pictureBase64;
        $album = $request->album;
        $type = $request->type;
        $file = $request->file('file'); // or using $request->file; is OK
        $fileName = $artist . " - " . $title . ".mp3";
        $pictureName = $artist . " - " . $title  . '_' . time() . ".jpg";  // add time to name to prevent cache in browser

        $song = Song::where('title', $title)
            ->where('artist', $artist)
            ->first();

        Log::info(DB::getQueryLog()); // Show results of log

        if (!$song) {
            $song = new Song();
            $song->listens = 0;
        } else if ($song->is_deleted == 0) {
            $result->res(400004, "Error: This song has already existed!");
            return response()->json($result, $result->getStatus());
        } else {
            if ($song->image_name) {
                if (!$this->removePicture(($song->image_name))) {
                    $result->res(400005, "Error: Cannot delete old picture!");
                    return response()->json($result, $result->getStatus());
                }
            }
        }

        $this->saveMp3File($file, $type, $fileName);

        if (isset($pictureBase64)) {
            $song->image_name = $pictureName;
            $song->image_url = "/api/song/picture?file=" . $pictureName;
            $this->savePicture($pictureBase64, $pictureName);
        }

        $song->title = $title;
        $song->artist = $artist;
        $song->album = $album;
        $song->type = $type;
        $song->file_name = $fileName;
        $song->is_deleted = 0;
        $song->save();

        $result->res(200000, "New song has been created!");
        return response()->json($result);
    }

    /**
     * Update the specified song. Only allow updating title, artist, album and picture
     */
    public function updateSong(Request $request, $id)
    {
        $result = new Result();
        $title = $request->title;
        $artist = $request->artist;
        $pictureBase64 = $request->pictureBase64;
        $album = $request->album;
        $pictureName = $artist . " - " . $title . '_' . time() . ".jpg";
        $removePicture = $request->removePicture;

        $song = Song::find($request->id);

        if (!$song) {
            $result->res(404003, "Error: Song not found!");
            return response()->json($result, $result->getStatus());
        }

        // Nếu ko truyền param pictureBase64 thì sẽ giữ nguyên picture của song (giữ chứ ko xóa nhé!)
        // Nếu muốn xóa picture thì phải truyền param removePicture = 1
        if (isset($pictureBase64)) {
            if ($song->image_name) {
                $this->removePicture($song->image_name);
            }
            $song->image_name = $pictureName;
            $song->image_url = "/api/song/picture?file=" . $pictureName;
            $this->savePicture($pictureBase64, $pictureName);
        }
        if ($removePicture == 1) {
            if ($song->image_name) {
                $this->removePicture($song->image_name);
            }
            $song->image_name = null;
            $song->image_url = null;
        }

        $song->title = $title;
        $song->artist = $artist;
        $song->album = $album;
        $song->save();

        $result->res(200000, "Song has been updated!");
        return response()->json($result);
    }

    public function deleteSong($id)
    {
        $result = new Result();

        // Check if exist song
        $song = Song::find($id);
        if (!$song) {
            $result->res(404003, "Error: Song not found!");
            return response()->json($result, $result->getStatus());
        }

        // Delete file associated with this song
        $filePath = $this->getFilePathByFileName($song->file_name);
        if (!unlink($filePath)) {
            $result->res(400000, "Error: Cannot delete a file!");
            return response()->json($result, $result->getStatus());
        }

        // Delete this song in database by updating is_deleted column
        DB::table('song')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);
        $result->res(200000, "Song has been deleted!");
        return response()->json($result);
    }

    public function updateListens(Request $request)
    {
        $result = new Result();
        if (!$request->file) {
            $result->res(404000, "Error: file cannot be empty!");
            return response()->json($result, $result->getStatus());
        }

        $songs = DB::select("SELECT * FROM song WHERE file_name = ?", [$request->file]);

        if (!$songs || sizeof($songs) == 0) {
            $result->res(404001, "Song doesn't exist!");
            return response()->json($result, $result->getStatus());
        } else {
            $newListens = $songs[0]->listens + 1;
            $id = $songs[0]->id;
            DB::update("UPDATE song SET listens = ? WHERE id = ?", [$newListens, $id]);

            $result->res(200000, "Updated listens: " . $songs[0]->title .
                " (" . $songs[0]->artist . "): " . $newListens);
            return response()->json($result);
        }
    }

    public function getAllTypes()
    {
        $types = DB::select("SELECT DISTINCT type FROM song");
        return response()->json($types);
    }
}
