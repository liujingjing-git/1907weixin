@extends('layouts.admin')

@section('title', '渠道管理-展示页面')

@section('content')
    <h3>渠道展示</h3>
    <a href="{{url('admin/show')}}">点我</a>
    <table class="table table-hover table-bordered">
        <tr>
            <td>渠道ID</td>
            <td>渠道名称</td>
            <td>渠道标识</td>
            <td>二维码</td>
            <td>关注人数</td>
            <td>操作</td>
        </tr>
    @foreach($Info as $k=>$v)
        <tr>
            <td>{{$v['c_id']}}</td>
            <td>{{$v['c_name']}}</td>
            <td>{{$v['c_status']}}</td>
            <td>
                <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$v['ticket']}}" width="50px">
            </td>
            <td>{{$v['c_num']}}</td>
            <td>
                <a href="">删除</a> |
                <a href="">修改</a>
            </td>
        </tr>
    @endforeach
    </table>
@endsection
