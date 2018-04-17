@extends('common.adminLayout')
@section('title','修改信息')
@section('content')
    <form action="varyUser" method="post">
        <input type="text" name="user[name]" placeholder="姓名" value="{{ old('user.name') ?: $user->name }}" />
        <input type="text" name="user[gender]" placeholder="性别" value="{{ old('user.gender') ?: $user->gender }}" />
        <input type="text" name="user[birth]" placeholder="生日" value="{{ old('user.birth') ?: $user->birth }}" />
        <input type="text" name="user[school]" placeholder="学院" value="{{ old('user.school') ?: $user->school }}" />
        <input type="text" name="user[major]" placeholder="专业" value="{{ old('user.major') ?: $user->major }}" />
        <input type="text" name="user[class]" placeholder="班级" value="{{ old('user.class') ?: $user->class }}" />
        <input type="submit" />
        {{ csrf_field() }}
    </form>
@show