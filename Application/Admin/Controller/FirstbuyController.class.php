<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class FirstbuyController extends PrivilegeController
{
	/**
	 * 推广基础参数
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$first_buy = M('first_buy')->join('left join equipment on first_buy.aequipment_id = equipment.id')
    	->field('equipment.equipment_name, first_buy.aequipment_id')
    	->find();
		
    	$this->assign('first_buy',$first_buy);
        $this->display();
    }
    
    /**
	 * 推广基础参数编辑
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function edit()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','推广基础参数编辑');
    	$this->assign('spreadInfo',M('spread')->find(I('get.id')));
        $this->display('form');
    }

    /**
     * 处理编辑参数请求
     * @author zh 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$id = I('post.id');
    	
    	$neck = I('post.neck');
    	$award = I('post.award');
		
    	$data = [
    		'neck' => $neck,
    		'award' => $award,
    	];
    		
    	M('spread')->where("id = $id")->save($data);
    	
		$this->success('操作成功!',U('spread/index'),2);
		exit;
    }
    
}