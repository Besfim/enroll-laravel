@extends('common.userLayout')
@section('title','重置密码')
@section('style')
    <style>
        #main {margin-top: 50px;}
        .input-group {margin-bottom: 10px;}
        #captchaImg {width: 100%}
    </style>
    @show
@section('content')
    <div class="container">
        <div id="main" class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">忘记密码</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('userResetPassword') }}" method="post">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input id="phoneInput" type="text" name="user[phone]" class="form-control" value="{{ old('user.phone') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">新密码</span>
                        <input type="password" name="user[password]" class="form-control" value="{{ old('user.password') }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">图形验证码</span>
                        <img id="captchaImg" src="{{ url('/captcha') }}" onclick="this.src='{{ url('/captcha') }}?r='+Math.random();">
                        <input id="captchaInput" class="form-control" value="{{ old('captcha') }}" type="text" name="captcha" style="width: 40%" />
                        <input type="button" class="form-control" value="获取手机验证码" onclick="getSMScode()" style="width: 60%" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">手机验证码</span>
                        <input type="text" class="form-control" name="SMScode" />
                    </div>
                    <input class="btn-info form-control" type="submit" value="重置密码">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')<script>
    function getSMScode()
    {
        var captcha = document.getElementById("captchaInput").value;
        var phone = document.getElementById("phoneInput").value;
        var postData = "captcha=" + captcha + "&phone=" + phone + "&_token={{ csrf_token() }}";
        muyu_post('captcha4SendSMS',postData,function()
        {
            alert(data['msg'])
            if(data['status'] != 1 || data['status'] != 4)
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