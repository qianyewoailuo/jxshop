<?php
//命名空间
namespace Admin\Model;
//自定义公共模型
class CategoryModel extends CommonModel
{
    //定义主键
    protected $pk = 'id';   //当主键名为id时，其实可以不用定义主键，TP默认就是id
    //自定义字段
    protected $fields = array('id','cname','parent_id','isrec');
    //自动验证规则
    protected $_validate = array(
        array('cname','require','分类名称必须填写'),
    );
    /**
     * getChildren() 获取某个分类的子分类
     * @param $id 分类id即是cate_id
     */
    public function getChildren($id)    
    {
        //先获取分类信息
        $data = $this->select();
        //再格式化分类信息
        $list = $this->getTree($data,$id,1,false);  //查找格式化分类信息
        //最后获取分类下子分类id
        foreach($list as $key => $value){
            $tree[] = $value['id'];
        }
        return $tree;
    }

    /**
     * getCateTree() 获取格式化分类信息方法
     * @param $id 从第几级分类开始获取 默认0为顶级分类
     */
    public function getCateTree($id=0)      //$id=0表示默认从顶级分类开始获取
    {
        //先获取分类信息
        $data = $this->select();
        //再格式化分类信息
        $list = $this->getTree($data,$id);  //查找格式化分类信息
        //测试查看$list
        // dump($list);
        return $list;
    }
    //格式化分类信息的方法
    /**
     * getTree 格式化分类信息的方法
     * @param $data 所有的记录数据
     * @param $id 从第几级分类开始获取 默认0为顶级分类
     * @param $lev 层次等级，用于分级显示 
     * @param $iscache 是否使用静态变量缓存
     */
    public function getTree($data,$id=0,$lev=1,$iscache=true)
    {
        static $list = array();
        //不使用静态变量只获取子分类
        if(!$iscache){
            $list = array();
        }
        foreach ($data as $key => $value){
            if($value['parent_id'] == $id){
                $value['lev'] = $lev;
                $list[] = $value;
                //递归循环获取子分类
                $this->getTree($data,$value['id'],$lev+1);
            }
        }
        return $list;
    }
    /**
     * del($id) 删除分类商品前处理的方法
     */
    public function del($id)
    {
        $rs = $this->where('parent_id='.$id)->find();
        if($rs){
            return false;
        }
        return $this->where('id='.$id)->delete();
    }
    /**
     * update($data) 修改分类商品前判断是否容许处理的方法
     */
    public function update($data)
    {
        //当修改的数据其父类是其本身或是其子类返回错误提示，不容许修改
        //开始修改前进行逻辑判断
        $tree = $this->getCateTree($data['id']);     //获取当前数据下的子分类信息
        //从逻辑原则上，不容许修改的父类还应有其本身
        $tree[] = array('id'=>$data['id']);   
        // dump($tree);exit();      //测试数据
        foreach($tree as $key =>$value){
            if($data['parent_id']==$value['id']){
                $this->error = "不能设置上级分类为子类或其本身";      //在模型里的属性error用来保存自定义错误信息
                return false;
            }
        }
        //容许修改返回结果
        return $this->save($data);
    }

    //增删查改外的其他功能--------------------------------------------------------

    //获取子分类
    public function getChildAll($id)
    {
        $data = $this->select();
        return $list = $this->getTree($data,$id,1,false);
    }
    //根据分类ID获取相关联的商品信息
    public function getGoodsByCateId($cate_id,$limit=8)
    {
        //1.获取当前分类下面的子分类信息
        $children = $this->getChildren($cate_id);
        //2.添加当前分类ID
        $children[] = $cate_id;
        //3.转换成字符串使用in语句获取商品信息
        $children = implode(',',$children);
        $goods = D('Goods')->where("is_sale=1 AND cate_id in ($children)")->limit($limit)->select();
        return $goods;
    }

    //获取楼层分类以及商品信息
    public function getFloor()
    {
        //获取顶级分类
        $data = $this->where('parent_id=0')->select();      
        //遍历顶级分类获取二级分类
        foreach ($data as $key => $value) {
            //获取二级分类信息
            $data[$key]['children'] = $this->where('parent_id='.$value['id'])->select();
            //获取推荐二级分类信息
            $data[$key]['rec_children'] = $this->where('isrec=1 AND parent_id='.$value['id'])->select();
            //获取二级分类关联商品的信息
            foreach ($data[$key]['rec_children'] as $k => $v) {
                //$v代表的是每一个推荐的分类信息
                $data[$key]['rec_children'][$k]['goods'] = $this->getGoodsByCateId($v['id']);
            }
        }
        // dump($data);
        return $data;
    }
}
