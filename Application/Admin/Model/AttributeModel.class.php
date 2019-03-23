<?php
//命名空间
namespace Admin\Model;
// 属性管理模型
class AttributeModel extends CommonModel
{
    //定义字段
    protected $fields = array(
        'id', 'attr_name', 'type_id', 'attr_type', 'attr_input_type', 'attr_value'
    );
    //定义规则
    protected $_validate = array(
        array('attr_name', 'require', '属性名称必须填写'),
        array('type_id', 'require', '类型名称必须填写'),
        array('attr_type', '1,2', '属性类型必须为1唯一或者2单选', 1, 'in'),
        array('attr_input_type', '1,2', '属性录入方式只能为1手工输入或2列表选择', 1, 'in'),
    );

    //属性删除
    public function remove($attr_id)
    {
        return $this->where('id=' . $attr_id)->delete();
    }

    //列表属性
    public function listData()
    {
        //页面显示记录大小
        $pagesize = 10;
        //记录总数
        $count = $this->count();
        //实例化相关页面大小与记录总数的分页对象
        $page = new \Think\Page($count, $pagesize);
        //通过分页对象获取导航信息
        $show = $page->show();              //分页导航显示
        //获取页码
        $p = intval(I('get.p'));
        //获取页码与页面大小相关的分页数据
        $data = $this->page($p, $pagesize)->select();

        /** 这里是实现将type_id转换为对应的类型名称信息:
         *在模型中可以使用:1.联表查询,2.替换字段值
         *在模板中可以使用:传递type数据遍历比对显示(当前使用这种方法)
         *下面是[替换字段值]方法的具体代码
         *   $type = D('Type')->select();
         *   1.遍历类型数据将其转为以类型ID为索引数组
         *   foreach ($type as $key => $value) {
         *       $typeinfo[$value['id']] = $value;
         *   }
         *   2.遍历分页数据
         *   foreach ($data as $key => $value) {
         *       $data[$key]['type_id'] = $typeinfo[$value['type_id']]['type_name'];
         *   }
         */
        return array(
            'data' => $data,
            'show' => $show
        );
    }
}