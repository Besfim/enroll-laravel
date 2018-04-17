@extends('common.adminLayout')
@section('title','初次使用')
@section('style')
    <style>
        #main {margin-top: 50px;}
        .form-control {margin-bottom: 10px}
        .btn-default {margin-bottom: 10px;}
    </style>
@show
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">选择部门</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('chooseDepartment') }}" method="post">
                    <select name="manager[did]" class="form-control">
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <input class="btn btn-default col-xs-12" type="submit" value="确认选择" />
                    {{ csrf_field() }}
                </form>
                <button class="btn btn-default col-xs-12" onclick="location='{{ route('admin') }}'">返回首页</button>
            </div>
        </div>
    </div>
@endsection