<?php
//命名空间
namespace Home\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 购物车控制器
class CartController extends CommonController
{
    //购物车商品添加功能
    public function addCart()
    {
        $goods_id = intval(I('post.goods_id'));
        $goods_count = intval(I('post.goods_count'));
        $attr = I('post.attr');
        //实例化购物车模型实现数据写入
        $model = D('Cart');
        $rs = $model->addCart($goods_id,$goods_count,$attr);
        if(!$rs){
            $this->error($model->getError());
        }
        $this->success('商品添加成功');
    }
    //获取cookie
    public function getCookie()
    {
        dump(cookie());
    }
    //购物车列表显示
    public function index()
    {
        $model = D('Cart');
        //获取购物车及商品相关信息
        $data = $model->getList();
        //计算总金额
        $total = $model->getTotal($data);
        $this->assign('total',$total);
        $this->assign('data',$data);
        $this->display();
    }
    //购物车商品删除
    public function del()
    {
        $goods_id = intval(I('get.goods_id'));
        if($goods_id < 0){
            $this->error('参数错误');
        }
        $goods_attr_ids = I('get.goods_attr_ids');
        D('Cart')->del($goods_id,$goods_attr_ids);
        $this->success('删除成功');
    }

    //购物车商品数量增加以及减少的更新
    public function updateCount()
    {
        $goods_id = intval(I('post.goods_id'));
        $goods_count = intval(I('post.goods_count'));
        $goods_attr_ids = I('post.goods_attr_ids');
        D('Cart')->updateCount($goods_id,$goods_attr_ids,$goods_count);
    }

}