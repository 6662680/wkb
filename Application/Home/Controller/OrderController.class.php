<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class OrderController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}


    /**
     * 购买商品: 人物，装备，药品 /下单动作
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function buy()
    {
        $post = I('post.');

        $rst = D('order')->buy(session('user_id'), $post['commodity_id'], $post['commodity_type']);

        if ($rst['status'] == true) {
            returnajax(true, '' ,'下单成功');
        } else {
            returnajax(true, '' , $rst['msg']);
        }
    }

    /**
     * 购买商品，异步更新
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function accomplishBuy()
    {
        $id = 2;
        $rst = D('order')->accomplishBuy($id, 1, 1 ,'121.23', 'safdsfdsgdf');

        if ($rst['status']) {
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }


    }

}