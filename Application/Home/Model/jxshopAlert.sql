  `cx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `start` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `sale_number` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `plcount` int(11) NOT NULL DEFAULT '0' COMMENT '评论量',
  `end` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',

-- 新增促销字段:促销价格 促销开始时间 促销结束时间  (使用分号逐条修改)

  ALTER TABLE jx_goods add COLUMN `cx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格';
  ALTER TABLE jx_goods add COLUMN `start` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间';
  ALTER TABLE jx_goods add COLUMN `end` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间';

  ALTER TABLE jx_goods add COLUMN `sale_number` int(11) NOT NULL DEFAULT '0' COMMENT '销量';
  ALTER TABLE jx_goods add COLUMN `total_comment` int(11) NOT NULL DEFAULT '0' COMMENT '评论量';

  ALTER TABLE jx_user add COLUMN `tel` char(11) NOT NULL DEFAULT '' COMMENT '手机号';
  ALTER TABLE jx_user add COLUMN `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态,0表示未激活,1表示已激活';
  ALTER TABLE jx_user add COLUMN `email` char(25) NOT NULL DEFAULT '' COMMENT 'email邮箱';
  ALTER TABLE jx_user add COLUMN `active_code` char(25) NOT NULL DEFAULT '' COMMENT '激活码';
