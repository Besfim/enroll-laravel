@extends('common.adminLayout')
@section('title','社团部门')
@section('style')
    <style>
        #main {margin-top: 30px;}
    </style>
@show
@section('content')
    <div id="main" class="container" style="margin-bottom: 50px;overflow: scroll">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">所有部门</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered text-center">
                    <tr>
                        <td>名称</td>
                        <td>管理员</td>
                        <td>最近修改</td>
                    </tr>
                    @foreach($department as $d)
                        <?php $i = 0; ?>
                        <tr>
                            <td>{{ $d->name }}</td>
                            <td>{{ $managerNum[$i++] }}</td>
                            <td>{{ date('Y-m-d',strtotime($d->updated_at)) }}</td>
                        </tr>
                    @endforeach
                </table>
                <input class="form-control" type="button" class="btn btn-default col-xs-12" value="返回主页" onclick="location='{{ route('admin') }}'" />
            </div>
        </div>
    </div>
@endsection