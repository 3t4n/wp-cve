<?php

/**
 * Dashboard class.
 *
 * @since      1.0.0
 * @package    Surror
 * @author     Surror <dev@surror.com>
 */
namespace FAL\Surror;

use FAL\Surror\Dashboard\Base;
\defined('ABSPATH') || exit;
/**
 * Dashboard class.
 */
class Dashboard extends Base
{
    /**
     * Version.
     */
    public $version = '1.0.4';
    /**
     * Constructor.
     */
    public function __construct($args = [])
    {
        parent::__construct();
        global $surror;
        // Not yet defined define with default values.
        if (empty($surror) || !isset($surror['dashboard'])) {
            $surror = ['dashboard' => ['path' => '', 'uri' => '', 'version' => '', 'hooks' => []]];
        }
        // Version check before execute latest code.
        if (!empty($surror['dashboard']['version'])) {
            if (\version_compare($surror['dashboard']['version'], $this->version, '>=')) {
                return;
            }
            $this->remove_hooks($surror['dashboard']['hooks']);
        }
        $hook_data = ['actions' => [['admin_init', [$this, 'save_authentication']], ['admin_init', [$this, 'check_product_subscriptions']], ['admin_menu', [$this, 'add_custom_admin_page']], ['admin_enqueue_scripts', [$this, 'enqueue_scripts']], ['wp_ajax_surror_activate_plugin', [$this, 'activate_plugin']]], 'filters' => [['pre_set_site_transient_update_plugins', [$this, 'transient_check'], 12], ['plugins_api', [$this, 'add_plugin_information'], 10, 3]]];
        // Store hooks in the global array.
        global $surror;
        $surror['dashboard'] = ['path' => $this->path, 'uri' => $this->uri, 'version' => $this->version, 'hooks' => $hook_data];
        // Add hooks.
        foreach ($hook_data['actions'] as $action_info) {
            $hook = $action_info[0];
            $method = $action_info[1];
            $priority = isset($action_info[2]) ? $action_info[2] : 10;
            $args = isset($action_info[3]) ? $action_info[3] : 1;
            add_action($hook, $method, $priority, $args);
        }
        // Add filters.
        foreach ($hook_data['filters'] as $filter_info) {
            $hook = $filter_info[0];
            $method = $filter_info[1];
            $priority = isset($filter_info[2]) ? $filter_info[2] : 10;
            $args = isset($filter_info[3]) ? $filter_info[3] : 1;
            add_filter($hook, $method, $priority, $args);
        }
    }
    /**
     * Remove hooks
     */
    function remove_hooks($hooks)
    {
        foreach ($hooks['actions'] as $action_info) {
            $hook = $action_info[0];
            $method = $action_info[1];
            $priority = isset($action_info[2]) ? $action_info[2] : 10;
            remove_action($hook, $method, $priority);
        }
        foreach ($hooks['filters'] as $filter_info) {
            $hook = $filter_info[0];
            $method = $filter_info[1];
            $priority = isset($filter_info[2]) ? $filter_info[2] : 10;
            remove_filter($hook, $method, $priority);
        }
    }
    /**
     * Activate
     */
    public function activate_plugin()
    {
        check_ajax_referer('surror_nonce', 'security');
        $plugin_init = isset($_POST['init']) ? esc_attr($_POST['init']) : '';
        if (!$plugin_init) {
            wp_send_json_error(['success' => \false, 'message' => __('Invalid plugin.', 'surror')]);
        }
        if (!current_user_can('install_plugins') || !$plugin_init) {
            wp_send_json_error(['success' => \false, 'message' => __('Dont have plugin install permissions.', 'surror')]);
        }
        $activate = activate_plugin($plugin_init, '', \false, \false);
        if (is_wp_error($activate)) {
            wp_send_json_error(['success' => \false, 'message' => $activate->get_error_message()]);
        }
        wp_send_json_success(['success' => \true, 'message' => __('Plugin Activated', 'surror')]);
    }
    /**
     * Get plugins
     */
    public function get_plugins()
    {
        return ['free-images' => ['id' => 'free-images', 'name' => __('Free Assets Library', 'surror'), 'description' => __('Free Assets Library is the #1 WordPress plugin which provides 600 Million FREE Images with 90,000+ downloads 🚀', 'surror'), 'on_org' => \true, 'rating_url' => 'https://wordpress.org/support/plugin/free-images/reviews/?filter=5#new-post', 'is_free' => \true, 'is_woo' => \false, 'is_new' => \false, 'url' => 'https://surror.com/free-assets-library/', 'doc_url' => 'https://docs.surror.com/doc/free-assets-library/', 'init' => 'free-images/free-images.php', 'is_installed' => \file_exists(WP_PLUGIN_DIR . '/' . 'free-images/free-images.php'), 'is_active' => is_plugin_active('free-images/free-images.php'), 'image' => 'https://ps.w.org/free-images/assets/icon-128x128.png?rev=2821179', 'active_links' => [['text' => __('See Library', 'surror'), 'url' => admin_url('upload.php?page=fal')]]], 'shortcodehub' => ['id' => 'shortcodehub', 'name' => __('ShortcodeHub', 'surror'), 'description' => __('Discover the Power of Shortcodes with "ShortcodeHub" - The Ultimate All-in-One Multi-Purpose Shortcode Builder!', 'surror'), 'on_org' => \true, 'rating_url' => 'https://wordpress.org/support/plugin/shortcodehub/reviews/?filter=5#new-post', 'is_free' => \true, 'is_woo' => \false, 'is_new' => \false, 'url' => 'https://surror.com/shortcodehub/', 'doc_url' => 'https://docs.surror.com/doc/shortcodehub/', 'init' => 'shortcodehub/shortcodehub.php', 'is_installed' => \file_exists(WP_PLUGIN_DIR . '/' . 'shortcodehub/shortcodehub.php'), 'is_active' => is_plugin_active('shortcodehub/shortcodehub.php'), 'image' => 'https://ps.w.org/shortcodehub/assets/icon-128x128.jpg?rev=2088527', 'active_links' => [['text' => __('Add Shortcode', 'surror'), 'url' => admin_url('edit.php?post_type=shortcode&page=shortcode-add-new')]]], 'easy-post-taxonomy-builder' => ['id' => 'easy-post-taxonomy-builder', 'name' => __('Post, Taxonomy Builder', 'surror'), 'description' => __('Manage custom post types and taxonomies effortlessly with the help of our intuitive post and taxonomy manager!', 'surror'), 'on_org' => \true, 'rating_url' => 'https://wordpress.org/support/plugin/easy-post-taxonomy-builder/reviews/?filter=5#new-post', 'is_free' => \true, 'is_woo' => \false, 'is_new' => \false, 'url' => 'https://surror.com/easy-post-taxonomy-builder/', 'doc_url' => 'https://docs.surror.com/doc/easy-post-taxonomy-builder/', 'init' => 'easy-post-taxonomy-builder/easy-post-tax-builder.php', 'is_installed' => \file_exists(WP_PLUGIN_DIR . '/' . 'easy-post-taxonomy-builder/easy-post-tax-builder.php'), 'is_active' => is_plugin_active('easy-post-taxonomy-builder/easy-post-tax-builder.php'), 'image' => 'https://ps.w.org/easy-post-taxonomy-builder/assets/icon-128x128.jpg?rev=2181221', 'active_links' => [['text' => __('Add Type', 'surror'), 'url' => admin_url('post-new.php?post_type=easy-post')]]], 'easy-search' => ['id' => 'easy-search', 'name' => __('Easy Search', 'surror'), 'description' => __('Simple plugin to display the quick search with shortcode [easy_search]. Also use it as a Gutenberg block.', 'surror'), 'on_org' => \true, 'rating_url' => 'https://wordpress.org/support/plugin/easy-search/reviews/?filter=5#new-post', 'is_free' => \true, 'is_woo' => \false, 'is_new' => \false, 'url' => 'https://surror.com/easy-search/', 'doc_url' => 'https://docs.surror.com/doc/easy-search/', 'init' => 'easy-search/easy-search.php', 'is_installed' => \file_exists(WP_PLUGIN_DIR . '/' . 'easy-search/easy-search.php'), 'is_active' => is_plugin_active('easy-search/easy-search.php'), 'image' => 'https://surror.com/wp-content/uploads/2023/08/easy-search-128x128.png', 'active_links' => []], 'qdocs' => ['id' => 'prod_ORYO3oUiCR64Ir', 'name' => __('QDocs', 'surror'), 'description' => __('Build your own documentation library to help your audience to understand about your product, business, or services.', 'surror'), 'on_org' => \false, 'rating_url' => '', 'is_free' => \false, 'is_woo' => \false, 'is_new' => \true, 'url' => 'https://surror.com/qdocs/', 'doc_url' => 'https://docs.surror.com/doc/qdocs/', 'init' => 'qdocs/qdocs.php', 'is_installed' => \file_exists(WP_PLUGIN_DIR . '/' . 'qdocs/qdocs.php'), 'is_active' => is_plugin_active('qdocs/qdocs.php'), 'image' => 'https://surror.com/wp-content/uploads/2023/08/qdocs-128x128.png', 'active_links' => [['text' => __('Add Doc', 'surror'), 'url' => admin_url('edit.php?post_type=doc')], ['text' => __('Settings', 'surror'), 'url' => admin_url('edit.php?post_type=doc&page=qdoc_settings')], ['text' => __('Analytics', 'surror'), 'url' => admin_url('edit.php?post_type=doc&page=qdoc_analytics')]]]];
    }
    /**
     * Save authentication.
     */
    function save_authentication()
    {
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $secret = isset($_GET['secret']) ? sanitize_text_field($_GET['secret']) : '';
        if (!$page || !$secret) {
            return;
        }
        if ('surror' !== $page) {
            return;
        }
        update_option('_surror_tools_town_secret', $secret);
        wp_safe_redirect(admin_url('admin.php?page=surror&check-updates=yes'));
        exit;
    }
    /**
     * Check authentication.
     */
    function check_product_subscriptions()
    {
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        $check_updates = isset($_GET['check-updates']) ? sanitize_text_field($_GET['check-updates']) : 'no';
        if (!$page || !$check_updates) {
            return;
        }
        if ('surror' !== $page || 'yes' !== $check_updates) {
            return;
        }
        $secret = get_option('_surror_tools_town_secret', '');
        if (!$secret) {
            return;
        }
        $validate_url = 'https://tools.town/api/surror/validate-subscription';
        $info = [];
        $products = $this->get_plugins();
        foreach ($products as $product_slug => $product) {
            if (empty($product['id']) || $product['on_org']) {
                continue;
            }
            $response = wp_remote_post($validate_url, ['method' => 'POST', 'body' => array('secret' => $secret, 'product_id' => $product['id'])]);
            $data = ['success' => \false];
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $data = \json_decode(wp_remote_retrieve_body($response), \true);
            }
            $subscription = [];
            if ($data['success']) {
                $subscription = $data['subscription'];
            }
            $info[$product_slug] = $subscription;
        }
        update_option('surror_tools_town_info', $info);
        wp_safe_redirect(admin_url('admin.php?page=surror'));
        exit;
    }
    /**
     * Transient check
     * 
     * @param array $transient
     */
    function transient_check($transient)
    {
        $active_plugin = [];
        $plugins = $this->get_plugins();
        foreach ($plugins as $plugin_slug => $plugin) {
            if (!$plugin['is_active'] || $plugin['on_org']) {
                continue;
            }
            $active_plugin[$plugin_slug] = $plugin;
        }
        if (empty($active_plugin)) {
            return $transient;
        }
        foreach ($active_plugin as $plugin_slug => $plugin) {
            $plugin_init = $plugin['init'];
            $current = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_init);
            // Get the installed version of the plugin.
            $installed_version = $current['Version'];
            // Update check.
            $expiry = (\time() + 24) * 60 * 60 * 1000;
            $auth = get_option('_surror_tools_town_secret', '');
            $nonce = wp_create_nonce('_surror_update_check');
            $secret = $nonce . '-' . $expiry . '-' . $nonce;
            $check_link = 'https://surror.com/wp-json/surror/v1/update-check/?file=' . $plugin_slug . '&version=' . $installed_version . '&secret=' . $secret . '&auth=' . $auth;
            $api_response = wp_safe_remote_get($check_link);
            $data = \json_decode(wp_remote_retrieve_body($api_response), \true);
            if (!$data['success']) {
                continue;
            }
            $transient->response[$plugin_init] = (object) $data['details'];
        }
        return $transient;
    }
    /**
     * Add plugin information
     * 
     * @param string $api
     * @param string $action
     * @param array $args
     */
    function add_plugin_information($api, $action, $args)
    {
        if ($action !== 'plugin_information') {
            return $api;
        }
        $products = $this->get_plugins();
        if (!\array_key_exists($args->slug, $products)) {
            return $api;
        }
        // Is free plugin which is org? Then return.
        if ($products[$args->slug]['on_org']) {
            return $api;
        }
        // Simulate a response from the API
        $expiry = (\time() + 24) * 60 * 60 * 1000;
        $auth = get_option('_surror_tools_town_secret', '');
        $nonce = wp_create_nonce('_surror_download');
        $secret = $nonce . '-' . $expiry . '-' . $nonce;
        $plugin = $products[$args->slug];
        $new_api = new \stdClass();
        $new_api->name = $plugin['name'];
        $new_api->slug = $args->slug;
        $new_api->download_link = 'https://surror.com/wp-json/surror/v1/download-zip/?file=' . $args->slug . '&secret=' . $secret . '&auth=' . $auth;
        return $new_api;
    }
    /**
     * Register admin page.
     */
    public function add_custom_admin_page()
    {
        $existing_menu = menu_page_url('surror', \false);
        if ($existing_menu) {
            return;
        }
        // If the page doesn't exist, register the new admin page
        add_menu_page(__('Surror', 'surror'), __('Surror', 'surror'), 'manage_options', 'surror', [$this, 'page_content'], 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI4IDIwIDIwIDE1LjUyMjggMjAgMTBDMjAgNC40NzcxNSAxNS41MjI4IDAgMTAgMEM0LjQ3NzE1IDAgMCA0LjQ3NzE1IDAgMTBDMCAxNS41MjI4IDQuNDc3MTUgMjAgMTAgMjBaTTEyLjQ3MzIgMTMuNTkwMkMxMi43MDY0IDEzLjA4NzQgMTIuODIzMSAxMi41MzAxIDEyLjgyMzEgMTEuOTE4QzEyLjgyMzEgMTEuMzM4OCAxMi43NDg4IDEwLjg2MzQgMTIuNjAwNCAxMC40OTE4QzEyLjQ2MjYgMTAuMTA5MyAxMi4yODc2IDkuNzkyMzUgMTIuMDc1NSA5LjU0MDk4QzExLjg2MzUgOS4yODk2MiAxMS42MzAyIDkuMDg3NDMgMTEuMzc1NyA4LjkzNDQzQzExLjEzMTkgOC43NzA0OSAxMC45MDM5IDguNjEyMDIgMTAuNjkxOCA4LjQ1OTAyQzEwLjQ3OTggOC4zMDYwMSAxMC4yOTk1IDguMTQyMDggMTAuMTUxMSA3Ljk2NzIxQzEwLjAxMzMgNy43OTIzNSA5Ljk0NDMzIDcuNTczNzcgOS45NDQzMyA3LjMxMTQ4QzkuOTQ0MzMgNy4xNDc1NCA5Ljk4Njc1IDYuOTk0NTQgMTAuMDcxNiA2Ljg1MjQ2QzEwLjE1NjQgNi43MTAzOCAxMC4yNTcxIDYuNTkwMTYgMTAuMzczOCA2LjQ5MThDMTAuNTAxIDYuMzgyNTEgMTAuNjIyOSA2LjI5NTA4IDEwLjczOTYgNi4yMjk1MUMxMC44NjY4IDYuMTYzOTMgMTAuOTcyOCA2LjEzMTE1IDExLjA1NzcgNi4xMzExNUMxMS4yMzc5IDYuMTMxMTUgMTEuMzgxIDYuMjA3NjUgMTEuNDg3MSA2LjM2MDY2QzExLjYwMzcgNi41MDI3MyAxMS42NjIgNi43Mzc3IDExLjY2MiA3LjA2NTU3QzExLjY2MiA3LjE1MzAxIDExLjY1MTQgNy4yNDA0NCAxMS42MzAyIDcuMzI3ODdDMTEuNjE5NiA3LjQxNTMgMTEuNjAzNyA3LjUwMjczIDExLjU4MjUgNy41OTAxNkwxMy44ODg3IDYuOTAxNjRDMTMuOTA5OSA2LjcyNjc4IDEzLjkzMTEgNi41NjI4NCAxMy45NTIzIDYuNDA5ODRDMTMuOTg0MSA2LjI1NjgzIDE0IDYuMTAzODMgMTQgNS45NTA4MkMxNCA1LjU5MDE2IDEzLjk0MTcgNS4yODk2MiAxMy44MjUxIDUuMDQ5MThDMTMuNzE5IDQuODA4NzQgMTMuNTcwNiA0LjYxMjAyIDEzLjM3OTcgNC40NTkwMkMxMy4xOTk1IDQuMjk1MDggMTIuOTgyMSA0LjE4MDMzIDEyLjcyNzYgNC4xMTQ3NUMxMi40NzMyIDQuMDM4MjUgMTIuMjAyOCA0IDExLjkxNjUgNEMxMS4zOTcgNCAxMC44NDU2IDQuMTAzODMgMTAuMjYyNCA0LjMxMTQ4QzkuNjc5MjYgNC41MTkxMyA5LjEzODUgNC44MDMyOCA4LjY0MDE2IDUuMTYzOTNDOC4xNTI0MiA1LjUxMzY2IDcuNzQ0MiA1LjkzNDQzIDcuNDE1NTEgNi40MjYyM0M3LjA4NjgxIDYuOTE4MDMgNi45MjI0NyA3LjQ0ODA5IDYuOTIyNDcgOC4wMTYzOUM2LjkyMjQ3IDguMzY2MTIgNy4wMDcyOSA4LjY4MzA2IDcuMTc2OTQgOC45NjcyMUM3LjM0NjU5IDkuMjUxMzcgNy41NDgwNSA5LjUwODIgNy43ODEzMSA5LjczNzcxQzguMDI1MTggOS45NjcyMSA4LjI5MDI2IDEwLjE4NTggOC41NzY1NCAxMC4zOTM0QzguODYyODIgMTAuNjAxMSA5LjEyNzkgMTAuODA4NyA5LjM3MTc3IDExLjAxNjRDOS42MTU2NCAxMS4yMTMxIDkuODE3MSAxMS40MjYyIDkuOTc2MTQgMTEuNjU1N0MxMC4xNDU4IDExLjg3NDMgMTAuMjMwNiAxMi4xMTQ4IDEwLjIzMDYgMTIuMzc3QzEwLjIzMDYgMTIuNTMwMSAxMC4xOTM1IDEyLjY2NjcgMTAuMTE5MyAxMi43ODY5QzEwLjA1NTcgMTIuODk2MiA5Ljk2NTU0IDEyLjk5NDUgOS44NDg5MSAxMy4wODJDOS43MzIyNyAxMy4xNjk0IDkuNjA1MDQgMTMuMjM1IDkuNDY3MiAxMy4yNzg3QzkuMzI5MzYgMTMuMzIyNCA5LjE5NjgyIDEzLjM0NDMgOS4wNjk1OCAxMy4zNDQzQzguOTIxMTQgMTMuMzQ0MyA4Ljc1MTQ5IDEzLjMxMTUgOC41NjA2NCAxMy4yNDU5QzguMzgwMzggMTMuMTY5NCA4LjE5NDgzIDEzLjA3NjUgOC4wMDM5OCAxMi45NjcyQzcuODIzNzIgMTIuODU3OSA3LjY1OTM4IDEyLjczMjIgNy41MTA5MyAxMi41OTAyQzcuMzYyNDkgMTIuNDM3MiA3LjI1MTE2IDEyLjI4OTYgNy4xNzY5NCAxMi4xNDc1TDYgMTQuNTI0NkM2LjM5MjMxIDE1LjAzODMgNi44MDA1MyAxNS40MTUzIDcuMjI0NjUgMTUuNjU1N0M3LjY0ODc3IDE1Ljg4NTIgOC4wNzI5IDE2IDguNDk3MDIgMTZDOS4xMDEzOSAxNiA5LjY2MzM1IDE1LjkwMTYgMTAuMTgyOSAxNS43MDQ5QzEwLjcxMzEgMTUuNTA4MiAxMS4xNjkgMTUuMjI5NSAxMS41NTA3IDE0Ljg2ODlDMTEuOTQzIDE0LjUwODIgMTIuMjUwNSAxNC4wODIgMTIuNDczMiAxMy41OTAyWiIgZmlsbD0iI0E3QUFBRCIvPgo8L3N2Zz4K', 99);
    }
    /**
     * Enqueue scripts
     * 
     * @param string $hook
     */
    public function enqueue_scripts($hook = '')
    {
        if ('toplevel_page_surror' !== $hook) {
            return;
        }
        wp_enqueue_style('surror-core-dashboard', $this->uri . 'assets/css/style.css', null, $this->version, 'all');
        wp_enqueue_script('surror-core-dashboard', $this->uri . 'assets/js/script.js', ['jquery', 'updates'], $this->version, \true);
        wp_localize_script('surror-core-dashboard', 'surrorVars', ['security' => wp_create_nonce('surror_nonce')]);
    }
    /**
     * Page content
     */
    public function page_content()
    {
        $store_page = 'https://tools.town/surror?tab=products';
        $user_firstname = wp_get_current_user()->user_firstname;
        $user_name = !empty($user_firstname) ? \ucfirst($user_firstname) : \ucfirst(wp_get_current_user()->display_name);
        $secret = get_option('_surror_tools_town_secret', '');
        $info = get_option('surror_tools_town_info', []);
        $connect_url = add_query_arg(['connect' => \urlencode(admin_url('admin.php?page=surror'))], $store_page);
        ?>
        <div class="s-page">
            <div class="s-header">
                <div class="s-logo">
                    <img src="<?php 
        echo esc_attr($this->uri) . '/assets/images/logo.svg';
        ?>" />
                </div>
                <div class='s-header-right'>
                    <ul class="s-menu s-menu-admin">
                        <li class="active">
                            <a href="#" data-slug='dashboard'><?php 
        esc_html_e('Dashboard', 'surror');
        ?></a>
                        </li>
                    </ul>
                    <div class="s-flex">
                        <ul class="s-menu s-menu-right">
                            <li>
                                <?php 
        if ($secret) {
            ?>
                                    <a style="color: green;" href="<?php 
            echo esc_attr($connect_url);
            ?>"><?php 
            esc_html_e('Connected', 'surror');
            ?> 
                                        <i class="dashicons dashicons-yes"></i>
                                    </a>
                                    <div class="s-tooltip">
                                        <a href="<?php 
            echo esc_url(admin_url('admin.php?page=surror&check-updates=yes'));
            ?>">
                                            <i class="dashicons dashicons-update"></i>
                                        </a>
                                        <span class="s-tooltip-content"><?php 
            esc_html_e('Fetch Latest Details', 'surror');
            ?></span>
                                    </div>
                                <?php 
        } else {
            ?>
                                    <a style="color: red;" href="<?php 
            echo esc_attr($connect_url);
            ?>"><?php 
            esc_html_e('Disconnected', 'surror');
            ?> <i class="dashicons dashicons-no-alt"></i></a>
                                <?php 
        }
        ?>
                            </li>
                            <li class="s-knowledge-base">
                                <a href="https://docs.surror.com/" target='_blank'>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                        <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"></path>
                                        <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"></path>
                                        <path d="M4.462 19.462c.42-.419.753-.89 1-1.394.453.213.902.434 1.347.661a6.743 6.743 0 01-1.286 1.794.75.75 0 11-1.06-1.06z"></path>
                                    </svg> <?php 
        esc_html_e('Knowledge Base', 'surror');
        ?>
                                </a>
                            </li>
                        </ul>
                        <!-- <div class="s-notification">
                            <div class="s-notification-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                                </svg>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="s-content">

                <!-- dashboard tab content -->
                <div class="s-tab-content" data-slug="dashboard">
                    <div class="s-banner">
                        <h1>Hello <?php 
        esc_html_e($user_name);
        ?></h1>
                        <p class="s-description"><?php 
        esc_html_e('Utilize the following plugins to enhance your experience.', 'surror');
        ?></p>
                    </div>

                    <div class="s-row s-plugins">
                        <?php 
        foreach ($this->get_plugins() as $plugin_slug => $plugin) {
            ?>
                            <div class="s-col s-col--3">
                                <div class="s-card">

                                    <?php 
            if ($plugin['is_free']) {
                ?>
                                        <div class="s-card--badge s-card--badge--free"><?php 
                esc_html_e('Free', 'surror');
                ?></div>
                                    <?php 
            } else {
                ?>
                                        <div class="s-card--badge s-card--badge--paid"><?php 
                esc_html_e('Premium', 'surror');
                ?></div>
                                    <?php 
            }
            ?>

                                    <?php 
            if ($plugin['image']) {
                ?>
                                        <div class="s-card--heading-with-image">
                                            <img src="<?php 
                echo esc_attr($plugin['image']);
                ?>" />
                                            <div class="s-card--heading"><?php 
                echo esc_attr($plugin['name']);
                ?></div>
                                        </div>
                                    <?php 
            } else {
                ?>
                                        <div class="s-card--heading"><?php 
                echo esc_html($plugin['name']);
                ?></div>
                                    <?php 
            }
            ?>

                                    <div class="s-card--description"><?php 
            echo esc_html($plugin['description']);
            ?></div>
                                    <ul class="s-card--actions s-flex">
                                        <li>
                                            <?php 
            $details = isset($info[$plugin_slug]) ? $info[$plugin_slug] : \false;
            if ($plugin['on_org'] || $details && $details['status'] && 'active' === $details['status']) {
                if ($plugin['is_installed'] && !$plugin['is_active']) {
                    ?>
                                                    <a class="s-btn s-btn-activate" data-slug="<?php 
                    echo esc_attr($plugin_slug);
                    ?>" data-init="<?php 
                    echo esc_attr($plugin['init']);
                    ?>" href="#"><?php 
                    esc_html_e('Activate', 'surror');
                    ?></a>
                                                	<?php 
                } else {
                    if ($plugin['is_active']) {
                        if (!empty($plugin['active_links'])) {
                            echo '<span class="s-active-links">';
                            foreach ($plugin['active_links'] as $active_link) {
                                ?>
															<a href="<?php 
                                echo esc_attr($active_link['url']);
                                ?>"><?php 
                                echo esc_html($active_link['text']);
                                ?></a>
															<?php 
                            }
                            echo '</span>';
                        } else {
                            ?>
														<span class="s-active"><?php 
                            esc_html_e('Active', 'surror');
                            ?></span>
														<?php 
                        }
                    } else {
                        ?>
                                                    <a class="s-btn s-btn-install" data-slug="<?php 
                        echo esc_attr($plugin_slug);
                        ?>" data-init="<?php 
                        echo esc_attr($plugin['init']);
                        ?>" href="#"><?php 
                        esc_html_e('Install', 'surror');
                        ?></a>
                                                <?php 
                    }
                }
                ?>
                                            <?php 
            } else {
                ?>
                                                <a class="s-btn" href="<?php 
                echo esc_attr($store_page);
                ?>" target="_blank"><?php 
                esc_html_e('Buy', 'surror');
                ?></a>
                                            <?php 
            }
            ?>
                                        </li>
                                        <li>
                                            <a href="<?php 
            echo esc_attr($plugin['doc_url']);
            ?>" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgb(148, 163, 184)" aria-hidden="true" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                                                    <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"></path>
                                                    <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"></path>
                                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.394.453.213.902.434 1.347.661a6.743 6.743 0 01-1.286 1.794.75.75 0 11-1.06-1.06z"></path>
                                                </svg> <?php 
            esc_html_e('Docs', 'surror');
            ?>
                                            </a>
                                        </li>
                                        <?php 
            if ($plugin['rating_url']) {
                ?>
                                            <li class="s-star-rating">
                                                <a href="<?php 
                echo esc_url($plugin['rating_url']);
                ?>" target="_blank">🌟</a>
                                            </li>
                                        <?php 
            }
            ?>
                                    </ul>
                                </div>
                            </div>
                        <?php 
        }
        ?>
                    </div>
                </div>
            </div>
            <div class="s-footer"></div>
        </div>
		<?php 
    }
}
