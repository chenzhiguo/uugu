<?php
/**
 * Created on 2011-6-17
 * 管理员ACL权限模型
 */
if(!defined('APP_PATH')) exit('Access Denied');
class m_acl extends spModel{
	var $table = 'acl';
	var $pk = 'aclid';
	
}
?>
