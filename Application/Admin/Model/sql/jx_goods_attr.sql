-- 商品属性中间表
CREATE TABLE jx_goods_attr(
    id int(11) NOT NULL AUTO_INCREMENT,
    goods_id int(11) NOT NULL DEFAULT 0 COMMENT '商品ID',
    attr_id int(11) NOT NULL DEFAULT 0 COMMENT '属性ID',
    attr_values varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
    PRIMARY KEY(id)
)ENGINE = InooDB DEFAULT CHARSET=utf8;