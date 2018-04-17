@extends('common.adminLayout')
@section('title','社团招新后台')
@section('style')
    <style>
        #main {margin-top: 30px;}
        .oper {margin-bottom: 5px;}
        hr {width: 100%}
        .btn-default {margin-top: 10px;}
    </style>
@show
@section('content')
    <div id="main" class="container" style="margin-bottom: 30px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                @if($type == 1)
                    <h3 class="panel-title text-center">超级管理员</h3>
                @else
                    @if($association && $department)
                        <h3 class="panel-title text-center">{{ $association->name . $department->name }}</h3>
                    @elseif($association)
                        <h3 class="panel-title text-center">{{ $association->name }}</h3>
                    @else
                        <h3 class="panel-title text-center"></h3>
                    @endif
                @endif
            </div>
            <div class="panel-body text-center">
                @if(!in_array($type,[1,2]))
                    @if($type == 4)
                    <p>当前面试轮数：{{ $departmentRound }}</p>
                    @endif
                @endif
                <p>总计报名人数：{{ $applicationNum }}</p>
                <hr />
                @if(!in_array($type,[1,2]))
                    @if($association)
                        <p>【招新】</p>
                        @if($type == 4)
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('departmentApplication') }}'">查看报名</button>
                            @if(!in_array($departmentRound,['未开始','已结束']))
                                <button class="btn btn-default col-xs-12 oper" onclick="nextRound()">到下一轮</button>
                            @elseif($departmentRound == '未开始')
                                <button class="btn btn-default col-xs-12 oper" onclick="nextRound()">开始面试</button>
                            @endif
                            @if(!in_array($departmentRound,['未开始','已结束']))
                                <button class="btn btn-default col-xs-12 oper" onclick="finish()">结束招新</button>
                            @endif
                        @else
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('associationApplication') }}'">查看报名</button>
                        @endif

                        <hr />
                        <p>【维护】</p>
                        @if($type == 3)
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('createDepartment') }}'">创建部门</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('varyAssociation') }}'">修改社团</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('myDepartment') }}'">部门列表</button>
                        @else
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('varyDepartment') }}'">修改部门</button>
                        @endif
                        <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('myManager') }}'">管理人员</button>
                        <hr />
                        <p>【报表】</p>
                        @if($type == 3)
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('getAssociationDataExcel') }}'">招新资料</button>
                        @else
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('getDataExcel') }}'">招新资料</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('getSignExcel') }}'">签到用表</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('getInterviewExcel') }}'">面试用表</button>
                        @endif
                        <hr />
                        @if($type == 4)
                        <p>【短信】</p>
                            <button class="btn btn-default col-xs-12 oper" onclick="sendStartSMS()">开始面试</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="sendNextSMS()">通过该轮</button>
                            <button class="btn btn-default col-xs-12 oper" onclick="sendPassSMS()">通过考核 & 见面事宜</button>
                        @endif
                    @else
                        <p>【初次使用】</p>
                        <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('createAssociation') }}'">创建社团</button>
                    @endif
                @endif
                @if($type == 1)
                    <p>超管操作</p>
                    <button class="btn btn-default col-xs-12 oper" onclick="location='{{ route('myManager') }}'">管理人员</button>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        function nextRound()
        {
            var ensure = prompt("确认进入下一轮？请输入确认");
            if(ensure != "确认")
                return;
            var postData = "_token={{ csrf_token() }}";
            muyu_post("{{ route('nextRound') }}",postData,function()
            {
                if(data.status == 1)
                {
                    alert(data.msg);
                    location.reload();
                }
                else
                    alert(data.msg);
            });
        }
        function finish()
        {
            var ensure = prompt("确认结束招新？请输入确认");
            if(ensure != "确认")
                return;
            var postData = "_token=" + "{{ csrf_token() }}";
            muyu_post("{{ route('finish') }}",postData,function(){
                if(data.status == 1)
                {
                    alert(data.msg);
                    location.reload();
                }
                else
                    alert(data.msg);
            });
        }
        function sendStartSMS()
        {
            if(confirm("确认要发送开始面试的通知短信吗？") == false)
                return;
            else
            {
                var time = prompt("请输入面试的时间");
                if(time == null)
                    return;
                var place = prompt("请输入面试的地点");
                if(place == null)
                    return;
                muyu_post("{{ route('sendStartSMS') }}","_token={{ csrf_token() }}&time=" + time + "&place=" + place,function(){alert(data.msg)});
            }
        }
        function sendNextSMS()
        {
            if(confirm("确认要发送进入下一轮的通知短信吗？") == false)
                return;
            else
            {
                var time = prompt("请输入面试的时间");
                if(time == null)
                    return;
                var place = prompt("请输入面试的地点");
                if(place == null)
                    return;
                muyu_post("{{ route('sendNextSMS') }}","_token={{ csrf_token() }}&time=" + time + "&place=" + place,function(){alert(data.msg)});
            }
        }
        function sendPassSMS()
        {
            if(confirm("确认要发送通过考核与见面会事宜的通知短信吗？") == false)
                return;
            else
            {
                var time = prompt("请输入见面会的时间");
                if(time == null)
                    return;
                var place = prompt("请输入见面会的地点");
                if(place == null)
                    return;
                muyu_post("{{ route('sendPassSMS') }}","_token={{ csrf_token() }}&time=" + time + "&place=" + place,function(){alert(data.msg)});
            }
        }
        function muyu_post(url,postData,callback)
        {
            var request = new XMLHttpRequest();
            request.open("POST",url);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.send(postData);
            request.onreadystatechange=function()
            {
                if(request.readyState===4)
                    if(request.status===200)
                    {
                        data=JSON.parse(request.responseText);
                        callback();
                    }
            }
        }
    </script>
@endsection