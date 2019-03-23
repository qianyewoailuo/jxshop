<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// ------控制器
class DemoController extends CommonController
{
    //控制器自动实例模型方法
    protected function model()
    {
        if (!$this->_model) {
            $this->_model = D('Attribute');
        }
        return $this->_model;
    }

    // ----添加
    public function add()
    {
        if (IS_GET) {
            //未提交前显示信息
            $this->display();
        } else {
            //提交数据后开始入库
        }
    }
    
    // ----删除
    public function del()
    {
        $variable = intval(I('get.variable'));
    }

    // ----显示
    public function index()
    {

    }

    // ----编辑
    public function edit()
    {
        if (IS_GET) {
            //未提交前显示信息
            $variable = intval(I('get.variable'));
            #code..
            $this->display();
        } else {
            //提交数据后开始修改
        }
    }
}