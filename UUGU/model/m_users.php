<?php
    class m_users extends spModel
    {
      var $pk = "userid"; // 数据表的主键
      var $table = "users"; // 数据表的名称
      var $verifier = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行“格式验证”
		"rules" => array( // 规则
			'uname' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 3,  // uname长度不能小于3
				'maxlength' => 20  // uname长度不能大于12
			),
		),
		"messages" => array( // 提示信息
			'uname' => array(
				'notnull' => "用户名不能为空",
				'minlength' => "用户名不能少于3个字符",
				'maxlength' => "用户名不能大于20个字符"
			),
		)
	);
	var $verifier_for_adduser = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行“格式验证”
		"rules" => array( // 规则
			'uname' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 3,  // uname长度不能小于3
				'maxlength' => 20  // uname长度不能大于12
			),
			'upass_check'=>array(
				'equalto' => 'upass',
			),			
		),
		"messages" => array( // 提示信息
			'uname' => array(
				'notnull' => "用户名不能为空",
				'minlength' => "用户名不能少于3个字符",
				'maxlength' => "用户名不能大于20个字符"
			),
			'upass_check'=>array(
				'equalto' => '两次输入的密码不相同，请检查后重新输入！',
			),			
		)
	);	
	/**
	 * 这里我们建立一个成员函数来进行用户登录验证
	 *
	 * @param username    用户名
	 * @param password    密码，请注意，本例中使用了加密输入框，所以这里的$password是经过MD5加密的字符串。
	 */
	public function userlogin($username, $passwoed){ 
		$conditions = array(
			'username' => $username,
			'password' => $passwoed, // 请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
		);
		// dump($conditions);
		// 检查用户名/密码，由于$conditions是数组，所以SP会自动过滤SQL攻击字符以保证数据库安全。
		if( $result = $this->find($conditions) ){ 
			// 成功通过验证，下面开始对用户的权限进行会话设置，最后返回用户ID
			// 用户的角色存储在用户表的acl字段中
			spClass('spAcl')->set($result['acl']); // 通过spAcl类的set方法，将当前会话角色设置成该用户的角色
			$_SESSION["userinfo"] = $result; // 在SESSION中记录当前用户的信息
			//dump(spClass("spAcl")->get());
			return true;
		}else{
			// 找不到匹配记录，用户名或密码错误，返回false
			return false;
		}
	}
	/**
	 * 无权限提示及跳转
	 */        
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
}
?>
