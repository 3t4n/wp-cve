<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WShop_Wechat_Api{
    private $appid,$appsecret,$crossdomain_url;
    public function __construct($appid,$appsecret,$crossdomain_url=null){
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->crossdomain_url =$crossdomain_url;
    }
    /**
     * 获取用户 openid
     * @param string $appid
     * @param string $appsecret
     * @throws Exception
     * @return string|NULL|wp_redirect()
     * @since 1.0.0
     */
    public function get_openid(){
        $openid = apply_filters('xh_wechat_get_openid', null,$this);
        if(!empty($openid)){
            return $openid;
        }
        
        if(!empty($this->crossdomain_url)){
            if(isset($_POST['userdata'])&&isset($_POST['user_hash'])){
                $userdata = isset($_POST['userdata'])? base64_decode($_POST['userdata']):null;
                $user_hash = isset($_POST['user_hash'])?$_POST['user_hash']:'';
                
                $userdata =$userdata?json_decode($userdata,true):null;
                if(!$userdata){
                    return null;
                }
            
                if($user_hash!=WShop_Helper::generate_hash($userdata, $this->appsecret)){
                    WShop::instance()->WP->wp_die(__('Please check cross-domain app secret config(equal to current website app secret)!',WSHOP));
                    return null;
                }
                return isset($userdata['openid'])?$userdata['openid']:null;
            }
            
            $params = array();
            $params['callback']=WShop_Helper_Uri::get_location_uri();
            $params['hash'] = WShop_Helper::generate_hash($params, $this->appsecret);
            
            wp_redirect(WShop_Helper_Uri::get_new_uri($this->crossdomain_url,$params));
            exit;
        }
        
        //微信登录
        if(class_exists('XH_Social')){
            $wechat_login =XH_Social::instance()->channel->get_social_channel('social_wechat',array('login'));
            if($wechat_login&&method_exists($wechat_login, 'get_openid')){
                $openid = $wechat_login->get_openid();
                if(!empty($openid)){
                    return $openid;
                }
            }
        }
    
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $params = array();
            $params["appid"] = $this->appid;
            $params["redirect_uri"] =WShop_Helper_Uri::get_location_uri();
            $params["response_type"] = "code";
            $params["scope"] = "snsapi_base";
            $params["state"] = "STATE";
             
            header("location: https://open.weixin.qq.com/connect/oauth2/authorize?".http_build_query($params)."#wechat_redirect");
            exit();
        } else {
            $params = array();
            $params["appid"] = $this->appid;
            $params["secret"] = $this->appsecret;
            $params["code"] = $_GET['code'];
            $params["grant_type"] = "authorization_code";
    
            $response = WShop_Helper_Http::http_get( "https://api.weixin.qq.com/sns/oauth2/access_token?".http_build_query($params));
            if(!$response){
                throw new Exception('invalid callback data when get openid.');
            }
    
            //取出openid
            $data = json_decode($response,true);
            if(!$data||(isset($data['errcode'])&&$data['errcode']!=0)){
                throw new Exception($response,$data&&isset($data['errcode'])?$data['errcode']:-1);
            }
    
            return isset($data['openid'])?$data['openid']:null;
        }
    }
    
    public function get_wechat(){
        $wechat = apply_filters('xh_wechat_get_wechat', null,$this);
        if($wechat){
            return $wechat;
        }
        
        if(!empty($this->crossdomain_url)){
            if(isset($_POST['userdata'])&&isset($_POST['user_hash'])){
                $userdata = isset($_POST['userdata'])? base64_decode($_POST['userdata']):null;
                $user_hash = isset($_POST['user_hash'])?$_POST['user_hash']:'';
        
                $userdata =$userdata?json_decode($userdata,true):null;
                if(!$userdata){
                    return null;
                }
        
                if($user_hash!=WShop_Helper::generate_hash($userdata, $this->appsecret)){
                    WShop::instance()->WP->wp_die(__('Please check cross-domain app secret config(equal to current website app secret)!',WSHOP));
                    return null;
                }
                
                $userdata['nickname']= WShop_Helper_String::remove_emoji($userdata['nickname']);
                
                return $userdata;
            }
        
            $params = array();
            $params['callback']=WShop_Helper_Uri::get_location_uri();
            $params['hash'] = WShop_Helper::generate_hash($params, $this->appsecret);
        
            wp_redirect(WShop_Helper_Uri::get_new_uri($this->crossdomain_url,$params));
            exit;
        }
        
        //微信登录
        if(class_exists('XH_Social')){
            $wechat_login =XH_Social::instance()->channel->get_social_channel('social_wechat',array('login'));
            if($wechat_login&&method_exists($wechat_login, 'get_wechat')){
                $wechat = $wechat_login->get_wechat();
                if($wechat){
                    return $wechat;
                }
            }
        }
    
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $params = array();
            $params["appid"] = $this->appid;
            $params["redirect_uri"] =WShop_Helper_Uri::get_location_uri();
            $params["response_type"] = "code";
            $params["scope"] = "snsapi_userinfo";
            $params["state"] = "STATE";
             
            header("location: https://open.weixin.qq.com/connect/oauth2/authorize?".http_build_query($params)."#wechat_redirect");
            exit;
        } else {
            $params = array();
            $params["appid"] = $this->appid;
            $params["secret"] = $this->appsecret;
            $params["code"] = $_GET['code'];
            $params["grant_type"] = "authorization_code";
    
            $response = WShop_Helper_Http::http_get( "https://api.weixin.qq.com/sns/oauth2/access_token?".http_build_query($params));
            if(!$response){
                throw new Exception('invalid callback data when get openid.');
            }
    
            //取出openid
            $response = json_decode($response,true);
            if(!$response){
                throw new Exception(__('Nothing callback when get access token!'),500);
            }
            
            if(isset($response['errcode'])){
                throw new Exception($response['errmsg'],$response['errcode']);
            }
            
            $openid =$response['openid'];
            $access_token = $response['access_token'];
             
            $result = WShop_Helper_Http::http_get("https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid");
             
            $response = json_decode($result,true);
            if(!$response){
                throw new Exception(__('Nothing callback when get user info!'),500);
            }
            
            if(isset($response['errcode'])){
                throw new Exception($response['errmsg'],$response['errcode']);
            }
            
            if(isset($response['nickname'])){
                $response['nickname'] = WShop_Helper_String::remove_emoji($response['nickname']);
            }
            if(isset($response['headimgurl'])&&!empty($response['headimgurl'])){
                $response['img']=str_replace('http://', '//', $response['headimgurl']);
            }
            return $response;
        }
    }
    
    /**
     * 发送模板消息
     * @param string $openid
     * @param string $msg_id
     * @param string $link
     * @param array $data
     * @return WShop_Error|ARRAY
     */
    public function set_template_msg($openid,$msg_id,$link=null,$data=array()){
        require_once 'wechat/class-wechat-token.php';
        $tokenapi = new WShop_Wechat_Token($this->appid,$this->appsecret,$this->crossdomain_url);
        $retry = 2;
        $access_token = $tokenapi->access_token($retry);
        if($access_token instanceof WShop_Error){
            return $access_token;
        }
        
       try {
           $response = WShop_Helper_Http::http_post("https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}",json_encode(array(
               'touser'=>$openid,
               'template_id'=>$msg_id,
               'url'=>$link,
               'data'=>$data
           )));
           $error = new WShop_Wechat_Error($this->appid,$this->appsecret);
           return $error->validate($response); 
       } catch (Exception $e) {
           WShop_Log::error($e);
           return WShop_Error::error_custom($e->getMessage());
       }
    }
}