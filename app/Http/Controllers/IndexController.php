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
        $id = session_id();
        session(['id' => $id] );
        //COOkIE路径
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/cookie/' . session('id') . '.txt'; //cookie路径
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
     * 提交登录按钮
     */
    public function login()
    {
        $input = Input::except('_token');
        header("Content-type: text/html; charset=gbk");//视学校而定，博主学校是gbk编码，php也采用的gbk编码方式
        $_SESSION['xh']=$input['xh'];
        session(['xh' => $input['xh'] ] );
        $xh=$input['xh'];
        $pw=$input['pw'];
        $code= $input['yzm'];
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt'; //cookie路径
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
        preg_match_all('/<span id="xhxm">([^<>]+)/', $con2, $xm);   //正则出的数据存到$xm数组中
        $xm = substr($xm[1][0],0,-4);
        session(['xm' => $xm ]);
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

    /**
     * 获取课表
     */
    public function kebiao()
    {
        header("Content-type: text/html; charset=gbk");
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt'; //cookie路径
        $url="http://10.1.2.57/xskbcx.aspx?xh=".session('xh')."&xm=".session('xm');

        //查询过去课程表
//        $con1 = $this->login_post($url,$cookie,'');
//        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
//        $post = array(
//            '__VIEWSTATE' => $view[1][0],
//            '__EVENTTARGET' => 'xqd',
//            'xnd' => '2017-2018',      //学年
//            'xqd' => '1',              //学期
//        );
//        $result=$this->login_post($url,$cookie,http_build_query($post));

        $result=$this->login_post($url,$cookie,''); //将数组连接成字符串
        echo $result;
    }
    
    /**
     * 获取成绩
     */
    public function chenji()
    {
        header("Content-type: text/html; charset=gbk");
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt';
        $url = "http://10.1.2.57/xscjcx.aspx?xh=".session('xh')."&xm=".session('xm');
        $con1 = $this->login_post($url,$cookie,'');
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
        $post=array(
            '__EVENTTARGET'=>'',
            '__EVENTARGUMENT'=>'',
            '__VIEWSTATE'=>$view[1][0],
            'hidLanguage'=>'',
            'ddlXN'=>'2016-2017',  //当前学年
            'ddlXQ'=>'1',  //当前学期
            'ddl_kcxz'=>'',
            'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8'  //“学期成绩”的gbk编码，视情况而定
        );
        $url1="http://10.1.2.57/xscjcx.aspx?xh=".session('xh')."&xm=".session('xm');
        $content=$this->login_post($url1,$cookie,http_build_query($post) );
        echo $content;
    }
}
