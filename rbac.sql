/*
SQLyog Professional v12.09 (64 bit)
MySQL - 5.5.47 : Database - ar
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ar` /*!40100 DEFAULT CHARACTER SET gbk */;

USE `ar`;

/*Table structure for table `tp_admin` */

DROP TABLE IF EXISTS `tp_admin`;

CREATE TABLE `tp_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `pword` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属角色id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员表';

/*Data for the table `tp_admin` */

insert  into `tp_admin`(`admin_id`,`uname`,`pword`,`role_id`,`create_time`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3',1,NULL),(2,'test','21232f297a57a5a743894a0e4a801fc3',1,NULL),(3,'jianglibin','e10adc3949ba59abbe56e057f20f883e',4,NULL),(4,'test1','e10adc3949ba59abbe56e057f20f883e',4,1481183995);

/*Table structure for table `tp_menu` */

DROP TABLE IF EXISTS `tp_menu`;

CREATE TABLE `tp_menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父类id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `controller` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '方法',
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器与方法url,如 c/a,b/d',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '菜单显示状态: 0不显示,1显示',
  `power` tinyint(1) unsigned DEFAULT '1' COMMENT '是否启用, 0关闭,1开启',
  `step` tinyint(3) unsigned DEFAULT '0' COMMENT '排序权重,0-255, 越小菜单越靠前',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

/*Data for the table `tp_menu` */

insert  into `tp_menu`(`menu_id`,`pid`,`name`,`controller`,`action`,`url`,`status`,`power`,`step`) values (1,0,'权限管理','','','/',1,1,0),(2,1,'菜单管理','Menu','index','Menu/index',1,1,0),(3,1,'角色管理','Role','index','Role/index',1,1,0),(4,0,'后台人员管理','','','/',1,1,0),(5,4,'后台人员列表','Admin','index','Admin/index',1,1,0),(6,2,'添加菜单','Menu','add','Menu/add',1,1,0),(9,5,'后台用户添加','Admin','add','Admin/add',1,1,0),(15,2,'添加子菜单','Menu','addChild','Menu/addChild',1,1,0),(16,2,'删除菜单','Menu','del','Menu/del',1,1,0),(17,2,'编辑菜单','Menu','edit','Menu/edit',1,1,0),(18,3,'配置权限','Role','privilegeEdit','Role/privilegeEdit',1,1,0),(21,3,'添加角色','Role','add','Role/add',1,1,0),(22,3,'删除角色','Role','del','Role/del',1,1,0),(23,3,'编辑角色','Role','edit','Role/edit',1,1,0),(25,5,'后台人员编辑','Admin','edit','Admin/edit',1,1,0),(26,5,'后台人员删除','Admin','del','Admin/del',1,1,0);

/*Table structure for table `tp_role` */

DROP TABLE IF EXISTS `tp_role`;

CREATE TABLE `tp_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rname` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色菜单表';

/*Data for the table `tp_role` */

insert  into `tp_role`(`role_id`,`rname`) values (1,'超级管理员'),(4,'测试人员1');

/*Table structure for table `tp_role_menu` */

DROP TABLE IF EXISTS `tp_role_menu`;

CREATE TABLE `tp_role_menu` (
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色菜单对应表';

/*Data for the table `tp_role_menu` */

insert  into `tp_role_menu`(`role_id`,`menu_id`) values (3,1),(3,2),(3,6),(3,3),(3,4),(3,5),(3,9),(4,1),(4,2),(4,6),(4,15),(4,16),(4,17),(4,3),(4,18),(4,21),(4,22),(4,23);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
