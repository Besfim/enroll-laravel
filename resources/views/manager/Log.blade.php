@extends('common.adminLayout')
@section('title','后台登录')
@section('style')
    <style>
        #logTable input {margin-bottom: 15px}
        #logTable {margin-top: 50px;margin-bottom: 150px;}
        #others {text-align: right;width: 100%;height: 30px;}
        #others a {color: gray;}
    </style>
@endsection
@section('content')
    <div id="logTable" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">后台登录</h3>
            </div>
            <div class="panel-body">
                <form action="adminLog" method="post">
                    {{ csrf_field() }}
                    <input class="form-control" type="text" name="manager[phone]" placeholder="手机" value="{{ old('phone') }}">
                    <input class="form-control" type="password" name="manager[password]" placeholder="密码" value="{{ old('password') }}">
                    <input class="btn-info form-control" type="submit" value="登录">
                    <div id="others"><a href="{{ route('managerResetPassword') }}">忘记密码</a></div>
                </form>
            </div>
        </div>
    </div>
@endsection