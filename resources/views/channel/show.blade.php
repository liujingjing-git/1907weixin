@extends('layouts.admin')

@section('title', '渠道管理-添加页面')

@section('content')
    <h3>渠道添加</h3>
    <form action="{{url('admin/add')}}" method="post" enctype="multipart/form-data"> 
        <div class="form-group">
            <label for="exampleInputEmail1">渠道名称</label>
            <input type="text" class="form-control"  placeholder="请输入渠道名称" name="c_name">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">渠道标识</label>
            <input type="text" class="form-control" placeholder="请输入渠道标识" name="c_status" >
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
@endsection
