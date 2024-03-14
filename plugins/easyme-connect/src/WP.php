<?php

namespace EasyMe;

use Exception;

class WP {

    const EM_OPTIONS_KEY = 'easyme_connect_options';
    const EM_ACTIVATION_TRANSIENT = 'easyme_redirect_to_activation';
    const EM_TOKEN_REFRESH_FAILING_KEY = 'token_refresh_failed';
    
    public static $_menuSlugs = [
        'main' => 'easyme',
        'settings' => 'easyme-settings',
        'settings-etc' => 'easyme-settings-etc'
    ];

    public static function loadTemplate($tpl, $vars = []) {

        $_page = $vars;
        $_page['i18n'] = self::getSharedI18N();

        if(self::isConnected()) {
            $_page['site'] = self::getSetting('site');
            $_page['isPro'] = self::isPro();
        }
        
        $file = __DIR__ . '/../html/' . $tpl . '.php';

        if(file_exists($file)) {
            include_once($file);
        }
        
    }
    
    public static function showNotice($msg, $type = 'warning') {
        self::loadTemplate('message', ['msg' => $msg, 'type' => $type]);        
    }
    
    public static function getActionKey() {
        return self::getSetting('action_get_key');
    }
    
    public static function easymeActivate() {

        add_option(self::EM_OPTIONS_KEY, []);
        self::updateSetting('action_get_key', 'easyme-' . wp_generate_uuid4());
        self::enablePostActivation();
        
    }
    
    public static function easymePostActivation() {
        
        if(self::needsPostActivation()) {

            self::disablePostActivation();
            
            $client = new OAuthClient('site');

            wp_redirect($client->getAuthUrl());

            exit;

        }

    }

    public static function easymeDeactivate() {

        if(!self::isConnected()) {
            return;
        }
        
        try {
        
            $client = new OAuthClient('site');
            $client->setAccessToken(self::getAccessToken());
            $client->revokeToken();

        } catch( Exception $ex ) {
            self::handleException($ex); 
        }

        self::deleteToken();

    }

    public static function easymeUninstall() {
        delete_option(self::EM_OPTIONS_KEY);
    }

    public static function checkForOAuthCode() {
        
        if(array_key_exists('code', $_GET)) {

            try {
                $client = new OAuthClient('site');                    
                $token = $client->handleAuthCodeReturn();
                self::persistToken($token);

                // caches the client
                self::getEasyMeClient();                                    

                /**
                 * Redirect to a clean URL to avoid provoking a CSRF exception if user refreshes page
                 */
                wp_redirect(admin_url('admin.php?' . http_build_query(['page' => self::$_menuSlugs['main']])));

            } catch (Exception $ex) {
                if(400 !== $ex->getCode()) {
                    throw $ex;
                }
            }
            
        }

    }

    /**
     * @link https://developer.wordpress.org/reference/functions/add_menu_page/
     *
     * Icon is styled via admin.css
     *
     */
    public static function addMenu() {

        add_menu_page(
            'EasyMe',
            'EasyMe',
            'manage_options',
            self::$_menuSlugs['main'],
            [get_class(), '_connectMenu'],
            'none',
            65
        );

        add_submenu_page(
            self::$_menuSlugs['main'],
            __('EasyMe Settings', 'easyme'),
            __('Access control', 'easyme'),
            'manage_options',
            self::$_menuSlugs['settings'],
            [get_class(), '_settingsMenu']            
        );

        add_submenu_page(
            self::$_menuSlugs['main'],
            __('EasyMe Settings', 'easyme'),
            __('Other', 'easyme'),
            'manage_options',
            self::$_menuSlugs['settings-etc'],
            [get_class(), '_etcMenu']            
        );
        
    }

    public static function _settingsMenu() {

        $defaults = self::_getDefaultSettings();
        
        $settings = [
            'access_blocked_not_logged_in' => [
                'label' => __('Message when not logged in', 'easyme'),
                'default' => $defaults['access_blocked_not_logged_in']
            ],
            'access_blocked_no_access' => [
                'label' => __('Message when logged in but no access', 'easyme'),
                'default' => $defaults['access_blocked_no_access']
            ],
            'access_logged_in_slug' => [
                'label' => __('One-liner showing your login name', 'easyme'),
                'default' => $defaults['access_logged_in_slug']
            ]            
        ];

        $keys = array_keys($settings);        
        $_page = [
            'fields' => [],
            'submit_url' => admin_url('admin.php?' . http_build_query(['page' => self::$_menuSlugs['settings']])),
            'editor_options' => [
                'media_buttons' => FALSE,
                'textarea_rows' => 8,
                'teeny' => TRUE
            ]
        ];        

        if(array_key_exists($keys[0], $_POST) || array_key_exists($keys[1], $_POST)) {

            foreach($keys as $s) {
                $value = filter_input(INPUT_POST, $s);
                self::updateSetting($s, $value);
            }

            self::showNotice(self::getSharedI18N()['SETTINGS_SAVED'], 'success');
            
        }

        if(!self::isPro()) {
            $i18n = self::getSharedI18N();
            self::showNotice($i18n['NOT_PRO'], 'warning');
        }
        
        foreach($keys as $s) {  
            $_page['fields'][] = [
                'name' => $s,
                'label' => $settings[ $s ]['label'],
                'content' => (self::getSetting($s) ?: $settings[ $s ]['default'])                
            ];
        }                    

        self::loadTemplate('auth-settings', $_page);            
        
    }

    public static function _etcMenu() {

        $opts = ['widget_include_option'];
        
        if('POST' == strtoupper($_SERVER['REQUEST_METHOD'])) {           

            foreach($opts as $o) {
                if(array_key_exists($o, $_POST)) {
                    self::updateSetting($o, filter_input(INPUT_POST, $o));
                }
            }

            self::showNotice(self::getSharedI18N()['SETTINGS_SAVED'], 'success');

        }
        
        $_page = [
            'submit_url' => admin_url('admin.php?' . http_build_query(['page' => self::$_menuSlugs['settings-etc']]))
        ];

        foreach($opts as $o) {
            $_page[ $o ] = self::getSetting($o);
        }
            
        self::loadTemplate('etc-settings', $_page);                    

    }
    
    public static function _connectMenu() {

        $client = new OAuthClient('site');
        $_page = [
            'auth_url' => $client->getAuthUrl(),
            'colors' => self::getSetting('colors'),
            'connected' => FALSE
        ];
                        
        if(self::isConnected()) {

            $_page['connected'] = TRUE;
            
            $_page['submit_url'] = admin_url('admin.php?' . http_build_query(['page' => self::$_menuSlugs['main']]));

            if('POST' == strtoupper($_SERVER['REQUEST_METHOD'])) {

                $colors = self::getSetting('colors');
                
                if(!filter_input(INPUT_POST, 'primary_color')) {
                    self::removeSetting('colors');
                    $colors = self::_getDefaultSettings()['colors'];
                } else {

                    $colors['primary'] = [
                        'hex' => filter_input(INPUT_POST, 'primary_color'),
                        'hsl' => []
                    ];
                    
                    list($h, $s, $l) = explode(',', filter_input(INPUT_POST, 'primary_color_hsl'));
                    $colors['primary']['hsl'] = [
                        'h' => intval($h),
                        's' => intval($s),
                        'l' => intval($l)
                    ];
                    self::updateSetting('colors', $colors);                    
                }

                $_page['colors'] = $colors;
                
                self::showNotice(self::getSharedI18N()['SETTINGS_SAVED'], 'success');
                
            }
            
        }
        
        self::loadTemplate('main', $_page);                                    
        
    }

    public static function removeSetting($key) {

        $set = get_option(self::EM_OPTIONS_KEY);

        if(array_key_exists($key, $set)) {
            unset($set[ $key ]);
            update_option(self::EM_OPTIONS_KEY, $set);
        }
        
    }
    
    public static function updateSetting($key, $val) {

        $set = get_option(self::EM_OPTIONS_KEY);

        $set[ $key ] = $val;

        update_option(self::EM_OPTIONS_KEY, $set);
        
    }

    public static function getSetting($key) {
        
        $set = array_merge(self::_getDefaultSettings(), (is_array(get_option(self::EM_OPTIONS_KEY)) ? get_option(self::EM_OPTIONS_KEY) : []));

        return (is_array($set) && array_key_exists($key, $set) ? $set[ $key ] : NULL);
        
    }

    public static function hasFeature($f) {
        $features = self::getSetting('site_features');
        return (is_array($features) && in_array($f, $features));        
    }
    
    public static function isPro() {
        return self::hasFeature('WP_ADVANCED');
    }
    
    public static function isConnected() {
        return !empty(self::getSetting('access_token'));
    }

    public static function hasServerMessage() {
        return (!empty(self::getSetting('site_message')));
    }

    public static function getServerMessage() {
        return self::getSetting('site_message');
    }
    
    public static function refreshTokenFailing()  {
        return !empty(self::getSetting(self::EM_TOKEN_REFRESH_FAILING_KEY));
    }

    public static function getAccessToken() {

        if(self::refreshTokenFailing()) {
            return '# invalid refresh token #';
        }
        
        if(self::getSetting('access_token_expires') < ($_SERVER['REQUEST_TIME'] + 60)) {
            $client = new OAuthClient('site');
            try {
                $token = $client->refreshToken(self::getSetting('refresh_token'));
                self::persistToken($token);
                self::updateSetting(self::EM_TOKEN_REFRESH_FAILING_KEY, FALSE);
            } catch( Exception $ex ) {
                self::handleException($ex);                
                if(in_array($ex->getCode(), ['401'])) {
                    self::updateSetting(self::EM_TOKEN_REFRESH_FAILING_KEY, time());
                    self::removeSetting('refresh_token');
                }
            }
        }

        return self::getSetting('access_token');
        
    }

    private static function getAccessTokenAcquiredTime() {
        return self::getSetting('access_token_acquired');
    }
    
    private static function persistToken($token) {

        self::disablePostActivation();        
        
        self::updateSetting('access_token', $token['access_token']);
        self::updateSetting('refresh_token', $token['refresh_token']);                
        self::updateSetting('access_token_expires', ($_SERVER['REQUEST_TIME'] + $token['expires_in']));
        self::updateSetting('access_token_acquired', $_SERVER['REQUEST_TIME']);
        self::updateSetting(self::EM_TOKEN_REFRESH_FAILING_KEY, FALSE);
        
        $client = new OAuthClient('site');
        $client->setAccessToken(self::getAccessToken());
        $info = $client->getTokenInfo();
        
        foreach(['site', 'site_id', 'site_features', 'site_message'] as $f) {
            if(array_key_exists($f, $info)) {
                WP::updateSetting($f, $info[ $f ]);
            }
        }
        
    }
    private static function deleteToken() {
        self::updateSetting('access_token', FALSE);        
    }

    
    private static function getEasyMeClient() {

        if('EZME' == self::getSetting('widget_include_option')) {
            
            $post = get_post();        

            $includeWidget = (1 === preg_match('!((dev|stage)\.){0,1}ezme.io!', $post->post_content));

            if(!$includeWidget && WP::isPro()) {
                // check for access control - don't omit widget if found
                $control = get_post_meta($post->ID, Auth::EM_AUTH_META_KEY, TRUE);
                $includeWidget = (is_array($control) && 0 < sizeof($control));
            }
            
            if(!$includeWidget) {
                return '<!-- not including easyme widget on this page due to settings -->';
            }
            
        }
        
        $set = get_option(self::EM_OPTIONS_KEY);

        if(is_array($set) && array_key_exists('client_expires', $set) && $set['client_expires'] > $_SERVER['REQUEST_TIME']) {
            return $set['client'];
        }        

        if(!$set['refresh_token']) {
            return '<!-- EasyMe Connect: No valid refresh_token found -->';
        }

        $client = new OAuthClient('site');
        $client->setAccessToken(self::getAccessToken());

        try {
            
            $res = $client->get('/connect/oauth/client');
        
            self::updateSetting('client', $res['html']);
            self::updateSetting('client_expires', ($_SERVER['REQUEST_TIME'] + $res['ttl']));

            $set = get_option(self::EM_OPTIONS_KEY);

        } catch( Exception $ex ) {
            self::handleException($ex);
            $pre = "<!-- Error when getting client JS from server. Using cached version -->\n";
        }
        
        return (isset($pre) ? $pre : '') . $set['client'];
        
    }
    
    public static function loadTranslations() {
        load_plugin_textdomain('easyme', FALSE, basename(realpath(__DIR__ . '/../')) . '/lang/');
    }

    public static function loadWidgetJS() {

        $cfg = [
            'host' => 'wordpress',
            'paths' => [
                'token_deposit' => '/?' . http_build_query([WP::getActionKey() => 'persist-token']),
                'logout' => '/?' . http_build_query([WP::getActionKey() => 'logout']),
                'client_refresh' => '/?' . http_build_query([WP::getActionKey() => 'client-refresh'])
            ]
        ];
                
        if(FALSE !== ($colors = self::getSetting('colors')) && is_array($colors) && array_key_exists('primary', $colors) && array_key_exists('hex', $colors['primary']) && $colors['primary']['hex']) {
            $cfg['colors'] = [
                'primary' => $colors['primary']
            ];
        }

        $js = str_replace(['$cfg$'], [json_encode($cfg)], file_get_contents(__DIR__ . '/../assets/js/public.min.js'));
        wp_add_inline_script('jquery', $js);

    }

    public static function getPluginData() {

        if(!is_callable('get_plugin_data')) {
            return FALSE;
        }
            
        return get_plugin_data(self::getPluginMainFile(), FALSE, FALSE);
        
    }

    public static function addWidget() {

        if(!self::isConnected()) {
            return;
        }
        
        $vars = [];
        if(FALSE !== ($pData = self::getPluginData())) {
            $vars['plugin'] = $pData;
        }

        $q = new \WP_Query([
            'post_type' => ['page','post'],
            'meta_query' => [
                [
                    'key' => Auth::EM_AUTH_META_KEY,
                    'compare' => 'EXISTS'
                ]
            ]
        ]);

        $vars['protected_pages'] = $q->found_posts;
        $vars['access_token_acquired'] = self::getAccessTokenAcquiredTime();
        
        wp_add_dashboard_widget(
            'easyme_dashboard_widget',
            __('EasyMe Connect ' . $vars['plugin']['Version'], 'easyme'),
            function() use ($vars) {
                self::loadTemplate('dashboard-widget', $vars);
            }
        );

    }

    private static function onPluginPage() {
        return (is_admin() && in_array(filter_input(INPUT_GET, 'page'), array_values(self::$_menuSlugs)));            
    }
    
    public static function initUI() {

        // plugin icon svg
        wp_enqueue_style('ezme-admin-css', plugins_url('../assets/css/admin.css', __FILE__));

        if(self::onPluginPage()) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('ezme-admin-js', plugins_url('../assets/js/admin.js', __FILE__), ['wp-color-picker'], FALSE, TRUE);            
        }

        wp_register_style('s2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
        wp_enqueue_style('s2css');
        
        wp_register_script('s2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', ['jquery'], FALSE, TRUE);
        wp_enqueue_script('s2');
        
    }
    
    public static function run() {

        if(is_admin()) {
            
            register_activation_hook(self::getPluginMainFile(), [get_class(), 'easymeActivate']);
            register_deactivation_hook(self::getPluginMainFile(), [get_class(), 'easymeDeactivate']);
            register_uninstall_hook(self::getPluginMainFile(), [get_class(), 'easymeUninstall']);
            
            add_action('admin_menu', [get_class(), 'addMenu']);

            add_action('admin_init', [get_class(), 'checkForOAuthCode']);
            
            if(!self::isConnected() && self::needsPostActivation()) {
                add_action('admin_init', [get_class(), 'easymePostActivation']);
            }

            if(self::isConnected() && self::hasServerMessage()) {
                add_action('admin_notices', function() {                        
                    self::showNotice(self::getServerMessage(), 'info');
                });
            }

            add_action('wp_dashboard_setup', [get_class(), 'addwidget']);
            add_action('admin_enqueue_scripts', [get_class(), 'initUI']);
            
        } else {
            add_action('wp_footer', function() {                
                echo self::getEasyMeClient();                
            });
            add_action( 'wp_enqueue_scripts', [get_class(), 'loadWidgetJS']);
        }        

        add_action('plugins_loaded', [get_class(), 'loadTranslations']);
        
        if(is_admin() && self::$_menuSlugs['main'] != filter_input(INPUT_GET, 'page')) {

            $oauth = new OAuthClient('site');
            
            if(self::isConnected()) {

                if(self::refreshTokenFailing()) {
                    add_action('admin_notices', function() use ($oauth) {
                        
                        self::showNotice(
                            sprintf(
                                __('Your connection to EasyMe has gone stale - please <a href="%s">re-connect</a>', 'easyme'),
                                $oauth->getAuthUrl()
                            ),
                            'error'
                        );
                    });
                }

            } else {
                add_action('admin_notices', function() use ($oauth) {
                    self::showNotice(
                        sprintf(
                            __('You still need to <a href="%s">connect Wordpress to your EasyMe account</a> before you are ready to use the plugin', 'easyme'),
                            $oauth->getAuthUrl()                            
                        ),
                        'warning'
                    );
                });
            }
            
        } 
             
        if(self::isConnected()) {
            Auth::init();
        }

    }

    private static function getProUrl() {
        return 'https://secure.easyme.biz/admin/app/#/help/article/13';
    }
    
    private static function getSharedI18N() {
        
        return  [
            'PRO_DESCRIPTION' => sprintf(__('With <a href="%s" target="_blank">WordPress PRO</a> you can restrict access to your WordPress content based on subscriptions and online courses in EasyMe.', 'easyme'), self::getProUrl()),
            'IS_PRO' => sprintf(__('You have access to <a href="%s" target="_blank">WordPress PRO</a>', 'easyme'), self::getProUrl()),
            'NOT_PRO' => sprintf(__('You do not have access to <a href="%s" target="_blank">WordPress PRO</a>', 'easyme'), self::getProUrl()),
            'SETTINGS_SAVED' => __('Your settings are saved', 'easyme')
        ];

    }
    
    private static function getPluginMainFile() {
        return realpath(__DIR__ . '/../') . '/easyme.php';
    }

    private static function enablePostActivation() {
        set_transient(self::EM_ACTIVATION_TRANSIENT, TRUE, 60);
    }
    private static function disablePostActivation() {
        delete_transient(self::EM_ACTIVATION_TRANSIENT);
    }
    private static function needsPostActivation() {
        return (FALSE !== get_transient(self::EM_ACTIVATION_TRANSIENT) && !self::isConnected());
    }

    private static function _getDefaultSettings() {

        return [
            'widget_include_option' => 'ALL',
            'access_blocked_not_logged_in' => __('You need to <a href="https://ezme.io/wp/login">log in</a> to access this content.', 'easyme'),
            'access_blocked_no_access' =>  __('You do not have access to this content.', 'easyme'),
            /* Translators: Inline message in posts when the user is logged in with EasyMe. */
            'access_logged_in_slug' => __('<p style="text-align:right; font-style:italic; font-size: 80%; margin:0 padding: 0">You are logged in as https://ezme.io/wp/user/name | <a href="https://ezme.io/wp/logout">log out</a></p>', 'easyme'),
            'colors' => [
                'primary' => [
                    'hex' => NULL,
                    'hsl' => []
                ]
            ]
        ];

    }

    public static function getEasyMeServer($type = 'api') {

        switch($type) {

        case 'oauth':
            return (array_key_exists('EM_OAUTH_SERVER', $_SERVER) ? $_SERVER['EM_OAUTH_SERVER'] : 'https://oauth.easyme.com');
            break;
            
        case 'api':
        default:
            return (array_key_exists('EM_API_SERVER', $_SERVER) ? $_SERVER['EM_API_SERVER'] : 'https://api.easyme.com');
        }
        
    }
    
    public static function handleException(Exception $ex, $throw = FALSE) {

        error_log($ex->getMessage());

        if(TRUE === $throw) {
            throw $ex;
        }

    }
    
}

?>
