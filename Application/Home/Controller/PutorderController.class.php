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
        $type = I('post.type',1);
        $commodity_type = I('post.commodity_type',1);
        $rst = D('Putorder')->orderList($type, $commodity_type);
        $this->assign('rst',$rst);
        echo $type; echo $commodity_type;
        if ($type == 1) {
            if ($commodity_type == 1) {
                $this->display('person');
            } else {
                $this->display('equipment');
            }
        } else {
            if ($commodity_type == 1) {
                $this->display('person');
            } else {
                $this->display('equipment');
            }

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
     * 创建订单(订单类型：买)
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

    /**
     * 玩家点击购买(订单类型：买)
     * @author LiYang
     * @date 2018-1-13
     * @return void
     */
    public function buyReceiving()
    {
        $order_id = I('post.order_id', '', 'int');
        $rst = D('Putorder')->buyAccomplish(session('user_id'), 99);

        if ($rst['status'] == true) {
            returnajax(true, $rst['data']);
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }




}