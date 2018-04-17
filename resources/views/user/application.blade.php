@extends('common.userLayout')
@section('title','报名表')
@section('style')
    <style>
        .input-group {margin-bottom: 10px;}
        .btn-info {margin-top :10px;}
    </style>
@endsection
@section('content')
    <div class="container" style="overflow: scroll;margin-bottom: 50px">
        @if($act == 'apply')
            <form action="{{ route('apply',$did) }}" method="post">
            @else
                <?php $extra_info = json_decode($application->require_info);$i = 0; ?>
                <form>
                @endif
                <h3 class="text-center">{{ $applicationName }}报名表</h3><hr />
                <div class="input-group" style="margin-top:20px;">
                    <span class="input-group-addon">姓名</span>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">性别</span>
                    <input type="text" class="form-control" value="{{ $user->getGender() }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">生日</span>
                    <input type="text" class="form-control" value="{{ $user->birth }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">学院</span>
                    <input type="text" class="form-control" value="{{ $user->school }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">专业</span>
                    <input type="text" class="form-control" value="{{ $user->major }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">班级</span>
                    <input type="text" class="form-control" value="{{ $user->class }}" disabled="disabled">
                </div>
                @foreach($require_info as $extra)
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon" style="display: inline-block;width: 100%;">{{ $extra }}</span>
                        @if($act == 'apply')
                            <textarea class="form-control" name="require_info[{{ $extra }}]" rows="3">{{ old('require_info.'.$extra) }}</textarea>
                        @else
                            <textarea class="form-control" name="require_info[{{ $extra }}]" rows="3" disabled="disabled">{{ $extra_info[$i++] }}</textarea>
                        @endif
                    </div>
                @endforeach
                @if($act == 'apply')
                    <input type="submit" class="btn btn-primary col-xs-12" value="提交">
                @endif
                    <input type="button" class="btn btn-info col-xs-12" onclick="history.back()" value="返回">
                {{ csrf_field() }}
            </form>
    </div>
@endsection