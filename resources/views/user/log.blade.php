@extends('common.userLayout')
@section('title','用户登录')
@section('style')
    <style>
        #logTable input {margin-bottom: 15px}
        #logTable {margin-top: 50px;margin-bottom: 140px;}
        #others {text-align: right;width: 100%;height: 30px;}
        #others a {color: gray;}
    </style>
@show
@section('content')
    <div id="logTable" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">用户登录</h3>
            </div>
            <div class="panel-body">
                <form action="userLog" method="post">
                    {{ csrf_field() }}
                    <input class="form-control" type="text" name="user[phone]" placeholder="手机" value="{{ old('phone') }}">
                    <input class="form-control" type="password" name="user[password]" placeholder="密码" value="{{ old('password') }}">
                    <input class="btn-info form-control" type="submit" value="登录">
                    <div id="others"><a href="{{ route('userResetPassword') }}">忘记密码</a>&nbsp;&nbsp;&nbsp;或是&nbsp;&nbsp;&nbsp;<a href="{{ route('userRegister') }}">注册</a></div>
                </form>
            </div>
        </div>
    </div>
@endsection
