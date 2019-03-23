<?php
//命名空间
namespace Home\Model;
// ----管理模型
class UserModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','username','password','salt','tel','status','email','active_code');
    //注册验证
    public function regist($username,$password,$tel)
    {
        $info = $this->where("username = '$username' ")->find();
        //检查用户是否存在
        if($info){
            //当前用户已存在
            $this->error = '用户已存在';
            return false;
            // exit();
        }
        //检查手机号是否已注册
        $telinfo = $this->where("tel = '$tel' ")->find();
        if($telinfo){
            //当前手机号已注册
            $this->error = '手机号已注册';
            return false;
        } 
        //生成盐
        $salt = rand(100000,999999);
        //生成盐加密双层MD5密码
        $db_password = md5(md5($password).$salt);
        $data = array(
            'username' => $username,
            'password' => $db_password,
            'salt'     => $salt,
            'tel'      => $tel,
            'status'   => '1'
        );
        return $this->add($data);
    }

    //邮箱注册验证
    public function registbyemail($username,$password,$email)
    {
        $info = $this->where("username = '$username' ")->find();
        //检查用户是否存在
        if($info){
            //当前用户已存在
            $this->error = '用户已存在';
            return false;
        }
        //检查邮箱是否已注册
        $info = $this->where("email = '$email' ")->find();
        if($info){
            //当前手机号已注册
            $this->error = '此邮箱已注册';
            return false;
        }
        //生成盐
        $salt = rand(100000,999999);
        //生成盐加密双层MD5密码
        $db_password = md5(md5($password).$salt);
        $data = array(
            'username' => $username,
            'password' => $db_password,
            'salt'     => $salt,
            'email'    => $email,
            'status'   => '0',
            'active_code' => uniqid(),  //生成激活码
        );
        $user_id = $this->add($data);
        $data['user_id'] = $user_id;
        return $data;
    }

    //登录验证
    public function login($username,$password)
    {
        $info = $this->where("username = '$username' ")->find();
        //用户是否存在
        if(!$info){
            $this->error = '用户不存在';
            return false;
        }
        //用户是否已激活
        if($info['status'] == 0){
            $this->error = '用户未激活呢';
            return false;
        }
        //密码是否正确
        $pwd = md5(md5($password).$info['salt']);
        if($pwd != $info['password']){
            $this->error = '用户名或密码不正确';
            return false;
        }        
        //保存用户登录状态->session设置需要在配置中应用
        session('user',$info);
        session('user_id',$info['id']);
        //购物车cookie数据转移
        D('Cart')->cookie2db();
        return true;
    }
    //通过请求本地接口实现登录验证
    public function loginByAPI($username,$password)
    {
        $param = array(
            'username'  =>  $username,
            'password'  =>  $password,
            'c'         =>  'user',
            'a'         =>  'login',
        );
        //通过get_data()函数请求接口
        $rs = get_data($param);
        if($rs['status'] == 0){
            $this->error = $rs['msg'];
            return false;
        }
        //保存用户登录状态
        $info = $rs['data'];
        //session设置需要在配置中应用
        session('user',$info);
        session('user_id',$info['id']);
        //购物车cookie数据转移
        D('Cart')->cookie2db();
        return true;        
    }


    //qq登录用户信息入库以及session存储
    public function qqlogin($user,$openID)
    {
        //判断当前登录的qq用户是否已存在
        $data = $this->where("openID = '$openID'")->find();
        if(!$data){
            //当前qq登录用户不存在,数据更新入库

            //生成盐
            $salt = rand(100000,999999);
            //生成qq用户盐加密双层MD5密码(默认密码为12345)
            $db_password = md5(md5('12345').$salt);
            $data = array(
                //避免用户名重复,使用随机数+qq_user昵称拼接
                'username'  =>  'qquser_'.rand(1000,9999).'_'.$user['nickname'],
                'password'  =>  $db_password,
                'salt'      =>  $salt,
                'status'    =>  1,
                'openID'    =>  $openID
            );
            $data['id'] = $this->add($data);
        }
        //于此qq用户无论是否已存在,用户ID已获取可以开始设置session
        session('user',$data);
        session('user_id',$data['id']);
        //购物车cookie数据转移
        D('Cart')->cookie2db();
        return true;
    }
}