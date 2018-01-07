<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author jlb <[<email address>]>
 * @since 2016年12月7日09:57:37 
 */
class EquipmentController extends PrivilegeController
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
        $page = getpage(M('Equipment')->count(), $pageSize, array());
    	$equipmentList = M('Equipment')->limit($page->firstRow, $page->listRows)->select();
    	$this->assign('equipmentList',$equipmentList);
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
    	$this->assign('actionName','道具添加');
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
    	$this->assign('actionName','道具编辑');
    	$this->assign('equipmentInfo',M('Equipment')->find(I('get.id')));
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
    	
    	$equipment_name = I('post.equipment_name');
    	$equipment_endurance = I('post.equipment_endurance');
    	$equipment_protect = I('post.equipment_protect');
		$equipment_img = I('post.equipment_img');
		
    	//$admin_id存在就是修改,不存在就是添加
    	if ( !$id && !$equipment_name )
    	{
    		$this->error('请填写道具名称');
    	}
    	if ( !$id && !$equipment_endurance )
    	{
    		$this->error('请填写挖掘效率');
    	}
		if ( !$id && !$equipment_protect )
    	{
    		$this->error('请填写是否防坍塌');
    	}
		if ( !$id && !$equipment_img )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('Equipment')->where("equipment_name = '{$equipment_name}'")->count() )
    	{
    		$this->error('道具已经存在,请换一个!');
    	}

    	$data = [
    		'equipment_name' => $equipment_name,
    		'equipment_endurance' => $equipment_endurance,
    		'equipment_protect' => $equipment_protect,
    		'equipment_img' => $equipment_img,
    	];

    	if ( $id )
    	{
    		M('Equipment')->where("id = $id")->save($data);
    	}
    	else 
    	{
    		M('Equipment')->add($data);
    	}

		$this->success('操作成功!',U('Equipment/index'),2);
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
		
		M('Equipment')->delete($id);
		$this->success('删除成功');
	}
}