<?php
class admin extends spController
{
    function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }
	function index() {
            $useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
           if ("GBADMIN" == $useracl) {            
                $this->display($this->skin.'/admin/index.html');
            } else {
                $this->error("对不起，登陆超时！请重新登录！", spUrl("admin", "login"));
//                echo "<script language=\"javascript\">alert('对不起，登陆失败！请检查用户名和密码！');history.go(-1)</script>";
            }
//                if(isset($_SESSION['userinfo'])){
//                    $this->display($this->skin.'/admin/index.html');
//		}else{
//			$this->jump(spUrl('admin','login'));
//			exit();
//		}
             
        }
        
        function modifyacl(){
		$oAcl=spClass("m_users");
		$condition=array('aclid'=>$this->spArgs('aclid'));
		$result_acl=$oAcl->find($condition);
		//echo $oUser->dumpSql();
		if ($result_acl){
                    if ($postmode=$this->spArgs('postmode')){
			$results=$oAcl->spVerifier($this->spArgs());
			if (FALSE==$results){
				$row_acl=array(
					'aclid'=>$this->spArgs('aclid'),
					'name'=>$this->spArgs('name'),
					'controller'=>$this->spArgs('controller'),
					'action'=>$this->spArgs('action'),
					'acl_name'=>$this->spArgs('acl_name')
				);
				//dump($row_user);	
			$result_update=$oAcl->update($condition,$row_acl);
			if ($result_update)
                            $this->success("ACL更新成功！", spUrl("sa","manageracl"));
			else
                            $this->error("ACL更新失败！", spUrl("sa","manageracl"));
			}
			else{
				foreach($results as $item){
							// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
						foreach($item as $msg){ 
							// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
							// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
							$this->error($msg,spUrl("sa","manageracl"));
						}
				}
			}				
	
			}	
			else{
				$this->row_acl=$result_acl;			
			}
			
			$this->page_title="账户修改";
			$this->page_info="用户的修改操作，在这里可以修改用户信息及角色。";
			$this->display("sa_modifyacl.html");			
		}
		else{
			$this->error("该ACL不存在！",spUrl("sa","manageracl"));
		}		
	}
        
        //==============================更新用户信息=============================
        function repwd() {
            if (!isset($_SESSION['userinfo'])) {
                $this->error('您还没有登陆！', spUrl('admin', 'login'));
            } else {
                $this->userinfo = $_SESSION['userinfo'];
                $this->display($this->skin.'/admin/update_pwd.html');
                
                $users = spClass('m_users');
                $adname=$this->spArgs('adname');
                $oldpass=$this->spArgs('oldpass');
                $adpass=$this->spArgs('adpass');
                $adpass2=$this->spArgs('adpass2');                
                $conditions = array(
			'username'=>$adname,
			'password'=>md5($oldpass),
		);
                if ($this->spArgs('adpass')) {
                    if($adpass!=$adpass2){
                        $this->error('输入两次新密码不一致！', spUrl('admin', 'repwd'));
                    }elseif( FALSE != $users->find($conditions)) {
                        if(FALSE != $users->update(array('userid' => $this->spArgs('userid')), array('password' => md5($adpass)))){
                            $this->success('修改密码成功！', spUrl('admin', 'repwd'));
                        }else{
                        $this->error('修改密码失败！', spUrl('admin', 'repwd'));
                        }
                    }else {
                        $this->error('账户原密码输入错误！', spUrl('admin', 'repwd'));
                    }
                }
            }
        }
	
	function deluser(){
		$oUser=spClass("m_users");
		$condition=array('uid'=>$this->spArgs('uid'));
		$result_user=$oUser->find($condition);

		if ($result_user) 
		{
			$result=FALSE;
			foreach ($GLOBALS['G_SP']['inneruser'] as $key=>$value){
				if (strtoupper($value)==strtoupper($result_user['uname'])){
					$result=TRUE;
				}
			}
			if (FALSE==$result){
				
				$results=$oUser->delete($condition);
				if ($results)$this->success("用户删除成功！", spUrl("sa","manageruser"));
				else $this->error('删除用户失败！',spUrl("sa","manageruser"));
			}
			else{
				$this->error('您不能删除内部用户！',spUrl("sa","manageruser"));
			}
		}
		else
		{
			$this->error('该用户不存在，删除失败',spUrl("sa","manageruser"));
		}
		
	}
	function manageracl(){
		$oAcls=spClass("lib_acl");
		$this->page_title="ACL管理";
		$this->page_info="在这里您可以查看所有ACL以及调整ACL的信息。";
		$results=$oAcls->spPager($this->spArgs('page', 1), 5)->findAll(); 
		$this->acl_list=$results;
		$this->pager=$oAcls->spPager()->getPager();
		//dump($this->pager);
		$this->display("sa_manageracl.html");
	}
	
	function manageruser(){

		$oUsers=spClass("m_users");
	
		$this->page_title="账户管理";
		$this->page_info="在这里您可以查看所有用户以及调整用户的信息。";		
		$results=$oUsers->spPager($this->spArgs('page', 1), 5)->findAll(); 
		$this->user_list=$results;
		
		$this->pager=$oUsers->spPager()->getPager();
		$this->display("sa_manageruser.html");
	}
	function delacl(){
		$oAcl=spClass("lib_acl");
		$condition=array('aclid'=>$this->spArgs('aclid'));
		$results=$oAcl->delete($condition);
		if ($results) $this->success("ACL删除成功！", spUrl("sa","manageracl"));
		else $this->error("ACL删除失败！", spUrl("sa","manageracl"));
	}
	function addacl(){
		$oAcl=spClass("lib_acl");
		if ($postmode=$this->spArgs('postmode')){
			
			$results=$oAcl->spVerifier($this->spArgs());
			
			if (FALSE==$results){
					$oAcl->create($this->spArgs());
					$this->success("ACL添加成功！", spUrl("sa","addacl"));
			}
			else{
				
				foreach($results as $item){
					// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
					foreach($item as $msg){ 
						// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
						// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
						$this->error($msg,spUrl("sa","addacl"));
					}
				}
			}
		}
		$this->page_title="ACL添加";
		$this->page_info="ACL的添加操作，控制系统功能的权限。";

		$this->display("sa_addacl.html");		
	}
	
	function adduser(){
	
	$oUser=spClass("m_users");
		if ($postmode=$this->spArgs('postmode')){
			$oUser->verifier=$oUser->verifier_for_adduser;
			$results=$oUser->spVerifier($this->spArgs());
			$arrArgs=$this->spArgs();
			
			if (FALSE==$results){
					$arrArgs['upass']=md5($this->spArgs('upass'));
					$result_create=$oUser->create($arrArgs);
					if ($result_create){
						$this->success("用户添加成功！", spUrl("sa","adduser"));	
					}
					else $this->error('用户添加失败！',spUrl("sa","adduser"));

			}
			else{
				foreach($results as $item){
					// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
					foreach($item as $msg){ 
						// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
						// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
						$this->error($msg,spUrl("sa","adduser"));						
					}
					
				}
				
			}
		}
		$this->page_title="账户添加";
		$this->page_info="用户的添加操作，在这里可以添加普通用户、管理员用户和更多角色的用户。";
		
		$oLib_acl=spClass('lib_acl');
		$this->acl_list=$oLib_acl->aclname_list();	

		$this->display("sa_adduser.html");	
	}

	public function login(){
            //import("spAcl.php"); // 引入Acl文件，使得可以生成加密的密码输入框
		//如果已经登录，直接跳转到首页
		if(isset($_SESSION['userinfo'])){
			$this->jump(spUrl('admin','index'));
			exit();
		}elseif($username = $this->spArgs('username')){
			if($_SESSION['passcode']!=strtoupper($this->spArgs('passcode'))){
				$this->error('验证码错误!',spUrl('admin','login'));
				exit();
			}else{
				$userObj = spClass('m_users');
                                //$password = spClass("spAcl")->pwvalue(); // 通过acl的pwvalue获取提交的加密密码
				//$password = $this->spArgs('password');
                                $password=md5($this->spArgs("password"));
				//$rows = array('username'=>$username, 'password'=>$password);
				if(FALSE==$userObj->userlogin($username,$password)){
					$this->error('对不起，用户名或密码错误！',spUrl('admin','login'));
				}else{
					//登陆成功写入记录并提示信息
					//spClass('m_history')->hadd();
					$this->success('欢迎您，登陆成功！',spUrl('admin','index'));
				}
			}
		}
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单		
		$this->display($this->skin."/admin/login.html");
	}
        
        public function logout(){
		//如果SESSION已经注销，直接跳转
		if(!isset($_SESSION['userinfo'])){
			$this->jump(spUrl('admin','login'));
			exit();
		}else{
			//设置当前用户角色为空
			spClass('spAcl')->set('');
			//清除设置的SESSION信息
			unset($_SESSION['userinfo']);
			$this->jump(spUrl('admin','login'));
			exit();
		}
	}
        
        public function acljump(){
		//登陆后权限不够时跳转到后台首页
		if(isset($_SESSION['userinfo'])){
			echo '<script>alert("您的权限不足！");history.go(-1);</script>';
			exit(); //退出执行流程
		}else{
			//未登录时直接跳转到登陆界面
			$gourl = spUrl('admin','login');
			echo '<script>location.href="'.$gourl.'";</script>';
			exit(); //退出执行流程
		}
	}
	//获取用户IP
	public function getip(){
		if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown")){
			$ip = getenv("HTTP_CLIENT_IP");
		}else if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"unknown")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"),"unknown")){
			$ip = getenv("REMOTE_ADDR");
		}else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],"unknown")){
			$ip = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip = "unknown";
		}
		return $ip;
	}
        
        //==========================配置系统参数=================================
        public function options(){
            $this->display($this->skin.'/admin/options.html');
        }
        
        //=========================更新系统配置==================================
        public function update_options(){
            $siteconf = spClass('m_config');
            $conditions = array('conid'=>  $this->spArgs("conid"));
            $newconf = array(// 这里制作新增记录的值
                'sitename' => $this->spArgs('sitename'),
                'shortname' => $this->spArgs('shortname'),
                'siteurl' => $this->spArgs('siteurl'), 
                'keywords' => $this->spArgs('keywords'),
                'description' => $this->spArgs('description'),
                 'statistics' => $this->spArgs('statistics'),
                'icp' => $this->spArgs('icp'),
                'linkman' => $this->spArgs('linkman'), 
                'mobile' => $this->spArgs('mobile'),
                'tel' => $this->spArgs('tel'),
                'qq' => $this->spArgs('qq'),
                'email' => $this->spArgs('email'),
                'address' => $this->spArgs('address')
            );
            
            if ($siteconf->update($conditions,$newconf)) {
                $this->success("恭喜您，系统配置更新成功！", spUrl("admin", "options"));
            } else {
                echo "<script language=\"javascript\">alert('Sorry, Update Unsuccessful!');history.go(-1)</script>";
            };
            $this->display($this->skin.'/admin/options.html');
        }
}
?>