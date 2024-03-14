<?php

/*
  WPFront Scroll Top Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront Scroll Top Plugin is distributed under the GNU General Public License, Version 3,
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

namespace WPFront\Scroll_Top;


require_once("trait-wpfront-scroll-top.php");
require_once("class-wpfront-scroll-top-template.php");
require_once("class-wpfront-scroll-top-options.php");
require_once("class-wpfront-scroll-top-options-view.php");

/**
 * Main class of WPFront Scroll Top plugin
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */
class WPFront_Scroll_Top
{
    use WPFront_Scroll_Top_Functions;

    //Constants
    const VERSION = '2.2.10081';
    const PLUGIN_SLUG = 'wpfront-scroll-top';

    /**
     * Plugin file
     *
     * @var string
     */
    protected $plugin_file;

    /**
     * Plugin base name
     *
     * @var string
     */
    protected $plugin_basename;

    /**
     * Suffix to use for assets
     *
     * @var string
     */
    protected $min_file_suffix = null;

    /**
     * Options obj
     *
     * @var WPFront_Scroll_Top_Options
     */
    protected $options;

    /**
     * Template obj
     *
     * @var WPFront_Scroll_Top_Template
     */
    protected $template;

    /**
     * Template obj
     *
     * @var WPFront_Scroll_Top_Options_View|null
     */
    protected $template_options;

    /**
     * Init function
     *
     * @param string $plugin_file
     * @return void
     */
    public static function init($plugin_file)
    {
        if(!function_exists('add_action')) {
            return;// @codeCoverageIgnore
        }

        $obj = new WPFront_Scroll_Top($plugin_file);
        add_action('plugins_loaded', array($obj, 'load'));
        $obj->add_activation_redirect();
    }

    /**
     *
     * @param string $plugin_file
     */
    public function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;
    }

    /**
     * Set dependencies
     *
     * @param WPFront_Scroll_Top_Options $options
     * @param WPFront_Scroll_Top_Template $template
     * @param WPFront_Scroll_Top_Options_View $template_options
     * 
     * @return void
     */
    public function set_dependencies($options, $template, $template_options) {
        $this->options = $options;
        $this->template = $template;
        $this->template_options = $template_options;
    }

    /**
     * Load function
     *
     * @return void
     */
    public function load()
    {
        if (is_admin()) {
            add_action('init', array($this, 'load_plugin_textdomain'));
            add_action('admin_init', array($this, 'register_admin_ajax'));
            add_action('admin_menu', array($this, 'admin_menu'));
            add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

            add_action('admin_init', array($this, 'enqueue_script_hooks'));
            
            return;
        }

        add_action('wp', array($this, 'enqueue_script_hooks')); //before wp filters won't work.
    }

    #region Helper Methods

    /**
     * Returns the capability name
     *
     * @return string
     */
    protected function get_capability()
    {
        return 'manage_options';
    }

    /**
     * Loads plugin text domain
     *
     * @return void
     */
    public function load_plugin_textdomain() {
        $dir = dirname(plugin_basename($this->plugin_file)) . '/languages';
        load_plugin_textdomain(self::PLUGIN_SLUG, false, $dir);
    }

    /**
     * Die if not authorized
     *
     * @return void
     */
    protected function verify_permission()
    {
        if (!current_user_can($this->get_capability())) {
            if (wp_doing_ajax()) {
                wp_send_json_error(__('Permission denied.', 'wpfront-scroll-top'));
            }

            wp_die(__('You do not have sufficient permissions to access this page.', 'wpfront-scroll-top'));
        }
    }

    /**
     * Returns options object
     *
     * @return WPFront_Scroll_Top_Options
     */
    protected function get_options()
    {
        if(empty($this->options)) { //@phpstan-ignore-line
            $entity = new WPFront_Scroll_Top_Options();
            $this->options = $entity->get();
        }

        return $this->options;
    }

    /**
     * Returns template object
     *
     * @return WPFront_Scroll_Top_Template
     */
    protected function get_template() {
        if(empty($this->template)) { //@phpstan-ignore-line
            $this->template = new WPFront_Scroll_Top_Template($this, $this->get_options());
        }

        return $this->template;
    }

    /**
     * Checks whether scroll top is enabled
     *
     * @return bool
     */
    protected function enabled()
    {
        $options = $this->get_options();
        $enabled = $options->enabled;

        if ($enabled && $options->hide_wpadmin && is_admin()) {
            $enabled = false;
        }

        if ($enabled && !$this->filter_pages()) {
            $enabled = false;
        }

        $enabled = apply_filters('wpfront_scroll_top_enabled', $enabled);

        return $enabled;
    }

    /**
     * Filters current page
     *
     * @return bool
     */
    protected function filter_pages()
    {
        if (is_admin()) {
            return true;
        }

        $options = $this->get_options();

        switch ($options->display_pages) {
            case 1:
                return true;
            case 2:
                return $this->filter_check_list($options->include_pages);
            case 3:
                return !$this->filter_check_list($options->exclude_pages);
        }

        return true;
    }

    /**
     * Checks current page id in list
     *
     * @param string $list
     * @return bool
     */
    protected function filter_check_list($list)
    {
        $id = false;
        global $post;

        if (is_home()) {
            $id = 'home';
        } elseif (!empty($post)) {
            $id = $post->ID;
        }

        if (!empty($id)) {
            if (strpos(",$list,", ",$id,") === false) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Returns plugins basename
     *  
     * @return string
     */
    protected function get_plugin_basename() {
        if(empty($this->plugin_basename)) {
            $this->plugin_basename = plugin_basename($this->plugin_file);
        }

        return $this->plugin_basename;
    }

    #endregion

    /**
     * Adds links in Plugins page
     *
     * @param string[] $links
     * @param string $file
     * @return string[]
     */
    public function plugin_action_links($links, $file)
    {
        if ($file == $this->get_plugin_basename()) {
            $settings_link = '<a href="' . menu_page_url(self::PLUGIN_SLUG, false) . '">' . __('Settings', 'wpfront-scroll-top') . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    #region Scroll Top functions

    /**
     * Returns data for front end button
     *
     * @param boolean $is_admin
     * @return array<string,string|array<string,mixed>>
     */
    public function get_front_end_data($is_admin) {
        $options = $this->get_options();
        $template = $this->get_template();

        $css = $template->get_css();
        $html = $template->get_html($is_admin);
        $data = array(
            'hide_iframe' => $options->hide_iframe,
            'button_fade_duration' => $options->button_fade_duration,
            'auto_hide' => $options->auto_hide,
            'auto_hide_after' => $options->auto_hide_after,
            'scroll_offset' => $options->scroll_offset,
            'button_opacity' => $options->button_opacity / 100,
            'button_action' => $is_admin ? 'top' : $options->button_action,
            'button_action_element_selector' => $options->button_action_element_selector,
            'button_action_container_selector' => $options->button_action_container_selector,
            'button_action_element_offset' => $options->button_action_element_offset,
            'scroll_duration' => $options->scroll_duration
        );

        return ['css' => $css, 'html' => $html, 'data' => $data];
    }

    /**
     * Add hooks required for scroll top
     *
     * @return void
     */
    public function enqueue_script_hooks()
    {
        if ($this->enabled()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scroll_top_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scroll_top_scripts'));
            if($this->get_options()->javascript_async) {
                add_filter('script_loader_tag', array($this, 'script_loader_tag'), 999999, 3);
            }
        }
    }

    /**
     * Returns min file suffix
     *
     * @return string
     */
    protected function get_min_file_suffix() {
        if($this->min_file_suffix === null) {
            $this->min_file_suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
        }

        return $this->min_file_suffix;
    }

    /**
     * Enqueue script and styles required for scroll top
     *
     * @return void
     */
    public function enqueue_scroll_top_scripts() {
        $min_suffix = $this->get_min_file_suffix();
        $src = plugin_dir_url(__DIR__) . "js/wpfront-scroll-top{$min_suffix}.js";
        wp_enqueue_script(self::PLUGIN_SLUG, $src, ['jquery'], self::VERSION, true);

        wp_localize_script(self::PLUGIN_SLUG, 'wpfront_scroll_top_data', [ 'data' => $this->get_front_end_data(is_admin()) ]);

        $options = $this->get_options();

        if ($options->button_style == 'font-awesome') {
            if (!$options->fa_button_exclude_URL || is_admin()) {
                $url = trim($options->fa_button_URL);
                $ver = false;
                if (empty($url)) {
                    $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
                    $ver = '4.7.0';
                }
                wp_enqueue_style('font-awesome', $url, array(), $ver);
            }
        }
    }

    /**
     * Adds async attributes to script tag
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public function script_loader_tag($tag, $handle, $src) {
        if ($handle === self::PLUGIN_SLUG) {
            return '<script src="' . $src . '" id="wpfront-scroll-top-js" async="async" defer="defer"></script>' . "\n";
        }

        return $tag;
    }

    #endregion

    #region Admin Ajax Hooks

    

    /**
     * Register admin ajax hooks
     *
     * @return void
     */
    public function register_admin_ajax()
    {
        add_action('wp_ajax_wpfront_scroll_top_get_data', array($this, 'send_ajax_data'));
        add_action('wp_ajax_wpfront_scroll_top_get_static', array($this, 'send_ajax_static'));
        add_action('wp_ajax_wpfront_scroll_top_submit_data', array($this, 'submit_data'));
    }

    /**
     * Sends data for ajax call
     *
     * @return void
     */
    public function send_ajax_data()
    {
        $this->verify_permission();

        $entity = $this->get_options();

        $data = array(
            'data' => $entity,
            'nonce' => wp_create_nonce('wpfront-scroll-top')
        );

        wp_send_json_success($data);
    }

    /**
     * Sends static data like templates
     *
     * @return void
     */
    public function send_ajax_static()
    {
        $this->verify_permission();

        $templates_list = array(
            'display-settings',
            'button-action-settings',
            'location-settings',
            'filter-settings',
            'posts-filter-selection',
            'image-button-settings',
            'font-awesome-button-settings',
            'text-button-settings',
            'help-icon',
            'color-picker'
        );

        $templates = array();
        $dir = dirname(__FILE__, 2);
        foreach ($templates_list as $template) {
            $file = "$dir/templates/$template/$template.html";
            $templates[$template] = file_get_contents($file);
        }

        $data = array(
            'templates' => $templates,
            'labels' => $this->get_labels_data(),
            'help' => $this->get_help_data(),
            'location_options' => $this->get_location_options(),
            'button_style_options' => $this->get_button_style_options(),
            'button_action_options' => $this->get_button_action_options(),
            'icons_list' => $this->get_icons(),
            'filter_options' => $this->get_filter_options(),
            'filter_posts_list' => $this->get_filter_objects(),
        );

        header('Cache-Control: max-age=60');
        add_filter('nocache_headers', '__return_empty_array');
        wp_send_json_success($data);
    }

    /**
     * Submit data from Ajax
     *
     * @return void
     */
    public function submit_data()
    {
        $this->verify_permission();

        if (!check_ajax_referer('wpfront-scroll-top', false, false)) {
            wp_send_json_error(__('Permission denied.', 'wpfront-scroll-top'));
        }

        $data = $_POST['data'];
        $data = stripslashes($data);
        $data = html_entity_decode($data);
        $data = json_decode($data);

        $options = $this->get_options();
        $options->set_values($data);
        $options->save();

        $current_url = admin_url('options-general.php?page=' . self::PLUGIN_SLUG);
        $current_url = $current_url . '&settings-updated=true';

        if(function_exists('w3tc_flush_posts')) {
            w3tc_flush_posts();
        }

        wp_send_json_success($current_url);
    }

    #endregion

    #region Menu Functions

    /**
     * Adds the menu
     *
     * @return void
     */
    public function admin_menu()
    {
        $page_hook_suffix = add_options_page(__('WPFront Scroll Top', 'wpfront-scroll-top'), __('Scroll Top', 'wpfront-scroll-top'), $this->get_capability(), self::PLUGIN_SLUG, array($this, 'options_page'));

        add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'enqueue_options_scripts'));
        add_action('admin_print_styles-' . $page_hook_suffix, array($this, 'enqueue_options_styles'));
    }

    /**
     * Returns options view object
     *
     * @return WPFront_Scroll_Top_Options_View
     */
    protected function get_options_template() {
        if(empty($this->template_options)) {
            $this->template_options = new WPFront_Scroll_Top_Options_View();
        }

        return $this->template_options;
    }

    /**
     * Options page view
     *
     * @return void
     */
    public function options_page()
    {
        $this->verify_permission();

        $options_view = $this->get_options_template();
        $options_view->view($this);
    }

    /**
     * Enqueue options scripts
     *
     * @return void
     */
    public function enqueue_options_scripts()
    {
        $min_suffix = $this->get_min_file_suffix();
        $jsRoot = plugin_dir_url($this->plugin_file) . 'js/';

        wp_enqueue_media();
        wp_enqueue_script('postbox');
        wp_enqueue_script('vue', "{$jsRoot}vue.global.prod.js", array('jquery'), '3.2.37');
        wp_enqueue_script('element-plus', "{$jsRoot}element-plus.min.js", array('vue'), '2.2.6');
        wp_enqueue_script('wpfront-scroll-top-app', "{$jsRoot}options{$min_suffix}.js", array('jquery', 'vue', 'element-plus'), self::VERSION);
    }

    /**
     * Enqueue options CSS
     *
     * @return void
     */
    public function enqueue_options_styles()
    {
        $min_suffix = $this->get_min_file_suffix();
        $styleRoot = plugin_dir_url($this->plugin_file) . 'css/';
        wp_enqueue_style('element-plus', "{$styleRoot}element-plus.min.css", array(), '2.2.6');
        wp_enqueue_style('wpfront-scroll-top-options', "{$styleRoot}options{$min_suffix}.css", array('element-plus'), self::VERSION);
    }

    #endregion

    #region Plugin Activation Redirect

    /**
     * Hooks for activation redirect
     *
     * @return void
     */
    protected function add_activation_redirect()
    {
        add_action('activated_plugin', array($this, 'activated_plugin_callback'));
        add_action('admin_init', array($this, 'redirect_after_activation'), 999999);
    }

    /**
     * Adds option on activation
     *
     * @param string $plugin
     * @return void
     */
    public function activated_plugin_callback($plugin)
    {
        if ($plugin !== $this->get_plugin_basename()) {
            return;
        }

        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }

        $key = $this->plugin_file . '-activation-redirect';
        add_option($key, true);
    }

    /**
     * Redirect if option exists
     *
     * @return void
     */
    public function redirect_after_activation()
    {
        $key = $this->plugin_file . '-activation-redirect';

        if (get_option($key, false)) {
            delete_option($key);

            if (is_network_admin() || isset($_GET['activate-multi'])) {
                return;
            }

            wp_safe_redirect(menu_page_url(self::PLUGIN_SLUG, false));
            exit;// @codeCoverageIgnore
        }
    }

    #endregion
}
