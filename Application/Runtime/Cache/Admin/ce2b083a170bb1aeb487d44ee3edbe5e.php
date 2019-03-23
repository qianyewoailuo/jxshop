<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP - 添加属性 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="<?php echo U('index');?>">属性列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加属性 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
    <form action="" method="post" name="theForm" enctype="multipart/form-data">
        <table width="100%" id="general-table">
            <!-- 属性名称 -->
            <tr>
                <td class="label">属性名称:</td>
                <td>
                    <input type='text' name='attr_name' maxlength="20" value='<?php echo ($info["attr_name"]); ?>' size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <!-- 属性所属类型 -->
            <tr>
                <td class="label">属性所属类型:</td>
                <td>
                    <select name="type_id">
                        <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $info["type_id"]): ?>selected<?php endif; ?> ><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <!-- 属性类型 -->
            <tr>
                <td class="label">属性类型:</td>
                <td>
                    <input type="radio" name="attr_type" value="1" <?php if(($info["attr_type"]) == "1"): ?>checked<?php endif; ?> >唯一
                    <input type="radio" name="attr_type" value="2" <?php if(($info["attr_type"]) == "2"): ?>checked<?php endif; ?> >单选
                </td>
            </tr>
            <!-- 属性录入方式 -->
            <tr>
                <td class="label">属性录入方式:</td>
                <td>
                    <input type="radio" name="attr_input_type" value="1" <?php if(($info["attr_input_type"]) == "1"): ?>checked<?php endif; ?> >手工输入
                    <input type="radio" name="attr_input_type" value="2" <?php if(($info["attr_input_type"]) == "2"): ?>checked<?php endif; ?> >列表选择
                </td>
            </tr>
            <!-- 默认值 -->
            <tr>
                <td class="label">[列表选择]默认值:</td>
                <td>
                    <textarea name="attr_value" id="" cols="30" rows="3"><?php echo ($info["attr_value"]); ?></textarea>
                    <span style="font-size:12px">*默认值为列表选择时必须输入,多个值间使用逗号隔开</span>
                </td>
            </tr>
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
    
    <script>
        //设置默认值为禁用状态
        <?php if(($info["attr_input_type"]) == "1"): ?>$("textarea[name='attr_value']").attr('disabled',true);<?php endif; ?>
        //属性录入值切换绑定事件
        $("input[name='attr_input_type']").change(function(){
            var value = $(this).val();
            if(value == 1){
                // 原来jq中方法可以连续使用的哦
                $("textarea[name='attr_value']").attr('disabled',true).val('');
            }else{
                $("textarea[name='attr_value']").attr('disabled',false).val('');
            }
        });
    </script>