<?php

namespace App\Http\Controllers;

use App\Course;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use HtmlParser\ParserDom;
use Carbon\Carbon;
use Psy\Command\DumpCommand;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->setTime();
        View()->share('week', getenv('week'));
    }


    /**
     *  设置当前教学周为环境变量；
     *  调用方法：
     *  getenv('week');
     */
    public function setTime()
    {
        $FirstSemester = config('app.FirstSemester');
        $time = explode('-', $FirstSemester);
        $week = Carbon::today()->diffInWeeks(Carbon::createFromDate($time[0], $time[1], $time[2]));   //diffInWeeks 获取指定Carbon对象与当前实例时间的星期数差, 取整
        $week++;
        putenv('week=' . $week);    //env文件
        $this->week = $week;
    }



    /**
     * 后台登录页面展示
     */
    public function login()
    {
        session_start();
        $id = session_id();
        session(['id' => $id]);
        $this->yzm();
        $captcha = "public/yzm/".$id . ".jpg";
        $captcha_path = url($captcha);
        //echo $captcha_path;
        return view('Index.login',compact('captcha_path'));
    }


    /**
     * 获取验证码并且保存cookie
     */
    public function yzm()
    {
        //COOkIE路径
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/public/cookie/' . session('id') . '.txt'; //cookie路径
        $verify_code_url = "http://jwxt.gcu.edu.cn/CheckCode.aspx";//验证码地址
        //CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $verify_code_url);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($curl);  //执行curl
        curl_close($curl);
        $fp = fopen(dirname(dirname(dirname(dirname(__FILE__)))) . "/public/yzm/". session('id') . ".jpg", "w");  //文件名
        fwrite($fp, $img); //写入文件
        fclose($fp);
    }


    /**
     * 提交登录按钮
     */
    public function login_post()
    {
        $input = Input::except('_token');
        header("Content-type: text/html; charset=gbk");//视学校而定，博主学校是gbk编码，php也采用的gbk编码方式
        $_SESSION['xh'] = $input['xh'];
        session(['xh' => $input['xh']]);
        $xh = $input['xh'];
        $pw = $input['pw'];
        $code = $input['yzm'];
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt'; //cookie路径
        $url = "http://jwxt.gcu.edu.cn/default2.aspx";  //教务处地址
        $con1 = $this->CURL($url, $cookie, '');
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
        $post = array(
            '__VIEWSTATE' => $view[1][0],
            'txtUserName' => $xh,
            'TextBox2' => $pw,
            'txtSecretCode' => $code,
            'RadioButtonList1' => iconv('utf-8', 'gb2312', '学生'),  //“学生”的gbk编码
            'Button1' => iconv('utf-8', 'gb2312', '登录'),
            'lbLanguage' => '',
            'hidPdrs' => '',
            'hidsc' => ''
        );
        $con2 = $this->CURL($url, $cookie, http_build_query($post)); //将数组连接成字符串
        if (strpos($con2,'欢迎您')){

            preg_match_all('/<span id="xhxm">([^<>]+)/', $con2, $xm);   //正则出的数据存到$xm数组中
            $xm = substr($xm[1][0], 0, -6);
            $xm = mb_convert_encoding($xm, "gb2312", "UTF-8");
            $xm = urlencode($xm);
            session(['xm' => $xm]);
            $this->Getkebiao();            //向学校服务器爬取课表
            //$this->Getchengji();           //向学校服务器爬取成绩
            $data = [
                'status' => 1,
                'msg' => '登录成功'
            ];

        }else{

            if (strpos($con2, '密码')) {

                $data = [
                    'status' => 0,
                    'msg' => '密码错误'
                ];

            } elseif (strpos($con2, '验证码不正确')) {

                $data = [
                    'status' => 0,
                    'msg' => '验证码错误'
                ];

            } elseif (strpos($con2, '用户名不存在')) {

                $data = [
                    'status' => 0,
                    'msg' => '用户名不存在'
                ];

            }

        }

        return $data;
    }


    /**
     * 主页面
     */
    public function index()
    {
        //存在session直接可以查看信息
        if (!session('xh')){

            return redirect( url('login') );

        }else{

            $xh = session('xh');
            $xm = session('xm');
            return view('Index.index',compact('xh','xm'));

        }
    }


    /**
     * 课表展示
     */
    public function course()
    {
        $xh = session('xh');
        return view('Index.course',compact('xh'));
    }


    /**
     * @return array|\Illuminate\Database\Eloquent\Model|null|string|static
     * 客户端请求课表数据
     */
    public function kebiao()
    {
        $input = Input::except('_token');
        $result = Course::where('student_id',$input['xh'])->first();
        if ($result){

            return $result['student_course'];

        }else{

             return $this->Getkebiao();

        }
    }

    /**
     * 成绩清单展示
     */
    public function score()
    {
        $xh = session('xh');
        return view('Index.score',compact('xh'));
    }


    /**
     * @return mixed|void
     * 客户端请求成绩数据
     */
    public function chengji()
    {
        $input = Input::except('_token');
        $result = Score::where('student_id',$input['xh'])->first();
        if ($result){

            return $result['student_scores'];

        }else{

            return $this->Getchengji();

        }
    }



    /**
     * 向学校服务器端爬取课表并且存库
     */
    public function Getkebiao()
    {
        header("Content-type: text/html; charset=utf8");
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt'; //cookie路径
        $url = "http://jwxt.gcu.edu.cn/xskbcx.aspx?xh=" . session('xh') . "&xm=" . session('xm');

        //查询过去课程表
//        $con1 = $this->CURL($url,$cookie,'');
//        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
//        $post = array(
//            '__VIEWSTATE' => $view[1][0],
//            '__EVENTTARGET' => 'xqd',
//            'xnd' => '2017-2018',      //学年
//            'xqd' => '1',              //学期
//        );
//        $result=$this->CURL($url,$cookie,http_build_query($post));

        $result = $this->CURL($url, $cookie, ''); //将数组连接成字符串
        //echo $result;
        $html_dom = new \HtmlParser\ParserDom($result);
        $courses = array();
        $coursess = $html_dom->find('#Table1 tr');
        foreach ($coursess as $tr){
            $first_td = $tr->find('td', 0)->getPlainText();
            if ($first_td == '时间') {
                continue;
            }elseif ($first_td == '早晨'){
                continue;
            }elseif ($first_td == '上午'){
                $first_td = '第1节';
            }elseif ($first_td == '下午'){
                $first_td = '第5节';
            }elseif ($first_td == '晚上'){
                $first_td = '第9节';
            }
            //dump($first_td);

            $td_array = $tr->find('td[align=Center]');
            foreach ($td_array as $td) {

                //去掉空的课表
                if (strlen(trim($td->getPlainText())) != 2){

                    //var_dump($td->innerHtml());

                    $content = explode('<br><br>', $td->innerHtml());
                    //var_dump($content);
                    foreach ($content as $c){
                        if (substr($c,0,4) == '<br>'){
                            $c = substr($c, 4);
                        }
                        //var_dump($c);
                        //echo "<br>";
                        //echo "<br>";

                        $contents = explode('<br>',$c);
                        //var_dump($contents);

                        $course['name'] = $contents[0];    //课程名称
                        $course['teacher'] = $contents[2];
                        $course['place'] = $contents[3];
                        $time = $contents[1];
                        preg_match_all("|周(.*)第(.*),(.*)节{第(.*)-(.*)周}|isU", $time, $time_array);
                        $weekday = implode('',$time_array[1]);
                        switch ($weekday) {
                            case '一':
                                $weekday = '1';
                                break;
                            case '二':
                                $weekday = '2';
                                break;
                            case '三':
                                $weekday = '3';
                                break;
                            case '四':
                                $weekday = '4';
                                break;
                            case '五':
                                $weekday = '5';
                                break;
                        }
                        $course['weekday'] = $weekday;
                        $course['class_begin'] = implode('',$time_array[2]);
                        $course['class_end'] = implode('',$time_array[3]);
                        $course['week_begin'] = implode('',$time_array[4]);

                        $end = implode('',$time_array[5]);
                        if (strlen($end) > 2) {
                            preg_match_all('/\d+/', $end, $rs);
                            //var_dump($rs);
                            $course['week_end'] = implode('',$rs[0]);
                            if (strpos($end, '双')) {
                                $course['week_odd'] = '2';
                            } else {
                                $course['week_odd'] = '1';
                            }
                        }else{
                            $course['week_end'] = $end;
                            $course['week_odd'] = '0';
                        }

                        //$course['week_end'] = implode('',$time_array[5]);

                        array_push($courses, $course);
                        //var_dump($course);
                    }
                }

            }
        }
        $courses = json_encode($courses, JSON_UNESCAPED_UNICODE);

        //储存课表在数据库
        $result = Course::where('student_id', session('xh') )->first();
        if ($result != null){

            $data['student_course'] = $courses;
            $data['time'] = time();
            Course::where('student_id',session('xh'))->update($data);

        }else{

            $data['student_id'] = session('xh');
            $data['student_course'] = $courses;
            $data['time'] = time();
            Course::create($data);
        }

//        dd($courses);
        return $courses;

    }

    /**
     * 向学校服务器爬取成绩并且入库
     */
    public function Getchengji()
    {
        header("Content-type: text/html; charset=utf-8");
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt';
        $url = "http://jwxt.gcu.edu.cn/xscjcx.aspx?xh=" . session('xh') . "&xm=" . session('xm');
        $con1 = $this->CURL($url, $cookie, '');
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);

//        $post = array(
//            '__EVENTTARGET' => '',
//            '__EVENTARGUMENT' => '',
//            '__VIEWSTATE' => $view[1][0],
//            'hidLanguage' => '',
//            'ddlXN' => '2016-2017',  //当前学年
//            'ddlXQ' => '1',  //当前学期
//            'ddl_kcxz' => '',
//            'btn_xq' => iconv('utf-8', 'gb2312', '学期成绩')
//        );

        $post = array(
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => $view[1][0],
            'hidLanguage' => '',
            'ddlXN' => '',
            'ddlXQ' => '',
            'ddl_kcxz' => '',
            'btn_zcj' => iconv('utf-8', 'gb2312', '历年成绩')
        );
        $url1 = "http://jwxt.gcu.edu.cn/xscjcx.aspx?xh=" . session('xh') . "&xm=" . session('xm');
        $content = $this->CURL($url1, $cookie, http_build_query($post));

        $html_dom = new \HtmlParser\ParserDom($content);
        $score=array(); //成绩数组
        $table = $html_dom->find('table[id=DataGrid1]',0);
        foreach($table->find('tr') as $k=> $tr){
            $score[$k]['course_year']=$tr->find('td',0)->plaintext;     //学年
            $score[$k]['course_term']=$tr->find('td',1)->plaintext;    //学期
            $score[$k]['course_name']=$tr->find('td',3)->plaintext;       //课程名称
            $score[$k]['course_credit']=$tr->find('td',6)->plaintext;       //学分
            $score[$k]['course_points']=$tr->find('td',7)->plaintext;       //绩点
            $score[$k]['course_score']=$tr->find('td',12)->plaintext;       //成绩
        }
        array_shift($score);
        $scores = json_encode($score, JSON_UNESCAPED_UNICODE);


        //储存课表在数据库
        $result = Score::where('student_id', session('xh') )->first();
        if ($result != null){

            $data['student_scores'] = $scores;
            $data['time'] = time();
            Score::where('student_id',session('xh'))->update($data);

        }else{

            $data['student_id'] = session('xh');
            $data['student_scores'] = $scores;
            $data['time'] = time();
            Score::create($data);
        }


        return $scores;

    }

    /**
     * 教室预约
     */
    public function classroom()
    {
        header("Content-type: text/html; charset=utf-8");
        $cookie = dirname(dirname(dirname(dirname(__FILE__)))) . '/Public/cookie/' . session('id') . '.txt';
        $url = "http://jwxt.gcu.edu.cn/xxjsjy.aspx?xh=" . session('xh') . "&xm=" . session('xm');
        $con1 = $this->CURL($url, $cookie, '');
        preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
        $post = array(
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => $view[1][0],
            'xiaoq' => '',      //校区
            'jslb' => '',       //教室类别
            'min_zws' => '0',    //座位最小
            'max_zws' => '',    //座位最大
            'kssj' => '419',       //星期几+第几周
            'xqj' => '4',        //星期几
            'ddlDsz' => iconv('utf-8', 'gb2312', '单'),     //单双周
            'sjd' => " '1'|'1','0','0','0','0','0','0','0','0' ",        //预约时间
            'Button2' => iconv('utf-8', 'gb2312', '空教室查询'),        //空教室查询 gbk编码 urlencode
            'xn' => '2017-2018',         //学年
            'xq' => '1',         //学期
            'ddlSyXn' => '2017-2018',    //
            'ddlSyxq' => '1'     //
        );
        $url1 = "http://jwxt.gcu.edu.cn/xxjsjy.aspx?xh=" . session('xh') . "&xm=" . session('xm');
        $content = $this->CURL($url1, $cookie, http_build_query($post));
        echo $content;
    }



}
