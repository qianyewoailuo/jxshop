<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//自定义分类控制器
class CategoryController extends CommonController{
    //实现分类的商品添加业务逻辑
    public function add()
    {
        if(IS_GET){
            //获取格式化的分类信息
            $model = D('Category');
            $cate = $model->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
        }else{
            //实例化模型对象
            $model = D('Category');
            //创建数据
            $data = $model->create();
            //创建数据失败时返回错误信息
            if(!$data){
                $this->error($model->getError());
            }
            //创建数据成功后开始写入数据
            $insertid = $model->add($data);
            //写入失败时返回错误信息
            if(!$insertid){
                $this->error('数据写入失败');     //参数1:提示信息|参数2:跳转地址|参数3:跳转时间|error:3s|success:1s
            }
            $this->success('数据写入成功');
        }
    }
    //实现分类的商品列表显示业务逻辑
    public function index()
    {
        $model = D('Category');
        $list = $model->getCateTree();
        $this->assign('list',$list);
        $this->display();
    }
    //实现分类商品删除的业务逻辑
    public function del()
    {
        //获取id
        $id = intval(I('get.id'));
        //判断id参数是否符合规定
        if($id<=0){
            $this->error('参数不正确');
        }
        $model = D('Category');
        //开始删除操作
        $rs = $model->del($id);
        // $rs = $model->delete($id);
        if($rs===false){
            $this->error('删除失败：此分类下含有其他子类');
        }
        $this->success('删除成功');
    }
    public function edit()
    {
        $model = D('Category');
        if(IS_GET){
            //显示需要编辑的分类商品信息
            $id = intval(I('get.id'));
            // $model = D('Category');
            $info = $model -> findOneById($id);     //自定义的根据id获取一条记录的方法
            $this->assign('info',$info);
            //获取所有分类信息
            $cate = $model->getCateTree();
            $this->assign('cate',$cate);
            $this->display();
        }else{
            //接受post的数据开始修改
            $data = $model->create();       //不传参的create()方法会自动创建post提交获取的数据
            // dump($data);exit();         //检查下创建的数据
            $rs = $model->update($data);   //使用自定义的update方法修改数据
            if($rs === false){      //不是使用取非判断，注意这些增删改判断是否成功都是使用是否全等false
                $this->error($model->getError());     //修改失败
            }else{
                $this->success('修改成功',U('index'));   //修改成功
            }
        }
    }
}