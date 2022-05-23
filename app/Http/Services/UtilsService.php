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
}
