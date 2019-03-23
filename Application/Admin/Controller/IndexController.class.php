<?php
//命名空间
namespace Admin\Controller;
// use Think\Controller;       //引用当前目录的公共控制器不必引用
//字符编码
header('Content-type:text/html;charset=utf-8');
//自定义后台首页控制器
class IndexController extends CommonController {
    public function index(){
        $this->display();
    }
    public function menu()
    {
        //导航菜单显示权限信息
        // dump($this->user['menus']);
        $this->assign('menus',$this->user['menus']);
        $this->display();
    }
    public function top()
    {
        $this->display();
    }
    public function main()
    {
        $this->display();
    }
}