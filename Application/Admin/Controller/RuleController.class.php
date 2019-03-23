<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//权限管理控制器
class RuleController extends CommonController
{
    //权限添加
    public function add()
    {
        $model = D('Rule');
        if(IS_GET){
            //未提交表单时:
            $cate = $model->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
        }else{
            //提交表单后:
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $rs = $model->add($data);
            if(!$rs){
                $this->error('添加权限失败:'.$model->getError());
            }else{
                $this->success('添加权限成功');
            }
        }
    }
    
    //权限删除
    public function del()
    {
        $rule_id =  intval(I('get.rule_id'));
        if($rule_id<=0){
            $this->error('参数错误');
        }
        $model = D('Rule');
        $rs = $model->del($rule_id);
        if($rs === false){
            $this->error('删除失败:'.$model->getError());
        }else{
            $this->success('删除成功');
        }

    }

    //权限显示
    public function index()
    {
        $model = D('Rule');
        $data = $model->getCateTree();
        $this->assign('data',$data);
        $this->display();
    }

    //权限编辑
    public function edit()
    {
        $model = D('Rule');
        if(IS_GET){
            //未提交数据时显示需要编辑的记录
            $rule_id = intval(I('get.rule_id'));
            //传递权限记录信息
            $info = $model->findOneById($rule_id);
            $this->assign('info',$info);
            //传递权限分类信息
            $cate = $model->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
        }else{
            //提交数据时开始更新修改记录
            $data = $model->create();
            if(!$data){
                //创建数据失败时返回错误信息
                $this->error($model->getError());
            }
            $rs = $model->update($data);
            if($rs === false){
                $this->error('修改失败:'.$model->getError());
            }else{
                $this->success('修改成功',U('index'));
            }
        }
    }
}