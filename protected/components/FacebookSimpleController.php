<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FacebookSimpleController extends Facebook
{

    public $fbUser;
    public $facebook;
    public $nowUserId;
    private $fbConfig;
    public $appId;
    private $debug = true;
    private $fb;
    public $signedRequest;
    public $redirect_uri;
    private $permissions;

    public $loginUrl = "";

    function debug($line = '') {
        if ($this->debug == true) {
            echo $line . "-" . time();
            exit;
        }
    }

    function __construct($auth = 1, $fbConfig = '', $permissions = '', $redirect_uri = '') {

        if ($fbConfig == '') {
            $fbConfig = array(
                'appId' => '447733101946678',
                'secret' => '802ee516d767a07ceacd2d0ce7da36d5',
                'fileUpload' => true,
                'cookie'     => true,
            );
        }

        $this->redirect_uri = $redirect_uri;
        $this->fbConfig = $fbConfig;
        $this->appId = $fbConfig['appId'];

        $facebook = new Facebook(array(
            'appId'      => $this->fbConfig['appId'],
            'secret'     => $this->fbConfig['secret'],
            'fileUpload' => $this->fbConfig['fileUpload'],
            'cookie'     => $this->fbConfig['cookie'],
        ));

        $this->facebook = $facebook;

        //save active access token in the session
        $this->getAccToken();

        if ($permissions == '') {
            $permissions = "publish_stream, email, user_photos, user_likes, manage_pages, offline_access, read_insights";
        }

        $this->permissions = $permissions;

        //auth = 0 (no permission check)
        //auth = 1 (permission check and redirect for permission)
        //auth = 2 (permission check and but don't redirect for permission)

        if ($auth == 1 or $auth == 2) {
            // Get User ID
            $user = $facebook->getUser();
            $this->nowUserId = $user;

            if ($user) {
                //$this->debug(__LINE__);
                try {
                    //$this->debug(__LINE__);
                    // Proceed knowing you have a logged in user who's authenticated.
                    $user_profile = $facebook->api('/' . $user);
                } catch (FacebookApiException $e) {
                    $this->debug();
                    //error_log($e);
                    print_r($e);
                    $user = null;
                }
            }

            // Login or logout url will be needed depending on current user state.
            if ($user) {
                //$this->debug();
                $this->fbUser = $user_profile;
                $logoutUrl = $facebook->getLogoutUrl();
            } else {
                if (true) {
                    if ($redirect_uri != '')  {
                        $params = array(
                            'scope'        => $permissions,
                            'redirect_uri' => $redirect_uri
                        );
                    } else {
                        $params = array(
                            'scope' => $permissions
                        );
                    }

                    $this->loginUrl = $facebook->getLoginUrl($params);
                    //$this->debug();

                    if($auth == 1) {
                        echo '<script>window.top.location.href = "' . $this->loginUrl . '"</script>';
                        exit;
                    }

                }
            }
        }
    }

    public function getFbConfig() {
        return $this->fbConfig;
    }

    public function getFbUser() {
        return $this->fbUser;
    }

    function getPages($value = 0, $limit = '500', $after = '') {
        try {
            $facebook = $this->facebook;
            return $facebook->api('me/accounts?until&value=' . $value . '&type=pages&limit=' . $limit . '&after=' . $after);
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e);
        }
    }

    function addAppToTab($pageId, $accessToken, $customName, $appId, $tabImage) {

        $facebook = $this->facebook;

        try {
            //circus social = 447733101946678
            //circus social form = 192218560920059
            //$appId = $this->appId;
            //$appId = "192218560920059";
            //adding if does not exist
            $facebook->api('/' . $pageId . '/tabs', 'POST', array('custom_name'  => $customName, 'custom_image' => '@' . realpath($tabImage), 'access_token' => $accessToken, 'app_id'       => $appId));

            //updating if exists
            return $facebook->api('/' . $pageId . '/tabs/app_' . $appId, 'POST', array('custom_name'  => $customName, 'custom_image' => '@' . realpath($tabImage), 'access_token' => $accessToken));
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e);
            // AdminController::errorAlert("Api Issue while App Publishing",serialize($e));
        }
    }


    /*to check if app is installed on the give page or not*/
    public function checkAppOnPage($appId,$pageId) {
        try {
            $result = $this->facebook->api('/' . $pageId . '/tabs/app_' . $appId, 'GET');
            // print_r($result);
            if(isset($result['data'][0]['id']))  {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // echo "<pre>";
            // print_r($e);
        }
    }


    function deleteAppFromTab($pageId, $pageAccessToken, $appId) {
        $facebook = $this->facebook;

        try {
            $facebook->api('/'.$pageId.'/tabs/app_'.$appId, 'delete', array('access_token' => $pageAccessToken));
        } catch (Exception $e) {
            //echo "<pre>";
            //print_r($e);
        }
    }

    function getCurrentPageId() {
        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];
        $signed_requst_data = $this->parse_signed_request($signed_request);
        if (isset($signed_requst_data['page'])) {
            return $signed_requst_data['page']['id'];
        }
    }

    function isTab() {
        $signed_request = @$_REQUEST['signed_request'];

        if ($signed_request == '') {
            return false;
        }

        $signed_requst_data = $this->parse_signed_request($signed_request);

        if (isset($signed_requst_data['page'])) {
            return true;
        }
        return false;
    }

    function getTabData()
    {
        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];

        return $this->parse_signed_request($signed_request);
    }

    function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        return $data;
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function getFacebookPhotos($albumID, $limit = 500) {
        $facebook = $this->facebook;
        $photos = $facebook->api("/" . $albumID . "/photos?limit=" . $limit);
        return $photos;
    }

    function getFacebookAlbums($value = '', $limit = '500', $after = '') {
        $facebook = $this->facebook;
        $access_token = $facebook->getAccessToken();
        $params = array('access_token' => $access_token);

        if ($this->nowUserId == 0) {
            return
            array("data" => array(
                array(
                    'id'   => '1',
                    'name' => 'test album'
                    )
                )
            );
        }

        $albums = $facebook->api('' . $this->nowUserId . '?fields=albums');
        return $albums['albums'];
    }

    function getFacebookAlbums_neases($value = '', $limit = '500', $after = '')  {
        //return dummy data if it is on localhost
        $facebook = $this->facebook;
        $access_token = $facebook->getAccessToken();

        $params = array('access_token' => $access_token);

        if ($this->nowUserId == 0)
        {
            return
            array("data" => array(
                array(
                    'id'   => '1',
                    'name' => 'test album'
                    )
                )
            );
        }

        $albums = $facebook->api('' . $this->nowUserId . '/albums?limit=100');

        $pics = array();
        foreach($albums['data'] as $album):
            $pic = $facebook->api('' . $album['id'] . '/picture?redirect=false');
            $pics[] = $pic['data']['url'];
        endforeach;

        $albums['cover'] = $pics;
        return $albums;
    }

    function publishStream($userMessage, $pageLink, $picUrl, $postTitle, $postDetails, $userId = 'me')
    {
        $facebook = $this->facebook;

        $publishStream = 0;

        // echo Yii::app()->session['access_token'];

        try
        {

            $attachment =  array(
                'access_token' => Yii::app()->session['access_token'],
                'message' => $userMessage,
                'name' => $postTitle,
                'link' => $pageLink,
                'description' => $postDetails,
                'picture'=>$picUrl,
                'actions' => json_encode(array('name' => "Visit",'link' => $pageLink))
                );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/'.$userId.'/feed');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
            $result = curl_exec($ch);
            curl_close ($ch);


            // $publishStream = $facebook->api("/" . $userId . "/feed?access_token=".Yii::app()->session['access_token'], 'post', array(
            //     'message'     => $userMessage,
            //     'link'        => $pageLink,
            //     'picture'     => $picUrl,
            //     'name'        => $postTitle,
            //     'description' => $postDetails,
            //     )
            // );
            //as $_GET['publish'] is set so remove it by redirecting user to the base url
        }
        catch (FacebookApiException $e)
        {

            //print_r($_REQUEST);
            echo "<pre>";
            print_r($e);
        }

        return $result;
    }

    public function getSigRequest(){
        $sr = $this->facebook->getSignedRequest();
        return $sr['code'];
    }


    public function curlApi($url,$fields)
    {

        /*usage
        $fields =  array(
                'access_token' => Yii::app()->session['access_token'],
                'message' => $userMessage,
                'name' => $postTitle,
                'link' => $pageLink,
                'description' => $postDetails,
                'picture'=>$picUrl,
                'actions' => json_encode(array('name' => "Visit",'link' => $pageLink))
                );

                $this->curlApi('https://graph.facebook.com/'.$userId.'/feed',$fields);
        */

        try
        {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
            $result = curl_exec($ch);
            curl_close ($ch);

            return $result;
        }
        catch (FacebookApiException $e)
        {
            //print_r($_REQUEST);
            echo "<pre>";
            print_r($e);
        }
    }


    public function getAccToken(){

        $access_token = $this->facebook->getAccessToken();;

        if(strlen($access_token) > 100){
            Yii::app()->session['access_token'] = $access_token;
        }

        return $this->facebook->getAccessToken();
    }

    public function parsePageSignedRequest()
    {
        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];

        if (isset($signed_request))
        {
            $encoded_sig = null;
            $payload = null;
            list($encoded_sig, $payload) = explode('.', $signed_request, 2);
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));
            return $data;
        }
        return false;
    }

    public function isPageLiked()
    {
        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];



        @list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        $app_data = isset($data["app_data"]) ? $data["app_data"] : '';


        if (isset($data["page"]))
        {
            $_REQUEST["fb_page_id"] = $data["page"]["id"];
            $access_admin = $data["page"]["admin"] == 1;
            $has_liked = $data["page"]["liked"] == 1;

            return $has_liked;
        }
    }

    public function getAppData()
    {
        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];

        @list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        $app_data = isset($data["app_data"]) ? $data["app_data"] : '';

        return $app_data;
    }

    public function appDataRedirection($moduleName)
    {

        $signed_request = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : Yii::app()->session['signed_request'];



        @list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        $app_data = isset($data["app_data"]) ? $data["app_data"] : '';

        //echo $app_data;

        if ($app_data != '')
        {
            $dataParts = explode("_", $app_data);

            $redirectTo = "index.php?r=" . $moduleName . "/{$dataParts['0']}/{$dataParts['1']}&entryId={$dataParts['2']}&signed_request=" . $_REQUEST['signed_request'];

            if( !Yii::app()->request->isAjaxRequest){

                //echo '<script>window.top.location.href = "' . $redirectTo . '"</script>';
                echo 1;
                exit;
            }
            //header("location: $redirectTo"); //it was giving error with fierce fashion app
        }
    }

    function getPageLikes($pageId)
    {
        $this->facebook->api("/".$pageId);
    }

    public function getFriends($fields = "name")
    {
        try
        {
            return $this->facebook->api("me/friends?fields=$fields");
        }
        catch (FacebookApiException $e)
        {
            echo "<pre>";
            print_r($e);
        }
    }

    public function getCommonFriends($fields = "uid, name")
    {

        //$fql = "SELECT " . $fields . " FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1";
        //return $this->fql($fql,$this->facebook->getAccessToken());
        //exit;
        //return array('699429441');
        try
        {
            $params = array(
                'method' => 'fql.query',
                'query'  => "SELECT " . $fields . " FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1",
                );

            return $this->facebook->api($params);
        }
        catch (FacebookApiException $e)
        {

            echo '<script>document.location = document.location</script>';
            echo "<pre>";
            print_r($e);
        }
    }

    function getcommentcount($id)
    {
        $facebook = $this->facebook;
        $count = $facebook->api('?ids=' . $id);
//echo '<pre>';
        // print_r($count[$id]['comments']);
        //echo '</pre>';
//exit;
        return $count[$id]['comments'];
    }

    function getFbPage($pageId)
    {
        return $this->facebook->api("/" . $pageId);
    }

    public function getCommonFriendsCurl($fields = "uid, name")
    {
        print_r($this->facebook->getAccessToken());
        //exit;

        try
        {
            $params = array(
                'method' => 'fql.query',
                'query'  => "SELECT " . $fields . " FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1",
                );

            return $this->facebook->api($params);
        }
        catch (FacebookApiException $e)
        {
            $this->login();
            //echo '<script>document.location = document.location</script>';
            //echo "<pre>";
            //print_r($e);
        }

        return;

        //print_r($this->facebook->getSignedRequest());
        //exit;
        //print_r($this->fbUser);
        //return;
        //return $this->facebook->getAccessToken();
        //echo "<br /><br />".$this->facebook->getAccessToken()."<br /><br />";

        $fql = "SELECT " . $fields . " FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1";


        return $this->fql($fql, $this->facebook->getAccessToken());



        //exit;
        //return array('699429441');
        try
        {
            $params = array(
                'method' => 'fql.query',
                'query'  => "SELECT " . $fields . " FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1",
                );

            return $this->facebook->api($params);
        }
        catch (FacebookApiException $e)
        {

            $this->login();
            //echo '<script>document.location = document.location</script>';
            //echo "<pre>";
            print_r($e);
        }
    }

    function fql($q, $access_token)
    {
        // Run fql query
        $fql_query_url = 'https://graph.facebook.com'
        . '/fql?q=' . urlencode($q)
        . '&access_token=' . urlencode($access_token);

        //echo "<a href='".$fql_query_url."' target='_blank' >";
        //echo $fql_query_url;
        //echo "</a>";
        //exit;

        $fql_query_result = file_get_contents($fql_query_url);


        $length = strlen(PHP_INT_MAX);
        $fql_query_result = preg_replace('/"(user_id)":(\d{' . $length . ',})/', '"\1":"\2"', $fql_query_result);

        return json_decode($fql_query_result, true);
    }

    public function login() {
        if ($this->redirect_uri != '') {
            $params = array(
                'scope'        => $this->permissions,
                'redirect_uri' => $this->redirect_uri
            );
        } else {
            $params = array(
                'scope' => $this->permissions
            );
        }
        $loginUrl = $this->facebook->getLoginUrl($params);
        echo '<script>window.top.location.href = "' . $loginUrl . '"</script>';
        exit;
    }

    public function getPageAccessToken($pageId) {
        $data = $this->getPages();
        $pages=$data['data'];

        if(!is_array($pages)) {
            //echo "Facebook Api Issue, Please refresh the page.";
            echo '<meta http-equiv="refresh" content="0">';
            exit;
        }

        foreach ($pages as $page) {
            if($page['id'] == $pageId) {
                return $page['access_token'];
            }
        }
    }
}