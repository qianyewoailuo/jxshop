-- 管理员表
CREATE TABLE jx_admin(
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  password char(32) NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

-- 角色表
CREATE TABLE jx_role(
  id int(11) NOT NULL AUTO_INCREMENT,
  role_name varchar(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

-- 管理员_角色中间表
CREATE TABLE jx_admin_role(
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
  role_id int(11) NOT NULL DEFAULT 0 COMMENT '角色ID',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;

-- 权限表:
/**
 *  权限名称
 *  权限对应的 - 模型 - 控制器 - 方法 名称
 *  上级权限
 *  权限是否在导航菜单中显示(例如删除和编辑是不能在导航菜单中显示的)
 */
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

-- 角色_权限中间表

CREATE TABLE jx_role_rule(
  id int(11) NOT NULL AUTO_INCREMENT,
  role_id int(11) NOT NULL DEFAULT 0 COMMENT '角色ID',
  rule_id int(11) NOT NULL DEFAULT 0 COMMENT '权限ID',
  PRIMARY KEY(id)
)ENGINE= InnoDB DEFAULT CHARSET=utf8;























































