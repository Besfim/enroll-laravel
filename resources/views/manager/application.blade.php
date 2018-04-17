@extends('common.adminLayout')
@section('title','报名表')
@section('style')
    <style>
        #photo {float: right;width: 35%}
        .input-group {margin-bottom: 10px;}
        .bottom-btn {margin-bottom: 10px;}
    </style>
@endsection
@section('content')
    <div class="container" style="overflow: scroll;margin-bottom: 50px">
        @if($act == 'view&pass')
            <form action="{{ route('pass') }}" method="post">
            <?php $i = 0; ?>
        @endif
            <h3 class="text-center">报名表操作</h3><hr />
            <img id="photo" class="img-rounded" src="{{ route('viewUserPhoto',[$user->id . '.jpg']) }}" />
                <input type="text" name="application[id]" class="hidden" value="{{ $application->id }}" />
                <div class="input-group" style="margin-top: 20px;width: 60%">
                    <span class="input-group-addon">姓名</span>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled="disabled">
                </div>
                <div class="input-group" style="width: 60%">
                    <span class="input-group-addon">性别</span>
                    <input type="text" class="form-control" value="{{ $user->getGender() }}" disabled="disabled">
                </div>
                <div class="input-group" style="width: 60%">
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
                <div class="input-group">
                    <span class="input-group-addon">备注</span>
                    <textarea id="noteInput" class="form-control" name="application[note]" rows="2">{{ $application->note }}</textarea>
                </div>
                @foreach(json_decode($association->require_info) as $extra)
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon" style="display: inline-block;width: 100%;">{{ $extra }}</span>
                        @if($act == 'apply')
                            <textarea class="form-control" name="require_info[{{ $extra }}]" rows="3">{{ old('require_info.'.$extra) }}</textarea>
                        @else
                            <textarea class="form-control" name="require_info[{{ $extra }}]" rows="3" disabled="disabled">{{ $require_info[$i++] }}</textarea>
                        @endif
                    </div>
                @endforeach
                @if($act == 'view&pass')
                    <input type="button" class="btn btn-default col-xs-12 bottom-btn" value="修改备注" onclick="note()" />
                    @if($application->round == $departmentRound && $departmentRound != 0)
                            <input type="submit" class="btn btn-default col-xs-12 bottom-btn" value="已经通过该轮" disabled="disabled">
                        @elseif($departmentRound == 0)
                            <input type="submit" class="btn btn-default col-xs-12 bottom-btn" value="面试尚未开始" disabled="disabled">
                        @elseif($application->round == $departmentRound - 1)
                            <input type="submit" class="btn btn-default col-xs-12 bottom-btn" value="通过该轮面试">
                        @elseif($application->round <= $departmentRound - 2)
                            <input type="submit" class="btn btn-default col-xs-12 bottom-btn" value="该表已被筛掉" disabled="disabled">
                    @endif
                @else
                @endif
                <input type="button" class="btn btn-default col-xs-12 bottom-btn" onclick="location='{{ route('departmentApplication') }}'" value="返回报名列表">
                {{ csrf_field() }}
            </form>
    </div>
@endsection
@section('javascript')
    <script>
        function note()
        {
            var postData = "id={{ $application->id }}&note=" + document.getElementById('noteInput').value + "&_token={{ csrf_token() }}";
            muyu_post('{{ route('note') }}',postData,function()
            {
                alert(data.msg);
            });
        }
        function muyu_post(url,postData,callback)
        {
            var request = new XMLHttpRequest();
            request.open("POST",url);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.send(postData);
            request.onreadystatechange=function()
            {
                if(request.readyState===4)
                    if(request.status===200)
                    {
                        data=JSON.parse(request.responseText);
                        callback();
                    }
            }
        }
    </script>
@endsection