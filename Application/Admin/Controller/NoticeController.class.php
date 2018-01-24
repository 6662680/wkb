<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * @author zh <[<email address>]>
 * @since 2018.1.7 
 */
class NoticeController extends PrivilegeController
{
	/**
	 * 公告页面
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
    public function index()
    {
    	
        $this->display();
    }
    /**
	 * 撤销公告
	 * @author zh <[<email address>]>
	 * @return [type] [description]
	 */
  public function edit()
    {
    	$notice=M('Notice')->order('creation_time desc')->where(' isdel=1')->select();
		
        $this->assign('notice',$notice);
        $this->display();
    }

/**
     * 处理撤销请求
     * @author zh 
     * @return [type] [description]
     */
    public function del()
    {
    	$id=I('get.id');
    	/*pr($id);die;*/	
    	$data    = [
		    'isdel' =>2,
		            
		];
		
		$rst=M('Notice')->where( " id= $id ")->save($data);
		
		$this->success('公告撤销成功!',U('Notice/edit'),2);
    }


    /**
     * 处理发布请求
     * @author zh 
     * @return [type] [description]
     */
    public function notice()
    {
    	$title=I('post.title');
    	$text=I('post.text');
    	if (!$text  || !$title) {
    		$this->error('请输入公告标题或内容');
    	}
    	
    	
    	$data    = [
    		'title'     => $title,
		    'text'     => $text,
		    'creation_time' =>time(),
		            
		];
		
		$rst=M('Notice')->add($data);
		
		$this->success('公告发布成功!',U('Notice/index'),2);
    }
    
}