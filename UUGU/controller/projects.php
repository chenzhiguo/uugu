<?php
class projects extends spController
{
    function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }
	function index(){            
            $this->display($this->skin.'/projects.html');
	}
        
}