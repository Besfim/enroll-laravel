@extends('common.adminLayout')
@section('title','社团部门')
@section('style')
    <style>
        #main {margin-top: 50px;}
        .form-control {margin-top: 10px}
        .btn-default {margin-top: 10px;}
    </style>
@endsection
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                @if($act == 'create')
                    <h3 class="panel-title text-center">创建部门</h3>
                @elseif($act == 'vary')
                    <h3 class="panel-title text-center">修改部门</h3>
                @endif
            </div>
            <div class="panel-body">
                <form action="{{ $act }}Department" method="post">
                    @if(isset($department))
                        <input class="form-control" type="text" name="department[name]" placeholder="名称" value="{{ old('department.name') ?: isset($department->name) ? $department->name : '' }}" />
                        <textarea class="form-control hidden" name="department[short]" placeholder="简介" rows="4">{{ old('department.short') ?: isset($department->short) ? $department->short : '' }}</textarea>
                        <textarea class="form-control" name="department[introduce]" placeholder="介绍" rows="9">{{ old('department.short') ?: isset($department->introduce) ? $department->introduce : '' }}</textarea>
                    @else
                        <input class="form-control" type="text" name="department[name]" placeholder="名称" value="{{ old('department.name') }}" />
                        <textarea class="form-control hidden" name="department[short]" placeholder="简介" rows="4">{{ old('department.short') }}</textarea>
                        <textarea class="form-control" name="department[introduce]" placeholder="介绍" rows="9">{{ old('department.introduce') }}</textarea>
                    @endif
                    <input class="btn btn-default col-xs-12 form-control" type="submit" value="确定提交" />
                    {{ csrf_field() }}
                </form>
                @if($act == 'vary')
                    <br /><br /><br />
                    <form action="{{ route('uploadDepartmentBackground') }}" enctype="multipart/form-data" method="post">
                        <input type="file" name="file" class="form-control">
                        <input type="submit" class="btn btn-default col-xs-12" value="上传背景" />
                        {{ csrf_field() }}
                    </form>
                @endif
                <input type="button" class="btn btn-default col-xs-12" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection