<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 需要权限控制的控制器,统一继承这个
 * @author jlb
 * @since 2016年12月7日09:56:50
 */
class PrivilegeController extends CommonController
{



    public function __construct()
    {
    	parent::__construct();
    	if ( !$this->admin_id ) 
    	{
    		//请先登录
    		$this->error('请先登录',U('Index/index'));
    	}

    	//判断是否有权限.
    	if ( $this->role_id != 1 && (empty($this->privilegeList) || !$this->hasPrivilege(CONTROLLER_NAME, ACTION_NAME)) ) 
    	{
    		//没有权限
    		echo '<script>alert("您没有权限操作此项,如有需要请联系管理员");history.back(-1);</script>';
    		die;
    	}
    }

    /**
     * 判断当前操作是否具有权限
     * @param  [type]  $controller [description]
     * @param  [type]  $action     [description]
     * @return boolean             [description]
     */
    protected function hasPrivilege($controller, $action, $privilegeList=FALSE) 
    {	
    	if ( !$privilegeList ) 
        {
    		$privilegeList = array_column($this->privilegeList, 'url');
    	}
    	if ( !in_array($controller . '/' . $action, $privilegeList) ) 
    	{
    		return false;
    	}
    	return true;
    }


	public function upload()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 10485760;
		$upload->exts = explode(',', 'jpg,gif,png,jpeg');
		$upload->rootPath = './Public/images/';
		$upload->saveName = array('uniqid','');
		$upload->autoSub = true;
		$upload->subName = array('date','Ymd');
		$info = $upload->upload();

		$rst = array();

		if (!$info) {
			$rst['success'] = false;
			$rst['errorMsg'] = $upload->getError();
		} else {
			$rst['success'] = true;
			$rst['info'] = $info;
		}

		return $rst;
	}

	/**
	 * 导出
	 * @param  string  $filename   导出文件名
	 * @param  array   $params     导出数据
	 * @param  bool    $isMultiSheet是否分工作表导出
	 * @return void
	 */
	public function exportData($filename, $params, $isMultiSheet = false)
	{
		ob_end_clean();

		Vendor('PHPExcel.Classes.PHPExcel');

		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("weadoc")
			->setLastModifiedBy("weadoc")
			->setTitle("数据列表")
			->setSubject("数据列表")
			->setDescription("数据列表")
			->setKeywords("数据列表")
			->setCategory("数据列表");

		if ($isMultiSheet) {
			$index = 0;

			foreach ($params as $key => $value) {
				$objPHPExcel->setactivesheetindex($index);
				$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
				$objPHPExcel->getActiveSheet()->setTitle($key);
				$objPHPExcel->getActiveSheet()->fromArray($value);

				$index++;

				if ($index <= count($params)) {
					$objPHPExcel->createSheet($index);
				}
			}

			$objPHPExcel->setactivesheetindex(0);
		} else {
			$objPHPExcel->setactivesheetindex(0);
			$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
			$objPHPExcel->getActiveSheet()->fromArray($params);
		}

		// Redirect output to a client’s web browser (Excel5)
		$filename = $filename ? $filename . '.xls' : 'weadoc_' . date('Y-m-d H:i:s') . '.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}