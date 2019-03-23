<?php
//命名空间
namespace Home\Model;
// 订单管理模型
class OrderGoodsModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','order_id','goods_id','goods_attr_ids','price','goods_count');

}