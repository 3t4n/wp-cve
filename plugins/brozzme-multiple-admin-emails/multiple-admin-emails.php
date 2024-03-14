<?php

/**
 * Plugin Name: Brozzme Multiple Admin Emails
 * Plugin URI: https://brozzme.com/multiple-admin-email
 * Description: Allow multiple admin emails in General settings. Overide sanitization of admin_email and new_admin_email to allow multiple admin email, separate with commas. This function applies a limit, check for valid email using is_email(). With this function, site notifications are sent to multiple admin emails.
 * Version: 1.4
 * Author: Benoti
 * Author URI: https://brozzme.com
 * Text Domain: brozzme-multiple-emails
 * Domain Path: /languages
 */



if (!class_exists('wp_bmae')) {

    class wp_bmae {

        /**
         * wp_bmae constructor.
         */
        public function __construct() {

            $this->basename			 = plugin_basename( __FILE__ );
            $this->directory_path    = plugin_dir_path( __FILE__ );
            $this->directory_url	 = plugins_url( dirname( $this->basename ) );

            add_action( 'init', array($this, 'bmae_load_textdomain'));

            add_filter('plugin_action_links', array($this, 'bmae_plugin_action_links'), 10, 2);

            add_filter( 'sanitize_option_admin_email', array( $this, 'bmae_admin_email_sanitize' ), 10, 3 );
            add_filter( 'sanitize_option_new_admin_email', array( $this, 'bmae_admin_email_sanitize' ), 10, 3 );

            add_action('init', array($this, 'remove_pending_email_options'));

            remove_action( 'add_option_new_admin_email', 'update_option_new_admin_email' );
            remove_action( 'update_option_new_admin_email', 'update_option_new_admin_email' );

            add_filter('send_site_admin_email_change_email', function(){return FALSE;}, 10, 3 );

            add_action( 'add_option_new_admin_email', array($this, 'bmae_update_admin_email'), 10, 2 );
            add_action( 'update_option_new_admin_email', array($this, 'bmae_update_admin_email'), 10, 2 );

            // group menu ID
            $this->plugin_dev_group = 'Brozzme';
            $this->plugin_dev_group_id = 'brozzme-plugins';

            // plugin info
            $this->plugin_name = 'brozzme-multiple-admin-emails';
            $this->settings_page_slug = 'brozzme-multiple-admin-emails';
            $this->plugin_version = '1.2.2';
            $this->plugin_text_domain = 'brozzme-multiple-emails';

            $this->settings_options = get_option('bmae_settings');

            if(is_admin()){
                $this->_define_constants();
                add_action( 'admin_enqueue_scripts', array( $this, '_add_settings_styles') );
                $this->utilities();
            }
        }

        /**
         *
         */
        public function _define_constants(){

            defined('BFSL_PLUGINS_DEV_GROUPE')    or define('BFSL_PLUGINS_DEV_GROUPE', $this->plugin_dev_group);
            defined('BFSL_PLUGINS_DEV_GROUPE_ID') or define('BFSL_PLUGINS_DEV_GROUPE_ID', $this->plugin_dev_group_id);
            defined('BFSL_PLUGINS_URL') or define('BFSL_PLUGINS_URL', $this->directory_url);
            defined('BFSL_PLUGINS_SLUG') or define('BFSL_PLUGINS_SLUG', $this->plugin_slug);

            defined('BMAE_NAME')    or define('BMAE_NAME', $this->plugin_name);
            defined('BMAE')  or define('BMAE', $this->settings_page_slug);
            defined('BMAE_DIR')    or define('BMAE_DIR', $this->directory_path);
            defined('BMAE_VERSION')        or define('BMAE_VERSION', $this->plugin_version);
            defined('BMAE_TEXT_DOMAIN')    or define('BMAE_TEXT_DOMAIN', $this->plugin_text_domain);
        }

        /**
         * @param $hook
         */
        public function _add_settings_styles($hook){
            if($hook == 'toplevel_page_' . $this->plugin_dev_group_id || $hook == 'tools_page_brozzme-db-prefix'){
                wp_enqueue_style( $this->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'css/brozzme-admin-css.css');
            }
        }
        /**
         *
         */
        static function bmae_activate() {

            if (!get_option('bmae_settings')) {

                $options = array(
                    'limit' => 5,
                    'new_post_email' => 'false'
                );

                add_option('bmae_settings', $options);
            }
        }

        /**
         *
         */
        static function bmae_deactivate() {
            $current_admin_emails = get_option('admin_email');
            $new_admin_email = explode (',', $current_admin_emails);
            $i=1;
            foreach ($new_admin_email as $email) {
                if($i > 1){
                    break;
                }
                update_option('admin_email', trim($email));

                $i++;
            }

            delete_option('bmae_settings');
        }

        /**
         *
         */
        static function bmae_uninstall() {
            $current_admin_emails = get_option('admin_email');
            $new_admin_email = explode (',', $current_admin_emails);
            $i=1;
            foreach ($new_admin_email as $email) {
                if($i > 1){
                    break;
                }
                update_option('admin_email', trim($email));

                $i++;
            }
            delete_option('bmae_settings');
        }

        /**
         * @param $links
         * @param $file
         * @return mixed
         */
        public function bmae_plugin_action_links($links, $file) {
            static $plugin;

            if (!$plugin) {
                $plugin = plugin_basename(__FILE__);
            }

            if ($file == $plugin) {

                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page='.$this->settings_page_slug.'">'.__('Settings', $this->plugin_text_domain).'</a>';
                array_unshift($links, $settings_link);
            }

            return $links;
        }

        /**
         *
         */
        public function bmae_load_textdomain() {
            load_plugin_textdomain($this->plugin_text_domain, false, basename( dirname( __FILE__ ) ) . '/languages/');
        }

        /**
         *
         */
        public function utilities(){

            if (!class_exists('brozzme_plugins_page')){
                include_once ($this->directory_path . 'admin/brozzme_plugins_page.php');
            }

            include_once $this->directory_path . '/admin/bmae_admin.php';

            if($this->settings_options['new_post_email'] == 'true'){
                include_once $this->directory_path . 'admin/bmae_utilities.php';
            }
        }

        /**
         * @param $value
         * @param $option_name
         * @param $original_value
         * @return string
         */
        public function bmae_admin_email_sanitize($value, $option_name, $original_value) {

            $option = get_option('bmae_settings');
            $limit = $option['limit'] == '' ? 1 : $option['limit'];

            $input_email_array = explode(',', $original_value);

            $new_stack = array();

            if (count($input_email_array) <= $limit) {
                foreach ($input_email_array as $k => $value) {
                    $check = is_email(trim($value));
                    if ($check != false) {
                        $new_stack[] = $check;
                    }
                }
                $new_value = implode(', ', $new_stack);
            } else {
                $i = 0;
                foreach ($input_email_array as $k => $value) {
                    if ($i >= $limit) {
                        break;
                    }
                    $check = is_email(trim($value));
                    if ($check != false) {
                        $new_stack[] = $check;
                    }
                    $i++;
                }
                $new_value = implode(', ', $new_stack);
            }
            return $new_value;
        }

        /**
         * @param $send
         * @return false
         */
        public function silent_option_save($send){
            return false;
        }

        /**
         *
         */
        public function remove_pending_email_options(){
            delete_option("adminhash");
            delete_option("new_admin_email");
        }

        /**
         * @param $old_value
         * @param $value
         */
        public function bmae_update_admin_email($old_value, $value ) {
            global $wpdb;

            $table_name = $wpdb->prefix.'options';
            $save_data = array('option_value'=>$value);
            $where = array('option_name' => 'admin_email');
            $the_update = $wpdb->update($table_name, $save_data, $where, null, '%s');

        }
    }
}

if (class_exists('wp_bmae')) {

    register_activation_hook(__FILE__, array('wp_bmae', 'bmae_activate'));
    register_deactivation_hook(__FILE__, array('wp_bmae', 'bmae_deactivate'));
    register_uninstall_hook(__FILE__, array('wp_bmae', 'bmae_uninstall'));

    new wp_bmae();
}

