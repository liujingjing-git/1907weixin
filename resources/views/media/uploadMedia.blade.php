<?php 
    //上传素材接口
    $access_token = "29_9AHX-hpisMLgfHZdjcQaDXfoqgJAnsQE8GRw-X1lcqq1Zelq6wKHu6jptbjO5mq1p05BKSBeS-SHwia1nGbEl1nGbRXdy1PMPytLseEsxU0IyIM6TF4NMEUgNs1vQiclfdhj0sJUy2WOl23MCVQhADAPID";
    $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";

    //发送请求post
    $img = "D:\phpstudy_pro\WWW\d73cd5e7c43c31f4_640o.jpg";//图片路径
    $img = new \CURLFile($img);
    $postData['media']=$img;
    $res = curlPost($url,$postData);
    var_dump($res);die;

    function curlPost($url,$postData)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //设置请求地址
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 返回数据格式
        curl_setopt($curl, CURLOPT_POST, 1);  //设置以post发送
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);   //设置post发送的数据
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //关闭https验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//关闭https验证
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


?>