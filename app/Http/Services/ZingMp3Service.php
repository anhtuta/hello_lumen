<?php

namespace App\Http\Services;

use App\Http\Common\Result;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class ZingMp3Service
{
    const API_KEY = '88265e23d4284f25963e6eedac8fbfa3';
    const SECRET_KEY = '2aa2d1c561e809b267f3638c4a307aab';
    const VERSION = '1.6.27';

    /**
     * TODO: mỗi khi gọi API thì class này sẽ được tạo mới instance, dẫn tới việc
     * phải khởi tạo object Guzzle liên tục, khiến thời gian request lớn!
     * (Thật ra là mọi class, kể các các Controller đều sẽ khởi tạo liên tục)
     * Liệu PHP có cách nào dùng được dependency injection và tạo bean giống như
     * Java spring ko?
     */
    public function __construct()
    {
        $defaultParams = [
            'apiKey' => ZingMp3Service::API_KEY,
        ];

        // Thêm default param cho mọi request
        // Ref: https://stackoverflow.com/a/38758183/7688028
        $handler = HandlerStack::create();
        $handler->push(Middleware::mapRequest(function (RequestInterface $request) use ($defaultParams) {
            $uri  = $request->getUri();
            $uri .= ($uri ? '&' : '');
            $uri .= http_build_query($defaultParams);

            return new Request(
                $request->getMethod(),
                $uri,
                $request->getHeaders(),
                $request->getBody(),
                $request->getProtocolVersion()
            );
        }));

        $this->guzzle = new Client([
            'base_uri' => 'https://zingmp3.vn',
            'handler' => $handler,
            // shared cookie jar for all requests (làm theo docs chứ ko hiểu jar là cái gì :v)
            // ref: https://docs.guzzlephp.org/en/stable/quickstart.html#cookies
            'cookies' => true
        ]);

        // Cần cookie "zmp3_rqid" thì gọi API mới thành công, nên chỗ này
        // gọi demo 1 API để nó tự động nhận cookie từ phía Zing. Các request kể từ
        // sau đều dùng cookie này
        $this->guzzle->request('GET', '/api/v2/search?q=test');

        // Bây giờ có cookie zmp3_rqid rồi, ko tin thử in ra mà xem :v
        // $cookieJar = $this->guzzle->getConfig('cookies');
        // print_r($cookieJar->toArray());
    }

    /**
     * Get suggestion from Zing Mp3
     * @return JSON contents from Zing Mp3 which contains suggestion
     */
    public function suggestion($text)
    {
        $url = "https://ac.zingmp3.vn/v1/web/suggestion-keywords?num=10&query=" . $text;
        $client = new Client();
        $response = $client->request('GET', $url);
        $contents = $response->getBody()->getContents();
        return json_decode($contents);
    }

    /**
     * Not used! Just for testing and documentation
     */
    public function searchSongWithComment($text)
    {
        // echo hash('sha256', 'aaa'); // demo
        // echo hash_hmac('sha512', 'aaa', 'bbb'); // still a demo :v
        $uri = "/api/v2/search";
        $ctime = time(); // ex: 1653213682
        $paramsToHashArr = [
            'type' => 'song',
            'page' => 1,
            'count' => 18,
            'version' => ZingMp3Service::VERSION,
            'ctime' => $ctime
        ];
        ksort($paramsToHashArr); // cần sort array trên theo key tăng dần
        $paramsToHashStr = ''; // ex: count=18ctime=1653213682page=1type=songversion=1.6.27
        foreach ($paramsToHashArr as $key => $value) {
            $paramsToHashStr .= $key . '=' . $value;
        }

        // ex: /api/v2/searchd57c390b2fa12f9c20f39ee2d0e23855302a2f2ef87297d86e74304086c35c5e
        $dataForHmac = $uri . hash('sha256', $paramsToHashStr);

        // ex: 3b028caa1a519b8362e7dae7a21cb90c0e187050b7251f6ce197ba7eac07110ca553d47590cb6c4e50997e3f6721b5c6e840923d6e8089f6fd1bc0afffe50401
        $sig = hash_hmac('sha512', $dataForHmac, ZingMp3Service::SECRET_KEY);

        // Ex full URL (bỏ dấu xuống dòng đi):
        // https://zingmp3.vn/api/v2/search?q=ai%20la&ctime=1653213682&sig=3b028caa1a519b
        // 8362e7dae7a21cb90c0e187050b7251f6ce197ba7eac07110ca553d47590cb6c4e50997e3f6721
        // b5c6e840923d6e8089f6fd1bc0afffe50401&count=18&page=1&type=song&version=1.6.27&
        // apiKey=88265e23d4284f25963e6eedac8fbfa3

        $response = $this->guzzle->request('GET', $uri, [
            'query' => array_merge(
                [
                    'q' => $text,
                    'sig' => $sig
                ],
                $paramsToHashArr
            )
        ]);
        $contents = $response->getBody()->getContents();
        return json_decode($contents);
    }

    /**
     * Search song by text from Zing
     * @return JSON search result from Zing Mp3
     */
    public function searchSong($text)
    {
        $uri = "/api/v2/search";
        $paramsToHashArr = [
            'type' => 'song',
            'page' => 1,
            'count' => 18,
            'version' => ZingMp3Service::VERSION,
            'ctime' => time()
        ];
        $contents = $this->requestZing($uri, $paramsToHashArr, ['q' => $text]);
        return json_decode($contents);
    }

    /**
     * Hiện tại chỉ lấy được streaming 128k, còn 320k thì yêu cầu VIP,
     * mặc dù inspect trên web Zing thì API có return 320k
     * Có thể API mới Zing đã update nên chưa lấy được 320k, sẽ khám phá sau!
     * @return JSON contents from Zing Mp3 which contains stream URL
     */
    public function getStream($zing_id)
    {
        $uri = '/api/v2/song/get/streaming';
        $paramsToHashArr = [
            'id' => $zing_id,
            'version' => ZingMp3Service::VERSION,
            'ctime' => time()
        ];
        $contents = $this->requestZing($uri, $paramsToHashArr);
        return json_decode($contents);
    }

    /**
     * get lyric url from zing
     * @return string lyric url, ex: https://static-zmp3.zmdcdn.me/abc.lrc
     */
    public function getLyricUrl($zing_id = '')
    {
        $uri = '/api/v2/lyric/get/lyric';
        $paramsToHashArr = [
            'id' => $zing_id,
            'version' => ZingMp3Service::VERSION,
            'ctime' => time()
        ];
        $contents = $this->requestZing($uri, $paramsToHashArr);
        $json = json_decode($contents);
        if (isset($json) && isset($json->data->file))
            return $json->data->file;
        else return null;
    }

    /**
     * Send request to Zing, logic of this function is using sha512 to hash param
     * and hmac to hash(url + hashed_params) using secret key.
     * @return string content returned from Zing mp3
     */
    private function requestZing($uri = '',  $paramsToHashArr = [], $extraParamsRequest = [])
    {
        ksort($paramsToHashArr);
        $paramsToHashStr = '';
        foreach ($paramsToHashArr as $key => $value) {
            $paramsToHashStr .= $key . '=' . $value;
        }

        $dataForHmac = $uri . hash('sha256', $paramsToHashStr);

        $sig = hash_hmac('sha512', $dataForHmac, ZingMp3Service::SECRET_KEY);

        $response = $this->guzzle->request('GET', $uri, [
            'query' => array_merge(
                [
                    'sig' => $sig
                ],
                $extraParamsRequest,
                $paramsToHashArr
            )
        ]);
        return $response->getBody()->getContents();
    }
}
