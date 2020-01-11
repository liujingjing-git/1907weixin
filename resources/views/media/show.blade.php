@extends('layouts.admin')

@section('title', '素材管理-展示页面')

@section('content')
    <h3>素材展示</h3>
    <table class="table table-hover table-bordered">
        <tr>
            <td>标题</td>
            <td>格式</td>
            <td>类型</td>
            <td>展示</td>
            <td>添加时间</td>
        </tr>
    @foreach($data as $k=>$v)
        <tr>
            <td>{{$v['media_name']}}</td>
            <td>{{$v['media_format']}}</td>
            <td>
                @if($v['media_type']==1)
                    临时
                @else   
                    永久
                @endif
            </td>
            <td>
                @if($v['media_format']=='image')
                    <img src="\{{$v['media_url']}}" width="100px">
                @elseif($v['media_format']=='voice')
                    <audio src="\{{$v['media_url']}}" controls="controls" width="100px"></audio>
                @elseif($v['media_format']=='video')
                    <video src="\{{$v['media_url']}}" controls="controls" width="100px"></video>
                @endif
            </td>
            <td>{{date("Y-m-d H:i:s",$v->add_time)}}</td>
        </tr>
    @endforeach
    </table>
@endsection
