<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP - 修改用户
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('index');?>">用户列表</a></span>
    <span class="action-span1"><a href="">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 修改用户 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
    <form action="" method="post" name="theForm" enctype="multipart/form-data">
        <table width="100%" id="general-table">
            <!-- 用户名 -->
            <tr>
                <td class="label">用户名称:</td>
                <td>
                    <input type='text' name='username' maxlength="20" value="<?php echo ($info["username"]); ?>" size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <!-- 密码 -->
            <tr>
                <td class="label">用户密码:</td>
                <td>
                    <input type='text' name='password' maxlength="20" value='' size='27' /> <font color="red">md5密码不能反解显示空</font>
                </td>
            </tr>
            <!-- 所属管理员类别(角色) -->
            <tr>
                <td class="label">管理员类别(角色):</td>
                <td>
                    <select name="role_id">
                        <!-- <option value="0">请选择</option> -->
                        <?php if(is_array($role)): $i = 0; $__LIST__ = $role;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($info["role_id"]) == $vo["id"]): ?>selected<?php endif; ?> ><?php echo ($vo["role_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <!-- 增加一个隐藏的输入框，用以提交时传递本条数据的id值 -->
            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        </table>
        <div class="button-div">
            <input type="submit" value=" 确定 " />
            <input type="reset" value=" 重置 " />
        </div>
    </form>

</div>

<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>
    <!-- block JS -->