<?php
//命名空间
namespace Admin\Model;
// ----管理模型
class AdminModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','username','password');
    //定义规则
    protected $_validate = array(
        array('username','require','用户名必须填写'),
        array('username','','用户名重复',1,'unique'),
        array('password','require','密码必须填写')
    );
    protected $_auto = array(
        //自动MD5加密
        array('password','md5',3,'function')
    );
    //添加成功后自动入库用户角色中间表
    public function _after_insert($data)
    {
        $admin_role = array(
            'admin_id' => $data['id'],
            'role_id'  => I('post.role_id')
        );
        D('AdminRole')->add($admin_role);
    }
    //用户显示
    public function listData()
    {
        $pagesize = 4;
        $count = $this->count();
        $page = new \Think\Page($count,$pagesize);
        $show = $page->show();
        $p = intval(I('get.p'));

        $data = $this->alias('a')->field("a.*,c.role_name")->join("left join jx_admin_role b on a.id = b.admin_id")->join("left join jx_role c on b.role_id = c.id")->page($p,$pagesize)->select();
        return array(
            'data' => $data,
            'show' => $show
        );
        // 原生联表语句参考:select * from jx_admin a left join jx_admin_role b on a.id = b.admin_id left join jx_role c on b.role_id = c.id
    }
    //用户删除 
    public function remove($admin_id)
    {
        //开启事务
        $this->startTrans();
        //1.删用户表信息
        $userStatus = $this->where("id = $admin_id")->delete();
        if(!$userStatus){
            $this->rollback();  //事务回滚就是指之前表的一切操作重新回到原点(重置)
            return false;
        }
        //2.删角色表
        $roleStatus = D('AdminRole')->where("admin_id = $admin_id")->delete();
        if(!$roleStatus){
            $this->rollback();  //事务回滚就是指之前表的一切操作重新回到原点(重置)
            return false;
        }
        $this->commit();
        return true;
    }
    //通过用户ID联表查找相应的用户记录以及相对应的角色信息
    //联表:jx_admin ----jx_admin_role
    public function findOne($admin_id)
    {
        return $this->alias('a')->field("a.*,b.role_id")->join("left join jx_admin_role b on a.id = b.admin_id")->where("a.id = $admin_id")->find();    //注意使用了表别名在条件中也需要使用表别名,例如这里的where()中要使用 a.id,不然因为不知道是哪个表的id而出错
    }
    //用户修改
    public function update($data)
    {
        $role_id = intval(I('post.role_id'));
        //修改用户信息
        $rs = $this->save($data);
        if($rs === false){
            return false;   //如果修改失败返回false结束修改
        }
        //修改用户角色中间表
        D('AdminRole')->where('admin_id = '.$data['id'])->save(array('role_id'=>$role_id));

    }
    //login登录判断
    /**
     * $username 用户名
     * $password 密码
     * $remember 是否记住账户密码
     */
    public function login($username,$password,$remember)
    {
        //查询用户是否存在
        $userinfo = $this->where("username = '$username' ")->find();
        if(!$userinfo){
            $this->error = '用户不存在';
            return false;
        }
        //查询密码是否正确
        if($userinfo['password'] != md5($password)){
            $this->error = '密码或用户名不正确';
            return false;
        }
        //判断是否记住登陆
        /**
         * 还有两点值得注意的是:
         * 1.登陆后最好将[登陆的时间],[ip地址]记录入数据库方便查看登陆信息,防止密码被破解
         * 2.cookie保存的用户信息应当设置密钥,防止被MD5反查
         */
        if($remember){
            //当选中记住登陆信息的时候设置cookie保存时间
            cookie('admin',$userinfo,360000);               //cookie保存时间为100小时,浏览器关闭后不销毁
        }else{
            //未选中记住登陆时cookie在会话结束后销毁
            cookie('admin',$userinfo);                      //cookie不设置保存时间,默认关闭浏览器/会话结束销毁
        }
        //全部匹配正确后允许登录,保存cookie信息(也可以使用session保存信息)
        return true;
    }
}