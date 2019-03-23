<?php
//命名空间
namespace Home\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 订单控制器
class OrderController extends CommonController
{
    //购物车结算显示
    public function checkout()
    {
        //判断用户是否登录,否则跳转登录页
        $this->checkLogin();
        $model = D('Cart');
        //获取购物车及商品相关信息
        $data = $model->getList();                  
        //获取总金额以及总数量
        $total = $model->getTotal($data);
        $this->assign('total',$total);
        $this->assign('data',$data);
        $this->display();
    }

    //购物车订单提交
    public function order()
    {
        $model = D('Order');
        $rs = $model->order();
        if(!$rs){
            $this->error($model->getError());
        }
        echo '下单成功';
    }

    //购物车订单完成显示
    public function checkover()
    {
        $this->display();
    }

}