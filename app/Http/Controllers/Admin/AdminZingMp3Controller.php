<?php

namespace App\Http\Controllers\Admin;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\ZingMp3Service;
use Illuminate\Http\Request;

class AdminZingMp3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->zingMp3Service = new ZingMp3Service();
    }

    // Suggestion khi enter text vào ô search trên Zing Mp3 nhưng chưa
    // click vào icon kính lúp, do đó chưa gọi API search!
    // Currently unused!
    public function suggestion(Request $request)
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
    public function searchSong(Request $request)
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
     * Currently unused!
     */
    public function getLyricUrl(Request $request)
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
}
