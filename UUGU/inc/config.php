<?php
if (!defined('UUGU')) { exit(1);}
// 默认时区设置
@date_default_timezone_set('PRC');
// 载入用户自定义的函数文件
define("__SKIN_NAME",'default');

// 通用的全局配置
$spConfig = array(
	"db" => array(
            'host' => 'localhost',
            'port'=>3306,
            'login' => 'root',
            'password' => 'zhima',
            'database' => 'uugu',
            'prefix'=>'uu_',
	),
	'view' => array(
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/template', // 模板目录
			'compile_dir' => APP_PATH.'/tmp', // 编译目录
			'cache_dir' => APP_PATH.'/tmp', // 缓存目录
			'left_delimiter' => '<{',  // smarty左限定符
			'right_delimiter' => '}>', // smarty右限定符
		),
            'debugging' => FALSE,
        //'auto_display' => TRUE, // 是否使用自动输出模板功能
        //'auto_display_sep' => '/', // 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
        //'auto_display_suffix' => '.html', // 自动输出模板的后缀名
	),
	'url' => array( // URL设置
		'url_path_info' => FALSE, // 是否使用path_info方式的URL
	),
        'launch' => array(
            'router_prefilter' => array(                
                array('spAcl', 'mincheck'), // 开启有限的权限控制 
                array('spUrlRewrite', 'setReWrite') // 对路由进行挂靠，处理转向地址
            ),
            'function_url' => array(
                array("spUrlRewrite", "getReWrite"), // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
            ),
        ),
    	'ext'=>array(
                //无权限时跳转
            'spAcl'=>array(
                'prompt'=>array('m_users','acljump'),
            ),
	    // 以下是Url_ReWrite的设置
            'spUrlRewrite' => array(
                'hide_default' => false, // 隐藏默认的main/index名称，但这前提是需要隐藏的默认动作是无GET参数的
	 	'args_path_info' => false, // 地址参数是否使用path_info的方式，默认否
                'suffix' => '.html',
                'sep' => '-',
                'map' => array(
                    'allnews'=>'news@index',
                    'shownews' =>'news@show_news',
                    'message'=>'contactus@show_message',
                    'article'=>'article@index',
                    '@'=>'main@nopage',
                ),
                'args' => array(
                    'allnews'=>array('item'),
                    'shownews'=>array('news_item','news_id'),                 
                    'article'=>array('item'),
                ),
            ),
	),
        'dispatcher_error'=>"import(APP_PATH.'/404.html'); exit();",
);