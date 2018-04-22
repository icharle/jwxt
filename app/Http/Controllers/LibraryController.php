<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LibraryController extends Controller
{
    function LibCurl($url,$cookie,$post){
        //伪造Ip
        $ip = mt_rand(1, 255) . "." . mt_rand(1, 255) . "." . mt_rand(1, 255) . "." . mt_rand(1, 255) . "";
        $ch = curl_init();
        $header = array("Content-Type: text/plain;charset=gb2312");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gb2312');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("CLIENT-IP:" . $ip . "", "X_FORWARD_FOR:" . $ip . ""));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, 'http://ilib.gcu.edu.cn/WebOPAC/search_simpleSearchView');  //重要，302跳转需要referer，可以在Request Headers找到
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);  //post提交数据
        $result=curl_exec($ch);
        curl_close($ch);
        $result = mb_convert_encoding($result, 'gb2312', 'utf-8');
        $result = '<meta http-equiv="Content-Type" content="text/html;charset=gb2312">' . $result;
        return $result;
    }

    public function login()
    {
        session_start();
        $libid = session_id();
        session(['libid' => $libid]);
        $this->yzm();
        $captcha = "public/yzm/".$libid . ".jpg";
        $captcha_path = url($captcha);
        //echo $captcha_path;
        return view('Library.login',compact('captcha_path'));
    }


    public function yzm()
    {
        //COOkIE路径
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/cookie/' . session('libid') . '.txt'; //cookie路径
        $verify_code_url = "http://ilib.gcu.edu.cn/WebOPAC/Login_createPINCode";//验证码地址
        //CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $verify_code_url);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($curl);  //执行curl
        curl_close($curl);
        $fp = fopen(dirname(dirname(dirname(dirname(__FILE__)))) . "/public/yzm/". session('libid') . ".jpg", "w");  //文件名
        fwrite($fp, $img); //写入文件
        fclose($fp);
    }

    public function login_post()
    {
        $input = Input::except('_token');
        header("Content-type: text/html; charset=utf-8");
        $strID = $input['strID'];
        $strLoginPwd = $input['strLoginPwd'];
        $strLoginRandomCode = $input['strLoginRandomCode'];
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('libid') . '.txt'; //cookie路径
        $url = "http://ilib.gcu.edu.cn/WebOPAC/Login_loginValidate";    //图书馆
        $post = array(
            'strLoginType' => 'CardCode',
            'strID' => $strID,
            'strLoginPwd' => $strLoginPwd,
            'strLoginRandomCode' => $strLoginRandomCode

        );
        $con1 = $this->LibCurl($url, $cookie, http_build_query($post)); //将数组连接成字符串
        echo $con1;
    }


    public function jieye()
    {
//        header("Content-type: text/html; charset=utf8");
        $url = "http://ilib.gcu.edu.cn/WebOPAC/Personal_toJieYueView";
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/cookie/' . session('libid') . '.txt'; //cookie路径
        $con2 = $this->LibCurl($url, $cookie, ''); //将数组连接成字符串
//        echo $con2;
        $html_dom = new \HtmlParser\ParserDom($con2);
//        $res =  $html_dom->find('title');
//        foreach ($res as $r){
//            echo $r->getPlainText();
//        }
//        $tables = $html_dom->find('div.jszd_jsjl table tr td');       //3个table(当前借阅,借阅历史,借阅明细)
//        foreach ($tables as $table){
//            echo $table->innerHtml();
//            echo "<br>";
//        }



        $tables = $html_dom->find('div.jszd_jsjl table');       //3个table(当前借阅,借阅历史,借阅明细)
        foreach ($tables as $table){
            foreach ($table->find('tr') as $thd){

                foreach ($thd->find('th') as $th){
                    echo $th->getPlainText();
                }
                foreach ($thd->find('td') as $td){
                    echo $td->innerHtml();
                }
                echo "<br>";


            }
        }



//        dd($tables);
    }

}
