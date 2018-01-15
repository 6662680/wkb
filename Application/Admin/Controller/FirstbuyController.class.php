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
    	$this->assign('actionName','首次消费奖励编辑');
		$this->assign('equipmentInfo',M('equipment')->select());
    	$this->assign('firstbuyInfo',M('first_buy')->find());
        $this->display('form');
    }

    /**
     * 处理编辑参数请求
     * @author zh 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$aequipment_id = I('post.aequipment_id');
		
    	$data = [
    		'aequipment_id' => $aequipment_id,
    	];
    		
    	M('first_buy')->where(' id=1 ')->save($data);
    	
		$this->success('操作成功!',U('Firstbuy/index'),2);
		exit;
    }
    
}