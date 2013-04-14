<?php
class spAclModel extends spModel
{

        public $pk = 'aclid';
        /**
         * 表名
         */
        public $table = 'acl';

        /**
         * 检查对应的权限
         *
         * 返回1是通过检查，0是不能通过检查（控制器及动作存在但用户标识没有记录）
         * 返回-1是无该权限控制（即该控制器及动作不存在于权限表中）
         * 
         * @param acl_name    用户标识：可以是组名或是用户名
         * @param controller  控制器名称
         * @param action      动作名称
         */
        public function check($acl_name = SPANONYMOUS, $controller, $action)
        {
                $rows = array('controller' => $controller, 'action' => $action );
                if( $acl = $this->findAll($rows) ){
                        foreach($acl as $v){
                                if($v["acl_name"] == SPANONYMOUS || $v["acl_name"] == $acl_name)return 1;
                        }
                        return 0;
                }else{
                        return -1;
                }
        }
}

?>