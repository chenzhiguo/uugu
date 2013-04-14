<?php
class main extends spController
{
    function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }
    
    function index() {
        //spClass('spAcl')->set("GBUSER"); // 对当前访问者（SESSION）赋予THEADMIN角色
        $index = spClass("m_news");
        $this->somenews = $index->findAll(array('news_item'=>1),'news_id DESC',NULL,16);
        $this->somenotes = $index->findAll(array('news_item'=>2),'news_id DESC',NULL,16);       
        //$this->results = $index->findSql("select news_id,news_name,news_type,news_date,news_item from nss_news ORDER BY news_id desc limit 10");
        //$this->pager = $index->spPager()->getPager();
        $this->display( $this->skin.'/index.html');
    }
    
    function nopage() {
        $this->display(APP_PATH.'/404.html');
    }
        
}