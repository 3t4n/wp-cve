<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts_Admin
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 *
 * @package Easy_Related_Posts_Admin
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class easyRelatedPostsAdmin {

    /**
     * Instance of this class.
     *
     * @since 2.0.0
     * @var easyRelatedPostsAdmin
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since 2.0.0
     * @var string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     *
     * @var string 
     */
    private $plugin_slug;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since 2.0.0
     */
    private function __construct() {

        /**
         * *****************************************************
         * admin class should only be available for super admins
         * *****************************************************
         */
        if (!is_super_admin()) {
            return;
        }

        /**
         * ******************************************************
         * Call $plugin_slug from public plugin class.
         * *****************************************************
         */
        $plugin = easyRelatedPosts::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_admin_styles'
        ));
        add_action('admin_enqueue_scripts', array(
            $this,
            'enqueue_admin_scripts'
        ));

        /**
         * ******************************************************
         * Add the options page and menu item.
         * *****************************************************
         */
        add_action('admin_menu', array(
            $this,
            'add_plugin_admin_menu'
        ));

        /**
         * ******************************************************
         * Add an action link pointing to the options page.
         * *****************************************************
         */
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . 'easy_related_posts.php');
        add_filter('plugin_action_links_'.$plugin_basename, array(
            $this,
            'add_action_links'
        ));

        /**
         * ******************************************************
         * Save options
         * *****************************************************
         */
        add_action('admin_post_save_' . EPR_MAIN_OPTIONS_ARRAY_NAME, array(
            $this,
            'saveOptions'
        ));

        /**
         * ******************************************************
         * Ajax hooks
         * *****************************************************
         */
        add_action('wp_ajax_loadTemplateOptions', array(
            $this,
            'loadTemplateOptions'
        ));
    }

    /**
     * Return an instance of this class.
     *
     * @since 2.0.0
     * @return object A single instance of this class.
     */
    public static function get_instance() {

        /*
         * admin class should only be available for super admins
         */
        if (!is_super_admin()) {
            return;
        }

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since 2.0.0
     * @return null Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($this->plugin_screen_hook_suffix == $screen->id || 'widgets' == $screen->id) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.min.css', __FILE__), array(), easyRelatedPosts::VERSION);
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since 2.0.0
     * @return null Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();

        if ($this->plugin_screen_hook_suffix == $screen->id || 'widgets' == $screen->id) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-effects-fade');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-slider');

            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.min.js', __FILE__), array(
                'jquery',
                'jquery-ui-tabs'
                    // $this->plugin_slug . '-qtip'
                    ), easyRelatedPosts::VERSION);
        }
        if ($this->plugin_screen_hook_suffix == $screen->id) {
            wp_enqueue_script($this->plugin_slug . '-main-settings', plugins_url('assets/js/mainSettings.min.js', __FILE__), array(
                $this->plugin_slug . '-admin-script'
                    ), easyRelatedPosts::VERSION);
        }
        if ('widgets' == $screen->id) {
            wp_enqueue_script($this->plugin_slug . '-widget-settings', plugins_url('assets/js/widgetSettings.min.js', __FILE__), array(
                $this->plugin_slug . '-admin-script'
                    ), easyRelatedPosts::VERSION);
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 2.0.0
     */
    public function add_plugin_admin_menu() {
        $this->plugin_screen_hook_suffix = add_options_page(__('Easy Related Posts', $this->plugin_slug), __('Easy Related Posts', $this->plugin_slug), 'manage_options', $this->plugin_slug . '_settings', array(
            $this,
            'display_plugin_admin_page'
        ));
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 2.0.0
     */
    public function display_plugin_admin_page() {        
        if (!class_exists('erpView')) {
            erpPaths::requireOnce(erpPaths::$erpView);
        }

        $defaultOptions = erpDefaults::$mainOpts + erpDefaults::$comOpts;
        $optObj = new erpMainOpts();
        $options = $optObj->getOptions();

        $viewData ['erpOptions'] = is_array($options) ? array_merge($defaultOptions, $options) : $defaultOptions;
        $viewData ['optObj'] = $optObj;

        erpView::render(plugin_dir_path(__FILE__) . 'views/admin.php', $viewData, TRUE);
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since 2.0.0
     */
    public function add_action_links($links) {
        return array_merge(array(
            'settings' => '<a href="' . admin_url('options-general.php?page=' . $this->plugin_slug . '_settings') . '">' . __('Settings', $this->plugin_slug) . '</a>'
                ), $links);
    }

    /**
     * Saves admin options.
     * This is called through a hook
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function saveOptions() {
        if (!current_user_can('manage_options')) {
            wp_die('Not allowed');
        }
        erpPaths::requireOnce(erpPaths::$erpMainOpts);
        // Save template options
        if (isset($_POST ['dsplLayout'])) {
            erpPaths::requireOnce(erpPaths::$VPluginThemeFactory);
            VPluginThemeFactory::registerThemeInPathRecursive(erpPaths::getAbsPath(erpPaths::$mainThemesFolder), $_POST ['dsplLayout']);
            
            $theme = VPluginThemeFactory::getThemeByName($_POST ['dsplLayout']);
            
            if($theme){
                $theme->saveSettings($_POST);
                foreach ($theme->getDefOptions() as $key => $value) {
                    unset($_POST [$key]);
                }
            } else {
                $message = new WP_Error_Notice('Theme '.$_POST ['dsplLayout'].' not found. Theme options discarded', 1, array('settings_page_erp_settings'));
                WP_Admin_Notices::getInstance()->addNotice($message);
            }
        }
        // Save the rest of the options
        $mainOptionsObj = new erpMainOpts();
        $mainOptionsObj->saveOptions($_POST);
        
        $message = new WP_Updated_Notice('Options saved', 1, array('settings_page_erp_settings'));
        WP_Admin_Notices::getInstance()->addNotice($message);
        
        wp_redirect(add_query_arg(array(
            'page' => $this->plugin_slug . '_settings',
            'tab-spec' => wp_strip_all_tags($_POST ['tab-spec'])
                        ), admin_url('options-general.php')));
        exit();
    }

    /**
     * This is called through ajax hook and returns the plugin options as defined in template settings file
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function loadTemplateOptions() {
        if (!isset($_POST ['template']) || !isset($_POST ['templateRoot'])) {
            echo json_encode(false);
            die();
        }
        erpPaths::requireOnce(erpPaths::$VPluginThemeFactory);
        
        VPluginThemeFactory::registerThemeInPathRecursive(erpPaths::getAbsPath(erpPaths::$mainThemesFolder), $_POST ['template']);
        
        $theme = VPluginThemeFactory::getThemeByName($_POST ['template']);
        
        $data = array();
        
        if($theme){
            $data = array(
                'content' => $theme->renderSettings('', false),
                'optionValues' => $theme->getOptions()
            );
        }

        echo json_encode($data);
        die();
    }

}
