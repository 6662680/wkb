<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class LogController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}

    /**
     * 登陆 and 注册
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [name]     姓名
     * @param  [code]     验证码
     * @param  [deviceid] 唯一设备号
     * @date 2017-9-26
     * @return void
     */
    public function index()
    {
        $data = [];
        $post = getRequest();

        $model = M('user');

        if (empty($post['mobile']) && empty($post['code']) && empty($post['deviceid'])) {
            returnajax(false, '', '请完善用户信息');
        }

        if (cache('code' . $post['mobile']) != $post['code'] && $post['code'] != 0000) {
            returnajax(false, '', '验证码不正确');
        }

        Vendor('wangyiyun.ServerAPI');
        $serverAPI = new \ServerAPI(C(YUNXIN_APPKey),C(YUNXIN_AppSecret),'curl');

        if (!$rst = $model->where(['mobile' => $post['mobile']])->find()) {
            /*注册的*/
            if (empty($rst['name']) && empty($post['name'])) {
                $rst['name'] = '我自己';
            }

            /*默认头像*/
            $rst['head_portrait'] = 'Public/static/headportrait/Faces_grandpa.png';

            $add = [
                'mobile'=> $post['mobile'],
                'name' => $rst['name'],
                'deviceid' => $post['deviceid'],
                'last_time' => time(),
                'create_time' => time(),
                'head_portrait' => $rst['head_portrait'],
            ];
            $rst['id'] = M('user')->add($add);

            if ($rst['id']) {
                $apirst = $serverAPI->createUserId($rst['id'], $post['mobile']);
                $data['type'] = 1;
            } else {
                returnajax(false, 'NULL', '注册失败，请稍后再试');
            }

        } else {
            /*登陆*/
            $apirst = $serverAPI->updateUserToken($rst['id']);

            $data['type'] = 2;

            if ($post['deviceid'] != $rst['deviceid_tv'] && !empty($rst['deviceid_tv'])) {
                $jpush = new \Vendor\Jpush\Jpush();

                $push = [
                    'type' => 9,
                    'msg' => '该账号已在另一台设备上登陆,如果不是您本人操作，请立刻修改密码',
                    'user_id' => $rst['id'],
                ];
                $jpush->push($rst['deviceid_tv'], $push, '只是一个测试');

            }
        }

        session('username', $post['mobile']);
        session('deviceid', $post['deviceid']);
        session('user_id', $rst['id']);
        cache('code'.$post['mobile'], NULL);

        $save = [
            'create_time' => time(),
            'deviceid_tv' => $post['deviceid'],
            'token' => $apirst['info']['token'],
            'accid' => $apirst['info']['accid'],
        ];
        $model->where(['mobile' => $post['mobile']])->save($save);

        $data['token'] = $apirst['info']['token'];
        $data['accid'] =  $apirst['info']['accid'];
        $data['head_portrait'] = $rst['head_portrait'] ? C('AVATAR_URL').$rst['head_portrait'] : '';
        $data['name'] = $rst['name'];
        $data['mobile'] = $post['mobile'];

        returnajax(true, $data, '成功');
    }

    /**
     * 自动登录
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [user_id]  自身ID
     * @param  [token]    token
     * @param  [md5]      user_id + mobile + token //顺序不可弄错
     * @date 2017-9-26
     * @return void
     */
    public function AutLog()
    {
        $post = getRequest();

        if (!regexp('mobile', $post['mobile'])) {
            returnajax(false, '', '手机号格式不正确');
        }

        if (!$post['user_id'] && !$post['token']) {
            returnajax(false, '', '参数不可为空');
        }

        if (md5($post['user_id'] . $post['mobile'] . $post['token']) != $post['md5']) {
            returnajax(false, '', '非法请求');
        }

        $rst = M('user')->where(
            [
                'id' => $post['user_id'],
                'mobile' => $post['mobile'],
            ])
            ->find();

        if (!$rst) {
            returnajax(false, '', '用户信息不正确');
        }

        Vendor('wangyiyun.ServerAPI');
        $serverAPI = new \ServerAPI(C(YUNXIN_APPKey),C(YUNXIN_AppSecret),'curl');
        /*登陆*/
        $apirst = $serverAPI->updateUserToken($rst['id']);

        $update = M('user')->where(['id' => $post['user_id']])->save(['token' => $post['token']]);

        if ($update === FALSE) {
            returnajax(false, '', 'token写入失败');
        }

        $data = [
            'type' => 2,
            'accid' => $rst['id'],
            'mobile' => $rst['mobile'],
            'token' => $apirst['info']['token'],
            'name' => $rst['name'],
            'head_portrait' => $rst['head_portrait'] ? C('AVATAR_URL').$rst['head_portrait'] : '',
        ];

        if ($post['deviceid'] != $rst['deviceid_tv'] && !empty($rst['deviceid_mobile'])) {
            $jpush = new \Vendor\Jpush\Jpush();

            $push = [
                'type' => 9,
                'msg' => '该账号已在另一台设备上登陆,如果不是您本人操作，请立刻修改密码',
                'user_id' => $post['user_id'],
            ];
            $rst = $jpush->push($rst['deviceid_tv'], $push, '只是一个测试');
        }

        returnajax(true, $data, '成功');

    }


    /**
     * 发送短信
     * @author LiYang
     * @param  [mobile]   手机号
     * @date 2017-9-23 09:00:00
     * @return void
     */
    public function send ()
    {
        $post = getRequest();
        $code = generate_code();
        if (empty($post['mobile'])) {
            returnajax(false, '', '请输入手机号');
        }

        // 发送验证码
        Vendor('AliyunMns.mns');
        $result = run($post['mobile'],  'SMS_75810093',array("number" => strval($code)));

        if ($result){
            cache('code' . $post['mobile'], $code, 1800);
            returnajax(true, '', '发送成功');
        } else {
            returnajax(false, '', '发送失败');
        }

    }
	public function push($deviceid = ''){
		$jpush = new \Vendor\Jpush\Jpush();
		$jpush->appKey = 'b96c0a72a770e2d1b8dc2d43';
		$jpush->masterSecret = '7b8f3559cc29179c8b8a6f45';
		$push = [
			'type' => 9,
			'msg' => '该账号已在另一台设备上登陆,如果不是您本人操作，请立刻修改密码',
		];
		$rst = $jpush->push($deviceid,$push, '只是一个测试');
	}

    /**
     * 发送短信
     * @author LiYang
     * @param  [mobile]   手机号
     * @date 2017-11-7 09:00:00
     * @return void
     */
    public function logout()
    {
        $post = getRequest();

        $mode = M('user');
        $mode->find($post['user_id']);
        $mode->deviceid_tv = '';
        $mode->save();
        returnajax(true, '', '登出成功');
    }


}