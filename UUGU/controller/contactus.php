<?php
class contactus extends spController
{
    function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }
    
	function index(){
            $this->display($this->skin.'/contactus.html');
	} 
        
        //====================后台留言模块首页，管理留言==========================
        function index_c_m() {
//            $messages = spClass("m_messages");
//            $state = $this->spArgs("state");
//            $this->results = $messages->spPager($this->spArgs('page', 1), 10)->findAll(array('state' => $state), 'id desc', NULL);
//            $this->pager = $messages->spPager()->getPager();
        $state = $this->spArgs("state");
        $wipage = intval($this->spArgs('page') ? $this->spArgs('page') : 1);
        $gopage = $wipage <= 0 ? 1 : $wipage;
        $msObj = spClass('m_messages');
        $msinfo = $msObj->spLinker()->spPager($gopage, 10)->findAll(array('state' => $state), 'id DESC');
        //htmlspecialchars();处理
        foreach ($msinfo as $v1) {
            foreach ($v1 as $v2) {
                $v2 = htmlspecialchars(stripslashes($v2), ENT_QUOTES);
            }
        }
        $this->results = $msinfo;
        $this->pager = $msObj->spPager()->getPager();
        if ($state == 1) {
            $this->display($this->skin . '/admin/messages.html');
        } else {
            $this->display($this->skin . '/admin/unpass_messages.html');
        }
    }

        //==================依据ID，State后台留言审核控制一条留言=================
        function pass_message() {
        $messages = spClass("m_messages");
        $conditions = array('id' => $this->spArgs("id"));
        $newrow = array(// 这里制作新增记录的值
            'state' => $this->spArgs("state")
        );
        $state=$this->spArgs("state");
        if($state==1){
            $state=0;
        }else{
            $state=1;
        }
        if ($messages->update($conditions, $newrow)) {
            $this->success("恭喜您，该操作成功执行！", spUrl("contactus", "index_c_m", array("state"=>$state)));
        } else {
            //echo "<script language=\"javascript\">alert('Sorry, Update Unsuccessful!');history.go(-1)</script>";
            $this->error("对不起，该操作未成功执行！", spUrl("contactus", "index_c_m",array("state"=>$state)));
        };
    }
    
       
   //===========================根据ID删除留言===================================
   public function delete_m() {
        $id = $this->spArgs("id");
        if (spClass("m_messages")->delete(array('id' => $id))) {
            // 执行删除
            echo "<script language=\"javascript\">alert('恭喜您，留言删除成功！');history.go(-1)</script>";
            exit();
        } else {
            // 无gid则直接跳转回首页
            echo "<script language=\"javascript\">alert('对不起，留言删除失败，请确定ID值有效！');history.go(-1)</script>";
            exit();
        }
        
    }
        
        //====================后台需求模块首页，管理需求==========================
        function index_c_d(){
            $demands = spClass("m_demands");
            $state = $this->spArgs("state");
            $this->results = $demands->spPager($this->spArgs('page', 1), 10)->findAll(array('demand_state' => $state), 'id desc', NULL);
            $this->pager = $demands->spPager()->getPager();
            if($state==1){
                $this->display($this->skin.'/admin/demands.html');
            }  else {
                $this->display($this->skin . '/admin/unpass_demands.html');
            }
	} 
        
        //==========================前台展现留言和回复===========================
        function show_message(){
//            $message = spClass("m_messages");
//            $this->results = $message->spPager($this->spArgs('page', 1),8)->findAll(array('state'=>1),'id desc',NULL);
//            $this->pager = $message->spPager()->getPager();
//            $this->display($this->skin.'/message.html');
//            
//            	if(!isset($_SESSION['uinfo'])){
//			$uidstr = NULL;
//		}else{
//			if($uid = intval($this->spArgs('uid'))){
//				$uid = $uid<0 ? NULL : $uid;
//				$uidstr = "uid = '$uid'";
//			}else{
//				$uidstr = NULL;
//			}
//		}
		//处理page的值
		$wipage = intval($this->spArgs('page') ? $this->spArgs('page') : 1);
		$gopage = $wipage<=0 ? 1 : $wipage;
		$msObj = spClass('m_messages');
		$msinfo = $msObj->spLinker()->spPager($gopage,10)->findAll(array('state'=>1),'id DESC');
		//htmlspecialchars();处理
		foreach($msinfo as $v1){
			foreach($v1 as $v2){
				$v2 = htmlspecialchars(stripslashes($v2),ENT_QUOTES);
			}
		}
		$this->msinfo = $msinfo;
		$this->pager = $msObj->spPager()->getPager();
		//分配变量
//		$this->siteconfig = spClass('m_config')->find();
//		$this->goodscate = spClass('m_gcate')->findAll();
//		$this->linkinfo = spClass('m_links')->findAll(NULL,'lkid DESC',NULL,6);
		$this->display($this->skin.'/message.html');
        }
        
        //===============================回复信息================================
        public function replyms(){
            $msid = $this->spArgs("id");
            $msObj = spClass('m_messages');
            $msinfo = $msObj->spLinker()->findAll(array('id'=>$msid));
            $this->msinfo = $msinfo;
            $this->display($this->skin.'/admin/rems.html');
        }


        //==========================添加或更新回复信息===========================
	public function addrems() {
            $mrid= $this->spArgs('mrid');
            $msid= $this->spArgs('id');
        if ($this->spArgs('mrcontent') && $mrid == NULL) {
            if (FALSE != spClass('m_msreply')->create(array('msid' => $this->spArgs('id'), 'mrcontent' => $this->spArgs('mrcontent')))) {
                spClass('m_messages')->update(array('id' => $msid),array('name' => $this->spArgs('name'),'content' => $this->spArgs('content')));
                $this->success('回复留言成功！', spUrl("contactus","index_c_m",array("state"=>"1")));
            }  else {
                $this->success('回复或更新留言失败！', spUrl("contactus","index_c_m",array("state"=>"1")));
            }
        }elseif ($this->spArgs('mrcontent') && $mrid) {
                spClass('m_msreply')->update(array('mrid' => $mrid), array('mrcontent' => $this->spArgs('mrcontent')));
                spClass('m_messages')->update(array('id' => $msid),array('name' => $this->spArgs('name'),'content' => $this->spArgs('content')));
                $this->success('更新留言成功！', spUrl('contactus', 'index_c_m&state=1'));
            } else {
                $this->success('回复或更新留言失败！', spUrl("contactus", "index_c_m",array("state"=>"1")));
            }
    }
        

        //===============================添加留言================================
        function save_message() {
            if ($this->spArgs('passcode') == $_SESSION["passcode"]) {
                $message = spClass("m_messages");
                $newrow = array(// 这里制作新增记录的值
                    'name' => $this->spArgs('name'),
                    'content' => $this->spArgs('content'), // 从spArgs获取到表单提交上来的title
                    'state' => "0",
                    'datetime' => date('Y-m-d H:i:s'),
                );
                $message->create($newrow);
                //echo "<script language=\"javascript\">alert('Good luck, Successful!');history.go(-1)</script>";
                $this->success("感谢您留言成功！等待管理员审核！", spUrl("contactus","show_message"));
            } else {
                //echo "<script language=\"javascript\">alert('Sorry, PassCode not right!');history.go(-1)</script>";
                $this->error("很抱歉，由于某些原因您的留言未成功，请联系管理员！", spUrl("contactus","show_message"));
            }
        }
        
        function save_demands() { 
        if ($this->spArgs('passcode') == $_SESSION["passcode"]) {
            $demands = spClass("m_demands");
            $newrow = array(// 这里制作新增记录的值
                'customer_name' => $this->spArgs('username'),
                'customer_email' => $this->spArgs('email'), // 从spArgs获取到表单提交上来的title
                'customer_demand' => $this->spArgs('content'),
                'demand_state' => "0",
                'date' => date('Y-m-d H:i:s'),
            );
            $demands->create($newrow);
            echo "<script language=\"javascript\">alert('恭喜您，需求添加成功！');history.go(-1)</script>";
        } else {
            echo "<script language=\"javascript\">alert('对不起，验证码不正确！');history.go(-1)</script>";
        }
    }
}
?>
