<?php

//$conn = @mysql_connect("localhost", "root", "zhima") or die("数据库链接错误");
//mysql_select_db("nss", $conn);
//mysql_query("set names 'utf8'"); 

function htmtocode($content) {
	$content = str_replace("\n", "<br>", str_replace(" ", "&nbsp;", $content));
	return $content;
}

//$content=str_replace("'","‘",$content);
 //htmlspecialchars();
?>