<?php
//命名空间
namespace Admin\Model;
//引用模型基类
use Think\Model;
//自定义公共模型
class CommonModel extends Model
{
    //根据id获取一条记录的方法
    public function findOneById($id)
    {
        return $this->where('id='.$id)->find();
    }
}
