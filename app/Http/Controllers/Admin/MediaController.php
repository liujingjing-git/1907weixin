<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\MediaModel;

class MediaController extends Controller
{
    //素材添加
    public function add(){
        // $access_token = Wechat::getAccessToken();
        // echo $access_token;die;//获取access_token
        
        return view('media.add');
    }

    //素材入库
    public function add_do(Request $request)
    {
        //接值
        $data = $request->input();

        //文件上传
        $file = $request->file;
        $ext = $file->getClientOriginalExtension();
        $filename = md5(uniqid()).".".$ext;
        $filePath = $request->file->storeAs('images',$filename);
        
        //调用微信上传素材接口 将图片传给微信服务器
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];
        // echo $url;die;
        $filePathObj = new \CURLFile($filePath);        
       
        $postData = ['media'=>$filePathObj];
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        
        //添加到数据库
        if(isset($res['media_id'])){
            $media_id = $res['media_id'];
            $info=MediaModel::create([
                    'media_name'=> $data['media_name'],
                    'media_format' => $data['media_format'],
                    'media_type' => $data['media_type'],
                    'media_url' => $filePath,
                    'wechat_media_id' => $media_id,
                    'add_time' => time(),
            ]);
            if($info){
                echo "<script>alert('添加成功'),location='media_show'</script>";
            }else{
                echo "<script>alert('添加失败'),location='media_add'</script>";
            }
        }
        
    }

    public function show(){
        $data = MediaModel::get();
        return view('media.show',['data'=>$data]);
    }

   
    //测试
    public function uploadMedia(){
        return view('media.uploadMedia');
    }
    //测试
    public function createdo(){
        return view('media.createdo');
    }
}
