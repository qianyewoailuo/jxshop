<?php
//命名空间
namespace Admin\Model;
//自定义公共模型
/**
 * 商品扩展分类模型
 */
class GoodsCateModel extends CommonModel{
    //将商品的扩展分类写入数据库的方法
    public function insertExtCate($ext_cate_id,$goods_id)
    {
        //避免重复勾选进行去重操作
        $ext_cate_id = array_unique($ext_cate_id);
        // dump($ext_cate_id);
        //遍历获取的扩展分类中的cate_id数组
        foreach($ext_cate_id as $key => $value){
            //扩展分类个数是否等于0，即是否存在
            if($value != 0){
                $list[] = array(
                    'goods_id' => $goods_id,
                    'cate_id' => $value
                );
        //注意addAll()传入的参数必须是二维索引数组，下标从0开始.
        //如果要使用add方法则需要在遍历循环里面进行数据入库
            }
        }
        //遍历结束开始扩展分类入库
        $this->addAll($list);
    }

}