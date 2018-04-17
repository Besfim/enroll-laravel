@extends('common.adminLayout')
@section('title','超级管理员')
@section('style')
    <style>
        #main {margin-top: 50px;}
        .form-control {margin-bottom: 10px}
    </style>
@show
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">创建社管</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('createManager') }}" method="post">
                    <input class="form-control" type="text" name="manager[phone]" placeholder="电话" value="{{ old('manager.phone') }}" />
                    <input class="form-control" type="text" name="manager[name]" placeholder="姓名" value="{{ old('manager.name') }}" />
                    <input class="form-control" type="text" name="manager[password]" placeholder="密码" value="{{ old('manager.password') }}" />
                    <input class="form-control" type="submit" />
                    {{ csrf_field() }}
                </form>
                <button class="btn btn-default col-xs-12" onclick="location='{{ route('admin') }}'">返回首页</button>
            </div>
        </div>
    </div>
@endsection