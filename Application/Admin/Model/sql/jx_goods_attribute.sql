-- 商品属性的数据表
-- id
-- attr_name          属性名称
-- type_id            属性对应的类型id
-- attr_type          属性的类型
-- attr_input_type    属性值录入方式
-- attr_value         列表选择的默认值

CREATE TABLE jx_attribute(
  id int(11) NOT NULL AUTO_INCREMENT,
  attr_name varchar(30) NOT NULL DEFAULT '' COMMENT '属性名称',
  type_id int(11) NOT NULL DEFAULT 0 COMMENT '属性所对应的类型',
  attr_type tinyint(1) NOT NULL DEFAULT 1 COMMENT '表示属性的类型 1表示唯一 2表示单选',
  attr_input_type tinyint(1) NOT NULL DEFAULT 1 COMMENT '表示属性值的录入方法 1表示手工输入 2表示列表选择',
  attr_value varchar(255) NOT NULL DEFAULT '' COMMENT '列表选择的默认值 多个值之间使用逗号隔开',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;