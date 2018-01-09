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
	 * 食物基础配置列表
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
	 * 食物基础配置添加
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function add()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','食物类型添加');
    	$this->assign('roleList',M('Role')->where("role_id <> " . ($this->role_id == 1 ? 0 : 1))->select());
        $this->display('form');
    }
    /**
	 * 食物基础配置编辑
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function bag()
    {
    	/*if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','食物编辑');
    	$this->assign('adminInfo',M('user')->find(I('get.id')));
        $this->display('form');*/
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
     * 处理添加,编辑食物配置请求
     * @author zh 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$user_name = I('post.user_name');
    	$user_treat = I('post.user_treat');
    	$user_price = I('post.user_price');
		$user_img = I('post.user_img');
    	//$id存在就是修改,不存在就是添加
    	if ( !$id && !$user_name )
    	{
    		$this->error('请填写食物名称');
    	}
    	if ( !$id && !$user_treat )
    	{
    		$this->error('请填写回血值');
    	}
    	if ( !$id && !$user_price )
    	{
    		$this->error('请填写食物价格');
    	}
		if ( !$id && !$user_img )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('user')->where("user_name = '{$user_name}'")->count() )
    	{
    		$this->error('食物已经存在,请换一个!');
    	}

    	$data = [
    		'user_name' => $user_name,
    		'user_treat' => $user_treat,
    		'user_price' => $user_price,
    		'user_img' => $user_img,
    	];

    	if ( $id )
    	{
    		
    		
    		M('user')->where("id = $id")->save($data);
    	}
    	else 
    	{
			
    		M('user')->add($data);
    	}

		$this->success('操作成功!',U('user/index'),2);
		exit;
    }
    /**
	 * 删除食物类型
	 * @author zh
	 * @since 2018.1.7 
	 */
	public function del()
	{
		$id = I('get.id',0,'intval');
		if ( !$id ) 
		{
			$this->error('非法请求');
		}
		
		M('user')->delete($id);
		$this->success('删除成功');
	}
}