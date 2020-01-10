@extends('layouts.admin')

@section('title', '新闻管理-展示页面')

@section('content')
    <h3>新闻展示</h3>
    <a href="{{url('admin/create')}}">点我</a>
    <form action="">
        <input type="text" name="new_ath" value="{{$query['new_ath']??''}}" placeholder="请输入作者">
        <input type="text" name="new_name" value="{{$query['new_name']??''}}" placeholder="请输入新闻标题">
        <input type="submit" value="搜素">
    </form>
    <table class="table table-hover table-bordered">
        <tr>
            <td>新闻标题</td>
            <td>新闻内容</td>
            <td>新闻作者</td>
            <td>添加时间</td>
            <td>访问次数</td>
            <td>操作</td>
        </tr>
    @foreach($newsInfo as $k=>$v)
        <tr>
            <td>{{$v['new_name']}}</td>
            <td>{{$v['new_desc']}}</td>
            <td>{{$v['new_ath']}}</td>
            <td>{{date("Y-m-d H:i:s",$v->add_time)}}</td>
            <td>{{$v['new_fang']}}</td>
            <td>
                <a href="{{url('admin/delete/'.$v->new_id)}}">删除</a>
                <a href="{{url('admin/update/'.$v->new_id)}}">修改</a>
            </td>
        </tr>
    @endforeach
    </table>
    {{$newsInfo->appends($query)->links()}} 
@endsection
