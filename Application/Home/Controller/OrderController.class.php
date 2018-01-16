<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class OrderController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}


    /**
     * 购买商品: 人物，装备，药品 /下单动作
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function buy()
    {
        $post = I('get.');
		/*pr($post);die;*/
		/*pr($post['commodity_type']);die;*/
        $rst = D('order')->buy(session('user_id'), $post['commodity_id'], $post['commodity_type']);
		/*pr($post['commodity_type']);die;*/
        if ($rst['status'] == true) {
        	$this->doneBuy();
            /*returnajax(true, '' ,'下单成功');*/
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }
	public function doneBuy()
    {
       $this->display('order');
    }
    /**
     * 购买商品，异步更新
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function accomplishBuy()
    {

        $rst = D('order')->accomplishBuy('200', 'dsfdsfdsgdfgf');
        pr($rst);
        if ($rst['status']) {
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }


    }

}