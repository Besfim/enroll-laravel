<?php
    function getGender($int)
    {
        return $int == 1 ? '男' : '女';
    }
?>
@extends('common.adminLayout')
@section('title','招新情况')
@section('style')
    <style>
        #main {margin-top: 30px;}
        .form-control {margin-bottom: 10px;}
        th {text-align: center;}
        .panel-body p {display: inline;}
    </style>
@show
@section('content')
    <div id="main" class="container" style="margin-bottom: 50px;overflow: scroll">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">报名状况概览</h3>
            </div>
            <div class="panel-body">
                @foreach($departmentNum as $d)
                    <p>{{ $d[0] . '：' . $d[1] . '人 丨 ' }}</p>
                @endforeach
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">所有报名表</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped text-center">
                    <tr>
                        <th width="5%">编号</th>
                        <th width="25%">姓名</th>
                        <th width="5%">性别</th>
                        <th width="20%">专业</th>
                        <th width="35%">部门</th>
                    </tr>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ getGender($u->gender) }}</td>
                            <td>{{ $u->major }}</td>
                            <td>{{ $u->departmentName }}</td>
                        </tr>
                    @endforeach
                </table>
                <input class="form-control" type="button" class="btn btn-default col-xs-12" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection