<?php

namespace Home\Model;
use Think\Model;
class UserModel extends Model
{
    private static $AppKey = 'cc77fa6c3aa184e38e61a9d7';
    private static $Master_secret = 'b24653d97bd0501b9360d6c';

    /*  $receiver 接收者的信息*/
    //$content 推送的内容。
    //$m_type 推送附加字段的类型(可不填) http,tips,chat....
    //$m_txt 推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
    //$m_time 保存离线时间的秒数默认为一天(可不传)单位为秒
    public function push($receiver, $content='', $m_type='', $m_txt='', $m_time='86400')
    {
        $postUrl = "https://api.jpush.cn/v3/push";
        $base64=base64_encode(self::$AppKey . ':' . self::$Master_secret );
        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
        $data = array();
        $data['platform'] = 'android';          //目标用户终端手机的平台类型android,ios,winphone
        $data['audience'] = $receiver;      //目标用户

        $data['notification'] = array(
            //统一的模式--标准模式
            "alert" => $content,
            //安卓自定义
            "android" => array(
                "alert" => $content,
                "title" => "",
                "builder_id" => 1,
                "extras" => array("type" => $m_type, "txt" => $m_txt)
            ),
//            //ios的自定义
//            "ios" => array(
//                "alert" => $content,
//                "badge" => "1",
//                "sound" => "default",
//                "extras" => array("type" => $m_type, "txt" => $m_txt)
//            )
        );

        //苹果自定义---为了弹出值方便调测
        $data['message'] = array(
            "msg_content" => $content,
            "extras" => array("type" => $m_type, "txt" => $m_txt)
        );

        //附加选项
        $data['options'] = array(
            "sendno" => time(),
            "time_to_live" => $m_time, //保存离线时间的秒数默认为一天
            "apns_production" => false, //布尔类型   指定 APNS 通知发送环境：0开发环境，1生产环境。或者传递false和true
        );
        $param = json_encode($data);

        $curlPost = $param;

        $ch = curl_init();                                      //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl);                 //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $return_data = curl_exec($ch);                                 //运行curl
        curl_close($ch);

        return $return_data;
    }


}
