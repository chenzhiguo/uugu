<?php
/**
 * Created on 2011-7-16
 * 留言信息模型
 */
if(!defined('APP_PATH')) exit('Access Denied');
class m_messages extends spModel{
	var $table = 'messages';
	var $pk = 'id';
	//留言模型验证信息
	var $verifier = array(
		'rules'=>array(
			'name'=>array(
				'notnull'=>TRUE,
				'minlength'=>2,
				'maxlength'=>11,
			),
			'content'=>array(
				'notnull'=>TRUE,
				'minlength'=>10,
				'maxlength'=>255,
			),
		),
	);
	//关联留言回复表
	var $linker = array(
		array(
			'type'=>'hasone', //关联类型|一对一关联
			'map'=>'msreply', //关联的标识
			'mapkey'=>'id', //本表的关联字段名
			'fclass'=>'m_msreply', //对应表的类名
			'fkey'=>'msid', //对应表的关联字段名
			'enabled'=>TRUE, //启用关联
		)
	);
}
?>
