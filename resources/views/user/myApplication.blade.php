@extends('common.userLayout')
@section('title','我的面试')
@section('style')
    <style>
        #main input {margin-bottom: 15px}
        #main {margin-top: 50px;margin-bottom: 145px;}
        #noApplication {text-align: center;color: lightgray}
        .appl-wrap {width: 100%;height: 40px;}
        .appl-name {float: left;width: 100%;font-size: 16px;}
        .badge {float: right;margin-right: 0px;font-size: 13px;}
    </style>
@show
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">我的面试</h3>
            </div>
            <div class="panel-body">
                @foreach($applications as $a)
                    <div class="appl-wrap" onclick="location='{{ route('userApplication',[$a->id]) }}'">
                        <h4 class="appl-name">{{ $a->association->name . ' - ' . $a->department->name }}
                            <?php $department = $a->department ?>
                            @if($a->round == 100)
                                <span class="badge" style="background: green">已通过</span>
                            @elseif($a->round <= $department->round - 2)
                                <span class="badge">未通过</span>
                            @elseif($department->round == 0)
                                <span class="badge">未开始</span>
                            @else
                                <span class="badge" style="background: lightblue">第{{ $department->getRound() }}轮</span>
                            @endif
                        </h4>
                    </div>
                    <hr />
                @endforeach
                @if(count($applications) == 0)
                    <p id="noApplication">暂无面试，快去找个社团报名吧~</p>
                @endif
            </div>
            <a class="btn btn-info col-xs-12" style="margin-top: 15px;" href="{{ route('home') }}">返回首页</a>
        </div>
    </div>
@endsection
