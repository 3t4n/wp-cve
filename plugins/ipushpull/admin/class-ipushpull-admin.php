<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ipushpull-logger.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/admin
 * @author     ipushpull <support@ipushpull.com>
 */
class Ipushpull_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $user;
    private $current_user = null;
    private $token = "";

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }    

    private function set_user()
    {

        global $wpdb;

        if (is_user_logged_in()) {
            // Set the current user data
            $this->current_user = wp_get_current_user();
        } else {
            // If no user is logged in, set $this->current_user to null or any default value
            $this->current_user = null;
        }

        // check for user token
        // $user = $wpdb->get_row("SELECT * FROM {$wpdb->options} where option_name = 'ipushpull_user' ");

        // // make api call to check if token is valid
        // if ($user) {
        //     $response = wp_remote_get(IPUSHPULL_URL . '/wordpress/default/user/', array(
        //         'headers' => array(
        //             'Content-Type' => 'application/json',
        //             'Authorization' => 'Bearer ' . $user->option_value
        //         ),
        //     ));
        //     if (isset($response['body'])) {
        //         $data = json_decode($response['body'], true);
        //         if (!isset($data['data']['id'])) {
        //             $wpdb->replace($wpdb->options, array('option_name' => 'ipushpull_user', 'option_value' => ''));
        //             $user = null;
        //         } else {
        //             $this->token = $user->option_value;
        //             $user = $data['data'];
        //         }
        //     }
        //     $this->user = $user;
        // }

        // $this->current_user = wp_get_current_user();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ipushpull_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ipushpull_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ipushpull-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ipushpull_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ipushpull_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ipushpull-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    2.0.0
     */

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
//        add_options_page( 'ipushpull', 'ipushpull', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page') );

        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
        add_menu_page(
            'ipushpull',
            'ipushpull',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin'),
            'https://ipushpull.s3.amazonaws.com/static/prd/icon-16.png'
        );

        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' )
        add_submenu_page(
            $this->plugin_name,
            'App',
            'App',
            'manage_options',
            'ipushpull',
            array($this, 'display_plugin')
        );
        add_submenu_page(
            $this->plugin_name,
            'Support',
            'Support',
            'manage_options',
            'ipushpull-support',
            array($this, 'display_support')
        );
        add_submenu_page(
            $this->plugin_name,
            'Maintenance',
            'Maintenance',
            'manage_options',
            'ipushpull-maintenance',
            array($this, 'display_maintenance')
        );

    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    2.0.0
     */

    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    2.0.0
     */

    public function display_plugin()
    {

        global $wpdb;

        $this->set_user();
//        print_r($this->current_user);
//        exit('eh');

        $params = array();

        // ref?
        if (isset($_GET['ref'])) {
            $user = isset($_GET['token']) ? $_GET['token'] : -1;
            $wpdb->replace($wpdb->options, array('option_name' => 'ipushpull_user', 'option_value' => $user));
            wp_redirect(IPUSHPULL_URL . '/pages/');
            die();
        } else {
            $user = $this->user;
            $domain = get_bloginfo('url');
            $split = '://';
            $scheme = $this->scheme();
            $domainsParts = explode($split, $domain);
            $domain = $scheme . $split . $domainsParts[1];

            $params['domain'] = $domain;
            $params['email'] = $this->current_user->user_email;
            $params['first_name'] = $this->current_user->user_firstname;
            $params['last_name'] = $this->current_user->user_lastname;
            $params['display_name'] = $this->current_user->display_name;
        }

        $query = http_build_query($params);

        include_once('partials/ipushpull-admin-display.php');

    }

    public function display_support()
    {
        include_once('partials/ipushpull-admin-support.php');
    }

    public function display_maintenance()
    {
        // if (phpversion() >= '5.4.0') {
        //     if (session_status() == PHP_SESSION_NONE) session_start();
        // } else {
        //     if (!session_id()) session_start();
        // }
        // $this->set_user();
        // $user = $this->user;
        // if (!$user) wp_redirect(admin_url('admin.php?page=ipushpull'));

        global $wpdb;
        $posts = $wpdb->get_results("SELECT ID, guid, post_title, post_date, post_content FROM $wpdb->posts where post_content LIKE '%[ipushpull_page%' AND post_status = 'publish' ORDER BY post_date DESC");
        $check = $_SESSION['ipushpull_check'] = md5(time());
        $action = admin_url('admin-ajax.php?action=ipushpull_post');
        include_once('partials/ipushpull-admin-maintenance.php');

    }

    public function ipushpull_post()
    {

        // if (phpversion() >= '5.4.0') {
        //     if (session_status() == PHP_SESSION_NONE) session_start();
        // } else {
        //     if (!session_id()) session_start();
        // }

        $this->set_user();

        global $wpdb;

        $re = '/\[ipushpull_page (.*?)\]/';

        if ($_POST && $_POST['check'] == $_SESSION['ipushpull_check'] && $_POST['id']) {

            $which = $_POST['which'];
            $id = (int)$_POST['id'];
            $post = $wpdb->get_row("SELECT ID, post_title, post_content FROM $wpdb->posts where ID = $id");
            if (empty($post->ID)) {
                json_encode(array('error' => true, 'message' => 'ID not found'));
                wp_die();
            };

            preg_match_all($re, $post->post_content, $matches, PREG_SET_ORDER, 0);
            $codes = array();
            $embed_codes = array();
            if ($matches) {
                foreach ($matches as $match) {
                    $codes[] = shortcode_parse_atts($match[0]);
                    $embed_codes[] = $match[0];
                }
            } else {
                json_encode(array('error' => true, 'message' => 'No pages found'));
                wp_die();
            }
            $uuids = array();

            $search = array();
            $replace = array();

            foreach ($codes as $i => $code) {

                if (empty($code['page'])) {
                    $url = IPUSHPULL_API_URL . "/internal/page_content/{$code['uuid']}/";
                } else {
                    $url = IPUSHPULL_API_URL . "/domains/name/{$code['folder']}/page_content/name/{$code['page']}/?client_seq_no=0";
                }

                $response = wp_remote_get($url, array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token
                    ),
                ));

                $data = json_decode($response['body'], true);

                if (empty($data['id'])) {
                    $uuids[] = array(
                        'code' => $code,
                        'html' => '',
                        'old_html' => $embed_codes[$i],
                        'uuid' => '',
                        'id' => '',
                        'post_id' => $id,
                        'data' => $data,
                        'token' => $this->token
                    );
                    continue;
                }

                $html = $code[0];
                if ($which == 'page') {
                    $is_public = $data['is_public'] ? true : false;
                    $is_obscured_public = false;
                    $html .= " page=\"{$data['name']}\" folder=\"{$data['domain_name']}\"";
                } else {
                    $is_public = false;
                    $is_obscured_public = true;
                    $html .= " uuid=\"{$data['uuid']}\"";
                }
                foreach ($code as $key => $value) {
                    if (is_int($key) || $key == 'page' || $key == 'folder' || $key == 'uuid') continue;
                    $value = trim($value, ' []');
                    $html .= " {$key}=\"{$value}\"";
                }
                if (isset($code[1]))
                    $html .= ' ' . $code[1];
                else
                    $html .= ' ]';

                $search[] = $embed_codes[$i];
                $replace[] = $html;

                // update page
                $url = IPUSHPULL_API_URL . "/domains/{$data['domain_id']}/pages/{$data['id']}/";
                $response = wp_remote_post($url, array(
                    'method' => 'PUT',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token
                    ),
                    'body' => json_encode(array(
                        'is_obscured_public' => $is_obscured_public
                    ))
                ));
                $update = json_decode($response['body'], true);

                $uuids[] = array(
                    'code' => $code,
                    'html' => $html,
                    'old_html' => $embed_codes[$i],
                    'uuid' => $data['uuid'],
                    'id' => $data['id'],
                    'post_id' => $id,
                    'data' => $data,
                    'token' => $this->token,
                    'update' => $update
                );

            }

            $wpdb->update(
                $wpdb->posts,
                array('post_content' => str_replace($search, $replace, $post->post_content)),
                array('ID' => $id)
            );


            echo json_encode($uuids);

        }

        wp_die();

    }

    public function add_plugin_links($links, $file)
    {
        if (!strstr($file, $this->plugin_name)) return $links;
        $links[] = '<a href="admin.php?page=ipushpull">' . __('Web Application') . '</a>';
        $links[] = '<a href="admin.php?page=ipushpull-support">' . __('Support') . '</a>';
        $links[] = '<a href="' . IPUSHPULL_URL . '/join?ref=wp" target="_blank">' . __('Start Free Trial') . '</a>';
        $links[] = '<a href="' . IPUSHPULL_URL . '/blog?ref=wp" target="_blank">' . __('Blog') . '</a>';
        $links[] = '<a href="' . IPUSHPULL_URL . '/contact?ref=wp" target="_blank">' . __('Contact') . '</a>';
        return $links;
    }

    /**
     * Tinymce plugin
     * @param $plugin_array
     * @return mixed
     */
    public function add_mce_javascript($plugin_array)
    {
        $this->set_user();
        // $file = $this->user ? 'tinymce/plugin.js' : 'tinymce/user.js';
        $file = 'tinymce/plugin.js';
        $plugin_array['ippplugin'] = plugin_dir_url(__FILE__) . $file;
        return $plugin_array;
    }

    /**
     * Tinymce plugin buttons
     * @param $buttons
     * @return mixed
     */
    public function add_mce_buttons($buttons)
    {
        array_push($buttons, 'separator', 'ippplugin');
        return $buttons;
    }

    public function add_dashboard_widget()
    {
        wp_add_dashboard_widget(
            'ipp_dashboard_widget',
            '<img src="https://ipp-eu-prod-1-5.s3.amazonaws.com/static/images/logo-wp-dashboard.png" height="20px" />',
            array($this, 'display_dashboard_widget')
        );
    }

    public function display_dashboard_widget()
    {
        include_once('partials/ipushpull-dashboard-widget.php');
    }

    private function scheme()
    {
        if ((!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) {
            $server_request_scheme = 'https';
        } else {
            $server_request_scheme = 'http';
        }
        return $server_request_scheme;
    }

    private function extract_vars($code)
    {

    }

}
