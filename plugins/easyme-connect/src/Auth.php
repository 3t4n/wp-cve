<?php

namespace EasyMe;

use Exception;

class Auth {

    const EM_AUTH_META_KEY = 'easyme_access_control';
    const EM_SESSION_KEY = '__easyme';
    const EM_SESSION_LAST_URL_KEY = 'last_url';

    private static $_userAssetTypes = ['subscriptions', 'online_products', 'tags'];
    
    public static function init() {

        add_action('add_meta_boxes', [get_class(), 'addMetaBox']);
        add_action('save_post', [get_class(), 'saveMetaBox']);

        if(WP::isPro()) {
            
            add_action('init', [get_class(), 'inspectRequest']);                    
            add_filter('the_content', [get_class(), 'filterContent'], PHP_INT_MAX, 1);
            add_filter('comments_array', [get_class(), 'filterComments'], PHP_INT_MAX, 1);
            add_filter('comments_open', [get_class(), 'allowComments'], PHP_INT_MAX, 1);            
            add_filter('em_filter_content', [get_class(), 'emFilterContent']);        

            add_action('template_redirect', function() {
                if(array_key_exists('REQUEST_URI', $_SERVER)) {
                    self::setSessionValue(self::EM_SESSION_LAST_URL_KEY, $_SERVER['REQUEST_URI']);
                }
            });
            
        }
        
    }
    
    /**
     * @link https://developer.wordpress.org/reference/hooks/the_content/
     */
    public static function filterContent($content) {

        $post = get_post();        

        $control = get_post_meta($post->ID, self::EM_AUTH_META_KEY, TRUE);

        if(!is_array($control) || 1 > sizeof($control) || current_user_can('administrator')) {
            return apply_filters('em_filter_content', $content);
        }
        
        self::preventCaching();

        if(!self::userIsLoggedIn()) {

            $content = nl2br(WP::getSetting('access_blocked_not_logged_in'));

        } else {

            if(!self::hasAccessToPost($post->ID)) {
                $content = nl2br(WP::getSetting('access_blocked_no_access'));
            }

            if(is_single() || is_page()) {
                $content = nl2br(WP::getSetting('access_logged_in_slug')) . $content;
            }

        }

        return apply_filters('em_filter_content', $content);        
        
    }

    private static function postIsAccessControlled($postID) {

        $control = get_post_meta($postID, self::EM_AUTH_META_KEY, TRUE);
        
        return (is_array($control) && 0 < sizeof($control));
        
    }
        
    private static function hasAccessToPost($postID) {

        $control = get_post_meta($postID, self::EM_AUTH_META_KEY, TRUE);
        
        if(!is_array($control) || 1 > sizeof($control) || current_user_can('administrator')) {
            return TRUE;
        }

        if(!self::userIsLoggedIn()) {
            return FALSE;
        }

        $assets = self::getUserAssets();

        $hasAccess = FALSE;
        foreach(self::$_userAssetTypes as $type) {
            if(array_key_exists($type, $assets) && array_key_exists($type, $control) && sizeof(array_intersect($control[ $type ], $assets[ $type ])) > 0) {
                return TRUE;
            }
        }

        return FALSE;

    }
    
    /**
     * @link https://developer.wordpress.org/reference/hooks/comments_array
     */
    public static function filterComments($array, $postID = NULL) {

        if(!self::userIsLoggedIn()) {
            return [];
        }
        
        $post = get_post();
        if(NULL === $postID && is_a($post, 'WP_Post')) {
            $postID = $post->ID;
        }

        return (self::hasAccessToPost($postID) ? $array : []);
        
    }

    /**
     * @link https://developer.wordpress.org/reference/functions/comments_open/
     */
    public static function allowComments($allow) {

        $post = get_post();
        
        if(is_a($post, 'WP_Post') && self::postIsAccessControlled($post->ID)) {
            return (self::hasAccessToPost($post->ID) ? $allow : FALSE);
        }

        return $allow;

    }
    
    public static function emFilterContent($content) {

        $login = 'https://ezme.io/wp/login';
        $profile = 'https://ezme.io/wp/profile';        
        
        if(self::userIsLoggedIn()) {
            $content = str_replace($login, $profile, $content); 
        }

        $username = 'https://ezme.io/wp/user/name';
        if(FALSE !== strstr($content, $username)) {
            $content = str_replace($username, self::getSessionValue('name'), $content);
        }
        
        return $content;
        
    }    

    /**
     * @link https://odd.blog/wp-super-cache-developers/
     */
    private static function preventCaching() {

        $const = 'DONOTCACHEPAGE';
        if(!defined($const)) {
            define($const, TRUE);
        }
        
    }
    
    public static function saveMetaBox($postID) {


        //if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $postID;
 
        /** 
         * Don't accidently clear out permissions in case the connection was lost
         * or if the metabox wasn't submitted for any other reason
         */
        if(!WP::isConnected() || !wp_verify_nonce(filter_input(INPUT_POST, 'easyme-auth-metabox-nonce'), (self::EM_AUTH_META_KEY . $postID))) {
            return;
        }

        $control = [];
        foreach(self::$_userAssetTypes as $type) {
            $checked = filter_input(INPUT_POST, $type, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            if(is_array($checked) && sizeof($checked) > 0) {
                $control[ $type ] = array_values($checked);
            }
        }
        
        if(sizeof($control) > 0) {
            update_post_meta($postID, self::EM_AUTH_META_KEY, $control);
        } else {
            delete_post_meta($postID, self::EM_AUTH_META_KEY);
        }
        
    }
    
    public static function addMetaBox() {

        foreach(['page', 'post'] as $type) {
            add_meta_box(
                'easyme_access_control',
                __('EasyMe Access Control', 'easyme'),
                [get_class(), 'getMetaContent'],
                $type,
                'side');
        }
        
    }

    public static function getMetaContent($post) {

        try {

            $oauth = new OAuthClient('site');
            $oauth->setAccessToken(WP::getAccessToken());

            $control = get_post_meta($post->ID, self::EM_AUTH_META_KEY, TRUE);

            if(!is_array($control)) {
                $control = [];
            }

            $tplArgs = [
                'isPro' => WP::isPro(),
                'nonce' => wp_create_nonce(self::EM_AUTH_META_KEY . $post->ID),
                'subs' => array_filter($oauth->get('/subscription'), function($sub) {
                    return ('ACTIVE' == $sub['state']);
                }),
                'courses' => $oauth->get('/online-product'),
                'tags' => $oauth->get('/tag')                
            ];

            foreach(['subs' => 'subscriptions', 'courses' => 'online_products', 'tags' => 'tags'] as $short => $long) {
                
                array_walk($tplArgs[ $short ], function(&$v) use($control, $long, $short) {

                    switch($short) {

                    case 'tags':
                        $title = $v['label'];
                        break;

                    default:
                        $title = $v['title'];

                    }
                    
                    $v = [
                        'id' => $v['id'],
                        'text' => $title,
                        'selected' => (array_key_exists($long, $control) && is_array($control[$long]) && in_array($v['id'], $control[$long])),
                        'disabled' => !WP::isPro()
                    ];
                    
                });

            }
            
            WP::loadTemplate('auth-metabox', $tplArgs);

        } catch ( Exception $ex ) {

            WP::handleException($ex);

            echo '<p>' . _e('Could not connect to EasyMe Server - check your connection.', 'easyme') . '</p>';
            echo '<p>' . sprintf( __('Error message was: %s', 'easyme'), $ex->getMessage() ) . '</p>';
            
        }

    }

    private static function userIsLoggedIn() {
        return self::getSessionValue('logged_in');
    }

    private static function getUserAssets() {

        $retVal = [];
        
        if(self::hasValidUserAccessToken()) {

            foreach(self::$_userAssetTypes as $asset) {

                $retVal[ $asset ] = [];
                
                if(FALSE !== ($has = self::getSessionValue($asset))) {
                    $retVal[ $asset ] = $has;                    
                }

            }

        }

        return $retVal;
        
    }
    
    private static function hasValidUserAccessToken() {

        if(FALSE !== ($at = self::getSessionValue('access_token'))) {
            return TRUE;
        }

        self::logout();

        return FALSE;
        
    }

    private static function persistAccessToken($token) {
        if(!$token['expires'] && $token['expires_in']) {
            $token['expires'] = ($_SERVER['REQUEST_TIME'] + $token['expires_in']);
        }
        self::setSessionValue('access_token', $token);
        self::setSessionValue('logged_in', TRUE);                
    }
    
    private static function refreshUserInfo($force = FALSE) {

        if(FALSE !== ($lastRefresh = self::getSessionValue('last_refresh')) && (time() - $lastRefresh < 600) && TRUE !== $force) {
            return;
        }

        $oauth = new OAuthClient('otp');
        $oauth->setAccessToken(self::getSessionValue('access_token')['access_token']);
        
        $user = $oauth->getTokenInfo();

        $user['subscriptions'] = $oauth->get('/connect/client/subscriptions');
        $user['online_products'] = $oauth->get('/connect/client/online-products');            
        
        self::setSessionValue('name', $user['name']);
        self::setSessionValue('email', $user['email']);
        self::setSessionValue('last_refresh', time());        

        foreach(self::$_userAssetTypes as $type) {
            $has = [];
            if(is_array($user) && array_key_exists($type, $user) && sizeof($user[ $type ]) > 0) {
                foreach($user[ $type ] as $a) {

                    switch($type) {
                            
                    case 'subscriptions':
                        $a['id'] = $a['plan_id'];
                        break;

                    case 'tags':
                        $a = ['id' => $a];
                        break;
                    }                        

                    $has[] = $a['id'];
                }
            }
            self::setSessionValue($type, array_map('intval', array_unique($has)));
        }
        
    }

    public static function ensureSessionsEnabled() {
        if(PHP_SESSION_ACTIVE != session_status()) {
            session_cache_limiter(FALSE);
            session_start();
        }
    }
    
    private static function getSession() {
        self::ensureSessionsEnabled();
        return (is_array($_SESSION) && array_key_exists(self::EM_SESSION_KEY, $_SESSION) ? (json_decode($_SESSION[ self::EM_SESSION_KEY ], TRUE) ?: []) : []);
    }
    
    public static function setSessionValue($key, $value) {
        
        $sess = self::getSession();
        $sess[ $key ] = $value;

        $_SESSION[ self::EM_SESSION_KEY ] = json_encode($sess);        

    }
    
    public static function getSessionValue($key) {

        $sess = self::getSession();
        
        return (array_key_exists($key, $sess) ? $sess[ $key ] : FALSE);
        
    }

    public static function logout() {
        
        self::ensureSessionsEnabled();        
        $_SESSION[ self::EM_SESSION_KEY ] = FALSE;
        
    }

    public static function inspectRequest() {
        
        if(array_key_exists(WP::getActionKey(), $_GET)) {

            self::preventCaching();
            
            switch(filter_input(INPUT_GET, WP::getActionKey())) {

            case 'client-refresh':
                self::refreshUserInfo(TRUE);                        
                break;
                
            case 'persist-token':
                $token = [
                    'access_token' => filter_input(INPUT_POST, 'access_token'),
                    'expires' => filter_input(INPUT_POST, 'expires')
                ];
                self::persistAccessToken($token);
                self::refreshUserInfo();                        
                break;

            case 'logout':
                
                self::logout();
                wp_redirect('/');
                exit;
                
                break;

            }
            
        }

    }
    
}

?>
