@extends('common.userLayout')
@section('title','用户注册')
@section('style')
    <style>
        #main {margin-top: 10px;}
        .input-group {margin-bottom: 10px;}
        #captchaImg {width: 100%}
    </style>
@endsection
@section('content')
    <div class="container">
        <div id="main" class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">个人信息</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('userRegister') }}" method="post">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input id="phoneInput" type="text" name="user[phone]" class="form-control" value="{{ old('user.phone') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">密码</span>
                        <input type="text" name="user[password]" class="form-control" value="{{ old('user.password') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">图形验证码</span>
                        <img id="captchaImg" src="{{ url('/captcha') }}" onclick="this.src='{{ url('/captcha') }}?r='+Math.random();">
                        <input id="captchaInput" class="form-control" value="{{ old('captcha') }}" type="text" name="captcha" style="width: 40%" />
                        <input type="button" class="form-control" value="获取手机验证码" onclick="getSMScode()" style="width: 60%" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">手机验证码</span>
                        <input type="text" class="form-control" name="SMScode" value="{{ old('SMScode') }}"/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">姓名</span>
                        <input type="text" name="user[name]" class="form-control" value="{{ old('user.name') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">性别</span>
                        <select name="user[gender]" class="form-control">
                            <option value="1" {{ old('user.gender') == 1 ? "selected='selected'" : ""}}>男</option>
                            <option value="2" {{ old('user.gender') == 2 ? "selected='selected'" : ""}}>女</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">生日</span>
                        <input type="date" name="user[birth]" class="form-control" value="{{ old('user.birth') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">学院</span>
                        <select id="school" onchange="finishChooseSchool()" name="user[school]" class="form-control">
                            <option value="">请选择学院</option>
                            @foreach(config('option') as $key => $val)
                            <option value="{{ $key }}" {{ old('user.school') == $key ? "selected='selected'" : '' }}>{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">专业</span>
                        <select id="major" name="user[major]" class="form-control">
                            <option value="">请选择专业</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">班级</span>
                        <input type="number" name="user[class]" class="form-control" placeholder="请填数字，例如：2" value="{{ old('user.class') }}">
                    </div>
                    <input class="btn-info form-control" type="submit" value="注册">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')<script>
    var majorSelect = document.getElementById('major');
    var option = JSON.parse('<?php echo json_encode(config('option'),JSON_UNESCAPED_UNICODE) ?>');
    finishChooseSchool();
    function finishChooseSchool()
    {
        var school = document.getElementById('school').value;
        var oldMajor = '{{ old('user.major') }}';
        if(school !== '')
        {
            majorSelect.innerHTML = '<option value="">请选择专业</option>';
            for(var i = 0;i < option[school].length;i++)
            {
                if(oldMajor === option[school][i])
                    majorSelect.innerHTML += "<option selected='selected' value='" + option[school][i] + "'>" + option[school][i] + "</option>";
                else
                    majorSelect.innerHTML += "<option value='" + option[school][i] + "'>" + option[school][i] + "</option>";
            }
        }
    }
    function getSMScode()
    {
        var captcha = document.getElementById("captchaInput").value;
        var phone = document.getElementById("phoneInput").value;
        var postData = "captcha=" + captcha + "&phone=" + phone + "&_token={{ csrf_token() }}";
        muyu_post('captcha4SendSMS',postData,function()
        {
            alert(data['msg']);
            if(data['status'] != 1 && data['status'] != 4)
            {
                document.getElementById('captchaImg').src = "{{ url('/captcha') }}?r='+Math.random();";
                document.getElementById("captchaInput").value = "";
            }
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