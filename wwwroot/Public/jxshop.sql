#创建数据库
create database jxshop;

#创建分类数据表
CREATE TABLE `jx_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cname` char(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '分类的父分类ID',
  `isrec` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否推荐 0表示不推荐1表示推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#创建商品数据表
CREATE TABLE `jx_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_sn` char(30) NOT NULL DEFAULT '' COMMENT '商品货号',
  `cate_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '商品分类ID 对于category表中的ID字段',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场售价',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店售价',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品缩略图',
  `goods_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '商品缩略小图',
  `goods_body` text COMMENT '商品描述',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖 1表示热卖 0表示不是',
  `is_rec` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否推荐 1表示推荐 0表示不推荐',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖 1表示新品 0表示不是',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `isdel` tinyint(4) NOT NULL DEFAULT '1' COMMENT '表示商品是否删除 1正常 0删除状态',
  `is_sale` tinyint(4) NOT NULL DEFAULT '1' COMMENT '商品是否销售 1销售 0下架状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_sn` (`goods_sn`) USING BTREE,
  KEY `goods_name` (`goods_name`) USING BTREE,
  KEY `isdel` (`isdel`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#创建商品扩展分类的中间表
CREATE TABLE jx_goods_cate(
  id int(11) NOT NULL AUTO_INCREMENT,
  goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID标识',
  cate_id smallint(6) NOT NULL DEFAULT 0 COMMENT '分类ID标识',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

#管理员表
CREATE TABLE jx_admin(
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  password char(32) NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#角色表
CREATE TABLE jx_role(
  id int(11) NOT NULL AUTO_INCREMENT,
  role_name varchar(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#管理员角色中间表
CREATE TABLE jx_admin_role(
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  role_id int(11) NOT NULL DEFAULT 0 COMMENT '角色ID',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#权限表
CREATE TABLE jx_rule(
  id int(11) NOT NULL AUTO_INCREMENT,
  rule_name varchar(30) NOT NULL DEFAULT '' COMMENT '权限名称',
  module_name varchar(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  controller_name varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  action_name varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  parent_id int(11) not NULL DEFAULT 0 COMMENT '上级权限ID 0表示顶级权限',
  is_show tinyint(1) not NULL DEFAULT 1 COMMENT '是否导航菜单显示1  显示 0 不显示',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#角色权限表
CREATE TABLE jx_role_rule(
  id int(11) NOT NULL AUTO_INCREMENT,
  role_id int(11) NOT NULL DEFAULT 0 COMMENT '角色ID',
  rule_id int(11) NOT NULL DEFAULT 0 COMMENT '权限ID',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#商品类型的数据表
CREATE TABLE jx_type(
  id int(11) NOT NULL AUTO_INCREMENT,
  type_name varchar(30) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#商品属性的数据表
CREATE TABLE jx_attribute(
  id int(11) NOT NULL AUTO_INCREMENT,
  attr_name varchar(30) NOT NULL DEFAULT '' COMMENT '属性名称',
  type_id int(11) NOT NULL DEFAULT 0 COMMENT '属性所对应的类型',
  attr_type tinyint(1) NOT NULL DEFAULT 1 COMMENT '表示属性的类型 1表示唯一 2表示单选',
  attr_input_type tinyint(1) NOT NULL DEFAULT 1 COMMENT '表示属性值的录入方法 1表示手工输入 2表示列表选择',
  attr_value varchar(255) NOT NULL DEFAULT '' COMMENT '列表选择的默认值 多个值之间使用逗号隔开',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
#修改商品数据表结构
ALTER TABLE jx_goods ADD COLUMN type_id int(11) NOT NULL DEFAULT 0 COMMENT '商品对应的类型ID'
#商品属性中间表
CREATE TABLE jx_goods_attr(
  id int(11) NOT NULL AUTO_INCREMENT,
  goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID',
  attr_id int(11) NOT NULL DEFAULT 0 COMMENT '属性ID',
  attr_values varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

#商品相册数据表
CREATE TABLE jx_goods_img(
  id int(11) NOT NULL AUTO_INCREMENT,
  goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID',
  goods_img varchar(255) NOT NULL DEFAULT '' COMMENT '相册图片',
  goods_thumb varchar(255) NOT NULL DEFAULT '' COMMENT '相册小图',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

#修改商品表增加商品总数 
ALTER TABLE jx_goods add COLUMN goods_number int(11) NOT NULL DEFAULT 0 COMMENT '商品个数'
#商品货品表
CREATE TABLE `jx_goods_number` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品的id',
  `goods_attr_ids` varchar(32) NOT NULL COMMENT '属性信息多个属性使用逗号隔开',
  `goods_number` int(11) NOT NULL DEFAULT '0' COMMENT '货品的库存',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
#修改商品表增加促销
ALTER TABLE jx_goods add COLUMN `cx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格';
ALTER TABLE jx_goods add COLUMN `start` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间';
ALTER TABLE jx_goods add COLUMN `end` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间';

#创建用户表
CREATE TABLE `jx_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '盐',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表';
#创建购物车数据表
CREATE TABLE `jx_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '登录的用户id',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_attr_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '商品属性id每个属性逗号隔开,关联jx_goods_attr表中的id',
  `goods_count` int(11) NOT NULL DEFAULT '0' COMMENT '商品购买数量',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#创建订单表
CREATE TABLE `jx_order` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '定单总价',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 1、已经支付0 未支付',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `address` varchar(150) NOT NULL DEFAULT '' COMMENT '收货人详细地址',
  `tel` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='定单';

#创建订单商品详情表
CREATE TABLE `jx_order_goods` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '定单Id',
  `goods_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品Id',
  `goods_attr_ids` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性Id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品的价格',
  `goods_count` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='定单商品';
#创建商品评论表
CREATE TABLE `jx_comment` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(8) unsigned NOT NULL COMMENT '会员id',
  `goods_id` int(8) unsigned NOT NULL COMMENT '商品id',
  `addtime` int(10) unsigned NOT NULL COMMENT '评论时间',
  `content` varchar(255) NOT NULL COMMENT '评论的内容',
  `star` tinyint(1) unsigned NOT NULL COMMENT '评论的分值',
  `good_number` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '有用的数字',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论';
#创建印象表
CREATE TABLE `jx_impression` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `name` varchar(30) NOT NULL DEFAULT '0' COMMENT '印象名称',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '印象出现的次数',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='印象';
#修改商品表结构增加字段
ALTER TABLE jx_goods add COLUMN `plcount` int(11) NOT NULL DEFAULT '0' COMMENT '评论总数量';
ALTER TABLE jx_goods add COLUMN `sale_number` int(11) NOT NULL DEFAULT '0' COMMENT '总销量';
#修改会员表结构增加字段
ALTER TABLE jx_user add COLUMN `tel` char(11) NOT NULL DEFAULT '' COMMENT '手机号码';
ALTER TABLE jx_user add COLUMN `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户状态 0未激活 1、已激活';

ALTER TABLE jx_user add COLUMN `email` char(25) NOT NULL DEFAULT '' COMMENT '手机号码';
ALTER TABLE jx_user add COLUMN `active_code` char(25) NOT NULL DEFAULT '' COMMENT '邮件激活码';

#修改订单表增加字段
ALTER TABLE jx_order add COLUMN `com` varchar(255) NOT NULL DEFAULT '' COMMENT '快递公司编号';
ALTER TABLE jx_order add COLUMN `no` varchar(255) NOT NULL DEFAULT '' COMMENT '快递单号';
ALTER TABLE jx_order add COLUMN `order_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '快递状态1未发货2、以发货';

ALTER TABLE jx_user add COLUMN `openid` varchar(255) NOT NULL DEFAULT '' COMMENT 'QQ登录的唯一标识';
