@extends('common.adminLayout')
@section('title','学生组织招新后台')
@section('style')
    <style>
        .table th {text-align: center;}
        #chartDiv {height: 300px;width: 100%;padding-top: 40px}
        #btnList {height: 38px;}
        #adminList {overflow-y: scroll;}
    </style>
@endsection
@section('content')
    @component('common.loading')@endcomponent
    <div class="container">
        <h3 class="text-center">学生组织招新情况</h3>
        <hr />
        <div id="chartDiv">
            <div class="chart" id="associationChart" style="width: 100%;height: 200px;display: none"></div>
            <div class="chart" id="schoolChart" style="width: 100%;height: 200px;display: none;"></div>
            <div class="chart" id="majorChart" style="width: 100%;height: 200px;display: none;"></div>
            <div class="chart" id="adminList" style="width: 100%;height: 200px;display: none;">
                <table class="table table-bordered text-center table-striped">
                    <tr>
                        <th>姓名</th>
                        <th>手机</th>
                        <th>组织</th>
                    </tr>
                    @foreach($managers as $m)
                        <tr>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->phone }}</td>
                            <td>{{ $m->association }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <p class="text-center">报名总人数：{{ $total }}</p>
            <p style="font-size: 14px;color:lightgray">说明：人数都是当前正在面试的人数，随着面试的进行，人数会逐渐减少，面试结束后人数会固定下来</p>
        </div>
        <hr />
        <div id="btnList">
            <button onclick="viewAssociationChart()" class="btn btn-default col-xs-3">按组织</button>
            <button onclick="viewSchoolChart()" class="btn btn-default col-xs-3">按学院</button>
            <button onclick="viewMajorChart()" class="btn btn-default col-xs-3">按专业</button>
            <button onclick="viewAdminList()" class="btn btn-default col-xs-3">联系人</button>
        </div>
    </div>
@endsection
@section('javascript')
<script src="{{ asset('static/js/highcharts.js') }}"></script>
<script src="{{ asset('static/js/exporting.js') }}"></script>
<script>
    var associationChart = document.getElementById('associationChart');
    var schoolChart = document.getElementById('schoolChart');
    var majorChart = document.getElementById('majorChart');
    var adminList = document.getElementById('adminList');
    var charts = document.getElementsByClassName('chart');
    viewAssociationChart();
    function viewAssociationChart()
    {
        for(var i = 0;i < charts.length;i++)
            charts[i].style.display = 'none';
        associationChart.style.display = 'block';
        Highcharts.chart('associationChart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '各组织报名人数'
            },
            tooltip: {
                pointFormat: '人数：{point.y}<br />占比：{point.percentage:.1f}%'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}人',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'brand',
                colorByPoint: true,
                data: [
                    @foreach($associationNum as $an)
                    {name:'{{ $an->name }}',y:{{ $an->count }},},
                    @endforeach
                ]
            }]
        });
        clearBrand();
    }
    function viewSchoolChart()
    {
        for(var i = 0;i < charts.length;i++)
            charts[i].style.display = 'none';
        schoolChart.style.display = 'block';
        Highcharts.chart('schoolChart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '各学院报名人数'
            },
            tooltip: {
                pointFormat: '人数：{point.y}<br />占比：{point.percentage:.1f}%'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}人',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'brand',
                colorByPoint: true,
                data: [
                        @foreach($schoolNum as $sn)
                    {name:'{{ $sn->name }}',y:{{ $sn->count }},},
                    @endforeach
                ]
            }]
        });
        clearBrand();
    }
    function viewMajorChart()
    {
        for(var i = 0;i < charts.length;i++)
            charts[i].style.display = 'none';
        majorChart.style.display = 'block';
        Highcharts.chart('majorChart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '各专业报名人数'
            },
            tooltip: {
                pointFormat: '人数：{point.y}<br />占比：{point.percentage:.1f}%'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}人',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'brand',
                colorByPoint: true,
                data: [
                        @foreach($majorNum as $mn)
                    {name:'{{ $mn->name }}',y:{{ $mn->count }},},
                    @endforeach
                ]
            }]
        });
        clearBrand();
    }
    function viewAdminList()
    {
        for(var i = 0;i < charts.length;i++)
            charts[i].style.display = 'none';
        adminList.style.display = 'block';
    }
    function clearBrand()
    {
        var allChartBtn = document.getElementsByClassName('highcharts-button highcharts-contextbutton');
        var allChartA =document.getElementsByClassName('highcharts-credits');
        for(var i = 0;i < allChartBtn.length;i++)
        {
            allChartBtn[i].style.display = 'none';
            allChartA[i].style.display = 'none';
        }
    }
</script>
@endsection
