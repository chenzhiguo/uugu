<?php
class news extends spController
{
    function __construct(){ // 公用
	parent::__construct(); // 这是必须的
        $this->skin = __SKIN_NAME; 
        $this->siteconfig = spClass('m_config')->find();
    }

    //============================前台根据Item分页显示新闻列表====================
    function index() {        
        $news = spClass("m_news");
        $newsitem = $this->spArgs("item");
        $this->results = $news->spPager($this->spArgs('page', 1),20)->findAll(array('news_item'=>$newsitem),'news_id desc',NULL);
        $this->pager = $news->spPager()->getPager();
        if($this->results == null){
                $this->display(APP_PATH."/404.html");
        }
        else{
            $this->display($this->skin."/news.html");  
        }

    }
    
    //==========================后台根绝Item分页显示新闻列表======================
    function index_c() {
        $news = spClass("m_news");
        $newsitem = $this->spArgs("item");
        $this->results = $news->spPager($this->spArgs('page', 1),10)->findAll(array('news_item'=>$newsitem),'news_id desc',NULL);
        $this->pager = $news->spPager()->getPager();
        $this->display($this->skin."/admin/articles.html");         
    }
    
    //=============================根据ID，Item显示一条新闻======================
    function show_news(){ 
            $onenews = spClass("m_news");
            $newsitem= $this->spArgs("news_item");
            $newsid = $this->spArgs("news_id");            
            $conditions = array(
                'news_item'=>$newsitem,
                'news_id'=>$newsid,                
            );
            $onenews->incrField(array('news_id'=>$newsid), 'news_click',1);
            $this->results = $onenews->findAll($conditions);
            if($this->results == null){
                $this->display(APP_PATH."/404.html");
            }
            else{
                $this->display($this->skin."/onenews.html");
            }
   }
   
    //==========================后台根据ID，Item显示一条新闻======================
    function show_news_c(){ 
            $onenews = spClass("m_news");
            $newsid = $this->spArgs("news_id");
            $conditions = array(
                'news_id'=>$newsid,
            );
            $this->results = $onenews->findAll($conditions);
            $this->display($this->skin."/admin/update_article.html");
   }
   
   //===========================显示添加文章表单=================================
   public function article(){
       $this->display($this->skin."/admin/article.html");
   }
   
   //================================更新文章====================================
   public function update_article() {
        $onenews = spClass("m_news");
        $conditions = array('news_id'=>  $this->spArgs("news_id"));
        $newrow = array(// 这里制作新增记录的值
            'news_name' => $this->spArgs('news_name'),
            'news_type' => $this->spArgs('news_type'),
            'news_content' => $this->spArgs('content'), // 从spArgs获取到表单提交上来的title
            'news_author' => $this->spArgs('news_author'),
            'news_item' => $this->spArgs('news_item')
        );
        $news_item=$this->spArgs('news_item');
        if ($onenews->update($conditions,$newrow)) {
            $this->success("恭喜您，信息更新成功！", spUrl("news", "index_c",array("item"=>$news_item)));
        } else {
            echo "<script language=\"javascript\">alert('Sorry, Update Unsuccessful!');history.go(-1)</script>";
        };
    }
   
   //===========================根据ID删除信息===================================
   public function delete() {
        $news_id = $this->spArgs("news_id");
        if (spClass("m_news")->delete(array('news_id' => $news_id))) {
            // 执行删除
            echo "<script language=\"javascript\">alert('恭喜您，信息删除成功！');history.go(-1)</script>";
            exit();
        } else {
            // 无gid则直接跳转回首页
            echo "<script language=\"javascript\">alert('对不起，信息删除失败，请确定ID值有效！');history.go(-1)</script>";
            exit();
        }
        
    }
   
   //===========================保存一条信息=====================================
   function save_news() {
        $onenews = spClass("m_news");
        $newrow = array(// 这里制作新增记录的值
            'news_name' => $this->spArgs('news_name'),
            'news_type'=>  $this->spArgs('news_type'),            
            'news_content' => $this->spArgs('content'), // 从spArgs获取到表单提交上来的title
            'news_author'=> $this->spArgs('news_author'),          
            'news_date' => date('Y-m-d'),
            'news_item'=>  $this->spArgs('news_item'),
            'news_state' => "0",
            'news_click'=>"0"
        );
        if($onenews->create($newrow)){
            echo "<script language=\"javascript\">alert('Good luck, Successful!');history.go(-1)</script>";
            $this->display($this->skin . "/admin/article.html");
            exit();
        }else{
            echo "<script language=\"javascript\">alert('Sorry, Unsuccessful!');history.go(-1)</script>";
        };        
    }
}
?>