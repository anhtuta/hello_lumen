<?php

namespace App\Http\Controllers\Liliana;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Http\Services\UtilsService;
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
        return $this->jsonResponse($result);
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
        if (isset($json->err) && $json->err != 0 && isset($json->msg)) {
            $result->res("Error: cannot play this song from Zing: " . $json->msg);
            return response()->json($result, 400);
        }

        // Google hơn nửa tiếng mới ra được cách truy cập value nếu key là 1 số!
        // Bọn Zing thật khó chịu và ngu học!
        // Ref: https://stackoverflow.com/a/3240547/7688028
        // ex: streamUrl = "https://mp3-s1-zmp3.zmdcdn.me/b738600166458f1bd654/filename
        $streamUrl = $json->data->{'128'};

        // Nếu copy streamUrl lên browser thì nó sẽ redirect sang 1 URL khác (chưa hiểu sao bọn Zing
        // nó làm như vậy). Cái redirectUrl đó mới stream audio, do đó cách làm như sau:
        // Đầu tiên set allow_redirects = false (để guzzle chặn redirect, nếu ko server sẽ tốn công
        // tải cái file đó về => bị chậm)
        $guzzle = new Client(['allow_redirects' => false]);

        // Sau đó đọc redirectUrl từ header
        $response = $guzzle->request('GET', $streamUrl);

        // ex: https://vnno-vn-5-tf-mp3-s1-zmp3.zmdcdn.me/b738600166458f1bd654/filename,
        // để ý thì redirectUrl khác host so với url trên
        $redirectUrl = $response->getHeader('Location')[0];

        // Stream audio từ redirectUrl đó!
        UtilsService::streamFromUrl($redirectUrl);
    }
}
