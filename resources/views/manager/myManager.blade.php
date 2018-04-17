<?php $type = \Illuminate\Support\Facades\Session::get('managerType') ?>
@extends('common.adminLayout')
@section('title','管理人员')
@section('style')
    <style>
        #main {margin-top: 30px;}
        .form-control {margin-bottom: 10px;}
        th {text-align: center;}
    </style>
@endsection
@section('content')
    <div id="main" class="container" style="margin-bottom: 50px;overflow: scroll">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">所有管理员</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered text-center table-striped">
                    <tr>
                        <th width="25%">姓名</th>
                        <th>手机</th>
                        <th width="25%">部门</th>
                        <th>类别</th>
                    </tr>
                    @foreach($managers as $m)
                        <tr>
                            <td>{{ $m->name }}</td>
                            <td style="font-size: 13px;line-height: 25px;">{{ $m->phone }}</td>
                            @if($m->did == 0)
                                @if($m->aid != 0)
                                    <td>{{ $m->association->name }}</td>
                                @else
                                    <td></td>
                                @endif
                            @else
                                @if($type == 3 || $type == 4)
                                    <td>{{ $m->department->name }}</td>
                                @elseif($type == 1)
                                    <td>{{ $m->association->name . $m->department->name }}</td>
                                @endif
                            @endif
                            <td>{{ $m->getType() }}</td>
                        </tr>
                    @endforeach
                </table>
                @if($type == 3)
                    <input class="form-control" type="button" class="btn btn-default col-xs-12" value="新增人员" onclick="location='{{ route('addManager') }}'" />
                @elseif($type == 1)
                    <button class="btn btn-default col-xs-12 form-control" onclick="location='{{ route('createManager') }}'">新增社管</button>
                @endif
                <input class="form-control" type="button" class="btn btn-default col-xs-12 form-control" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection