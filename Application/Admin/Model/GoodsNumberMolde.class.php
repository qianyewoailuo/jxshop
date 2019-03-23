<?php
//命名空间
namespace Admin\Model;
// 商品相册管理模型
class GoodsNumberModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','goods_id','goods_attr_ids','goods_number');
}