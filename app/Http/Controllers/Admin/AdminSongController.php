<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Dto\SongMeta;
use App\Http\Services\SongService;
use App\Http\Services\UtilsService;
use App\Http\Services\ZingMp3Service;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminSongController extends Controller
{
    private ZingMp3Service $zingMp3Service;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->zingMp3Service = new ZingMp3Service();
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
        return $this->jsonResponse($songs);
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
        $warnings = [];
        $title = $request->title;
        $artist = $request->artist;
        $pictureBase64 = $request->pictureBase64;
        $song_of_the_year = $request->song_of_the_year;
        $album = $request->album;
        $path = $request->path;
        $type = $request->type;
        $lyric = $request->lyric;
        $zing_id = $request->zing_id;
        $image_url = $request->imageUrl;
        $file = $request->file('file'); // or using $request->file; is OK
        $fileName = $artist . " - " . $title . ".mp3";
        $pictureName = $this->getPictureName($title, $artist);

        // Check existed song theo tên bài + tên ca sĩ
        $song = Song::where('title', $title)
            ->where('artist', $artist)
            ->first();

        Log::info(DB::getQueryLog()); // Show results of log

        if (!$song) {
            $song = new Song();
            $song->listens = 0;
        } else if ($song->is_deleted == 0) {
            $result->res("Error: This song has already existed!");
            return response()->json($result, 400);
        } else {
            // xóa ảnh cũ của song này đi, sau đó sẽ update bằng ảnh mới từ request
            if ($song->image_name && !SongService::removePicture(($song->image_name))) {
                array_push($warnings, '"image_name" column exists, ' .
                    'but cannot delete this image, maybe it has been deleted already!');
            }
            $song->image_name = null;
        }

        if (!isset($zing_id)) {
            SongService::saveMp3File($file, $type, $fileName);
            $song->file_name = $fileName;
            $song->lyric = $lyric;
        } else {
            $song->zing_id = $zing_id;
            $song->lyric = $this->zingMp3Service->downloadLyric(
                $zing_id,
                UtilsService::cleanWithHyphen($artist . " - " . $title),
                new SongMeta($title, $artist)
            );
            if (isset($image_url)) {
                $song->image_url = $image_url;
            }
        }

        // Note: tuy chọn song từ Zing nhưng vẫn có thể chọn ảnh khác từ local,
        // lúc này image_url chứa ảnh từ zing đã set ở trên sẽ bị ghi đè
        if (isset($pictureBase64)) {
            $song->image_name = $pictureName;
            $song->image_url = "/api/song/picture?file=" . $pictureName;
            SongService::savePicture($pictureBase64, $pictureName);
        }

        $song->title = $title;
        $song->artist = $artist;
        $song->song_of_the_year = $song_of_the_year;
        $song->album = $album;
        $song->path = $path;
        $song->type = $type;
        $song->is_deleted = 0;
        $song->save();

        $result->res("New song has been created!", 'Warnings:' . implode('; ', $warnings));
        return $this->jsonResponse($result);
    }

    /**
     * Update the specified song. Only allow updating title, artist, album and picture.
     * Note: Nếu bên route dùng PathVariable: $router->post('/id/{id}', ...);
     * Thì biến $id sẽ tự động có trong param $request, do đó function ko cần param $id
     */
    public function updateSong(Request $request)
    {
        $result = new Result();
        $title = $request->title;
        $artist = $request->artist;
        $pictureBase64 = $request->pictureBase64;
        $song_of_the_year = $request->song_of_the_year;
        $album = $request->album;
        $path = $request->path;
        $lyric = $request->lyric;
        $removePicture = $request->removePicture;

        $song = Song::find($request->id);

        if (!$song) {
            $result->res("Error: Song not found!");
            return response()->json($result, 404);
        }

        // Nếu ko truyền param pictureBase64 thì sẽ giữ nguyên picture của song (giữ chứ ko xóa nhé!)
        // Nếu có truyền param pictureBase64 thì xóa picture hiện tại trước, sau đó thay = picture đó
        // Nếu muốn xóa picture thì phải truyền param removePicture = 1
        if (isset($pictureBase64)) {
            if ($song->image_name) {
                SongService::removePicture($song->image_name);
            }
            $pictureName = $this->getPictureName($title, $artist);
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
        $song->song_of_the_year = $song_of_the_year;
        $song->album = $album;
        $song->path = $path;
        $song->lyric = $lyric;
        $song->save();

        $result->res("Song has been updated!");
        return $this->jsonResponse($result);
    }

    public function deleteSong($id)
    {
        $result = new Result();
        $deleteMsg = "";

        // Check if exist song
        $song = Song::find($id);
        if (!$song) {
            $result->res("Error: Song not found!");
            return response()->json($result, 404);
        } elseif ($song->is_deleted == 1) {
            $result->res("Error: Song has been deleted already!");
            return response()->json($result, 400);
        }

        if (isset($song->file_name)) {
            // Delete file associated with this song
            $filePath = SongService::getFilePathByFileName($song->file_name);
            if ($filePath != null) {
                if (!unlink($filePath)) {
                    $result->res("Error: Cannot delete a file!");
                    return response()->json($result, 404);
                }
            } else {
                $deleteMsg = " But the cannot delete mp3 file because it didn't exist!";
            }
        }

        // Delete this song in database by updating is_deleted column
        DB::table('ll_song')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);
        $result->res("Song has been deleted!" . $deleteMsg);
        return $this->jsonResponse($result);
    }

    // This method is for running manually, not for FE to call.
    // We will update directly on FE side (using Javascript to create path).
    // Only use this method for old data when in db, path column is NULL
    public function updatePath()
    {
        $songs = Song::all();
        $totalSong = count($songs);
        $count = 0;

        for ($i = 0; $i < $totalSong; $i++) {
            if (isset($songs[$i]->path)) continue;
            $id = $songs[$i]->id;
            $path = $songs[$i]->title . ' ' . $songs[$i]->artist;
            $path = str_replace([' '], '-', trim($path));
            $path = str_replace(['?', ' '], '', $path);
            DB::update("UPDATE ll_song SET path = ? WHERE id = ?", [$path, $id]);
            $count++;
        }

        return (new Result())->successRes('Updated! Total rows: ' . $count);
    }

    private function getPictureName($title, $artist)
    {
        // add time to name to prevent cache in browser
        return UtilsService::cleanWithHyphen($artist . " - " . $title) . '_' . time() . ".jpg";
    }
}
