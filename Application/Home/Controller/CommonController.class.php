<?php
//命名空间
namespace Home\Controller;              
//引用基类
use Think\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//公共控制器
class CommonController extends Controller
{
    //404 NOT FOUND 提醒
    public function _empty()
    {
        echo '404 NotFound：你输入的是一个不存在的方法',ACTION_NAME;
    }
    //构造函数
    public function __construct()
    {
        //继承父类构造函数
        parent::__construct();
        //分类信息获取以及传递赋值模板
        $cate = \D('Admin/Category')->getCateTree();
        $this->assign('cate',$cate);
    }
    //检查用户是否登录,否则跳转登录页面.
    public function checkLogin()
    {
        $user_id = session('user_id');
        if(!$user_id){
            //没有登录
            $this->error('您还未登录,请先登录',U('User/login'));
        }
    }

    //获取并打印session信息
    public function getSession()
    {
        echo '<pre>';
        var_dump(session());
    }
}