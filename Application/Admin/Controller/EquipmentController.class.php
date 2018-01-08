<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7
 */
class EquipmentController extends PrivilegeController
{
	/**
	 * 道具列表
	 * @author zh <[<email address>]>
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
	 * 道具添加
	 * @author zh <[<email address>]>
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
	 * 道具编辑
	 * @author zh <[<email address>]>
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
     * 处理添加,编辑道具请求
     * @author jlb 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$equipment_name = I('post.equipment_name');
    	$equipment_endurance = I('post.equipment_endurance');
    	$equipment_protect = I('post.equipment_protect');
		$equipment_price = I('post.equipment_price');
		$equipment_multiple = I('post.equipment_multiple');
		$equipment_img = I('post.equipment_img');
		
    	//$id存在就是修改,不存在就是添加
    	if ( !$id && !$equipment_name )
    	{
    		$this->error('请填写道具名称');
    	}
    	if ( !$id && !$equipment_endurance )
    	{
    		$this->error('请填写基础耐久度');
    	}
		if ( !$id && !$equipment_price )
    	{
    		$this->error('请填写道具价格');
    	}
		if ( !$id && !$equipment_multiple )
    	{
    		$this->error('请填写挖矿效率');
    	}
		if ( !$id && !($equipment_protect=='0' || $equipment_protect==1) )
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
    		'equipment_price' => $equipment_price,
    		'equipment_multiple' => $equipment_multiple,
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
	 * 删除道具
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
		
		M('Equipment')->delete($id);
		$this->success('删除成功');
	}
}