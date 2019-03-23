<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP - 添加新商品 
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
    <span class="action-span1"><a href="<?php echo U('Index/index');?>">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加新商品 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
    <div class="tab-div">
        <div id="tabbar-div">
            <p>
                <span class="tab-front">通用信息</span>
                <span class="tab-front">商品属性</span>
                <span class="tab-front">商品相册</span>
            </p>
        </div>
        <div id="tabbody-div">
            <form enctype="multipart/form-data" action="" method="post">
                <!-- 商品table -->
                <table width="90%" class="table" align="center">
                    <tr>
                        <td class="label">商品名称：</td>
                        <td><input type="text" name="goods_name" value=""size="30" />
                        <span class="require-field">*</span></td>
                    </tr>
                    <tr>
                        <td class="label">商品货号： </td>
                        <td>
                            <input type="text" name="goods_sn" value="" size="20"/>
                            <span id="goods_sn_notice"></span><br />
                            <span class="notice-span"id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label" id="cx_goods">是否促销<a id="cx_show">[显示]</a>：</td>
                        <td id="cx_notice" style="color:red">*若无促销无需填写,若有促销点击显示填写信息</td>
                        <td style="display:none" id="cx_msg">
                            促销价格：<input type="text" class="cx_value" id="cx_price" name="cx_price" value="" size="20"/>
                            开始时间：<input type="text" class="cx_value" name="start" value="" size="20"/>
                            结束时间：<input type="text" class="cx_value" name="end" value="" size="20"/>
                            <span id="goods_sn_notice"></span><br />
                            <span class="notice-span"id="noticeGoodsSN">若无促销无需填写</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品分类：</td>
                        <td>
                            <select name="cate_id">
                                <option value="0">请选择...</option>
                                <!-- 循环分类 -->
                            <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo str_repeat("&nbsp;",$vo['lev']); echo ($vo["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <span class="require-field">*</span>
                        </td>
                    </tr>
                        <!-- 扩展分类 -->
                    <tr>
                        <td class="label">扩展分类：</td>
                        <td>
                            <input type="button" name="addExtCate" id="addExtCate" value="增加扩展分类">
                            <select name="ext_cate_id[]" id="">
                                <option value="0">请选择...</option>
                                <!-- 扩展分类循环 -->
                            <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo str_repeat("&nbsp;",$vo['lev']); echo ($vo["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">本店售价：</td>
                        <td>
                            <input type="text" name="shop_price" value="" size="20"/>
                            <span class="require-field">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">是否上架：</td>
                        <td>
                            <input type="radio" name="is_sale" value="1" checked="checked" /> 是
                            <input type="radio" name="is_sale" value="0"/> 否
                        </td>
                    </tr>
                    <tr>
                        <td class="label">加入推荐：</td>
                        <td>
                            <input type="checkbox" name="is_hot" value="1" /> 热卖 
                            <input type="checkbox" name="is_new" value="1" /> 新品 
                            <input type="checkbox" name="is_rec" value="1" /> 推荐
                        </td>
                    </tr>
    
                    <tr>
                        <td class="label">市场售价：</td>
                        <td>
                            <input type="text" name="market_price" value="" size="20" />
                        </td>
                    </tr>
    
                    <tr>
                        <td class="label">商品图片：</td>
                        <td>
                            <input type="file" name="goods_img" size="35" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品描述：</td>
                        <td>
                            <script type="text/javascript" charset="utf-8" src="/Public/Ueditor/ueditor.config.js"></script>
                            <script type="text/javascript" charset="utf-8" src="/Public/Ueditor/ueditor.all.min.js"> </script>
                            <!-- 语言加载js -->
                            <script type="text/javascript" charset="utf-8" src="/Public/Ueditor/lang/zh-cn/zh-cn.js"></script>
                            <script id="editor" name="goods_body" type="text/plain" style="width:700px;height:500px;"></script>
                        </td>
                    </tr>
                </table>
                <!-- 属性table -->
                <table width="90%" class="table" align="center" style="display:none;">
                    <tr>
                        <td class="label">商品类型:</td>
                        <td>
                            <select name="type_id" id="type_id">
                                <option value="0">---请选择---</option>
                            <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" id="showAttr"></td>
                    </tr>
                </table>
                <!-- 相册table -->
                <table width="90%" class="table pic" align="center" style="display:none;">
                    <!-- 增加按钮 -->
                    <tr>
                        <td class="label">继续添加?:</td>
                        <td>
                            <input type="button" name="addNewPic" id="" class="addNewPic" value="增加相册图片">
                        </td>
                    </tr>
                    <!-- 添加相册 -->
                    <tr>
                        <td class="label">相册图片上传:</td>
                        <td>
                            <input type="file" name="pic[]" id="">
                        </td>
                    </tr>
                </table>                
                <div class="button-div">
                    <input type="submit" value=" 确定 " class="button"/>
                    <input type="reset" value=" 重置 " class="button" />
                </div>
            </form>
        </div>
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
    //1.实例化editor编辑器
        var ue = UE.getEditor('editor');
        //实现点击增加新的扩展分类
        $("#addExtCate").click(function(){
            //复制select并写入
            var newSelect = $(this).next().clone();
            $(this).parent().append(newSelect);
        });

    //2.实现选项卡的切换
        $("#tabbar-div p span").click(function(){
            //将所有table设置为隐藏
            $('.table').hide();                         //所有的class = table 的元素隐藏
            //将当前点击的选项卡对应的内容显示
            var i = $(this).index();                    //获取当前点击的索引值
            // alert(i);        ->0 ->1
            $('.table').eq(i).show();                   //class = table 的元素索引值等于i值对应table显示
        });

    //3.商品类型切换显示相对应的属性
        $('#type_id').change(function(){
            //获取到当前前被选中的类型标识
            var  type_id = $(this).val();
            $.ajax({
                url:"<?php echo U('showAttr');?>",            //请求地址为showAttr的方法
                data:{type_id:type_id},            //传递type_id数据
                type:'post',                       //传递方式为post
                success:function(msg){             //成功执行后将返回的信息显示成html
                    // console.log(msg);
                    $('#showAttr').html(msg);
                }
            });
        });
    //4.实现单选类型的属性点击增加
        function clonethis(obj){
            //获取当前行tr的对象
            var current = $(obj).parent().parent();     //当前行对象
            if($(obj).html() == '[+]'){
                //当点击的对象为[+]时复制当前行tr
                var newtr = current.clone();            //复制当前行
                //将当前的[+]变成[-]
                newtr.find('a').html('[-]');
                current.after(newtr);                   //依附在当前点击行的下一元素
            }else{
                //当点击对象不为[+]时删除当前行
                current.remove();                       //删除当前行
            }
        }
    //5.实现点击按钮增加图片上传
        $('.addNewPic').click(function(){
            // 获取需要复制的行
            var newfile = $(this).parent().parent().next().clone();
            // 追加到table后
            $('.pic').append(newfile);
        });

    //6.实现促销信息填写的显示与隐藏
        $('#cx_show').click(function(){     //点击[显示]|[隐藏]
            if($(this).html() == '[显示]'){
                $('#cx_notice').hide();     //提示是否展开显示促销填写信息
                $('#cx_msg').show();        //促销填写信息
                $(this).html('[隐藏]');
            }else{
                $('#cx_notice').show();
                $('#cx_msg').hide();
                $('.cx_value').val('');
                $(this).html('[显示]');
            }
        });
    </script>