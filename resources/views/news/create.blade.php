@extends('layouts.admin')

@section('title', '新闻管理-添加页面')

@section('content')
    <h3>新闻添加</h3>
    <form action="{{url('admin/store')}}" method="post" enctype="multipart/form-data"> 
        <div class="form-group">
            <label for="exampleInputEmail1">新闻标题</label>
            <input type="text" class="form-control"  placeholder="请输入新闻标题" name="new_name">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">新闻内容</label>
            <textarea name="new_desc" class="form-control" cols="10" rows="10" placeholder="请输入新闻内容..."></textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">新闻作者</label>
            <input type="text" name="new_ath" class="form-control" placeholder="请输入新闻作者">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
@endsection
