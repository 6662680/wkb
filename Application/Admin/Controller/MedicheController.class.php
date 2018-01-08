<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class MedicheController extends PrivilegeController
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
        $page = getpage(M('Mediche')->count(), $pageSize, array());
    	$medicheList = M('Mediche')->limit($page->firstRow, $page->listRows)->select();
    	$this->assign('medicheList',$medicheList);
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
     * 处理添加,编辑食物配置请求
     * @author zh 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$mediche_name = I('post.mediche_name');
    	$mediche_treat = I('post.mediche_treat');
    	$mediche_price = I('post.mediche_price');
		$mediche_img = I('post.mediche_img');
    	//$id存在就是修改,不存在就是添加
    	if ( !$id && !$mediche_name )
    	{
    		$this->error('请填写食物名称');
    	}
    	if ( !$id && !$mediche_treat )
    	{
    		$this->error('请填写回血值');
    	}
    	if ( !$id && !$mediche_price )
    	{
    		$this->error('请填写食物价格');
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
    		'mediche_price' => $mediche_price,
    		'mediche_img' => $mediche_img,
    	];

    	if ( $id )
    	{
    		
    		
    		M('Mediche')->where("id = $id")->save($data);
    	}
    	else 
    	{
			
    		M('Mediche')->add($data);
    	}

		$this->success('操作成功!',U('Mediche/index'),2);
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
		
		M('Mediche')->delete($id);
		$this->success('删除成功');
	}
}