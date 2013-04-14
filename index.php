<?php
define('UUGU', true);
define("UUGU_VERSION","0.1.0");
define("APP_PATH",dirname(__FILE__));
// 定义框架目录
define("SP_PATH",APP_PATH."/include/SpeedPHP");

if( true != @file_exists(APP_PATH.'/inc/config.php') ){require(APP_PATH.'/install.php');exit;}
// 网站主体模块程序入口文件
// 载入配置与定义文件
require("inc/config.php");
// 载入SpeedPHP框架
require(SP_PATH."/SpeedPHP.php");
spRun();