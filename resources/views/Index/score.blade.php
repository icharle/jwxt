<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="keywords" content="成绩清单"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="成绩清单" />
    <title>成绩清单</title>
    <link rel="stylesheet" href="{{ asset('public/js/layui/css/layui.css') }}" type="text/css" >
    <link rel="stylesheet" href="{{ asset('public/css/score.css') }}" type="text/css">
</head>
<style type="text/css">

</style>
<body style="background: rgb(236, 239, 246);">

<!--顶部-->
<div class="header">
    <div class="back" id="back"><i class="layui-icon" style="font-size: 7rem;">&#xe65c;</i></div>
    <div class="score">成绩清单</div>
</div>


<!--各学期的成绩-->
<div id="score_list">
    <div class="layui-collapse">
        <div id="colla">
        </div>
    </div>
</div>

<script src="{{ asset('public/js/layui/layui.js') }}"></script>
<script src="{{ asset('public/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/score.js') }}"></script>
<script>
    var url = '{{ url('chengji') }}';        //后台请求数据
    var url1 = '{{ url('index') }}';        //主页面
    var xh = '{{ $xh }}';             //学号
</script>
</body>
</html>