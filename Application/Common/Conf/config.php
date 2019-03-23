<?php
use Behavior\ShowPageTraceBehavior ;	//引用TP内置的调试工具
return array(
	//'配置项'=>'配置值'
	//URL模式设为重写模式
	'URL_MODEl' => 2,		//这样系统会自动生成rewrite模式的URL
	//设定默认访问模块
	'DEFAULT_MODULE' => 'Home',
	//设定允许访问的模块
	'MODULE_ALLOW_LIST'	=>array('Home','Admin'),
	// 模板地址替换 定义常量别名
	'TMPL_PARSE_STRING' =>array(
		//前台静态资源
		'__PUBLIC_HOME__'	=>	'/Public/Home',
		//后台静态资源
		'__PUBLIC_ADMIN__'	=>	'/Public/Admin',
	),
	/* 2.数据库设置 */
	'DB_TYPE'       =>  'mysql',         	// 数据库类型
	'DB_HOST'   	=>  '127.0.0.1',     	// 服务器地址
	'DB_NAME'    	=>  'jxshop',          	// 数据库名
	'DB_USER'      	=>  'root',          	// 用户名
	'DB_PWD' 		=>  'luo12345',         // 密码
	'DB_PORT'		=>  '3306',         	// 端口
	'DB_PREFIX'		=>  'jx_',         		// 数据库表前缀
	/* 3.开启TP自带的开发者工具 */
	'SHOW_PAGE_TRACE' => false,				// true开启调试工具，false关闭

	//session设置
	'SESSION_OPTIONS' => array(
		'name' 		=> 		'jx_session',		//session名称
		'expire' 	=> 		3600*24*3			//session保存时间
	),

);