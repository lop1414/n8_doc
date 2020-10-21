/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50724
Source Host           : localhost:3306
Source Database       : n8_doc

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2020-10-21 15:27:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '状态',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序值',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='文章表';
