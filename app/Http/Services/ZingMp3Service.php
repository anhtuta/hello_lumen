<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Util\Json;
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
            $uri = $request->getUri();
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
     *
     * Ví dụ response từ Zing. Note: key nó là số 128 và 320 nhá. Vâng, nó là số đó,
     * bọn Zing ngu học, việc access key là số của thằng PHP cũng ngu học nốt!
     * {"err":0,"msg":"Success","data":{"128":"https://static-zmp3.zmdcdn.me/abcdef","320":"VIP"},"timestamp":1653499621669}
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
     * get lyric url from zing, return lyric url,
     * ex: https://static-zmp3.zmdcdn.me/abc.lrc
     */
    public function getLyricUrl($zing_id = ''): ?string
    {
        $json = $this->getLyricRaw($zing_id);
        return $json->data->file ?? null;
    }

    public function getLyricRaw($zing_id = '')
    {
        $uri = '/api/v2/lyric/get/lyric';
        $paramsToHashArr = [
            'id' => $zing_id,
            'version' => ZingMp3Service::VERSION,
            'ctime' => time()
        ];
        $contents = $this->requestZing($uri, $paramsToHashArr);
        return json_decode($contents);
    }

    /**
     * Download lyric file from Zing
     * @param string filename tên file sẽ lưu, KHÔNG có extension
     * (extension sẽ được chọn sau khi gọi API, ưu tiên chọn .trc, sau đó là .lrc)
     * @return string filename has been saved to server. If Zing doesn't have lyric, return null
     */
    public function downloadLyric($zing_id = '', $filename = ''): ?string
    {
        if ($filename == '') {
            $filename = $zing_id . '_' . time();
        }
        $json = $this->getLyricRaw($zing_id);
        if (isset($json->data->sentences)) {
            return $this->downloadLyricTrc($json->data->sentences, $filename . '.trc');
        } else if (isset($json->data->file)) {
            return $this->downloadLyricLrc($json->data->file, $filename . '.lrc');
        }

        return null;
    }

    private function downloadLyricLrc($url, $filename): ?string
    {
        LyricService::saveLyricFileFromUrl($url, $filename);
        return $filename;
    }

    /*
    Example: $sentences = [
      {
        "words": [
          { "startTime": 36189, "endTime": 36289, "data": "Tôi" },
          { "startTime": 36339, "endTime": 36840, "data": "không" },
          { "startTime": 36879, "endTime": 37419, "data": "tin" },
          { "startTime": 37419, "endTime": 37699, "data": "đời" },
          { "startTime": 37699, "endTime": 38419, "data": "tôi" }
        ]
      },
      {
        "words": [
          { "startTime": 39159, "endTime": 39369, "data": "Có" },
          { "startTime": 39369, "endTime": 39639, "data": "em" },
          { "startTime": 39639, "endTime": 39900, "data": "rồi" },
          { "startTime": 39900, "endTime": 40180, "data": "phải" }
        ]
      }]
     */
    private function downloadLyricTrc($sentences = [], $filename = ''): ?string
    {
        $lyricFolder = env('LL_LYRIC_FOLDER', '') or die("Unable to open file!");
        $file = fopen($lyricFolder . DIRECTORY_SEPARATOR . $filename, "w");
        $this->writeMeta($file);
        $cntSen = count($sentences);

        // foreach ($sentences as $sentence) {
        for ($i = 0; $i < $cntSen; $i++) {
            $words = $sentences[$i]->words;
            $line = $this->formatStartLine($words[0]->startTime);
            $wordCnt = count($words);

            for ($j = 0; $j < $wordCnt; $j++) {
                $currWord = $words[$j];
                $ms = $currWord->endTime - $currWord->startTime;
                $nextWord = null;
                if ($j < $wordCnt - 1) {
                    $nextWord = $words[$j + 1];
                } else if ($i < $cntSen - 1) {
                    $nextWord = $sentences[$i + 1]->words[0];
                } else {
                    // $i = $cntSen - 1: từ cuối cùng của câu cuối cùng
                    $nextWord = null;
                }

                $gap = isset($nextWord) ? ($nextWord->startTime - $currWord->endTime) : 0;
                $line .= '<' . ($ms + $gap) . '>' .  $currWord->data . ($j < $wordCnt - 1 ? ' ' : '');
            }

            fwrite($file, $line . PHP_EOL);
        }

        fclose($file);
        return $filename;
    }

    private function writeMeta($file)
    {
        fwrite($file, '[by:Tuzaku]' . PHP_EOL);
        fwrite($file, '[source:ZingMp3]' . PHP_EOL);
        fwrite($file, '[date:' . date("Y-m-d") . ']' . PHP_EOL);
    }


    /**
     * Ref: Zuka lyric maker (getFormattedPassTime)
     * Trả về thời gian theo format [minute:second.millisecond], ex: [00:36.189]
     * @param {int} ms: thời gian cần format (millisecond), ex: 36189 (36 giây 189ms)
     **/
    private function formatStartLine($ms = 0)
    {
        $minute = floor($ms / 60000);  // 1 minute = 60000 ms
        $second = ($ms - $minute * 60000) / 1000;   //1 second = 1000 ms
        $fractionStr = $second - floor($second) == 0 ? '.000' : '';

        return "[" .
            ($minute < 10 ? "0" . $minute : $minute) . ":" .
            ($second < 10 ? "0" . $second : $second) . $fractionStr .
            "]";
    }

    /**
     * Send request to Zing, logic of this function is using sha512 to hash param
     * and hmac to hash(url + hashed_params) using secret key.
     * @return string content returned from Zing mp3
     */
    private function requestZing($uri = '', $paramsToHashArr = [], $extraParamsRequest = []): string
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
