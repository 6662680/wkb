<?php
namespace Home\Controller;
use Think\Controller;
use JPush\Client as JPush;
class BagController extends BaseController

{
    public function __construct()
	{
        parent::__construct();

	}

    /**
     * 获取人物
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */
    public function person()
    {
        $person = D('person')->getBagPerson();

        foreach ($person as &$value) {
            $level = $value['level'] / 10;

            $img = M('person_img')->where(['person_id' => $value['person_id'], 'level' => floor($level)])->find();

            if ($value['blood'] > 0) {
                $value['person_img'] = $img['action_img'];
            } else {
                $value['person_img'] = $img['img'];
            }
        }

        $this->assign('person',$person);
		/*pr($person);die;*/
        $this->display('index');
    }

    /**
     * 人物详情
     * @author LiYang
     * @date 2018-1-7
     * @return void
     */

    public function personDetails()
    {
        $id = I('get.id');
        $m = I('get.m');
        if (!$id) {
           $this->error();
        }

        $person = D('person')->getBagPersonDetails($id);
		/*pr($person);die;*/
		
			//显示动态图
            $level = $person['level'] / 10;

            $img = M('person_img')->where(['person_id' => $person['person_id'], 'level' => floor($level)])->find();

            if ($person['blood'] > 0) {
                $person['person_img'] = $img['action_img'];
            } else {
                $person['person_img'] = $img['img'];
            }

        if (!$person['id']) {
            $this->error();
        }

        if ($person['equipment_id'] != 0) {
           $person['equipment_info'] = D('equipment')->getBagEquipment($person['equipment_id']);
        }

        if ($person['equipment_id_card'] != 0) {
            $person['equipment_card_info'] = D('equipment')->getBagEquipment($person['equipment_id_card']);
        }

        $equipment_all = D('equipment')->getBagEquipment(false, true);

        $mediche_all = D('mediche')->getBagMediche(FALSE, true);
        $log = D('log')->findLog(session('user_id'), $id, 2, 10);
//        pr($mediche_all);die();
        $this->assign('person',$person);
        $this->assign('m',$m);
        $this->assign('equipment_all',$equipment_all);
        $this->assign('mediche_all',$mediche_all);
        $this->assign('log',$log);
        $this->display('main');
    }

	/*个人中心*/
	public function userMe()
    {
        $user = D('user')->getUser();
        $person = D('person')->getBagPerson();
		$personNum=count($person);
        $sumEarnings = 0;
		foreach ($person as $key => $value) {
			$sumEarnings+=$value['yesterday_capacity'];
		}
		/*获取会员的订单数据*/
		$buyOrderList=D('user')->getBuyOrder();
		$buyOrderNum=count($buyOrderList['data']);
		$sellOrderList=D('user')->getSellOrder();
		$sellOrderNum=count($sellOrderList['data']);
		$OrderNum=$buyOrderNum+$sellOrderNum;
		/*pr($OrderNum);die;*/
		/*获取会员的商城订单数据*/
		$scOrderList=D('user')->getscOrder();
		$scOrderNum=count($scOrderList['data']);
		/*pr($OrderNum);die;*/
		
		$wpointList = M('user_withdraw')->where(['user_id' => session('user_id'),'status' => 2])->field('SUM(wpoint)')->find();
		/*pr($wpointList['sum(wpoint)']);die;*/
		$sumWpoint=$wpointList['sum(wpoint)'];
		
		$this->assign('sumWpoint',$sumWpoint);
		$this->assign('user',$user);
        $this->assign('person',$person);
        $this->assign('personNum',$personNum);
        $this->assign('sumEarnings',$sumEarnings);
        $this->assign('OrderNum',$OrderNum);
		$this->assign('scOrderNum',$scOrderNum);
		
        $this->display('me');
    }
	/*获取会员的订单数据*/
	public function orderDetail()
    {
		$buyOrder=D('user')->getBuyOrder();
		$sellOrder=D('user')->getSellOrder();
		$buyOrderList=$buyOrder['data'];
		$sellOrderList=$sellOrder['data'];
		foreach ($buyOrderList as $key => $value) {
			if ($value['commodity_type']==1) {
					$nperson = M('person')->where(['id' => $value['commodity_id']])->find();
					
					/*pr($value);die();*/
					//修改为动态图
					$level = $buyOrderList[$key]['person_level'] / 10;
		            $img = M('person_img')->where(['person_id' => $nperson['id'], 'level' => floor($level)])->find();
					/*pr($nperson);die();*/
		            if ($buyOrderList[$key]['residue'] > 0) {
		                $buyOrderList[$key]['commodity_img'] = $img['action_img'];
		            } else {
		                $buyOrderList[$key]['commodity_img'] = $img['img'];
		            }
					
			} elseif($value['commodity_type']==2) {
					$nequipment = M('equipment')->where(['id' => $value['commodity_id']])->find();
					$buyOrderList[$key]['commodity_img']=$nequipment['equipment_img'];

			}
			
		}
		/*pr($buyOrderList);die;*/
        $this->assign('user_id',session('user_id'));
		$this->assign('buyOrderList',$buyOrderList);
		$this->assign('sellOrderList',$sellOrderList);
		/*pr($nperson['person_img']);die;*/
        $this->display('want');
    }


	/*获取会员的出售订单数据*/
	public function sellorderDetail()
    {
		$sellOrder=D('user')->getSellOrder();
		$sellOrderList=$sellOrder['data'];
		/*pr($sellOrderList);die;*/

		foreach ($sellOrderList as $key => $value) {
			if ($value['commodity_type']==1) {
                $nperson = M('person_bag')->where(['id' => $value['commodity_id']])->find();
                $person = M('person')->where(['id' => $nperson['person_id']])->find();

                if ($value['equipment_id']) {

                    $equipment_bag = M('equipment_bag')->where(['id' => $value['equipment_id']])->find();

                    $nequipment = M('equipment')->where(['id' =>$equipment_bag['equipment_id']])->find();

                    $sellOrderList[$key]['equipment']['equipment_img'] = $nequipment['equipment_img'];
                    $sellOrderList[$key]['equipment']['equipment_endurance'] = $equipment_bag['equipment_endurance'];
                    $sellOrderList[$key]['equipment']['equipment_multiple'] = $nequipment['equipment_multiple'];
                    $sellOrderList[$key]['equipment']['equipment_name'] = $nequipment['equipment_name'];
                }

                if ($value['equipment_id_card']) {
                    $equipment_bag = M('equipment_bag')->where(['id' => $value['equipment_id_card']])->find();
                    $nequipment = M('equipment')->where(['id' =>$equipment_bag['equipment_id']])->find();
                    $sellOrderList[$key]['equipment_card']['equipment_img'] = $nequipment['equipment_img'];
                    $sellOrderList[$key]['equipment_card']['equipment_endurance'] = $equipment_bag['equipment_endurance'];
                    $sellOrderList[$key]['equipment_card']['equipment_multiple'] = $nequipment['equipment_multiple'];
                    $sellOrderList[$key]['equipment_card']['equipment_name'] = $nequipment['equipment_name'];
                }

                $sellOrderList[$key]['commodity_img']=$person['person_img'];
                $sellOrderList[$key]['blood']=$nperson['blood'];
                $sellOrderList[$key]['level']=$nperson['level'];
                $sellOrderList[$key]['capacity']=$person['person_capacity'];
                $sellOrderList[$key]['person_property']=$person['person_property'];



                $sellOrderList[$key]['capacity'] = $person['person_capacity'] + ($person['person_property'] * $nperson['level']);

                $sellOrderList[$key]['capacity'] = $sellOrderList[$key]['capacity'] * $sellOrderList[$key]['equipment']['equipment_multiple'];

				/*pr($nperson);die;*/
				//修改为动态图
				$level = $sellOrderList[$key]['level'] / 10;

	            $img = M('person_img')->where(['person_id' => $nperson['person_id'], 'level' => floor($level)])->find();
	
	            if ($sellOrderList[$key]['blood'] > 0) {
	                $sellOrderList[$key]['commodity_img'] = $img['action_img'];
	            } else {
	                $sellOrderList[$key]['commodity_img'] = $img['img'];
	            }

				
			} elseif($value['commodity_type']==2) {
                $nequipment = M('equipment_bag')->where(['id' => $value['commodity_id']])->find();
                $equipment = M('equipment')->where(['id' => $nequipment['equipment_id']])->find();
				$sellOrderList[$key]['equipment_endurance']=$nequipment['equipment_endurance'];
				$sellOrderList[$key]['equipment_multiple']=$equipment['equipment_multiple'];
				/*pr($sellOrderList[$key]);die;*/
				
                $sellOrderList[$key]['commodity_img']=$equipment['equipment_img'];
				/*pr($sellOrderList[$key]);die;*/
			}
		}


		$this->assign('sellOrderList',$sellOrderList);
		/*pr($nperson['person_img']);die;*/
        $this->display('sellout');
    }

	/*获取会员的商城订单数据*/
	public function scorderDetail()
    {
		$scOrder=D('user')->getscOrder();
		$scOrderList=$scOrder['data'];
		foreach ($scOrderList as $key => $value) {
			if ($value['commodity_type']==1) {
					$nperson = M('person')->where(['id' => $value['commodity_id']])->find();
					$scOrderList[$key]['commodity_img']=$nperson['person_img'];
					$scOrderList[$key]['commodity_name']=$nperson['person_name'];
					$scOrderList[$key]['commodity_residue']=$nperson['person_blood'];
					$scOrderList[$key]['commodity_capacity']=$nperson['person_capacity'];
					
					
					
			} elseif($value['commodity_type']==2) {
					$nequipment = M('equipment')->where(['id' => $value['commodity_id']])->find();
					$scOrderList[$key]['commodity_img']=$nequipment['equipment_img'];
					$scOrderList[$key]['commodity_name']=$nequipment['equipment_name'];
					$scOrderList[$key]['commodity_residue']=$nequipment['equipment_endurance'];
					$scOrderList[$key]['commodity_capacity']=$nequipment['equipment_multiple'];
					$scOrderList[$key]['commodity_protect']=$nequipment['equipment_protect'];

			}elseif($value['commodity_type']==3) {
					$nmediche = M('mediche')->where(['id' => $value['commodity_id']])->find();
					$scOrderList[$key]['commodity_img']=$nmediche['mediche_img'];
					$scOrderList[$key]['commodity_name']=$nmediche['mediche_name'];
					$scOrderList[$key]['commodity_residue']=$nmediche['mediche_treat'];
					
					

			}
			
		}
		/*pr($buyOrderList);die;*/
        $this->assign('user_id',session('user_id'));
		$this->assign('scOrderList',$scOrderList);
		/*pr($nperson['person_img']);die;*/
        $this->display('scwant');
    }

	/*支付注册费*/
	public function zhifu()
    {
    	$user=M('user')->where(['id' => session('user_id')])->find();
		$sitetemp=$user['sitetemp'];
        $site = D('log')->getconfig('site');
		/*pr($sitetemp);die;*/
		$this->assign('sitetemp',$sitetemp);
		$this->assign('site',$site);
        $this->display('zhifu');
    }
	/*取消支付*/
	public function unZhifu()
    {
    	
		$this->redirect(U('bag/userMe','',''));
           
    }


	/*系统公告*/
	public function newspage()
    {
    	$notice=M('Notice')->where(' isdel=1')->order('creation_time desc')->limit(3)->select();
    	$collapse=M('Collapse')->order('collapse_time desc')->limit(3)->select();
		
        $this->assign('notice',$notice);
        $this->assign('collapse',$collapse);
        $this->display('newspage');
    }
	/*帮助中心*/
	public function help()
    {
        $this->display('help');
    }

    /**
     * 获取装备
     * @author LiYang
     * @date 2018-1-8
     * @return void
     */
    public function equipment()
    {
        $equipment = D('equipment')->getBagEquipment();

        $this->assign('equipment',$equipment);
        $this->display('equipment');
    }

    /**
     * 获取药品
     * @author LiYang
     * @date 2018-1-8
     * @return void
     */
    public function mediche()
    {
        $mediche = D('mediche')->getBagMediche();

        $this->assign('mediche',$mediche);
        $this->display('mediche');
    }

    /**
     * 卸下或者装备道具
     * @author LiYang
     * @date 2018-1-11
     * @return void
     */
    public function switchover()
    {
        $this->ban();

        $post = I('post.');
        $equipment = D('equipment')->switchover($post['person_bage_id'], $post['equipment_bag_id'], $post['type']);

        if ($equipment['status'] == true) {
            returnajax(true);
        } else {
            returnajax(false, '' , $equipment['msg']);
        }
    }

    /**
     * 使用药水
     * @author LiYang
     * @date 2018-1-12
     * @return void
     */
    public function useMediche()
    {
        $this->ban();
        $post = I('post.');
        $mediche = D('mediche')->useMediche($post['person_bag_id'], $post['mediche_bag_id']);

        if ($mediche['status'] == true) {
            returnajax(true);
        } else {
            returnajax(false, '' , $mediche['msg']);
        }
    }
}