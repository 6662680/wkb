<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 20181.7 
 */
class PersonController extends PrivilegeController
{
	/**
	 * 人物基础配置列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('Person')->count(), $pageSize, array());
    	$PersonList = M('Person')->limit($page->firstRow, $page->listRows)->select();
	
    	$this->assign('PersonList',$PersonList);
    	$this->assign('page',$page->show());
        $this->display();
    }
    /**
	 * 人物基础配置添加
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function add()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','人物添加');
        $this->display('form');
    }
    /**
	 * 人物基础配置编辑
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function edit()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','人物编辑');
    	$this->assign('PersonInfo',M('Person')->find(I('get.id')));
        $this->display('form');
    }

    /**
     * 处理添加,编辑人物配置请求
     * @author jlb 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$person_name = I('post.person_name');
    	$person_capacity = I('post.person_capacity');
    	$person_blood = I('post.person_blood');
		$person_price = I('post.person_price');
		$person_property = I('post.person_property');
		$status = I('post.status');
		$person_img = I('post.person_img');
		
    	//$id存在就是修改,不存在就是添加
    	if ( !$id && !$person_name)
    	{
    		$this->error('请填写人物名称');
    	}
    	if ( !$id && !$person_capacity )
    	{
    		$this->error('请填写挖掘效率');
    	}
		if ( !$id && !$person_blood )
    	{
    		$this->error('请填写人物血量');
    	}
		if ( !$id && !$person_property )
    	{
    		$this->error('请填写人物成长');
    	}
		if ( !$id && !$person_price )
    	{
    		$this->error('请填写人物价格');
    	}
		if ( !$id && !($status=='0' || $status==1) )
    	{
    		$this->error('请填写人物是否隐藏');
    	}
		if ( !$id && !$person_img )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('Person')->where("person_name = '{$person_name}'")->count() )
    	{
    		$this->error('人物已经存在,请换一个!');
    	}

    	$data = [
    		'person_name' => $person_name,
    		'person_capacity' => $person_capacity,
    		'person_blood' => $person_blood,
    		'person_price' => $person_price,
    		'person_property' => $person_property,
    		'person_img' => $person_img,
    	];

    	if ( $id )
    	{
    		M('Person')->where("id = $id")->save($data);
    	}
    	else 
    	{
    		M('Person')->add($data);
    	}

		$this->success('操作成功!',U('Person/index'),2);
		exit;
    }
    /**
	 * 删除人物
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
		
		M('Person')->delete($id);
		$this->success('删除成功');
	}
}