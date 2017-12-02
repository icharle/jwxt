//加载layui模块
layui.use(['jquery','layer'],function () {
    var layer = layui.layer;
    $.support.cors =true;



    //添加课程的监听
    $('#add').click(function () {
        layer.open({
            type: 1,
            content: $('#add_course'),
            scrollbar: false,
            anim: 2,
            shadeClose: true,
            closeBtn: 2,
            btn: ['添加'],
            yes: function (index) {
                var $add_course = $('#add_course'), color_num = parseInt(Math.random()*9+1), $rows = $('tr.row');
                var course_name = $add_course.find("input[name='course_name']").val();
                var course_weekday = $add_course.find("select[name='course_weekday'] option:selected").val();
                var course_num = $add_course.find("select[name='course_num'] option:selected").val();
                var course_place = $add_course.find("input[name='course_place']").val();
                var teacher_name = $add_course.find("input[name='teacher_name']").val();

                if ( course_name=='' || course_weekday== 0 || course_num== 0 || course_place=='' ){
                    layer.msg('添加失败',{icon:5,time:1000});
                }else{
                    $rows.eq(course_num).find("td").eq(course_weekday).removeClass().addClass("color"+color_num).html(course_name+"<br>"+course_place+"@"+teacher_name);
                    layer.msg('添加成功',{icon:6,time:1000});
                    layer.close(index);
                }
            }
        });
    });


    //加载完页面后自动请求，展示第一周的课程
    ajax(1);

    //选择周数
    $('#week').change(function () {
        var $choose_week = $('#week');
        var weeks = $choose_week.find("select[name='choose_week'] option:selected").val();
        ajax(weeks);
    });



    //向后台请求数据并且绘制
    function ajax(weeks) {
        $.ajax({
            type: 'POST',
            url: "http://keepfriend.cn/kcb/course_schedule.php",
            data:{
                week: weeks
            },
            dataType: "json",
            success:function (data) {
                var content = data.content;

                //清空重绘课程表
                for (var i = 1; i <8; i++) {
                    for (var j = 1; j <6; j++) {
                        $("tr.row").eq(j).find("td").eq(i).removeClass().html("&nbsp;");
                    }
                }

                //统计json条数
                function JSONLength(obj) {
                    var size = 0, key;
                    for (key in obj) {
                        if (obj.hasOwnProperty(key)) size++;
                    }
                    return size;
                }

                //遍历打印表格
                for (var i = 0; i < JSONLength(content); i++) {
                    if(content[i].week_begin <= parseInt(weeks) && content[i].week_end >= parseInt(weeks) ){
                        if ( ( parseInt(weeks) %content[i].week_odd == 0) || content[i].week_odd == 0 ) {
                            if (content[i].class_begin == 1) {
                                temp = 1;
                            }else if (content[i].class_begin == 3) {
                                temp = 2;
                            }else if (content[i].class_begin == 5) {
                                temp = 3;
                            }else if (content[i].class_begin == 7) {
                                temp = 4;
                            }else if (content[i].class_begin == 9) {
                                temp = 5;
                            }
                            var color_num=parseInt(Math.random()*9+1);
                            $("tr.row").eq(parseInt(temp)).find("td").eq(parseInt(content[i].weekday)).removeClass().addClass("color"+color_num)
                                .html(content[i].name+"<br>"+content[i].place+"@"+content[i].teacher);
                        }
                    }
                }

            },error:function(data){
                layer.msg("加载出现错误！可能是网络问题");
            }
        });
    }





});