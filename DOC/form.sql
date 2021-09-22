--
-- 业务中台（子应用数据库配置表）
--
DROP TABLE IF EXISTS `zt_form`;
CREATE TABLE `zt_form` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `form_name` varchar(32) NOT NULL COMMENT '名称',
   `table_name` varchar(32) NOT NULL COMMENT '表名称',
   `user_id` varchar(32) DEFAULT NULL COMMENT '创建用户ID',
   `config` text COMMENT '表单配置',
   `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0有效、1无效',
   `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
   `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
   `deleted` enum('0','1') NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COMMENT='表信息';

--
-- 业务中台（字段配置）
--
DROP TABLE IF EXISTS `zt_form_field`;
CREATE TABLE `zt_form_field` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `field_name` varchar(64) NOT NULL COMMENT '字段名称',
    `now_field_name` varchar(64) DEFAULT NULL COMMENT '当前字段名称',
    `original_id` int(10) DEFAULT NULL COMMENT '追溯原始字段ID',
    `form_id` int(11) NOT NULL COMMENT '对应表单ID',
    `table_name` varchar(32) NOT NULL COMMENT '表名称',
    `type` varchar(32) NOT NULL COMMENT '字段类型',
    `user_id` varchar(32) DEFAULT NULL COMMENT '创建用户ID',
    `sort` int(11) DEFAULT NULL COMMENT '排序',
    `config` text COMMENT '字段配置',
    `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0有效、1无效',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
    `deleted` enum('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COMMENT='扩展字段基础';

--
-- 业务中台（子应用数据库配置表）
--
DROP TABLE IF EXISTS `zt_form_history`;
CREATE TABLE `zt_form_history` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `form_id` int(10) NOT NULL COMMENT '表单ID',
    `live_id` int(10) NOT NULL COMMENT '记录ID',
    `config` text COMMENT '表单配置',
    `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态，0有效、1无效',
    `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
    `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
    `deleted` enum('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COMMENT='提交表单时配置记录';
