<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP 管理中心 - 库存设置 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('index');?>">商品列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 库存设置 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
    <div class="list-div" id="listDiv">
    <form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
            <tr>
                <?php if(is_array($attr)): $i = 0; $__LIST__ = $attr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><th><?php echo ($vo["0"]["attr_name"]); ?></th><?php endforeach; endif; else: echo "" ;endif; ?>
                <th>数量</th>
                <th>操作</th>
            </tr>
        <?php if(is_array($info)): $keys = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($keys % 2 );++$keys;?><tr align="center" class="0">
               <?php if(is_array($attr)): $i = 0; $__LIST__ = $attr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td align="left" class="first-cell" >
                    <select name="attr[<?php echo ($vo["0"]["attr_id"]); ?>][]">
                        <?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><!-- goods_attr_ids值 -->
                        <option value="<?php echo ($v["id"]); ?>" <?php if(in_array(($v["id"]), is_array($v2["goods_attr_ids"])?$v2["goods_attr_ids"]:explode(',',$v2["goods_attr_ids"]))): ?>selected<?php endif; ?> ><?php echo ($v["attr_values"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td><?php endforeach; endif; else: echo "" ;endif; ?>
                <!-- goods_number数组值 -->
                <td><input type="text" name="goods_number[]" value="<?php echo ($v2["goods_number"]); ?>"/></td>
                <td><input type="button" value="<?php if(($keys) == "1"): ?>+<?php else: ?>-<?php endif; ?>" /></td>
            </tr>
            <!-- 隐藏域:goods_id值 --><?php endforeach; endif; else: echo "" ;endif; ?>
            <input type="hidden" name="goods_id" value="<?php echo ($_GET['goods_id']); ?>" />
            
        </table>
        <div style="width: 100px;margin: 0 auto"><input type="submit" value="保存"/>&nbsp;&nbsp;&nbsp;<input type="reset" value="重置"></div>
    </div>
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
    
<script type="text/javascript">
    $(":button").click(function(){
        //取出当前行
        var curr_tr = $(this).parent().parent();
        if($(this).val()=='+'){
            //完成自我复制
            var new_tr = curr_tr.clone(true);
            new_tr.find(":button").val('-');
            curr_tr.after(new_tr);
        }else{
            //完成当前行删除
            curr_tr.remove();
        }
    });
</script>