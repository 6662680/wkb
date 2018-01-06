<?php
namespace Mobile\Controller;
use Think\Controller;
/**
 *
 * @category   MerchandiseController
 * @package    User
 * @author  stone <664577655@qq.com>
 * @license
 * @version    PHP version 5.6
 * @link
 * @since   2017年11月3日
 *
 **/
class MerchandiseController extends BaseController{
    /**
     *  商品列表
     *
     * @return void
     * @author stone
     * @since  2016年12月7日
     */
    public function lists()
    {
        $merchandiseData = M('merchandise')->where(['putaway' => 1, 'type' => 1])->save();

        returnajax('true', $merchandiseData, '成功');
        $this->display('merchandiseList');
    }

}