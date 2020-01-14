<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\MediaModel;
use App\Model\ChannelModel;
use App\Model\WechatUser;
use Illuminate\Support\Facades\Redis;

class WeixinController extends Controller
{
    private  $student = ['张艺兴','陈伟霆','张若昀','郭麒麟','任嘉伦','王一博','肖战'];
    

    public function index(Request $request)
    {
        // echo $request->input('echostr');die;
        // $echostr = $_GET['echostr'];
        // echo $echostr;die;

        $xml = file_get_contents("php://input");//接收原始的xml和json数据
        file_put_contents("log.txt","\n\n".$xml."\n",FILE_APPEND);//写到文件中
        $xmlObj=simplexml_load_string($xml);//方便处理xml对象

        //下载图片素材
        $media_id = $xmlObj->MediaId;
        if($xmlObj->MsgType=='image')  //图片
        {
            //下载图片
            $this->downloadImg($media_id);
        }elseif($xmlObj->MsgType=='video')  //视频
        {  
            //下载视频
            $this->downloadVideo($media_id);
        }   


        /*用户关注时*/
        if($xmlObj->MsgType == "event" && $xmlObj->Event == "subscribe"){
            //关注时获取用户信息
            $access_token = Wechat::getAccessToken();
            //存储方式  SQL  cookie  session  redis  memcahe  file
            $data = Wechat::getUserInfoByOpenId($xmlObj->FromUserName);
            //得到渠道标识
            $c_status = $data['qr_scene_str'];
            //根据聚到标识 得到人数递增
            ChannelModel::where(['c_status'=>$c_status])->increment('c_num');
            
            $user_data = WechatUser::where(['openid'=>$xmlObj->FromUserName])->first();
            if($user_data){
                WechatUser::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>0,'c_status'=>$c_status]);
            }else{
                $sql = WechatUser::create([
                    'openid' => $data['openid'],
                    'sex' => $data['sex'],
                    'nickname' => $data['nickname'],
                    'city' => $data['city'],
                    'add_time' => $data['subscribe_time'],
                    'c_status' => $c_status,
                ]);
            }
            $nickname = $data['nickname']; //取到用户昵称
            $msg = "欢迎".$nickname."关注";

            //回复文本消息
            Wechat::reponseText($xmlObj,$msg);
        }

        /*取消关注时候*/
        if($xmlObj->MsgType == "event" && $xmlObj->Event == "unsubscribe"){
            $userdata = WechatUser::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>1]);
            //查询用户信息表
            $user_data = WechatUser::where(['openid'=>$xmlObj->FromUserName])->first();
            //获取表示
            $c_status = $user_data['c_status'];
            ChannelModel::where(['c_status'=>$c_status])->decrement('c_num');
        }
      
        //用户发送消息时
        if($xmlObj->MsgType=='text'){
            $content = trim($xmlObj->Content);
            if($content=='1'){
                //回复全部姓名
                $msg = implode(",",$this->student);
                //回复用户文本消息
                Wechat::reponseText($xmlObj,$msg);
            }elseif($content=='2'){
                //随机回复一个同学
                shuffle($this->student);
                $msg = $this->student[0];
                //回复用户文本消息
                Wechat::reponseText($xmlObj,$msg);
            }elseif(mb_strpos($content,"天气") !== false){  //城市名字+天气
                //回复天气
                $city = trim($content,"天气");
                if(empty($city)){
                    $city="北京";
                }
        
                //调用k780天气接口  获取数据
                $url = "http://api.k780.com/?app=weather.future&weaid=".$city."&&appkey=47877&sign=a04ca21de735d770272b1c64a5c19a51&format=json";
                $data = file_get_contents($url);
                $data = json_decode($data,true); 
                
                if($data['success']==0){
                        echo "暂无城市信息";die;
                 }elseif($data['success']==1){
                    $msg = "";
                    foreach ($data['result'] as $key => $value) {
                        $msg .= $value['days']." ".$value['week']." ".$value['citynm']." ".$value['temperature']."\n";
                    }
                    Wechat::reponseText($xmlObj,$msg);
                }else{
                    $msg = $xmlObj->Content;
                    Wechat::reponseText($xmlObj,$msg);
                }

            }               
        }  
        
        //当用户发送图时
        if($xmlObj->MsgType=='image'){
            $where = ['media_format'=>'image'];
            $rand = MediaModel::inRandomOrder()->where($where)->first()->toArray();
            $MediaId = $rand['wechat_media_id'];
        
            //随机回复图片
            echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                    <MediaId><![CDATA[".$MediaId."]]></MediaId>
                </Image>
            </xml>";
        }  

        
    }

    /*下载图片素材*/
    protected function downloadImg($media_id)
    {
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;

        //请求获取素材接口
        $img = file_get_contents($url);
        //保存图片
        file_put_contents('haha.jpg',$img);
    }

    //下载视频素材
    protected function downloadVideo($media_id)
    {
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;

        //请求获取素材接口
        $img = file_get_contents($url);
       
        //保存视频
        $file_name = date("Ymd-His").rand(1111,9999).'.mp4';
        $res = file_put_contents($file_name,$img);
        var_dump($res);
    }


    /*自定义菜单*/
    public function createMenu(){
        echo date('Y-m-d H:i:s');
        //调用接口路径
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        // echo $url;die;
        
        $postData =[
            "button" => [
                [
                    "type" => "view",
                    "name" => "签到❤",
                    "url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09d1d54ef09170a9&redirect_uri=http%3A%2F%2F1905liujingjing.comcto.com%2Fwx%2Fauth&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
                ],
                [
                   "name" => "点这里❤",
                   "sub_button" => [
                        [
                            "type"  => "scancode_push",
                            "name"  => "扫一扫",
                            "key"   => "scan111"
                        ],
                        [
                            "type"  => "pic_sysphoto",
                            "name"  => "变好看",
                            "key"   => "photo111"
                        ],
                        [
                            "type" => "view",
                            "name" => "搜一下",
                            "url" => "https://www.sogou.com/"
                        ]
                    ]
                ],
            ]
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        var_dump($res);
    }

    /*根据openid群发事件*/
    public function sendAllByOpenId(){

        $users = WechatUser::select('openid')->get()->toArray();
        $openid_list = array_column($users,'openid');
        $access_token = Wechat::getAccessToken();
        
        $msg = date("Y-m-d H:i:s")."想带一人，回云深不知处，带回去，藏起来。——《陈情令》";

        $postData = [
            "touser" => $openid_list,
            "msgtype" => "text",
            "text" => [
                "content" => $msg
            ]
        ];

        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$access_token;
        $response = Curl::post($url,json_encode($postData,JSON_UNESCAPED_UNICODE));
        // echo '<pre>';print_r($response);echo '</pre>';die;
        $res = json_decode($response,true);
        // var_dump($res);die;
        if($res['errcode']>0){
            echo '错误信息:'.$res['errmsg'];
        }else{
            echo "发送成功";
        }
    }   

    /*微信网页授权*/
    public function test()
    {
        $redis_key = 'checkin:'.date('Y-m-d');
        // echo $redis_key;die;
        $appid = env('WX_APPID');
        $redirect_uri = urlencode(env('WX_AUTH_REDIRECT_URI'));
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        echo $url;
    }

    /*获取微信授权code*/
    public function auth()
    {
        //接收code
        $code = $_GET['code'];
        //换取access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APPID')."&secret=".env('WX_APPSECRET')."&code=".$code."&grant_type=authorization_code";
        $json_data = file_get_contents($url);
        $arr = json_decode($json_data,true);


        //获取用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$arr['access_token']."&openid=".$arr['openid']."&lang=zh_CN";
        $json_user_info = file_get_contents($url);
        $user_info_arr = json_decode($json_user_info,true);
        
        //将用户信息保存到HASH中

        $key = 'h:user_info:'.$user_info_arr['openid'];
        Redis::hMset($key,$user_info_arr);

        //实现签到功能  记录用户签到
        $redis_key = 'checkin:'.date('Y-m-d');
        Redis::Zadd($redis_key,time(),$user_info_arr['openid']);  //将openid加入有序集合中
        echo $user_info_arr['nickname']."签到成功"."签到时间:".date("Y-m-d H:i:s");

        $user_list = Redis::zrange($redis_key,0,-1);

        foreach($user_list as $k=>$v){
            $key = 'h:user_info:'.$v;
            $u = Redis::hGetAll($key);
            if(empty($u)){
                continue;
            }
            // echo '<pre>';print_r($u);echo '</pre>';
            echo "<img src='".$u['headimgurl']."'>";
        }
    }   
}