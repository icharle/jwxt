<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录系统</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/login.css') }}">
    <script src="{{ asset('public/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/js/layer.js') }}"></script>
</head>
<body>
<div class="container">
    <div><input type="text" name="xh" id="xh" placeholder="请输入学号"></div>
    <div><input type="password" name="pw" id="pw" placeholder="请输入密码"></div>
    <div>
        <input type="text" id="yzm" style="width: 35%;vertical-align:middle" placeholder="请输入验证码">
        <img src="{{ $captcha_path }}" style="width: 30%; height: 10rem; vertical-align:middle">
    </div>
    <div>
        <button onclick="submit()">登录</button>
    </div>
</div>
</body>
<script>

    /*
    提交按钮
     */
    function submit() {
        var xh = $('#xh').val();
        var pw = $('#pw').val();
        var yzm = $('#yzm').val();

        //ajax提交
        $.ajax({
            type: 'POST',
            url: "{{ url('login') }}",
            data: {
                xh : xh,
                pw : pw,
                yzm : yzm,
                _token : '{{ csrf_token() }}'
            } ,
            success: function (data) {
                if (data.status == 1){
                    location.href = '{{ url('index') }}';
                    layer.msg('登录成功', {icon: 6});
                }else {
                    layer.msg('登录失败', {icon: 5});
                }
            }
        });
    }

</script>
</html>