<?php

// +------------------------------------------------------------------------------------------ 
// | Author: longDD <longdd_love@163.com> 
// +------------------------------------------------------------------------------------------ 
// | There is no true,no evil,no light,there is only power. 
// +------------------------------------------------------------------------------------------ 
// | Description: 极光推送 Dates: 2015-09-22
// +------------------------------------------------------------------------------------------

namespace Vendor\Jpush;

require_once 'jpush/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class Jpush
{
    /** 秘钥 */
    public $appKey = 'cc77fa6c3aa184e38e61a9d7';
    /** 秘钥 */
    public $masterSecret = 'b24653d97bd0501b9360d6c9';
    /** Jpush对象 */
    public $jpush;

    /**
     * 构造函数
     *
     * @return void
     */
    public function __construct($key="cc77fa6c3aa184e38e61a9d7",$masterSecret = 'b24653d97bd0501b9360d6c9')
    {
        $this->jpush = new JPushClient($key, $masterSecret);
    }

    /**
     * 发送订单推送
     *
     * @param string $registrationId 设备标识
     * @param int $orderId 订单ID
     * @return boolean 推送成功返回 true, 推送失败返回 false
     */
    public function push($registrationId, $data)
    {
//        $postUrl = "https://api.jpush.cn/v3/push";
//        $base64=base64_encode('cc77fa6c3aa184e38e61a9d7' . ':' . 'b24653d97bd0501b9360d6c9');
//        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
//        $data = array();
//        $data['platform'] = 'android';          //目标用户终端手机的平台类型android,ios,winphone
//        $data['audience'] = $registrationId;      //目标用户
//
//        $data['notification'] = array(
//            //统一的模式--标准模式
//            "alert" => $content,
//            //安卓自定义
//            "android" => array(
//                "alert" => $content,
//                "title" => "",
//                "builder_id" => 1,
//                "extras" => $datas,
//            ),
////            //ios的自定义
////            "ios" => array(
////                "alert" => $content,
////                "badge" => "1",
////                "sound" => "default",
////                "extras" => array("type" => $m_type, "txt" => $m_txt)
////            )
//        );
//
////        //苹果自定义---为了弹出值方便调测
////        $data['message'] = array(
////            "msg_content" => $content,
////            "extras" => array("type" => $m_type, "txt" => $m_txt)
////        );
//
//        //附加选项
//        $data['options'] = array(
//            "sendno" => time(),
//            "time_to_live" => '', //保存离线时间的秒数默认为一天
//            "apns_production" => false, //布尔类型   指定 APNS 通知发送环境：0开发环境，1生产环境。或者传递false和true
//        );
//        $param = json_encode($data);
//
//        $curlPost = $param;
//
//        $ch = curl_init();                                      //初始化curl
//        curl_setopt($ch, CURLOPT_URL, $postUrl);                 //抓取指定网页
//        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
//        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           // 增加 HTTP Header（头）里的字段
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//        $return_data = curl_exec($ch);                                 //运行curl
//        curl_close($ch);
//
//        return $return_data;
        $registration_ids = array();
        $registration_ids[] = $registrationId;

        try {
                $this->jpush->push()
                ->setPlatform(M\Platform('android'))
                ->setAudience(M\Audience(M\alias($registration_ids)))
                /** ->setNotification(M\notification('您有新的订单！',  M\ios("您有新的订单！", "happy", "+1", true, $data))) */
                ->setNotification(M\notification('测试1！',  M\android("测试1！", "", 1)))
                ->setMessage(M\message('msg content', null, null, $data))
                ->send();
            return true;
        } catch (APIRequestException $e) {
            $log = "Push Fail:"
                .'Http Code : ' . $e->httpCode
                . '-code : ' . $e->code
                . '-Error Message : ' . $e->message
                . '-Response JSON : ' . $e->json
                . '-rateLimitLimit : ' . $e->rateLimitLimit
                . '-rateLimitRemaining : ' . $e->rateLimitRemaining
                . '-rateLimitReset : ' . $e->rateLimitReset;

            \Think\Log::record($log, 'ERR');
            return false;
        } catch (APIConnectionException $e) {
            $log = 'Push Fail: '
                . 'Error Message: ' . $e->getMessage()
                . 'IsResponseTimeout: ' . $e->isResponseTimeout;

            \Think\Log::record($log, 'ERR');
            return false;
        }
    }
}
