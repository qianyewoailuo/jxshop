<?php
namespace Home\Controller;

use Zane\Utils\Ary;
// 测试控制器
class TestController extends CommonController
{
	// 测试zane/utils类库的方法
	public function test1()
	{
        $data = ['red','green','blue','yellow','red','red'];
        // 
        $rs = Ary::new($data)->countValues()->maxKey();
        echo $rs;
	}
}