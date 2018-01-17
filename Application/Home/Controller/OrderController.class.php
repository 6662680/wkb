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
        $get = I('get.');
		
		$order = D('order')->where(['user_id' =>  session('user_id'),'status' =>  1,'commodity_type' =>  $get['commodity_type'],'commodity_id' =>  $get['commodity_id']])->find();
		$time=$order['creation_time']+C('ORDER_TIME');
	
		if ($order && $time > time()) {
			
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
	        	$time=$order['creation_time']+C('ORDER_TIME');
				$commodity_id=$get['commodity_id'];
				$commodity_type=$get['commodity_type'];
				
	        	$this->assign('rst2',$rst2);
				$this->assign('typeImg',$typeImg);
				$this->assign('typeName',$typeName);
				$this->assign('typePrice',$typePrice);
				$this->assign('time',$time);
				$this->assign('user',$user);
				$this->assign('commodity_id',$commodity_id);
				$this->assign('commodity_type',$commodity_type);
				
	        	$this->display('order');
	        
		}else{
			
			$rst = D('order')->buy(session('user_id'), $get['commodity_id'], $get['commodity_type']);
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
			
	        if ($rst['status'] == true) {
	        	/*pr($rst['creation_time']);die;*/
	        	$time=$rst['creation_time']+C('ORDER_TIME');
				$commodity_id=$get['commodity_id'];
				$commodity_type=$get['commodity_type'];
				
	        	$this->assign('rst2',$rst2);
				$this->assign('typeImg',$typeImg);
				$this->assign('typeName',$typeName);
				$this->assign('typePrice',$typePrice);
				$this->assign('time',$time);
				$this->assign('user',$user);
				$this->assign('commodity_id',$commodity_id);
				$this->assign('commodity_type',$commodity_type);
				
	        	$this->display('order');
	            /*returnajax(true, '' ,'下单成功');*/
	        } else {
	            returnajax(false, '' , $rst['msg']);
	        }
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

        $rst = D('order')->accomplishBuy('200', 'dsfdsfdsgdfgf');
        pr($rst);
        if ($rst['status']) {
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

        $rst = D('order')->unBuy(session('user_id'), $get['commodity_id'], $get['commodity_type']);
        if ($rst) {
			$this->redirect(U('store/person','',''));
            /*returnajax(true, '','取消订单成功');*/
        } else {
            returnajax(false, '', $rst['msg']);
        }


    }

}