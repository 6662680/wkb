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
    	/*数据暂为假-2018.1.13*/
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('user_withdraw')->where(' status!=2 ')->count(), $pageSize, array());
    	$user_withdrawList = M('user_withdraw')->limit($page->firstRow, $page->listRows)->where(' status!=2 ')->select();
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
    	$user_id=I('get.user_id');
		/*pr($user_id);die;*/
    	$withdraw = M('user_withdraw')->where(" user_id=$user_id ")->find();
    	$user = M('user')->where(" id=$user_id ")->find();
		$data = [
    		'status' => 2,
    		];
		M('user_withdraw')->where(" user_id=$user_id ")->save($data);
		$data2 = [
    		'point' => $user['point']-$withdraw['wpoint'],
    		];
		M('user')->where(" id=$user_id ")->save($data2);
		
		$this->success('审核发放成功!',U('Withdraw/index'),2);
    }

    /**
	 * 冻结
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function freeze()
    {
    	$user_id=I('get.user_id');
    	$withdraw = M('user_withdraw')->where(" user_id=$user_id ")->find();
		$data = [
    		'status' => 3,
    		];
		M('user_withdraw')->where(" user_id=$user_id ")->save($data);
		
		$this->success('冻结成功!',U('Withdraw/index'),2);
    	
    }
	/**
	 * 解冻
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
	public function defreeze()
    {
    	$user_id=I('get.user_id');
    	$withdraw = M('user_withdraw')->where(" user_id=$user_id ")->find();
		$data = [
    		'status' => 1,
    		];
		M('user_withdraw')->where(" user_id=$user_id ")->save($data);
		
		$this->success('解冻成功!',U('Withdraw/index'),2);
    	
    }
}