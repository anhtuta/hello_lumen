<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Controllers\Controller;
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

        $songs = Song::orderBy($sortBy, $sortOrder)->get();
        return $songs;
    }

    public function getSongById($id)
    {
        $songs = Song::find($id);
        return response()->json($songs);
    }

    public function getSong(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $mp3Folder = env('LL_MP3_FOLDER', '');
        $listOfFolders = scandir($mp3Folder);
        $totalFolder = count($listOfFolders);
        $path = '';

        for ($i = 0; $i < $totalFolder; $i++) {
            if ($listOfFolders[$i] != '.' && $listOfFolders[$i] != '..') {
                $filePath = $mp3Folder . DIRECTORY_SEPARATOR . $listOfFolders[$i] . DIRECTORY_SEPARATOR . $request->file;
                if (file_exists($filePath)) {
                    $path = $filePath;
                    break;
                }
            }
        }

        if (!$path) {
            return response()->json(["code" => 404001, "message" => "Song doesn't exist!"], 404);
        } else {
            $type = 'audio/mp3';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($path, 200, $headers);
            return $response;
        }
    }

    public function getAlbum(Request $request)
    {
        if (!$request->file) {
            return response()->json(["code" => 404000, "message" => "Error: file cannot be empty!"], 404);
        }

        $albumFolder = env('LL_ALBUM_FOLDER', '');
        $filePath = $albumFolder . DIRECTORY_SEPARATOR . $request->file;
        if (!file_exists($filePath)) {
            return response()->json(["code" => 404002, "message" => "Album doesn't exist!"], 404);
        } else {
            $type = 'image/png';
            $headers = ['Content-Type' => $type];
            $response = new BinaryFileResponse($filePath, 200, $headers);
            return $response;
        }
    }

    // this function hasn't done yet!
    public function createSong(Request $request)
    {
        $song = new Song();
        $song->title = $request->title;
        $song->artist = $request->artist;
        $song->save();
        return response()->json("New song has been created!");
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
            return "Updated listens: " . $songs[0]->title . " (" . $songs[0]->artist . "): " . $newListens;
        }
    }
}
