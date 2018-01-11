<?php

namespace Home\Model;
use Think\Model;
class EquipmentModel extends Model
{

    //获取装备
    public function getBagEquipment()
    {
        $person = M('equipment_bag')
            ->where(['user_id' => session('user_id')])
            ->join('left join equipment on equipment_bag.equipment_id = equipment.id')
            ->field('equipment_bag.*, equipment.equipment_name,equipment.equipment_img,equipment.equipment_endurance,equipment.equipment_protect,equipment_multiple')
            ->select();

        foreach ($person as &$value) {
            $value['capacity'] = $value['person_capacity'] + ($value['person_property'] * $value['level']);
        }

        return $person;
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
    public function switchover($person_bag_id, $equipment_id)
    {
        $person = M('person_bag')
            ->where(['id' => $person_bag_id, 'user_id' => session('user_id')])
            ->select();

        if (empty($person)) {
            return ['status' => false, 'msg' => '不存在的人物'];
        }

        $bag = M('equipment_bag')
            ->where(['equipment_bag.id' => $equipment_id, 'user_id' => session('user_id')])
            ->join('left join `equipment_bag` on equipment_bag.equipment_id= equipment.id')
            ->find();

        if (empty($bag)) {
            return ['status' => false, 'msg' => '不存在的装备'];
        }

        $trans = new Model();
        $trans->startTrans();

        if ($bag['equipment_protect'] != 0) {
            //判断是不是帽子，并且是否装备上，如果装备上，就卸下
            if ($person['equipment_id_card'] == 1) {
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id_card' => 0]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 0]);
            } else {
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id_card' => $equipment_id]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 1]);
            }
        } else {
            //判断是不是装备，并且是否装备上，如果装备上，就卸下
            if ($person['equipment_id'] == 1) {
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id' => 0]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 0]);
            } else {
                $personRst = M('person_bag')->where(['id' => $person_bag_id])->limit(1)->save(['equipment_id' => $equipment_id]);
                $equipmentRst = M('equipment_bag')->where(['id' => $equipment_id])->limit(1)->save(['use' => 1]);
            }
        }

        if ($personRst !== 1 || $equipmentRst !== 1) {
            $trans->rollback();
            return ['status' => false, 'msg' => '装备穿上或卸下失败，请稍后再试'];
        }
        $trans->commit();
        return ['status' => true];
    }

}
