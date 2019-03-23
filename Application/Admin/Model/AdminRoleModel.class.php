<?php
//命名空间
namespace Admin\Model;
// ----管理模型
class AdminRoleModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','admin_id','role_id');
    //定义规则
    // protected $_validate = array(
    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    /**
     * 验证规则:require 字段必须、email 邮箱、url URL地址、currency 货币、number 数字
     * 验证条件:0存在字段就验证 1必须验证 2值不为空的时候验证
     * 附加规则:常用->regex正则 callback回调 function函数
     */
    // );
}