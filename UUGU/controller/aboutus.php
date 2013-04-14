<?php
class aboutus extends spController
{
   function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }

	function index(){
            $this->display('default/aboutus.html');
	}
        
        function rules(){
            $this->display('default/rules.html');
	}
}