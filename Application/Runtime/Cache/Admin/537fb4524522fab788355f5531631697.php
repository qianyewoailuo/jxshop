<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP - 权限列表 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('add');?>">添加权限</a></span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 权限列表 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
    <form method="post" action="" name="listForm">
        <div class="list-div" id="listDiv">
            <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
                <tr>
                    <th>权限名称</th>
                    <th>模型名称</th>
                    <th>控制器名称</th>
                    <th>方法名称</th>
                    <th>是否导航显示</th>
                    <th>操作</th>
                </tr>
                <!-- 开始循环 -->
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" class="0">
                    <td align="left" class="first-cell" >
                        <img src="/Public/Admin/Images/menu_minus.gif" width="9" height="9" border="0" style="margin-left:0em" />
                        <!--  -->
                        <span><a href="#" <?php if(($vo["parent_id"]) == "0"): ?>style="color:red"<?php endif; ?> >|<?php echo str_repeat("---",$vo['lev']); echo ($vo["rule_name"]); ?></a></span>
                    </td>
                    <td><?php echo ($vo["module_name"]); ?></td>
                    <td><?php echo ($vo["controller_name"]); ?></td>
                    <td><?php echo ($vo["action_name"]); ?></td>
                    <td width="15%"><img src="/Public/Admin/Images/<?php echo $vo['is_show']==0?no:yes ?>.gif"  /></td>
                    <td width="30%" align="center">
                    <a href="<?php echo U('edit','rule_id='.$vo['id']);?>">编辑</a> |
                    <!-- 注意使用dom函数confirm()时要配合return使用才能实现取消跳转 -->
                    <a href="<?php echo U('del','rule_id='.$vo['id']);?>" title="移除" onclick="return confirm('确定删除吗？')">移除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>  
            </table>
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