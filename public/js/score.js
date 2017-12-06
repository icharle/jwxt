layui.use(['element'], function(){
    var layer = layui.layer;
    var element = layui.element;


    ajax(xh);     //执行向后台加载数据

    //监听返回链接
    $('#back').click(function () {
        location.href = url1;
    });



    function ajax(xh) {
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                xh: xh
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success:function (data) {
                var content = data;
                var html = '';

                //统计json条数
                function JSONLength(obj) {
                    var size = 0, key;
                    for (key in obj) {
                        if (obj.hasOwnProperty(key)) size++;
                    }
                    return size;
                }

                //获取学期总数
                var arr = new Array();
                var j=0;
                for (var i=0; i<JSONLength(content); i++){
                    if (j==0 || arr[j-1] != content[i].course_year+content[i].course_term ){
                        arr[j] = content[i].course_year+content[i].course_term;
                        j++;
                    }
                }


                //动态生成多个成绩面板
                for (var i=0; i<arr.length; i++){

                    html += '<div class="layui-colla-item">'+'<h2 class="layui-colla-title">'+arr[i].substring(0,9)+'学年 第'+arr[i].substring(9,10)+'学期'+'</h2>'+'<div class="layui-colla-content">'+'<table class="layui-table">'+
                        '<tr>'+
                        '<td>课程</td>'+
                        '<td>分数</td>'+
                        '<td>绩点</td>'+
                        '<td>学分</td>'+
                        '</tr>';

                    for(var j=0; j<JSONLength(content); j++){

                        if (arr[i] == content[j].course_year+content[j].course_term){
                            html += '<tr>'+
                                '<td>'+content[j].course_name+'</td>'+
                                '<td>'+content[j].course_score+'</td>'+
                                '<td>'+content[j].course_points+'</td>'+
                                '<td>'+content[j].course_credit+'</td>'+
                                '</tr>'
                        }

                    }
                    html +='</table>'+'</div>'+'</div>';
                }

                $('#colla').html(html);     //嵌入HTML中
                element.init('collapse');      //重新加载面板

            }
        })
    }

});
