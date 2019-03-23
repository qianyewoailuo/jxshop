<?php if (!defined('THINK_PATH')) exit(); if(is_array($data)): $iteration = 0; $__LIST__ = array_slice($data,0,5,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($iteration % 2 );++$iteration; echo ($key); ?> --<?php echo ($iteration); ?>- <?php echo ($val["goods_name"]); ?>--<br><?php endforeach; endif; else: echo "" ;endif; ?>
<hr>
<?php if(is_array($data)): foreach($data as $k=>$val): echo ($k); ?> -- <?php echo ($val["goods_name"]); ?> <br/><?php endforeach; endif; ?>
<hr>
<?php foreach($data as $key => $val){ if($key == 5){ break; } echo $key,'=>',$val['goods_name'],'<br>'; } ?>