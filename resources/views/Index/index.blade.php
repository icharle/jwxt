<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0 user-scalable=1" />
    <meta name="keywords" content="个人信息中心"/>
    <meta name="description" content="个人信息中心" />
    <title>个人信息中心</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/index.css') }}">
</head>
<body>
<div class="header">
    <p>个人信息中心</p>
</div>
<div class="container">
    <div>
        <label>姓名:</label>
        <span>
            <?php
                $r = urldecode($xm);
                echo mb_convert_encoding($r, 'utf-8', 'gb2312');
            ?>
        </span>
    </div>
    <div>
        <label>学号:</label>
        <span>{{ $xh }}</span>
    </div>
    <div>
        <label>课表:</label>
        <a href="{{ url('course') }}">我的课表</a>
    </div>
    <div>
        <label>成绩:</label>
        <a href="{{ url('score') }}">我的成绩</a>
    </div>
</div>

</body>
</html>