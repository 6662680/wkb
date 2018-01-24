<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class WithdrawController extends PrivilegeController
{
	/**
	 * 提现列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('user_withdraw')->where(' status!=2 ')->count(), $pageSize, array());
    	$user_withdrawList = M('user_withdraw')->limit($page->firstRow, $page->listRows)->where(' status!=2 ')->select();
		
		
		//本周总提现
		$thisweek_start=mktime(0,0,0,date('m'),date('d')-date('w'+1),date('Y'));
		$thisweek_end=mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
		$map['create_time'] = array(
			    array('egt',$thisweek_start),
			    array('lt',$thisweek_end)
			);
		$where['status']=2;
		$sumwpoint=M('user_withdraw')->where($map)->where($where)->field('SUM(wpoint)')->find();
		/*pr($currentOrder['sum(commodity_price)']);die;*/
		
		$this->assign("sumwpoint",$sumwpoint['sum(wpoint)']);
		
		
    	$this->assign('user_withdrawList',$user_withdrawList);
    	$this->assign('page',$page->show());
        $this->display();
    }
    
    /**
	 * 发放奖励
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function grant()
    {
    	
    	$id=I('get.id');
		/*pr($user_id);die;*/
    	$withdraw = M('user_withdraw')->where(" id=$id ")->find();
		$userid=$withdraw['user_id'];
    	$user = M('user')->where(" id=$userid ")->find();
		$data = [
    		'status' => 2,
    		];
		M('user_withdraw')->where(" id=$id ")->save($data);
		$data2 = [
    		'point' => $user['point']-$withdraw['wpoint'],
    		];
		M('user')->where(" id=$userid ")->save($data2);
		
		$this->success('审核发放成功!',U('Withdraw/index'),2);
    }

    /**
	 * 冻结
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function freeze()
    {
    	$id=I('get.id');
    	$withdraw = M('user_withdraw')->where(" id=$id ")->find();
		$data = [
    		'status' => 3,
    		];
		M('user_withdraw')->where(" id=$id ")->save($data);
		
		$this->success('冻结成功!',U('Withdraw/index'),2);
    	
    }
	/**
	 * 解冻
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
	public function defreeze()
    {
    	$id=I('get.id');
    	$withdraw = M('user_withdraw')->where(" id=$id ")->find();
		$data = [
    		'status' => 1,
    		];
		M('user_withdraw')->where(" id=$id ")->save($data);
		
		$this->success('解冻成功!',U('Withdraw/index'),2);
    	
    }
}