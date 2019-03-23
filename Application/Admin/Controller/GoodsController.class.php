<?php
//命名空间
namespace Admin\Controller;
//字符编码
header('Content-type:text/html;charset=utf-8');
//自定义商品控制器
class GoodsController extends CommonController
{

    //显示类型对应属性的方法
    public function showAttr()
    {
        $type_id = intval(I('post.type_id'));
        if ($type_id <= 0) {
            echo '没有数据';
            exit();
        }
        $data = D('Attribute')->where('type_id=' . $type_id)->select();
        foreach ($data as $key => $value) {
            if ($value['attr_input_type'] == 2) {
                //列表选择,需要处理默认值
                $data[$key]['attr_value'] = explode(',', $value['attr_value']);
            }
        }
        $this->assign('data', $data);
        $this->display();
    }

    //实现商品入库的方法
    public function add()
    {
        if (IS_GET) {
            //传递类型信息
            $type = D('Type')->select();
            $this->assign('type', $type);
            //传递分类信息
            $cate = D('Category')->getCateTree();   //尽量节省变量
            $this->assign('cate', $cate);
            $this->display();
        } else {
            // dump(I('post.attr'));exit;   //商品属性中间表数据测试
            $model = D('Goods');
            $data = $model->create();
            //如果创建失败返回错误信息
            if (!$data) {
                $this->error($model->getError());
            }
            //创建成功开始数据入库
            $goods_id = $model->add($data);
            if (!$goods_id) {
                $this->error($model->getError());
            } else {
                $this->success('商品添加成功');
            }
        }
    }
    //editor显示
    public function demo()
    {
        $this->display();
    }
    //实现显示商品列表
    public function index()
    {
        //传递分类结构信息
        $cate = D('Category')->getCateTree();
        $this->assign('cate', $cate);
        //默认显示在售商品使用模型自定义的listData()方法获取
        $data = D('Goods')->listData();
        $this->assign('data', $data);
        $this->display();
    }
    //实现删除商品的功能
    public function del()
    {
        $goods_id = intval(I('get.goods_id'));
        if ($goods_id <= 0) {
            $this->error('参数错误');
        }
        $model = D('Goods');
        $rs = $model->setStatus($goods_id);
        if ($rs === false) {
            $this->error('删除失败');
        } else {
            $this->success('删除成功');
        }
    }
    //实现编辑商品的功能
    public function edit()
    {
        $model = D('Goods');
        if (IS_GET) {
            $goods_id = intval(I('get.goods_id'));
            $info = $model->findOneById($goods_id);
            //将预定义的 HTML 实体转换为字符
            $info['goods_body'] = htmlspecialchars_decode($info['goods_body']);
            $this->assign('info', $info);
            //格式化分类信息
            $cate = D('Category')->getCateTree();
            $this->assign('cate', $cate);
            //扩展分类信息
            $ext_cate = M('GoodsCate')->where("goods_id=$goods_id")->select();
            if (!$ext_cate) {
                $ext_cate = array('msg' => 'no data');
            }
            $this->assign('ext_cate', $ext_cate);            // dump($ext_cate);
            //类型信息
            $type = D('Type')->select();
            $this->assign('type', $type);
            //商品属性联表信息
            $attr = D('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_type,b.attr_input_type,b.attr_value')->join('left join jx_attribute b on a.attr_id = b.id')->where('goods_id=' . $goods_id)->select();
            foreach ($attr as $key => $value) {
                if ($value['attr_input_type'] == 2) {
                    $attr[$key]['attr_value'] = explode(',', $value['attr_value']);
                }
            }
            foreach ($attr as $key => $value) {
                $attr_list[$value['attr_id']][] = $value;
            }
            // dump($attr_list);exit;               //以下标为attr_id值为第一维的三维数组
            $this->assign('attr', $attr_list);
            //商品相册信息
            $goods_img_list = D('GoodsImg')->where('goods_id=' . $goods_id)->select();
            $this->assign('goods_img_list', $goods_img_list);
            $this->display();
        } else {
            $model = D('Goods');
            $data = $model->create();
            // dump(I('post.attr'));exit;       //商品属性中间表入库测试
            if (!$data) {
                $this->error($model->getError());
            }
            //如果是非推荐状态手动将值0添加到数据中
            //可以使用二元运算符方式 例如 $data['is_hot']==''? $data['is_hot']='0':$data['is_hot']='1';
            //也可以使用下面的多重判定
            if ($data['is_new'] == '') {
                $data['is_new'] = '0';
            } elseif ($data['is_rec'] == '') {
                $data['is_rec'] = '0';
            } elseif ($data['is_hot'] == '') {
                $data['is_hot'] = '0';
            }
            $rs = $model->update($data);
            if ($rs === false) {
                $this->error($model->getError());
            }
            $this->success('修改成功', U('index'));
        }
    }
    //实现回收站商品列表显示管理功能
    public function trash()
    {
        $cate = D('Category')->getCateTree();
        $this->assign('cate', $cate);        //分类信息
        $data = D('Goods')->listData(0);
        $this->assign('data', $data);        //商品信息
        $this->display();
    }
    //实现回收站还原商品功能
    public function recover()
    {
        $goods_id = intval(I('get.goods_id'));
        if ($goods_id <= 0) {
            $this->error('参数错误');
        }
        $rs = D('Goods')->setStatus($goods_id, 1);
        if ($rs === false) {
            $this->error('还原失败');
        } else {
            $this->success('还原成功');
        }
    }
    //实现彻底删除商品的功能
    public function remove()
    {
        $goods_id = intval(I('get.goods_id'));
        if ($goods_id <= 0) {
            $this->error('参数错误');
        }
        $rs = D('Goods')->remove($goods_id);
        if ($rs === false) {
            $this->error('删除失败');
        } else {
            $this->success('删除成功');
        }
    }

    // 实现删除商品相册的图片的功能
    public function delImg()
    {
        $img_id = intval(I('post.img_id'));
        $model = D('GoodsImg');
        //查找相关相册图片记录
        $info = $model->where('id=' . $img_id)->find();
        if (!$info) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '参数错误'));
        }
        //先删保存的图
        unlink($info['goods_img']);
        unlink($info['goods_thumb']);
        //再删记录
        $model->where('id=' . $img_id)->delete();
        $this->ajaxReturn(array('status' => 1, 'msg' => 'ok'));
    }

    // 实现库存设置
    public function setNumber()
    {
        $GoodsModel = D('Goods');
        $GoodsNumberModdel = D('GoodsNumber');
        if (IS_GET) {
            $goods_id = intval(I('goods_id'));
            if ($goods_id <= 0) {
                $this->error('参数错误', U('index'));
            }
            $GoodsAttrModel = D('GoodsAttr');
            $attr = $GoodsAttrModel->getSigleAttr($goods_id);       //自定义方法获取单选属性
            if (!$attr) {
                //如果该商品没有单选属性,显示另外的库存设置页面
                $info = $GoodsModel->where('id=' . $goods_id)->find();
                $this->assign('info', $info);
                $this->display('NoSigleNumber');
                exit();
            }
            //库存表信息
            $info = $GoodsNumberModdel->where('goods_id=' . $goods_id)->select();
            if (!$info) {
                //如果有单选属性的商品但库存还未设置的时要给予$info一个可循环一次的数组避免设置信息不显示
                $info = array('nodata' => 0);
            }
            // dump($info);
            $this->assign('info', $info);
            $this->assign('attr', $attr);
            $this->display();
        } else {
            //提交实现库存
            $attr = I('post.attr');
            $goods_number = I('post.goods_number');
            $goods_id = I('post.goods_id');
            if (!$attr) {
                //如果是非单选的库存设置直接写入商品表的库存字段
                $GoodsModel->where('id=' . $goods_id)->setField('goods_number', $goods_number);
                $this->success('库存设置成功', U('index'));
                exit();
            }
            // dump($attr);exit();
            // dump($goods_number);
            foreach ($goods_number as $key => $value) {
                $tmp = array();
                foreach ($attr as $k => $v) {
                    $tmp[] = $v[$key];
                }
                //对$tmp排序,避免属性组合a,b与b,a相同但不能去重的问题
                sort($tmp);             //!!!!!!!!!!!!!!!
                //数组拼接转换成字符串
                $goods_attr_ids = implode(',', $tmp);
                //组合去重
                if (in_array($goods_attr_ids, $has)) {
                    unset($goods_number[$key]);   //释放当前库存量值
                    continue;   //跳过循环
                }
                $has[] = $goods_attr_ids;
                $list[] = array(
                    'goods_id' => $goods_id,
                    'goods_attr_ids' => $goods_attr_ids,
                    'goods_number' => $value
                );
            }
            //删除旧记录
            $GoodsNumberModdel->where('goods_id=' . $goods_id)->delete();
            //库存入库
            $rs = $GoodsNumberModdel->addAll($list);
            if (!$rs) {
                $this->error('库存设置失败:' . $GoodsNumberModdel->getError());
            }
            //更新商品库存总数
            $goods_count = array_sum($goods_number);        //数组值求和函数
            $GoodsModel->where('id=' . $goods_id)->setField('goods_number', $goods_count);
            $this->success('库存设置成功', U('index'));       //成功跳转回商品页
        }
    }
    // 测试方法
    public function volist(){
        $data = D('Goods')->select();
        // $data = [
        //     ['goods_name' => 'luo'],
        //     ['goods_name' => 'yong'],
        //     ['goods_name' => 'kang'],
        // ];
        $this->assign('data',$data);
        $this->display();

    }
}