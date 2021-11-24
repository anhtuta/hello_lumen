<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\SongService;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminSongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getSongs(Request $request)
    {
        $size = $request->size;

        $sortBy = $request->sortBy ? $request->sortBy : 'updated_at';
        if ($sortBy == 'createdDate') $sortBy = 'created_date';

        $sortOrder = $request->sortOrder ? $request->sortOrder : 'DESC';

        $searchText = $request->searchText;

        $songs = Song::where('is_deleted', '0');
        if (isset($searchText)) {
            $songs = $songs->where('title', 'like', '%' . $searchText . '%')
                ->orWhere('artist', 'like', '%' . $searchText . '%')
                ->orWhere('album', 'like', '%' . $searchText . '%');
        }
        $songs = $songs->orderBy($sortBy, $sortOrder)->paginate($size);
        return $songs;
    }

    /**
     * Create song. Note: nếu song đã có trong database nhưng tạm thời đang bị xóa
     * (is_deleted = 1), thì khôi phục lại (set is_deleted = 0),
     * đồng thời xóa picture cũ đi và tạo picture mới (làm như vậy để đổi tên picture,
     * tránh bị cache phía browser)
     */
    public function createSong(Request $request)
    {
        DB::enableQueryLog();

        $this->validate($request, [
            'title' => 'required',
            'artist' => 'required'
        ]);

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
                if (!SongService::removePicture(($song->image_name))) {
                    $result->res(400005, "Error: Cannot delete old picture!");
                    return response()->json($result, $result->getStatus());
                }
            }
        }

        SongService::saveMp3File($file, $type, $fileName);

        if (isset($pictureBase64)) {
            $song->image_name = $pictureName;
            $song->image_url = "/api/song/picture?file=" . $pictureName;
            SongService::savePicture($pictureBase64, $pictureName);
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
                SongService::removePicture($song->image_name);
            }
            $song->image_name = $pictureName;
            $song->image_url = "/api/song/picture?file=" . $pictureName;
            SongService::savePicture($pictureBase64, $pictureName);
        }
        if ($removePicture == 1) {
            if ($song->image_name) {
                SongService::removePicture($song->image_name);
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
        $filePath = SongService::getFilePathByFileName($song->file_name);
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
}
