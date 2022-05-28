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
        // remove all following special characters: ?,&%!@#$^*
        $res = preg_replace('/[?,&%!@#$^*\\/]+/', '', $res);
        // remove multiple hyphens with single hyphen
        $res = preg_replace('/[-]+/', '-', $res);
        return $res;
    }

    /**
     * stream file from URL
     * Vẫn chưa hiểu logic của code này, chả hiểu sao nó lại work, thật magic!!!
     * Thậm chí ko cần setHeader: 'Content-Type' = 'audio/mpeg'
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
        $conh = stream_context_create($opts);

        $opts['http']['method'] = "GET";
        $cong = stream_context_create($opts);

        // Chưa hiểu đoạn này! biến $out[] dùng chỗ nào???
        $out[] = file_get_contents($url, false, $conh);
        $out[] = $http_response_header;

        ob_end_clean();
        array_map("header", $http_response_header);
        readfile($url, false, $cong);
    }
}
