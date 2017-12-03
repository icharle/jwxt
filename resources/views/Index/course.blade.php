<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0 user-scalable=1" />
    <meta name="keywords" content="超级课程表"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="超级课程表" />
    <title>超级课程表</title>
    <link rel="stylesheet" href="{{ asset('public/css/course.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/js/layui/css/layui.css') }}" type="text/css" >
</head>
<body>

<!--顶部-->
<div class="header">
    <div class="back" id="back"><i class="layui-icon" style="font-size: 4rem;">&#xe65c;</i></div>
    <div class="week" id="week">
        <select name="choose_week">
            <option value="1">第 01 周</option>
            <option value="2">第 02 周</option>
            <option value="3">第 03 周</option>
            <option value="4">第 04 周</option>
            <option value="5">第 05 周</option>
            <option value="6">第 06 周</option>
            <option value="7">第 07 周</option>
            <option value="8">第 08 周</option>
            <option value="9">第 09 周</option>
            <option value="10">第 10 周</option>
            <option value="11">第 11 周</option>
            <option value="12">第 12 周</option>
            <option value="13">第 13 周</option>
            <option value="14">第 14 周</option>
            <option value="15">第 15 周</option>
            <option value="16">第 16 周</option>
            <option value="17">第 17 周</option>
            <option value="18">第 18 周</option>
            <option value="19">第 19 周</option>
            <option value="20">第 20 周</option>
        </select><i class="layui-icon">&#xe61a;</i>
    </div>
    <div class="add" id="add"><i class="layui-icon" style="font-size: 4.2rem; ">&#xe654;</i></div>
</div>

<!--表格-->
<table class="desk_table">

    <!--周几-->
    <tr class="tr_color weekday row">

        <td>节数</td>
        <td>周一</td>
        <td>周二</td>
        <td>周三</td>
        <td>周四</td>
        <td>周五</td>
        <td>周六</td>
        <td>周日</td>
    </tr>


    <!--第一二节课-->
    <tr class="row">
        <td class="tr_color">1</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
    </tr>
    <tr><td class="tr_color">2</td></tr>


    <!--第三四节课-->
    <tr class="row">
        <td class="tr_color">3</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
    </tr>
    <tr><td class="tr_color">4</td></tr>


    <!--第五六节课-->
    <tr class="row">
        <td class="tr_color">5</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
    </tr>
    <tr><td class="tr_color">6</td></tr>



    <!--第七八节课-->
    <tr class="row">
        <td class="tr_color">7</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
    </tr>
    <tr><td class="tr_color">8</td></tr>


    <!--第九十节课-->
    <tr class="row">
        <td class="tr_color">9</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2">&nbsp;</td>
    </tr>
    <tr><td class="tr_color">10</td></tr>

</table>

<!--添加课程弹框按钮-->
<div id="add_course" style="display: none">
    <ul>
        <li>
            <span>课名:</span>
            <input type="text" name="course_name">
        </li>
        <li>
            <span>星期:</span>
            <select name="course_weekday">
                <option value="0">星期天数</option>
                <option value="1">周一</option>
                <option value="2">周二</option>
                <option value="3">周三</option>
                <option value="4">周四</option>
                <option value="5">周五</option>
                <option value="6">周六</option>
                <option value="7">周日</option>
            </select>
        </li>
        <li>
            <span>节数:</span>
            <select name="course_num">
                <option value="0">选择节数</option>
                <option value="1">1,2</option>
                <option value="2">3,4</option>
                <option value="3">5,6</option>
                <option value="4">7,8</option>
                <option value="5">9,10</option>
            </select>
        </li>
        <li>
            <span>教室:</span>
            <input type="text" name="course_place">
        </li>
        <li>
            <span>老师:</span>
            <input type="text" name="teacher_name">
        </li>
    </ul>
</div>

<script src="{{ asset('public/js/layui/layui.js') }}"></script>
<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/course.js') }}"></script>
<script type="text/javascript">
    var url = '{{ url('kebiao') }}';        //后台请求
    var url1 = '{{ url('index') }}';         //主页面
    var weektime = '{!! $week !!}';          //当前周数
    $('#week').find("option[value={{ $week }}]").attr("selected",true);
</script>
</body>
</html>