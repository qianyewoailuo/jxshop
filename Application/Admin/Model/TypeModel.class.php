<?php
//命名空间
namespace Admin\Model;
// 角色管理模型
class TypeModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','type_name');
    //定义验证
    protected $_validate = array(
        array('type_name','require','类型名称必须填写!'),
        array('type_name','','类型重复!',1,'unique')
    );

    //角色删除
    public function remove($type_id)
    {
        return $this->where(" id = $type_id ")->delete();
    }

    //角色显示
    public function listData()
    {
        $pagesize = 5;
        $count = $this->count();
        $page = new \Think\Page($count,$pagesize);
        //页码形式显示信息
        $show = $page->show();
        //每页数据显示信息
        $p = intval(I('get.p'));
        $data = $this->page($p,$pagesize)->select();
        return array(
            'data' => $data,
            'show' => $show
        );
    }
}