-- 商品相册数据表

CREATE TABLE jx_goods_img(
  id int(11) NOT NULL AUTO_INCREMENT,
  goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID',
  goods_img varchar(255) NOT NULL DEFAULT '' COMMENT '相册图片',
  goods_thumb varchar(255) NOT NULL DEFAULT '' COMMENT '相册小图',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;
