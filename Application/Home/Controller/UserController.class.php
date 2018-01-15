<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class UserController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}

    /**
     * 注册
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [password] 密码
     * @date 2018-1-7
     * @return void
     */
    public function register()
    {
        if (!IS_POST) {
            $this->display('register');
        } else {
            $data = [];
            $post = I('post.');

            if (empty($post['mobile']) && empty($post['password'])) {
                returnajax(false, '', '请完善用户信息');
            }

            if (!regexp('mobile', $post['mobile'])) {
                returnajax(false, '', '手机号格式不正确');
            }

            if (!regexp('password', $post['password'])) {
                returnajax(false, '', '密码格式不正确');
            }

            if (M('user')->where(['mobile' => $post['mobile']])->find() ) {
                returnajax(false, '', '该手机号已被注册');
            }

            $salt = createSalt();
            $password = encryption($post['password'], $salt);

            $data = [
                'mobile' => $post['mobile'],
                'salt' => $salt,
                'password' => $password,
                'create_time' => time(),
                'last_login_time' => time(),
                'last_login_ip' => getIp(),
            ];

            $rst = M('user')->add($data);

            if ($rst) {
                D('Log')->addLog('ip' . getIp() . '注册成功!', $rst);
                returnajax(true, '', '注册成功');
            } else {
                returnajax(false, '', '注册失败，请稍候再试');
            }

        }


    }

    /**
     * 登录
     * @author LiYang
     * @param  [mobile]   手机号
     * @param  [password] 密码
     * @date 2018-1-7
     * @return void
     */
    public function login()
    {
        if (!IS_POST) {
            $this->display('login');
        } else {
            $post = I('post.');

            if (!regexp('mobile', $post['mobile'])) {
                returnajax(false, '', '手机号格式不正确');
            }

            if (!regexp('password', $post['password'])) {
                returnajax(false, '', '密码格式不正确');
            }

            $rst = M('user')->where(['mobile' => $post['mobile']])->find();

            if (!$rst) {
                returnajax(false, '', '账号不正确');
            }

            if (encryption($post['password'], $rst['salt']) != $rst['password']) {
                returnajax(false, '', '密码不正确');
            }

            $model = M('user');
            $model->find($rst['id']);
            $model->last_login_time = time();
            $model->last_login_ip = getIp();

            D('log')->addLog('ip' . getIp() . '登陆成功!', $rst['id']);
            session('user_id', $rst['id']);
            session('mobile', $rst['password']);
            returnajax(true, '', '成功');
        }


    }

    public function verificationAddress()
    {
        getwkb('0x19e2fbe87147cb8d7b15b92b0b7e35b906339b6b');
    }

    /**
     * 重置密码
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function reset()
    {
        // 发送验证码
        Vendor('AliyunMns.mns');
        $result = run($post['mobile'],  'SMS_75810093',array("number" => strval($code)));
    }


}