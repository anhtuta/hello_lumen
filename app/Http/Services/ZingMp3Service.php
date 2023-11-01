<?php

namespace App\Http\Services;

use App\Http\Dto\SongMeta;
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

    private Client $guzzle;

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
    public function downloadLyric($zing_id = '', $filename = '', SongMeta $songMeta = null): ?string
    {
        if ($zing_id == '') return null;
        if ($filename == '') {
            $filename = $zing_id . '_' . time();
        }
        $json = $this->getLyricRaw($zing_id);
        if (isset($json->data->sentences)) {
            return $this->downloadLyricTrc($json->data->sentences, $filename . '.trc', $songMeta);
        } elseif (isset($json->data->file)) {
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
    private function downloadLyricTrc($sentences = [], $filename = '', SongMeta $songMeta = null): ?string
    {
        $lyricFolder = env('LL_LYRIC_FOLDER', '') or die("Unable to open file!");
        $filePath = $lyricFolder . DIRECTORY_SEPARATOR . $filename;
        $file = fopen($filePath, "w");
        $this->writeMeta($file, $songMeta);
        $cntSen = count($sentences);

        // gap between each word. Cái này do lỗi lyric của Zing. Theo lý thuyết thì endTime của từ
        // hiện tại phải = startTime của từ tiếp theo. Nhưng đôi khi Zing nó làm lỗi, tức là
        // endTime từ hiện tại < startTime từ tiếp theo. Ta cần nới rộng endTime từ hiện tại
        // (tức là cộng gap cho nó) để nó = startTime từ tiếp theo.
        // Ex: như lyric ở trên comment, giữa các cặp từ Tôi-không, không-tin là có gap,
        // giữa cặp tin-đời, đời-tôi là ko có gap
        $gap = 0;

        for ($i = 0; $i < $cntSen; $i++) {
            $words = $sentences[$i]->words;
            $lineTime = $this->formatStartLine($words[0]->startTime);
            $line = '';
            $wordCnt = count($words);
            $emptyLine = ''; // Tạo 1 dòng trống mới nếu từ cuối cùng có time quá lớn
            $isZeroWord = false; // check if contain a word that has zero time

            for ($j = 0; $j < $wordCnt; $j++) {
                $currWord = $words[$j];
                $ms = $currWord->endTime - $currWord->startTime;
                if ($ms == 0) $isZeroWord = true;
                $nextWord = null; // dùng để tính gap
                if ($j < $wordCnt - 1) {
                    $nextWord = $words[$j + 1];
                } elseif ($i < $cntSen - 1) {
                    $nextWord = $sentences[$i + 1]->words[0];
                } else {
                    // $j = $wordCnt - 1 & $i = $cntSen - 1: từ cuối cùng của câu cuối cùng
                    // (từ cuối cùng của file lyric)
                    $nextWord = null;
                }

                $gap = isset($nextWord) ? ($nextWord->startTime - $currWord->endTime) : 0;

                if ($j == $wordCnt - 1 && $gap >= 4000) {
                    $emptyLine = $this->formatStartLine($currWord->endTime);
                    $emptyLine .= $this->formatWord(' ', $gap, false);
                    $gap = 0;
                }
                $line .= $this->formatWord($currWord->data, $ms + $gap, $j < $wordCnt - 1);
            }

            if ($isZeroWord) $line = $this->mergeZeroWords($line);
            fwrite($file, $lineTime . $line . PHP_EOL);
            if ($emptyLine != '') fwrite($file, $emptyLine . PHP_EOL);
        }

        fclose($file);
        return $filename;
    }

    private function writeMeta($file, SongMeta $songMeta = null)
    {
        if (isset($songMeta)) {
            if ($songMeta->title != '') {
                fwrite($file, '[ti:' . $songMeta->title . ']' . PHP_EOL);
            }
            if ($songMeta->artist != '') {
                fwrite($file, '[ar:' . $songMeta->artist . ']' . PHP_EOL);
            }
        }
        fwrite($file, '[by:Tuzaku]' . PHP_EOL);
        fwrite($file, '[source:ZingMp3]' . PHP_EOL);
        fwrite($file, '[date:' . date("Y-m-d") . ']' . PHP_EOL);
    }

    private function formatWord($word = '', $ms = 0, $extraSpace = false)
    {
        return '<' . $ms . '>' .  $word . ($extraSpace ? ' ' : '');
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
     * Gộp các từ có time = 0 vào từ bên cạnh nó. Có thể có 2 từ bên cạnh, ta sẽ chọn từ nào có time bé hơn.
     * @param $line: ko có đống time [00:27.61] ở đầu, ex: <0>Make <400>the <350>colors <390>in <360>the <4880>sky
     * Ex, đoạn lyric sau:
        [00:27.61]<0>Make <400>the <350>colors <390>in <360>the <4880>sky
        [00:33.99]<0>Green <380>black <370>and <0>blue
        [00:34.74]<380>Colors <370>in <380>the <1120>sky
        [00:36.99]<380>I've <0>been <390>searching <360>for <0>a <390>man
     * Sẽ được convert thành:
        [00:27.61]<400>Make the <350>colors <390>in <360>the <4880>sky
        [00:33.99]<380>Green black <370>and blue
        [00:34.74]<380>Colors <370>in <380>the <1120>sky
        [00:36.99]<380>I've been <390>searching <360>for a <390>man
     */
    private function mergeZeroWords($line)
    {
        $wordTimeRegex = '/<\d+>/'; // ex that match: <380>
        $words = explode(" ", $line); // ex: <380>I've
        $t = array(); // ex: [380, 0, 390, 360, 0, 390]
        $w = array(); // ex: ["I've", 'been', 'searching', 'for', 'a', 'man']
        $zero = array(); // lưu vị trí (index) của các từ có time = 0
        $len = count($words); // ex: 6. 2 mảng $t, $w phải có kích thước = $len

        // UtilsService::println($line, 'line');

        // Đầu tiên duyệt phần tử, lấy ra 2 phần là time và word, sau đó nhét vào 2 mảng t,w.
        // Nếu tại phần tử nào có time = 0, ta lấy index đó nhét vào mảng zero
        for ($i = 0; $i < $len; $i++) {
            $word = $words[$i];
            preg_match($wordTimeRegex, $word, $matches);
            $time = $matches[0]; // ex: <380>
            $timeMs = intval(substr($time, 1, strlen($time) - 2)); // Remove <, >. Ex: <380> -> 380
            array_push($t, $timeMs);
            array_push($w, substr($word, strlen($time)));
            if ($timeMs == 0) array_push($zero, $i);
        }

        // Duyệt mảng zero để nhét các từ có time = 0 vào từ bên cạnh nó (mảng w)
        for ($i = 0; $i < count($zero); $i++) {
            $idx0 = $zero[$i];
            if ($idx0 == 0) {
                $w[1] = $w[$idx0] . ' ' . $w[1];
            } elseif ($idx0 == $len - 1) {
                $w[$len - 2] = $w[$len - 2] . ' ' . $w[$idx0];
            } else {
                // Chọn từ có time bé hơn để gộp với từ hiện tại (time = 0)
                if ($t[$idx0 - 1] <= $t[$idx0 + 1]) {
                    $w[$idx0 - 1] = $w[$idx0 - 1] . ' ' . $w[$idx0];
                } else {
                    $w[$idx0 + 1] = $w[$idx0] . ' ' . $w[$idx0 + 1];
                }
            }
        }

        /*
        Mảng t và w sau biến đổi trên lần lượt là:
        Array
        (
            [0] => 380
            [1] => 0
            [2] => 390
            [3] => 360
            [4] => 0
            [5] => 390
        )
        Array
        (
            [0] => I've been
            [1] => been
            [2] => searching
            [3] => for a
            [4] => a
            [5] => man
        )
        */

        $newLine = '';
        // Tạo line mới từ 2 mảng t và w, chỉ cần bỏ các từ có time = 0 đi là được
        for ($i = 0; $i < $len; $i++) {
            if ($t[$i] == 0) continue;
            $newLine .= '<' . $t[$i] . '>' . $w[$i] . ' ';
        }
        return trim($newLine);
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
