<?php
/*
Plugin Name: ContentStudio
Description: ContentStudio provides you with powerful blogging & social media tools to keep your audience hooked by streamlining the process for you to discover and share engaging content on multiple blogging & social media networks
Version: 1.2.9
Author: ContentStudio
Author URI: http://contentstudio.io/
Plugin URI: http://contentstudio.io/
*/

/**
 * add the meta SEO title for the web page if the post has SEO title available
 */
// include_once(ABSPATH . 'wp-includes/pluggable.php');
function cstu_add_wpseo_title()
{

    global $post;
    if ($post) {
        if (isset($post->ID)) {
            if ($post->post_type == 'post') {
                $meta_title = get_post_meta($post->ID, 'contentstudio_wpseo_title');
                if(isset($meta_title[0]) && $meta_title[0]){
                    return $meta_title[0];
                }
            }
        }
    }
}

add_filter('pre_get_document_title', 'cstu_add_wpseo_title');

// Check for existing class
if (! class_exists('contentstudio')) {

    class ContentStudio
    {
        protected $api_url = 'https://api-prod.contentstudio.io/';

        protected $assets = 'https://contentstudio.io/img';

        private $version = "1.2.9";

        protected $contentstudio_id = '';

        protected $blog_id = '';

        public $cstu_plugin;

        protected $cstu_plugin_assets_dir;

        const INVALID_MESSAGE = 'Invalid API Key, please make sure you have correct API key added.';

        const INVALID_MESSAGE_POST_API = 'Invalid API Key, please make sure you have correct API key added.';

        const UNKNOWN_ERROR_MESSAGE = 'An Unknown error occurred while uploading media file.';

        /**
         * Add ContentStudio and settings link to the admin menu
         */
        public function __construct()
        {

            $this->cstu_plugin = plugin_basename(__FILE__);

            $this->cstu_plugin_assets_dir = plugin_dir_url(__FILE__) . "assets/";

            $this->hooks();
            register_activation_hook(__FILE__, [$this, 'activation']);
            if (is_admin()) {
                $this->register_admin_hooks();
            }

            // create db table and register webhooks
            $this->create_cstu_database_table();
            $this->register_global_hooks();

            // register style

        }



        /**
         * Creaate a database table for the SEO settings
         */
        public function create_cstu_database_table()
        {
            global $wpdb;
            $table_name = $wpdb->prefix."seo";
            $sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  post_id mediumint(9) NOT NULL default 0,
			  title varchar (100) not NULL,
			  description varchar (100) default null,
			  slug varchar (100) ,PRIMARY KEY (id));";

            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            maybe_create_table($table_name, $sql);
        }

        /**
         * Add a ContentStudio icon to the menu
         */
        public function add_menu()
        {
           if(current_user_can('editor') || current_user_can('administrator')){
                add_menu_page('ContentStudio Publisher', 'ContentStudio', 'edit_posts', 'contentstudio_settings', [
                    $this,
                    'connection_page',
                ], $this->cstu_plugin_assets_dir . "menu-logo.png", '50.505');
                // Settings link to the plugin listing page.
            }
        }

        /**
         * Register global webhooks
         */
        public function register_global_hooks()
        {

            add_action('init', [$this, 'cstu_verfiy_wp_user']);
            add_action('init', [$this, 'cstu_check_token']);
            add_action('init', [$this, 'cstu_get_blog_authors']);
            add_action('init', [$this, 'cstu_get_blog_categories']);
            add_action('init', [$this, 'cstu_create_new_post']);
            add_action('init', [$this, 'cstu_update_post']);
            add_action('init', [$this, 'cstu_is_installed']);
            add_action('init', [$this, 'cstu_unset_token']);
            add_action('init', [$this, 'cstu_get_metadata']);
            add_action('init', [$this, 'cstu_create_nonce_for_post']);
            add_action('init', [$this, 'cstu_is_upload_dir_exists']);
            add_action('wp_head', [$this, 'add_cstu_custom_stylesheet']);
            add_action('wp_head', [$this, 'add_cstu_meta_data']);
            add_action('init', [$this, 'cstu_change_post_status']);
            if (! function_exists('get_plugins')) {
                require_once ABSPATH.'wp-admin/includes/plugin.php';
            }
        }

        /**
         * Add meta SEO title for the users's shared blog posts.
         */
        function add_cstu_meta_data()
        {
            global $post;
            if ($post) {
                if (isset($post->ID)) {
                    $meta_description = get_post_meta($post->ID, 'contentstudio_wpseo_description');
                    if ($meta_description) {
                        echo '<meta name="description" content="'.esc_attr($meta_description[0]).'" />'."\n";
                    }

                    //$meta_title = get_post_meta($post->ID, 'contentstudio_wpseo_title');
                    //echo '<title>'.$meta_title[0].'</title>' . "\n";

                    //return $meta_title[0];
                }
            }
        }

        /**
         * Adding a custom stylesheet to the WordPress blog, added due to the drag and drop snippet to be shown on the WordPress.
         */

        function add_cstu_custom_stylesheet()
        {
            wp_register_style('contentstudio-curation', // handle name
                plugin_dir_url(__FILE__).'_inc/contentstudio_curation.css', // the URL of the stylesheet
                [], // an array of dependent styles
                '1.0', // version number
                'screen');
        }

        /**
         * Registers admin-only hooks.
         */
        public function register_admin_hooks()
        {

            add_action('admin_menu', [$this, 'add_menu']);

            add_filter("plugin_action_links_$this->cstu_plugin", [$this, 'plugin_settings_link'], 2, 2);

            // ajax requests
            add_action('wp_ajax_add_cstu_api_key', [$this, 'add_cstu_api_key']);
            // Add check for activation redirection
            add_action('admin_init', [$this, 'activation_redirect']);
            // load resources

        }

        public function hooks()
        {

            register_activation_hook(__FILE__, [$this, 'activation']);
            register_deactivation_hook(__FILE__, [$this, 'deactivation']);
        }

        /**
         * plugin activation, deactivation and uninstall hooks
         */
        public function activation()
        {
            register_uninstall_hook(__FILE__, ['contentstudio', 'uninstall']);
            // Set redirection to true
            add_option('contentstudio_redirect', true);
        }

        /**
         * on plugin deactivation
         */
        public function deactivation()
        {
            delete_option('contentstudio_redirect');
            delete_option('contentstudio_token');
        }

        /**
         * Find all of the image urls that are in the content description
         *
         * @param $content mixed The data of the post
         * @return array|null result whether it contains images or not
         */
        public function cstu_find_all_images_urls($content)
        {
            $pattern = '/<img[^>]*src=["\']([^"\']*)[^"\']*["\'][^>]*>/i'; // find img tags and retrieve src
            preg_match_all($pattern, $content, $urls, PREG_SET_ORDER);
            if (empty($urls)) {
                return null;
            }
            foreach ($urls as $index => &$url) {
                $images[$index]['alt'] = preg_match('/<img[^>]*alt=["\']([^"\']*)[^"\']*["\'][^>]*>/i', $url[0], $alt) ? $alt[1] : null;
                $images[$index]['url'] = $url = $url[1];
            }
            foreach (array_unique($urls) as $index => $url) {
                $unique_array[] = $images[$index];
            }

            return $unique_array;
        }
 
        /**
         *
         * Check whether the plugin yoast is active
         *
         * @return bool status of the plugin true if active/false if inactive
         */

        function is_yoast_active()
        {
            $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            foreach ($active_plugins as $plugin) {
                if (strpos($plugin, 'wp-seo')) {
                    return true;
                }
            }

            return false;
        }

        /**
         *
         * Check whether the All in one SEO plugin is active
         *
         * @return bool status of the plugin true if active/false if inactive
         */

        function is_all_in_one_seo_active()
        {

            $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            foreach ($active_plugins as $plugin) {
                if (strpos($plugin, 'all_in_one_seo_pack')) {
                    return true;
                }
            }

            return false;
        }

        // on plugin removal
        public function uninstall()
        {
            delete_option('contentstudio_redirect');
            delete_option('contentstudio_token');
        }

        /**
         * Checks to see if the plugin was just activated to redirect them to settings
         */
        public function activation_redirect()
        {


            if (get_option('contentstudio_redirect', false)) {
                // Redirect to settings page
                if (delete_option('contentstudio_redirect')) {
                    // If the plugin is being network activated on a multisite install
                    if (is_multisite() && is_network_admin()) {
                        $redirect_url = network_admin_url('plugins.php');
                    } else {
                        $redirect_url = 'admin.php?page=contentstudio_settings';
                    }

                    if (wp_safe_redirect($redirect_url)) {
                        // NOTE: call to exit after wp_redirect is per WP Codex doc:
                        //       http://codex.wordpress.org/Function_Reference/wp_redirect#Usage
                        exit;
                    }
                }
            }
        }

        // filters plugins section

        public function plugin_settings_link($actions, $file)
        {
            if (false !== strpos($file, 'plugin')) {
                $url = "admin.php?page=contentstudio_settings";
                $actions['settings'] = '<a href="'.esc_url($url).'">Settings</a>';
            }

            return $actions;
        }

        // ajax section

        public function add_cstu_api_key()
        {
            if (isset($_POST['data'])) {

                if ($_POST['data']['security']) {
                    $nonce = sanitize_text_field($_POST['data']['security']);
                    if (! wp_verify_nonce($nonce, 'ajax-nonce')) {
                        echo json_encode(['status' => false, 'message' => 'Invalid security token provided.']);
                        die();
                    }
                }

                if (isset($_POST['data']['key'])) {
                    if (strlen($_POST['data']['key']) == 0) {
                        echo json_encode(['status' => false, 'message' => 'Please enter your API key']);
                        die();
                    }

                    $api_key = sanitize_text_field($_POST['data']['key']);

                    $response = json_decode($this->is_cstu_connected($api_key), true);
                    if ($response['status'] == false) {
                        echo json_encode($response);
                        die();
                    }
                    if ($response['status'] == true) {
                        // if successfully verified.

                        if (add_option('contentstudio_token', $api_key) == false) {
                            update_option('contentstudio_token', $api_key);
                        }

                        echo json_encode([
                            'status' => true,
                            'message' => 'Your blog has been successfully connected with ContentStudio.',
                        ]);
                        die();
                    } else {
                        echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                        die();
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => 'Please enter your API key']);
                    die();
                }
            } else {
                echo json_encode(['status' => false, 'message' => 'Please enter your API key']);
                die();
            }
        }

        public function cstu_is_installed()
        {
            if (isset($_REQUEST['cstu_is_installed']) && ($_REQUEST['cstu_is_installed'])) {
                $plugin_data = get_plugin_data(__FILE__);

                echo json_encode([
                    'status' => true,
                    'message' => 'ContentStudio plugin installed',
                    'version' => $plugin_data['Version'],
                ]);
                die();
            }
        }

        // check token direct ajax request.
        public function cstu_check_token()
        {
            if (isset($_REQUEST['token_validity']) && isset($_REQUEST['token'])) {
                $valid = $this->do_validate_cstu_token($_REQUEST['token']);

                // server side token validation required.

                if ($valid) {
                    echo json_encode(['status' => true, 'message' => 'Token validated successfully.']);
                    die();
                } else {
                    echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                    die();
                }
            }
        }

        // validate token from the server to local.
        public function do_validate_cstu_token($token)
        {
            $token = sanitize_text_field($token);
            if (get_option('contentstudio_token') === $token) {
                return true;
            }

            return false;
        }

        /**
         * validate username and password.
         * 
         */
        public function do_validate_wp_user($user_info)
        {
            $user_info = explode(":", base64_decode($user_info));
            $user = get_user_by('login', $user_info[0]);
            if ($user && $user->ID != 0) {
                if (wp_check_password($user_info[1], $user->data->user_pass, $user->ID)) { // validate password
                    if ($user->has_cap('publish_posts') && $user->has_cap('edit_posts')) {
                        return ['status' => true, 'message' => 'User validated successfully.'];
                    } else {
                        $error = "You don't have permission to publish posts.";
                    }
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Invalid username.";
            }
            return ['status' => false, 'message' => $error];
        }


        /**
         * verify wordpress user and set token
         */
        public function cstu_verfiy_wp_user()
        {
            if (isset($_REQUEST['cstu_verfiy_wp_user']) && $_REQUEST['cstu_verfiy_wp_user']) {
                try {
                    if (isset($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['token']) && $_REQUEST['username'] && $_REQUEST['password'] && $_REQUEST['token']) {
                        $user = get_user_by('login', $_REQUEST['username']); // validate username
                        if ($user && $user->ID != 0) {
                            if (wp_check_password($_REQUEST['password'], $user->data->user_pass, $user->ID)) { // validate password
                                if ($user->has_cap('publish_posts') && $user->has_cap('edit_posts')) {
                                    // set token for later requests validation.
                                    $token = sanitize_text_field($_REQUEST['token']);
                                    update_option('contentstudio_token', $token);
                                    
                                    echo json_encode(['status' => true, 'message' => 'User verification completed successfully!']);
                                    die();
                                } else {
                                    echo json_encode(['status' => false, 'message' => "You don't have permissions or the capabilities to publish or edit posts."]);
                                    die();
                                }
                            } else {
                                echo json_encode(['status' => false, 'message' => 'The password that you entered is incorrect.']);
                                die();
                            }
                        } else {
                            echo json_encode(['status' => false, 'message' => 'No user exists with your provided username.']);
                            die();
                        }
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Invalid request parameters.']);
                        die();
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        'status' => false, 'message' => self::UNKNOWN_ERROR_MESSAGE,
                        'line' => $e->getLine(), 'error_message' =>  $e->getMessage()
                    ]);
                }
            }
        }

        /**
         * unset token ajax request
         */
        public function cstu_unset_token()
        {
            if (isset($_REQUEST['cstu_unset_token']) && isset($_REQUEST['token'])) {

                $valid = $this->do_validate_cstu_token($_REQUEST['token']);

                if ($valid) {
                    delete_option('contentstudio_token');
                    echo json_encode(['status' => true, 'message' => 'Your API key has been removed successfully!']);
                    die();
                } else {
                    // TODO: need to brainstorm here.
                    echo json_encode([
                        'status' => false,
                        'message' => 'API key mismatch, please enter the valid API key.',
                    ]);
                    die();
                }
            }
        }

        /**
         * Check if the user blog is connected or not, send a remote request to the ContentStudio.
         *
         * @param $token string - token to send for the request
         * @return mixed
         */
        public function is_cstu_connected($token)
        {
            $plugin_data = get_plugin_data(__FILE__);

            $payload = [
                'body' => [
                    'token' => $token,
                    "name" => get_bloginfo("name"),
                    "description" => get_bloginfo("description"),
                    "wpurl" => get_bloginfo("wpurl"),
                    "url" => get_bloginfo("url"),
                    'version' => $plugin_data['Version'],
                ],
            ];

            return wp_remote_post($this->api_url.'blog/wordpress_plugin', $payload)['body'];
        }

        /**
         * Gets blog meta data
         */

        public function cstu_get_metadata()
        {
            if (isset($_REQUEST['cstu_get_metadata'], $_REQUEST['token'])) {

                $valid = $this->do_validate_cstu_token($_REQUEST['token']);

                if (!$valid) {
                    echo json_encode([
                        'status' => false,
                        'message' => self::INVALID_MESSAGE_POST_API,
                    ]);
                    die();
                }


                $varsbloginfo                = array(
                    "name"                   => get_bloginfo( "name" ),
                    "description"            => get_bloginfo( "description" ),
                    "wpurl"                  => get_bloginfo( "wpurl" ),
                    "url"                    => get_bloginfo( "url" ),
                    "language"               => get_bloginfo( "language" ),
                    "charset"                => get_bloginfo( 'charset' ),
                    "version"                => get_bloginfo( "version" ),
                    "timezone_string"        => get_option( "timezone_string" ),
                    "gmt_offset"             => get_option( "gmt_offset" ),
                    "server_time"            => time(),
                    "server_date"            => date( 'c' ),
                    "token"                  => get_option('contentstudio_token'),
                    //"is_connected"           => $this->is_cstu_connected($token),
                    "plugin_version"         => $this->version,
                    "php_version"            => PHP_VERSION,
                    "php_disabled_fn"        => ini_get( 'disable_functions' ),
                    "php_disabled_cl"        => ini_get( 'disable_classes' ),
                    //"use_wp_json_encode"     => $this->use_wp_json_encode,
                    //"first_transport"        => $http->_get_first_available_transport( $this->api ),
                    // misc blog //
                    "site_url"               => get_option( 'siteurl' ),
                    "pingback_url"           => get_bloginfo( "pingback_url" ),
                    "rss2_url"               => get_bloginfo( "rss2_url" ),
                );

                $varsbloginfo["debug"] = array();

                $theme                                         = wp_get_theme();
                $varsbloginfo["debug"]["theme"]                = array();
                $varsbloginfo["debug"]["theme"]["Name"]        = $theme->get( 'Name' );
                $varsbloginfo["debug"]["theme"]["ThemeURI"]    = $theme->get( 'ThemeURI' );
                $varsbloginfo["debug"]["theme"]["Description"] = $theme->get( 'Description' );
                $varsbloginfo["debug"]["theme"]["Author"]      = $theme->get( 'Author' );
                $varsbloginfo["debug"]["theme"]["AuthorURI"]   = $theme->get( 'AuthorURI' );
                $varsbloginfo["debug"]["theme"]["Version"]     = $theme->get( 'Version' );
                $varsbloginfo["debug"]["theme"]["Template"]    = $theme->get( 'Template' );
                $varsbloginfo["debug"]["theme"]["Status"]      = $theme->get( 'Status' );
                $varsbloginfo["debug"]["theme"]["Tags"]        = $theme->get( 'Tags' );
                $varsbloginfo["debug"]["theme"]["TextDomain"]  = $theme->get( 'TextDomain' );
                $varsbloginfo["debug"]["theme"]["DomainPath"]  = $theme->get( 'DomainPath' );

                echo json_encode([
                    'status' => true,
                    'message' => 'Meta Data Of Blog',
                    'usermetadeata' => $varsbloginfo,
                ]);
                die();
            }
        }

        /**
         * Get a list of blog authors
         */
        public function cstu_get_blog_authors()
        {
            if (isset($_REQUEST['authors'], $_REQUEST['token'])) {
                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {

                    if (!isset($_REQUEST['user_info'])) {
                        echo json_encode(['status' => false, 'message' => 'user_info is required']);
                        die();
                    }
                    // validate user info
                    $result = $this->do_validate_wp_user($_REQUEST['user_info']);
                    if ($result['status'] == false) {
                        echo json_encode($result);
                        die();
                    }


                    $authors = get_users();
                    $return_authors = [];
                    foreach ($authors as $author) {
                        if (!$author->has_cap('publish_posts') || !$author->has_cap('edit_posts')) {
                            continue;
                        }
                        $return_authors[] = [
                            "display_name" => $author->data->display_name,
                            "user_id" => $author->ID,
                        ];
                    }
                    echo json_encode($return_authors);
                    die();
                } else {
                    echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                    die();
                }
            }
        }

        /**
         * Get a list of blog categories
         */
        public function cstu_get_blog_categories()
        {
            if (isset($_REQUEST['categories'], $_REQUEST['token'])) {
                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {

                    if (!isset($_REQUEST['user_info'])) {
                        echo json_encode(['status' => false, 'message' => 'user_info is required']);
                        die();
                    }
                    // validate user info
                    $result = $this->do_validate_wp_user($_REQUEST['user_info']);
                    if ($result['status'] == false) {
                        echo json_encode($result);
                        die();
                    }

                    $args = [
                        "hide_empty" => 0,
                        "type" => "post",
                        "orderby" => "name",
                        "order" => "ASC",
                    ];
                    $categories = get_categories($args);
                    $return_categories = [];

                    foreach ($categories as $category) {
                        $return_categories[] = [
                            "name" => $category->cat_name,
                            "term_id" => $category->term_id,
                        ];
                    }
                    echo json_encode($return_categories);
                    die();
                } else {
                    echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                    die();
                }
            }
        }

        /**
         * Check if the wp-content/upload directory exists for the user blog.
         *
         * It is called from the ContentStudio Remote Server.
         */
        public function cstu_is_upload_dir_exists()
        {
            if (isset($_REQUEST) && isset($_REQUEST['cstu_is_upload_dir_exists']) && isset($_REQUEST['token'])) {
                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {
                    $base_dir = wp_upload_dir()['basedir'];
                    if (! is_dir($base_dir)) {
                        echo json_encode([
                            'status' => true,
                            'message' => 'Your WordPress wp-content/uploads/ directory does not exist. Please create a directory first to enable featured images/media uploads.',
                        ]);
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Directory already exists.']);
                    }
                    die();
                } else {
                    echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                    die();
                }
            }
        }

        /**
         * @param $post_id mixed This will check whether the seo data already exists in database
         *
         * @return bool true if exists/false if empty
         */
        public function cstu_seo_exists($post_id)
        {
            global $wpdb;
            $sql = $wpdb->prepare("select id from ".$wpdb->prefix."seo where post_id='%d'", (int) $post_id);
            $get_post = $wpdb->get_results($sql);
            if (count($get_post)) {
                return true;
            }

            return false;
        }

        /**
         * Create a nonce for create and update post.
         * This nonce will used for create and update post.
         * 
         */
        public function cstu_create_nonce_for_post()
        {
            if (isset($_REQUEST) && isset($_REQUEST['cstu_create_nonce_for_post'], $_REQUEST['token'])) {
                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {

                    if (!isset($_REQUEST['user_info'])) {
                        echo json_encode(['status' => false, 'message' => 'user_info is required']);
                        die();
                    }
                    // validate user info
                    $result = $this->do_validate_wp_user($_REQUEST['user_info']);
                    if ($result['status'] == false) {
                        echo json_encode($result);
                        die();
                    }

                    $nonce = wp_create_nonce('cstu_nonce_for_post');
                    echo json_encode(['status' => true, 'message' => 'Nonce created successfully', 'nonce' => $nonce]);
                    die();
                } else {
                    echo json_encode(['status' => false, 'message' => self::INVALID_MESSAGE]);
                    die();
                }
            }
        }

        /**
         * Create a new WordPress post, action is called from the REMOTE ContentStudio Server.
         * This action is called from the ContentStudio Remote Server.
         * Post data is sent from the ContentStudio Remote Server.
         * Request will be validated using the token (contentstudio_token) and wordpress nonce.
         *
         */
        public function cstu_create_new_post()
        {
            if (isset($_REQUEST) && isset($_REQUEST['cstu_create_new_post'], $_REQUEST['token'], $_REQUEST['nonce'])) {

                // validate the token

                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {

                    // check if the nonce is valid
                    if (!wp_verify_nonce($_REQUEST['nonce'], 'cstu_nonce_for_post')) {
                        echo json_encode(['status' => false, 'message' => 'Invalid wordpress nonce', 'invalid_nonce' => true]);
                        die();
                    }

                    if (!isset($_REQUEST['user_info'])) {
                        echo json_encode(['status' => false, 'message' => 'user_info is required']);
                        die();
                    }

                    $result = $this->do_validate_wp_user($_REQUEST['user_info']);
                    if ($result['status'] == false) {
                        echo json_encode($result);
                        die();
                    }

                    // request post title is available

                    if (isset($_REQUEST['post'])) {
                        $post_title = sanitize_text_field($_REQUEST['post']['post_title']);
                        // check for the post title and make sure it does not exists.

                        if (isset($post_title) && $post_title) {
                            global $wpdb;
                            $post_title = wp_strip_all_tags($post_title);
                            $sql = $wpdb->prepare("select ID from ".$wpdb->posts." where post_title='%s' AND  post_status = 'publish'", $post_title);
                            $get_posts_list = $wpdb->get_results($sql);
                            if (count($get_posts_list)) {
                                $cstu_post_update = get_page_by_title( $post_title, '', 'post' );
                                $getid = $cstu_post_update->ID;
                                echo json_encode([
                                    'status' => false,
                                    'message' => "Post already exists on your blog with title '$getid'.",
                                ]);
                                die();
                            }
                        }
                    }

                    // get list of categories

                    $categories = explode(',', sanitize_text_field($_REQUEST['post']['post_category']));

                    $this->kses_remove_filters();
                    $post_id = 0;
                    if (isset($_REQUEST['post']['post_id']) && $_REQUEST['post']['post_id']) {
                        $post_id = (int) sanitize_text_field($_REQUEST['post']['post_id']);
                    }

                    $tags = [];
                    if (isset($_REQUEST['post']['tags']))
                        $tags = explode(',', sanitize_text_field($_REQUEST['post']['tags']));

                    // insert the post
                    $post_author = (int) sanitize_text_field($_REQUEST['post']['post_author']);
                    $post_content = sanitize_meta('post_content', $_REQUEST['post']['post_content'], 'post');
                    $post_status = sanitize_text_field($_REQUEST['post']['post_status']);
                    $post_title = sanitize_text_field($_REQUEST['post']['post_title']);

                    $post = wp_insert_post([
                        'ID' => $post_id,
                        'post_title' => $post_title,
                        'post_author' => $post_author,
                        'post_content' => $post_content,
                        'post_status' => $post_status,
                        'post_category' => $categories,
                        'tags_input' => $tags
                    ]);

                    if (! $post or $post == 0) {
                        $post = wp_insert_post([
                            'post_author' => $post_author,
                            'post_content' => $post_content,
                            'post_status' => $post_status,
                            'post_category' => $categories,
                            'tags_input' => $tags
                        ]);
                        global $wpdb;
                        $wpdb->update($wpdb->posts, ['post_title' => wp_strip_all_tags((string) $post_title)], ['ID' => $post]);
                        // slug scenario
                    }

                    // get post
                    $get_post = get_post($post);

                    // set the tags
                    if (isset($_REQUEST['post']['terms'])) $this->cstu_set_tags($get_post);

                    // seo settings
                    $this->set_cstu_metadata_post($get_post);
                    $this->set_cstu_yoast_settinsg($get_post);
                    $this->set_cstu_all_in_one_seo($get_post);


                    $post_response = [
                        'post_id' => $get_post->ID,
                        'link' => get_permalink($get_post->ID),
                        'status' => true,
                        'allow_url_fopen' => ini_get('allow_url_fopen')
                    ];

                    $this->uploadFeatureImages($post_response, $_REQUEST['post']['featured_image'], $post, $post_title);
                    echo json_encode($post_response);
                    die();

                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => self::INVALID_MESSAGE_POST_API,
                    ]);
                    die();
                }
            }
        }

        private function uploadFeatureImages(&$response,$image,$post,$post_title) {
            try {
                // reload the post again to get the latest url.
                if (isset($image) && $image) {
                    $status_code = wp_remote_get($image)['response']['code'];
                    // if the status is valid process for upload.
                    if ($status_code == 301 || $status_code == 200) {
                        $img = $this->cstu_generate_image($image, $post,$post_title);

                        if (!$img['status']) {
                            $response['status'] = false;
                            $response['warning_message'] = $img['message'];
                            $response['resp'] = $img;
                        }
                    } else {
                        $response['status'] = false;
                        $response['warning_message'] = 'Post featured image seems to be down. Image HTTP status code is '.$status_code;
                    }
                }
            }
            catch (\Exception $e){
                $response['status'] = false;
                $response['message'] = self::UNKNOWN_ERROR_MESSAGE;
                $response['line'] =  $e->getLine();
                $response['error_message'] = $e->getMessage();
            }

        }

        /**
         * Updates an existing WordPress post, action is called from the REMOTE ContentStudio Server.
         * This action is called from the ContentStudio Remote Server.
         * Post data is sent from the ContentStudio Remote Server.
         * Request will be validated using the token (contentstudio_token) and wordpress nonce.
         *
         */
        public function cstu_update_post()
        {
            if (isset($_REQUEST) && isset($_REQUEST['cstu_update_post'], $_REQUEST['token'], $_REQUEST['nonce'])) {

                // validate the token

                $valid = $this->do_validate_cstu_token($_REQUEST['token']);
                if ($valid) {

                    // check if the nonce is valid
                    if (!wp_verify_nonce($_REQUEST['nonce'], 'cstu_nonce_for_post')) {
                        echo json_encode(['status' => false, 'message' => 'Invalid wordpress nonce', 'invalid_nonce' => true]);
                        die();
                    }

                    if (!isset($_REQUEST['user_info'])) {
                        echo json_encode(['status' => false, 'message' => 'user_info is required']);
                        die();
                    }

                    // validate the username and password
                    $result = $this->do_validate_wp_user($_REQUEST['user_info']);
                    if ($result['status'] == false) {
                        echo json_encode($result);
                        die();
                    }


                    $categories = explode(',', sanitize_text_field($_REQUEST['post']['post_category']));

                    $this->kses_remove_filters();
                    $post_id = 0;
                    if (isset($_REQUEST['post']['post_id']) && $_REQUEST['post']['post_id']) {
                        $post_id = (int) sanitize_text_field($_REQUEST['post']['post_id']);
                    }

                    // update the post
                    $post_author = (int) sanitize_text_field($_REQUEST['post']['post_author']);
                    $post_content = sanitize_meta('post_content', $_REQUEST['post']['post_content'], 'post');
                    $post_status = sanitize_text_field($_REQUEST['post']['post_status']);
                    $post_title = sanitize_text_field($_REQUEST['post']['post_title']);

                    $post = wp_update_post([
                        'ID' => $post_id,
                        'post_title' => $post_title,
                        'post_author' => $post_author,
                        'post_content' => $post_content,
                        'post_status' => $post_status,
                        'post_category' => $categories,
                    ]);

                    if (! $post or $post == 0) {
                        $post = wp_update_post([
                            'post_title' => $post_title,
                            'post_author' => $post_author,
                            'post_content' => $post_content,
                            'post_status' => $post_status,
                            'post_category' => $categories,
                        ]);
                        global $wpdb;
                        $wpdb->update($wpdb->posts, ['post_title' => wp_strip_all_tags((string) $post_title)], ['ID' => $post]);
                        // slug scenario
                    }

                    // get post

                    $get_post = get_post($post);

                    // set the tags

                    if (isset($_REQUEST['post']['terms'])) $this->cstu_set_tags($get_post);

                    // seo settings
                    $this->set_cstu_metadata_post($get_post);
                    $this->set_cstu_yoast_settinsg($get_post);
                    $this->set_cstu_all_in_one_seo($get_post);

                    // reload the post again to get the latest url.

                    if (isset($_REQUEST['post']['featured_image']) && $_REQUEST['post']['featured_image']) {
                        // perform http request to see the status code of the image.
                        $status_code = wp_remote_get($_REQUEST['post']['featured_image'])['response']['code'];

                        // if the status is valid process for upload.

                        if ($status_code == 301 || $status_code == 200) {
                            $img = $this->cstu_generate_image($_REQUEST['post']['featured_image'], $post,$_REQUEST['post']['post_title']);
                            if ($img['status']) {
                                echo json_encode([
                                    'status' => true,
                                    'post_id' => $get_post->ID,
                                    'link' => get_permalink($get_post->ID),
                                ]);
                                die();
                            } else {
                                echo json_encode([
                                    'status' => false,
                                    'warning_message' => $img['message'],
                                    'post_id' => $get_post->ID,
                                    'link' => get_permalink($get_post->ID),
                                ]);
                                die();
                            }
                        } else {
                            echo json_encode([
                                'status' => false,
                                'warning_message' => 'Post featured image seems to be down. Image HTTP status code is '.$status_code,
                                'post_id' => $get_post->ID,
                                'link' => get_permalink($get_post->ID)//get_post_permalink($get_post->ID),
                            ]);
                            die();
                        }
                    } else {
                        echo json_encode([
                            'status' => true,
                            'post_id' => $get_post->ID,
                            'link' => get_permalink($get_post->ID),
                        ]); // get_post_permalink($get_post->ID)
                        die();
                    }
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => self::INVALID_MESSAGE_POST_API,
                    ]);
                    die();
                }
            }
            /*else {
                echo json_encode(['status' => false, 'message' => "error"]);
                die();
            }*/
        }

        /**
         * Set the meta description so that when we publish our content, we show that to the end-user instead of our personal one.
         *
         * @param $get_post object WordPress post that we retrieved.
         */
        public function set_cstu_metadata_post($get_post)
        {
            try {
                // setting up meta description
                $meta_description = null;
                if (isset($_REQUEST['post']['post_meta_description'])) {
                    $meta_description = sanitize_text_field($_REQUEST['post']['post_meta_description']);
                }
                if ($meta_description) {
                    if (! get_post_meta($get_post->ID, 'contentstudio_wpseo_description')) {
                        add_post_meta($get_post->ID, 'contentstudio_wpseo_description', $meta_description, true);
                    } else {
                        update_post_meta($get_post->ID, 'contentstudio_wpseo_description', $meta_description);
                    }
                }
                $meta_title = null;
                if (isset($_REQUEST['post']['post_meta_title'])) {
                    $meta_title = sanitize_text_field($_REQUEST['post']['post_meta_title']);
                }

                if ($meta_title) {
                    if (! get_post_meta($get_post->ID, 'contentstudio_wpseo_title')) {
                        add_post_meta($get_post->ID, 'contentstudio_wpseo_title', $meta_title, true);
                    } else {
                        update_post_meta($get_post->ID, 'contentstudio_wpseo_title', $meta_title);
                    }
                }

                $slug = null;
                if (isset($_REQUEST['post']['post_meta_url'])) {
                    global $wpdb;

                    $slug = sanitize_text_field($_REQUEST['post']['post_meta_url']);
                    $value = wp_unique_post_slug($slug, $get_post->ID, $get_post->post_status, $get_post->post_type, $get_post->post_parent);
                    $slug = $value;

                    wp_update_post([
                        'post_name' => (string) $slug,
                        'ID' => $get_post->ID,
                    ]);
                    //$wpdb->update($wpdb->posts, ['post_name' => (string) $slug], ['ID' => $get_post->ID]);
                }
            }
            catch (\Exception $e){

            }

        }

        /**
         * Configure the SEO settings for the YOAST SEO plugin.
         *
         * @param $post object - Post object so that we can get the ID of a POST.
         */
        public function set_cstu_yoast_settinsg($post)
        {
            try {
                if ($this->is_yoast_active()) {
                    global $wpdb;
                    $sql = $wpdb->prepare("select object_id from ".$wpdb->prefix."yoast_seo_meta where object_id='%d'", $post->ID);
                    $get_object = $wpdb->get_results($sql);
                    if (! count($get_object)) {
                        $wpdb->insert($wpdb->prefix."yoast_seo_meta", [
                            "object_id" => $post->ID,
                            "internal_link_count" => 0,
                            "incoming_link_count" => 0,
                        ]);
                    }
                    $wpdb->insert($wpdb->postmeta, [
                        "post_id" => $post->ID,
                        "meta_key" => "_yoast_wpseo_title",
                        "meta_value" => sanitize_text_field($_REQUEST['post']['post_meta_title']),
                    ]);
                    $wpdb->insert($wpdb->postmeta, [
                        "post_id" => $post->ID,
                        "meta_key" => "_yoast_wpseo_metadesc",
                        "meta_value" => sanitize_text_field($_REQUEST['post']['post_meta_description']),
                    ]);
                }
            }
            catch (\Exception $e){

            }

        }

        /**
         * Configure the SEO settings for the All-in-one SEO plugin.
         *
         * @param $post object - Post object so that we can get the ID of a POST.
         */
        public function set_cstu_all_in_one_seo($post)
        {
            try {
                if ($this->is_all_in_one_seo_active()) {
                    global $wpdb;
                    $wpdb->insert($wpdb->postmeta, [
                        "post_id" => $post->ID,
                        "meta_key" => "_aioseop_description",
                        "meta_value" => sanitize_text_field($_REQUEST['post']['post_meta_description']),
                    ]);
                    $wpdb->insert($wpdb->postmeta, [
                        "post_id" => $post->ID,
                        "meta_key" => "_aioseop_title",
                        "meta_value" => sanitize_text_field($_REQUEST['post']['post_meta_title']),
                    ]);
                    $slug = sanitize_text_field($_REQUEST['post']['post_meta_url']);
                    if ($slug) {
                        $wpdb->insert($wpdb->postmeta, [
                            "post_id" => $post->ID,
                            "meta_key" => "_wp_old_slug",
                            "meta_value" => $slug,
                        ]);
                    }
                }
            }
            catch (\Exception $e){

            }

        }

        /**
         * Download a featured image and store the web server of the user.
         *
         * @param $image_url - target url to download
         * @param $post_id - post id for which it will be attached/
         * @return array - return of a status true or false with a message.
         */
        public function cstu_generate_image($image_url, $post_id, $post_title)
        {

            try {
                //get the upload dir of a website
                $upload_dir = wp_upload_dir();

                // if there is no upload dir folder made for the user website
                if (isset($upload_dir['error']) && $upload_dir['error']) return ['status' => false, 'message' => $upload_dir['error']];

                // check allow_url_fopen is disable or enable
                if ( !ini_get('allow_url_fopen') ) return ['status' => false, 'message' => 'allow_url_fopen is disable from PHP Configuration.'];

                // check if the url contains query params or arguments, remove those.
                if(strpos($image_url, '?') !== false)   $image_url = substr($image_url, 0, strpos($image_url, '?'));
                if(strpos($image_url, '#') !== false)   $image_url = substr($image_url, 0, strpos($image_url, '#'));
                $image_data = file_get_contents($image_url);

                // if the url contains the amazon url. download the image and get its mimetype
                if (strpos($image_url, 'contentstudioio.s3.amazonaws.com') !== false) {

                    $filename = basename($image_url);
                    $img_headers = wp_remote_get($image_url);
                    // check content type and assign a type of image to the filename.
                    switch ($img_headers['headers']['content-type']){
                        case 'image/png':
                            $filename .= '.png';
                            break;
                        case 'image/jpg':
                        case 'image/jpeg':
                            $filename .= '.jpg';
                            break;
                        case 'image/gif':
                            $filename .= '.gif';
                            break;
                    }
                }
                // if it is ytimg link, get the correct id by splitting it.
                elseif (strpos($image_url, 'ytimg.com') !== false) $filename = explode('/', $image_url)[4].'_'.basename($image_url);
                else $filename = basename($image_url);

                $modified_filename = sanitize_file_name($post_title);
                if(strpos($filename, '.') === false){
                    $filename = $modified_filename . '.png';
                } else{
                    $filename =  $modified_filename .  substr($filename, strrpos($filename, '.'));
                }

                // create a file with its name
                if (wp_mkdir_p($upload_dir['path'])) $file = $upload_dir['path'].'/'.$filename;
                else $file = $upload_dir['basedir'].'/'.$filename;


                // put the content
                $resp = file_put_contents($file, $image_data);
                $wp_filetype = wp_check_filetype($filename, null);

                // prepare attachment payload
                $attachment = [
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => $filename,
                    'post_content' => '',
                    'post_status' => 'inherit',
                ];
                $attach_id = wp_insert_attachment($attachment, $file, $post_id);

                // store the image and set it for the post

                require_once(ABSPATH.'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $file);
                $res1 = wp_update_attachment_metadata($attach_id, $attach_data);
                $res2 = set_post_thumbnail($post_id, $attach_id);
                update_post_meta($attach_id, '_wp_attachment_image_alt', $post_title);
                if ($res2) {
                    return ['status' => true];
                } else {
                    return ['status' => false, 'message' => self::UNKNOWN_ERROR_MESSAGE];
                }
            }
            catch (\Exception $e){
                return ['status' => false, 'message' => self::UNKNOWN_ERROR_MESSAGE,
                    'line'=>$e->getLine(), 'error_message' =>  $e->getMessage()];
            }

        }

        /**
         * Render a ContentStudio plugin page.
         */
        public function connection_page()
        {
            if (! current_user_can('edit_posts')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            $token = get_option('contentstudio_token');
            //$response =  ['status'=>true];  //NOTE: for locally testing...
            $response = json_decode($this->is_cstu_connected($token), true);

            $response['reconnect'] = false;
            if (isset($_GET['reconnect']) && $_GET['reconnect'] == 'true') {
                $response['reconnect'] = true;
            }

            $response['security_plugins'] = $this->cstu_check_installed_security_plugins();
            // Save the data to the error log so you can see what the array format is like.

            $this->load_resources();

            include(sprintf("%s/page.php", dirname(__FILE__)));
        }

        /**
         * Analyzing the security plugins that the user may have installed, and giving them a headsup to
         * whitelist our server's IP address so that there are no problems while authentication being done
         * with ContentStudio.
         *
         * NOTE: we are not modifying anything to these plugins, just checking their status wether they have been
         * activated, if activated, show a notification to the user.
         *
         * @return array - returns list of an array that is used for displaying the name of the plugins.
         */
        function cstu_check_installed_security_plugins()
        {
            $activated_plugins = get_option('active_plugins');
            $response = [
                'wordfence' => $this->is_plugin_activated($activated_plugins, 'wordfence/wordfence.php'),
                'jetpack' => $this->is_plugin_activated($activated_plugins, 'jetpack/jetpack.php'),
                '6scan' => $this->is_plugin_activated($activated_plugins, '6scan-protection/6scan.php'),
                'wp_security_scan' => $this->is_plugin_activated($activated_plugins, 'wp-security-scan/index.php'),
                'wp_all_in_one_wp_security' => $this->is_plugin_activated($activated_plugins, 'all-in-one-wp-security-and-firewall/wp-security.php'),
                'bulletproof_security' => $this->is_plugin_activated($activated_plugins, 'bulletproof-security/bulletproof-security.php'),
                'better_wp_security' => $this->is_plugin_activated($activated_plugins, 'better-wp-security/better-wp-security.php'),
                'limit_login_attempts_reloaded' => $this->is_plugin_activated($activated_plugins, 'limit-login-attempts-reloaded/limit-login-attempts-reloaded.php'),
                'limit_login_attempts' => $this->is_plugin_activated($activated_plugins, 'limit-login-attempts/limit-login-attempts.php'),
                'lockdown_wp_admin' => $this->is_plugin_activated($activated_plugins, 'lockdown-wp-admin/lockdown-wp-admin.php'),
                'miniorange_limit_login_attempts' => $this->is_plugin_activated($activated_plugins, 'miniorange-limit-login-attempts/mo_limit_login_widget.php'),
                'wp_cerber' => $this->is_plugin_activated($activated_plugins, 'wp-cerber/wp-cerber.php'),
                'wp_limit_login_attempts' => $this->is_plugin_activated($activated_plugins, 'wp-limit-login-attempts/wp-limit-login-attempts.php'),
                'sucuri_scanner' => $this->is_plugin_activated($activated_plugins, 'sucuri-scanner/sucuri.php'),
                //                'limit_login_attempts_reloaded' => $this->is_plugin_activated($all_plugins, 'limit-login-attempts-reloaded/limit-login-attempts-reloaded.php'),
            ];

            return $response;
        }

        /**
         * Check if the value of the plugin name is found in the list of plugins that have been activated by the user.
         *
         * @param $plugins_list - list of activated plugins by the user
         * @param $file_name - name of the file for the plugin
         * @return bool
         */
        function is_plugin_activated($plugins_list, $file_name)
        {
            if (in_array($file_name, $plugins_list)) {
                return true;
            }

            return false;
        }

        /**
         * Load the style
         */
        function load_resources()
        {
            wp_enqueue_style('contentstudio.css', plugin_dir_url(__FILE__).'_inc/contentstudio.css', [], 0.01, false);
            wp_enqueue_style('contentstudio_curation.css', plugin_dir_url(__FILE__).'_inc/contentstudio_curation.css', [], 0.01, false);
            wp_enqueue_script('notify.min.js', plugin_dir_url(__FILE__).'_inc/notify.min.js', ['jquery'], 0.01, false);
            wp_enqueue_script('helper.js', plugin_dir_url(__FILE__).'_inc/helper.js', ['jquery'], 0.01, false);
            wp_localize_script('helper.js', 'ajax_object', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('add_cstu_api_key'),
            ]);
        }

        /**
         * Prepare a payload for the request.
         *
         * @param $url - fully qualified URL to target
         * @param $body - payload to send to the request.
         * @return mixed
         */
        public function prepare_request($url, $body)
        {
            $params = [
                'method' => 'POST',
                'body' => $this->array_decode_entities($body),
            ];

            return $this->perform_request($this->api_url.$url, $params);
        }

        /**
         * Provide a layer of compatibility by detecting and retrying after an initial error state.  All attempts to
         * access external resources should use this function.
         *
         * @param $url - fully qualified URL to target
         * @param null $params - optional used in cases where caller wishes to POST
         *
         * @return mixed - result of $http->request(...) call or WP_Error instance
         */
        public function perform_request($url, $params = null)
        {
            $http = new WP_Http;

            $out = $this->perform_http_request($http, $url, false, $params);

            if (is_wp_error($out)) {
                $out = $this->perform_http_request($http, $url, true, $params);
            }

            return $out;
        }

        /**
         * @param $http - instance of an HTTP client, providing a `request` function
         * @param $url - fully qualified URL to target
         * @param bool|false $skip_ssl_verify - if true, will install filters that should prevent SSL cert validation
         * for next request
         * @param null $params - optional used in cases where caller wishes to POST
         *
         * @return mixed - result of $http->request(...) call or WP_Error instance
         */
        public function perform_http_request($http, $url, $skip_ssl_verify = false, $params = null)
        {

            if (isset($skip_ssl_verify) && (true === $skip_ssl_verify)) {
                // For the CURL SSL verifying, some websites does not have the valid SSL certificates.
                add_filter('https_ssl_verify', '__return_false');
                add_filter('https_local_ssl_verify', '__return_false');
            }

            if (isset($params)) {
                /** @noinspection PhpUndefinedMethodInspection */
                return $http->request($url, $params);
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                return $http->request($url);
            }
        }

        /**
         * Decode entities from the array
         *
         * @param $array
         * @return array
         */

        public function array_decode_entities($array)
        {
            $new_array = [];

            foreach ($array as $key => $string) {
                if (is_string($string)) {
                    $new_array[$key] = html_entity_decode($string, ENT_QUOTES);
                } else {
                    $new_array[$key] = $string;
                }
            }

            return $new_array;
        }

        /**
         * @param string $param
         */
        public function sanitize(&$param = '')
        {
            if (is_string($param)) {
                $param = esc_sql($param);
                $param = esc_html($param);
            }
        }

        /**
         * Remove the filters before altering the post.
         */
        function kses_remove_filters()
        {
            // Post filtering
            remove_filter('content_save_pre', 'wp_filter_post_kses');
            remove_filter('excerpt_save_pre', 'wp_filter_post_kses');
            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
        }

        /**
         * change status of the post from the draft to publish or publish to draft.
         */

        public function cstu_change_post_status()
        {
            if (isset($_REQUEST) && isset($_REQUEST['cstu_change_post_status'])) {
                $post_id = (int) sanitize_text_field($_REQUEST['post']['id']);
                $status = sanitize_text_field($_REQUEST['post']['status']);
                global $wpdb;
                $sql = $wpdb->prepare("select post_status from ".$wpdb->posts." where ID = '%d'", $post_id);
                $post_status = $wpdb->get_results($sql)[0]->post_status;
                if ($post_status == $status) {
                    echo json_encode(['status' => false, 'message' => "Your post status is already $post_status"]);
                    die();
                }
                $result = $wpdb->update($wpdb->posts, ["post_status" => $status], ["ID" => $post_id]);
                if ($result) {
                    echo json_encode(['status' => true, 'message' => 'Your post status has been updated']);
                    die();
                }
            }
        }

        /**
         *
         * Assign a post with the tags that the user may have assigned.
         *
         * @param $get_post mixed The post object to which the tags are to be assigned
         */

        public function cstu_set_tags($get_post)
        {
            try {
                global $wpdb;
                $post_tags = sanitize_text_field($_REQUEST['post']['terms']);
                if (! is_array($post_tags)) {
                    $post_tags = explode(",", $post_tags);
                }
                $terms = [];
                foreach ($post_tags as $tag) {
                    $term = term_exists($tag);
                    if ($term) {

                        $sql = $wpdb->prepare("select term_taxonomy_id from ".$wpdb->term_taxonomy." where term_id='%s'", $term);
                        $result = $wpdb->get_results($sql);
                        $term_taxonomy_id = $result[0]->term_taxonomy_id;
                        $wpdb->query("UPDATE ".$wpdb->term_taxonomy." SET count = count + 1 where term_id=".$term);
                        $terms[] = $term_taxonomy_id;
                    } else {
                        if ($_REQUEST['post']['post_status'] == 'publish') {
                            $new_term = wp_insert_term($tag, "category");
                            $wpdb->query("UPDATE ".$wpdb->term_taxonomy." SET count = count + 1 where term_id=".$new_term["term_id"]);
                            $terms[] = $new_term["term_taxonomy_id"];
                        } else {
                            $new_term = wp_insert_term($tag, "post_tag");
                            $wpdb->query("UPDATE ".$wpdb->term_taxonomy." SET count = count + 1 where term_id=".$new_term["term_id"]);
                            $terms[] = $new_term["term_taxonomy_id"];
                        }
                    }
                }
                foreach ($terms as $term) {
                    $data = [$get_post->ID, $term];
                    global $wpdb;
                    $post_query = $wpdb->prepare("insert into ".$wpdb->term_relationships." (object_id,term_taxonomy_id) values ('%s','%s')", $data);
                    $wpdb->get_results($post_query);
                }
            }
            catch (\Exception $e){

            }

        }
    }

    function my_cstu_scripts() {
        wp_register_style('contentstudio-dashboard', plugin_dir_url(__FILE__).("_inc/main.css"), [], '1.0.0');

        wp_enqueue_style('contentstudio-dashboard');

    }

    add_action( 'wp_enqueue_scripts', 'my_cstu_scripts' );

    return new ContentStudio();
}
