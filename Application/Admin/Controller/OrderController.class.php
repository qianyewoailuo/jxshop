<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// ------控制器
class OrderController extends CommonController
{
    //控制器自动实例模型方法
    protected function model()
    {
        if (!$this->_model) {
            $this->_model = D('Home/Order');
        }
        return $this->_model;
    }
    //订单列表
    public function index()
    {
        $data = $this->model()->select();
        $this->assign('data',$data);
        $this->display();
    }

    //订单发货
    public function send()
    {
        if(IS_GET){
            $order_id = I('get.order_id');
            $info = $this->model()->alias('a')->field('a.*,b.username')->join('left join jx_user b  on a.user_id=b.id')->where('a.id='.$order_id)->find();
            $this->assign('info',$info);
            $this->display();
        }else{
            //发货
            $order_id = I('post.id');
            $info = $this->model()->where('id='.$order_id)->find();
            if(!$info){
                $this->error('订单号为'.$order_id.'的订单不存在');
            }
            if($info['pay_status'] != 1){
                $this->error('该订单未支付,暂时不能发货');
            }
            if($info['order_status'] == 2){
                $this->error('该订单已发货');
            }
            $data = array(
                'com'   =>  I('post.com'),
                'no'    =>  I('post.no'),
                'order_status'  =>  2
            );
            //订单发货
            $this->model()->where('id='.$order_id)->save($data);
            $this->success('发货成功');
        }
    }
}