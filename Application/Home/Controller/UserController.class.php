<?php
//命名空间
namespace Home\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//用户控制器
class UserController extends CommonController
{
    //测试authcode函数
    public function autocodetest()
    {
        $code = authcode('admin','ENCODE','luo');     //加密
        dump($code);
        echo '<br>';
        $decode = authcode($code,'DECODE','luo');     //解密
        dump($decode);
    }

    //测试http_curl函数请求外部接口
    public function curltest()
    {
        // api接口中需要用户提供username password参数,本来还有appID参数,不过为了方便测试已经写进请求里了
        $rs = http_curl('http://api.com/home/user/login',array('username'=>'luo','password'=>'12345'));
        // 请求结果为jxshop数据库中user表的用户数据
        dump($rs);
    }

    //测试短信注册
    public function SMStest()
    {
        $to = "13622434582";            //发送号码
        $datas = array('9909','5');     //替换内容数组(短信验证码以及生效时间)
        $tempId = '1';                  //短信模板ID
        $rs = sendTemplateSMS($to,$datas,$tempId);    //发送请求
        if($rs){
            echo '短信发送成功';
        }else{
            echo '短信发送失败';
        }
    }

    //实现短信验证码发送
    public function sendcode()
    {
        //获取手机号
        $tel = I('post.tel');
        if(!$tel){
            $this->ajaxReturn(array('status'=>0,'msg'=>'手机号错误'));
        }
        //生成4位验证码
        $telcode = rand(1000,9999);
        //发送短信验证码
        $rs = sendTemplateSMS($tel,array($telcode,'5'),"1");
        if(!$rs){
            //验证码发送失败时返回错误信息
            $this->ajaxReturn(array('status'=>0,'msg'=>'发送验证码失败'));
        }else{
            //验证码发送成功,session记录并设定相应的过期时间
            $data = array(
                'code' => $telcode,        //验证码
                'time' => time(),       //发送时间
                'limit'=> 300,          //过期时间
            );
            //session记录
            session('telcode',$data);
            $this->ajaxReturn(array('status'=>1,'msg'=>'手机验证码已发送'));
        }
    }

    //测试邮件发送
    public function emailtest()
    {
        $to = '821527966@qq.com';
        $subject = '主题-emailtest';
        $body = '正文-这是一封emailtest测试相关的邮件O(∩_∩)O哈哈~';
        $rs = sendemail($to,$subject,$body);
        if($rs){
            echo '邮件已发送到:'.$to;
        }else{
            echo '邮件发送失败';
        }
    }

    //邮箱注册
    public function registbyemail()
    {
        if(IS_GET){
            //显示邮箱注册页面
            $this->display();
        }else{
            //开始注册
            $username = I('post.username');
            $password = I('post.password');
            $is_password = I('post.is_password');
            $email = I('post.email');
            // dump($checkcode);
            if($username == '' || $password == ''){
                $this->ajaxReturn(array('status'=>0,'msg'=>'用户名或密码不能为空'));
            }
            if($password != $is_password){
                $this->ajaxReturn(array('status'=>0,'msg'=>'密码不一致'));
            }         
            //模型验证入库
            $model = D('User');
            $rs = $model->registbyemail($username,$password,$email);
            if(!$rs){
                //验证失败
                $this->ajaxReturn(array('status'=>0,'msg'=>$model->getError()));
            }else{
                $parm = array('user_id'=>$rs['user_id'],'active_code'=>$rs['active_code']);
                $demo_link = 'http://jxshop.com'.U('active',$parm);  //这是跳转链接范例.当然也可以直接拼接
                //验证成功,发送激活邮件
                $link = '您好'.$rs['username'].'!点击链接以下链接即可激活商城账号: http://jxshop.com'.U('active').'?user_id='.$rs['user_id'].'&active_code='.$rs['active_code'];
                sendemail($email,'TP商城用户激活邮件',$link);
                //返回ajax信息
                $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
            }
        }
    }

    //邮箱激活
    public function active()
    {
        $user_id = I('get.user_id');
        $active_code = I('get.active_code');
        $model = D('User');
        $user_info = $model->where("id= '$user_id'")->find();
        if(!$user_info){
            $this->error('激活失败,用户不存在');
        }
        if($active_code != $user_info['active_code']){
            $this->error('非法操作:激活码错误');
        }
        if($user_info['status'] == 1){
            $this->error('已激活请直接登录',U('User/login'));
        }
        $model->where('id='.$user_id)->setField('status','1');
        $this->success('激活成功',U('User/login'));
    }

    //实现用户注册的方法
    public function regist()
    {
        if(IS_GET){
            //显示注册页面
            $this->display();
        }else{
            //开始注册
            // dump(I('post.'));exit();
            //获取数据
            $username = I('post.username');
            $password = I('post.password');
            $checkcode = I('post.checkcode');
            $is_password = I('post.is_password');
            $tel = I('post.tel');
            $telcode = I('post.telcode');
            // dump($checkcode);
            if($username == '' || $password == ''){
                $this->ajaxReturn(array('status'=>0,'msg'=>'用户名或密码不能为空'));
            }
            if($password != $is_password){
                $this->ajaxReturn(array('status'=>0,'msg'=>'密码不一致'));
            }         
            //普通验证码验证
            $verify = new \Think\Verify();
            $rs = $verify->check($checkcode);
            // dump($rs);exit();
            if(!$rs){
                $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));
            }
            //手机验证码验证
            if(!$telcode){
                $this->ajaxReturn(array('status'=>0,'msg'=>'请输入手机验证码'));
            }
            $checkTelcode = session('telcode');
            if(!$checkTelcode){
                $this->ajaxReturn(array('status'=>0,'msg'=>'没有获取手机验证码或短信验证码发送失败'));
            }
            if(time() > $checkTelcode['time']+$checkTelcode['limit']){
                $this->ajaxReturn(array('status'=>0,'msg'=>'手机验证码过期'));
            }
            if($telcode != $checkTelcode['code']){
                $this->ajaxReturn(array('status'=>0,'msg'=>'手机验证码错误'));
            }
            //模型验证入库
            $model = D('User');
            $rs = $model->regist($username,$password,$tel);
            if(!$rs){
                //验证失败
                $this->ajaxReturn(array('status'=>0,'msg'=>$model->getError()));
            }else{
                //验证成功
                $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
            }
        }
    }

    //用户登录
    public function login()
    {
        if(IS_GET){
            //显示登录
            $this->display();
        }else{
            //开始登录
            $username = I('post.username');
            $password = I('post.password');
            $checkcode = I('post.checkcode');
            $remember = I('post.remember');
            if($username == '' || $password == ''){
                $this->ajaxReturn(array('status'=>0,'msg'=>'用户名或密码不能为空'));
            }
            //验证码验证
            $verify = new \Think\Verify();
            $rs = $verify->check($checkcode);
            // dump($rs);exit();
            if(!$rs){
                $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));
            }
            //模型验证入库
            $model = D('User');
            //数据库验证:有通过接口与非接口的两种验证方法,去除注释可选
            //通过非接口验证:
            // $rs = $model->login($username,$password);
            //通过调用本地API接口验证
            $rs = $model->loginByAPI($username,$password);
            if(!$rs){
                //验证失败
                $this->ajaxReturn(array('status'=>0,'msg'=>$model->getError()));
            }else{
                //验证成功
                $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
            }            
        }
    }

    //退出登录
    public function logout()
    {
        session('user',null);
        session('user_id',null);
        //重定向返回首页
        $this->redirect('/');                   //这里的'/'表示配置里自定义设置的首页
    }

    //qq登录回调函数callback
    public function callback()
    {
        require_once("qq/API/qqConnectAPI.php");
        $qc = new \QC();
        /*  测试数据↓↓↓
            //输出access_token
            echo 'access_token='.$qc->qq_callback();
            //输出换行
            echo '<br>';
            //输出用户对应的openID,区分特定用户的唯一标识,非常重要.
            echo 'openID='.$qc->get_openid();
        */

        //配置中对应勾选的获取信息方法
        //获取access_token
        $access_token = $qc->qq_callback();
        //获取openID
        $openID = $qc->get_openid();
        //重新实例化对应参数的qc对象,目的是为了确保获取用户信息正常实现,例如网络不佳,用户修改资料未更新等qq登录时会报错
        $qc = new \QC($access_token,$openID);
        $user = $qc->get_user_info();

        //项目逻辑实现登录入库
        $model = D('User');
        $model->qqlogin($user,$openID);
        $this->success('登录成功',U('Index/index'));
    }

    //qq登录index函数-参考目前demo中oauth下的index.php
    public function oauth()
    {
        require_once("qq/API/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();
    }

    //实现创建验证码的方法
    public function code()
    {
        $config = array(
            'length' => 3,          //验证码长度
            'codeSet' => '0123456789',
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }
}