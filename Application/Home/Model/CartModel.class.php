<?php
//命名空间
namespace Home\Model;
// 购物车管理模型
class CartModel extends CommonModel
{
    //定义字段
    protected $fields = array('id','user_id','goods_id','goods_attr_ids','goods_count');
    
    //购物车商品添加入库
    public function addCart($goods_id,$goods_count,$attr)
    {
        //将属性信息排序
        sort($attr);                                        //目的是为了考虑后期库存量的检查
        //属性转换为字符串格式
        $goods_attr_ids = $attr?implode(',',$attr):'';      //有单选属性的分割字符串否则为空字符
        //检查库存量是否充足
        $rs = $this->checkGoodsNumber($goods_id,$goods_count,$goods_attr_ids);
        if(!$rs){
            $this->error = '库存量不足';
            return false;
        }
        //获取用户ID
        $user_id = session('user_id');
        if($user_id){
            //登录用户入库购物车数据表
            //判断当前写入的数据是否已存在
            $map = array(
                'user_id' => $user_id,
                'goods_id' => $goods_id,
                'goods_attr_ids' =>$goods_attr_ids
            );
            $info = $this->where($map)->find();
            if($info){
                return $this->where($map)->setField('goods_count',$goods_count+$info['goods_count']);
            }else{
                $map['goods_count'] = $goods_count;
                return $this->add($map);
            }
        }else{
            //未登录用户信息添加到cookie中
            //因为cookie存储的数据只能是字符串所以数组数据要序列化
            $cart = unserialize(cookie('cart'));        //获取cookie并反序列化
            $key = $goods_id.'-'.$goods_attr_ids;       //将获取的数据拼接成key键以备查询
            if(array_key_exists($key,$cart)){
                //当前商品-属性数据存在时直接将value值进行+运算
                $cart[$key] += $goods_count;
            }else{
                //当前商品-属性数据不存在新增value值
                $cart[$key] = $goods_count;
            }
            //处理结束写入cookie
            cookie('cart',serialize($cart));
            //返回结果信息
            return true;
        }   
    }           //这里是addCart结束

    //商品库存量检查
    public function checkGoodsNumber($goods_id,$goods_count,$goods_attr_ids)
    {
        //检查总库存量是否充足
        $goods = D('Admin/Goods')->where("id=$goods_id")->find();
        if($goods['goods_number'] < $goods_count){
            //总库存量不足
            return false;
        }
        //检查相应属性组合商品库存是否充足
        if($goods_attr_ids){                         //有单选属性
            $where = "goods_id=$goods_id AND goods_attr_ids='$goods_attr_ids'";
            $number = D('GoodsNumber')->where($where)->find();
            if(!$number || $number['goods_number'] < $goods_count){
                //属性组合库存不足
                return false;
            }
        }
        return true;
    }

    //购物车cookie数据转移到数据库
    //此方法在UserModel登录验证完成返回true前触发
    public function cookie2db()
    {
        //获取购物车cookie信息
        $cart = unserialize(cookie('cart'));
        //获取当前用户的ID标识
        $user_id = session('user_id');
        if(!$user_id){
            return false;
        }
        foreach ($cart as $key => $value) {
            //先将商品ID以及属性组合拆分
            $tmp = explode('-',$key);
            //通过map条件查询当前商品信息是否存在
            $map = array(
                'user_id'           =>  $user_id,
                'goods_id'          =>  $tmp[0],
                'goods_attr_ids'    =>  $tmp[1]
            );
            $info = $this->where($map)->find();
            if($info){          
                //当前购物车信息存在直接修改数量字段
                $this->where($map)->setField('goods_count',$info['goods_count']+$value);
            }else{
                //当前购物车信息不存在写入当前数据
                $map['goods_count'] = $value;
                $this->add($map);
            }
        }
        //清空当前cookie数据
        cookie('cart',null);
    }

    //购物车列表信息显示
    public function getList()
    {
        //1.获取当前购物车中对应的信息
        $user_id = session('user_id');
        if($user_id){
            //登录用户从购物车数据表获取数据
            $data = $this->where('user_id='.$user_id)->select();
        }else{
            //未登录用户从cookie获取购物车数据
            $cart = unserialize(cookie('cart'));
            //转换cookie购物车数据格式
            foreach ($cart as $key => $value) {
                $tmp = explode('-',$key);
                $data[] = array(
                    'goods_id'          =>  $tmp[0],
                    'goods_attr_ids'    =>  $tmp[1],
                    'goods_count'       =>  $value
                );
            }
        }
        //2.跟住购物车中的商品ID获取商品信息
        $GoodsModel = D('Goods');
        foreach ($data as $key => $value) {
            //获取商品信息
            $goods = $GoodsModel->where('id='.$value['goods_id'])->find();
            //判断商品是否处于促销状态设置价格
            if($goods['cx_price'] >0 && $goods['start'] < time() && $goods['end'] > time()){
                //促销中
                $goods['shop_price'] = $goods['cx_price'];
            }
            $data[$key]['goods'] = $goods;
            //3.根据购物车表中的属性值组合获取对应的属性名称与属性值
            if($value['goods_attr_ids']){
                //获取商品的属性信息
                //联表查询思路:1.通过in语句找出GoodsAttr记录 2.联表attribute查询
                $attr = D('Admin/GoodsAttr')->alias('a')->field('a.attr_values,b.attr_name')->join('left join jx_attribute b on a.attr_id=b.id')->where("a.id in ({$value['goods_attr_ids']})")->select();
                $data[$key]['attr'] = $attr;
            }
        }
        return $data;
        // dump($data);exit();
    }
    //计算购物车总价格
    public function getTotal($data)
    {
        $count = $sum = 0;
        foreach ($data as $key => $value) {
            $count += $value['goods_count'];
            $price += $value['goods_count']*$value['goods']['shop_price'];
        }
        return array('count'=>$count,'price'=>$price);
    }

    //购物车商品删除功能
    public function del($goods_id,$goods_attr_ids)
    {
        $goods_attr_ids = $goods_attr_ids?$goods_attr_ids:'';
        $user_id = session('user_id');
        if($user_id){
            //登录用户的购物车表删除
            $map = array(
               'user_id'        =>  $user_id,
               'goods_id'       =>  $goods_id,
               'goods_attr_ids' =>  $goods_attr_ids
           ); 
           $this->where($map)->delete();
        }else{
            //非登录用户的购物车cookie信息删除
            $cart = unserialize(cookie('cart'));
            $key = $goods_id.'-'.$goods_attr_ids;
            unset($cart[$key]);
            //删除后重新将新的数据写入cookie
            cookie('cart',serialize($cart));
        }
    }

    //购物车商品数量增加以及减少的更新
    public function updateCount($goods_id,$goods_attr_ids,$goods_count)
    {
        //当goods_count的值小于或等于0时操作非法返回false不做处理
        if($goods_count <= 0){
            return false;
        }
        $goods_attr_ids = $goods_attr_ids?$goods_attr_ids:'';
        $user_id = session('user_id');
        if($user_id){
            //登录用户的购物车表删除
            $map = array(
               'user_id'        =>  $user_id,
               'goods_id'       =>  $goods_id,
               'goods_attr_ids' =>  $goods_attr_ids
           ); 
           $this->where($map)->setField('goods_count',$goods_count);
        }else{
            //非登录用户的购物车cookie信息删除
            $cart = unserialize(cookie('cart'));
            $key = $goods_id.'-'.$goods_attr_ids;
            $cart[$key] = $goods_count;
            //删除后重新将新的数据写入cookie
            cookie('cart',serialize($cart));
        }
    }
}