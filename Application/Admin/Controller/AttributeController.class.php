<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// 属性控制器
class AttributeController extends CommonController
{
    //控制器自动实例模型方法,以免每次都用D函数实例化
    protected function model()
    {
        if (!$this->_model) {
            $this->_model = D('Attribute');
        }
        return $this->_model;
    }

    // 属性添加
    public function add()
    {
        if (IS_GET) {
            //未提交前显示信息
            $type = D('Type')->select();
            $this->assign('type', $type);
            $this->display();
        } else {
            //提交数据后开始入库
            $data = $this->model()->create();
            if (!$data) {
                $this->error($this->model()->getError());
            }
            $rs = $this->model()->add($data);
            if (!$rs) {
                $this->error('写入失败' . $this->model()->getError());
            }
            $this->success('写入成功');
        }
    }
    
    // 属性删除
    public function del()
    {
        $attr_id = intval(I('get.attr_id'));
        if ($attr_id <= 0) {
            $this->error('参数错误');
        }
        $rs = $this->model()->remove($attr_id);
        if ($rs === false) {
            $this->error('删除失败:' . $this->model()->getError());
        }
        $this->success('删除成功');
    }

    // 属性显示
    public function index()
    {
        $data = $this->model()->listData();
        $type = D('Type')->select();
        $this->assign('type', $type);
        $this->assign('data', $data);
        $this->display();
    }

    // 属性编辑
    public function edit()
    {
        if (IS_GET) {
            //未提交前显示信息
            $attr_id = intval(I('get.attr_id'));
            $info = $this->model()->findOneById($attr_id);
            $this->assign('info', $info);
            $type = D('Type')->select();
            $this->assign('type', $type);
            $this->display();
        } else {
            //提交数据后开始修改
            $data = $this->model()->create();
            if (!$data) {
                $this->error($this->model()->getError);
            }
            if ($data['id'] <= 0) {
                $this->error('参数错误');
            }
            $rs = $this->model()->save($data);
            if ($rs === false) {
                $this->error('修改失败:' . $this->model()->getError());
            }
            $this->success('修改成功', U('index'));
        }
    }
}