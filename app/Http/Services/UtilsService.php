<?php

namespace App\Http\Services;

class UtilsService
{
    // Note: khi update method này cũng phải update method phía FE
    public static function cleanWithHyphen($str)
    {
        $res = trim($str);
        // replace all spaces with hyphen
        $res = str_replace(' ', '-', $res);
        // remove all following special characters: ?&%!@#$^*\/'
        $res = preg_replace('/[?&%!@#$^*\\/\']+/', '', $res);
        // remove multiple hyphens with single hyphen
        $res = preg_replace('/[-,]+/', '-', $res);
        return $res;
    }

    /**
     * stream file from URL
     * Ref: https://stackoverflow.com/a/18271362/7688028
     * Ref: https://stackoverflow.com/a/18271545/7688028
     * Ref: https://stackoverflow.com/questions/8401412/php-streaming-video-handler
     */
    public static function streamFromUrl($url)
    {
        ob_start();

        if (isset($_SERVER['HTTP_RANGE'])) {
            $opts['http']['header'] = "Range: " . $_SERVER['HTTP_RANGE'];
        }

        // request method HEAD để đọc info của file, sẽ nhanh hơn nhiều
        // so với dùng request GET
        $opts['http']['method'] = "HEAD";
        $contextHead = stream_context_create($opts);

        // request này dùng để stream trên browser
        $opts['http']['method'] = "GET";
        $contextGet = stream_context_create($opts);

        // Để ý method này dùng contextHead, tức là lấy header từ url,
        // và gán vào biến $http_response_header
        file_get_contents($url, false, $contextHead);

        // Lấy header từ request HEAD, sửa lại giá trị Content-Disposition = inline
        $newHeaders = UtilsService::changeContentDispositionInline($http_response_header);

        ob_end_clean();

        // set header for response
        array_map("header", $newHeaders);

        // return file to response
        readfile($url, false, $contextGet);
    }

    /**
     * stream file from URL
     * Cách này tham khảo 1 member trên facebook, ý tưởng đơn giản hơn
     * (ko cần request HEAD để đọc header trước, mà GET xong return luôn):
     * 1. Tạo stream_context_create method GET
     * 2. Lấy header từ request đó
     * 3. readfile và return
     */
    public static function streamFromUrlWithoutHead($url)
    {
        $opts = array(
            'http' => array(
                'method' => 'GET'
            )
        );
        $context = stream_context_create($opts);
        $fp = fopen($url, 'r', false, $context);
        $meta = stream_get_meta_data($fp);

        foreach ($meta['wrapper_data'] as $header) {
            print_r($header);
            header($header);
        }
        header('Content-Disposition: inline');

        // return file to response
        readfile($url, false, $context);
    }

    public static function println($content, $label = null)
    {
        echo "<pre>";
        if (isset($label)) {
            print_r($label . ': ');
        }
        print_r($content);
        echo "</pre>";
    }

    public static function humanFilesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /*
    $http_response_header sẽ trông giống như này
    [
        "HTTP/1.0 200 OK",
        "Server: nginx",
        "Content-Type: audio/mpeg",
        "Content-Length: 4481024",
        "ETag: 0d25322fd2e16abe33f1",
        "Origin: 358.27",
        "Content-Disposition: attachment; filename=\"Toi-Khong-Tin-Ung-Hoang-Phuc-Ung-Hoang-Phuc_128.mp3\"",
        "Accept-Ranges: bytes",
        "Cache-Control: max-age=2430389",
        "Date: Sat, 28 May 2022 12:40:07 GMT",
        "Connection: close"
    ]
    Cần sửa giá trị 'Content-Disposition: attachment' thành 'inline', nếu ko browser sẽ
    download file mp3 thay vì play trực tiếp nó
    */
    private static function changeContentDispositionInline($headers = [])
    {
        $newHeaders = [];
        foreach ($headers as $header) {
            if (strpos($header, 'Content-Disposition') !== false) {
                array_push($newHeaders, str_replace('attachment', 'inline', $header));
            } else {
                array_push($newHeaders, $header);
            }
        }
        return $newHeaders;
    }
}
