CREATE TABLE jx_goods_cate(
  id int(11) NOT NULL AUTO_INCREMENT,
  goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID标识',
  cate_id smallint(6) NOT NULL DEFAULT 0 COMMENT '分类ID标识',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;