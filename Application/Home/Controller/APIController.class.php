<?php
//命名空间
namespace Home\Controller;
use Think\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 自定义的API控制器
/**
 * 注意当当前继承的控制器有登录验证就不能继承该控制器
 * 综述自定义接口注意点:
 * 1.接口不能有登录验证
 * 2.接口接收到的参数都必须要验证
 * 3.接口需要充分考虑每一种情况并返回处理结果
 * 4.接口返回数据之前需要先将重要的数据(例如使用unset())去除
 */
class APIController extends Controller
{
    public function login()
    {
        $username = I('get.username');
        $password = I('get.password');
        dump($password);
        dump($username);
        if (!$username || !$password) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'参数错误'));
        }
        $model = D('User');
        $info = $model->where("username='$username'")->find();
        if(!$info){
            $this->ajaxReturn(array('status'=>0,'msg'=>'用户不存在'));
        }
        if($info['status'] != 1){
            $this->ajaxReturn(array('status'=>0,'msg'=>'用户未激活'));
        }
        if($info['password'] != md5(md5($password).$info['salt'])){
            $this->ajaxReturn(array('status'=>0,'msg'=>'用户密码错误'));
        }
        $this->ajaxReturn(array('status'=>1,'msg'=>'ok','data'=>$info));
    }
}