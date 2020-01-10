<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\NewsModel;
use App\Tools\Wechat;

class NewsController extends Controller
{
    /*新闻添加视图*/
    public function create(){
        return view('news.create');
    } 

    /*新闻执行添加*/
    public function store(Request $request){
        $data = $request->input();
        $data['add_time']=time();
        $info = NewsModel::create($data);
        if($info){
            echo "<script>alert('添加成功'),location='list';</script>";
        }else{
            echo "<script>alert('添加失败'),location='create';</script>";
        }
    }

    /*新闻列表展示*/
    public function list(){
        //分页
        $new_ath = request()->new_ath;
        $new_name = request()->new_name;
        $where=[];
        if($new_ath){
            $where[]=['new_ath','like',"%$new_ath%"];
        }
        if($new_name){
            $where[]=['new_name','like',"%$new_name%"];
        }
        $query = request()->all();
        
        $newsInfo = NewsModel::where($where)->paginate(2);
        return view('news.list',['newsInfo'=>$newsInfo,'query'=>$query]);
    }

    /*新闻删除*/
    public function delete($new_id){
        $res = NewsModel::where('new_id',$new_id)->delete();
        if($res){
            return redirect('admin/list');
        }
    }

    /*新闻修改视图*/
    public function update($new_id){
        $data = NewsModel::where('new_id',$new_id)->first();
        return view('news/update',['data'=>$data]);
    }

    /*执行修改*/
    public function updatedo($new_id){
        $post = request()->input();
        $data = NewsModel::where('new_id',$new_id)->update($post);
        if($data){
            echo  "<script>alert('修改成功'),location='/admin/list';</script>";
        }else{
            echo  "<script>alert('修改失败'),location='update';</script>";
        }
    }

    /*回复消息*/
    public function wx(){
        // $echostr = $_GET['echostr'];
        // echo $echostr;die;
        $xml = file_get_contents("php://input");//接收原始的xml和json数据
        file_put_contents("wx.txt","\n\n".$xml."\n",FILE_APPEND);//写到文件中
        $xmlObj=simplexml_load_string($xml);//方便处理xml对象

        if($xmlObj->MsgType == "event" && $xmlObj->Event == "subscribe"){
            $userData = Wechat::getUserInfoByOpenid($xmlObj->FromUserName);
            $sex = $userData['sex'];
            $nickname = $userData['nickname'];
            if($sex==1){
                $msg = "欢迎".$nickname."先生关注";
            }else{
                $msg = "欢迎".$nickname."女士关注";
            }
            
            //回复文本消息
            Wechat::reponseText($xmlObj,$msg);
        }
        //发送最新新闻
        if($xmlObj->MsgType=='text'){
            $content = trim($xmlObj->Content);
            if($content=='最新新闻'){
                $msg = NewsModel::orderBy('new_id','desc')->first();
                $msg = "新闻标题:".$msg->new_name."\n"."内容:".$msg->new_desc;
                Wechat::reponseText($xmlObj,$msg);
            }elseif(mb_strpos($content,"新闻+")!==false){
                $nick = mb_substr($content,3);
                $sql = NewsModel::where('new_name','like',"%$nick%")->get()->toArray();
                if($sql){
                    $msg = "";
                    foreach($sql as $k=>$v){
                        NewsModel::where('new_id','=',$v['new_id'])->increment('new_fang');
                        $msg = "新闻标题:".$sql[0]['new_name']."\n内容:".$sql[0]['new_desc'];
                    }
                    Wechat::reponseText($xmlObj,$msg);
                }else{
                    $msg = "暂无相关新闻";
                    Wechat::reponseText($xmlObj,$msg);
                }
            }
        }  
    }
}