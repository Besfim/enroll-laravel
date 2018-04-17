<?php $type= \Illuminate\Support\Facades\Session::get('managerType')?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-nav">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">GDPU</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ $type == 2 ? route('teacher') : route('admin') }}">首页</a></li>
                <li><a data-toggle="modal" href="{{ url('/') }}">新生首页</a></li>
                @if($type != 2)
                <li><a data-toggle="modal" data-target="#help-modal">帮助</a></li>
                @endif
                @if(isset($type))
                <li><a href="{{ route('adminLogout') }}">注销</a></li>
                @endif
                <li><a data-toggle="modal" data-target="#notice-modal">关于</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="modal-label">帮助</h4>
            </div>
            <div class="modal-body">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;执行不懂的操作时请先询问相关人员或者校团委网络中心，以保证系统稳定运行为优先事项。</p>
                <p>1、上传图片格式：<br />&nbsp;&nbsp;&nbsp;&nbsp;格式统一为png（图片的质量完好且体积较小）</p>
                <p>2、上传图片尺寸：<br />&nbsp;&nbsp;&nbsp;&nbsp;logo为正方形 150 * 150较适宜，logo的背景必须是透明的。<br />&nbsp;&nbsp;&nbsp;&nbsp;社团背景为360 * 200的长方形图片，部门背景为360 * 150的长方形图片，展示效果请到平台用户首页查看。</p>
                <p>3、短信使用须知：<br />&nbsp;&nbsp;&nbsp;&nbsp;短信操作与本平台的其他功能没有任何关系，是独立的一个部分，可用可不用，使用短信服务价格为每条0.1元，平台记录后由负责人收费。</p>
                <p>4、预留联系方式：<br />&nbsp;&nbsp;&nbsp;&nbsp;为了方便部长与新生的交流，请在部门介绍中写上联系方式。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">了解</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="modal-label">注意</h4>
            </div>
            <div class="modal-body">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;该系统为广药学生社团的招新工作提供网络技术支持，由校团委网络中心制作。若在使用过程中发现任何问题，或者想提供建议均可联系校团委网络中心。</p>
                <p>&nbsp;&nbsp;所用技术：Boostrap 3.3.7、Laravel 5.4.6。该系统版本号为0.9。</p>
                <p>&nbsp;&nbsp;开发人员注意：<br />1、Laravel框架较为激进，使用了大量PHP的新特性，请将PHP版本升至尽可能高。<br />2、为了上传大图（海报），请将php的文件上传大小限制设置为足够大。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">了解</button>
            </div>
        </div>
    </div>
</div>