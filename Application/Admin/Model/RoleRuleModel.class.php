<?php
//命名空间
namespace Admin\Model;
// 角色权限中间表模型
class RoleRuleModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','role_id','rule_id');
    //定义规则 no data

    //获取对应角色的角色权限中间表记录
    public function getRules($role_id)
    {
        return $this->where("role_id = $role_id")->select();
    }

    //角色权限中间表入库事务方法(这个方法比较严格可以避免出错)
    public function disfetch($role_id,$rules)
    {
        //开启事务
        $this->startTrans();
        //1.删除之前的数据
        $delStatus = $this->where("role_id = $role_id")->delete();
        if($delStatus === false){
            $this->rollback();
            return false;
        }
        //去重以及遍历获取数据
        $rules = array_unique($rules);
        foreach($rules as $key => $value){
            $data[] = array(
                'role_id' => $role_id,
                'rule_id' => $value
            );
        }
        //2.记录入库
        $addStatus = $this->addAll($data);
        if(!$addStatus){
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }
    //角色权限中间表入库常规方法
    public function disfetch2($role_id,$rules)
    {
        $this->where("role_id = $role_id")->delete();
        $rules = array_unique($rules);
        foreach($rules as $key => $value){
            $data[] = array(
                'role_id' => $role_id,
                'rule_id' => $value
            );
        }
        $rs = $this->addAll($data);
        if(!$rs){
            return false;
        }
        return true;
    }


}