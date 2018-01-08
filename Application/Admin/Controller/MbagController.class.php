<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class MbagController extends PrivilegeController
{
	/**
	 * 食物背包列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('mediche_bag')->count(), $pageSize, array());
    	$mediche_bagList = M('mediche_bag')->limit($page->firstRow, $page->listRows)->select();
    	$this->assign('mediche_bagList',$mediche_bagList);
    	$this->assign('page',$page->show());
        $this->display();
    }
    /**
	 * 食物背包添加
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
        $this->display('form');
    }
    /**
	 * 食物背包编辑
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
    	$this->assign('adminInfo',M('mediche_bag')->find(I('get.id')));
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
    	
    	$mediche_bag_name = I('post.mediche_bag_name');
    	$mediche_bag_treat = I('post.mediche_bag_treat');
		$mediche_bag_price = I('post.mediche_bag_price');
		$mediche_bag_img = I('post.mediche_bag_img');
    	//$admin_id存在就是修改,不存在就是添加
    	if ( !$id && !$mediche_bag_name )
    	{
    		$this->error('请填写食物名称');
    	}
    	if ( !$id && !$mediche_bag_treat )
    	{
    		$this->error('请填写回血值');
    	}
		if ( !$id && !$mediche_bag_price )
    	{
    		$this->error('请填写食物价格');
    	}
		if ( !$id && !$mediche_bag_img )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('mediche_bag')->where("mediche_bag_name = '{$mediche_bag_name}'")->count() )
    	{
    		$this->error('食物已经存在,请换一个!');
    	}

    	$data = [
    		'mediche_bag_name' => $mediche_bag_name,
    		'mediche_bag_treat' => $mediche_bag_treat,
    		'mediche_bag_price' => $mediche_bag_price,
    		'mediche_bag_img' => $mediche_bag_img,
    	];

    	if ( $id )
    	{
    		/*$file = $_FILES['file'];
			$name = $file['name'];
			$type = strtolower(substr($name,strrpos($name,'.')+1)); 
			$allow_type = array('jpg','jpeg','gif','png'); 
			if(!in_array($type, $allow_type)){
			  return ;
			}
			if(!is_uploaded_file($file['tmp_name'])){
			  return ;
			}
			$upload_path = "D:/now/"; 
			if(move_uploaded_file($file['tmp_name'],$upload_path.$file['name'])){
			  echo "Successfully!";
			}else{
			  echo "Failed!";
			}*/
    		M('mediche_bag')->where("id = $id")->save($data);
    	}
    	else 
    	{
    		M('mediche_bag')->add($data);
    	}

		$this->success('操作成功!',U('mediche_bag/index'),2);
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
		
		M('mediche_bag')->delete($id);
		$this->success('删除成功');
	}
}