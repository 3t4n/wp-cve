<?php
/**
 * @package Flattr
 * @author Michael Henke
 * @version 1.2.2
Plugin Name: Flattr
Plugin URI: http://wordpress.org/extend/plugins/flattr/
Description: Give your readers the opportunity to Flattr your effort
Version: 1.2.2
Author: Michael Henke
Author URI: http://www.codingmerc.com/tags/flattr/
License: This code is (un)licensed under the kopimi (copyme) non-license; http://www.kopimi.com. In other words you are free to copy it, taunt it, share it, fork it or whatever. :)
Comment: The author of this plugin is not affiliated with the flattr company in whatever meaning.
 */

class Flattr
{
    /**
     * @deprecated
     */
    const API_SCRIPT = 'api.flattr.com/js/0.6/load.js?mode=auto';

    const FLATTR_DOMAIN = 'flattr.com';

    const VERSION = "1.2.2";

    /**
     * We should only create Flattr once - make it a singleton
     */
    protected static $instance;

    /**
     * Are we running on default or secure http?
     * @var String http:// or https:// protocol
     */
    var $proto = "http://";

    /**
     * construct and initialize Flattr object
     */
    protected function __construct() {
        if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])
            $this->proto = 'https://';

        self::default_options();

        if (is_admin()) {
            $this->backend();
        } else {
            $this->frontend();
        }

        if (empty($this->postMetaHandler)) {
            require_once( plugin_dir_path( __FILE__ ) . 'postmeta.php');
            $this->postMetaHandler = new Flattr_PostMeta();
        }

        add_action( 'widgets_init', array( $this, 'register_widget' ) );
        add_shortcode( 'flattr', array( $this, 'register_shortcode' ) );
    }

    /**
     * prepare Frontend
     */
    protected function frontend() {
        if (!in_array(get_option('flattr_button_style'), array('text', 'image', 'image-white'))) {
            add_action('wp_print_footer_scripts', array($this, 'insert_script'));
        }

        add_action('wp_head', array($this, 'injectIntoHead'));

        if (get_option('flattr_aut') || get_option('flattr_aut_page')) {
            add_action('the_content', array($this, 'injectIntoTheContent'), 32767);
        }
    }

    /**
     * prepare Dashboard
     */
    protected function backend() {
        add_action('wp_print_footer_scripts', array($this, 'insert_script'));
        add_action('admin_init', array($this, 'ajax'));
        add_action('admin_init', array($this, 'insert_wizard'));
        add_action('admin_init', array( $this, 'register_settings') );
        add_action('admin_init', array( $this, 'update_user_meta') );
        add_action('admin_menu', array( $this, 'settings') );
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            try
            {
                self::$instance = new self();
            }
            catch(Exception $e)
            {
                Flattr_Logger::log($e->getMessage(), 'Flattr_View::getInstance');
                self::$instance = false;
            }
        }
        return self::$instance;
    }

    public function ajax () {

        if (isset ($_GET["q"], $_GET["flattrJAX"])) {
            define('PASS', "passed");
            define('FAIL', "failed");
            define('WARN', "warn");

            $feature = $_GET["q"];

            $retval = array();
            $retval["result"] = FAIL;
            $retval["feature"] = $feature;
            $retval["text"] = $retval["result"];

            switch ($feature) {
                case "cURL" :
                    if (function_exists("curl_init")) {
                        $retval["result"] = PASS;
                        $retval["text"] = "curl_init";
                    }
                    break;
                case "php" :
                        $retval["text"] = PHP_VERSION;
                    if (version_compare(PHP_VERSION, '5.0.0', '>')) {
                        $retval["result"] = WARN;
                    }
                    if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
                        $retval["result"] = PASS;
                    }
                    break;
                case "oAuth" :
                    if (!class_exists('OAuth2Client')) {
                        $retval["result"] = PASS;
                        $retval["text"] = 'OAuth2Client';
                    }
                    break;
                case "Wordpress" :
                    require '../wp-includes/version.php';
                    $retval["text"] = $wp_version;
                    if (version_compare($wp_version, '3.0', '>=')) {
                        $retval["result"] = WARN;
                    }
                    if (version_compare($wp_version, '3.3', '>=')) {
                        $retval["result"] = PASS;
                    }
                    break;
                case "Flattr" :
                    $retval["text"] = "Flattr API v2";
                    
                    $ch = curl_init ('https://api.' . Flattr::FLATTR_DOMAIN . '/rest/v2/users/der_michael');
                    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true) ;
                    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false) ;
                    $res = curl_exec ($ch) ;
                    $res = json_decode($res);
                    if (isset($res->type)) {
                        $retval["text"] = "connection established";
                        $retval["result"] = PASS;
                    } else {
                        $retval["text"] = "curl connection error ".curl_error($ch);
                    }
                    curl_close ($ch) ;
                    break;
                default :
                    break;
            }

            print json_encode($retval);
            exit (0);
        } elseif (isset ($_GET["flattrss_api_key"], $_GET["flattrss_api_secret"], $_GET["flattrJAX"])) {
            $retval = array ( "result" => -1,
                              "result_text" => "uninitialised" );
            
            $callback = urlencode(home_url()."/wp-admin/admin.php?page=flattr/flattr.php");
            
            $key = $_GET["flattrss_api_key"];
            $sec = $_GET["flattrss_api_secret"];
            
            update_option('flattrss_api_key', $key);
            update_option('flattrss_api_secret', $sec);
            
            include_once 'flattr_client.php';
            
            $client = new OAuth2Client(array_merge(array(
                'client_id'         => $key,
                'client_secret'     => $sec,
                'base_url'          => 'https://api.' . Flattr::FLATTR_DOMAIN . '/rest/v2',
                'site_url'          => 'https://' . Flattr::FLATTR_DOMAIN,
                'authorize_url'     => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/authorize',
                'access_token_url'  => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/token',

                'redirect_uri'      => $callback,
                'scopes'            => 'thing+flattr',
                'token_param_name'  => 'Bearer',
                'response_type'     => 'code',
                'grant_type'        => 'authorization_code',
                'access_token'      => null,
                'refresh_token'     => null,
                'code'              => null,
                'developer_mode'    => false
            ))); 
            
            $retval["result_text"] = $client->authorizeUrl();
            $retval["result"] = 0;
            print json_encode($retval);

            exit (0);
        } elseif (isset ($_GET["code"], $_GET["flattrJAX"])) {
            $retval = array ( "result" => -1,
                              "result_text" => "uninitialised" );
            
            $callback = urlencode(home_url()."/wp-admin/admin.php?page=flattr/flattr.php");
            
            $key = get_option('flattrss_api_key');
            $sec = get_option('flattrss_api_secret');
            
            
            include_once 'flattr_client.php';
            
            $access_token = get_option('flattr_access_token', true);
            
            try { 
            
                $client = new OAuth2Client( array_merge(array(
                    'client_id'         => $key,
                    'client_secret'     => $sec,
                    'base_url'          => 'https://api.' . Flattr::FLATTR_DOMAIN . '/rest/v2',
                    'site_url'          => 'https://' . Flattr::FLATTR_DOMAIN,
                    'authorize_url'     => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/authorize',
                    'access_token_url'  => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/token',

                    'redirect_uri'      => $callback,
                    'scopes'            => 'thing+flattr',
                    'token_param_name'  => 'Bearer',
                    'response_type'     => 'code',
                    'grant_type'        => 'authorization_code',
                    'refresh_token'     => null,
                    'code'              => null,
                    'developer_mode'    => false,

                    'access_token'      => $access_token
                )));
                
                $user = $client->getParsed('/user');
                
                $retval["result_text"] = '<img style="float:right;width:48px;height:48px;border:0;" src="'. $user['avatar'] .'"/>'.
                                         '<h3>'.$user['username'].'</h3>'.
                                         '<ul><li>If this is your name and avatar authentication was successfull.</li>'.
                                         '<li>If the name displayed and avatar do not match <a href="/wp-admin/admin.php?page=flattr/flattr.php">start over</a>.</li>'.
                                         '<li>You need to authorize your blog once only!</li></ul>';
                
            } catch (Exception $e) {
                $retval["result_text"] = '<h3>Error</h3><p>'.$e->getMessage().'</p>';
            }
            
            
            $retval["result"] = 0;
            print json_encode($retval);

            exit (0);
        }
    }

    /**
     * initialize default options
     */
    protected static function default_options() {
        // If this is a new install - then set the defaults to some non-disruptive
        $new_install = (get_option('flattr_post_types', false) == false);

        add_option('flattr_global_button', $new_install? true : false);

        add_option('flattr_post_types', array('post','page'));
        add_option('flattr_lng', 'en_GB');
        add_option('flattr_aut', true);
        add_option('flattr_aut_page', true);
        add_option('flattr_atags', 'blog');
        add_option('flattr_cat', 'text');
        add_option('flattr_top', false);
        add_option('flattr_compact', false);
        add_option('flattr_popout_enabled', true);
        add_option('flattr_button_style', "js");
        add_option('flattrss_custom_image_url', get_bloginfo('wpurl') . '/wp-content/plugins/flattr/img/flattr-badge-large.png');
        add_option('user_based_flattr_buttons', false);
        add_option('user_based_flattr_buttons_since_time', time());
        add_option('flattrss_button_enabled', true);
        add_option('flattrss_relpayment_enabled', true);
        add_option('flattr_relpayment_enabled', true);
        add_option('flattrss_relpayment_escaping_disabled', false);
    }

    public function attsNormalize( $value ) {
        return ($value == 'n' || $value == 'no' || $value == 'off' || empty($value)) ? false : $value;
    }

    public function register_shortcode( $atts ) {
        $atts = array_map( array( $this, 'attsNormalize' ), $atts );

        $atts = shortcode_atts( array(
            'user'        => null,
            'popout'      => get_option('flattr_popout_enabled'),
            'url'         => null,
            'compact'     => get_option('flattr_compact'),
            'hidden'      => get_option('flattr_hide'),
            'language'    => str_replace('-', '_', get_bloginfo('language')),
            'category'    => get_option('flattr_cat', 'text'),
            'title'       => null,
            'description' => null,
            'tags'        => null,
            'type'        => get_option('flattr_button_style'),
        ), $atts );

        if ($atts['type'] == 'url') {
            $atts['type'] = 'autosubmitUrl';
        } else if ($atts['type'] == 'compact') {
            $atts['type'] = 'js';
            $atts['compact'] = true;
        }

        $button = $this->getNonPostButton(array(
            'user_id'     => $atts['user'],
            'popout'      => $atts['popout'] == true,
            'url'         => $atts['url'],
            'compact'     => $atts['compact'] == true,
            'hidden'      => $atts['hidden'] == true,
            'language'    => empty($atts['language']) ? 'en_GB' : $atts['language'],
            'category'    => $atts['category'],
            'title'       => $atts['title'],
            'description' => $atts['description'],
            'tags'        => $atts['tags'],
        ), $atts['type']);

        return empty($button) ? '' : $button;
    }

    public function register_widget() {
        register_widget( 'Flattr_Global_Widget' );
    }

    public function admin_script() {
        static $added = false;
        if (!$added) {
            $this->insert_script();
            $added = true;
        }
    }

    public function insert_script() {
        ?><script type="text/javascript">
          (function() {
            var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
            s.type = 'text/javascript';
            s.async = true;
            s.src = '<?php echo $this->proto . "api." . Flattr::FLATTR_DOMAIN . "/js/0.6/load.js?mode=auto"; ?>';
            t.parentNode.insertBefore(s, t);
          })();
        </script><?php
    }

    public function insert_wizard() {
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-datepicker' );

        wp_deregister_script( 'flattrscriptwizard' );
        wp_register_script( 'flattrscriptwizard', get_bloginfo('wpurl') . '/wp-content/plugins/flattr/wizard.js');
        wp_enqueue_script( 'flattrscriptwizard' );
    }

    public function update_user_meta() {
        if (isset($_POST['user_flattr_uid'], $_POST['user_flattr_cat'], $_POST['user_flattr_lng'])) {
            $user_id = get_current_user_id( );

            update_user_meta( $user_id, "user_flattr_uid", $_POST['user_flattr_uid'] );
            update_user_meta( $user_id, "user_flattr_cat", $_POST['user_flattr_cat'] );
            update_user_meta( $user_id, "user_flattr_lng", $_POST['user_flattr_lng'] );
        }
    }

    public function register_settings() {
        register_setting('flattr-settings-group', 'flattr_post_types');
        register_setting('flattr-settings-group', 'flattr_uid');
        register_setting('flattr-settings-group', 'flattr_lng');
        register_setting('flattr-settings-group', 'flattr_atags');
        register_setting('flattr-settings-group', 'flattr_cat');
        register_setting('flattr-settings-group', 'flattr_compact');
        register_setting('flattr-settings-group', 'flattr_top');
        register_setting('flattr-settings-group', 'flattr_hide');
        register_setting('flattr-settings-group', 'flattr_button_style');
        register_setting('flattr-settings-group', 'flattrss_custom_image_url');
        register_setting('flattr-settings-group', 'user_based_flattr_buttons');
        register_setting('flattr-settings-group', 'user_based_flattr_buttons_since_time', 'strtotime');
        register_setting('flattr-settings-group', 'flattr_global_button');
        register_setting('flattr-settings-group', 'flattrss_button_enabled');
        register_setting('flattr-settings-group', 'flattrss_relpayment_enabled');
        register_setting('flattr-settings-group', 'flattr_relpayment_enabled');
        register_setting('flattr-settings-group', 'flattrss_relpayment_escaping_disabled');
        register_setting('flattr-settings-group', 'flattr_aut_page');
        register_setting('flattr-settings-group', 'flattr_aut');
        register_setting('flattr-settings-group', 'flattr_popout_enabled');
    }

    public function settings() {
        $menutitle = __('Flattr', 'flattr');

        /**
         * Where to put the flattr settings menu
         */
        if (get_option('user_based_flattr_buttons')) {
            $page = add_submenu_page( "users.php", __('Flattr User Settings'), __('Flattr'), "edit_posts", __FILE__."?user", array($this, 'render_user_settings'));
            add_action( 'admin_print_styles-' . $page, array ($this, 'admin_styles'));
        }

        $cap = "manage_options";
        add_menu_page('Flattr',  $menutitle, $cap, __FILE__, '', get_bloginfo('wpurl') . '/wp-content/plugins/flattr'.'/img/flattr-icon_new.png');
        $page = add_submenu_page( __FILE__, __('Flattr'), __('Flattr'), $cap, __FILE__, array($this, 'render_settings'));

        /**
         * Using registered $page handle to hook stylesheet loading for admin pages
         * @see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
         */
        add_action( 'admin_print_styles-' . $page, array ($this, 'admin_styles'));
    }

    /**
     * Include custom styles for admin pages
     */
    public function admin_styles() {
        wp_register_style( 'flattr_admin_style', plugins_url('flattr.css', __FILE__) );
        wp_enqueue_style( 'flattr_admin_style' );
        wp_register_style( 'flattr-jquery-ui-style', plugins_url('jquery-ui/style.css', __FILE__) );
        wp_enqueue_style( 'flattr-jquery-ui-style' );
    }

    public function render_user_settings() {
        include('settings-templates/header.php');
        include('settings-templates/user.php');
        include('settings-templates/footer.php');
    }

    public function render_settings() {
        include('settings-templates/header.php');
        include('settings-templates/plugin.php');
        include('settings-templates/footer.php');
    }

    public function injectIntoHead() {
        if ( (!is_front_page() && !is_singular()) || is_attachment() || post_password_required() || !get_option('flattr_relpayment_enabled')) {
            return;
        }

        if (is_front_page()) {
            $url = get_option('flattr_global_button') ? $this->getGlobalButton('autosubmitUrl') : false;
        } else if (in_array(get_post_type(), (array)get_option('flattr_post_types', array()))) {
            $url = $this->getButton('autosubmitUrl');
        }

        if (!empty($url))
        {
            $link = '<link rel="payment" type="text/html" title="Flattr this!" href="' . esc_attr($url) . '" />' . "\n";
            echo apply_filters( 'flattr_inject_into_head', $link );
        }
    }

    /**
     * Insert the flattr button into the post content
     * @global type $post
     * @param type $content
     * @return string 
     */
    public function injectIntoTheContent($content) {
        static $processingPosts = array();

        global $post;

        if ( post_password_required($post->ID) ) {
            return $content;
        }

        if ( ( is_page($post) && !get_option('flattr_aut_page') ) || !get_option('flattr_aut') ) {
            return $content;
        }

        if (in_array(get_post_type(), (array)get_option('flattr_post_types', array())) && !is_feed()) {
            if (isset($processingPosts[$post->ID])) {
                return $content;
            } else {
                $processingPosts[$post->ID] = true;
            }
            $button = $this->getButton();
            $button = '<p class="wp-flattr-button">'.$button.'</p>';

            if ( get_option('flattr_top', false) ) {
                    $content = $button . $content;
            }
            else {
                    $content = $content . $button;
            }
            unset($processingPosts[$post->ID]);
        }
        return $content;
    }

    public function getGlobalButton($type = null) {
        $flattr_uid = get_option('flattr_uid');

        if (empty($flattr_uid)) {
            return false;
        }

        $buttonData = array(
            'user_id'     => $flattr_uid,
            'popout'      => (get_option('flattr_popout_enabled', true) ? 1 : 0 ),
            'url'         => site_url('/'),
            'compact'     => (get_option('flattr_compact', false) ? true : false ),
            'hidden'      => get_option('flattr_hide'),
            'language'    => str_replace('-', '_', get_bloginfo('language')),
            'category'    => get_option('flattr_cat', 'text'),
            'title'       => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'tags'        => get_option('flattr_atags', 'blog'),
        );

        return $this->getNonPostButton($buttonData, $type);
    }

    public function getNonPostButton(array $buttonData, $type = null) {
        switch (empty($type) ? get_option('flattr_button_style') : $type) {
            case "text":
                $retval = '<a href="'. esc_attr($this->getAutosubmitUrl($buttonData)) .'" title="Flattr" target="_blank">Flattr this!</a>';
                break;
            case "image":
                $retval = '<a href="'. esc_attr($this->getAutosubmitUrl($buttonData)) .'" title="Flattr" target="_blank">'. Flattr::getStaticImageButton($type) .'</a>';
                break;
			case "image-white":
                $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank">'. Flattr::getStaticImageButton($type) .'</a>';
				break;
            case "autosubmitUrl":
                $retval = $this->getAutosubmitUrl($buttonData);
                break;
            default:
                $retval = $this->getButtonCode($buttonData);
        }

        return $retval;
    }

    /**
     * https://flattr.com/submit/auto?user_id=USERNAME&url=URL&title=TITLE&description=DESCRIPTION&language=LANGUAGE&tags=TAGS&hidden=HIDDEN&category=CATEGORY
     * @see http://blog.flattr.net/2011/11/url-auto-submit-documentation/
     */
    public function getButton($type = null, $post = null) {
        if (!$post)
        {
            $post = $GLOBALS['post'];
        }

        if (get_post_meta($post->ID, '_flattr_btn_disabled', true))
        {
                return '';
        }
        if (get_option('user_based_flattr_buttons_since_time') == '' || intval(get_option('user_based_flattr_buttons_since_time')) < strtotime(get_the_time("c",$post))) {
            $flattr_uid = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_uid", true): get_option('flattr_uid');
        } else {
            $flattr_uid = get_option('flattr_uid');
        }

        if (!$flattr_uid) {
                return '';
        }

        $selectedLanguage = get_post_meta($post->ID, '_flattr_post_language', true);
        if (empty($selectedLanguage)) {
                $selectedLanguage = (get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_lng", true): get_option('flattr_lng');
        }

        $additionalTags = get_option('flattr_atags', 'blog');

        $selectedCategory = get_post_meta($post->ID, '_flattr_post_category', true);
        if (empty($selectedCategory)) {
                $selectedCategory = (get_option('user_based_flattr_buttons')&& get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true)!="")? get_user_meta(get_the_author_meta('ID'), "user_flattr_cat", true): get_option('flattr_cat');
        }

        $hidden = get_post_meta($post->ID, '_flattr_post_hidden', true);
        if ($hidden == '') {
                $hidden = get_option('flattr_hide', false);
        }

        $description = get_the_content('');
        $description = strip_shortcodes( $description );
        $description = apply_filters('the_content', $description);
        $description = str_replace(']]>', ']]&gt;', $description);
        $description = wp_trim_words( $description, 30, '...' );

        $customUrl = get_post_meta($post->ID, '_flattr_post_customurl', true);
        $buttonUrl = (empty($customUrl) ? get_permalink() : $customUrl);

        $buttonData = array(

            'user_id'     => $flattr_uid,
            'popout'      => (get_option('flattr_popout_enabled', true) ? 1 : 0 ),
            'url'         => $buttonUrl,
            'compact'     => (get_option('flattr_compact', false) ? true : false ),
            'hidden'      => $hidden,
            'language'    => $selectedLanguage,
            'category'    => $selectedCategory,
            'title'       => strip_tags(get_the_title()),
            'description' => $description,
            'tags'        => trim(strip_tags(get_the_tag_list('', ',', '')) . ',' . $additionalTags, ', ')

        );

        if (empty($buttonData['description']) && !in_array($buttonData['category'], array('images', 'video', 'audio')))
        {
                $buttonData['description'] = get_bloginfo('description');

                if (empty($buttonData['description']) || strlen($buttonData['description']) < 5)
                {
                        $buttonData['description'] = $buttonData['title'];
                }
        }


        if (isset($buttonData['user_id'], $buttonData['url'], $buttonData['language'], $buttonData['category']))
        {
                switch (empty($type) ? get_option('flattr_button_style') : $type) {
                    case "text":
                        $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank">Flattr this!</a>';
                        break;
                    case "image":
                        $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank">'. Flattr::getStaticImageButton($type) .'</a>';
                        break;
					case "image-white":
	                    $retval = '<a href="'. static_flattr_url($post).'" title="Flattr" target="_blank">'. Flattr::getStaticImageButton($type) .'</a>';
						break;
                    case "autosubmitUrl":
                        $retval = $this->getAutosubmitUrl($buttonData);
                        break;
                    default:
                        $retval = $this->getButtonCode($buttonData);
                }
                return $retval;
        }
        return '';
    }

    protected function getButtonCode($params)
    {
        $rev = '';
        if (!empty($params['user_id'])) {
            $rev .= sprintf('uid:%s;language:%s;category:%s;',
                $params['user_id'],
                $params['language'],
                $params['category']
            );

            if (!empty($params['tags']))
            {
                $rev .= 'tags:'. htmlspecialchars($params['tags']) .';';
            }

            if ($params['hidden'])
            {
                $rev .= 'hidden:1;';
            }
        }

        if (empty($params['popout']))
        {
            $rev .= 'popout:' . ($params['popout'] ? 1 : 0) . ';';
        }

        if (!empty($params['compact']))
        {
            $rev .= 'button:compact;';
        }

        return '<a class="FlattrButton" style="display:none;" href="' . esc_attr($params['url']) . '"' .
            (!empty($params['title']) ? ' title=" ' . esc_attr($params['title']) . '"' : '') .
            (!empty($rev) ? ' rev="flattr;' . $rev . '"' : '') .
            '>' .
                esc_html(empty($params['description']) ? '' : $params['description']) .
            '</a>';
    }

    function getAutosubmitUrl($params) {
        if (isset($params['compact'])) {
            unset($params['compact']);
        }

        $params = (empty($params['user_id']) ? array('url' => $params['url']) : array_filter($params));

        return 'https://' . Flattr::FLATTR_DOMAIN . '/submit/auto?' . http_build_query($params);
    }
	
	public static function getStaticImageButton($type=null) {
		
		$imgBaseURL = get_bloginfo('wpurl') . '/wp-content/plugins/flattr/img/';
		
		$srcset = "";
		$src = "";
		
		switch (empty($type) ? get_option('flattr_button_style') : $type) {
			
			case "image":
				$src = $imgBaseURL . "flattr-badge-large.png";
				
				$custom = get_option('flattrss_custom_image_url');
				if ($src != $custom) {
					$src = $custom;
				}
				
				$srcset .= $src;
				break;
			case "image-white":
				$src = $imgBaseURL . "flattr-badge-white.png";
				$srcset .= $src . ", " . $imgBaseURL . "flattr-badge-white@2x.png 2x";
				$srcset .= $src . ", " . $imgBaseURL . "flattr-badge-white@3x.png 3x";
				break;

		}

		$imgTag = '<img src="' . $src . '" srcset="'. $srcset . '" alt="Flattr this!"/>';
		
		return $imgTag;
				
	}

    protected static $languages;
    public static function getLanguages() {
        if (empty(self::$languages)) {
            self::$languages['sq_AL'] = 'Albanian';
            self::$languages['ar_DZ'] = 'Arabic';
            self::$languages['be_BY'] = 'Belarusian';
            self::$languages['bg_BG'] = 'Bulgarian';
            self::$languages['ca_ES'] = 'Catalan';
            self::$languages['zh_CN'] = 'Chinese';
            self::$languages['hr_HR'] = 'Croatian';
            self::$languages['cs_CZ'] = 'Czech';
            self::$languages['da_DK'] = 'Danish';
            self::$languages['nl_NL'] = 'Dutch';
            self::$languages['en_GB'] = 'English';
            self::$languages['et_EE'] = 'Estonian';
            self::$languages['fi_FI'] = 'Finnish';
            self::$languages['fr_FR'] = 'French';
            self::$languages['de_DE'] = 'German';
            self::$languages['el_GR'] = 'Greek';
            self::$languages['iw_IL'] = 'Hebrew';
            self::$languages['hi_IN'] = 'Hindi';
            self::$languages['hu_HU'] = 'Hungarian';
            self::$languages['is_IS'] = 'Icelandic';
            self::$languages['in_ID'] = 'Indonesian';
            self::$languages['ga_IE'] = 'Irish';
            self::$languages['it_IT'] = 'Italian';
            self::$languages['ja_JP'] = 'Japanese';
            self::$languages['ko_KR'] = 'Korean';
            self::$languages['lv_LV'] = 'Latvian';
            self::$languages['lt_LT'] = 'Lithuanian';
            self::$languages['mk_MK'] = 'Macedonian';
            self::$languages['ms_MY'] = 'Malay';
            self::$languages['mt_MT'] = 'Maltese';
            self::$languages['no_NO'] = 'Norwegian';
            self::$languages['pl_PL'] = 'Polish';
            self::$languages['pt_PT'] = 'Portuguese';
            self::$languages['ro_RO'] = 'Romanian';
            self::$languages['ru_RU'] = 'Russian';
            self::$languages['sr_RS'] = 'Serbian';
            self::$languages['sk_SK'] = 'Slovak';
            self::$languages['sl_SI'] = 'Slovenian';
            self::$languages['es_ES'] = 'Spanish';
            self::$languages['sv_SE'] = 'Swedish';
            self::$languages['th_TH'] = 'Thai';
            self::$languages['tr_TR'] = 'Turkish';
            self::$languages['uk_UA'] = 'Ukrainian';
            self::$languages['vi_VN'] = 'Vietnamese';
        }

        return self::$languages;
    }

    protected static $categories;
    public static function getCategories() {
        if (empty(self::$categories)) {
            self::$categories = array('text', 'images', 'audio', 'video', 'software', 'rest');
        }
        return self::$categories;
    }
}

class Flattr_Global_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'widget_flattrglobal', 'description' => 'Contains a Flattr button for flattring the site' );
        parent::__construct( 'flattrglobalwidget', 'Flattr Site Button', $widget_ops );
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        echo $before_widget;

        if ($title) {
            echo $before_title . $title . $after_title;
        }

        echo Flattr::getInstance()->getGlobalButton();

        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
        ?><p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
    }
}


function static_flattr_url($post) {
    $id = $post->ID;
    $md5 = md5($post->post_title);

    return (get_bloginfo('wpurl') .'/?flattrss_redirect&amp;id='.$id.'&amp;md5='.$md5);
}

function flattr_post2rss($content) {
    global $post;

    $flattr = "";
    $flattr_post_types = (array)get_option('flattr_post_types', array());

    $meta = get_post_meta($post->ID, '_flattr_btn_disable');

    $postmeta = isset($meta['_flattr_btn_disable'])? $meta['_flattr_btn_disable'] : true;

    if (($postmeta) && is_feed() && in_array(get_post_type(), $flattr_post_types)) {
		
		// Determine which button style to use. Default: image-white.
		$button_style_pref = get_option('flattr_button_style');
		$button_type = $button_style_pref;
		if ($button_type != "image" && $button_type != "image-white") {
			$button_type = "image-white";
		}
				
		$flattr.= '<p><a href="'. static_flattr_url($post) .'">' . Flattr::getStaticImageButton($button_type) . '</a></p>';
		
    }
    return ($content.$flattr);
}

add_action('init', 'new_flattrss_redirect');
add_action('init', 'flattr_init');

function flattr_init() {
    include_once 'init.php';
}

function new_flattrss_redirect() {
    include_once 'redirect.php';
}

if(get_option('flattrss_button_enabled')) {
    add_filter('the_content_feed', 'flattr_post2rss',999999);
}

add_action('atom_head', 'flattr_feed_atom_head');
add_action('rss2_head', 'flattr_feed_rss2_head');
add_action('atom_entry', 'flattr_feed_atom_item');
add_action('rss2_item', 'flattr_feed_rss2_item');

function flattr_feed_escape($string) {
    if (get_option('flattrss_relpayment_escaping_disabled')) {
        return $string;
    }
    return esc_attr($string);
}

function flattr_feed_atom_head() {
    if (get_option('flattrss_relpayment_enabled') && get_option('flattr_global_button')) {
        echo '	<link rel="payment" title="Flattr this!" href="' . flattr_feed_escape(Flattr::getInstance()->getGlobalButton('autosubmitUrl')) . '" type="text/html" />'."\n";
    }
}

function flattr_feed_rss2_head() {
    if (get_option('flattrss_relpayment_enabled') && get_option('flattr_global_button')) {
        echo '	<atom:link rel="payment" title="Flattr this!" href="' . flattr_feed_escape(Flattr::getInstance()->getGlobalButton('autosubmitUrl')) . '" type="text/html" />'."\n";
    }
}

function flattr_feed_atom_item() {
    global $post;
    if (get_option('flattrss_relpayment_enabled') && in_array(get_post_type($post), (array)get_option('flattr_post_types', array()))) {
        $url = Flattr::getInstance()->getButton("autosubmitUrl", $post);
        if (!empty($url)) {
            echo '		<link rel="payment" title="Flattr this!" href="' . flattr_feed_escape($url) . '" type="text/html" />'."\n";
        }
    }
}

function flattr_feed_rss2_item() {
    global $post;
    if (get_option('flattrss_relpayment_enabled') && in_array(get_post_type($post), (array)get_option('flattr_post_types', array()))) {
        $url = Flattr::getInstance()->getButton("autosubmitUrl", $post);
        if (!empty($url)) {
            echo '	<atom:link rel="payment" title="Flattr this!" href="' . flattr_feed_escape($url) . '" type="text/html" />'."\n";
        }
    }
}


$call_n = 0; # Do not delete! It will break autosubmit.
function new_flattrss_autosubmit_action () {

    global $call_n;

    $call_n += 1;
    $post = $_POST;

    if (($post['post_status'] == "publish") && (get_post_meta($post['ID'], "flattrss_autosubmited", true)=="") && ($call_n == 2) && (get_the_time('U') <= time())) {

        $url = get_permalink($post['ID']);
        $tagsA = get_the_tags($post['ID']);
        $tags = "";

        if (!empty($tagsA)) {
            foreach ($tagsA as $tag) {
                if (strlen($tags)!=0){
                    $tags .=",";
                }
                $tags .= $tag->name;
            }
        }

        $additionalTags = get_option('flattr_atags', 'blog');
        if (!empty($additionalTags)) {
            $tags .= ',' . $additionalTags;
        }
        $tags = trim($tags, ', ');

        $category = "text";
        if (get_option('flattr_cat')!= "") {
            $category = get_option('flattr_cat');
        }

        $language = "en_EN";
        if (get_option('flattr_lng')!="") {
            $language = get_option('flattr_lng');
        }

        if (!function_exists('getExcerpt')) {
            function getExcerpt($post, $excerpt_max_length = 1024) {

                $excerpt = $post['post_excerpt'];
                if (trim($excerpt) == "") {
                        $excerpt = $post['post_content'];
                }

                $excerpt = strip_shortcodes($excerpt);
                $excerpt = strip_tags($excerpt);
                $excerpt = str_replace(']]>', ']]&gt;', $excerpt);

                // Hacks for various plugins
                $excerpt = preg_replace('/httpvh:\/\/[^ ]+/', '', $excerpt); // hack for smartyoutube plugin
                $excerpt = preg_replace('%httpv%', 'http', $excerpt); // hack for youtube lyte plugin

                // Try to shorten without breaking words
                if ( strlen($excerpt) > $excerpt_max_length ) {
                    $pos = strpos($excerpt, ' ', $excerpt_max_length);
                    if ($pos !== false) {
                            $excerpt = substr($excerpt, 0, $pos);
                    }
                }

                // If excerpt still too long
                if (strlen($excerpt) > $excerpt_max_length) {
                    $excerpt = substr($excerpt, 0, $excerpt_max_length);
                }

                return $excerpt;
            }
        }

        $content = preg_replace(array('/\<br\s*\/?\>/i',"/\n/","/\r/", "/ +/"), " ", getExcerpt($post));
        $content = strip_tags($content);

        if (strlen(trim($content)) == 0) {
            $content = "(no content provided...)";
        }

        $title = strip_tags($post['post_title']);
        $title = str_replace(array("\"","\'"), "", $title);

        $oauth_token = get_option('flattrss_api_key');
        $oauth_token_secret = get_option('flattrss_api_secret');

        $flattr_access_token = get_option('flattr_access_token');

        if (get_option('user_based_flattr_buttons')< strtotime(get_the_time("c",$post))) {
            $user_id = get_current_user_id();
            $flattr_access_token = (get_user_meta( $user_id, "user_flattrss_api_oauth_token",true)!="")?get_user_meta( $user_id, "user_flattrss_api_oauth_token",true):get_option('flattr_access_token');

        }

        include_once 'flattr_client.php';

        $client = new OAuth2Client( array_merge(array(
            'client_id'         => $oauth_token,
            'client_secret'     => $oauth_token_secret,
            'base_url'          => 'https://api.' . Flattr::FLATTR_DOMAIN . '/rest/v2',
            'site_url'          => 'https://' . Flattr::FLATTR_DOMAIN,
            'authorize_url'     => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/authorize',
            'access_token_url'  => 'https://' . Flattr::FLATTR_DOMAIN . '/oauth/token',

            'redirect_uri'      => urlencode(home_url()."/wp-admin/admin.php?page=flattr/flattr.php"),
            'scopes'            => 'thing+flattr',
            'token_param_name'  => 'Bearer',
            'response_type'     => 'code',
            'grant_type'        => 'authorization_code',
            'refresh_token'     => null,
            'code'              => null,
            'developer_mode'    => false,

            'access_token'      => $flattr_access_token,
        )));

        if(!function_exists("encode")) {
            function encode($string) {
                if (function_exists("mb_detect_encoding")) {
                    $string = (mb_detect_encoding($string, "UTF-8") == "UTF-8" )? $string : utf8_encode($string);
                } else {
                    $string = utf8_encode($string);
                }
                return $string;
            }
        }

        $server = $_SERVER["SERVER_NAME"];
        $server = preg_split("/:/", $server);
        $server = $server[0];

        $hidden = (get_option('flattr_hide', true) || get_post_meta($post->ID, '_flattr_post_hidden', true) ||$server == "localhost")? true:false;

        try {
            $response = $client->post('/things', array (
                    "url" => $url, 
                    "title" => encode($title), 
                    "category" => $category, 
                    "description" => encode($content), 
                    "tags"=> $tags, 
                    "language" => $language, 
                    "hidden" => $hidden)
                );

            if (strpos($response->responseCode,'20') === 0)
                add_post_meta($post['ID'], "flattrss_autosubmited", "true");

        } catch (Exception $e) {

        }
    }
}

if (get_option('flattrss_autosubmit') && get_option('flattr_access_token')) {
    add_action('save_post','new_flattrss_autosubmit_action',9999);
}

/**
 * prints the Flattr button
 * Use this from your template
 * @param type the button type. See Flattr->getButton for details.
 */
function the_flattr_permalink()
{
    echo Flattr::getInstance()->getButton();
}

// Make sure that the flattr object is ran at least once
$flattr = Flattr::getInstance();
