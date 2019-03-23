<?php
//命名空间
namespace Admin\Model;

use Think\Upload;


//自定义公共模型
class GoodsModel extends CommonModel{
    //自定义字段
    protected $fields = array(
        'id','goods_name','goods_sn','cate_id','market_price','shop_price','goods_img','goods_thumb','goods_body','is_hot','is_rec','is_new','addtime','isdel','is_sale','type_id','goods_number','cx_price','start','end','sale_number','total_comment',
    );
    //自动验证规则
    protected $_validate = array(
        //array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间])
        /**
         * TP内置验证规则 require 字段必须、email 邮箱、url URL地址、currency 货币、number 数字
         * 验证条件 0 存在字段就验证（默认）| 1 必须验证 |2 值不为空时验证
         * 常用附加规则  regex 定义的验证规则是一个正则表达式（默认） | function | callback 定义的验证规则是当前模型类的一个方法 | unique 字段值是否唯一 | confirm 验证表单中的两个字段是否相同，定义的验证规则是一个字段名 (检测重新输入的密码)
         */
        array('goods_name','require','商品名称必须填写'),
        array('cate_id','checkCategory','商品分类必须选择',1,'callback'),
        array('market_price','currency','市场价格格式不正确'),
        array('shop_price','currency','本店价格格式不正确'),
        // array('cx_price','currency','促销价格格式不正确'),
        // array('goods_sn','','货号不能重复',1,'unique'),
    );
    //自动验证规则中checkCategory验证方法
    public function checkCategory($cate_id)
    {
        $cate_id = intval($cate_id);
        if($cate_id > 0){
            return true;
        }
        return false;
    }
    //上传相册图片的方法
    public function uploadImgList($goods_id)
    {
        unset($_FILES['goods_img']);        //先将商品图片上传释放
        $upload = new \Think\Upload();
        $info = $upload->upload();
        // dump($info);exit();
        foreach ($info as $key => $value) {
            //生成缩略图
            $goods_img = 'Uploads/'.$value['savepath'].$value['savename'];  //获取原图的保存地址
            $img = new \Think\Image();  //实例化
            $img->open($goods_img);     //打开图片
            $goods_thumb = 'Uploads/'.$value['savepath'].'thumb_'.$value['savename'];  //设定缩略图保存地址
            $img->thumb(100,100)->save($goods_thumb);   //设定生成缩略图尺寸并且保存在设定的地址
            $list[] = array(
                'goods_id'     =>  $goods_id,
                'goods_img'    =>  $goods_img,
                'goods_thumb'  =>  $goods_thumb
            );
        }
        return $list;   //返回数据
    }

    //上传图片的方法
    public function uploadImg()
    {
        //判断是否有图片上传或上传是否成功，如果都为否就直接返回false
        if(!isset($_FILES['goods_img']) || $_FILES['goods_img']['error']!=0){
            return false;
        }
        //设置上传图片格式限制
        $config = array(
            'exts' => array('jpg','gif','png','jpeg'),
        );
        $upload =new \Think\Upload($config);
        $info = $upload->uploadOne($_FILES['goods_img']);
        if(!$info){
            //上传失败返回错误信息
            $this->error = $upload->getError();
            return false;
        }
        //上传后的图片地址
        //在php代码中图片的地址不要使用'/'(系统根目录)，而在html代码中显示图片时必须使用'/'(域名根目录)，当然如果忘记了可以直接输入域名加上/以防万一
        $goods_img = 'Uploads/'.$info['savepath'].$info['savename'];
        //缩略图制作
        /**
         * TP缩略图的制作 \Think\Image()类的一些方法
         * open($img) 打开需要处理的图片 $img 图片地址
         * crop(W,H,[X],[Y]) 裁剪打开的图片 [x][y]表示裁剪的起始坐标
         * thump(W,H,[type]) 制作缩略图 type: 1 默认等比缩略 | 2 缩放填充成固定尺寸 | 3 居中裁剪缩略 | 6 固定尺寸缩放
         * water($logoimg,[position],[透明度]) 添加图片水印 $logimg 水印图地址
         * text(txt,ttf,fontsize,color,[position],[透明度]) 添加文字水印 
         * [position] -> 1 默认左上角 | 9 右下角 | [透明度] ->1-100 默认80
         */
        $img = new \Think\Image();  //实例化
        $img->open($goods_img);     //打开图片
        $goods_thumb = 'Uploads/'.$info['savepath'].'thumb_'.$info['savename'];  //设定缩略图保存地址
        $img->thumb(450,450)->save($goods_thumb);   //生成缩略图并且保存在设定的地址
        //返回图像数据
        return array('goods_img'=>$goods_img,'goods_thumb'=>$goods_thumb);
    }

    //在添加新商品时还应该优化：1.自动插入添加时间 2.货号没填写为空时自动添加货号 3.实现图片上传
    //因此可以使用前置写入钩子函数
    public function _before_insert(&$data,$options)
    {
        //促销商品时间格式化
        if($data['cx_price'] > 0){
            //能获取促销信息
            $data['start'] = strtotime($data['start']);
            $data['end'] = strtotime($data['end']);
        }else{
            //没有设置促销信息
            $data['cx_price'] = 0.00;
            $data['start'] = 0;
            $data['end'] = 0;
        }
        // dump($data);exit();
        //自动添加时间
        $data['addtime'] = time();
        //货号没填写自动添加货号
        if($data['goods_sn'] == ''){
            $data['goods_sn'] = uniqid();   //基于毫秒时间戳的uniqid()函数，参考/www1/test/upload
        }else{
            //检查货号是否重复
            $goods_sn = $data['goods_sn'];
            $rs = $this->where("goods_sn = '$goods_sn' ")->find();
            if($rs){
                $this->error = '货号重复';
                return false;
            }
        }
        //实现图片上传
        $rs = $this->uploadImg();
        if($rs){
            $data['goods_img'] = $rs['goods_img'];
            $data['goods_thumb'] = $rs['goods_thumb'];
        }
    }
    //后置插入钩子函数：用于获取扩展分类数据的写入
    public function _after_insert($data)
    {
        //扩展分类入库
        $goods_id = $data['id'];
        $ext_cate_id = I('post.ext_cate_id');
        D('GoodsCate')->insertExtCate($ext_cate_id,$goods_id);

        //商品属性中间表入库
        $attr = I('post.attr');
        //如果有属性添加就实现商品属性中间表入库
        if($attr){
            D('GoodsAttr')->insertGoodsAttr($attr,$goods_id);         //代码重用,使用相应模型中的insert数据方法
        }
        //图片相册入库
        $list = $this->uploadImgList($goods_id);                 //代码重用
        //循环结束如果有数据就开始入库
        if($list){
            D('GoodsImg')->addAll($list);
        }
    }
    //获取(默认isdel=1)商品信息的方法
    public function listData($isdel=1)
    {
        $where = 'isdel='.$isdel;         //设定默认获取的商品状态条件
        //分类搜索
        $cate_id = intval(I('get.cate_id'));
        #一.分类搜索
        if($cate_id){
            /**
             * 拼接where语句搜索,两种情况
             * 1.条件分类下没有其他子分类的商品
             * 2.条件分类下含有其他子分类的商品
             * 3.扩展分类查询出的商品
             */
            //1.根据当前设定的分类id获取子分类
            $cateModel = D('Category');
            $tree = $cateModel->getChildren($cate_id);
            //2.添加第一种情况的自身分类
            $tree[] = $cate_id; 
            //转为字符串以备拼接sql语句
            $children = implode(',',$tree); 
            //3.获取扩展分类情况的商品id
            $ext_goods_ids = M('GoodsCate')->group("goods_id")->where("cate_id in ($children)")->select();
            // dump($ext_goods_ids);//上面的group("goods_id")是通过商品ID排序获取的
            if($ext_goods_ids){
                foreach($ext_goods_ids as $key => $value){
                    $goods_ids[] = $value['goods_id'];
                }
                //同理转为字符串以备拼接sql语句
                $goods_ids = implode(',',$goods_ids);
            }
            //4.开始组合where搜索语句
            if(!$goods_ids){
                //如果没有扩展分类时
                $where .= " AND cate_id in ($children)";
            }else{
                $where .= " AND (cate_id in ($children) OR id in ($goods_ids))";
            }
        }
        #二.推荐状态搜索
        $intro_type = I('get.intro_type');
        if($intro_type){
            if($intro_type == 'is_new' || $intro_type == 'is_hot' || $intro_type == 'is_rec'){
                $where .= " AND $intro_type = 1";   
            }
        }
        #三.上下架状态搜索
        $is_sale = intval(I('get.is_sale'));
        if($is_sale == 1){
            $where .= " AND is_sale=1";     //上架状态
        }elseif($is_sale == 2){
            $where .= " AND is_sale=0";     //下架状态
        }
        #四.关键字搜索
        $keyword = I('get.keyword');
        if($keyword != ''){
            $where .= " AND goods_name LIKE '%$keyword%'";
        }
        //测试$where条件语句
        // dump($where);
        $pagesize = 6;                              //每页显示的数据量
        $count = $this->where($where)->count();     //获取总记录数
        $page = new \Think\Page($count,$pagesize);  //实例化分页类
        //-----下面是分页样式的设定---------
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
        $show = $page->show();                      //获取分页导航
        $p = intval( I('get.p'));                   //获取当前页码
        $order = 'id desc';
        $is_desc = I('get.is_desc');
        if($is_desc == 1){
            $order = 'id asc';
        }
        $data = $this->where($where)->page($p,$pagesize)->order($order)->select();          //获取分页数据
        $list = array(                              //将获取的数据通过数组返回
            'pageShow' =>  $show,
            'data'     =>  $data 
        );
        return $list;
    }
    //删除/还原商品状态的方法
    public function setStatus($goods_id,$idel=0)
    {
        return $this->where("id=$goods_id")->setField('isdel',$idel);   //注意格式setField(字段名,字段值)
        // 使用save方式时↓↓↓
        // $data['isdel'] = 0; 
        // return $this->where("id=$goods_id")->save($data);
    }
    //回收站彻底删除商品记录的方法
    public function remove($goods_id)
    {
        /**
         * 彻底删除商品记录需要删除三部分
         * 1.删除上传图片以及缩略图
         * 2.删除相对应的扩展分类信息记录
         * 3.删除商品表相对应的记录
         */
        //1.删图
        $goods_info = $this->findOneById($goods_id);    //获取记录
        if($goods_info === false){                      //判断记录是否存在
            return false;
        }
        unlink($goods_info['goods_img']);       //删原图
        unlink($goods_info['goods_thumb']);     //删缩略图
        //2.删扩展分类
        D('GoodsCate')->where("goods_id = $goods_id")->delete();
        //3.删记录
        $this->where("id = $goods_id")->delete();
        return true;
    }
    //编辑修改商品的方法
    public function update($data)
    {
        
        $goods_id = $data['id'];
        //更新商品数据的代码还需要3步优化

        //1.货号优化
        $goods_sn = $data['goods_sn'];
        if(!$goods_sn){
            //没有填写货号时自动填写
            $data['goods_sn'] = 'JX'.uniqid();
        }
        //货号已填写时判断货号是否重复
        $rs = $this->where("goods_sn = '$goods_sn' AND id != $goods_id")->find();
        if($rs){
            $this->error='货号重复';
            return false;
        }
        //2.扩展分类优化
        $ext_cateModel = D('GoodsCate');
        $ext_cateModel->where("goods_id = $goods_id")->delete();
        //获取修改后的扩展分类信息
        $ext_cate_id = I('post.ext_cate_id');
        $ext_cateModel->insertExtCate($ext_cate_id,$goods_id);
        //3.图片上传优化
        $rs = $this->uploadImg();
        if($rs){
            $data['goods_img'] = $rs['goods_img'];
            $data['goods_thumb'] = $rs['goods_thumb'];
        }
        //4.商品属性中间表修改入库
        $goodsAttrModel = D('GoodsAttr');
        $goodsAttrModel->where('goods_id='.$goods_id)->delete();
        $attr = I('post.attr');
        $goodsAttrModel->insertGoodsAttr($attr,$goods_id);        //代码重用,使用模型中的insert数据方法

        //5.商品相册入库
        $list = $this->uploadImgList($goods_id);
        if($list){
            D('GoodsImg')->addAll($list);
        }
        //6.促销信息时间自动转换
        if($data['cx_price'] > 0){
            //能获取促销信息
            $data['start'] = strtotime($data['start']);
            $data['end'] = strtotime($data['end']);
        }else{
            //没有设置促销信息
            $data['cx_price'] = 0.00;
            $data['start'] = 0;
            $data['end'] = 0;
        }
        //商品入库
        return $this->save($data);
    }

    //根据传递的参数返回热卖,推荐,新品数据
    public function getRecGoods($type)
    {
        //必须是在售类型标识为1以及限定取5个
        return $this->where("is_sale=1 AND $type=1")->limit(5)->order('id desc')->select();
    }

    //实现获取促销商品记录
    public function getCrazyGoods()
    {
        $where = 'is_sale=1 AND cx_price>0 AND start<'.time().' AND end>'.time();
        return $this->where($where)->limit(5)->select();
    }

    //获取某分类下的商品信息
    public function getList()
    {
        //1.实现分类及其子分类的获取
        $cate_id = intval(I('get.id'));
        //获取当前分类下的子分类并追加到此
        $children = D('Admin/Category')->getChildren($cate_id);
        $children[] = $cate_id;
        //转换为字符串
        $children = implode(',',$children);

        //初始where条件语句
        $where = "is_sale=1 AND cate_id in ($children)";

        //2.实现价格区间获取
        //获取当前分类下最大/最小价格以及商品总数
        //获取当前分类下所有商品的ID组合(group_concat(字段)将字段所有信息组合在一起)
        $goods_info = $this->field('max(shop_price) max_price ,min(shop_price) min_price, count(id) goods_count, group_concat(id) goods_ids')->where($where)->find();
        // dump($goods_info);
        //根据当前获取的商品总数允许显示出价格区间
        if($goods_info['goods_count'] > 1){
            $cha = $goods_info['max_price'] - $goods_info['min_price'];
            //根据小价格差值$cha显示不同的价格区间个数
            if($cha < 100){
                //差值100内显示1个价格区间
                $sec = 1;
            }elseif($cha < 500){
                //差值500内显示2个价格区间
                $sec = 2;
            }elseif($cha < 1000){
                //差值1000内显示3个价格区间
                $sec = 3;
            }elseif ($cha < 5000) {
                //差值5000内显示4个价格区间
                $sec = 4;
            }elseif ($cha < 10000) {
                //差值10000内显示5个价格区间
                $sec = 5;
            }else{
                //差值大于10000显示6个价格区间
                $sec = 6;
            }
            
            $price = array();                           //保存各个价格区间对应的值
            $first = ceil($goods_info['min_price']);    //开始价格
            $inc   = ceil($cha/$sec);                   //增量值
            //循环获取各个区间的开始价格和结束价格
            for ($i=0; $i < $sec; $i++) { 
                //组装区间价格
                $price[] = $first.'-'.($first+$inc);
                $first += $inc+1;
            }
            //测试
            // dump($price);exit();
        }
        # A.价格条件筛选
        if(I('get.price')){
            //获取当前价格条件并拆分成数组拼接查询语句
            $priceTmp   = explode('-',I('get.price'));
            $where .= ' AND shop_price>='.$priceTmp[0].' AND shop_price<='.$priceTmp[1];
        }

        //根据当前获取的商品ID取得属性信息 distinct 去重
        if($goods_info['goods_ids']){
            $attr = M('GoodsAttr')->alias('a')->field(' distinct a.attr_id,a.attr_values,b.attr_name')->join('left join jx_attribute b on a.attr_id=b.id')->where('a.goods_id in ('.$goods_info['goods_ids'].')')->select();
            // dump($attr);
            foreach ($attr as $key => $value) {
                $attrwhere[$value['attr_id']][] = $value; 
            }
            // dump($attrwhere);
        }
         # B.属性条件筛选
        if(I('get.attr')){
            //将获取的属性值转换为数组格式,方便使用TP的条件查询
            $attrTmp =  explode(',',I('get.attr'));
            //获取属性对应的商品ID
            $goods = M('GoodsAttr')->field('group_concat(goods_id) as goods_ids')->where(array('attr_values' => array('in',$attrTmp)))->find();
            //如果获取到了商品ID值开始拼接where条件语句
            if($goods['goods_ids']){
                $where .= " AND id in ({$goods['goods_ids']})";
            }
        }
        // dump($goods);exit;

        //3.实现分页数据
        $p = I('get.p');
        $pagesize = 2;
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$pagesize);
        //分页信息
        $show = $page->show();
        //四种排序方式,不存在时默认销量排序
        $sort = I('get.sort')?I('get.sort'):'sale_number';
        //分页记录
        $data = $this->page($p,$pagesize)->where($where)->order($sort.' desc,id asc')->select();
        //促销价格更改
        foreach ($data as $key => $value) {
            if($value['cx_price'] > 0 && $value['start'] < time() && $value['end'] > time()){
                $data[$key]['shop_price'] = $value['cx_price'].'(促销价)';
            }
        }
        return array(
            'data'      =>  $data,      //分页记录
            'show'      =>  $show,      //分页信息
            'price'     =>  $price,     //价格区间
            'attrwhere' =>  $attrwhere, //属性筛选
        );
    }
}