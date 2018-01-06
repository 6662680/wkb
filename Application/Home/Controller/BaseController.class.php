<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
	{
        parent::__construct();
	}

    public function validationToken()
    {
        if (session('token') != I('post.token')) {
            $this-> error('当前链接已过期，请返回首页重新提交');
        };
        session('token', '');
    }

    public function check_verify($reset = '', $id = '')
    {
        $code = I('post.code');
        $verify = new \Think\Verify();

        if ($reset == 'reset') {
            $verify->reset = false;
        }else {
            $verify->reset = true;
        }

        return $verify->check($code, $id);
    }

    public function error($msg)
    {
        $this->assign('msg', $msg);
        $this->display('alert');
    }
}