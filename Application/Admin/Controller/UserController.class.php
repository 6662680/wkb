<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class UserController extends PrivilegeController
{
	/**
	 * 会员列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('user')->count(), $pageSize, array());
    	$userList = M('user')->limit($page->firstRow, $page->listRows)->select();
    	$this->assign('userList',$userList);
    	$this->assign('page',$page->show());
        $this->display();
    }
    
    /**
	 * 背包详情查看
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function bagdetail()
    {
    	
        $user_id=I('get.id');
        $pageSize = 5;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('mediche_bag')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$medicheList = M('mediche_bag')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*根据食物id查询食物名称显示到食物背包数组*/
    	foreach ($medicheList as $key => $value) {
    		$mediche_id=$value['mediche_id'];
    		$nmedicheList = M('mediche')->where("id = '$mediche_id' ")->find();
			if ($nmedicheList['mediche_name']) {
				$medicheList[$key]['mediche_id']=$nmedicheList['mediche_name'];
			} else {
				$medicheList[$key]['mediche_id']='无';
			}
    	}
    	$this->assign('medicheList',$medicheList);
    	$this->assign('page',$page->show());
    	$userList = M('user')->find(I('get.id'));
    	$this->assign('userList',$userList);
		
        $page2 = getpage(M('person_bag')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$personList = M('person_bag')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*根据人物id查询人物名称显示到人物背包数组*/
    	foreach ($personList as $key => $value) {
    		$person_id=$value['person_id'];
    		$npersonList = M('person')->where("id = '$person_id' ")->find();
			if ($npersonList['person_name']) {
				$personList[$key]['person_id']=$npersonList['person_name'];
			} else {
				$personList[$key]['person_id']='无';
			}
    	}
    	/*根据道具id查询道具名称显示到人物背包数组*/
    	foreach ($personList as $key => $value) {
    		$equipment_id=$value['equipment_id'];
			$npequipment = M('equipment_bag')->where("id = '$equipment_id' ")->find();
			$npequipmentid=$npequipment['equipment_id'];
			
    		$nequipmentList = M('equipment')->where("id = '$npequipmentid' ")->find();
			if ($nequipmentList['equipment_name']) {
				$personList[$key]['equipment_nname']=$nequipmentList['equipment_name'];
			} else {
				$personList[$key]['equipment_nname']='未配备';
			}
    	}
    	$this->assign('personList',$personList);
    	$this->assign('page2',$page2->show());
		
		$page3 = getpage(M('equipment_bag')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$equipmentList = M('equipment_bag')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*根据道具id查询道具名称显示到道具背包数组*/
    	foreach ($equipmentList as $key => $value) {
    		$equipment_id=$value['equipment_id'];
    		$nnequipmentList = M('equipment')->where("id = '$equipment_id' ")->find();
			if ($nnequipmentList['equipment_name']) {
				$equipmentList[$key]['equipment_id']=$nnequipmentList['equipment_name'];
			} else {
				$equipmentList[$key]['equipment_id']='无';
			}
    	}
    	$this->assign('equipmentList',$equipmentList);
    	$this->assign('page3',$page3->show());
		
        $this->display('bagdetail');
    }

    /**
	 * 订单详情查看
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function orderdetail()
    {
    	/*交易大厅某会员的出售详情*/
        $user_id=I('get.id');
        $pageSize = 5;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('user_sell_order')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$user_sell_orderList = M('user_sell_order')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*pr($user_sell_orderList);die;*/
    	foreach ($user_sell_orderList as $key => $value) {
    		$receiving_user_id=$value['receiving_user_id'];
    		$nuserList = M('user')->where("id = '$receiving_user_id' ")->find();
			if ($nuserList['mobile']) {
				$user_sell_orderList[$key]['receiving_user_id']=$nuserList['mobile'];
			} else {
				$user_sell_orderList[$key]['receiving_user_id']='无';
			}
    	}
		/*pr($user_sell_orderList);die;*/
    	$this->assign('user_sell_orderList',$user_sell_orderList);
    	$this->assign('page',$page->show());
    	$userList = M('user')->find(I('get.id'));
    	$this->assign('userList',$userList);
		
		/*交易大厅某会员的求购详情*/
        
        $page3 = getpage(M('user_buy_order')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$user_buy_orderList = M('user_buy_order')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*pr($user_sell_orderList);die;*/
    	foreach ($user_buy_orderList as $key => $value) {
    		$receiving_user_id=$value['receiving_user_id'];
    		$nuserList = M('user')->where("id = '$receiving_user_id' ")->find();
			if ($nuserList['mobile']) {
				$user_buy_orderList[$key]['receiving_user_id']=$nuserList['mobile'];
			} else {
				$user_buy_orderList[$key]['receiving_user_id']='无';
			}
			
			$commodity_type=$value['commodity_type'];
			$commodity_id=$value['commodity_id'];
			if ($commodity_type==1) {
    			$npersonList = M('person')->where("id = '$commodity_id' ")->find();
    			$user_buy_orderList[$key]['commodity_type']='人物';
				$user_buy_orderList[$key]['commodity_id']=$npersonList['person_name'];
			} elseif($commodity_type==2) {
				$nequipmentList = M('equipment')->where("id = '$commodity_id' ")->find();
				$user_buy_orderList[$key]['commodity_type']='道具';
				$user_buy_orderList[$key]['commodity_id']=$nequipmentList['equipment_name'];
			}
			
    	}
		/*pr($user_sell_orderList);die;*/
    	$this->assign('user_buy_orderList',$user_buy_orderList);
    	$this->assign('page3',$page3->show());
    	$userList = M('user')->find(I('get.id'));
    	$this->assign('userList',$userList);
		
        /*官方求购订单详情*/
        $page2 = getpage(M('order')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$orderList = M('order')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*pr($orderList);die;*/
		foreach ($orderList as $key => $value) {
    		$commodity_type=$value['commodity_type'];
			$commodity_id=$value['commodity_id'];
			if ($commodity_type==1) {
    			$npersonList = M('person')->where("id = '$commodity_id' ")->find();
				$orderList[$key]['commodity_id']=$npersonList['person_name'];
			} elseif($commodity_type==2) {
				$nequipmentList = M('equipment')->where("id = '$commodity_id' ")->find();
				$orderList[$key]['commodity_id']=$nequipmentList['equipment_name'];
			} elseif($commodity_type==3) {
				$nmedicheList = M('mediche')->where("id = '$commodity_id' ")->find();
				$orderList[$key]['commodity_id']=$nmedicheList['mediche_name'];
			}
    	}
		
    	$this->assign('orderList',$orderList);
    	$this->assign('page2',$page2->show());
        $this->display('orderdetail');
    }
}