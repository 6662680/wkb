<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class PutorderController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}


    /**
     * 挂单
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function buy()
    {
        $post = I('post.');

        $rst = D('Putorder')->creationOrder(session('user_id'), $post['commodity_id'], $post['commodity_type'], $post['commodity_price'] ,1);

        if ($rst['status'] == true) {
            returnajax(true, '' ,'下单成功');
        } else {
            returnajax(false, '' , $rst['msg']);
        }
    }

    /**
     * 购买商品，异步更新
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function accomplishBuy()
    {
        $id = 2;
        $rst = D('putorder')->accomplishBuy('200', '2132142342');

        if ($rst['status']) {
            returnajax(true, '','购买成功');
        } else {
            returnajax(false, '', $rst['msg']);
        }


    }

}