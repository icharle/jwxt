<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    /*
     * 后台登录页面展示
     */
    public function index()
    {
        return view('Index.index');
    }


    /*
     * 获取验证码并且保存cookie
     */
    public function yzm()
    {
        session_start();
        $id = session_id();
        $_SESSION['id'] = $id;
        //COOkIE路径
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/cookie/' . $_SESSION['id'] . '.txt'; //cookie路径
        $verify_code_url = "http://10.1.2.57/CheckCode.aspx";//验证码地址
        //CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $verify_code_url);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($curl);  //执行curl
        curl_close($curl);
        $fp = fopen(dirname(dirname(dirname(dirname(__FILE__)))) . "/public/yzm/verifyCode.jpg", "w");  //文件名
        fwrite($fp, $img); //写入文件
        fclose($fp);
    }
}
