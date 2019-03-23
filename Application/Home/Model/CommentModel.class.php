<?php
//命名空间
namespace Home\Model;
// 商品评价管理模型
class CommentModel extends CommonModel
{
    protected $fields = array('id','user_id','goods_id','addtime','content','star','good_number');

    protected $_validate = array(
        array('star','checkStar','评分星级必须选择',1,'callback'),
        array('content','checkContent','您还未发表任何评论',1,'callback')
    );
    //检查评分星级是否选择
    public function checkStar()
    {
        $star = I('post.star');
        if($star=='1' || $star=='2' || $star=='3' || $star=='4' || $star=='5' ){
            return true;
        }
        return false;
    }
    //检查评分内容是否输入
    public function checkContent()
    {
        $content = I('post.content');
        if($content != ''){
            return true;
        }
        return false;
    }
    //
    public function _before_insert(&$data)
    {
        $data['addtime'] = time();
        $data['user_id'] = session('user_id');
    }
    public function _after_insert($data)
    {
        //商品评价印象入库
        $model = M('Impression');
        $oldname = I('post.oldname');
        foreach ($oldname as $key => $value) {
            $model->where('id='.$value)->setInc('count');
        }
        $name = I('post.name');
        $name = explode(',',$name);
        $name = array_unique($name);
        foreach ($name as $key => $value) {
            if(!$value){
                //如果印象值为空
                continue;
            }
            //判断当前印象在数据库中是否存在
            $where = array(
                'goods_id'  =>  $data['goods_id'],
                'name'      =>  $value
            );
            $rs = $model->where($where)->find();
            if($rs){
                //已存在印象+1
                $model->where($where)->setInc('count');      
            }else{
                //未存在印象入库
                $where['count'] = 1;
                $model->add($where);
            }        
        }

        //商品评论总数入库
        D('Admin/Goods')->where('id='.$data['goods_id'])->setInc('total_comment');
    }

    //获取评论信息
    public function getList($goods_id)
    {
        $p = I('get.p');
        $pagesize = 3;
        $count = $this->where('goods_id='.$goods_id)->count();
        //实例化分页类
        $page = new \Think\Page($count,$pagesize);
        $page->setConfig('is_anchor',true);
        //分页信息
        $show = $page->show();
        //评论信息
        $data = $this->alias('a')->field('a.*,b.username')->join('left join jx_user b on a.user_id = b.id')->where('a.goods_id='.$goods_id)->page($p,$pagesize)->select();
        return array('data'=>$data,'show'=>$show);
    }
}