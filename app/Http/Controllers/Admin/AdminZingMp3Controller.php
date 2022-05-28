<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\ZingMp3Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminZingMp3Controller extends Controller
{
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
        return response()->json($result);
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
        return response()->json($result);
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
            $result->res("Error: param 'zing_id' cannot be empty!");
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->getLyricUrl($zing_id));
        return response()->json($result);
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
            $result->res("Error: param 'zing_id' cannot be empty!");
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->getLyricRaw($zing_id));
        return response()->json($result);
    }

    public function downloadLyric(Request $request): JsonResponse
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res("Error: param 'zing_id' cannot be empty!");
            return response()->json($result, 400);
        }

        $result->successRes($this->zingMp3Service->downloadLyric($zing_id));
        return response()->json($result);
    }
}
