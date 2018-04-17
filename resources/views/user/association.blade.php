@extends('common.userLayout')
@section('title',$association->name)
@section('style')
    <style>
        .asso {margin-top: 40px;margin-bottom: 50px;}
        .asso img {width: 75px;height: 75px;margin-left: -100px;opacity: 0;}
        .asso h2 {float: right;margin-right: -200px;opacity: 0;}
        .asso p {text-indent: 2em;margin-top: 45px;opacity: 0;}
        .depa {margin-top: 40px;opacity: 0}
        .depa img {width:360px;height: 150px;margin-left: -15px;}
        .depa p {text-indent: 2em;}
        .apply {opacity: 0;}
    </style>
@show
@section('content')
    <div class="container">
        <div class="asso">
            <img src="{{ route('viewAssociationLogo',[$association->id . '.png']) }}" />
            <h2>{{ $association->name }}</h2>
            <p>{{ $association->introduce }}</p>
        </div>
        <hr />
        @foreach($departments as $d)
        <div class="depa">
            <img src="{{ route('viewDepartmentBackground',[$d->id . '.png']) }}" />
            <h4>{{ $d->name }}</h4>
            <p>{{ $d->introduce }}</p>
        </div>
        @endforeach
        <hr />
        <div class="apply">
            <select id="departmentSelect" class="form-control">
                <option value="0">请选择部门</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ $apply == $d->id ? "selected='selected'" : ''}}>{{ $d->name }}</option>
                @endforeach
            </select>
            @if($apply)
            <button disabled="disabled" style="margin-top: 15px;" class="btn btn-primary col-xs-12">你已报名</button>
            @else
            <button onclick="apply()" style="margin-top: 15px;" class="btn btn-primary col-xs-12">立即报名</button>
            @endif
        </div>
        <a class="btn btn-info col-xs-12" style="margin-top: 15px;" href="{{ route('home') }}">返回首页</a>
    </div>
    @component('common.scrollDown')@endcomponent
@endsection
@section('javascript')
    <script src="{{ asset('static/js/move.js') }}"></script>
    <script>
        $(document).ready(function() {
            var logo = $(".asso img")[0];
            var title = $(".asso h2")[0];
            var intro = $(".asso p")[0];
            var asso = $(".asso")[0];
            var depa = $(".depa");
            var apply = $(".apply")[0];
            for(var i = 0;i < depa.length;i++)
                depa[i].isPlayed = 0;
            move(logo).delay('0.5s').set('margin-left','30px').set('opacity',1).end();
            move(title).delay('0.5s').set('margin-right','20px').set('opacity',1).end();
            move(intro).delay('0.5s').set('margin-top','30px').set('opacity',1).end();
            window.onscroll = function(){
                var st = document.body.scrollTop;
                for(var i = 0;i < depa.length;i++)
                {
                    if(st + 640 - depa[i].offsetTop > 220 && depa[i].isPlayed === 0)
                    {
                        depa[i].isPlayed = 1;
                        move(depa[i]).set('margin-top', '0px').set('opacity', 1).end();
                    }
                }
                if(st + 640 - apply.offsetTop > 110)
                    move(apply).set('opacity', 1).end();
            };
        });
        function apply()
        {
            var department = $("#departmentSelect").val();
            if(department === '0')
                alert('请选择部门');
            else
                location = "{{ route('apply',['']) }}"  + '/' + department;
        }
    </script>
@endsection