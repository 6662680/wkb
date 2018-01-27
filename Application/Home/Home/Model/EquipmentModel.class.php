<?php

namespace Home\Model;
use Think\Model;
class EquipmentModel extends Model
{
    Protected $autoCheckFields = false;
    //获取背包装备
    public function getBagEquipment($id=NULL, $s =NULL)
    {
        $model = M('equipment_bag');

        if ($id) {
                $model->where(['user_id' => session('user_id'), 'equipment_bag.id' => $id, 'equipment_bag.equipment_endurance' => ['neq', 0]]);
        } else {
            if ($s) {
                $model->where(['user_id' => session('user_id'), 'use' => 0, 'equipment_bag.equipment_endurance' => ['neq', 0]]);
            } else {
                $model->where(['user_id' => session('user_id'), 'equipment_bag.equipment_endurance' => ['neq', 0]]);
            }
        }

        $model->join('left join equipment on equipment_bag.equipment_id = equipment.id');
        $model->field('equipment_bag.*, equipment.equipment_name,equipment.equipment_img,equipment_bag.equipment_endurance,equipment.equipment_protect,equipment_multiple');

        if ($id) {
            $equipment = $model->find();
        } else {
            $equipment = $model->select();
        }

        return $equipment;
    }

    //获取商城装备
    public function getStoreEquipment()
    {
        $person = M('equipment')
            ->where(['status' => 0])
            ->select();

        return $person;
    }

    //装备卸下与穿戴
    public function switchover($person_bag_id, $equipment_id, $type)
    {

        $person = M('person_bag')
            ->where(['id' => $person_bag_id, 'user_id' => session('user_id')])
            ->find();

        if (empty($person)) {
            return ['status' => false, 'msg' => '不存在的人物'];
        }

        $bag = M('equipment_bag')
            ->where(['equipment_bag.id' => $equipment_id, 'user_id' => session('user_id')])
            ->join('left join `equipment` on equipment_bag.equipment_id= equipment.id')
            ->find();

        if (empty($bag)) {
            return ['status' => false, 'msg' => '不存在的装备'];
        }

        $trans = new Model();
        $trans->startTrans();


        if ($bag['equipment_protect'] != 0) {

            if ($person['equipment_id_card']) {
                M('equipment')->where(['id' => $person['equipment_id_card']])->save(['use' => 0]);
            }

            //判断是不是帽子，并且是否装备上，如果装备上，就卸下
            if ($person['equipment_id_card'] != 0 && $person['equipment_id_card'] == $equipment_id) {
                //卸下
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id_card' => 0]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 0]);
            } else {
                //装备上
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id_card' => $equipment_id]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 1]);
            }
        } else {

            if ($person['equipment_id']) {

                M('equipment_bag')->where(['id' => $person['equipment_id']])->save(['use' => 0]);
            }

            //判断是不是装备，并且是否装备上，如果装备上，就卸下
            if ($type == 0 && $person['equipment_id'] == $equipment_id) {
                //卸下

                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id' => 0]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 0]);
            } else {
                //装备上

                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id' => $equipment_id]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 1]);
            }
        }

        if ($personRst === false || $equipmentRst ===false) {
            $trans->rollback();
            return ['status' => false, 'msg' => '装备穿上或卸下失败，请稍后再试'];
        }
        $trans->commit();
        return ['status' => true];
    }


}
