<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class BagController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 获取人物
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function person()
    {
        $person = D('person')->getBagPerson();
        returnajax(true, $person);
    }

}