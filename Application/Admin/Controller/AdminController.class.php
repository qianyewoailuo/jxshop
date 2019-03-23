<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// ------控制器
class AdminController extends CommonController
{
    // ----添加
    public function add()
    {
        if(IS_GET){
            //未提交前显示信息
            $role = D('Role')->select();
            $this->assign('role',$role); 
            $this->display();
        }else{
            //提交数据后开始入库
            $model = D('Admin');
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $rs = $model->add($data);
            if(!$rs){
                $this->error('添加用户失败'.$model->getError());
            }
            $this->success('添加用户成功');
        }
    }
    
    // ----删除
    public function del()
    {
        $model = D('Admin');
        $admin_id = intval(I('get.admin_id'));
        if($admin_id<=1){
            $this->error('参数不正确');
        }
        $rs = $model->remove($admin_id);
        if($rs === false){
            $this->error('删除失败'.$model->getError());
        }else{
            $this->success('删除成功',U('index'));
        }
    }

    // ----显示
    public function index()
    {
        $data = D('Admin')->listData();
        $this->assign('data',$data);
        $this->display();
    }

    // ----编辑
    public function edit()
    {
        $model = D('Admin');
        if(IS_GET){
            //未提交前显示信息
            $admin_id = intval(I('get.admin_id'));  //获取需要编辑的用户ID
            $info = $model->findOne($admin_id);     //获取用户记录信息
            $this->assign('info',$info); 
            $role = D('Role')->select();            //获取角色类别信息
            $this->assign('role',$role);
            $this->display();
        }else{
            //提交数据后开始修改
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            if($data['id'] <= 1){
                $this->error('参数错误');   //不允许修改超级管理员
            }
            $rs = $model->update($data);
            if($rs === false){
                $this->error('修改用户失败'.$model->getError());
            }else{
                $this->success('修改用户成功',U('index'));
            }
        }
    }
}