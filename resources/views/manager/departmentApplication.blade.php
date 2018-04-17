<?php
    function getGender($int)
    {
        return $int == 1 ? '男' : '女';
    }
?>
@extends('common.adminLayout')
@section('title','部门招新')
@section('style')
    <style>
        #main {margin-top: 30px;}
        .form-control {margin-bottom: 10px;}
    </style>
@endsection
@section('content')
    <div id="main" class="container" style="margin-bottom: 50px;overflow: scroll">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">所有报名表</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered text-center">
                    <tr>
                        <td width="5%">编号</td>
                        <td width="25%">姓名</td>
                        <td width="5%">性别</td>
                        <td>手机</td>
                        <td>专业</td>
                    </tr>
                    @foreach($users as $u)
                        @if($u->round == $departmentRound && $departmentRound != 0)
                            <tr onclick="location='{{ route('departmentApplication',[$u->aid]) }}'" style="background: orange">
                        @elseif($u->round == $departmentRound - 1)
                            <tr onclick="location='{{ route('departmentApplication',[$u->aid]) }}'">
                        @elseif($u->round <= $departmentRound - 2)
                            <tr onclick="location='{{ route('departmentApplication',[$u->aid]) }}'" style="background: lightgray">
						@else
                            <tr onclick="location='{{ route('departmentApplication',[$u->aid]) }}'">
                        @endif
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ getGender($u->gender) }}</td>
                            <td style="font-size: 13px">{{ $u->phone }}</td>
                            <td>{{ $u->major }}</td>
                        </tr>
                    @endforeach
                </table>
                <input class="form-control" type="button" class="btn btn-default col-xs-12" value="灰色筛掉橙色通过，点某条查看详情并操作" disabled="disabled" />
                <input class="form-control" type="button" class="btn btn-default col-xs-12" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection