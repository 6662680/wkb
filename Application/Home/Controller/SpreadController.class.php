<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class SpreadController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 查询出某个会员消费总金额及数量
     * @author zh
     * @date 2018-1-13
     * @return void
     */
    public function getPriceOrNum()
    {

        $rst = D('spread')->getPriceOrNum(2);
		pr($rst['sumprice']);
		pr($rst['num']);

    }
	

    

}