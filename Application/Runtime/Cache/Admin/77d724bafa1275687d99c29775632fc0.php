<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP 管理中心 - 商品分类 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('index');?>">角色列表</a></span>
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品分类 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
<div class="list-div" id="listDiv">
    <form action="" method="POST" enctype="multipart/form-data">
        <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" id="selectAll" />全选</th>
                    <th>顶级权限</th>
                    <th>子权限</th>
                </tr>
            </thead>
            <tbody>
                <!-- 顶级权限循环 -->
                 <!-- <input type="checkbox" class="top" name="rule[]" value="" >  -->
            <?php if(is_array($parent_rule)): $i = 0; $__LIST__ = $parent_rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td>
                        
                    </td>
                    <td>
                        <input type="checkbox" class="top" name="rule[]" value="<?php echo ($vo["id"]); ?>" <?php if(in_array(($vo["id"]), is_array($hasRules)?$hasRules:explode(',',$hasRules))): ?>checked<?php endif; ?> >
                        <?php echo ($vo["rule_name"]); ?>---->
                    <td>
                <!-- 子级权限循环 -->                       
                <?php if(is_array($rule)): $i = 0; $__LIST__ = $rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if(($v["parent_id"]) == $vo["id"]): ?><input type="checkbox" class="child" name="rule[]" value="<?php echo ($v["id"]); ?>" <?php if(in_array(($v["id"]), is_array($hasRules)?$hasRules:explode(',',$hasRules))): ?>checked<?php endif; ?> /><?php echo ($v["rule_name"]); endif; endforeach; endif; else: echo "" ;endif; ?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <!-- 表单提交 -->
                <tr>
                    <td colspan="3" style="text-align: center">
                        <!-- 隐藏的角色ID值 -->
                        <!-- <input type="hidden" name="role_id" value="<?php echo I('get.role_id');?>" /> -->
                        <input type="hidden" name="role_id" value="<?php echo ($_GET['role_id']); ?>" />
                        <button type="submit" class="btn btn-default" onclick="return confirm('确定变更权限?')">权限更改</button>
                        <button type="button" class="btn btn-default" onclick="location.href='/Admin/Role/index.html'" >返回列表</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

</div>

<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>
    <!-- block JS -->