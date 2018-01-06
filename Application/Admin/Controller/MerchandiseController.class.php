<?php
namespace Admin\Controller;
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
class MerchandiseController extends PrivilegeController{
    /**
     *  商品列表
     *
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    public function lists() {
        if (I('get.start_time')) {

            $time1 = strtotime(I('get.start_time'));
            if (I('get.end_time') && I('get.end_time') >= I('get.start_time')) {
                $time2 = strtotime(I('get.end_time')) - 1 + 86400;

            } else {
                $time2 = strtotime(I('get.start_time')) - 1 + 86400;
            }
            $where['create_time'] = array(array('egt', $time1), array('elt', $time2), 'and');

        }

        if (I('get.user')){
            $where['id|name|model'] = str_replace(' ','',I('get.user'));
        }

        if (!$where){$where = "";}
        list($merchandiseData,$show) = D('merchandise')->getMerchandiseData($where);
        $this->assign("merchandise",$merchandiseData);
        $this->assign("page",$show);
        $this->display('merchandiseList');
    }


    /**
     *  查看会员详情
     *
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    public function merchandiseShow(){
        $this->assign("merchandise",D('merchandise')->getMerchandiseDetail(I('get.id')));
        $this->display();
    }

    /**
     * 添加角色
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    public function add()
    {
        if ( IS_POST )
        {
            $this->requestSubmit();
        }
        $this->assign('actionName','添加商品');
        $this->display('form');
    }

    /**
     * 处理增加,编辑角色的请求
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    private function requestSubmit()
    {

        if ( !IS_POST )
        {
            $this->error('非法请求');
        }

        $post = I('post.');

        if(!empty($_FILES)){

            $info =  upload();
            $upkey = 'dbspapi/shop/'.date('Ymd').'/';

            if ($info['info']['img']['name']) {
                $upd_file = 'Uploads/'.$info['info']['img']['savepath'].$info['info']['img']['savename'];
                $upd_keyname = $upkey.$info['info']['img']['savename'];
                jscloud_upload($upd_file,$upd_keyname);
                $post['img'] = $upd_keyname;
            }

            if ($info['info']['detail_img']['name']) {
                $upd_file    = 'Uploads/' . $info['info']['detail_img']['savepath'] . $info['info']['detail_img']['savename'];
                $upd_keyname = $upkey . $info['info']['detail_img']['savename'];
                jscloud_upload($upd_file, $upd_keyname);
                $post['detail_img'] = $upd_keyname;
            }
        }

        if ( !$post['name'] )
        {
            $this->error('请填写商品名称');
        }

        if ( !$post['introduce'] )
        {
            $this->error('请填写商品简介');
        }

        if ( !$post['num'] )
        {
            $this->error('请填写商品数量');
        }

        if ( !$post['price'] )
        {
            $this->error('请填写商品价格');
        }

        if ( !$post['orig'] )
        {
            $this->error('请填写市场价格');
        }

        if ( !$post['type'] )
        {
            $this->error('请选择商品类型');
        }

        if ( !$post['model'] )
        {
            $this->error('请填写型号');
        }

        if ( !$post['detail_img'] && !$post['id'])
        {
            $this->error('请选择详情图片');
        }

        if ( !$post['img'] && !$post['id'])
        {
            $this->error('请选择图片');
        }

        if ( $post['type'] == 2)
        {
            M('Merchandise')->where(['type' => 2])->save(['type' => 1]);
        }

        if ( $post['id'] )
        {
            $model = M("Merchandise");
            $model->find($post['id']);
            $model->name = $post['name'];
            $model->introduce = $post['introduce'];
            $model->num = $post['num'];
            $model->price = $post['price'];
            $model->orig = $post['orig'];
            $model->type = $post['type'];
            $model->model = $post['model'];
            $model->detail_img = $post['detail_img'] ? $post['detail_img'] : $model->detail_img;
            $model->img = $post['img'] ? $post['img'] : $model->img;
            $model->save();
        }
        else
        {
            $post['create_time'] = time();
            M("Merchandise")->add($post);
        }
        $this->success('操作成功!',U('Merchandise/lists'));
        die;
    }

    /**
     * 编辑菜单
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    public function edit()
    {
        if (IS_POST)
        {
            $this->requestSubmit();
        }

        $id = I('get.id',0,'intval');
        if ( !$id )
        {
            $this->error('非法请求');
        }

        $menuInfo = M('merchandise')->find($id);

        if ( !$menuInfo )
        {
            $this->error('该商品已不存在,请重新登录在试!');
        }

        $this->assign('merchandise',$menuInfo);
        $this->assign('actionName','编辑商品');
        $this->display('form');
    }

    /**
     * 删除菜单
     * @return void
     * @author liyang
     * @since  2017年11月16日
     */
    public function putaway()
    {
        $id = I('get.id',0,'intval');
        if ( !$id )
        {
            $this->error('非法请求');
        }

        $model = M('merchandise');
        $model->find($id);

        if ($model->putaway == 1) {
            $model->putaway = 2;
        } else {
            $model->putaway = 1;
        }

        $model->save();

        $this->success('修改成功');
    }
}