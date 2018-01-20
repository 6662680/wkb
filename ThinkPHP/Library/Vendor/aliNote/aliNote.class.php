<?php

// +------------------------------------------------------------------------------------------ 
// | Author: liyang <664577655@qq.com>
// +------------------------------------------------------------------------------------------ 
// | There is no true,no evil,no light,there is only power. 
// +------------------------------------------------------------------------------------------ 
// | Description: 短信操作类 (阿里) Dates: 2017-09-23
// +------------------------------------------------------------------------------------------

namespace Vendor\aliNote;

class aliNote 
{
    /** App Key */
    private $appkey = '23427369';
    /** 密码 */
    private $appSecret = 'd0eb6e03b0d3c9dfb369a1659d92698c';
    /** 服务器地址 */
    private $server = 'http://gw.api.taobao.com/router/rest';
    /** 返回信息格式 */
    private $format = 'json';
    /** 错误信息 */
    public $errorMsg = '';

    /**
     * 发送短信
     *
     * @return void
     */
    public function send($mobiles = '', $content = '', $template = '')
    {
        include "TopSdk.php";

        $c = new \TopClient;
        $c->appkey = $this->appkey;
        $c->secretKey = $this->appSecret;
        $c->format = $this->format;


        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("只是测试短信");
        $req->setSmsParam(json_encode($content));
        /** $req->setRecNum("13336046996"); */
        $req->setRecNum($mobiles);
        /** $req->setSmsTemplateCode("SMS_5024350"); */
        $req->setSmsTemplateCode($template);

        $rst = $c->execute($req);
       $this->resultHandle($rst);

    }

    /**
     * 结果处理
     *
     * @return void
     */
    private function resultHandle($rst)
    {
        if ($rst->result->success) {
            return true;
        } else {
            $msg = "短信接口错误，code:" . $rst->code . ";msg:" . $rst->msg . ";sub_code:" . $rst->sub_code . ";sub_msg:" . $rst->sub_msg . ";request_id:" . $rst->request_id;
            \Think\Log::record($msg, 'ERR');
            return false;
        }     
    }

}
// END Note class

// + --------------------------------------------
// + demo
// + --------------------------------------------
// + $note = new note();
// + $note->send('13336046996,15158113084', '这是测试内容：Hello World!~~~');
// + --------------------------------------------