-- 商品类型数据表
CREATE TABLE jx_type(
  id int(11) NOT NULL AUTO_INCREMENT,
  type_name varchar(30) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
