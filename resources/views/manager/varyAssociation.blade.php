@extends('common.adminLayout')
@section('title','社团')
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
                <h3 class="panel-title text-center">创建社团</h3>
             @elseif($act == 'vary')
                <h3 class="panel-title text-center">修改社团</h3>
             @endif
            </div>
            <div class="panel-body">
                <form action="{{ $act }}Association" method="post">
                    @if(isset($association))
                        <input class="form-control" type="text" name="association[name]" placeholder="名称" value="{{ old('association.name') ?: isset($association->name) ? $association->name : '' }}" />
                        <textarea class="form-control" name="association[short]" placeholder="简介" rows="4">{{ old('association.short') ?: isset($association->short) ? $association->short : '' }}</textarea>
                        <textarea class="form-control" name="association[introduce]" placeholder="详情" rows="9">{{ old('association.short') ?: isset($association->introduce) ? $association->introduce : '' }}</textarea>
                        <input class="form-control" type="text" name="association[require_info]" placeholder="额外字段" value="{{ old('association.require_info') ?: isset($association->require_info) ? $association->require_info : '' }}" />
                    @else
                        <input class="form-control" type="text" name="association[name]" placeholder="名称" value="{{ old('association.name') }}" />
                        <textarea class="form-control" name="association[short]" placeholder="简介" rows="4">{{ old('association.short') }}</textarea>
                        <textarea class="form-control" name="association[introduce]" placeholder="详情" rows="9">{{ old('association.introduce') }}</textarea>
                        <input class="form-control" type="text" name="association[require_info]" placeholder="额外字段，用空格隔开例如：爱好 特长" value="{{ old('association.require_info') }}" />
                    @endif
                    <input class="btn btn-default col-xs-12 form-control" type="submit" value="确定提交" />
                    {{ csrf_field() }}
                </form>
                @if($act == 'vary')
                    <br /><br /><br />
                    <form action="{{ route('uploadAssociationLogo') }}" enctype="multipart/form-data" method="post">
                        <input type="file" name="file" class="form-control">
                        <input type="submit" class="btn btn-default col-xs-12 form-control" value="上传logo" />
                        {{ csrf_field() }}
                    </form>
                    <br /><br /><br />
                    <form action="{{ route('uploadAssociationBackground') }}" enctype="multipart/form-data" method="post">
                        <input type="file" name="file" class="form-control">
                        <input type="submit" class="btn btn-default col-xs-12 form-control" value="上传背景" />
                        {{ csrf_field() }}
                    </form>
                @endif
                <input type="button" class="btn btn-default col-xs-12" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection