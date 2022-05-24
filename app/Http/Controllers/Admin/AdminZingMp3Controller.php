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
    // Unused!
    public function suggestion(Request $request)
    {
        $q = $request->q;
        $result = new Result();

        if (!isset($q)) {
            $result->res("Error: param 'q' cannot be empty!");
            return response()->json($result, 400);
        }

        return $this->zingMp3Service->suggestion($q);
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

        return $this->zingMp3Service->searchSong($q);
    }
}
