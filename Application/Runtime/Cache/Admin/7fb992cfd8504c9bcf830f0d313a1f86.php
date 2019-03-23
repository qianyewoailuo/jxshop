<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP - 属性列表 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('add');?>">添加属性</a></span>
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 管理属性列表 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
<!-- 商品筛选 -->
<!-- 商品列表 -->
<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>编号</th>
            <th>属性名称</th>
            <th>类型名称</th>
            <th>属性类型</th>
            <th>属性录入方式</th>
            <th>默认值</th>
            <th>操作</th>
        </tr>
    <?php if(is_array($data["data"])): $i = 0; $__LIST__ = $data["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><?php echo ($vo["id"]); ?></td>
            <td align="center" class="first-cell"><span><?php echo ($vo["attr_name"]); ?></span></td>
        <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if(($v["id"]) == $vo["type_id"]): ?><td align="center" class="first-cell"><span><?php echo ($v["type_name"]); ?></span></td><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <td align="center" class="first-cell"><span><?php if(($vo["attr_type"]) == "1"): ?>唯一<?php else: ?>单选<?php endif; ?></span></td>
            <td align="center" class="first-cell"><span><?php if(($vo["attr_input_type"]) == "1"): ?>手动输入<?php else: ?>列表选择<?php endif; ?></span></td>
            <td align="center" class="first-cell"><span><?php echo ($vo["attr_value"]); ?></span></td>
             
            <td align="center">
                <a href="<?php echo U('edit','attr_id='.$vo['id']);?>" title="编辑"><img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('del','attr_id='.$vo['id']);?>" title="删除" onclick="return confirm('确定删除吗？')"><img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a>
            </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>

    <!-- 分页开始 -->
    <table id="page-table" cellspacing="0">
        <tr>
            <td width="80%">&nbsp;</td>
            <td align="center" nowrap="true">
                <?php echo ($data["show"]); ?>
            </td>
        </tr>
    </table>
<!-- 分页结束 -->
</div>

</div>

<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>
    <!-- block JS -->