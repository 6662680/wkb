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

		$personList=M('Person')->where('status=0')->select();
		
		$this->assign('personarr',$personList);
        if ($type == 1) {
            if ($commodity_type == 1) {
//                echo '买 人物';
                $this->assign('type',1);
				
             
				
                $this->display('person');
            } else {
//                echo '买 装备';
                $this->assign('type',1);
                $this->display('equipment');
            }
        } else {

            if ($commodity_type == 1) {
//                echo '卖 人物';
                $this->assign('type',2);
                $this->display('person');
            } else {
//                echo '卖 装备';
                $this->assign('type',2);
                $this->display('equipment');
            }

        }

    }

/**
     * ajax获取道具类型
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
	public function getCommodity() 
	{
		$id = I('post.id');
		if ($id == 1) {
			$rst = D('person')->getStorePerson();
		} else {
			$rst = D('equipment')->getStoreEquipment();
		}	
		echo json_encode($rst);
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
        $order_id = I('get.order_id');
        $rst = D('putorder')->sellreceiving(session('user_id'), $order_id);

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
        $order_id = I('get.order_id');
        $rst = D('putorder')->sellAccomplish(session('user_id'), $order_id);

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
        $order_id = I('get.order_id', '', 'int');
        $rst = D('Putorder')->buyReceiving(session('user_id'), $order_id);

        if ($rst['status'] == true) {
            returnajax(true, $rst['data']);
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }

    /**
     * 完成购买(订单类型：买)
     * @author LiYang
     * @date 2018-1-13
     * @return void
     */
    public function buyAccomplish()
    {
        
        $order_id = I('post.order_id', '', 'int');
        $rst = D('Putorder')->buyAccomplish(session('user_id'), $order_id);

        if ($rst['status'] == true) {
            returnajax(true, $rst['data']);
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }

    //让用户打款
    public function remit()
    {
        $order_id = I('get.order_id');
        $type = I('get.type');

        if ($type ==1) {
            $model = M('user_buy_order');
        } else {
            $model = M('user_sell_order');
        }

        $rst = $model->where(['id' => $order_id])->find();

        if ($rst['commodity_id'] == 1) {
            $model = M('person');
            $data = $model->where(['id' => $rst['commodity_id']])->find();
            $rst['typeImg'] = $data['person_img'];
        } else {
            $model = M('mediche');
            $data = $model->where(['id' => $rst['commodity_id']])->find();
            $rst['typeImg'] = $data['mediche_img'];
        }

        $rst['server_price'] = $rst['commodity_price'] / 20;
        $user = M('user')->where(['id' => $rst['user_id']])->find();
        $rst['putorder_site'] = $user['site'];
        $rst['order_time'] = $rst['use_time'] + C('ORDER_TIME');

        $overtime = false;

        if (time() > $rst['order_time'] || $rst['status'] == 2){
            $overtime = true;
        }

        $this->assign('rst',$rst);

        $this->assign('overtime',$overtime);

        $this->display('putorder/order');
    }

    //通知用户打款
    public function send() {
        $order_id = I('get.order_id');
        $type = I('get.type');

        if ($type ==1) {
            $model = M('user_buy_order');
        } else {
            $model = M('user_sell_order');
        }

        $rst = $model->where(['id' => $order_id])->find();

        //用户打款
        returnajax(true);
    }



}