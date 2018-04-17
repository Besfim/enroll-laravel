@extends('common.adminLayout')
@section('title','管理人员')
@section('style')
    <style>
        #main {margin-top: 50px;}
        .form-control {margin-bottom: 10px}
        .btn-default {margin-bottom: 10px;}
    </style>
@endsection
@section('content')
    <div id="main" class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">新增管理员</h3>
            </div>
            <div class="panel-body">
                <form action="{{ $act }}Manager" method="post">
                    @if(isset($manager))
                        <input class="form-control" type="text" name="manager[phone]" placeholder="电话" value="{{ old('manager.phone') ?: isset($manager->phone) ? $manager->phone : '' }}" />
                        <input class="form-control" type="text" name="manager[name]" placeholder="姓名" value="{{ old('manager.name') ?: isset($manager->name) ? $manager->name : '' }}" />
                        <input class="form-control" type="text" name="manager[password]" placeholder="密码" value="{{ old('manager.password') ?: isset($manager->password) ? $manager->password : '' }}" />
                        <select name="manager[type]" class="form-control" onclick="managerTypeSelect()">
                            <option id="manager4Option" value="4" {{ $manager->type == 4 || old('manager.type') == 4 ? "selected='selected" : ""}}>部门管理员</option>
                            <option  id="manager3Option" value="3" {{ $manager->type == 3 || old('manager.type') == 3 ? "selected='selected" : ""}}>社团管理员</option>
                        </select>
                    @else
                        <input class="form-control" type="text" name="manager[phone]" placeholder="电话" value="{{ old('manager.phone') }}" />
                        <input class="form-control" type="text" name="manager[name]" placeholder="姓名" value="{{ old('manager.name') }}" />
                        <input class="form-control" type="text" name="manager[password]" placeholder="密码" value="{{ old('manager.password') }}" />
                        <select name="manager[type]" class="form-control" onclick="managerTypeSelect()">
                            <option id="manager4Option" value="4" {{ old('manager.type') == 4 ? "selected='selected" : ""}}>部门管理员</option>
                            <option id="manager3Option" value="3" {{ old('manager.type') == 3 ? "selected='selected" : ""}}>社团管理员</option>
                        </select>
                        <select name="manager[did]" class="form-control" id="departmentSelect">
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ old('did') == $d->id ? "selected='selected" : ""}}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <input class="btn btn-default col-xs-12" type="submit" value="确认新增" />
                    {{ csrf_field() }}
                </form>
                <button class="btn btn-default col-xs-12" onclick="location='{{ route('myManager') }}'">返回列表</button>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        managerTypeSelect();
        function managerTypeSelect()
        {
            if(document.getElementById('manager3Option').selected)
                document.getElementById('departmentSelect').style.display = 'none';
            else
                document.getElementById('departmentSelect').style.display = 'block';
        }
    </script>
@endsection