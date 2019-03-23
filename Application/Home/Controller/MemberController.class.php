<?php
//命名空间
namespace Home\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 用户中心控制器
class MemberController extends CommonController
{
    //构造函数
    public function __construct()
    {
        parent::__construct();
        //因为用户中心的所有功能都必须验证登录,所以直接构造函数实现
        $this->checkLogin();    
    }

    //显示我的订单中心
    public function order()
    {
        $user_id = session('user_id');      //获取用户ID
        $data = D('Order')->where('user_id='.$user_id)->select();       //获取用户订单信息
        $this->assign('rs',$rs);
        $this->assign('data',$data);
        $this->display();
    }

    //查看快递信息
    public function express()
    {
        $order_id = intval(I('get.order_id'));
        $info = D('Order')->where('id='.$order_id)->find();
        if(!$info || $info['order_status'] != 2){
            $this->error('参数错误');
        }
        //调用快递接口查看快递信息
        $key = '886f0ab48fa8e9dfd09b88ba146338a3';      //接口地址
        $url = 'http://v.juhe.cn/exp/index?key='.$key.'&com='.$info['com'].'&no='.$info['no'];
        //发送请求
        $rs = file_get_contents($url);
        //json转换
        $data = json_decode($rs,true);

        // dump($express);
        $this->assign('data',$data);
        $this->display();
    }
}