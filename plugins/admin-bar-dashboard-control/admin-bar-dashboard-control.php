<?php

/*
Plugin Name: Admin Bar & Dashboard Control
Plugin URI: https://profilepress.com/
Description: Disable admin bar and control access to WordPress dashboard.
Version: 1.2.9
Author: ProfilePress Team
Author URI: https://profilepress.com
License: GPL2
Text Domain: admin-bar-dashboard-control
Domain Path: /lang/
*/

namespace ProfilePress\PP_Admin_Bar_Control;

register_activation_hook(__FILE__, array('ProfilePress\PP_Admin_Bar_Control\PP_Admin_Bar_Control', 'on_activation'));
register_uninstall_hook(__FILE__, array('ProfilePress\PP_Admin_Bar_Control\PP_Admin_Bar_Control', 'on_uninstall'));

require_once dirname(__FILE__) . '/mo-admin-notice-featured.php';
require_once dirname(__FILE__) . '/settings.php';
require_once dirname(__FILE__) . '/fuse/FuseWP.php';
Settings::get_instance();
PP_Admin_Bar_Control::get_instance();
\PP_Admin_Bar_Control_FuseWP::get_instance();


add_action('init', function () {
    load_plugin_textdomain('admin-bar-dashboard-control', false, plugin_basename(dirname(__FILE__)) . '/lang');
});

class PP_Admin_Bar_Control
{
    protected $dashboard_redirect_url;
    protected $is_admin_bar_disabled;
    protected $is_dashboard_access_disabled;
    protected $disable_admin_bar_roles;
    protected $disable_dashboard_access_roles;

    private static $instance;

    public function __construct()
    {
        $abdc_options                       = get_option('abdc_options', array());
        $this->is_admin_bar_disabled        = isset($abdc_options['disable_admin_bar']) ? $abdc_options['disable_admin_bar'] : '';
        $this->is_dashboard_access_disabled = ! empty($abdc_options['disable_dashboard_access']) ? $abdc_options['disable_dashboard_access'] : '';
        $this->dashboard_redirect_url       = ! empty($abdc_options['dashboard_redirect_url']) ? $abdc_options['dashboard_redirect_url'] : home_url();

        $this->disable_admin_bar_roles        = ! empty($abdc_options['disable_admin_bar_roles']) ? $abdc_options['disable_admin_bar_roles'] : array();
        $this->disable_dashboard_access_roles = ! empty($abdc_options['disable_dashboard_access_roles']) ? $abdc_options['disable_dashboard_access_roles'] : array();

        add_filter('show_admin_bar', array($this, 'admin_bar'), 9999);
        add_action('admin_init', array($this, 'dashboard_access'), 1);
    }

    /**
     * Callback to disable admin bar.
     *
     * @return bool
     */
    public function admin_bar($show_admin_bar)
    {
        $current_user       = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        // bail if the disable admin bar checkbox isn't checked.
        if ($this->is_admin_bar_disabled != 'yes') {
            return $show_admin_bar;
        }

        if (is_user_logged_in()) {

            // get current user's admin_bar_front preference
            // if value is true, $user_option will has a boolen true value or false otherwise.
            $user_option = get_user_option('show_admin_bar_front', $current_user->ID) == 'true';

            // if user is admin, bail.
            if (is_super_admin($current_user->ID)) {
                return $user_option;
            }

            // if no role is selected, disable for everyone by return false.
            if (empty($this->disable_admin_bar_roles)) {
                return false;
            }

            $intersect = array_intersect($current_user_roles, $this->disable_admin_bar_roles);

            return empty($intersect);
        }

        return $show_admin_bar;
    }

    /**
     * Disable dashboard access.
     *
     * @return bool|void
     */
    public function dashboard_access()
    {
        $current_user       = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        // bail if doing admin-ajax.php as it's often accessed from frontend
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        if (basename(sanitize_text_field(wp_unslash($_SERVER['SCRIPT_FILENAME']))) == 'admin-post.php') return;

        // bail if disable dashboard access checkbox isn't checked.
        if ($this->is_dashboard_access_disabled != 'yes') {
            return;
        }

        // if user is admin, bail.
        if (is_super_admin($current_user->ID)) {
            return;
        }

        // if no role is selected, disable for everyone by return false.
        if (empty($this->disable_dashboard_access_roles)) {
            return $this->disable_dashboard_access();
        }

        $intersect = array_intersect($current_user_roles, $this->disable_dashboard_access_roles);

        if ( ! empty($intersect)) $this->disable_dashboard_access();
    }

    /**
     * Call to disable dashboard access.
     */
    public function disable_dashboard_access()
    {
        if (is_admin()) {
            wp_redirect(esc_url($this->dashboard_redirect_url));
            exit;
        }
    }

    public static function on_activation()
    {
        if ( ! current_user_can('activate_plugins')) {
            return;
        }

        $data = array(
            'disable_admin_bar'        => 'yes',
            'disable_dashboard_access' => 'yes',
        );

        add_option('abdc_options', $data);
    }

    public static function on_uninstall()
    {
        if ( ! current_user_can('activate_plugins')) {
            return;
        }

        delete_option('abdc_options');
    }

    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
