--
-- 业务中台（子应用数据库配置表）
--
DROP TABLE IF EXISTS `zt_app_database`;
CREATE TABLE `zt_app_database` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `app_id` int(11) NOT NULL COMMENT '应用ID',
   `host` varchar(32) NOT NULL COMMENT '地址',
   `port` varchar(32) NOT NULL COMMENT '端口',
   `database` varchar(32) NOT NULL COMMENT '数据库',
   `user_name` varchar(32) NOT NULL COMMENT '用户名',
   `user_password` varchar(32) NOT NULL COMMENT '密码',
   `prefix` varchar(32) DEFAULT NULL COMMENT '表前缀',
   `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0有效、1无效',
   `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
   `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21201 DEFAULT CHARSET=utf8mb4 COMMENT='应用信息数据库配置表';

CREATE TABLE `wpg_app_field` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `app` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
    `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `field` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
    `builtin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用内置字段',
    `required` tinyint(1) DEFAULT '0' COMMENT '是否必须',
    `is_keyfield` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为关键字段',
    `tip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '字段提示',
    `default` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '为空时的默认值',
    `sequence` int(10) DEFAULT '0',
    `errormsg` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
    `config` text COLLATE utf8_unicode_ci COMMENT '字段配置',
    `display_enabled` tinyint(1) DEFAULT '0' COMMENT '后台列表显示',
    `display_sequence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '列表排序号',
    `post` tinyint(1) DEFAULT '0' COMMENT '前台提交',
    `search_enabled` tinyint(1) DEFAULT '0' COMMENT '后台搜索',
    `search_sequence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索列表序号',
    `ordering_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台可排序',
    `ordering_sequence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序列表序号',
    `graph_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '统计图表（0：不支持,1:饼图,2:柱状图）',
    `graph_sequence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '统计显示序列号',
    `form` tinyint(1) NOT NULL DEFAULT '1' COMMENT '后台录入',
    PRIMARY KEY (`id`),
    KEY `field` (`field`),
    KEY `sequence` (`sequence`),
    KEY `fieldset_id` (`app`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='扩展字段基础';
