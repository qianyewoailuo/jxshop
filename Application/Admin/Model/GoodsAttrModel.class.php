<?php
//命名空间
namespace Admin\Model;
// 商品属性中间表模型
class GoodsAttrModel extends CommonModel
{
    //定义字段
    protected $fields = array(
        'id','goods_id','attr_id','attr_values'
    );
    public function insertGoodsAttr($attr,$goods_id)
    {
        foreach ($attr as $key => $value) {
            foreach ($value as $k => $v) {
                $attr_list[] = array(
                    'goods_id' => $goods_id,
                    'attr_id'  => $key,
                    'attr_values' => $v
                );
            }
        }
        //如果有数据就入库
        $this->addAll($attr_list);
    }

    //
    public function getSigleAttr($goods_id)
    {
        $data = $this->alias('a')->join('left join jx_attribute b on a.attr_id=b.id')->field('a.*,b.attr_name,attr_type,attr_input_type,attr_value')->where("a.goods_id=$goods_id AND b.attr_type=2")->select();
        foreach ($data as $key => $value) {
            $list[$value['attr_name']][] = $value;
        }
        // dump($list);
        return $list;
    }

}