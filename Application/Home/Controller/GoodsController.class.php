<?php
// 命名空间
namespace Home\Controller;
// 字符编码
header('Content-type:text/html;charset=utf-8');
// 商品控制器
class GoodsController extends CommonController
{
    //cookie信息查看
    public function getCookie()
    {
        echo('---所有cookie信息----');
        dump(cookie());
        echo('---cart购物车信息----');
        dump(unserialize(cookie('cart')));
        echo('---session信息----');
        dump(session());  
    }
    //商品首页
    public function index()
    {
        //获取商品ID
        $goods_id = intval(I('get.goods_id'));
        if($goods_id <= 0){
            //参数不正常,重定向到商城首页
            //注意重定向穿的的URL地址不需要使用U函数,直接指定地址参数即可
            $this->redirect('Index/index');
        }
        $GoodsModel = D('Admin/Goods');
        //  获取当前商品信息
        $info = $GoodsModel->where('is_sale=1 AND id='.$goods_id)->find();
        if(!$info){
            //商品不存在或者下架状态不允许查看,重定向回首页
            $this->redirect('Index/index');
        }
        // 将商品描述信息格式化
        $info['goods_body'] = htmlspecialchars_decode($info['goods_body']);
        $info['sale'] = '本店价';                    //个人设定的本店价与促销价
        //如果当前商品为促销状态更改价格为促销价
        if($info['cx_price'] > 0 && $info['start'] < time() && $info['end'] > time()){
            $info['shop_price'] = $info['cx_price'];
            $info['sale'] = '促销价';
        }
        //  1.传递模板商品信息
        $this->assign('info',$info);
        //  2.传递相册信息
        $pic = D('Admin/GoodsImg')->where('goods_id='.$goods_id)->select();
        $this->assign('pic',$pic);
        //  3.传递属性信息
        $attr = D('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_type')->join('left join jx_attribute b on a.attr_id=b.id')->where('a.goods_id='.$goods_id)->select();
        // dump($attr);         //测试
        foreach ($attr as $key => $value) {
            if($value['attr_type'] == 1){
                //唯一属性
                $unique[] = $value;
            }else{
                //单选属性,格式化为三维数组
                $sigle[$value['attr_id']][] = $value; 
            }
        }
        // dump($sigle);
        $this->assign('unique',$unique);
        $this->assign('sigle',$sigle);
        // 4.传递商品评论信息
        $CommentModel = D('Comment');
        $comment = $CommentModel->getList($goods_id);
        $this->assign('comment',$comment);
        // 5.传递商品印象信息
        $impression = M('Impression')->where('goods_id='.$goods_id)->order('count desc')->limit(8)->select();
        // if(!$impression){
        //     $impression = 1;
        // }
        // dump($impression);
        $this->assign('impression',$impression);
        $this->display();
    }

    //商品评价
    public function comment()
    {
        //用户登录判断
        $this->checkLogin();
        //评论数据入库
        $model = D('Comment');
        $data = $model->create();
        if(!$data){
            //创建失败返回错误提示信息
            $this->error($model->getError());
        }
        $rs = $model->add($data);
        if(!$rs){
            $this->error($model->getError());
        }
        $this->success('评价成功');
    }

    //商品有用评价添加
    public function good()
    {
        $comment_id = I('post.comment_id');
        $model = D('Comment');
        $info = $model->where('id='.$comment_id)->find();
        if(!$info){
            $this->ajaxReturn(array('status'=>0,'msg'=>'error'));
        }else{
            $model->where('id='.$comment_id)->setField('good_number',$info['good_number']+1);
            $this->ajaxReturn(array('status'=>1,'msg'=>'success','good_number'=>$info['good_number']+1));
        }
    }
}