<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ChannelModel;
use App\Tools\Wechat;
use App\Tools\Curl;

class ChannelController extends Controller
{
    /*渠道展示*/
    public function show()
    {
        return view('channel.show');
    }

    /*渠道执行添加*/
    public function add()
    {
        $c_name = request()->c_name;
        $c_status = request()->c_status;
        
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        
        // $postData = '{"expire_seconds": 2592000, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$c_status.'"}}}';
        /*转成数组格式*/
        $postData = [
            'expire_seconds'=>2592000,
            'action_name'=>"QR_STR_SCENE",
            'action_info'=>[
                'scene'=>[
                    'scene_str'=>$c_status
                ],
            ],
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        $ticket = $res['ticket'];
       
        $sql = ChannelModel::create([
            'c_name' => $c_name,
            'c_status' => $c_status,
            'ticket' => $ticket
        ]);
        if($sql){
            echo "<script>alert('添加成功'),location='lists'</script>";
        }else{
            echo "<script>alert('添加失败'),location='show'</script>";
        }
    }

    /*列表展示*/
    public function lists(){
        $Info = ChannelModel::get()->toArray(); 
        return view('channel.lists',['Info'=>$Info]);
    }

    /*渠道图表*/
    public function chart(){
        $post = ChannelModel::get()->toArray();
        // dd($post);die;
        $c_name = "";
        $c_num = "";
        foreach($post as $k=>$v){
            $c_name .= "'".$v['c_name']."',";
            $c_num .= $v['c_num'].","; 
        }
        $c_name = rtrim($c_name,',');
        $c_num = rtrim($c_num,',');
        return view('channel.chart',['c_name'=>$c_name,'c_num'=>$c_num]);
    }
}
