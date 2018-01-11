<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class PutorderController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    //订单列表
    public function orderList()
    {
        $rst = D('Putorder')->orderList(I('post.type'));
        if ($rst) {
            returnajax(true, $rst ,'');
        } else {
            returnajax(true, '' , '目前没有订单正在交易');
        }
    }

    /**
     * 挂单(订单类型：卖)
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function sellCreationOrder()
    {
        $post = I('post.');

        $rst = D('Putorder')->sellCreationOrder(session('user_id'), $post['commodity_id'], $post['commodity_type'], $post['commodity_price']);

        if ($rst['status'] == true) {
            returnajax(true, '' ,'下单成功');
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }

    /**
     * 购买商品，用户点击(订单类型：卖)
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function sellReceiving()
    {

        $rst = D('putorder')->receiving(session('user_id'),34);

        if ($rst['status']) {
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }

    }


    /**
     * 购买商品，异步更新(订单类型：卖)
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function sellAccomplish()
    {
        $id = 2;
        $rst = D('putorder')->sellAccomplish('200', 'dsfdsfdsgdfgfd');

        if ($rst['status']) {
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }

    }

    /**
     * 购买商品，异步更新(订单类型：买)
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function buyCreationOrder()
    {
        $post = I('post.');

        $rst = D('Putorder')->buyCreationOrder(session('user_id'), $post ,1);

        if ($rst['status'] == true) {
            returnajax(true, '' ,'下单成功');
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }


}