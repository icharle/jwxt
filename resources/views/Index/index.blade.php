<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登陆界面</title>
    <script src="{{ asset('public/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('public/js/layer.js') }}"></script>
</head>
<style>
    body{
        background-color:  #00AAFF;
    }
    h1{
        margin-top: 20%;
        font-size: 60px;
        color: white;
    }
    input{
        width: 330px;
        height: 50px;
        border: none;
        border-radius: 5px;
        margin-top: 30px;
        box-shadow: 10px 10px 5px black;
    }
</style>
<body>
<center>
    <h1>教务系统模拟登录系统</h1>
    <input type="text" name="xh" placeholder="学号" id="xh">
    <br>
    <input type="password" name="pw" placeholder="密码" id="pw">
    <br>
    <input type="text" name="yzm" placeholder="验证码" id="yzm" style="width: 250px; margin-left: -5px;">
    <img src="{{ url('/public/yzm/verifyCode.jpg') }}" style="width: 72px; height: 27px; position: relative; top: 10px;">
    <br>
    <input type="submit" value="登录" onclick="submit()" style="background-color: white">
</center>
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
                _token : '{{ csrf_token() }}',
           } ,
            success: function (data) {
                if (data.status == 1){
                    location.href = ' {{ url('admin/article') }} ' ;
                    layer.msg('添加文章成功', {icon: 6});
                }else {
                    layer.msg('添加文章失败', {icon: 5});
                }
            }
        });
    }

</script>
</html>