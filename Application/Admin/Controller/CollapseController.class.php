<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class CollapseController extends PrivilegeController
{
	/**
	 * 坍塌设置页面
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	
        $this->display();
    }
    
  

    /**
     * 处理坍塌请求
     * @author zh 
     * @return [type] [description]
     */
    public function collapse()
    {
    	$data    = [
		    'blood'     => 1,
		            
		];
		$data2    = [
		    'collapse_time'     => time(),
		            
		];
		$rst=M('Person_bag')->where('equipment_id_card=0 AND blood>1 ')->save($data);
		$rst=M('Collapse')->add($data2);
		
		$this->success('触发坍塌成功!',U('Collapse/index'),2);
    }
    
}