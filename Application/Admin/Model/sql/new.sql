CREATE TABLE `news` (
    `id` int(10) unsigned not null AUTO_INCREMENT,
    `title` varchar(100) not null default '',
    `content` text not null default '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 插入数据
insert into news values (null,'静态化','静态化可以减少服务器的压力');
insert into news values (null,'伪静态化','伪静态化可以更好的满足seo优化的要求');

-- 插入数据
insert into news values (null,'静态化','静态化可以减少服务器的压力'),(null,'伪静态化','伪静态化可以更好的满足seo优化的要求');