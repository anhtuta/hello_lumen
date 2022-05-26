<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\ZingMp3Service;
use GuzzleHttp\Client;
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

        $json = $this->zingMp3Service->getStream($zing_id);
        $result->successRes($json);
        return response()->json($result);
    }

    public function streaming(Request $request)
    {
        $zing_id = $request->zing_id;
        $result = new Result();

        if (!isset($zing_id)) {
            $result->res("Error: param 'zing_id' cannot be empty!");
            return response()->json($result, 400);
        }

        $json = $this->zingMp3Service->getStream($zing_id);

        // Google hơn nửa tiếng mới ra được cách truy cập value nếu key là 1 số!
        // Bọn Zing thật khó chịu và ngu học!
        // Ref: https://stackoverflow.com/a/3240547/7688028
        if (isset($json->err) && $json->err != 0 && isset($json->msg)) {
            $result->res("Error: cannot play this song from Zing: " . $json->msg);
            return response()->json($result, 400);
        }

        $streamUrl = $json->data->{'128'};

        $guzzle = new Client();
        $response = $guzzle->request('GET', $streamUrl);
        return response($response->getBody()->getContents())->withHeaders([
            'Content-Type' => 'audio/mpeg'
        ]);
    }
}
