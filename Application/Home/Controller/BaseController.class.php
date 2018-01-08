<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
	{
        parent::__construct();
        //$this->check_login();
	}


    public function check_login()
    {
        if (empty(session('id'))) {
            returnajax('false', '', '等待超时，请重新登陆', 1);
        }
    }


}