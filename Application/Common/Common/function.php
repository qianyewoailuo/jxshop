<?php
/**
 * 用户自定义函数库(Tp会自动加载这里的函数供使用)
 * myU ->  生成自定义链接地址函数
 * sendTemplateSMS  ->  SMS发送(短信注册)函数
 * sendmail ->  Email发送函数
 */

//1.生成商品列表中的链接地址
//ps:这种方法可能还适用于Category中的页码跳转功能?
function myU($name,$value)
{
    $attr = I('get.attr');

    if($name == 'sort'){
        //将目前的排序字段保存到$sort变量中
        $sort   = $value;
        $price  = I('get.price');
        $attr   = I('get.attr');
    }elseif($name == 'price'){
        //将目前的价格信息保存到变量中
        $price  = $value;
        $sort  = I('get.sort');
        $attr   = I('get.attr');
    }elseif($name == 'attr'){
        //将目前的属性信息保存到变量中
        if(!$attr){
            //获取的attr值为空时
            $attr = $value;
            $sort  = I('get.sort');
            $price  = I('get.price');
        }else{
            //获取的attr值有时
            $attr = explode(',',$attr);     //分割成数组
            $attr[] = $value;               //新增元素到数组
            $attr = array_unique($attr);    //二元数组去重
            $attr = implode(',',$attr);     //链接回字符串
            $price  = I('get.price');
            $sort  = I('get.sort');
        }
    }elseif($name = 'reset'){
        //重置筛选
        $attr   = '';
        $sort   = '';
        $price  = '';
    }
    //生成并返回带查询参数的url地址
    return U('Category/index').'?id='.I('get.id').'&sort='.$sort.'&price='.$price.'&attr='.$attr;
}

//2.SMS发送(短信注册)函数
/**
  * sendTemplateSMS()短信注册的函数
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */ 
function sendTemplateSMS($to,$datas,$tempId)
{
    include_once("../SMS/CCPRestSmsSDK.php");

    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    $accountSid= '8aaf070867c517d20167e56b98111338';
    
    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    $accountToken= '3d6ace5965ce419191b25fbd719ead30';
    
    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    $appId='8aaf070867c517d20167e574935d1344';
    
    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    $serverIP='sandboxapp.cloopen.com';
    
    
    //请求端口，生产环境和沙盒环境一致
    $serverPort='8883';
    
    //REST版本号，在官网文档REST介绍中获得。
    $softVersion='2013-12-26';    

    // 初始化REST SDK
    // global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
    $rest = new \REST($serverIP,$serverPort,$softVersion);      //注意TP中引用类需要加上'\'
    $rest->setAccount($accountSid,$accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    // echo "Sending TemplateSMS to $to <br/>";                 //非测试,项目中使用不需要输出
    $result = $rest->sendTemplateSMS($to,$datas,$tempId);
    if($result == NULL ) {
        //发送失败,返回false
        return false;
    }
    if($result->statusCode!=0) {
        // echo "error code :" . $result->statusCode . "<br>";
        // echo "error msg :" . $result->statusMsg . "<br>";
        return false;
        //TODO 添加错误处理逻辑
    }else{
        //短信发送成功
        return true;
    }
    // else{
    //     echo "Sendind TemplateSMS success!<br/>";
    //     // 获取返回信息
    //     $smsmessage = $result->TemplateSMS;
    //     echo "dateCreated:".$smsmessage->dateCreated."<br/>";
    //     echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
    //     //TODO 添加成功处理逻辑
    // }
}

//3.邮件发送函数
/**
 * sendemail()函数
 * 实现邮件发送
 * $attachment 是否添加附件,默认为false不需要添加
 */
function sendemail($to,$subject,$body,$attachment=false)
{
    //引入类文件
    require '../PHPMailer/class.phpmailer.php';
    $mail = new PHPMailer();
	/*服务器相关信息*/
    $mail->IsSMTP();  					//使用smtp方式发送邮件
    $mail->SMTPAuth = true;				//是否使用用户信息认证        
    $mail->Host = 'smtp.126.com';		//设置stmp邮件服务器地址
    $mail->Username = 'qianyewoailuo';	//用户名
    $mail->Password = 'qaz5201314';		//第三方授权密码
	/*内容信息*/
    $mail->IsHTML(true);					//发送的内容为html文本
    $mail->CharSet = "UTF-8";
    $mail->From = 'qianyewoailuo@126.com';	//发件箱
    $mail->FromName = "TP商城";				//发件人昵称
    $mail->Subject = $subject;             //主题
    $mail->MsgHTML($body);		           //邮件正文
	/*发送相关*/
    $mail->AddAddress($to);			       //收件人地址
    if ($attachment) {
        //如果有附件则追加附件
        $mail->AddAttachment($attachment); 	//追加附件
    }
    return $mail->Send();					//开始发送
}

//4.发送请求的curl函数
//想要实现请求的发送就必须要指定相关的参数信息,其中包括请求的URL地址 请求说携带的参数信息以及具体的请求方式(get|post)
/**
  * http_curl()函数
  * 作用:发送请求
  * @param url 请求的URL的地址
  * @param data 发送请求的相关参数,数组类型
  * @param method 请求的方式:get | post
  */ 
function http_curl($url,$data=array(),$method='get')
{
    if(!function_exists('curl_init')){
        //表示目前不存在curl_init函数,说明目前curl扩展函数尚未开启
        echo 'curl扩展未开启';
        exit();
    }
    //开始组装具体函数实现请求方式
    //1.打开会话
    $ch = curl_init();
    //2.设置参数信息(指定:请求地址,参数,请求方式)
    //2.1.0.设置加密密文,使用base64加密避免出现特殊字符影响data参数
    //在实际应用时autocode()加密的appID是接口方提供的,这里为了测试直接写进请求里
    $data['appID'] = base64_encode(authcode('luoyk','ENCODE'));
    //2.1.请求方式
    if($method=='post'){
        //当用户指定为post请求时更改输出设定
        curl_setopt($ch,CURLOPT_POST,TRUE);
        //设置请求的参数
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }else{
        //当前请求方式为get,可以直接将参数增加到url地址后即可
        $url .= '?&'.http_build_query($data);
    }
    //2.2.设置请求的地址
    curl_setopt($ch,CURLOPT_URL,$url);
    //2.3.设置结果不进行输出
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //3.执行具体请求
    $rs = curl_exec($ch);
    //4.关闭会话
    curl_close($ch);
    //将请求得到的结果转换为数组格式并且返回
    //p.s.当然也可以新增一个参数判断返回的是json格式还是数组格式,这里直接返回数组格式
    return json_decode($rs,true);
}

//5.请求接口函数get_data();
/** 
 * get_data()函数
 * 作用:调用通过http_curl()函数实现的接口进行数据操作
 * @param data 发送请求的相关参数信息,数组类型;可选参数有c:控制器,a:方法,url:外部请求地址
 * @param method 请求的方式:get | post
*/ 

function get_data($data=array(),$method='get')
{
    //首先根据具体情况生成URL地址
    //规定用户自定义具体接口可以在$data数组中增加两个参数:1.c=>控制器名称;2.a=方法名称
    if(!$data['c']){
        //未指定控制器时使用当前项目同名控制器
        $data['c'] = CONTROLLER_NAME;
    }
    if(!$data['a']){
        //未指定方法时使用当前项目同名方法
        $data['a'] = CONTROLLER_NAME;
    }
    if($data['url']){
        //如果当前请求接口非本地api接口,url请求地址则使用其外部规则
        $url = $data['url'];
    }else{
        //本地自定义api接口请求地址规则
        $url = 'http://api.com/home/'.$data['c'].'/'.$data['a'];
    }
    //请求接口前删除不需要的指定控制器,方法或url地址的参数;
    unset($data['c']);
    unset($data['a']);
    unset($data['url']);
    //开始请求接口
    $rs = http_curl($url,$data,$method);
    return $rs;
}

//自定义的加密或解密函数
/**
 * authcode()函数
 * 作用:加密以及解密,用以限制访问本地API
 * @param string 加密的字符串
 * @param operation 'DECODE'解密 | 'ENCODE'加密
 * @param key 密钥
 * @param expiry 密文有效时间
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
{  
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙  
    $ckey_length = 4;  
      
    // 密匙  
    $key = md5($key ? $key : 'abc');  
      
    // 密匙a会参与加解密  
    $keya = md5(substr($key, 0, 16));  
    // 密匙b会用来做数据完整性验证  
    $keyb = md5(substr($key, 16, 16));  
    // 密匙c用于变化生成的密文  
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';  
    // 参与运算的密匙  
    $cryptkey = $keya.md5($keya.$keyc);  
    $key_length = strlen($cryptkey);  
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;  
    $string_length = strlen($string);  
    $result = '';  
    $box = range(0, 255);  
    $rndkey = array();  
    // 产生密匙簿  
    for($i = 0; $i <= 255; $i++) 
    {  
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);  
    }  
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
    for($j = $i = 0; $i < 256; $i++) 
    {  
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;  
        $tmp = $box[$i];  
        $box[$i] = $box[$j];  
        $box[$j] = $tmp;  
    }  
    // 核心加解密部分  
    for($a = $j = $i = 0; $i < $string_length; $i++) 
    {  
        $a = ($a + 1) % 256;  
        $j = ($j + $box[$a]) % 256;  
        $tmp = $box[$a];  
        $box[$a] = $box[$j];  
        $box[$j] = $tmp;  
        // 从密匙簿得出密匙进行异或，再转成字符  
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));  
    }  
    if($operation == 'DECODE') 
    {  
        // substr($result, 0, 10) == 0 验证数据有效性  
        // substr($result, 0, 10) - time() > 0 验证数据有效性  
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
        // 验证数据有效性，请看未加密明文的格式  
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) 
        {  
            return substr($result, 26);  
        } else {  
            return '';  
        }  
    } else {  
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  
        return $keyc.str_replace('=', '', base64_encode($result));  
    }  
}

?>