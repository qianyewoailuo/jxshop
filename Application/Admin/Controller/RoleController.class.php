<?php
//命名空间
namespace Admin\Controller;
//字符编码
// header('Content-type:text/html;charset=utf-8');
//角色管理控制器
class RoleController extends CommonController{
    //角色管理添加
    public function add()
    {
        if(IS_GET){
            //未提交数据时
            $this->display();
        }else{
            //提交数据时
            // dump(I('post.role_name'));
            $model = D('Role');
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            $rs = $model->add($data);
            if(!$rs){
                $this->error('添加失败'.$model->getError());
            }else{
                $this->success('添加成功');
            }
        }
    }
    
    //角色删除
    public function del()
    {
        $model = D('Role');
        $role_id = intval(I('get.role_id'));
        if($role_id<=1){
            $this->error('参数错误');
        }
        $rs = $model->remove($role_id);
        if($rs === false){
            $this->error('删除失败'.$model->getError());
        }else{
            $this->success('删除成功');
        }
    }
    //角色编辑
    public function edit()
    {
        $model = D('Role');
        if(IS_GET){
            //未提交数据时显示当前记录信息
            $role_id = intval(I('get.role_id'));
            $info = $model->findOneById($role_id);
            $this->assign('info',$info);
            $this->display();
        }else{
            //提交表单时开始更新数据
            $data = $model->create();
            if(!$data){
                $this->error($model->getError());
            }
            //注意还应该判断一下获取的id是否是>=1,不然会误伤超级管理员角色
            if($data['id']<=1){
                $this->error('参数错误');
            }
            $rs = $model->save($data);
            if($rs === false){
                $this->error('修改失败'.$model->getError());
            }else{
                $this->success('修改成功',U('index'));
            }
        }
    }

    //角色列表显示
    public function index()
    {
        $data = D('Role')->listData();
        $this->assign('data',$data);
        $this->display();
    }

    //角色分配权限
    public function disfetch()
    {
        if(IS_GET){
        //表单未提交显示可分配权限
            //1.当前角色已拥有的权限信息
            $role_id = intval(I('get.role_id'));
            if($role_id<=1){
                $this->error('参数错误');
            }
            $getRules = D('RoleRule')->getRules($role_id);
            foreach($getRules as $key => $value){
                $hasRules[] = $value['rule_id'];
            }
            $this->assign('hasRules',$hasRules);
            //2.权限表数据信息
            $rule = D('Rule')->getCateTree();
            $this->assign('rule',$rule);
            //3.顶级权限数据信息
            foreach($rule as $key=>$value){
                if($value['parent_id'] == 0){
                    $parent_rule[] = $value;
                }
            }
            $this->assign('parent_rule',$parent_rule);
            //显示模板
            $this->display();
        }else{
            //表单提交分配权限给予角色
            $role_id = intval(I('post.role_id'));
            if($role_id<=1){
                $this->error('参数错误');
            }
            $rules = I('post.rule');
            $rs = D('RoleRule')->disfetch($role_id,$rules);
            if(!$rs){
                $this->error('更改权限失败:'.D('RoleRule')->getError());
            }else{
                //当更改权限成功时需要将当前角色的所有相关管理员的缓存信息删除
                $user_info = D('AdminRole')->where("role_id = $role_id")->select();
                foreach ($user_info as $key => $value) {
                    S('user_'.$value['admin_id'],null);         //删除更改角色相对应的用户缓存信息
                }
                $this->success('更改权限成功');
            }
        }
    }

    //更新超级管理员用户的缓存文件
    public function superAdminUpdate()
    {
        //获取所有的超级管理员的用户信息
        $superUser = D('AdminRole')->where('role_id=1')->select();
        foreach ($superUser as $key => $value) {
            S('user_'.$value['admin_id'],null);
        }
        //测试
        echo 'OK!';
    }

}