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
     */
    public static function streamFromUrl($url)
    {
        ob_start();

        if (isset($_SERVER['HTTP_RANGE'])) {
            $opts['http']['header'] = "Range: " . $_SERVER['HTTP_RANGE'];
        }

        $opts['http']['method'] = "HEAD";
        $opts['http']['method'] = "GET";
        $steamContext = stream_context_create($opts);
        $out[] = file_get_contents($url, false, $steamContext);
        $out[] = $http_response_header;

        ob_end_clean();
        array_map("header", $http_response_header);
        $cong = stream_context_create($opts);
        readfile($url, false, $cong);
    }
}
