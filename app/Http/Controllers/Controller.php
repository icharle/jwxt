<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $url
     * @param $cookie
     * @param $post
     * @return mixed
     */
    function CURL($url,$cookie,$post){
        $ch = curl_init();
        $header = array("Content-Type: application/x-www-form-urlencoded; charset=gb2312");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gb2312');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, 'http://jwxt.gcu.edu.cn/default2.aspx');  //重要，302跳转需要referer，可以在Request Headers找到
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);  //post提交数据
        $result=curl_exec($ch);
        curl_close($ch);
        $result = mb_convert_encoding($result, "UTF-8", "gb2312");
        //适应ParserDom
        $result = str_replace('gb2312', 'utf-8', $result);
        return $result;
    }
}
