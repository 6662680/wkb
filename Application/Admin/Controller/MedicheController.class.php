<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author jlb <[<email address>]>
 * @since 2016年12月7日09:57:37 
 */
class MedicheController extends PrivilegeController
{
	/**
	 * 后台用户列表
	 * @author jlb <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('Mediche')->count(), $pageSize, array());
    	$medicheList = M('Mediche')->limit($page->firstRow, $page->listRows)->select();
    	$this->assign('medicheList',$medicheList);
    	$this->assign('page',$page->show());
        $this->display();
    }
    /**
	 * 后台用户添加
	 * @author jlb <[<email address>]>
	 * @return [type] [description]
	 */
    public function add()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','后台用户添加');
    	$this->assign('roleList',M('Role')->where("role_id <> " . ($this->role_id == 1 ? 0 : 1))->select());
        $this->display('form');
    }
    /**
	 * 后台用户编辑
	 * @author jlb <[<email address>]>
	 * @return [type] [description]
	 */
    public function edit()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','食物编辑');
    	$this->assign('adminInfo',M('Mediche')->find(I('get.id')));
        $this->display('form');
    }

    /**
     * 处理添加,编辑用户请求
     * @author jlb 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$mediche_name = I('post.mediche_name');
    	$mediche_treat = I('post.mediche_treat');
		$mediche_img = I('post.mediche_img');
    	//$admin_id存在就是修改,不存在就是添加
    	if ( !$id && !$mediche_name )
    	{
    		$this->error('请填写食物名称');
    	}
    	if ( !$id && !$mediche_treat )
    	{
    		$this->error('请填写回血值');
    	}
		if ( !$id && !$mediche_img )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('Mediche')->where("mediche_name = '{$mediche_name}'")->count() )
    	{
    		$this->error('食物已经存在,请换一个!');
    	}

    	$data = [
    		'mediche_name' => $mediche_name,
    		'mediche_treat' => $mediche_treat,
    		'mediche_img' => $mediche_img,
    	];

    	if ( $id )
    	{
    		unset($data['mediche_iname']);
    		
    		M('Mediche')->where("id = $id")->save($data);
    	}
    	else 
    	{
			
    		M('Mediche')->add($data);
    	}

		$this->success('操作成功!');
		exit;
    }
    /**
	 * 删除后台人员
	 * @author jlb
	 * @since 2016年12月7日15:00:09 
	 */
	public function del()
	{
		$id = I('get.id',0,'intval');
		if ( !$id ) 
		{
			$this->error('非法请求');
		}
		
		M('Mediche')->delete($id);
		$this->success('删除成功');
	}
}