@extends('common.userLayout')
@section('title','个人信息')
@section('style')
    <style>
        #main {margin-top: 50px;margin-bottom: 80px;}
        #photo {float: right;width: 35%}
        .input-group {margin-bottom: 10px;}
        .bottom-btn {margin-top: 20px;margin-left: 15px;display:block;float: left;width: 90px;}
        .submitBtn {margin-top: 10px;}
        #photoClipDiv {z-index: 10000;height: 420px;background: lightgray;position: fixed;top: 100px;display: none;}
        #clipArea {margin: 20px;height: 300px;}
        #file,#clipBtn {margin: 20px;  }
    </style>
@endsection
@section('content')
<div id="main" class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center">个人信息</h3>
        </div>
        <div class="panel-body">
            <img id="photo" class="img-rounded" src="{{ route('viewUserPhoto',[Session::get('user').'.jpg']) }}" />
            <form action="{{ route('varyUser') }}" method="post">
                <div class="input-group" style="margin-top: 20px;width: 60%">
                    <span class="input-group-addon">姓名</span>
                    <input type="text" name="user[name]" class="form-control" value="{{ $user->name }}" disabled="disabled">
                </div>
                <div class="input-group" style="width: 60%">
                    <span class="input-group-addon">性别</span>
                    <select name="user[gender]" class="form-control" disabled="disabled">
                        <option value="1" {{ $user->gender == 1 ? "selected='selected'" : ""}}>男</option>
                        <option value="2" {{ $user->gender == 2 ? "selected='selected'" : ""}}>女</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">生日</span>
                    <input type="date" name="user[birth]" class="form-control" value="{{ $user->birth }}" disabled="disabled">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">学院</span>
                    <select id="school" onchange="finishChooseSchool()" name="user[school]" class="form-control" disabled="disabled">
                        @foreach(config('option') as $key => $val)
                            <option value="{{ $key }}" {{ $user->school == $key ? "selected='selected'" : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">专业</span>
                    <select id="major" name="user[major]" class="form-control" disabled="disabled">
                        <option value="{{ $user->major }}">{{ $user->major }}</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">班级</span>
                    <input type="number" name="user[class]" class="form-control" value="{{ $user->class }}" disabled="disabled">
                </div>
                <input type="submit" class="btn btn-primary col-xs-12 submitBtn hidden" value="确认修改" />
                {{ csrf_field() }}
            </form>
        </div>
        <button class="btn btn-info bottom-btn" id="varyBtn" onclick="startVary()">修改信息</button>
        <button class="btn btn-info bottom-btn" onclick="document.getElementById('photoClipDiv').style.display = 'block'">修改头像</button>
        <button class="btn btn-info bottom-btn" onclick="location='{{ url('/') }}'">返回首页</button>
    </div>
    <div id="photoClipDiv" class="col-xs-12 row">
        <div id="clipArea"></div>
        <input type="file" id="file" style="display:none;">
        <input type="button" value="取消" class="btn btn-info" onclick="document.getElementById('photoClipDiv').style.display = 'none'">
        <input type="button" value="选择相片" class="btn btn-info" onclick="file.click()">
        <input type="button" value="上传" class="btn btn-primary" id="clipBtn">
    </div>
</div>
@endsection
@section('javascript')
    <script src="{{ asset('static/js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('static/js/hammer.js') }}"></script>
    <script src="{{ asset('static/js/iscroll-zoom.js') }}"></script>
    <script src="{{ asset('static/js/lrz.all.bundle.js') }}"></script>
    <script src="{{ asset('static/js/jquery.photoClip.min.js') }}"></script>
    <script>
        var clipArea = new bjj.PhotoClip("#clipArea", {
            size: [200, 200],
            outputSize: [200, 200],
            file: "#file",
            ok: "#clipBtn",
            clipFinish: function(dataURL) {
                $.post("{{ route('uploadUserPhoto') }}", {"file":dataURL,"_token":"{{ csrf_token() }}"},function(data){
                    if(data.status == 0)
                        alert("头像上传失败");
                    else {
                        document.getElementById('photoClipDiv').style.display = 'none';
                        document.getElementById('photo').src="{{ route('viewUserPhoto',[Session::get('user').'.jpg']) }}" + "?r=" + Math.random();
                    }
                });
            },
        });
        var majorSelect = document.getElementById('major');
        var option = JSON.parse('<?php echo json_encode(config('option'),JSON_UNESCAPED_UNICODE) ?>');
        function finishChooseSchool()
        {
            var school = document.getElementById('school').value;
            if(school !== '')
            {
                majorSelect.innerHTML = '<option value="">请选择专业</option>';
                for(var i = 0;i < option[school].length;i++)
                    majorSelect.innerHTML += "<option value='" + option[school][i] + "'>" + option[school][i] + "</option>";
            }
        }
        function startVary()
        {
            var inputs = document.getElementsByClassName('form-control');
            for(var i = 0;i < inputs.length;i++)
                inputs[i].disabled = ''
            document.getElementsByClassName('submitBtn')[0].className = "btn btn-primary col-xs-12 submitBtn";
            document.getElementById('varyBtn').onclick = function(){quitVary()};
            document.getElementById('varyBtn').innerHTML = "取消修改";
        }
        function quitVary()
        {
            var inputs = document.getElementsByClassName('form-control');
            for(var i = 0;i < inputs.length;i++)
                inputs[i].disabled = 'disabled'
            document.getElementsByClassName('submitBtn')[0].className = "btn btn-info col-xs-12 submitBtn hidden";
            document.getElementById('varyBtn').onclick = function(){startVary()};
            document.getElementById('varyBtn').innerHTML = "修改信息";
        }
    </script>
@endsection