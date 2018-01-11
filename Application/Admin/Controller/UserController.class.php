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
    	$this->assign('medicheList',$medicheList);
    	$this->assign('page',$page->show());
    	$userList = M('user')->find(I('get.id'));
    	$this->assign('userList',$userList);
		
        $page2 = getpage(M('person_bag')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$personList = M('person_bag')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	$this->assign('personList',$personList);
    	$this->assign('page2',$page2->show());
		
		$page3 = getpage(M('equipment_bag')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$equipmentList = M('equipment_bag')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
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
    	/*交易大厅详情*/
        $user_id=I('get.id');
        $pageSize = 5;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('user_sell_order')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$user_sell_orderList = M('user_sell_order')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	/*pr($user_sell_orderList);die;*/
    	foreach ($user_sell_orderList as $key => $value) {
    		$receiving_user_id=$value['receiving_user_id'];
    		$nuserList = M('user')->where("id = '$receiving_user_id' ")->find();
			if ($nuserList['realname']) {
				$user_sell_orderList[$key]['receiving_user_id']=$nuserList['realname'];
			} else {
				$user_sell_orderList[$key]['receiving_user_id']='无';
			}
    	}
		/*pr($user_sell_orderList);die;*/
		
    	$this->assign('user_sell_orderList',$user_sell_orderList);
    	$this->assign('page',$page->show());
    	$userList = M('user')->find(I('get.id'));
    	$this->assign('userList',$userList);
		
        /*官方求购订单详情*/
        $page2 = getpage(M('order')->where("user_id = '$user_id' ")->count(), $pageSize, array());
    	$orderList = M('order')->limit($page->firstRow, $page->listRows)->where("user_id = '$user_id' ")->select();
    	$this->assign('orderList',$orderList);
    	$this->assign('page2',$page2->show());
    	
		
        $this->display('orderdetail');
    }
}