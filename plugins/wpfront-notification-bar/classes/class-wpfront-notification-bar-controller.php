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

require_once("class-wpfront-notification-bar-entity.php");
require_once(dirname(__DIR__) . "/templates/template-wpfront-notification-bar-custom-css.php");
require_once(dirname(__DIR__) . "/templates/template-wpfront-notification-bar.php");

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Controller')) {

    /**
     * Controller class of WPFront Notification Bar plugin
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2013 WPFront.com
     */
    class WPFront_Notification_Bar_Controller {

        //Constants
        const PREVIEW_MODE_NAME = 'wpfront-notification-bar-preview-mode';
        //role consts
        const ROLE_NOROLE = 'wpfront-notification-bar-role-_norole_';
        const ROLE_GUEST = 'wpfront-notification-bar-role-_guest_';

        //Variables
        private $plugin_file;
        private $main;
        private $options;
        private $markupLoaded;
        /**
         *
         * @var string
         */
        protected $min_file_suffix = null;
        private $scriptLoaded;
        private $enabled = null;
        private $logs = array();

        public function __construct($main, $options) {
            $this->main = $main;
            $this->options = $options;

            $this->markupLoaded = false;
            $this->min_file_suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            $this->init();
        }

        protected function init() {
            $this->plugin_file = $this->main->get_plugin_file();

            if(!function_exists('add_action')) {
                return;
            }

            $this->check_preview_mode();

            if (!is_admin()) {
                add_action('template_redirect', array($this, 'set_landingpage_cookie'));

                if ($this->options->css_enqueue_footer) {
                    add_action('get_footer', array($this, 'enqueue_styles'));
                } else {
                    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
                }
                add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            }
        }

        public function set_landingpage_cookie() {
            if (headers_sent()) {
                return;
            }

            if ($this->doing_ajax()) {
                return;
            }

            //for landing page tracking
            $cookie_name = $this->options->landingpage_cookie_name;
            if (!isset($_COOKIE[$cookie_name]) && !is_admin() && $this->options->display_pages == 2 && $this->enabled()) {
                setcookie($cookie_name, 1, 0, '/', '', false, true);
            }
        }

        public function check_preview_mode() {
            if ($this->options->preview_mode && isset($_GET[$this->get_preview_name()])) {
                $this->set_preview_mode();
                wp_redirect(home_url());
                WPFront_Notification_Bar::Instance()->kill();
            }
        }

        public function get_menu_slug() {
            return WPFront_Notification_Bar::PLUGIN_SLUG;
        }

        //options page scripts
        public function enqueue_options_scripts() {
            wp_enqueue_media();

            $this->enqueue_scripts();

            wp_enqueue_script('postbox');
            wp_enqueue_script('jquery');
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0');

            $js = 'js/vue.global.min.js';
            wp_enqueue_script('vue', plugins_url($js, $this->plugin_file), array('jquery'), '3.2.37');
            wp_enqueue_script('element-plus', plugins_url("js/element-plus.min.js", $this->plugin_file), array('vue'), '2.2.6');

            wp_enqueue_script('wpfront-notification-bar-options', plugins_url("js/options{$this->min_file_suffix}.js", $this->plugin_file), array('jquery','element-plus'), WPFront_Notification_Bar::VERSION);
        }

        //options page styles
        public function enqueue_options_styles() {
            $this->enqueue_styles();

            wp_enqueue_style('element-plus', plugins_url("css/element-plus.min.css", $this->plugin_file), array(), '2.2.6');

            $style = "css/options{$this->min_file_suffix}.css";
            wp_enqueue_style('wpfront-notification-bar-options', plugins_url($style, $this->plugin_file), array('element-plus'), WPFront_Notification_Bar::VERSION);
       
            
        }

        //add scripts
        public function enqueue_scripts() {
            if ($this->options->debug_mode) {
                add_action('wp_footer', array($this, 'write_debug_logs'), 99999);
                if ($this->options->attach_on_shutdown) {
                    add_action('shutdown', array($this, 'write_debug_logs'), 99999);
                }
            }

            if ($this->enabled() == false) {
                return;
            }

            wp_enqueue_script('jquery');

            if ($this->options->keep_closed || $this->options->set_max_views) {
                wp_enqueue_script('js-cookie', plugins_url('jquery-plugins/js-cookie.min.js', $this->plugin_file), array(), '2.2.1');
            }

            wp_enqueue_script('wpfront-notification-bar', plugins_url("js/wpfront-notification-bar{$this->min_file_suffix}.js", $this->plugin_file), array('jquery'), WPFront_Notification_Bar::VERSION);

            if ($this->options->position == 1) {
                add_action('wp_body_open', array($this, 'write_markup'), $this->options->fixed_position ? 1 : 10);
                add_action('wp_footer', array($this, 'write_markup'), $this->options->fixed_position ? 1 : 10); //Callback hook 'wp_body_open' only works from WordPress 5.2
            } else {
                add_action('wp_footer', array($this, 'write_markup'), $this->options->fixed_position ? 1 : 10);
            }
            if ($this->options->attach_on_shutdown) {
                add_action('shutdown', array($this, 'write_markup'), $this->options->fixed_position ? 1 : 10);
            }

            $this->scriptLoaded = true;
        }

        //add styles
        public function enqueue_styles() {
            if ($this->enabled() == false) {
                return;
            }

            wp_enqueue_style('wpfront-notification-bar', plugins_url("css/wpfront-notification-bar{$this->min_file_suffix}.css", $this->plugin_file), array(), WPFront_Notification_Bar::VERSION);

            if ($this->options->dynamic_css_use_url) {
                wp_enqueue_style('wpfront-notification-bar-custom', $this->custom_css_url(), array('wpfront-notification-bar'), WPFront_Notification_Bar::VERSION . '.' . $this->options->last_saved);
            }
        }

        public function view() {
            $view = new WPFront_Notification_Bar_Add_Edit_View($this);
            $view->view();
        }

        protected function custom_css_url() {
            return $this->main->custom_css_url();
        }

        public function get_html_id_suffix() {
            return '';
        }

        //writes the html and script for the bar
        public function write_markup() {
            if (is_admin()) {
                return;
            }

            if ($this->markupLoaded) {
                return;
            }

            if (!$this->scriptLoaded) {
                return;
            }

            if ($this->doing_ajax()) {
                return;
            }

            if ($this->enabled()) {
                $this->log('Writing HTML template.');

                $template = new WPFront_Notification_Bar_Template($this->options, $this);

                $template->write();

                $json = json_encode(array(
                    'position' => $this->options->position,
                    'height' => $this->options->height,
                    'fixed_position' => $this->options->fixed_position,
                    'animate_delay' => $this->options->animate_delay,
                    'close_button' => $this->options->close_button,
                    'button_action_close_bar' => $this->options->button_action_close_bar,
                    'auto_close_after' => $this->options->auto_close_after,
                    'display_after' => $this->options->display_after,
                    'is_admin_bar_showing' => is_admin_bar_showing(),
                    'display_open_button' => $this->options->display_open_button,
                    'keep_closed' => $this->options->keep_closed,
                    'keep_closed_for' => $this->options->keep_closed_for,
                    'position_offset' => $this->options->position_offset,
                    'display_scroll' => $this->options->display_scroll,
                    'display_scroll_offset' => $this->options->display_scroll_offset,
                    'keep_closed_cookie' => $this->options->keep_closed_cookie_name,
                    'log' => $this->options->debug_mode,
                    'id_suffix' => $this->get_html_id_suffix(),
                    'log_prefix' => $this->get_log_prefix(),
                    'theme_sticky_selector' => $this->options->theme_sticky_selector,
                    'set_max_views' => $this->options->set_max_views,
                    'max_views' => $this->options->max_views,
                    'max_views_for' => $this->options->max_views_for,
                    'max_views_cookie' => $this->options->max_views_cookie_name
                ));

                $this->write_load_script($json);
            }

            $this->markupLoaded = true;
        }

        private function write_load_script($json) {
            $this->log('Writing JS load script.');

            $this->write_debug_logs();

            if ($this->options->debug_mode) {
                $log_prefix = $this->get_log_prefix();
                ?>
                <script type="text/javascript">
                <?php echo "console.log('$log_prefix Starting JS scripts execution.');" ?>
                </script>
            <?php }
            ?>

            <script type="text/javascript">
                function __load_wpfront_notification_bar() {
                    if (typeof wpfront_notification_bar === "function") {
                        wpfront_notification_bar(<?php echo $json; ?>);
                    } else {
            <?php
            if ($this->options->debug_mode) {
                echo "console.log('$log_prefix Waiting for JS function \"wpfront_notification_bar\".');";
            }
            ?>
                        setTimeout(__load_wpfront_notification_bar, 100);
                    }
                }
                __load_wpfront_notification_bar();
            </script>
            <?php
        }

        public function write_debug_logs() {
            if (empty($this->logs)) {
                return;
            }

            if (!$this->options->debug_mode) {
                return;
            }

            if ($this->doing_ajax()) {
                return;
            }


            $now = current_time('mysql');
            $now = strtotime($now);
            $now_str = date('Y-m-d h:i:s a', $now);

            $log_prefix = $this->get_log_prefix();

            echo "<!-- '$log_prefix Page generated at $now_str. '-->";
            echo '<script type="text/javascript">';
            echo "console.log('$log_prefix Page generated at $now_str.');";
            foreach ($this->logs as $message => $args) {
                if (empty($args)) {
                    printf("console.log('$message');");
                } else {
                    vprintf("console.log('$message');", $args);
                }
            }
            echo '</script>';

            $this->logs = array();
        }

        public function get_message_text($translate = false) {
            $message = $this->options->message;

            if($translate) {
                $message = $this->get_wpml_string($message, 'Message Text');
            }

            $unfilter_html = defined('WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML') && constant('WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML');
            $unfilter_html = apply_filters('wpfront_notification_bar_message_allow_unfiltered_html', $unfilter_html);

            if (!$unfilter_html) {
                add_filter('wp_kses_allowed_html', array($this, 'message_allowed_html'));
                $message = wp_kses($message, wp_kses_allowed_html('post'));
                remove_filter('wp_kses_allowed_html', array($this, 'message_allowed_html'));
            }

            $message = apply_filters('wpfront_notification_bar_message', $message);

            if ($this->options->message_process_shortcode) {
                $message = do_shortcode($message);
            }

            return $message;
        }

        public function message_allowed_html($allowedposttags) {
            $allowedposttags['source'] = array('src' => true, 'type' => true);
            return $allowedposttags;
        }

        public function get_button_text($translate = false) {
            $text = $this->options->button_text;

            if($translate) {
                $text = $this->get_wpml_string($text, 'Button Text');
            }

            $unfilter_html = defined('WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML') && constant('WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML');
            $unfilter_html = apply_filters('wpfront_notification_bar_button_text_allow_unfiltered_html', $unfilter_html);

            if (!$unfilter_html) {
                $text = wp_kses($text, wp_kses_allowed_html('post'));
            }

            $text = apply_filters('wpfront_notification_bar_button_text', $text);

            if ($this->options->message_process_shortcode) {
                $text = do_shortcode($text);
            }

            return $text;
        }

        protected function filter() {
            if (is_admin()) {
                $this->log('Running in wp-admin, ignoring filters.');
                return true;
            }

            if ($this->options->filter_date_type === 'start_end') {
                $result = $this->start_date_time();
                if (!$result) {
                    return false;
                }
            }

            switch ($this->options->display_roles) {
                case 1:
                    break;
                case 2:
                    if (!$this->is_user_logged_in()) {
                        $this->log('Filter: Display only for logged-in users. User is not logged-in, disabling notification.');
                        return false;
                    }
                    break;
                case 3:
                    if ($this->is_user_logged_in()) {
                        $this->log('Filter: Display only for guest users. User is logged-in, disabling notification.');
                        return false;
                    }
                    break;
                case 4:
                    global $current_user;
                    if (empty($current_user->roles)) {
                        $role = self::ROLE_GUEST;
                        if ($this->is_user_logged_in())
                            $role = self::ROLE_NOROLE;
                        if (!in_array($role, $this->options->include_roles)) {
                            $this->log('Filter: Display set for user roles. Current user role is not allowed, disabling notification.');
                            return false;
                        }
                    } else {
                        $display = false;
                        foreach ($current_user->roles as $role) {
                            if (in_array($role, $this->options->include_roles)) {
                                $display = true;
                                break;
                            }
                        }
                        if (!$display) {
                            $this->log('Filter: Display set for user roles. Current user role is not allowed, disabling notification.');
                            return false;
                        }
                    }
                    break;
            }

            switch ($this->options->display_pages) {
                case 1:
                    return true;
                case 2:
                    if (isset($_COOKIE[$this->options->landingpage_cookie_name])) {
                        $this->log('Filter: Display only on landing page. This is not the landing page, disabling notification.');
                        return false;
                    }

                    return true;
                case 3:
                case 4:
                    global $post;
                    if (empty($post)) {
                        $this->log('Filter: Global post object is empty.');
                    }
                    $ID = false;
                    if (is_home()) {
                        $ID = 'home';
                    } elseif (is_singular()) {
                        $ID = $post->ID;
                    }
                    if ($this->options->display_pages == 3) {
                        if ($ID !== false) {
                            if ($this->filter_pages_contains($this->options->include_pages, $ID, true) === false) {
                                $this->log('Filter: Display is set to include in pages. Current page ID is "%s", which is not included, disabling notification.', array($ID));
                                return false;
                            } else {
                                return true;
                            }
                        }
                        return false;
                    }
                    if ($this->options->display_pages == 4) {
                        if ($ID !== false) {
                            if ($this->filter_pages_contains($this->options->exclude_pages, $ID, true) === false) {
                                return true;
                            } else {
                                $this->log('Filter: Display is set to exclude in pages. Current page ID is "%s", which is excluded, disabling notification.', array($ID));
                                return false;
                            }
                        }
                        return true;
                    }
            }

            return true;
        }

        protected function start_date_time() {
            $now = current_time('mysql');
            $now = strtotime($now);
            $now_str = date('Y-m-d h:i a', $now);
            $now = strtotime($now_str);

            $start_date = empty($this->options->start_date) ? null : strtotime($this->options->start_date);
            if (!empty($start_date)) {
                $start_date = date('Y-m-d', $start_date);
                $start_time = empty($this->options->start_time) ? null : strtotime($this->options->start_time);
                if (empty($start_time)) {
                    $start_time = '12:00 am';
                } else {
                    $start_time = date('h:i a', $start_time);
                }
                $start_date_str = $start_date . ' ' . $start_time;
                $start_date = strtotime($start_date_str);

                if ($start_date > $now) {
                    $this->log('Filter: Start time is in future, disabling notification. Start time: %s[%s], Current time: %s[%s]', [$start_date, $start_date_str, $now, $now_str]);
                    return false;
                }
            }

            $end_date = empty($this->options->end_date) ? null : strtotime($this->options->end_date);
            if (!empty($end_date)) {
                $end_date = date('Y-m-d', $end_date);
                $end_time = empty($this->options->end_time) ? null : strtotime($this->options->end_time);
                if (empty($end_time)) {
                    $end_time = '11:59 pm';
                } else {
                    $end_time = date('h:i a', $end_time);
                }

                $end_date_str = $end_date . ' ' . $end_time;
                $end_date = strtotime($end_date_str);

                if ($end_date < $now) {
                    $this->log('Filter: End time is in past, disabling notification. End time: %s[%s], Current time: %s[%s]', [$end_date, $end_date_str, $now, $now_str]);
                    return false;
                }
            }
            return true;
        }

        /**
         * Returns whether user is logged in
         *
         * @return boolean
         */
        protected function is_user_logged_in() {
            $logged_in = is_user_logged_in();

            if (!$logged_in && $this->options->wp_emember_integration && function_exists('wp_emember_is_member_logged_in')) {
                $logged_in = call_user_func('wp_emember_is_member_logged_in'); //@phpstan-ignore-line
            }

            return $logged_in;
        }

        protected function enabled() {
            if ($this->enabled !== null) {
                return $this->enabled;
            }

            if ($this->options->enabled) {
                $this->log('Notification bar is enabled.');
                $this->enabled = apply_filters('wpfront_notification_bar_enabled', $this->filter(), $this->options);
                return $this->enabled;
            }

            if ($this->is_preview_mode()) {
                $this->log('Notification bar is running in preview mode.');
                $this->enabled = apply_filters('wpfront_notification_bar_enabled', $this->filter(), $this->options);
                return $this->enabled;
            }

            $this->log('Notification bar is not enabled.');
            $this->enabled = apply_filters('wpfront_notification_bar_enabled', false, $this->options);
            return $this->enabled;
        }

        /**
         * Returns admin page footer text.
         *
         * @param string $text WordPress default.
         * @return string
         */
        public function admin_footer_text($text) {
            return $this->main->admin_footer_text($text);
        }

        protected function doing_ajax() {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                return TRUE;
            }

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                return TRUE;
            }

            if (!empty($_SERVER['REQUEST_URI']) && strtolower($_SERVER['REQUEST_URI']) == '/wp-admin/async-upload.php') {
                return TRUE;
            }

            if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
                return TRUE;
            }

            if (function_exists('wp_is_json_request') && wp_is_json_request()) {
                return TRUE;
            }

            if (function_exists('wp_is_jsonp_request') && wp_is_jsonp_request()) {
                return TRUE;
            }

            if (function_exists('wp_is_xml_request') && wp_is_xml_request()) {
                return TRUE;
            }

            if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
                return TRUE;
            }

            if (defined('WP_CLI') && constant('WP_CLI')) {
                return TRUE;
            }

            return FALSE;
        }

        /**
         * Settings updated callback.
         * Sets/Resets preview mode.
         * 
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         *
         * @param WPFront_Notification_Bar_Entity $entity
         * @return void
         */
        public function settings_updated($entity) {
            if ($entity->preview_mode) {
                $this->set_preview_mode();
            } else {
                $this->remove_preview_mode();
            }

            $this->register_wpml_string($entity->message, 'Message Text');
            if($entity->display_button) {
                $this->register_wpml_string($entity->button_text, 'Button Text');
            }
            
            if (function_exists('w3tc_flush_posts')) {
                call_user_func('w3tc_flush_posts'); //@phpstan-ignore-line
            }
        }

        /**
         * WPML Registration
         *
         * @param string $message_text
         * @param string $context
         * @return void
         */
        protected function register_wpml_string($value, $context) {
            do_action('wpml_register_single_string', 'WPFront Notification Bar', $context . $this->get_html_id_suffix(), $value);
        }

        /**
         * Returns wpml value
         *
         * @param string $value
         * @param string $context
         * @return string
         */
        protected function get_wpml_string($value, $context) {
            return apply_filters('wpml_translate_single_string', $value, 'WPFront Notification Bar', $context . $this->get_html_id_suffix());
        }

        public function get_filter_objects() {
            $objects = array();

            $objects['home'] = __('[Home Page]', 'wpfront-notification-bar');

            $posts = get_posts(array('numberposts' => 50));
            foreach ($posts as $post) {
                $objects[$post->ID] = __('[Post]', 'wpfront-notification-bar') . ' ' . $post->post_title;
            }

            $pages = get_pages(array('number' => 50));
            foreach ($pages as $page) {
                $objects[$page->ID] = __('[Page]', 'wpfront-notification-bar') . ' ' . $page->post_title;
            }

            $taxonomies = get_taxonomies(['public' => true]);
            $viewable_taxonomy = array();
            foreach ($taxonomies as $taxonomy) {
                if (function_exists('is_taxonomy_viewable') && !is_taxonomy_viewable($taxonomy)) {
                    continue;
                }
                $viewable_taxonomy[] = $taxonomy;
            }

            $taxonomies = [];
            foreach ($viewable_taxonomy as $tax) {
                $tax_object = get_taxonomy($tax);
                $taxonomies[$tax_object->name] = $tax_object->label;
            }
            asort($taxonomies);
            $taxonomies = array_keys($taxonomies);

            foreach ($taxonomies as $tax) {
                $terms = get_terms([
                    'taxonomy' => $tax,
                    'hide_empty' => false,
                ]);
                if (!empty($terms)) {
                    foreach ($terms as $term) {
                        $taxonomy_name = $term->taxonomy;
                        $taxonomy = get_taxonomy($taxonomy_name);
                        $objects['t' . $term->term_id] = '[' . $taxonomy->label . ']' . ' ' . $term->name;
                    }
                }
            }

            return $objects;
        }

        public function filter_pages_contains($list, $key, $deep = false) {
            $exists = strpos(',' . $list . ',', ',' . $key . ',');
            if ($exists !== false) {
                return true;
            }

            if (!$deep) {
                return false;
            }

            $ids = explode(',', $list);
            $term_ids = array();
            foreach ($ids as $id) {
                $id = trim($id);

                if($key == $id) {
                    return true;
                }
                
                if (substr($id, 0, 1) === 't') {
                    $term_ids[] = intval(substr($id, 1));
                }
            }

            $post_terms = wp_get_post_terms($key, get_taxonomies(), array('fields' => 'ids'));
            $actual_post_terms = array();
            foreach ($post_terms as $post_term) {
                do {
                    $term = get_term($post_term);
                    if($term instanceof \WP_Term) {
                        $actual_post_terms[] = $term->term_id;
                        $post_term = $term->parent;
                    } else {
                        $post_term = 0;
                    }
                } while ($post_term > 0);
            }

            $result = array_intersect($term_ids, $actual_post_terms);

            return !empty($result);
        }

        public function get_role_objects() {
            $objects = array();
            global $wp_roles;

            $roles = $wp_roles->role_names;
            foreach ($roles as $role_name => $role_display_name) {
                $objects[$role_name] = $role_display_name;
            }
            ksort($objects);
            return $objects;
        }

        /**
         * Set preview mode cookie
         * 
         * @SuppressWarnings(PHPMD.ErrorControlOperator)
         *
         * @return void
         */
        protected function set_preview_mode() {
            $preview_name = $this->get_preview_name();
            @setcookie($preview_name, 1, 0, '/');
        }

        /**
         * Remove preview mode cookie
         * 
         * @SuppressWarnings(PHPMD.ErrorControlOperator)
         *
         * @return void
         */
        protected function remove_preview_mode() {
            @setcookie($this->get_preview_name(), '', time() - 3600, '/');
        }

        private function is_preview_mode() {
            if ($this->options->preview_mode && isset($_COOKIE[$this->get_preview_name()])) {
                $this->log('Preview mode is enabled.');

                return true;
            }

            $this->log('Preview mode is not enabled.');
            return false;
        }

        protected function log($message, $args = null) {
            $log_prefix = $this->get_log_prefix();
            $this->logs["$log_prefix $message"] = $args;
        }

        public function get_plugin_file() {
            return $this->plugin_file;
        }

        public function get_options() {
            return $this->options;
        }

        public function display_on_page_load() {
            if (!$this->options->display_scroll && $this->options->display_after == 0 && $this->options->animate_delay == 0) {
                return true;
            }

            return false;
        }

        /**
         * Returns whether keep closed cookie is set
         *
         * @return boolean
         */
        public function has_keep_closed_set() {
            if ($this->options->keep_closed && isset($_COOKIE[$this->options->keep_closed_cookie_name])) {
                return true;
            }

            return false;
        }

        /**
         * Returns whether max views state reached.
         *
         * @return boolean
         */
        public function has_max_views_reached() {
            if ($this->options->set_max_views) {
                $views = 0;
                if(isset($_COOKIE[$this->options->max_views_cookie_name])) {
                    $views = intval($_COOKIE[$this->options->max_views_cookie_name]);
                }
                
                if($views >= $this->options->max_views) {
                    return true;
                }
            }

            return false;
        }

        public function get_lang_domain() {
            return $this->main->get_lang_domain();
        }

        public function get_entity_id() {
            return '';
        }

        public function get_preview_name() {
            $preview_id = $this->get_entity_id();
            if(!empty($preview_id)) {
                $preview_id = '-' . $preview_id;
            }
            return self::PREVIEW_MODE_NAME . $preview_id;
        }

        public function get_preview_url() {
            return add_query_arg($this->get_preview_name(), '1', home_url());
        }

        protected function get_log_prefix() {
            $entity_id = $this->get_entity_id();
            if (!empty($entity_id)) {
                return '[WPFront Notification Bar - ' . $entity_id . ']';
            } else {
                return '[WPFront Notification Bar]';
            }
        }

    }

}
