/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50722
Source Host           : localhost:3306
Source Database       : ly_finance

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2018-05-21 14:39:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lyf_article
-- ----------------------------
DROP TABLE IF EXISTS `lyf_article`;
CREATE TABLE `lyf_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` text COMMENT '文章正文',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '栏目id, 单选栏目时使用',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='文章表';

-- ----------------------------
-- Records of lyf_article
-- ----------------------------
INSERT INTO `lyf_article` VALUES ('1', '啊啊啊', '巴巴爸爸', '1', '2018-05-14 17:56:01', '2018-05-14 17:56:04');

-- ----------------------------
-- Table structure for lyf_category
-- ----------------------------
DROP TABLE IF EXISTS `lyf_category`;
CREATE TABLE `lyf_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_category_id` int(11) DEFAULT '0' COMMENT '父ID',
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `category_content` text,
  `category_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态： 0草稿中  1正常中',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='频道表';

-- ----------------------------
-- Records of lyf_category
-- ----------------------------
INSERT INTO `lyf_category` VALUES ('1', '0', '擦擦擦', '111', '1');

-- ----------------------------
-- Table structure for lyf_dict_bank
-- ----------------------------
DROP TABLE IF EXISTS `lyf_dict_bank`;
CREATE TABLE `lyf_dict_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '银行名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='词典表-银行';

-- ----------------------------
-- Records of lyf_dict_bank
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_manager
-- ----------------------------
DROP TABLE IF EXISTS `lyf_manager`;
CREATE TABLE `lyf_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT '' COMMENT '用户名',
  `password` varchar(255) DEFAULT '',
  `last_ip` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `status` tinyint(4) DEFAULT '1' COMMENT '0 禁用 1启用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_username` (`username`) USING BTREE,
  UNIQUE KEY `uni_email` (`email`),
  UNIQUE KEY `uni_mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='后台管理员表';

-- ----------------------------
-- Records of lyf_manager
-- ----------------------------
INSERT INTO `lyf_manager` VALUES ('1', 'admin', '$2y$10$7Q2d9w.gjO62tidZ6Qo7EOMIApoGHhMVnh2zF.eJvCBXsJwGKiuAO', '127.0.0.1', '2018-05-16 16:42:48', '2018-05-16 17:45:13', 'admin@sina.com', '13671197590', '1');

-- ----------------------------
-- Table structure for lyf_system_message
-- ----------------------------
DROP TABLE IF EXISTS `lyf_system_message`;
CREATE TABLE `lyf_system_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息正文',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息类型 0系统消息 1交易消息 2用户消息',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统 消息表，当用户登录时，insert到用户消息表';

-- ----------------------------
-- Records of lyf_system_message
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user`;
CREATE TABLE `lyf_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `last_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '上次登录ip',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0禁用 1正常',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '加密后的user_id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_username` (`username`),
  UNIQUE KEY `uni_email` (`email`),
  UNIQUE KEY `uni_mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of lyf_user
-- ----------------------------
INSERT INTO `lyf_user` VALUES ('26', 'dylan', 'f7acbe86594e0b18d830278fae1aab3f70fc36ebec0cd9655cbd5c1e55ec0622da44109ff958d66f9a50c06cc1774bb6', '$2y$10$UEh0LX/7LlKnZeJYa5TR1uFMPqKHuUmLQoVySX0kDoX5MSlgiT40G', '', '', '2018-05-17 16:13:06', '2018-05-17 16:13:06', '', '1', '22429c2fd957c92b450ab20a12eb3d3e');

-- ----------------------------
-- Table structure for lyf_user_balance
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_balance`;
CREATE TABLE `lyf_user_balance` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `balance` bigint(20) DEFAULT '0' COMMENT '账户余额',
  `freeze` bigint(20) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户 余额\r\n仅供查看余额、冻结资金等状态信息，不提供交易记录';

-- ----------------------------
-- Records of lyf_user_balance
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_balance_log
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_balance_log`;
CREATE TABLE `lyf_user_balance_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '交易类型 0未知 1充值成功 2提现成功 3收账入款 4冻结金额 5提现失败 6充值失败',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `total` bigint(20) DEFAULT '0' COMMENT '金额，单位元，无小数',
  `remark` varchar(255) DEFAULT '' COMMENT '交易备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易记录表/用户资金变更表';

-- ----------------------------
-- Records of lyf_user_balance_log
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_bank
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_bank`;
CREATE TABLE `lyf_user_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_number` varchar(255) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_id` int(11) NOT NULL DEFAULT '0' COMMENT '银行id',
  `bank_name` varchar(255) NOT NULL DEFAULT '' COMMENT '银行名称<选填>',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号码',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `is_delete` tinyint(4) DEFAULT '0' COMMENT '是否被删除 0整除 1已删除。如果涉及交易记录，只能软删除',
  `detelte_time` datetime DEFAULT NULL COMMENT '删除时的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户 绑定的银行卡信息\r\n关联键{user_bank.bank_id=dict_bank.id}';

-- ----------------------------
-- Records of lyf_user_bank
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_company
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_company`;
CREATE TABLE `lyf_user_company` (
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT '' COMMENT '企业名',
  `company_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '企业类型 1 企业、2 个体商户、3 机关事业单位、4 金融机构',
  `legal_representative` varchar(255) DEFAULT '' COMMENT '法定代表人姓名',
  `id_number` varchar(255) DEFAULT '' COMMENT '身份证号码',
  `mobile` varchar(255) DEFAULT '' COMMENT '法定代表人手机号',
  `income_last_year` int(11) DEFAULT '0' COMMENT '最近一年营业收入（万元）',
  `written_by` varchar(255) DEFAULT '' COMMENT '填写人身份',
  `status` tinyint(4) DEFAULT '0' COMMENT '认证状态 0默认 1审核通过 2审核失败',
  `attachment_1` varchar(255) DEFAULT '' COMMENT '营业执照 图片路径',
  `attachment_2` varchar(255) DEFAULT '' COMMENT '组织机构代码证 图片路径',
  `attachment_3` varchar(255) DEFAULT '' COMMENT '开户许可证 图片路径',
  `attachment_4` varchar(255) DEFAULT '' COMMENT '税务登记证 图片路径'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户 认证信息 企业信息';

-- ----------------------------
-- Records of lyf_user_company
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_detail
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_detail`;
CREATE TABLE `lyf_user_detail` (
  `user_id` int(11) NOT NULL,
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `wechat` varchar(255) DEFAULT '' COMMENT '微信',
  `sex` tinyint(4) DEFAULT '0' COMMENT '0未知 1男 2女'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户 详情表';

-- ----------------------------
-- Records of lyf_user_detail
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_file
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_file`;
CREATE TABLE `lyf_user_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件存储路径和文件名',
  `hash` varchar(255) NOT NULL DEFAULT '' COMMENT '文件hash值',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` datetime DEFAULT NULL,
  `mime` varchar(255) NOT NULL DEFAULT '' COMMENT 'content-type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='用户上传文件';

-- ----------------------------
-- Records of lyf_user_file
-- ----------------------------
INSERT INTO `lyf_user_file` VALUES ('6', '20180518\\b2e249a663d9947dc3019f738a89c3d5.jpg', '076e3caed758a1c18c91a0e9cae3368f', '26', '2018-05-18 13:13:41', 'image/jpeg');
INSERT INTO `lyf_user_file` VALUES ('7', '20180518\\37f1e153da2cb6288fbd3982822b4d11.jpg', '076e3caed758a1c18c91a0e9cae3368f', '26', '2018-05-18 15:08:29', 'image/jpeg');
INSERT INTO `lyf_user_file` VALUES ('8', '20180518\\7574312de6a1be7b3bf4095930971cc9.jpg', '076e3caed758a1c18c91a0e9cae3368f', '26', '2018-05-18 16:11:02', 'image/jpeg');

-- ----------------------------
-- Table structure for lyf_user_message
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_message`;
CREATE TABLE `lyf_user_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息正文',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '消息接收者 user_id，如果是系统消息则为0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息状态 0 未读 1已读',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息类型 0系统消息 1交易消息 2用户消息',
  `from_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '消息来源 user_id',
  `create_time` datetime DEFAULT NULL,
  `read_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户 消息表\r\n需要考虑广播消息怎么处理';

-- ----------------------------
-- Records of lyf_user_message
-- ----------------------------

-- ----------------------------
-- Table structure for lyf_user_wechat
-- ----------------------------
DROP TABLE IF EXISTS `lyf_user_wechat`;
CREATE TABLE `lyf_user_wechat` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='存储用户的微信信息<可能用到>';

-- ----------------------------
-- Records of lyf_user_wechat
-- ----------------------------
