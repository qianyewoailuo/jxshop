<?php
// 命名空间
namespace Home\Controller;
// 字符编码
header('Content-type:text/html;charset=utf-8');
// 首页控制器
class IndexController extends CommonController
{
    public function index()
    {
        // 传递一个is_home用以<empty>判断是否是首页,不是首页则隐藏header中的导航页
        $this->assign('is_home',1);
        $GoodsModel = D('Admin/Goods');
        $hot = $GoodsModel->getRecGoods('is_hot');         //热卖商品
        $this->assign('hot',$hot);
        $rec = $GoodsModel->getRecGoods('is_rec');         //推荐商品
        $this->assign('rec',$rec);
        $new = $GoodsModel->getRecGoods('is_new');         //新品商品
        $this->assign('new',$new);
        $crazy = $GoodsModel->getCrazyGoods();             //促销商品
        $this->assign('crazy',$crazy);
        $floor = D('Admin/Category')->getFloor();          //楼层分类及其商品
        $this->assign('floor',$floor);
        $this->display();
    }
    //测试U函数生成url
    public function testU()
    {
        echo U('Admin/Index/index'), '<hr>';
        echo U('index'), '<hr>';
        echo U('index', 'id=2'), '<hr>';
        echo U('index', array('id' => 3, 'name' => 4)), '<hr>';
    }
}