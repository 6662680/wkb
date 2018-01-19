<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7
 */
class PconfigController extends PrivilegeController
{
	/**
	 * 人物升级配置列表
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$configList = M('Config')->limit($page->firstRow, $page->listRows)->select();
		$configarr = json_decode($configList[0]['value'], true);
		
		$arr = array();
		foreach ($configarr as $key => $value) {
			$arr[$key]['level'] = $key;
			$arr[$key]['tlevel'] = ($key)*10;
			$arr[$key]['tlevel2'] = ($key+1)*10-1;
			$arr[$key]['value'] = $value;
		}
		/*pr($arr);die;*/
    	$this->assign('configList',$arr);
        $this->display();
    }
    /**
	 * 人物升级配置添加
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function add()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','人物升级配置添加');
    	$this->assign('roleList',M('Role')->where("role_id <> " . ($this->role_id == 1 ? 0 : 1))->select());
        $this->display('form');
    }
    /**
	 * 人物升级配置编辑
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function edit()
    {
    	if ( IS_POST )
    	{
    		$this->requestSubmit();
    	}
    	$this->assign('actionName','人物升级配置编辑');
		
		$configList = M('Config')->limit($page->firstRow, $page->listRows)->select();
		$configarr = json_decode($configList[0]['value'], true);
		
		$arr = array();
		foreach ($configarr as $key => $value) {
			$arr[$key]['level'] = $key;
			$arr[$key]['tlevel'] = ($key)*10;
			$arr[$key]['tlevel2'] = ($key+1)*10-1;
			$arr[$key]['value'] = $value;
		}
		/*pr($arr[8]);die;*/
    	
    	$this->assign('config',$arr[I('get.level')]);
    	
        $this->display('form');
    }

    /**
     * 处理修改人物升级难度时间配置请求
     * @author zh 
     * @return [type] [description]
     */
    private function requestSubmit()
    {
    	$level = I('post.level');
    	$nvalue = I('post.nvalue');

		$configList = M('Config')->limit($page->firstRow, $page->listRows)->select();
		$configarr = json_decode($configList[0]['value'], true);
		
    	$configarr[$level] = $nvalue;
		
		$nconfigarr = json_encode($configarr);
		$data = [
    		'value' => $nconfigarr,
    	];
		M('Config')->where("id = '1' ")->save($data);
		$this->success('操作成功!',U('Pconfig/index'),2);
		exit;
    }
}