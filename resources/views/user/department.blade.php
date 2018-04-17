@extends('common.userLayout')
@section('title','部门介绍')
@section('style')
    <style>
        #main input {margin-bottom: 15px}
        #main {margin-top: 50px;}
    </style>
@show
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">{{ $department->name }}</h3>
            </div>
            <div class="panel-body">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $department->introduce }}</p>
            </div>
        </div>
        @if($sign)
            <button class="btn btn-info col-xs-12" disabled="disabled">已报名该社团</button>
        @else
            <a class="btn btn-default col-xs-12" href="{{ route('apply',[$department->id]) }}">填写报名表</a>
        @endif
    </div>
@show