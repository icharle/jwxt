<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{

    /**
     * 后台登录页面展示
     */
    public function index()
    {
        $this->yzm();
        return view('Index.index');
    }


    /**
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


    /**
     * @param $url
     * @param $cookie
     * @param $post
     * @return mixed
     */
    function login_post($url,$cookie,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, 'http://10.1.2.57/default2.aspx');  //重要，302跳转需要referer，可以在Request Headers找到
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);  //post提交数据
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    /**
     * 提交登录按钮
     */
    public function login()
    {
        $input = Input::except('_token');
        session_start();
        header("Content-type: text/html; charset=gbk");//视学校而定，博主学校是gbk编码，php也采用的gbk编码方式
        $_SESSION['xh']=$input['xh'];
        $xh=$input['xh'];
        $pw=$input['pw'];
        $code= $input['yzm'];
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . $_SESSION['id'] . '.txt'; //cookie路径
        $url="http://10.1.2.57/default2.aspx";  //教务处地址
        $con1=$this->login_post($url,$cookie,'');
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
        $post=array(
            '__VIEWSTATE'=>$view[1][0],
            'txtUserName'=>$xh,
            'TextBox2'=>$pw,
            'txtSecretCode'=>$code,
            'RadioButtonList1'=>'%D1%A7%C9%FA',  //“学生”的gbk编码
            'Button1'=>'',
            'lbLanguage'=>'',
            'hidPdrs'=>'',
            'hidsc'=>''
        );
        $con2=$this->login_post($url,$cookie,http_build_query($post)); //将数组连接成字符串
        if ($con2){
            $data = [
                'status' => 1,
                'msg' => '登录成功'
            ];
        }else{
            $data = [
                'status' => 0,
                'msg' => '登录失败'
            ];
        }
        return $data;
    }

}