<?php
require_once 'mns-autoloader.php';
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;


function run($phone, $sms, $data)
{

	/**
	 * Step 1. 初始化Client
	 */
	$endPoint = "http://1495574994956310.mns.cn-hangzhou.aliyuncs.com/"; // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com

     $accessId = "LTAI0DHSPZKYReRY";
    $accessKey = "N9nVtEgIAD879SJ735BJ7PQYgKrCvp";
	$client = new Client($endPoint, $accessId, $accessKey);
	/**
	 * Step 2. 获取主题引用
	 */
	$topicName = "sms.topic-cn-hangzhou";
	$topic = $client->getTopicRef($topicName);
	/**
	 * Step 3. 生成SMS消息属性
	 */
	// 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
	$batchSmsAttributes = new BatchSmsAttributes("当贝网络", $sms);
	// 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
	$batchSmsAttributes->addReceiver($phone, $data);
	//$batchSmsAttributes->addReceiver("YourReceiverPhoneNumber2", array("YourSMSTemplateParamKey1" => "value1"));
	$messageAttributes = new MessageAttributes(array($batchSmsAttributes));
	/**
	 * Step 4. 设置SMS消息体（必须）
	 *
	 * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
	 */
	$messageBody = "smsmessage";
	/**
	 * Step 5. 发布SMS消息
	 */
	$request = new PublishMessageRequest($messageBody, $messageAttributes);
	try
	{
		$res = $topic->publishMessage($request);

		return $res;
//		echo $res->isSucceed();
//		echo "\n";
//		echo $res->getMessageId();
//		echo "\n";
	}
	catch (MnsException $e)
	{
//		echo $e;
//		echo "\n";
	}
}



?>
