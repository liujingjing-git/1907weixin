@extends('layouts.admin')


@section('content')
   <center><h3>查询天气</h3></center>
    
    城市:<input type="text" name="city">
    <input type="button" value="搜索" id="search">(输入城市名称)
    <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <script>
        </script>

        <script type="text/javascript">
        //点击搜索按钮
            $("#search").on("click",function(){
                //获取城市名称
                var city = $('[name="city"]').val();
                if(city==""){
                   city="北京";
                }
                //正则检验
                var reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
                var res = reg.test(city);
                if(!res){
                    alert('城市名称只能为拼音或者汉字');
                    return;
                }

                $.ajax({
                    url:"{{url('admin/getshou')}}",
                    type:"GET",
                    data:{city:city},
                    dataType:"json",
                    success:function(res){
                        getshou(res.result);
                }
            })

            function getshou(res){
                    var categories =[];
                    var data = [];
                    $.each(res,function(i,v){
                        categories.push(v.days);
                        var arr =[parseInt(v.temp_low),parseInt(v.temp_hign)];
                        data.push(arr);
                    })
                    var chart = Highcharts.chart('container', {
                    chart: {
                        type: 'columnrange', // columnrange 依赖 highcharts-more.js
                        inverted: true
                    },
                    title: {
                        text: '一周天气气温'
                    },
                    subtitle: {
                        text: res[0]['citynm']
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: {
                        title: {
                            text: '温度 ( °C )'
                        }
                    },
                    tooltip: {
                        valueSuffix: '°C'
                    },
                    plotOptions: {
                        columnrange: {
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    return this.y + '°C';
                                }
                            }
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    series: [{
                        name: '温度',
                        data: data
                    }]
                }); 

            }   
        })
    </script>
@endsection