<?php
//命名空间
namespace Admin\Controller;              
//引用基类
use Think\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//自定义控制器
class CommonController extends Controller
{
    //404 NOT FOUND 提醒
    public function _empty()
    {
        echo '404 NotFound：你输入的是一个不存在的方法',ACTION_NAME;
    }

    /**
     * RBAC认证实现
     */

    //设置是否需要权限认证标识属性,默认true需要权限认证
    public $is_check_rule = true;   
    //设置用以保存用户信息的属性,其中包括基本信息,角色ID,权限信息
    public $user = array();
    //是否登录判断防止翻墙
    public function __construct()
    {
        //实现父类构造函数
        parent::__construct();
        //取cookie判断是否已登录
        $admin = cookie('admin');   //这里的cookie是在admin模型中判断用户登录时设置的
        if(!$admin){
            $this->error('没有登录',U('Login/login'));
        }
        /**
         * S()是缓存管理方法
         * 读取缓存:S(缓存的名称)
         * 写入缓存:S(缓存名称,缓存信息)
         * 删除缓存:S(缓存名称,null)
         */
        //读取当前用户对应的文件信息
        $this->user = S('user_'.$admin['id']);  //读取名称为user_ID的文件
        //判断是否获取得到user_ID用户的缓存文件,否则执行保存用户信息与权限
        if(!$this->user){
            // echo '第一次获取不到用户缓存信息会显示这断话呢...';
            #   1.保存信息(基本信息,角色ID,权限信息)
            //保存当前用户信息
            $this->user = $admin;
            //根据用户ID获取角色ID
            $role_info = D('AdminRole')->where('admin_id='.$admin['id'])->find();
            //保存角色ID信息
            $this->user['role_id'] = $role_info['role_id'];
            //根据角色ID获取权限信息:$rule_list
            $ruleModel = D('Rule');
            if($role_info['role_id'] == 1){
                //超级管理员
                $this->is_check_rule = false;            //不用验证权限信息
                $rule_list  = $ruleModel->select();      //获取所有权限
            }else{
                //普通管理员
                //1.根据角色ID获取对应的权限ID
                //2.根据权限ID获取对应的权限信息
                $rules = D('RoleRule')->getRules($role_info['role_id']);
                foreach($rules as $key => $value){
                    $rules_ids[] = $value['rule_id'];       //获取一位数组的rule_id值
                }
                $rules_ids = implode(',',$rules_ids);
                $rule_list = $ruleModel->where(" id in ($rules_ids) ")->select();   //获取对应权限
            }

            #  2.分配权限
            //遍历二维数组的权限信息将其转换为一维数组并保存到user属性中
            foreach($rule_list as $key => $value){
                //模型+控制器+方法拼接后保存为一维数组
                $this->user['rules'][] = strtolower($value['module_name'].'/'.$value['controller_name'].'/'.$value['action_name']);
                //获取需要导航显示的权限信息保存到user属性中
                if($value['is_show'] == 1){
                    $this->user['menus'][] = $value;
                }
            }
            //将保存的user信息写入user_ID的用户缓存中
            S('user_'.$admin['id'],$this->user);
        }
        // dump($this->is_check_rule);     
        if($this->user['role_id'] == 1){
            //is_check_rule并不记录在缓存中,所以要另外判断
            $this->is_check_rule = false;       //超管is_check_rule设为false不需认证
        }
        #   3.比对检查非超级管理员用户的访问权限
        if($this->is_check_rule)
        {
            //增加默认的首页登录权限
            $this->user['rules'][] = 'admin/index/index';
            $this->user['rules'][] = 'admin/index/top';
            $this->user['rules'][] = 'admin/index/menu';
            $this->user['rules'][] = 'admin/index/main';
            $action = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
            if(!in_array($action,$this->user['rules']))
            {
                //不在权数组中无权限
                if(IS_AJAX){
                    //因为在网站中会经常使用ajax请求方式不能通过error()的方法返回错误信息
                    $this->ajaxReturn(array('status'=>0,'msg'=>'没有权限'));
                }else{
                    $this->error('没有权限');   //没权限报错返回首页
                }
            }
        }
    }
}