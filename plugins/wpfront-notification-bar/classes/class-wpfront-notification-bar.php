<?php

/*
  WPFront Notification Bar Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront Notification Bar Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace WPFront\Notification_Bar;

if (!defined('ABSPATH')) exit();

use ErrorException;

require_once("class-wpfront-notification-bar-entity.php");
require_once("class-wpfront-notification-bar-controller.php");
require_once(dirname(__DIR__) . "/templates/template-wpfront-notification-bar-custom-css.php");
require_once(dirname(__DIR__) . "/templates/template-wpfront-notification-bar.php");
require_once(dirname(__DIR__) . "/templates/template-wpfront-notification-bar-add-edit.php");

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar')) {

    /**
     * Main class of WPFront Notification Bar plugin
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2013 WPFront.com
     */
    class WPFront_Notification_Bar {

        //Constants
        const VERSION = '3.4.01231';
        const OPTIONS_GROUP_NAME = 'wpfront-notification-bar-options-group';
        const OPTION_NAME = 'wpfront-notification-bar-options';
        const PLUGIN_SLUG = 'wpfront-notification-bar';
        const PREVIEW_MODE_NAME = 'wpfront-notification-bar-preview-mode'; //TODO: remove
        //role consts
        const ROLE_NOROLE = 'wpfront-notification-bar-role-_norole_';
        const ROLE_GUEST = 'wpfront-notification-bar-role-_guest_';

        /**
         * Plugin file relative path
         *
         * @var string
         */
        protected $plugin_file;
        /**
         * Default settings capability
         *
         * @var string
         */
        protected $cap = 'manage_options';
        /**
         * List of controller objects
         *
         * @var \WPFront\Notification_Bar\WPFront_Notification_Bar_Controller[]
         */
        protected $controllers;
        /**
         * Holds current controller object
         *
         * @var \WPFront\Notification_Bar\WPFront_Notification_Bar_Controller|null
         */
        protected $current_controller;
        /**
         * Singleton
         *
         * @var self
         */
        protected static $instance = null;

        /**
         * Returns singleton instance
         *
         * @return WPFront_Notification_Bar
         */
        public static function Instance() {
            if (self::$instance === null) {
                self::$instance = new WPFront_Notification_Bar(); 

                self::$instance = apply_filters('wpfront_notification_bar_instance', self::$instance);
            }

            return self::$instance;
        }

        /**
         * Init function
         *
         * @param string $plugin_file
         * @return void
         */
        public function init($plugin_file) {
            $this->plugin_file = $plugin_file;

            add_action('plugins_loaded', array($this, 'set_controllers'));
            add_action('init', array($this, 'custom_css'));

            if (is_admin()) {
                if (defined('WPFRONT_NOTIFICATION_BAR_EDIT_CAPABILITY')) {
                    $this->cap = constant('WPFRONT_NOTIFICATION_BAR_EDIT_CAPABILITY');
                }
                $this->cap = apply_filters('wpfront_notification_bar_edit_capability', $this->cap);
                add_filter('option_page_capability_' . self::OPTIONS_GROUP_NAME, array($this, 'option_page_capability_callback'), 10);

                add_action('admin_init', array($this, 'admin_init'));
                add_action('admin_menu', array($this, 'admin_menu'));
                add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

                $this->add_activation_redirect();
            }
        }

        /**
         * Sets controllers
         *
         * @return void
         */
        public function set_controllers() {
            $this->controllers = $this->get_controllers();
            $this->current_controller = null;
        }

        /**
         * Returns the list of controllers.
         * For free there will be only one.
         *
         * @return WPFront_Notification_Bar_Controller[]
         */
        public function get_controllers() {
            $options = new WPFront_Notification_Bar_Entity();
            $options = $options->get();
            return array(new WPFront_Notification_Bar_Controller($this, $options));
        }

        /**
         * Attaches hooks for activation redirect functionality
         *
         * @return void
         */
        protected function add_activation_redirect() {
            add_action('activated_plugin', array($this, 'activated_plugin_callback'));
            add_action('admin_init', array($this, 'admin_init_callback'), 999999);
        }

        /**
         * Sets the activation redirect required flag in options table.
         *
         * @param string $plugin_file
         * @return void
         */
        public function activated_plugin_callback($plugin_file) {
            if ($plugin_file !== $this->plugin_file) {
                return;
            }

            if (is_network_admin() || isset($_GET['activate-multi'])) {
                return;
            }

            $key = self::PLUGIN_SLUG . '-activation-redirect';
            add_option($key, true);
        }

        /**
         * Redirects if the activation redirect flag is set in options table.
         *
         * @return void
         */
        public function admin_init_callback() {
            $key = self::PLUGIN_SLUG . '-activation-redirect';

            if (get_option($key, false)) {
                delete_option($key);

                if (is_network_admin() || isset($_GET['activate-multi'])) {
                    return;
                }

                wp_safe_redirect(menu_page_url(self::PLUGIN_SLUG, FALSE));
                $this->kill();
            }
        }

        /**
         * Register settings call for options page.
         *
         * @return void
         */
        public function admin_init() {
            register_setting(self::OPTIONS_GROUP_NAME, self::OPTION_NAME);
        }

        /**
         * Adds admin menu and attaches hooks for load and scripts.
         *
         * @return void
         */
        public function admin_menu() {
            $page_hook_suffix = add_options_page(__('WPFront Notification Bar', 'wpfront-notification-bar'), __('Notification Bar', 'wpfront-notification-bar'), $this->cap, self::PLUGIN_SLUG, array($this, 'view'));

            add_action('load-' . $page_hook_suffix, array($this, 'load_view'));
            add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'enqueue_options_scripts'));
            add_action('admin_print_styles-' . $page_hook_suffix, array($this, 'enqueue_options_styles'));
        }

        /**
         * Load view function, sets current controller.
         *
         * @return void
         */
        public function load_view() {
            if (!current_user_can($this->cap)) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wpfront-notification-bar'));
                return;
            }

            $this->current_controller = $this->controllers[0];

            if(isset($_POST['submit']) || isset($_POST['submit2'])){
                check_admin_referer('wpfront-notification-bar-options-group-options');

                $data = $_POST['wpfront-notification-bar-options'];
                
                foreach($data as $key => $value) {
                    if($key == 'include_roles'){
                        $data[$key] = explode(',', $value);
                    }
                }

                $data = stripslashes_deep($data);
                $data = (object)$data;

                $options = new WPFront_Notification_Bar_Entity();
                
                $options->set_values($data);
                $options->save();

                $this->current_controller->settings_updated($options);

                $current_url = menu_page_url(self::PLUGIN_SLUG, false);
                $current_url = $current_url . '&updated=true';

                wp_safe_redirect($current_url);
                $this->kill();
            }
        }

        /**
         * Enqueue's current controller scripts.
         *
         * @return void
         */
        public function enqueue_options_scripts() {
            if($this->current_controller !== null) {
                $this->current_controller->enqueue_options_scripts();
            }
        }

        /**
         * Enqueue's current controller styles.
         *
         * @return void
         */
        public function enqueue_options_styles() {
            if($this->current_controller !== null) {
                $this->current_controller->enqueue_options_styles();
            }
        }

        /**
         * Displays current controller settings.
         *
         * @return void
         */
        public function view() {
            if (!current_user_can($this->cap)) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wpfront-notification-bar'));
                return;
            }

            if($this->current_controller !== null) {
                $this->current_controller->view();
            }

            add_filter('admin_footer_text', array($this, 'admin_footer_text'));
        }

        /**
         * Add links to be displayed in Plugins page.
         *
         * @param string[] $links
         * @param string $plugin_file Plugin file
         * @return string[]
         */
        public function plugin_action_links($links, $plugin_file) {
            if ($plugin_file == $this->plugin_file) {
                if (current_user_can($this->cap)) {
                    $settings_link = '<a id="wpfront-notification-bar-settings-link" href="' . menu_page_url(self::PLUGIN_SLUG, false) . '">' . __('Settings', 'wpfront-notification-bar') . '</a>';
                    array_unshift($links, $settings_link);
                }

                $url = 'https://wpfront.com/notification-bar-pro/';
                $text = __('Upgrade', 'wpfront-notification-bar');
                $a = sprintf('<a id="wpfront-notification-bar-upgrade-link" style="color:red;" target="_blank" href="%s">%s</a>', $url, $text);
                array_unshift($links, $a);
            }

            return $links;
        }

        /**
         * Returns the custom css URL.
         *
         * @return string
         */
        public function custom_css_url() {
            return plugins_url("css/wpfront-notification-bar-custom-css/", $this->plugin_file);
        }

        /**
         * Prints custom css to output and exists.
         *
         * @SuppressWarnings(PHPMD.ErrorControlOperator)
         * 
         * @return void
         */
        public function custom_css() {
            if (strpos($_SERVER['REQUEST_URI'], '/css/wpfront-notification-bar-custom-css/') === false) {
                return;
            }

            @header('Content-Type: text/css; charset=UTF-8');
            $e = strtotime('+1 year');
            @header('Expires: ' . gmdate('D, d M Y H:i:s ', $e) . 'GMT');
            @header('Cache-Control: public, max-age=' . $e);

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $controller = $this->controllers[$id];
                $template = new WPFront_Notification_Bar_Custom_CSS_Template();
                $template->write($controller, true);

                $this->kill();
            }

            foreach ($this->controllers as $controller) {
                $options = $controller->get_options();
                if ($options->dynamic_css_use_url) {
                    $template = new WPFront_Notification_Bar_Custom_CSS_Template();
                    $template->write($controller);
                }
            }

            $this->kill();
        }

        /**
         * Returns admin page footer text.
         *
         * @param string $text WordPress default.
         * @return string
         */
        public function admin_footer_text($text) {
            $upgrade_link = sprintf('<a href="%s" target="_blank" style="color:red;"><b>%s</b></a>', 'https://wpfront.com/notification-bar-pro/', __('Create Multiple Bars', 'wpfront-notification-bar'));
            $troubleshootingLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wpfront.com/wordpress-plugins/notification-bar-plugin/wpfront-notification-bar-troubleshooting/', __('Troubleshooting', 'wpfront-notification-bar'));
            $settingsLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wpfront.com/notification-bar-plugin-settings/', __('Settings Description', 'wpfront-notification-bar'));
            $reviewLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/' . self::PLUGIN_SLUG . '/reviews/', __('Write a Review', 'wpfront-notification-bar'));

            return sprintf('%s | %s | %s | %s | %s', $upgrade_link, $troubleshootingLink, $settingsLink, $reviewLink, $text);
        }

        /**
         * Returns current language domain value.
         * Default - wpfront-notification-bar
         * Overridden by WPFRONT_NOTIFICATION_BAR_LANG_DOMAIN const.
         *
         * @return string
         */
        public function get_lang_domain() {
            if (defined('WPFRONT_NOTIFICATION_BAR_LANG_DOMAIN')) {
                return constant('WPFRONT_NOTIFICATION_BAR_LANG_DOMAIN');
            }

            return 'wpfront-notification-bar';
        }

        /**
         * Returns current plugin file.
         *
         * @return string
         */
        public function get_plugin_file() {
            return $this->plugin_file;
        }

        /**
         * Returns current options page capability
         *
         * @return string
         */
        public function option_page_capability_callback() {
            return $this->cap;
        }

        /**
         * Exit wrapper
         *
         * @return void
         */
        public function kill() {
            if(defined('WPFRONT_UNIT_TEST_MODE') && constant('WPFRONT_UNIT_TEST_MODE')) {
                throw new ErrorException("exit");
            }

            exit;
        }
       
    }

}

if (file_exists(dirname(__DIR__) . '/pro/classes/class-wpfront-notification-bar-pro.php')) {
    require_once dirname(__DIR__) . '/pro/classes/class-wpfront-notification-bar-pro.php';
}
