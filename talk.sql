/*
Navicat MySQL Data Transfer

Source Server         : 115
Source Server Version : 50558
Source Host           : 115.159.199.89:3306
Source Database       : talk

Target Server Type    : MYSQL
Target Server Version : 50558
File Encoding         : 65001

Date: 2018-08-28 19:26:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL COMMENT '别名',
  `sex` int(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `email` varchar(20) NOT NULL DEFAULT '' COMMENT 'email电子邮箱',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT 'role 角色分组外键',
  `salt` int(11) NOT NULL DEFAULT '0' COMMENT '盐值',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态 0 删除 1 正常',
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `账号唯一` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for chat_record
-- ----------------------------
DROP TABLE IF EXISTS `chat_record`;
CREATE TABLE `chat_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `to_id` int(11) unsigned NOT NULL,
  `data` text NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for friend
-- ----------------------------
DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `f_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for group
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gnumber` int(11) NOT NULL COMMENT '群号',
  `user_number` int(11) NOT NULL COMMENT '创建人',
  `groupname` varchar(255) NOT NULL DEFAULT '' COMMENT '群名称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '群头像',
  `ginfo` varchar(255) NOT NULL DEFAULT '',
  `gname` varchar(20) DEFAULT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for group_chat_record
-- ----------------------------
DROP TABLE IF EXISTS `group_chat_record`;
CREATE TABLE `group_chat_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `gnumber` int(11) NOT NULL,
  `data` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for group_member
-- ----------------------------
DROP TABLE IF EXISTS `group_member`;
CREATE TABLE `group_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gnumber` int(11) NOT NULL,
  `user_number` int(11) NOT NULL,
  `creater_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for group_user
-- ----------------------------
DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id外键',
  `groupname` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分组名',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0 删除 1 正常',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COMMENT='用户分组';

-- ----------------------------
-- Table structure for group_user_member
-- ----------------------------
DROP TABLE IF EXISTS `group_user_member`;
CREATE TABLE `group_user_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `groupid` int(11) unsigned NOT NULL COMMENT '好友分组外键',
  `friend_id` int(11) unsigned NOT NULL COMMENT '好友外键id',
  `remark_name` varchar(50) NOT NULL DEFAULT '' COMMENT '备注名',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0 删除 1 正常',
  `creater_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `好友只能存在一个` (`user_id`,`friend_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='好友分组里的成员';

-- ----------------------------
-- Table structure for msg_box
-- ----------------------------
DROP TABLE IF EXISTS `msg_box`;
CREATE TABLE `msg_box` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL COMMENT '消息类型 1好友添加 2系统消息3 加群消息',
  `from` int(11) NOT NULL COMMENT '消息发送方的id',
  `to` int(11) NOT NULL COMMENT '消息接受方id',
  `group_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组id',
  `handle` varchar(50) DEFAULT NULL COMMENT '群管理员名称',
  `group_name` varchar(50) DEFAULT NULL COMMENT '群名称',
  `status` int(1) NOT NULL COMMENT '消息状态 2 统一好友申请 4 拒绝好友申请',
  `remark` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '备注',
  `send_time` int(11) NOT NULL COMMENT '发送时间',
  `read_time` int(11) NOT NULL COMMENT '阅读时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `rules` varchar(255) NOT NULL DEFAULT '' COMMENT '权限规则外键id集合',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '分组状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for rule
-- ----------------------------
DROP TABLE IF EXISTS `rule`;
CREATE TABLE `rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '子类id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '规则 如 Index/index 首页访问',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '分状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `birthday` varchar(50) NOT NULL DEFAULT '' COMMENT '生日日期',
  `blood_type` varchar(50) NOT NULL DEFAULT '' COMMENT '血型',
  `job` int(10) NOT NULL COMMENT '职业',
  `qq` int(15) NOT NULL COMMENT 'qq号码',
  `wechat` varchar(50) NOT NULL DEFAULT '' COMMENT '微信号',
  `phone` int(13) NOT NULL COMMENT '手机号',
  `sign` varchar(255) NOT NULL DEFAULT '' COMMENT '签名',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别',
  `avatar` varchar(255) DEFAULT '/upload/jpg/20180819/B819760008137417.jpg' COMMENT '头像',
  `last_login` int(11) DEFAULT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number_union` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for uv
-- ----------------------------
DROP TABLE IF EXISTS `uv`;
CREATE TABLE `uv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL DEFAULT '',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
