<?php
/*
Plugin Name: Cleavr Clear Cache
Description: Manage your NGINX FastCGI cache for your Cleavr sites. Simply add the clear cache trigger hook and then you can click a button to clear your site's cache and optionally clear cache every time content changes.
Version: 1.0
Author: Cleavr
Author URI: https://cleavr.io
*/

if (!defined('ABSPATH')) {
    exit;
}

class cleavrcc_NginxCache
{
    private $screen = 'tools_page_cleavr';
    private $capability = 'manage_options';
    private $admin_page = 'tools.php?page=cleavr';

    public function __construct()
    {
        add_filter('option_cleavr_nginx_cache_hook', 'sanitize_text_field');
        add_filter('option_cleavr_auto_clear_cache', 'absint');
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), [$this, 'cleavrcc_add_plugin_actions_links']);

        if (get_option('cleavr_auto_clear_cache')) {
            add_action('init', [$this, 'cleavrcc_register_clear_actions'], 20);
        }

        add_action('admin_init', [$this, 'cleavrcc_register_settings']);
        add_action('admin_menu', [$this, 'cleavrcc_add_admin_menu_page']);
        add_action('admin_bar_menu', [$this, 'cleavrcc_add_admin_bar_node'], 100);
        add_action('admin_enqueue_scripts', [$this, 'cleavrcc_enqueue_admin_styles']);
        add_action('load-'.$this->screen, [$this, 'cleavrcc_do_admin_actions']);
        add_action('load-'.$this->screen, [$this, 'cleavrcc_add_settings_notices']);
    }

    public function cleavrcc_register_clear_actions()
    {
        $clear_actions = (array) apply_filters('nginx_cache_clear_actions', [
            'publish_phone',
            'save_post',
            'edit_post',
            'delete_post',
            'wp_trash_post',
            'clean_post_cache',
            'trackback_post',
            'pingback_post',
            'comment_post',
            'edit_comment',
            'delete_comment',
            'wp_set_comment_status',
            'switch_theme',
            'wp_update_nav_menu',
            'edit_user_profile_update',
        ]);

        foreach ($clear_actions as $action) {
            if (did_action($action)) {
                $this->cleavrcc_trigger_clear_hook_once();
            } else {
                add_action($action, [$this, 'cleavrcc_trigger_clear_hook_once']);
            }
        }
    }

    public function cleavrcc_register_settings()
    {
        register_setting('cleavr', 'cleavr_nginx_cache_hook', 'sanitize_text_field');
        register_setting('cleavr', 'cleavr_auto_clear_cache', 'absint');
    }

    public function cleavrcc_add_settings_notices()
    {
        $path_error = $this->cleavrcc_is_valid_hook();

        if (isset($_GET['message']) && !isset($_GET['settings-updated'])) {

            if ($_GET['message'] === 'cache-cleared') {
                add_settings_error('', 'cleavr_nginx_cache_hook', 'Cache Cleared', 'updated');
            }

            if ($_GET['message'] === 'clear-cache-failed') {
                add_settings_error('', 'cleavr_nginx_cache_hook', 'Cache could not be cleared. Make sure the hook URL is correct.');
            }

        } else {
            if (is_wp_error($path_error) && $path_error->get_error_code() === 'fs') {
                add_settings_error('', 'cleavr_nginx_cache_hook', wptexturize($path_error->get_error_message('fs')));

            }
        }
    }

    public function cleavrcc_do_admin_actions()
    {
        // clear cache
        if (isset($_GET['action']) && $_GET['action'] === 'clear-nginx-cache' && wp_verify_nonce($_GET['_wpnonce'], 'clear-nginx-cache')) {
            $result = $this->cleavrcc_trigger_clear_hook();

            error_log( 'Result Code: ' . $result );

            if ($result) {
                wp_safe_redirect(admin_url(add_query_arg('message', 'cache-cleared', $this->admin_page)));
            } else {
                wp_safe_redirect(admin_url(add_query_arg('message', 'clear-cache-failed', $this->admin_page)));
            }

            exit;
        }
    }

    public function cleavrcc_add_admin_bar_node($wp_admin_bar)
    {
        // verify user capability
        if (!current_user_can($this->capability)) {
            return;
        }

        $wp_admin_bar->add_node([
            'id'    => 'cleavr',
            'title' => 'Cleavr',
            'href'  => admin_url($this->admin_page),
        ]);

        $wp_admin_bar->add_node([
            'parent' => 'cleavr',
            'id'     => 'clear-nginx-cache',
            'title'  => 'Clear Nginx Cache',
            'href'   => wp_nonce_url(admin_url(add_query_arg('action', 'clear-nginx-cache', $this->admin_page)), 'clear-nginx-cache'),
        ]);
    }

    public function cleavrcc_add_admin_menu_page()
    {
        // add "Tools" sub-page
        add_management_page('Cleavr', 'Cleavr', $this->capability, 'cleavr', [
            $this,
            'cleavrcc_show_settings_page',
        ]);
    }

    public function cleavrcc_show_settings_page()
    {
        require_once plugin_dir_path(__FILE__).'/includes/settings.php';
    }

    public function cleavrcc_add_plugin_actions_links($links)
    {
        // add settings link to plugin actions
        return array_merge(['<a href="'.admin_url($this->admin_page).'">Settings</a>'], $links);
    }

    public function cleavrcc_enqueue_admin_styles($hook_suffix)
    {
        if ($hook_suffix === $this->screen) {
            $plugin = get_plugin_data(__FILE__);
            wp_enqueue_style('cleavr', plugin_dir_url(__FILE__).'includes/cleavr.css', null, $plugin['Version']);
        }
    }

    private function cleavrcc_is_valid_hook()
    {
        $path = get_option('cleavr_nginx_cache_hook');

        if (empty($path)) {
            return new WP_Error('empty', '"Clear Cache Hook" is not set.');
        }

        return true;
    }

    public function cleavrcc_trigger_clear_hook_once()
    {
        static $completed = false;

        if (!$completed) {
            $this->cleavrcc_trigger_clear_hook();
            $completed = true;
        }
    }

    private function cleavrcc_trigger_clear_hook()
    {
        if (!$this->cleavrcc_should_clear()) {
            return false;
        }

        $hook = get_option('cleavr_nginx_cache_hook');
        $hook_error = $this->cleavrcc_is_valid_hook();

        if (is_wp_error($hook_error)) {
            return $hook_error;
        }

        $request = wp_remote_post($hook, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'body'        => array()
            ));

        return ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 204 ) ? false : true;

    }

    private function cleavrcc_should_clear()
    {
        $post_type = get_post_type();

        if (!$post_type) {
            return true;
        }

        if (!in_array($post_type, (array) apply_filters('cleavr_nginx_cache_excluded_post_types', []))) {
            return true;
        }

        return false;
    }
}

new cleavrcc_NginxCache;
