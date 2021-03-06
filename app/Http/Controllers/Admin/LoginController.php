<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LoginModel;
use App\Tools\Wechat;
use App\Tools\Curl;

class LoginController extends Controller
{

    /*给用户发送消息*/
    public function shop()
    {   
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        //请求的参数
        $args = [
            'touser' => 'ovh231S5UOQ3o5OLx1ETDYFyjqeo',
            'template_id' => 'lToi2U4z0NEbVPCadBg9TDst4b98uqZxYASI5NC8RFQ',
            'data' => [
                'name' => [
                    'value' => 'lisi',
                    'color' => '#173177',
                ],
                'code' => [
                    'value' => '8888',
                    'color' => '#173177',
                ],
                'time' => [
                    'value' => date('Y-m-d H:i:s'),
                    'color' => '#173177',
                ],
            ],
        ];
        $args = json_encode($args,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$args);
        var_dump($res);die;
    }


    /*登录界面*/
    public function login(){
        return view('login.login');
    }

    /*执行登录*/
    public function logindo(){
        $post=request()->except('_token');
        $where[]=[
            'login_name', '=' ,$post['login_name']
        ];
        $res=LoginModel::where($where)->first(); 

        if($res['login_name']!==$post['login_name']){
            echo "<script>alert('该用户不存在,请确认后在重试'),location='login'</script>";
        }
        if($res['login_pwd']!==$post['login_pwd']){
            $code = $res['code'];
            $code = $code+1;
            $res=LoginModel::where($where)->update(['code'=>$code,'time'=>time()]); 
            echo "<script>alert('密码有误,请确认后在试'),location='login'</script>";
        }
        if($res){
            if($res['time']+60<=time()&&$res['code']<=3){
                $code = $res['code'];
                $res->code = 0;
                $res->save();
                echo "<script>alert('登录成功'),location='index'</script>";
            }else{
                if($res['code']>=3){
                    echo "<script>alert('您的账号已锁定,请您60秒后再试'),location='login'</script>";
                }
            }
        }else{
            echo "<script>alert('登录失败'),location='login'</script>";
        }
    }
}
