<?php
$isLog = \Illuminate\Support\Facades\Request::cookie('userPhone');
$route = \Illuminate\Support\Facades\Route::currentRouteName();
?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-nav" style="background: #0bc5de;border: 0px;">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" style="border: 0px;background: #0bc5de" onclick="if(this.style.borderBottomWidth === '0px') this.style.border = '1px white solid'; else this.style.border = '0px';">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a id="GDPU" class="navbar-brand" href="#" style="color: white">GDPU</a>
        </div>
        <div class="navbar-collapse collapse" style="border: 1px white solid;background: #0bc5de;">
            <ul class="nav navbar-nav">
                <li><a style="color: white;" href="{{ url('/') }}">回到首页</a></li>
                @if($route != 'userLog')
                <li><a style="color: white;" href="{{ route('userInfo') }}">个人信息</a></li>
                <li><a style="color: white;" href="{{ route('userApplication') }}">我的面试</a></li>
                @endif
                @if($isLog)
                <li><a style="color: white;" href="{{ route('userLogout') }}">注销</a></li>
                @endif
                <li><a style="color: white;" data-toggle="modal" data-target="#about-modal">关于</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="modal fade" id="about-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true"></span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="modal-label">关于</h4>
            </div>
            <div class="modal-body">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;广药社团招新平台v0.9，为广药各大社团的招新工作提供网络支持。</p>
                <br />
                <p class="text-right">Powered By 校团委网络中心&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="location='{{ route('admin') }}'">后台</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">了解</button>
            </div>
        </div>
    </div>
</div>