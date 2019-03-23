<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
    ECSHOP 管理中心 - 分类添加 
</title>

<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <!-- block头部 -->
    
    <span class="action-span"><a href="#">商品分类</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加分类 </span>
    <div style="clear:both"></div>

</h1>
<div class="main-div">
    <!-- block主体 -->
    
<style type="text/css">
.main-div table {background: #BBDDE5;}
</style>
    <form action="" method="post" name="theForm" enctype="multipart/form-data">
        <!-- 订单信息 -->
       <div class="list-div">
            <table width="100%" cellpadding="3" cellspacing="1">
            <tbody>
                <tr>
                    <th colspan="4">订单信息</th>
                </tr>
                <tr>
                    <td align="right" width="18%">订单号:</td>
                    <td align="left" width="34%"><?php echo ($info["id"]); ?></td>
                    <td align="right" width="15%">订单金额:</td>
                    <td align="left"><?php echo ($info["total_price"]); ?></td>
                </tr>
                <tr>
                    <td align="right" width="18%">下单时间:</td>
                    <td align="left" width="34%"><?php echo date('Y-m-d H:i:s',$info['addtime']);?></td>
                    <td align="right" width="15%">订单状态:</td>
                    <td align="left"><?php if(($info["pay_status"]) == "1"): ?>已支付<?php else: ?>非支付<?php endif; ?> <?php if(($info["order_status"]) == "2"): ?>已发货<?php else: ?>未发货<?php endif; ?></td>
                </tr>
                <tr>
                    <td align="right" width="18%">收货人:</td>
                    <td align="left" width="34%"><?php echo ($info["name"]); ?></td>
                    <td align="right" width="15%">收货地址:</td>
                    <td align="left"><?php echo ($info["address"]); ?></td>
                </tr>
                <tr>
                    <td align="right" width="18%">联系电话:</td>
                    <td align="left" width="34%"><?php echo ($info["tel"]); ?></td>
                    <td align="right" width="15%">下单人:</td>
                    <td align="left"><?php echo ($info["username"]); ?></td>
                </tr>
            </tbody>
            </table>
        </div>
        <!-- 地图信息 -->
        <div class="list-div">           
            <table width="100%" cellpadding="3" cellspacing="1">
                <tr>
                    <th>地图信息</th>
                </tr>
                <tr>
                    <td>
                        <!-- 新建地图DOM元素 -->
                        <div id="container" style="width:60%;height:300px;margin:auto"></div>
                    </td>
                </tr>
            </table>
        </div>
        <!-- 发货信息 -->
        <div class="list-div">           
            <table width="100%" cellpadding="3" cellspacing="1">
                <tr>
                    <th colspan="4">发货信息</th>
                </tr>
                <tr>
                    <td class="label">快递代号:</td>
                    <td>
                        <input type='text' name='com' id="com" value="<?php echo ($info["com"]); ?>" <?php if(($info["order_status"]) == "2"): ?>disabled<?php endif; ?>  /> 
                    </td>
                </tr>
                <tr>
                    <td class="label">快递单号:</td>
                    <td>
                        <input type='text' name='no' id="no" value="<?php echo ($info["no"]); ?>" <?php if(($info["order_status"]) == "2"): ?>disabled<?php endif; ?> /> 
                    </td>
                </tr>
            </table>
        </div>

        <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        <div class="button-div">
            <input type="submit" value=" 确定 " />
            <input type="reset" value=" 重置 " />
            <input type="button" value=" 修改快递信息 " id="express" />
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
    
<!--  地图API接口引入:直接引入方式 --> 
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=UQVBZ-MRZWQ-4MI5G-GUICT-27DF3-5QBPA"></script>
    <script>
        $('#express').click(function(){
            $('#com').attr('disabled',false);
            $('#no').attr('disabled',false);
        })

        //腾讯地图API
        function init(){
            var map = new qq.maps.Map(document.getElementById('container'),{
                zoom  :   16,     //初始缩放等级
                mapTypeId: qq.maps.MapTypeId.ROADMAP,     //地图类型:ROADMAP 普通街道 SATELLITE 卫星 HYBRID 卫星街道
            });
            map.panTo(new qq.maps.LatLng(39.916527,116.397128));

            //地址解析:注意官方示例中的result变量不一致会导致错误.
            var callbacks={
                //成功回调触发事件
                complete:function(result){
                    //使用map对象重新设定中心点
                    map.setCenter(result.detail.location);
                    //实例化覆盖物(即定位指针)
                    var marker = new qq.maps.Marker({
                        map:map,
                        // 指定具体地位指针位置
                        position: result.detail.location
                    });
                },
            }
            //实例化地址解析类
            geocoder = new qq.maps.Geocoder(callbacks);
            //地址解析对象通过getLocation方法获取经纬度,成功获取后触发回调函数callbacks
            geocoder.getLocation("<?php echo ($info["address"]); ?>");
        }
        init();
    </script>