<?php

class UserController extends CController
{

    protected $fbUser;
    private $image_folder;
    public $facebook;
    public $localUser;
    public $nowUserName;    
    public $moduleName = 'neases';
    public $nowTheme = 'basic';    

    private $maxFileSize = 5242880; //5 MB
    private $otherFileSize = 20100000;
    public $mediumImageWidth = 402;
    public $mediumImageHeight = 425;
    public $themeUrl;
    public $assetsUrl;
    public $canvasPage;
    public $tabUrl;
    public $appPath;   
    public $mainUrl; 
    public $entry;

    //fb details
    public $moduleAppId = "470491453282522";
    public $moduleAppSecret = "4e994cc3877e479f266fbb3edcbd7add";   
    public $tabId;  

    var $headData;
    var $footerData;  
    var $signed_request;
    var $showMobileVersion;

    /**
     * Declares class-based actions.
     */

    public function __construct($id, $module = null)
    {
    
      	// FB Apps Configuration Here
    	
        if (!isset($_REQUEST['redirect']) && isset($_REQUEST['code'])) {
            //echo '<script>window.top.location.href = "http://iheartdevs.com/apps/neases/tab"</script>';
        }

        $this->signed_request = Yii::app()->session['signed_request'];  
        $this->image_folder = Yii::app()->basePath . '/../photos';  
        $this->layout = '/layouts/main';        
        $this->themeUrl = Yii::app()->baseUrl . "/themes/".$this->nowTheme;        
        $this->image_folder = Yii::app()->basePath . '/../user_assets/entries';  
        $this->assetsUrl = 'http://iheartdevs.com/apps/keeplaosclean/user_assets';
        
        //$this->mainUrl   = 'http://iheartdevs.com/apps/neases/';
        //$this->canvasPage = "http://iheartdevs.com/apps/neases/"; 

	//$this->facebook = $facebook;
	//$this->fbUser = $facebook->getFbUser();  
   
	parent::__construct($id, $module);
    }
  

    public function actionFeed($page = "index") {    
	$data['user'] = Yii::app()->request->getParam('id');
        $data['page'] = 'index';
        $data['sort'] = "popular";
        if(isset($_REQUEST['sort']))
            $data['sort'] = $_REQUEST['sort'];
	$this->render("/user/index",array('data' => $data));	
    }
    
    public function actionLoadMore() {
        $data['page'] = 'load';
        $data['entries'] = array();
        $this->renderPartial("/user/load",array('data' => $data));    
    }    
    
    public function actionSingle() {   
    	$data['page'] = 'single';
    	$data['vote'] = false;    
    	
    	$data['profile'] = $_REQUEST['profile'];
    	$data['author'] = $_REQUEST['author'];
	
	$URL = $_REQUEST['post_url'];
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$html= curl_exec($ch);
	curl_close($ch);
      	$data["json"] = json_decode ($html);
    	
    	$this->render("/user/single",array('data' => $data)); 
    }

    public function actionGetPostData() {   
    
        $URL = $_REQUEST['post_url'];
    
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $html= curl_exec($ch);
        curl_close($ch);

        echo $html;
    }

    public function actionPrivacy() {
        $this->render("/user/privacy");
    }
}