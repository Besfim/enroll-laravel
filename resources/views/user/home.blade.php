@extends('common.userLayout')
@section('title','广药社团招新平台')
@section('style')
    <style>
        #openImg {width: 360px;margin-left: -15px;opacity: 0;filter: blur(2px);}
        #openFont {font-size: 24px;font-family: 幼圆;opacity: 0;position: absolute;top: 300px;left: 65px;}
        #GDPU {opacity: 0;}
        .openRow {padding-left: 55px;margin-top: 20px;margin-left: 330px;opacity: 0;}
        .openRow img {display: inline-block;width: 55px;float: left;}
        .openRow p {display: inline-block;width: 200px;margin-top: 5px;margin-left: 15px;}
        .assoShow {width: 360px;height: 200px;margin-left: -15px;opacity: 0;color: black;font-family: 幼圆;padding: 10px;}
        .assoShow img {width: 22px;height: 22px;display: inline-block;margin-top: 40px;margin-left: 40px;}
        .assoShow h2 {text-align: right;margin-right: 50px;display: inline-block;width: 100px;float: right;opacity: 0;}
        .assoShow p {text-indent: 7em;margin-top: 50px;opacity: 0;color: black;}
    </style>
@endsection
@section('content')
    <div class="container" id="main">
        <img id="openImg">
        <div class="row" style="margin-bottom: 100px">
            <p id="openFont">广东药科大学欢迎你！</p>
            <div class="openRow" style="margin-top: 100px">
                <img src="{{ asset('static/img/friend.png') }}">
                <p>汇集全校各大社团，你想要了解的广药大社团都在这里。</p>
            </div>
            <div class="openRow">
                <img src="{{ asset('static/img/location_light.png') }}">
                <p>个性化自我介绍，支持头像上传，让你的部长们全面认识你。</p>
            </div>
            <div class="openRow">
                <img src="{{ asset('static/img/we.png') }}">
                <p>各轮面试情况实时通知，同时面试多个社团也能轻松应对。</p>
            </div>
        </div>
        <?php $i = 0 ?>
        @foreach($association as $a)
            <div  onclick="location='{{ route('association',[$a->id]) }}'" class="assoShow" style="background: url('{{ route('viewAssociationBackground',[$a->id . '.png']) }}');margin-left: {{ $i++ % 2 == 0 ? '' : '-' }}330px">
                <img class="assoLogo" src="{{ route('viewAssociationLogo',[$a->id . '.png']) }}" />
                <h2>{{ $a->name }}</h2>
                <p>{{ $a->short }}</p>
            </div>
        @endforeach
    </div>
    @component('common.scrollDown')@endcomponent
@endsection
@section('javascript')
    <script src="{{ asset('static/js/move.js') }}"></script>
    <script>
        $(document).ready(function() {
            var openImg = document.getElementById('openImg');
            var openFont = document.getElementById('openFont');
            var GDPU = document.getElementById('GDPU');
            var openRow = document.getElementsByClassName('openRow');
            var assoShow = document.getElementsByClassName('assoShow');
            var openPlay = 0;
            for (var i = 0; i < assoShow.length; i++)
                assoShow[i].isPlayed = 0;
            openImg.src = '{{ asset('static/img/openImg' . rand(1,3) . '.jpg') }}';
            move(openImg).duration('4s').set('opacity', 1).end();
            move(openFont).delay('1s').duration('1s').set('opacity', 1).end();
            move(openFont).delay('1.5s').sub('top', 10).end();
            move(openRow[0]).delay('2.0s').set('margin-left', '-15px').set('opacity', 1).end();
            move(openRow[1]).delay('2.2s').set('margin-left', '-15px').set('opacity', 1).end();
            move(openRow[2]).delay('2.4s').set('margin-left', '-15px').set('opacity', 1).end();
            window.onscroll = function () {
                var st = document.body.scrollTop;
                if (st > 225) {
                    if (openPlay == 0) {
                        openPlay = 1;
                        move(GDPU).set('opacity', 1).end();
                    }
                }
                for (var i = 0; i < assoShow.length; i++) {

                    if (st > (220 + i * 200) && assoShow[i].isPlayed == 0) {
                        assoShow[i].isPlayed = 1;
                        move(assoShow[i]).set('margin-left', '-15px').set('opacity', 1).end();
                        move(assoShow[i].getElementsByTagName('img')[0]).scale(3).rotate(720).duration('4s').end();
                        move(assoShow[i].getElementsByTagName('h2')[0]).set('opacity', 1).duration('2s').end();
                        move(assoShow[i].getElementsByTagName('p')[0]).set('opacity', 1).duration('3s').end();
                        move(assoShow[i].getElementsByTagName('p')[0]).sub('margin-top', 30).end();
                    }
                }
            }
        });
    </script>
@endsection