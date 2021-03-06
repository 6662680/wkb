<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class SiteController extends PrivilegeController
{
	/**
	 * 域名设置页面
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	$siteList=M('config')->where(' id = 3')->find();
		$site=$siteList['value'];
		/*pr($site);die;*/
		$this->assign("site",$site);
        $this->display();
    }
    
  

    /**
     * 处理域名编辑请求
     * @author zh 
     * @return [type] [description]
     */
    public function site()
    {
    	$site=I('post.site');
		/*pr($site);die;*/
    	if (!$site) {
    		$this->error('请填写域名!');
    	} 
    	$data    = [
		    'value'     => $site,
		            
		];
		$rst=M('config')->where('id=3 ')->save($data);
		
		$this->success('域名设置成功!',U('Site/index'),2);
    }
    
	public function official()
    {
    	$officialList=M('config')->where(' id = 4')->find();
		$official=$officialList['value'];
		/*pr($site);die;*/
		$this->assign("official",$official);
        $this->display();
    }
	/**
     * 处理钱包地址编辑请求
     * @author zh 
     * @return [type] [description]
     */
    public function officialSite()
    {
    	$official=I('post.official');
		/*pr($site);die;*/
    	if (!$official) {
    		$this->error('请填写钱包地址!');
    	} 
    	$data    = [
		    'value'     => $official,
		            
		];
		$rst=M('config')->where('id=4 ')->save($data);
		
		$this->success('钱包地址设置成功!',U('Site/official'),2);
    }
}