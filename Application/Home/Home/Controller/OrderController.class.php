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
//	    $this->ban();
        $get = I('get.');

		/*$userDong = M('user')->where(['id' => session('user_id')])->find();
		pr($userDong);die;*/
		if ($userDong['status']==2) {
                returnajax(false, '', '账号冻结中,不允许进行此操作!');
            }


		$rst = D('order')->buy(session('user_id'), $get['commodity_id'], $get['commodity_type']);
		if ($rst['status'] == true) {
            returnajax(true, '' ,$rst['msg']);
        } else {
            returnajax(false, '' , $rst['msg']);
        }
		
        
    }
	// 检测是否创建新的订单还是显示之前下的
	public function pdOrder()
	{
		
		$get = I('get.');

		$order = D('order')->where(['user_id' => session('user_id'),'status' =>  1,'commodity_id' =>  $get['commodity_id'],'commodity_type' =>  $get['commodity_type']])->order('id desc')->find();
		$site = D('log')->getconfig('site');
		$order_id = $order['id'];
		/*pr($order);die;*/
		if ($order&&$order['creation_time']+C('ORDER_TIME' )< time()) {
			/*超时*/
			$rst = D('order')->creationOrder(session('user_id'), $get['commodity_id'], $get['commodity_type'], $get['num']);
			$order2 = D('order')->where(['user_id' => session('user_id'),'status' =>  1])->order('id desc')->find();
			$user = D('user')->where(['id' =>  session('user_id')])->find();
			if ($get['commodity_type']==1) {
				$rst2 = D('person')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='person_img';
				$typeName='person_name';
				$typePrice='person_price';
			} elseif($get['commodity_type']==2) {
				$rst2 = D('equipment')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='equipment_img';
				$typeName='equipment_name';
				$typePrice='equipment_price';
			}elseif($get['commodity_type']==3) {
				$rst2 = D('mediche')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='mediche_img';
				$typeName='mediche_name';
				$typePrice='mediche_price';
			}
	        	$time=$order2['creation_time']+C('ORDER_TIME');
				$commodity_id=$get['commodity_id'];
				$commodity_type=$get['commodity_type'];
				$this->assign('order_id',$order2['id']);
	        	$this->assign('rst2',$rst2);
				$this->assign('typeImg',$typeImg);
				$this->assign('typeName',$typeName);
				$this->assign('typePrice',$order2['commodity_price']);
				$this->assign('time',$time);
				$this->assign('user',$user);
				$this->assign('commodity_id',$commodity_id);
				$this->assign('commodity_type',$commodity_type);
				$this->assign('site',$site);
				$this->display('order');
//		elseif($order&&$order['creation_time']+C('ORDER_TIME' )>= time()) {
//				/*未超时*/
//				$user = D('user')->where(['id' =>  session('user_id')])->find();
//				if ($get['commodity_type']==1) {
//					$rst2 = D('person')->where(['id' =>  $get['commodity_id']])->find();
//					$typeImg='person_img';
//					$typeName='person_name';
//					$typePrice=$rst2['person_price'];
//				} elseif($get['commodity_type']==2) {
//					$rst2 = D('equipment')->where(['id' =>  $get['commodity_id']])->find();
//					$typeImg='equipment_img';
//					$typeName='equipment_name';
//					$typePrice=$rst2['equipment_price'];
//				}elseif($get['commodity_type']==3) {
//					$rst2 = D('mediche')->where(['id' =>  $get['commodity_id']])->find();
//					$typeImg='mediche_img';
//					$typeName='mediche_name';
//					$typePrice=$rst2['mediche_price'];
//				}
//				$time=$order['creation_time']+C('ORDER_TIME');
//				$commodity_id=$get['commodity_id'];
//				$commodity_type=$get['commodity_type'];
//
//				$this->assign('order_id',$order_id);
//				$this->assign('rst2',$rst2);
//				$this->assign('typeImg',$typeImg);
//				$this->assign('typeName',$typeName);
//				$this->assign('typePrice',$typePrice * $get['num']);
//				$this->assign('time',$time);
//				$this->assign('user',$user);
//				$this->assign('commodity_id',$commodity_id);
//				$this->assign('commodity_type',$commodity_type);
//
//				$this->display('order');
//
//
//			}
		} else {

			$rst = D('order')->creationOrder(session('user_id'), $get['commodity_id'], $get['commodity_type'], $get['num']);
			$user = D('user')->where(['id' =>  session('user_id')])->find();
			$order = D('order')->where(['user_id' => session('user_id'),'status' =>  1])->order('id desc')->find();
			if ($get['commodity_type']==1) {
				$rst2 = D('person')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='person_img';
				$typeName='person_name';
				$typePrice='person_price';
			} elseif($get['commodity_type']==2) {
				$rst2 = D('equipment')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='equipment_img';
				$typeName='equipment_name';
				$typePrice='equipment_price';
			}elseif($get['commodity_type']==3) {
				$rst2 = D('mediche')->where(['id' =>  $get['commodity_id']])->find();
				$typeImg='mediche_img';
				$typeName='mediche_name';
				$typePrice='mediche_price';
			}

	        	$time=$order['creation_time']+C('ORDER_TIME');
				$commodity_id=$get['commodity_id'];
				$commodity_type=$get['commodity_type'];
				$this->assign('order_id',$order['id']);
	        	$this->assign('rst2',$rst2);
				$this->assign('typeImg',$typeImg);
				$this->assign('typeName',$typeName);
				$this->assign('typePrice',$order['commodity_price']);
				$this->assign('time',$time);
				$this->assign('user',$user);
				$this->assign('commodity_id',$commodity_id);
				$this->assign('commodity_type',$commodity_type);
				$this->assign('site',$site);

				$this->display('order');
		}
		
	}

	// 从商城订单前往付款按钮执行的
	public function sczfOrder()
	{
		
		$get = I('get.');
		$order = D('order')->where(['user_id' => session('user_id'),'id' =>   $get['id']])->find();
		$order_id = $order['id'];
		$site = D('log')->getconfig('site');
		/*pr($order);die;*/
			
			$user = D('user')->where(['id' =>  session('user_id')])->find();
			if ($order['commodity_type']==1) {
				$rst2 = D('person')->where(['id' =>  $order['commodity_id']])->find();
				$typeImg=$rst2['person_img'];
				$typeName=$rst2['person_name'];
			} elseif($order['commodity_type']==2) {
				$rst2 = D('equipment')->where(['id' =>  $order['commodity_id']])->find();
				$typeImg=$rst2['equipment_img'];
				$typeName=$rst2['equipment_name'];
			}elseif($order['commodity_type']==3) {
				$rst2 = D('mediche')->where(['id' =>  $order['commodity_id']])->find();
				$typeImg=$rst2['mediche_img'];
				$typeName=$rst2['mediche_name'];
			}
	        	$time=$order['creation_time']+C('ORDER_TIME');
			
				$this->assign('order_id',$order_id);
	        	$this->assign('rst2',$rst2);
				$this->assign('typeImg',$typeImg);
				$this->assign('typeName',$typeName);
				$this->assign('typePrice',$order['commodity_price']);
				$this->assign('time',$time);
				$this->assign('user',$user);
				$this->assign('site',$site);

				$this->display('sczforder');

		
	}

    /**
     * 购买商品，异步更新
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function accomplishBuy()
    {
		$get = I('get.');
	    $this->ban();
        $rst = D('order')->accomplishBuy($get['order_id']);
       
        if ($rst['status']) {
        	$award=A('Spread')->award();
			$awardEquipment=A('Spread')->awardEquipment();
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }


    }
	
	/**
     * @author zh
     * @date 2018-1-16
     * @return void
     */
    public function unBuy()
    {
    	$get = I('get.');
	    $this->ban();
        $rst = D('order')->unBuy($get['order_id']);
        if ($rst) {
			$this->redirect(U('Store/person','',''));
            /*returnajax(true, '','取消订单成功');*/
        } else {
            returnajax(false, '', $rst['msg']);
        }

    }

}