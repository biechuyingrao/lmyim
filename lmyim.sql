/*
Navicat MySQL Data Transfer

Source Server         : 本地windows
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : lmyim

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-06-13 08:56:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lmy_ucenter_member
-- ----------------------------
DROP TABLE IF EXISTS `lmy_ucenter_member`;
CREATE TABLE `lmy_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `login` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of lmy_ucenter_member
-- ----------------------------
INSERT INTO `lmy_ucenter_member` VALUES ('2', '大白菜', 'liulong217', '335111164@qq.com', '', '1497247329', '127.0.0.1', '1', '1497247334', '127.0.0.1', '1497247329', '1497247334', '1');
INSERT INTO `lmy_ucenter_member` VALUES ('3', 'sss', 'liulong217', '1549441501@qq.com', '', '1497248524', '127.0.0.1', '1', '1497248540', '127.0.0.1', '1497248524', '1497248540', '1');
SET FOREIGN_KEY_CHECKS=1;

-- ----------------------------
-- Table structure for lmy_group
-- ----------------------------
DROP TABLE IF EXISTS `lmy_group`;
CREATE TABLE `lmy_group` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组ID',
  `title` VARCHAR(20) NOT NULL COMMENT '用户组标题',
  `notice` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '分组公告',
  `logo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '分组logo',
  `create_uid` INT(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建者ID',
  `create_time` INT(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` INT(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status`  tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `num` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户数',
  PRIMARY KEY (`id`) 
) ENGINE=MYISAM DEFAULT CHARSET=UTF8 COMMENT='用户组表';

-- ----------------------------
-- Table structure for lmy_action_log
-- ----------------------------
DROP TABLE IF EXISTS `lmy_action_log`;
CREATE TABLE `lmy_action_log` (

);
