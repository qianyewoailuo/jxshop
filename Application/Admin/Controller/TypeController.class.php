<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
// ------控制器
class TypeController extends CommonController
{
    // ----添加
    public function add()
    {
        if(IS_GET){
            //未提交前显示信息
            $this->display();
        }else{
            //提交数据后开始----
            $model = D('Type');
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $rs = $model->add($data);
            if(!$rs){
                $this->error('添加类型失败:'.$model->getError());
            }
            $this->success('添加类型成功');
        }
    }
    
    // ----删除
    public function del()
    {
        $type_id = intval(I('get.type_id'));
        if($type_id <= 0){
            $this->error('参数错误');
        }
        $rs = D('Type')->remove($type_id);
        if($rs === false){
            $this->error('删除类型失败');
        }
        $this->success('删除成功');
    }

    // ----显示
    public function index()
    {
        $data = D('Type')->listData();
        $this->assign('data',$data);
        $this->display();
    }

    // ----编辑
    public function edit()
    {
        $model = D('Type');
        if(IS_GET){
            //未提交前显示信息
            $type_id = intval(I('get.type_id'));
            if($type_id <= 0){
                $this->error('参数错误');
            }
            $info = $model->findOneById($type_id);
            $this->assign('info',$info);
            $this->display();
        }else{
            //提交数据后开始更新数据
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $rs = $model->save($data);
            if($rs === false){
                $this->error('修改类型失败');
            }
            $this->success('修改成功',U('index'));
        }
    }
}