<?php
//命名空间
namespace Admin\Controller;
//引用基础类
use Think\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//登录控制器
class LoginController extends Controller
{
    // 登录界面显示
    public function login()
    {
        if(IS_GET){
            //显示登录界面
            $this->display();
        }else{
            //开始登录
            // 配置新的error | success 跳转模板
            C('TMPL_ACTION_ERROR','error');
            C('TMPL_ACTION_SUCCESS','success');
            //获取post信息,可以比对是否填写了用户名或密码,但没必要
            $captcha = I('post.captcha');
            if($captcha == ''){
                $this->error('验证码没有填写');
            }
            //比对验证码
            $verify = new \Think\Verify();
            $rs = $verify->check($captcha);
            if(!$rs){
                $this->error('验证码不正确');
            }
            //比对用户密码
            $username = I('post.username');
            $password = I('post.password');
            $remember = I('post.remember');
            // dump($remember);exit();
            $model = D('Admin');
            $rs = $model->login($username,$password,$remember);
            if(!$rs){
                $this->error('登录失败--->'.$model->getError());
            }else{
                $this->success('登录成功',U('Index/index'));
            }
        }
    }
    //验证码功能
    public function verify()
    {
        //注意,如果开启了gd库仍然不能正常显示验证码,可以先使用ob_clean()清楚缓存再执行以下操作
        //验证码配置
        $config = array(
            'length'    =>  3,
            'imageW'    =>  146,
            'imageH'    =>  40,
            'fontSize'  =>  20
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
        //生成的验证码信息会保存到session中:array('verify_code'=>'当前验证码的值','verify_time'=>'验证码生成的时间戳')
    }
    // 退出登录
    public function logout()
    {
        //清除cookie
        cookie('admin',null);
        // 一般使用header跳转页面，但在TP建议使用重定向
        // header("location:".U('Login/login'));
        redirect(U('Login/login'));
    }
    
}