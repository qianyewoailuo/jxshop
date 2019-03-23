<?php
//命名空间
namespace Home\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 分类控制器
class CategoryController extends CommonController
{
    // 分类首页显示
    public function index()
    {
        $model = D('Admin/Goods');
        $data = $model->getList();
        $this->assign('data',$data);
        $this->display();
    }

}