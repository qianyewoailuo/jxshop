<?php
//命名空间
namespace Home\Model;
// 订单管理模型
class OrderModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','user_id','addtime','total_price','pay_status','name','address','tel','com','no','order_status');
    //订单提交
    //其实这里应该使用事务监听的方法,当出现错误就回滚.不然会导致数据重复或错误写入.
    public function order()
    {
        //1.获取购物车商品信息
        $CartModel = D('Cart');
        $data = $CartModel->getList();          //购物车数据
        if(!$data){
            $this->error = '购物车中没有商品';
            return false;
        }
        //2.商品库存检查
        foreach ($data as $key => $value) {
            $status = $CartModel->checkGoodsNumber($value['goods_id'],$value['goods_count'],$value['goods_attr_ids']);
            if(!$status){
                $this->error = '库存量不足';
                return false;
            }
        }
        //3.向用户订单表写入数据
        $name = I('post.name');
        $address = I('post.address');
        $tel = I('post.tel');
        $user_id = session('user_id');
        //获取总价格
        $total = $CartModel->getTotal($data);
        //数据写入
        $order = array(
            'user_id'       =>    $user_id,
            'addtime'       =>    time(),
            'total_price'   =>    $total['price'],
            'name'          =>    $name,
            'address'       =>    $address,
            'tel'           =>    $tel    
        );
        $order_id = $this->add($order);    //受影响记录就是订单ID号
        //4.向商品订单表写入数据
        //循环获取商品订单数据
        foreach ($data as $key => $value) {
            $order_goods[] = array(
                'order_id'          =>    $order_id,
                'goods_id'          =>    $value['goods_id'],
                'goods_attr_ids'    =>    $value['goods_attr_ids'],
                'price'             =>    $value['goods']['shop_price'],
                'goods_count'       =>    $value['goods_count']    
            );
        }
        //批量写入数据
        $rs = D('OrderGoods')->addAll($order_goods);
        if(!$rs){
            $this->error = '商品订单表写入失败';
            return false;
        }
        //5.商品库存量减少
        foreach ($data as $key => $value) {
            //1.总库存减少以及
            D('Admin/Goods')->where('id='.$value['goods_id'])->setDec('goods_number',$value['goods_count']);
            //2.总销量增加
            D('Admin/Goods')->where('id='.$value['goods_id'])->setInc('sale_number',$value['goods_count']);
            //3.单选属性组合库存减少
            if($value['goods_attr_ids']){
                //自动条件规则
                $map = array(
                    'goods_id'        =>  $value['goods_id'],
                    'goods_attr_ids'  =>  $value['goods_attr_ids']
                );
                D('Admin/GoodsNumber')->where($map)->setDec('goods_number',$value['goods_count']);
            }
        }
        //6.清空购物车数据
        $CartModel->where('user_id='.$user_id)->delete();
        //7.支付备用
        $order['id'] = $order_id;
        return $order;
        // return true;
    }

}