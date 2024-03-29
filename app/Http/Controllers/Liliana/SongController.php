<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\SongService;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SongController extends Controller
{
    public function getAllSongs(Request $request)
    {
        $sortBy = $request->sortBy ? $request->sortBy : 'listens';
        if ($sortBy == 'createdDate') $sortBy = 'created_date';

        $sortOrder = $request->sortOrder ? $request->sortOrder : 'DESC';

        $songs = Song::where('is_deleted', '0')
            ->orderBy($sortBy, $sortOrder)
            ->get();
        return $this->jsonResponse($songs);
    }

    public function getSongById($id)
    {
        $song = Song::find($id);
        $result = new Result();
        $result->successRes($song);
        return $this->jsonResponse($result);  // (new Result())->successRes($song);
    }

    public function getMp3File(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: \"file\" param cannot be empty!"], 404);
        }

        $path = SongService::getFilePathByFileName($request->file);
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
            $result->res("Error: \"file\" param cannot be empty!");
            return response()->json($result, 404);
        }

        $pictureFolder = env('LL_PICTURE_FOLDER', '');
        $filePath = $pictureFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            $result->res("Picture doesn't exist!");
            return response()->json($result, 404);
        } else {
            $type = 'image/png';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($filePath, 200, $headers);
            return $response;
        }
    }

    public function updateListens(Request $request)
    {
        $result = new Result();
        if (!isset($request->file) && !isset($request->path)) {
            $result->res("Error: file or path is required!");
            return response()->json($result, 404);
        }

        // Code cũ thì find by file name, code mới thì find by path.
        // Thôi thì cứ giữ cả 2
        if (isset($request->path)) {
            $songs = DB::select("SELECT * FROM ll_song WHERE path = ?", [$request->path]);
        } else {
            $songs = DB::select("SELECT * FROM ll_song WHERE file_name = ?", [$request->file]);
        }

        if (!$songs || sizeof($songs) == 0) {
            $result->res("Song doesn't exist!");
            return response()->json($result, 404);
        } else {
            $newListens = $songs[0]->listens + 1;
            $id = $songs[0]->id;
            DB::update("UPDATE ll_song SET listens = ? WHERE id = ?", [$newListens, $id]);

            $result->res("Updated listens: " . $songs[0]->title .
                " (" . $songs[0]->artist . "): " . $newListens);
            return response()->json($result);
        }
    }

    public function getAllTypes()
    {
        $types = DB::select("SELECT DISTINCT type FROM ll_song");
        return $this->jsonResponse($types);
    }

    public function updateLyric()
    {
        $songs = Song::all();
        $totalSong = count($songs);
        $count = 0;

        for ($i = 0; $i < $totalSong; $i++) {
            if (isset($songs[$i]->lyric)) continue;
            $id = $songs[$i]->id;
            $lyricFolder = env('LL_LYRIC_FOLDER', '');

            // lyric = "artist - title.trc"
            $lyricFileName = $songs[$i]->artist . ' - ' . $songs[$i]->title . '.trc';
            $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $lyricFileName;
            if (file_exists($filePath)) {
                DB::update("UPDATE ll_song SET lyric = ? WHERE id = ?", [$lyricFileName, $id]);
                $count++;
                continue;
            }

            // lyric = "file_name.trc"
            $lyricFileName = $songs[$i]->file_name . '.trc';
            $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $lyricFileName;
            if (file_exists($filePath)) {
                DB::update("UPDATE ll_song SET lyric = ? WHERE id = ?", [$lyricFileName, $id]);
                $count++;
                continue;
            }

            // lyric = "artist - title.lrc"
            $lyricFileName = $songs[$i]->artist . ' - ' . $songs[$i]->title . '.lrc';
            $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $lyricFileName;
            if (file_exists($filePath)) {
                DB::update("UPDATE ll_song SET lyric = ? WHERE id = ?", [$lyricFileName, $id]);
                $count++;
                continue;
            }

            // lyric = "file_name.lrc"
            $lyricFileName = $songs[$i]->file_name . '.lrc';
            $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $lyricFileName;
            if (file_exists($filePath)) {
                DB::update("UPDATE ll_song SET lyric = ? WHERE id = ?", [$lyricFileName, $id]);
                $count++;
                continue;
            }
        }

        return (new Result())->successRes('Updated! Total rows: ' . $count);
    }
}
