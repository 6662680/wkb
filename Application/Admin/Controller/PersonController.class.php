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
    	$PersonList = M('Person')->limit($page->firstRow, $page->listRows)->where("isdel = 1")->select();
	
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
    	/*pr($id);die;*/
    	$person_name = I('post.person_name');
    	$person_capacity = I('post.person_capacity');
    	$person_blood = I('post.person_blood');
		$person_price = I('post.person_price');
		$person_property = I('post.person_property');
		$status = I('post.status');
		$explain = I('post.explain');
		
	
		/*$person_img = I('post.person_img');*/
	    $upload = $this->upload();

//		$upload = new \Think\Upload();// 实例化上传类
//	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
//	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//	    $upload->autoSub = FALSE;// 取消上传时自动生成日期形式的文件夹
//		$upload->saveName = '';// 设置成以原文件名存放
//	    $upload->rootPath  =      './Public/images/person/'; // 设置附件上传根目录
//	    // 上传单个文件
//	    $info   =   $upload->uploadOne($_FILES['person_img']);
		/*pr($_FILES);die();*/
	    
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
		if ( !$id && !$explain )
    	{
    		$this->error('请填写人物说明');
    	}
		if ( !$id && !($status=='0' || $status==1) )
    	{
    		$this->error('请填写人物是否隐藏');
    	}
		if ( !$id && $_FILES['person_img']['error']==4 )
    	{
    		$this->error('请上传图片样式');
    	}

    	if ( !$id && M('Person')->where("person_name = '{$person_name}'")->count() )
    	{
    		$this->error('人物已经存在,请换一个!');
    	}
		
		/*pr($_FILES['person_img']['error']);die;*/
		if ($_FILES['person_img']['error']==4) {
			$data = [
    		'person_name' => $person_name,
    		'person_capacity' => $person_capacity,
    		'person_blood' => $person_blood,
    		'person_price' => $person_price,
    		'person_property' => $person_property,
    		'status' => $status,
    		'explain' => $explain,
    		
    		];
		} else {
			if(!$upload['info']['person_img']) {// 上传错误提示错误信息

	        $this->error($upload['errorMsg']);
		    }else{// 上传成功 获取上传文件信息
		        $person_img='/Public/images/'.$upload['info']['person_img']['savepath'].'/'.$upload['info']['person_img']['savename'];
		    }
			$data = [
    		'person_name' => $person_name,
    		'person_capacity' => $person_capacity,
    		'person_blood' => $person_blood,
    		'person_price' => $person_price,
    		'person_property' => $person_property,
    		'person_img' => $person_img,
    		'status' => $status,
    		'explain' => $explain,
    		
    		];
		}
		

    	/*$data = [
    		'person_name' => $person_name,
    		'person_capacity' => $person_capacity,
    		'person_blood' => $person_blood,
    		'person_price' => $person_price,
    		'person_property' => $person_property,
    		'person_img' => $person_img,
    	];*/

    	if ( $id )
    	{
    		/*pr($data);die;*/
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
		$data = [
    		'isdel' => 2,
    		];
		M('Person')->where("id = $id")->save($data);
		$this->success('删除成功');
	}

	/**
	 * 等级图列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function personImg()
    {
    	$id = I('get.id');
    	$pageSize = 10;
        $p = I('request.p', 1, 'intval');
        $page = getpage(M('person_img')->count(), $pageSize, array());
		
		$model = M('person_img');
		$model->join('left join `person` on person.id=person_img.person_id');
		$model->field('person_img.*,person.person_name');
		$model->limit($page->firstRow, $page->listRows);
		$model->where("person_id = $id");
		$PersonimgList  = $model->select();
		
		foreach ($PersonimgList as $key => $value) {
			$PersonimgList[$key]['level'] = $key;
			$PersonimgList[$key]['tlevel'] = ($key)*10;
			$PersonimgList[$key]['tlevel2'] = ($key+1)*10-1;
		}
		/*pr($PersonimgList);die;*/
    	
    	$this->assign('typeId',$id);
		$this->assign('Personname',$PersonimgList[0]['person_name']);
    	$this->assign('PersonimgList',$PersonimgList);
    	$this->assign('page',$page->show());
        $this->display('personimg');
    }
	/**
	 * 等级图修改--静态
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function editImg1()
    {
    	$id = I('get.id');
		/*pr($id );die;*/
		$typeId=I('get.typeId');
		
		$model = M('person_img');
		$model->join('left join `person` on person.id=person_img.person_id');
		$model->field('person_img.*,person.person_name');
		$model->limit($page->firstRow, $page->listRows);
		$model->where("person_id = $typeId");
		$PersonimgList  = $model->select();
		
    	$nPersonimg = M('Person_img')->where("id = $id")->find();
		/*pr($nPersonimg);die;*/
		$level1=$nPersonimg['level']*10;
		$level2=($nPersonimg['level']+1)*10-1;
		
		$this->assign('level',$nPersonimg['level']);
		$this->assign('level1',$level1);
		$this->assign('level2',$level2);
    	
    	$this->assign('actionName','编辑等级图');
		$this->assign('Personname',$PersonimgList[0]['person_name']);
		
    	$this->assign('typeId',$typeId);
		$this->assign('id',$id);
		
        $this->display('editimg1');
    }
	
	/**
	 * 等级图修改->提交后--静态
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function editPost1()
    {
    	$id = I('post.id');
		$typeId=I('post.typeId');
		/*pr($id );die;*/
		
	    $upload = $this->upload();
		
		if(!$upload['info']['img']) {// 上传错误提示错误信息

	        $this->error($upload['errorMsg']);
		}else{// 上传成功 获取上传文件信息
		        $img='/Public/images/'.$upload['info']['img']['savepath'].'/'.$upload['info']['img']['savename'];
				
		}
		
		$data = [
    		'img' => $img,
    		];
		/*pr($data);die;*/
    	M('Person_img')->where("id = $id")->save($data);

		$this->success('操作成功!',U('Person/personImg',array('id' => $typeId)),2);
    }
	/**
	 * 等级图修改--动态
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function editImg2()
    {
    	$id = I('get.id');
		/*pr($id );die;*/
		$typeId=I('get.typeId');
		
		$model = M('person_img');
		$model->join('left join `person` on person.id=person_img.person_id');
		$model->field('person_img.*,person.person_name');
		$model->limit($page->firstRow, $page->listRows);
		$model->where("person_id = $typeId");
		$PersonimgList  = $model->select();
		
    	$nPersonimg = M('Person_img')->where("id = $id")->find();
		/*pr($nPersonimg);die;*/
		$level1=$nPersonimg['level']*10;
		$level2=($nPersonimg['level']+1)*10-1;
		
		$this->assign('level',$nPersonimg['level']);
		$this->assign('level1',$level1);
		$this->assign('level2',$level2);
    	
    	$this->assign('actionName','编辑等级图');
		$this->assign('Personname',$PersonimgList[0]['person_name']);
		
    	$this->assign('typeId',$typeId);
		$this->assign('id',$id);
		
        $this->display('editimg2');
    }
	
	/**
	 * 等级图修改->提交后--动态
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function editPost2()
    {
    	$id = I('post.id');
		$typeId=I('post.typeId');
		/*pr($id );die;*/
		
	    $upload = $this->upload();
		
		
		if(!$upload['info']['action_img']) {// 上传错误提示错误信息

	        $this->error($upload['errorMsg']);
		}else{// 上传成功 获取上传文件信息
		        $action_img='/Public/images/'.$upload['info']['action_img']['savepath'].'/'.$upload['info']['action_img']['savename'];
				
		}
		$data = [
    		'action_img' => $action_img,
    		];
		/*pr($data);die;*/
    	M('Person_img')->where("id = $id")->save($data);

		$this->success('操作成功!',U('Person/personImg',array('id' => $typeId)),2);
    }
}