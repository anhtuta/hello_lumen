<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Dto\SongMeta;
use App\Http\Services\UtilsService;
use App\Http\Services\ZingMp3Service;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminZingMp3Controller extends Controller
{

    const MSG_ZING_ID_CANNOT_EMPTY = "Error: param 'zing_id' cannot be empty!";

    private ZingMp3Service $zingMp3Service;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->zingMp3Service = new ZingMp3Service();
    }

    // Suggestion khi enter text vào ô search trên Zing Mp3 nhưng chưa
    // click vào icon kính lúp, do đó chưa gọi API search!
    public function suggestion(Request $request): JsonResponse
    {
        $q = $request->q;
        $result = new Result();

        if (!isset($q)) {
            $result->res("Error: param 'q' cannot be empty!");
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->suggestion($q));
        return $this->jsonResponse($result);
    }

    // API search click vào icon kính lúp trên trang Zing.
    // Chỉ dùng search theo song là đủ!
    public function searchSong(Request $request): JsonResponse
    {
        $q = $request->q;
        $result = new Result();

        if (!isset($q)) {
            $result->res("Error: param 'q' cannot be empty!");
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->searchSong($q));
        return $this->jsonResponse($result);
    }

    /**
     * Get lyric .lrc URL from Zing Lyric API
     * @param Request $request
     * @return JsonResponse
     */
    public function getLyricUrl(Request $request): JsonResponse
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res(AdminZingMp3Controller::MSG_ZING_ID_CANNOT_EMPTY);
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->getLyricUrl($zing_id));
        return $this->jsonResponse($result);
    }

    /**
     * Get data from Zing Lyric API
     * @param Request $request
     * @return JsonResponse
     */
    public function getLyricRaw(Request $request): JsonResponse
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res(AdminZingMp3Controller::MSG_ZING_ID_CANNOT_EMPTY);
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->getLyricRaw($zing_id));
        return $this->jsonResponse($result);
    }

    /**
     * Download lyric từ zing theo zing_id, luôn tạo mới file lyric mỗi lần
     * download, vì tên file có timestamp. Download xong save ở server.
     * Note: API này hiện tại để test thôi
     * @return JsonResponse Tên file lyric đã lưu (chứ KHÔNG return file cho user)
     */
    public function downloadLyric(Request $request): JsonResponse
    {
        $zing_id = $request->zing_id;
        $title = $request->title;
        $artist = $request->artist;
        $filename = '';
        $songMeta = null;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res(AdminZingMp3Controller::MSG_ZING_ID_CANNOT_EMPTY);
            return response()->json($result, 400);
        }

        Log::info('Download lyric from Zing for: ' . $title . ' - ' . $artist);

        if (isset($title) && isset($artist)) {
            $filename = UtilsService::cleanWithHyphen($artist . " - " . $title);
            $songMeta = new SongMeta($title, $artist);
        }

        $result->successRes($this->zingMp3Service->downloadLyric($zing_id, $filename, $songMeta));
        return $this->jsonResponse($result);
    }

    /**
     * Update lyric from Zing, giống với method downloadLyric ở trên, nhưng method
     * này sẽ ghi đè lại file lyric đã có sẵn, do đó nó phải get tên file lyric từ
     * database trước, còn method downloadLyric thì luôn tạo mới file lyric
     * @return JsonResponse Tên file lưu ở server
     */
    public function updateLyric(Request $request): JsonResponse
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res(AdminZingMp3Controller::MSG_ZING_ID_CANNOT_EMPTY);
            return response()->json($result, 400);
        }

        $song = Song::where('zing_id', $zing_id)->first();
        if (!$song) {
            $result->res("Error: Song not found!");
            return response()->json($result, 404);
        }

        if (!$song->lyric) {
            $result->failRes("Error: this song currently has no lyric, " .
                "try to add new lyric first instead of updating!");
            return response()->json($result, 400);
        }

        Log::info('Update lyric from Zing for: ' . $song->title . ' - ' . $song->artist);

        $lyricName = substr($song->lyric, 0, -4); // remove extension (".trc", ".lrc")
        $result->successRes($this->zingMp3Service->downloadLyric(
            $zing_id,
            $lyricName,
            new SongMeta($song->title, $song->artist)
        ));
        return $this->jsonResponse($result);
    }
}
