<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSwpjobsession {

    public $sessionid;
    public $sessionexpire;
    private $sessiondata;
    private $datafor;
    private $nextsessionexpire;

    function __construct( ) {
        $this->init();
    }

    function getSessionId(){
        return $this->sessionid;
    }

    function init(){
        if (isset($_COOKIE['_wpjsjob_session_'])) {
            $cookie = jsjobslib::jsjobs_stripslashes(sanitize_text_field($_COOKIE['_wpjsjob_session_']));
            $user_cookie = jsjobslib::jsjobs_explode('/', $cookie);
            $this->sessionid = jsjobslib::jsjobs_preg_replace("/[^A-Za-z0-9_]/", '', $user_cookie[0]);
            $this->sessionexpire = absint($user_cookie[1]);
            $this->nextsessionexpire = absint($user_cookie[2]);
            // Update options session expiration
            if (time() > $this->nextsessionexpire) {
                $this->jsjob_set_cookies_expiration();
            }
        } else {
            $sessionid = $this->jsjob_generate_id();
            $this->sessionid = $sessionid . get_option( '_wpjsjob_session_', 0 );
            $this->jsjob_set_cookies_expiration();
        }
        $this->jsjob_set_user_cookies();
        return $this->sessionid;
    }

    private function jsjob_set_cookies_expiration(){
        $this->sessionexpire = time() + (int)(30*60);
        $this->nextsessionexpire = time() + (int)(60*60);
    }

    private function jsjob_generate_id(){
        require_once( ABSPATH . 'wp-includes/class-phpass.php' );
        $hash = new PasswordHash( 16, false );

        return jsjobslib::jsjobs_md5( $hash->get_random_bytes( 32 ) );
    }

    private function jsjob_set_user_cookies(){
        jsjobslib::jsjobs_setcookie( '_wpjsjob_session_', $this->sessionid . '/' . $this->sessionexpire . '/' . $this->nextsessionexpire , $this->sessionexpire, COOKIEPATH, COOKIE_DOMAIN);
        $count = get_option( '_wpjsjob_session_', 0 );
        update_option( '_wpjsjob_session_', ++$count);
    }

}

?>
