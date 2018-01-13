<?php

namespace Home\Model;
use Think\Model;
class MedicheModel extends Model
{

    //获取背包药水
    public function getBagMediche()
    {
        $mediche = M('mediche_bag')
            ->where(['user_id' => session('user_id'), 'mediche_num' => ['GT', 0]])
            ->join('left join mediche on mediche_bag.mediche_id = mediche.id')
            ->field('mediche_bag.*, mediche.mediche_name, mediche.mediche_img,mediche.mediche_treat')
            ->select();

        if ($mediche) {
            return ['status' => true, 'data' => $mediche];
        } else {
            return ['status' => false, 'msg' => '背包中没有药水'];
        }

    }

    //获取商城药水
    public function getStoreMediche()
    {
        $mediche = M('mediche')
            ->where(['status' => 0])
            ->select();

        if ($mediche) {
            return ['status' => true, 'data' => $mediche];
        } else {
            return ['status' => false, 'msg' => '商城中没有药水'];
        }


    }

    //使用药水
    public function useMediche($person_bag_id, $mediche_bag_id)
    {
        if (empty($person_bag_id) || empty($mediche_bag_id)) {
            return ['status' => false, 'msg' => '缺少参数'];
        }

        $rst = M('mediche_bag')
           ->join('left join mediche on mediche_bag.mediche_id = mediche.id')
           ->where(['mediche_bag.id' => $mediche_bag_id, 'user_id' => session('user_id')])
           ->find();

        if ($rst['mediche_num'] > 0) {
            $trans = new Model();
            $trans->startTrans();

            $medicheRst = M('mediche_bag')
               ->where(['mediche_bag.id' => $mediche_bag_id, 'user_id' => session('user_id')])
               ->setDec('mediche_num',1);

            $personRst = M('person_bag')
              ->where(['id' => $person_bag_id, 'user_id' => session('user_id')])
              ->setInc('blood', $rst['mediche_treat']);

            if (!$medicheRst || !$personRst) {
                $trans->rollback();
                return ['status' => false, 'msg' => '使用失败'];
            } else {
                D('Log')->addLog('使用道具:'. $rst['mediche_name'] . '一个，回复人物血量'.$rst['mediche_treat'], session('user_id'));
                $trans->commit();
                return ['status' => true];
            }

        } else {
            return ['status' => false, 'msg' => '药水剩余数量不足'];
        }

    }

}
