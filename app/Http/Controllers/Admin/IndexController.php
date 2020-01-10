<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;

class IndexController extends Controller
{
    /*后台首页*/
    public function index(){
        return view('index.index');
    }
    /*显示后台首页详情*/
    public function index_v1(){
        return view('index.index_v1');
    }

    /*调用微信接口*/
    public function shou(){
        return view('index.shou');
    }

    public function getshou(){
        //城市名称
        $city = request()->input("city");
        //天气接口
        $url = "http://api.k780.com/?app=weather.future&weaid=".$city."&&appkey=47877&sign=a04ca21de735d770272b1c64a5c19a51&format=json";
        $post = file_get_contents($url);
        return $post;
    }
}
