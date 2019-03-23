<?php
//命名空间
namespace Admin\Model;
// 权限管理模型
class RuleModel extends CommonModel
{
    //定义字段
    protected $fields = array(
        'id','rule_name','module_name','controller_name','action_name','parent_id','is_show'
    );
    //定义规则
    protected $_validate = array(
    //array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    /**
     * 验证规则:require 字段必须、email 邮箱、url URL地址、currency 货币、number 数字
     * 验证条件:0存在字段就验证 1必须验证 2值不为空的时候验证
     * 附加规则:常用->regex正则 callback回调 function函数
     */
        array('rule_name','require','权限名称必须填写'),
        array('module_name','require','模型名称必须填写'),
        array('controller_name','require','控制器名称必须填写'),
        array('action_name','require','方法名称必须填写')
    );
    //获取对应ID记录的子分类
    public function getChildren($id)    
    {
        //先获取分类信息
        $data = $this->select();
        //再格式化分类信息
        $list = $this->getTree($data,$id,1,false);  //查找格式化分类信息
        //最后获取分类下子分类id
        foreach($list as $key => $value){
            $tree[] = $value['id'];
        }
        return $tree;
    }

    //获取分类信息
    public function getCateTree($id=0)  //默认从顶级权限开始
    {
        //获取数据
        $data = $this->select();
        //获取格式化信息
        $list = $this->getTree($data,$id);
        //返回结果
        return $list;
    }

    //格式化分类信息
    public function getTree($data,$id=0,$lev=1,$iscache=true)
    {
        static $list = array();
        //判断是否缓存静态变量获取子分类
        if(!$iscache){
            $list = array();
        }
        //遍历数据
        foreach($data as $key => $value){
            //判断从何处开始查找子分类
            if($value['parent_id'] == $id){
                $value['lev'] = $lev;   //额外添加lev参数
                $list[] = $value;   //将$value值添加进数组
                //递归循环获取子分类
                $this->getTree($data,$value['id'],$lev+1);
            }
        }
        //遍历结束,返回数据
        return $list;
    }
    
    //权限删除
    public function del($rule_id)
    {
        //测试查找是否存在上级权限ID等于当前删除的权限ID
        //存在则表示当前删除的权限含有子权限暂不能删除
        $rs = $this->where("parent_id = $rule_id")->find();
        if($rs){
            $this->error = '此权限含有子权限,暂不能删除';
            return false;
        }
        return $this->where("id = $rule_id")->delete();
    }

    //权限编辑
    public function update($data)
    {
        $tree = $this->getCateTree($data['id']);    //子类
        $tree[] = array('id'=>$data['id']);         //本身
        foreach($tree as $key=>$value){
            if($data['parent_id'] == $value['id']){
                $this->error = '上级权限不能是其子权限或本身';
                return false;
            }
        }
        return $this->save($data);
    }
    
    //没有更多了.....
    
}