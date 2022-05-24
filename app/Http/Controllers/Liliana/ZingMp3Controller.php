<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\ZingMp3Service;
use Illuminate\Http\Request;

class ZingMp3Controller extends Controller
{
    public function __construct()
    {
        $this->zingMp3Service = new ZingMp3Service();
    }

    public function getStream(Request $request)
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res("Error: param 'zing_id' cannot be empty!");
            return response()->json($result, 400);
        }

        return $this->zingMp3Service->getStream($zing_id);
    }
}
