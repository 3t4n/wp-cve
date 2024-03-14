<?php
class generateView{
    
    public $referral, $ipAddress, $userAgent, $userID, $postType, $postID;
    
    public function __construct() {
        $this->updateView();
    }
    
    private function updateView () {
        
        $this->getIP();
        $this->getBrowserInfo();
        $this->getReferral();
        $this->getUserID();
        $this->getPostType();
        $this->getPostID();
        
        if($this->postType != '') {
           global $wpdb;

           $wpdb->insert(
                    $wpdb->prefix . VC_TABLENAME,
                    array(
                        'nUserID' => $this->userID,
                        'nPostID' => $this->postID,
                        'sPostType' => $this->postType,
                        'sIPAddress' => $this->ipAddress,
                        'sBrowserInfo' => $this->userAgent,
                        'sReferralURL' => $this->referral,
                        'dDateAdded' => current_time('mysql')
                            
                    )
            ); 
        }
    }
    
    private function getReferral() {
        $this->referral = wp_get_referer();
    }
    
    private function getIP() {
        
        if (isset($_SERVER["HTTP_CLIENT_IP"]) && !empty($_SERVER["HTTP_CLIENT_IP"])) {
            //check for ip from share internet
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            // Check for the Proxy User
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif(isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"]!='') {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip='';
        }
        
        $this->ipAddress = $ip;
    }
    
    private function getBrowserInfo() {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }
    
    private function getUserID () {
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $this->userID = $user->ID;
        } else {
            $this->userID = '0';
        }
    }
    
    public function getPostType () {
        
        global $post;
        
        if(is_single() || is_page()) {
            $this->postType = $post->post_type;
        } else {
            $this->postType = '';
        }
    }
    
    private function getPostID () {
        global $post; 
        
        $this->postID = $post->ID;
    }
}