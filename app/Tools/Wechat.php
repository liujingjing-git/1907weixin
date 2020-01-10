<?php

namespace App\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Wechat extends Model
{
    /*定义常量*/
    const appId = "wx09d1d54ef09170a9";
    const appSerect = "dd079df2d7127d1ae6429315e518aebb";

    //微信核心类   回复用户消息
   public static function reponseText($xmlObj,$msg){
        echo "<xml>
                 <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                 <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                 <CreateTime>".time()."</CreateTime>
                 <MsgType><![CDATA[text]]></MsgType>
                 <Content><![CDATA[".$msg."]]></Content>
              </xml>";die;
    }

    //回复微信接口调用凭据 access_token
    public static function getAccessToken(){
        //先判断缓存是否有数据
        $access_token = Cache::get('access_token');

        //有数据之间返回
        // if(empty($access_token)){
            //获取access_token 微信调用接口凭据
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appSerect;
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $access_token = $data['access_token'];
            
            //token存储两小时
        //     Cache::put('access_token',$access_token,7200); //2小时

        // }

        //没有数据再进去调用微信接口  存入缓存
        return $access_token;
    }

    /*获取用户信息*/
    public static function getUserInfoByOpenId($openid){
        $access_token = Self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        return $data;
    }

}
