<?php

    /*如何掉一个接口*/

    $access_token = "29_g1ZUuTEZptDzzdV7b0t92SSrx7poJim-TUIC4koIVulyA6Pz69uvajyjeM6SRL8inEAZta1YLGfN_5B8V_b5qa6yvm2WML4LXpZWYAi9gKHnlX7Aeb6sY449UZf-uWdeABNIvl_ucu5v5HjFWZYcACABED";
    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
    // echo $url;die;
    
    /*接口的参数*/
    $postData  = '{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "ha"}}}';
    // var_dump($postData);die;

    $res = post($url,$postData);
    // var_dump($res);die;
    
    function post($url,$postData){
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