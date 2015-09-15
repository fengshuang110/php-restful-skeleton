/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : yidiano2o

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-09-15 14:10:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu` (
  `menu_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `name` varchar(255) DEFAULT NULL,
  `is_show` tinyint(1) DEFAULT NULL COMMENT '是否显示',
  `parent_id` int(6) DEFAULT NULL,
  `controller` varchar(64) DEFAULT NULL,
  `action` varchar(64) DEFAULT NULL,
  `icon_class` varchar(64) DEFAULT NULL COMMENT '图标class',
  `parent_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
INSERT INTO `sys_menu` VALUES ('1', '权限管理', '1', '0', 'permission', 'lists', 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('2', '角色管理', '1', '0', 'role', 'lists', 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('3', '精细化营销', '1', '0', 'index', 'index', 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('4', '活动', '1', '3', 'index', 'index', 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('5', '用户管理', '1', '0', 'user', 'lists', 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('6', '红包管理', '1', '3', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('7', '推荐商品', '1', '3', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('8', '微信管理', '1', '0', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('9', '消息回复', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('10', '菜单管理', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('11', '分组管理', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('12', '用户管理', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('13', '素材管理', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('14', '图文消息', '1', '8', null, null, 'glyphicon-phone-alt', null);
INSERT INTO `sys_menu` VALUES ('15', '二维码管理', '1', '8', null, null, 'glyphicon-phone-alt', null);

-- ----------------------------
-- Table structure for sys_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) DEFAULT NULL COMMENT '角色名称',
  `desc` varchar(2000) DEFAULT NULL COMMENT '权限菜单id',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_role
-- ----------------------------
INSERT INTO `sys_role` VALUES ('3', '测试122', ' 测试1 ');
INSERT INTO `sys_role` VALUES ('4', '大大1', ' 大大 ');

-- ----------------------------
-- Table structure for sys_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `realname` varchar(32) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_user
-- ----------------------------
INSERT INTO `sys_user` VALUES ('1', 'fs@qq.com', '123456', '15501106810', '丰霜', '1');
INSERT INTO `sys_user` VALUES ('2', '212', null, '212', '2121', '3');
