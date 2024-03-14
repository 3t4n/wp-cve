<?php
/*
 * Plugin Name: Hide My WP Lite
 * Plugin URI:  #
 * Description: Hide & Protect wp-login by renaming or with a password. Hide Elementor plugin from front-end.
 * Version:     1.3
 * Author:      wpWave
 * Author URI:  http://www.wpwave.com/
 * Text Domain: hide-wp-login
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


define('HMWL_BASENAME', plugin_basename(__FILE__));
define('HMWL_DIR', plugin_dir_path(__FILE__));
define('HMWL_INCLUDES_DIR', HMWL_DIR . 'includes');

if (defined('ABSPATH') && !class_exists('HideMyWPLogin')) {

    class HideMyWPLogin {

        private $hmwl_wp_login_php;

        public function __construct() {
            add_filter('plugin_action_links_' . HMWL_BASENAME, array($this, 'hmwl_add_settings_link'), 20, 1);
            add_action('admin_notices', array($this, 'hmwl_admin_notice_for_new_slug'));
            add_action('plugins_loaded', array($this, 'hmwl_plugins_loaded'), 9999);
            add_action('wp_loaded', array($this, 'hmwl_wp_loaded'));
            add_filter('site_url', array($this, 'hmwl_site_url'), 10, 4);
            add_filter('wp_redirect', array($this, 'hmwl_change_logout_url'), 10, 2);
            remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);

            //Create admin menu
            add_action('admin_menu', array($this, 'hmwl_plugin_setup_menu'));
            add_action('admin_init', array(&$this, 'hmwl_save_admin_settings'), 11);
			$this->load_dependencies();
        }
		
		public function load_dependencies() {
			include_once(HMWL_INCLUDES_DIR . '/functions.php');
		}

        /**
         * @version 1.0
         * Create menu in admin panel
         */
        public function hmwl_plugin_setup_menu() {
            add_menu_page(__('Hide My WP Login Settings', 'hide-wp-login'), __('Hide My WP Login Settings', 'hide-wp-login'), 'manage_options', 'hide-wp-login', array($this, 'hmwl_admin_page'), 'dashicons-category');
        }

        /**
         * @version 1.0
         * Admin Page Setting HTML
         */
        public function hmwl_admin_page() {
            global $hmwl_success;
            ?>
            <style>
                table.hwml-table input[type=text]{
                    width: 400px;
                    margin: 0;
                    padding: 6px;
                    box-sizing: border-box;
                    vertical-align: top;
                }
                .sub-info{
                    font-style: italic;
                    color: #999;
                    display: block;
                }
            </style>            
            <script type="text/javascript">
                function hmwl_show_path() {
                    document.getElementById('tr_via_key').style.display = 'none';
                    document.getElementById('tr_via_path').removeAttribute("style");
                    document.getElementById('tr_via_path').style.display = 'inline-row';
                }
                function hmwl_show_key() {
                    document.getElementById('tr_via_key').removeAttribute("style");
                    document.getElementById('tr_via_key').style.display = 'inline-row';
                    document.getElementById('tr_via_path').style.display = 'none';
                }
            </script>
            <?php
            if ($hmwl_success) {
                ?>
                <div id="message" class="updated notice">
                    <p><strong><?php echo esc_html( $hmwl_success ); ?></strong></p>
                </div>
                <?php
            }
            ?>
            <h2><?php 
                    _e('Hide My WP Login Settings', 'hide-wp-login'); 
                    $allow_tags = array( 
                        'code' => array(),
                        'input' => array( "value" => array(),"id" => array(),"name" => array()),                        
                    );
                ?></h2>            
            <div class="woocommerce">
                <form method="post" id="mainform" action="" enctype="multipart/form-data">
                    <table class="form-table hmwl-table">
                        <tbody>
                            <tr>
                                <th><?php _e('Hide wp-login.php', 'hide-wp-login'); ?></th>
                                <td>
                                    <?php $hmwl_hide_option = get_option('hmwl_hide_option', 'hide_via_path'); ?>
                                    <label><input type="radio" name="hmwl_hide_option" value="hide_via_path" <?php checked($hmwl_hide_option, 'hide_via_path'); ?> onclick="hmwl_show_path();"><?php _e('Hide Using Path', 'hide-wp-login'); ?></label>
                                    <label><input type="radio" name="hmwl_hide_option" value="hide_via_key" <?php checked($hmwl_hide_option, 'hide_via_key'); ?> onclick="hmwl_show_key();"><?php _e('Hide Using Specific Key', 'hide-wp-login'); ?></label>
                                </td>
                            </tr>
                            <tr id="tr_via_path" <?php
                            if ($hmwl_hide_option != 'hide_via_path') {
                                echo 'style="display: none;"';
                            }
                            ?>>
                                <th><?php _e('Login URL', 'hide-wp-login'); ?></th>
                                <td>
                                    <?php
                                    $hmwl_slug_name = get_option('hmwl_slug_name', 'login');
                                    if (get_option('permalink_structure')) {
                                        echo wp_kses('<code>' . trailingslashit(home_url()) . '</code> <input id="hmwl-slug-name" type="text" name="hmwl_slug_name" value="' . $hmwl_slug_name . '">' . ( $this->hmwl_check_trailing_slashes() ? ' <code>/</code>' : '' ),$allow_tags);
                                    } else {
                                        echo wp_kses('<code>' . trailingslashit(home_url()) . '?</code> <input id="hmwl-slug-name" type="text" name="hmwl_slug_name" value="' . $hmwl_slug_name . '">',$allow_tags);
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr id="tr_via_key" <?php
                            if ($hmwl_hide_option != 'hide_via_key') {
                                echo 'style="display: none;"';
                            }
                            ?>>
                                <th><?php _e('Login Specific Key', 'hide-wp-login'); ?></th>
                                <td>
                                    <?php
                                    $hmwl_login_key = get_option('hmwl_login_key', '1234');
                                    echo  wp_kses( '<code>' . trailingslashit(home_url()) . 'wp-login.php?hide_my_wp=</code> <input type="text" name="hmwl_login_key" value="' . esc_attr( $hmwl_login_key ) . '">', $allow_tags) ;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Redirect URL', 'hide-wp-login'); ?></th>
                                <td>
                                    <?php
                                    $hmwl_redirect_slug = get_option('hmwl_redirect_slug', '404');
                                    if (get_option('permalink_structure')) {
                                        echo wp_kses('<code>' . trailingslashit(home_url()) . '</code> <input type="text" name="hmwl_redirect_slug" value="' . esc_attr($hmwl_redirect_slug) . '">' . ( $this->hmwl_check_trailing_slashes() ? ' <code>/</code>' : '' ), $allow_tags);
                                    } else {
                                        echo wp_kses( '<code>' . trailingslashit(home_url()) . '?</code> <input type="text" name="hmwl_redirect_slug" value="' . esc_attr( $hmwl_redirect_slug ) . '">', $allow_tags );
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Hide Elementor', 'hide-wp-login'); ?></th>
                                <td>
                                    <?php $hmwl_hide_elementor = get_option('hmwl_hide_elementor', 'no'); ?>
                                    <label><input type="radio" name="hmwl_hide_elementor" value="yes" <?php checked($hmwl_hide_elementor, 'yes'); ?>><?php _e('Yes', 'hide-wp-login'); ?></label>
                                    <label><input type="radio" name="hmwl_hide_elementor" value="no" <?php checked($hmwl_hide_elementor, 'no'); ?>><?php _e('No', 'hide-wp-login'); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <?php wp_nonce_field('hmwl_save_nonce', 'hmwl_nonce_save_form'); ?>
                                    <input type="submit" value="Save Settings" name="hmwl_save" class="button button button-primary">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>            
            <?php
        }
        
        /**
         * @version 1.0
         * @global string $hmwl_success
         * Save the admin settings
         */
        public function hmwl_save_admin_settings() {
            if (isset($_POST['hmwl_nonce_save_form']) && wp_verify_nonce($_POST['hmwl_nonce_save_form'], 'hmwl_save_nonce')) {
                global $hmwl_success;
                update_option('hmwl_hide_option', isset($_POST['hmwl_hide_option']) ? sanitize_text_field($_POST['hmwl_hide_option']) : '');
                update_option('hmwl_slug_name', isset($_POST['hmwl_slug_name']) ? sanitize_text_field($_POST['hmwl_slug_name']) : '');
                update_option('hmwl_login_key', isset($_POST['hmwl_login_key']) ? sanitize_text_field($_POST['hmwl_login_key']) : '');
                update_option('hmwl_redirect_slug', isset($_POST['hmwl_redirect_slug']) ? sanitize_text_field($_POST['hmwl_redirect_slug']) : '');
                update_option('hmwl_hide_elementor', isset($_POST['hmwl_hide_elementor']) ? sanitize_text_field($_POST['hmwl_hide_elementor']) : 'no');
                $hmwl_success = __('Settings are saved.', 'hide-wp-login');
            }
			$hmwl_hide_elementor = get_option('hmwl_hide_elementor', 'no');
			if ('yes' == $hmwl_hide_elementor) {
				if (!did_action('elementor/loaded')) {
					add_action('admin_notices', array(&$this, 'hmwl_missing_elementor'));
					return;
				}
			}
        }

        /**
         * @version 1.0
         * @global string $pagenow
         * @global array $error
         * @global array $interim_login
         * @global array $action
         * @global array $user_login
         * Redirect user to proper admin link
         */
        public function hmwl_wp_loaded() {
            global $pagenow;
            $request = parse_url($_SERVER['REQUEST_URI']);
            if (!isset($_POST['post_password'])) {
                //send wp-admin to 404 page
                if (is_admin() && !is_user_logged_in() && !defined('DOING_AJAX') && $pagenow !== 'admin-post.php' && ( isset($_GET) && empty($_GET['adminhash']) && $request['path'] !== '/wp-admin/options.php' )) {
                    wp_safe_redirect($this->hmwl_redirect_url());
                    die();
                }
                $hmwl_login_key = get_option('hmwl_login_key', '1234');
                if($pagenow === 'wp-login.php' && isset($_GET['hide_my_wp']) && str_replace('/', '', $_GET['hide_my_wp']) == $hmwl_login_key){
                    @require_once ABSPATH . 'wp-login.php';                    
                }else if ($pagenow === 'wp-login.php' && $request['path'] !== $this->hmwl_trailingslashit($request['path']) && get_option('permalink_structure')) {                    
                    wp_safe_redirect($this->hmwl_trailingslashit($this->hmwl_new_wp_login_url()) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ));
                    die;
                } elseif ($this->hmwl_wp_login_php) {
                    if (( $referer = wp_get_referer() ) && strpos($referer, 'wp-activate.php') !== false && ( $referer = parse_url($referer) ) && !empty($referer['query'])) {
                        parse_str($referer['query'], $referer);
                        if (!empty($referer['key']) && ( $result = wpmu_activate_signup($referer['key']) ) && is_wp_error($result) && ( $result->get_error_code() === 'already_active' || $result->get_error_code() === 'blog_taken' )) {
                            wp_safe_redirect($this->hmwl_new_wp_login_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ));
                            die;
                        }
                    }
                    $this->hmwl_template_loader();
                } elseif ($pagenow === 'wp-login.php') {
                    global $error, $interim_login, $action, $user_login;
                    if (is_user_logged_in() && !isset($_REQUEST['action'])) {                        
                        wp_safe_redirect(admin_url());
                        die();
                    }                    
                    @require_once ABSPATH . 'wp-login.php';
                    die;
                }                
            }
        }

        /**
         * @version 1.0
         * @param string $url
         * @param string $path
         * @param string $scheme
         * @param int $blog_id
         * @return Check validation link
         */
        public function hmwl_site_url($url, $path, $scheme, $blog_id) {
            return $this->hmwl_wp_login_filter($url, $scheme);
        }        

        /**
         * @version 1.0
         * @global string $pagenow
         * Load text domain and return user to new wp login slug
         */
        public function hmwl_plugins_loaded() {
            //Load text domain
            load_plugin_textdomain('hide-wp-login');

            global $pagenow;
            if (!is_multisite() && ( strpos($_SERVER['REQUEST_URI'], 'wp-signup') !== false || strpos($_SERVER['REQUEST_URI'], 'wp-activate') !== false )) {
                wp_die(__('This feature is not enabled.', 'hide-wp-login'));
            }
            $hmwl_hide_option = get_option('hmwl_hide_option', 'hide_via_path');
            $hmwl_login_key = get_option('hmwl_login_key', '1234');
            $request = parse_url($_SERVER['REQUEST_URI']);
            if (isset($request['query']) && strpos($request['query'], 'action=confirmaction') !== false) {
                @require_once ABSPATH . 'wp-login.php';
                $pagenow = 'index.php';
            } elseif($hmwl_hide_option == 'hide_via_key' && ( strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-login.php') !== false ) && isset($_GET['hide_my_wp']) && str_replace ('/', '', $_GET['hide_my_wp']) == $hmwl_login_key ){
                $pagenow = 'wp-login.php';
            } elseif (( strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-login.php') !== false || untrailingslashit($request['path']) === site_url('wp-login', 'relative') ) && !is_admin()) {                
                $this->hmwl_wp_login_php = true;
                $_SERVER['REQUEST_URI'] = $this->hmwl_trailingslashit('/' . str_repeat('-/', 10));
                $pagenow = 'index.php';
            } elseif ($hmwl_hide_option == 'hide_via_path' && untrailingslashit($request['path']) === home_url($this->hmwl_new_login_slug(), 'relative') || (!get_option('permalink_structure') && isset($_GET[$this->hmwl_new_login_slug()]) && empty($_GET[$this->hmwl_new_login_slug()]) )) {                
                $pagenow = 'wp-login.php';
            } elseif (( strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-register.php') !== false || untrailingslashit($request['path']) === site_url('wp-register', 'relative') ) && !is_admin()) {
                $this->hmwl_wp_login_php = true;
                $_SERVER['REQUEST_URI'] = $this->hmwl_trailingslashit('/' . str_repeat('-/', 10));
                $pagenow = 'index.php';
            }
        }

        /**
         * @version 1.0
         * @param array $links
         * @return Merge the setting link with other plugin links
         */
        public function hmwl_add_settings_link($links) {
            $setting_link = '<a href="' . admin_url('admin.php?page='. 'hide-wp-login') . '">' . __('Settings', 'hide-wp-login') . '</a>';
            array_unshift($links, $setting_link);
            return $links;
        }

        /**
         * @version 1.0
         * @return Check slash in end of slug
         */
        private function hmwl_check_trailing_slashes() {
            return '/' === substr(get_option('permalink_structure'), -1, 1);
        }

        /**
         * @version 1.0
         * @global string $pagenow
         * Add admin notice when setting save
         */
        public function hmwl_admin_notice_for_new_slug() {
            global $pagenow,$hmwl_success;            
            if ( $hmwl_success && isset($_GET['page']) && $_GET['page'] == 'hide-wp-login' ) {
                echo '<div class="updated"><p>' . sprintf(__('Your new WP login page is now here: %s.', 'hide-wp-login'), '<strong><a href="' . esc_url( $this->hmwl_new_wp_login_url() ) . '">' . esc_url( $this->hmwl_new_wp_login_url() ) . '</a></strong>') . '</p></div>';
            }
        }

        /**
         * @version 1.0
         * @param type $scheme
         * @return New slug for wp login
         */
        public function hmwl_new_wp_login_url($scheme = null) {
            $new_slug = $this->hmwl_new_login_slug();
            $hmwl_hide_option = get_option('hmwl_hide_option', 'hide_via_path');
            $hmwl_login_key = get_option('hmwl_login_key', '1234');
            if( $hmwl_hide_option == 'hide_via_key'){
                return home_url().'/wp-login.php?hide_my_wp='.$hmwl_login_key;
            }            
            if (get_option('permalink_structure')) {
                return $this->hmwl_trailingslashit(home_url('/', $scheme) . $new_slug);
            }                        
            return home_url('/', $scheme) . '?' . $new_slug;
        }

        /**
         * @version 1.0
         * @param string $string
         * @return Check trailingslashit in string
         */
        private function hmwl_trailingslashit($string) {
            return $this->hmwl_check_trailing_slashes() ? trailingslashit($string) : untrailingslashit($string);
        }

        /**
         * @version 1.0
         * @global string $pagenow
         * Return template loader file
         */
        private function hmwl_template_loader() {
            global $pagenow;
            $pagenow = 'index.php';
            if (!defined('WP_USE_THEMES')) {
                define('WP_USE_THEMES', true);
            }
            wp();
            if ($_SERVER['REQUEST_URI'] === $this->hmwl_trailingslashit(str_repeat('-/', 10))) {
                $_SERVER['REQUEST_URI'] = $this->hmwl_trailingslashit('/wp-login-php/');
            }
            require_once( ABSPATH . WPINC . '/template-loader.php' );
            die;
        }

        /**
         * @version 1.0
         * @return string Newly created slug
         */
        private function hmwl_new_login_slug() {
            if ($slug = get_option('hmwl_slug_name')) {
                return $slug;
            } else if (( is_multisite() && is_plugin_active_for_network(HMWL_BASENAME) && ( $slug = get_site_option('hmwl_slug_name', 'login') ))) {
                return $slug;
            } else if ($slug = 'login') {
                return $slug;
            }
        }

        /**
         * @version 1.0
         * @param string $url
         * @param string $scheme
         * @return String - URL of new wp-admin
         */
        public function hmwl_wp_login_filter($url, $scheme = null) {
            if (strpos($url, 'wp-login.php?action=postpass') !== false) {
                return $url;
            }

            if (strpos($url, 'wp-login.php') !== false) {
                if (is_ssl()) {
                    $scheme = 'https';
                }
                $args = explode('?', $url);
                if (isset($args[1])) {
                    parse_str($args[1], $args);
                    if (isset($args['login'])) {
                        $args['login'] = rawurlencode($args['login']);
                    }
                    $url = add_query_arg($args, $this->hmwl_new_wp_login_url($scheme));
                } else {
                    $url = $this->hmwl_new_wp_login_url($scheme);
                }
            }

            return $url;
        }

        /**
         * @version 1.0
         * @param type $location
         * @param type $status
         * @return type
         */
        public function hmwl_change_logout_url($location, $status) {
            if (strpos($location, 'https://wordpress.com/wp-login.php') !== false) {
                return $location;
            }
            return $this->hmwl_wp_login_filter($location);
        }
        
        /**
         * @version 1.0
         * Check slugs
         */
        public function hmwl_slugs_check() {

            $wp = new \WP;

            return array_merge($wp->public_query_vars, $wp->private_query_vars);
        }        
        
        /**
         * @version 1.0
         * @param string $scheme
         * @return return the redirect URL
         */
        private function hmwl_redirect_url($scheme = null) {
            $hmwl_redirect_slug = get_option('hmwl_redirect_slug', '404');
            if (get_option('permalink_structure')) {
                return $this->hmwl_trailingslashit(home_url('/', $scheme) . $hmwl_redirect_slug);
            } else {
                return home_url('/', $scheme) . '?' . $hmwl_redirect_slug;
            }
        }

		function is_elementor_installed() {
			$file_path = 'elementor/elementor.php';
			$installed_plugins = get_plugins();
			return isset($installed_plugins[$file_path]);
		}

		function hmwl_missing_elementor() {
			$plugin = 'elementor/elementor.php';
			if ($this->is_elementor_installed()) {
				if (!current_user_can('activate_plugins')) {
					return;
				}
				$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
				$message = '<p>' . __('Hide Elementor is not working because you need to activate the Elementor plugin.', 'elementor-pro') . '</p>';
				$message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Elementor Now', 'elementor-pro')) . '</p>';
			} else {
				if (!current_user_can('install_plugins')) {
					return;
				}
				$install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
				$message = '<p>' . __('Hide Elementor is not working because you need to install the Elementor plugin.', 'elementor-pro') . '</p>';
				$message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install Elementor Now', 'elementor-pro')) . '</p>';
			}
            $html_message = sprintf( '<div class="error">%s<p>', wpautop( $message ) );
			echo  wp_kses_post($html_message);
		}
    }

    new HideMyWPLogin();
}
