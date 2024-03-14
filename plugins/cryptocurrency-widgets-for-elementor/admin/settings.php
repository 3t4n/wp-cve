<?php
// Do not use namespace to keep this on global space to keep the singleton initialization working
if (!class_exists('CCWE_Settings')) {

    /**
     *
     * This is the main class for creating dashbord addon page and all submenu items
     *
     * Do not call or initialize this class directly, instead use the function mentioned at the bottom of this file
     */
    class CCWE_Settings
    {

        /**
         * None of these variables should be accessable from the outside of the class
         */
        private static $instance;

        /**
         * initialize the class and create dashboard page only one time
         */
        public static function init()
        {
            if (empty(self::$instance)) {
                return self::$instance = new self();
            }
            return self::$instance;

        }

        /**
         * Initialize the dashboard with specific plugins as per plugin tag
         */
        public function ccwe_init_hooks()
        {
            add_action('admin_menu', array($this, 'ccew_add_submenu_pages'), 50);
            add_action('admin_init', array($this, 'ccew_reg_settings'));

            add_action('admin_enqueue_scripts', array($this, 'ccew_admin_style'));
            add_action('wp_ajax_ccew_delete_transient', array($this, 'ccew_delete_transient'));

        }
        public function ccew_admin_style()
        {
            wp_enqueue_script('ccew-admin-script', CCEW_URL . 'assets/js/admin-script.js', array('jquery'), CCEW_VERSION);

            wp_enqueue_style('ccew_admin_menu_style', CCEW_URL . 'assets/css/ccew-admin.css', null, CCEW_VERSION);

        }

        /**
         * This function will initialize the main dashboard menu for all plugins
         */
        public function ccew_add_submenu_pages()
        {
            add_submenu_page('cool-crypto-plugins', 'Crypto Elementor Widget Settings', '<strong>Elementor Widget</strong>', 'manage_options', 'ccew-settings', array($this, 'Settings_callback'), 60);

            add_submenu_page('cool-crypto-plugins', 'Crypto Elementor Widget Settings', '↳ Settings', 'manage_options', 'ccew-settings', array($this, 'Settings_callback'), 61);

        }

        public function ccew_reg_settings()
        {
            register_setting(
                'ccew_option_group', // Option group
                'ccew-api-settings' // Option name
            );

            add_settings_section(
                'ccew_section_id', // ID
                'Crypto Elementor Widget Settings', // Title
                array($this, 'ccew_section_option'),
                'ccew-api-settings' // Page
            );

            add_settings_field('Api_key', 'Select API', array($this, 'api_key_fun'), 'ccew-api-settings', 'ccew_section_id');

            add_settings_field('ccew_delete_cache', 'Purge API Data Cache', array($this, 'ccew_purge_cache'), 'ccew-api-settings', 'ccew_section_id');

        }

        /**
         * This function will render and create the HTML display of dashboard page.
         * All the HTML can be located in other template files.
         * Avoid using any HTML here or use nominal HTML tags inside this function.
         */

        public function ccew_section_option()
        {
            echo '<div class="ccew_api_setting_section">Select API To Show Your Crypto Data In Elementor Widget</div>';
        }
        public function ccew_purge_cache()
        {

            $ajax_url = admin_url('admin-ajax.php');
            $ccew_nonce = wp_create_nonce('ccew-nonce');
            echo ' <button class="button button-secondary ccew-delete-transient" data-ccpw-nonce="' . esc_attr($ccew_nonce) . '" data-ajax-url="' . esc_url($ajax_url) . '" id="ccew-delete-transient">' . __('Purge Cache', 'ccpw') . '</button>';

        }

        public function api_key_fun()
        {
            $api_option = get_option('ccew-api-settings');
            $apikey = (!empty($api_option['select_api'])) ? $api_option['select_api'] : '';
            echo '<select name="ccew-api-settings[select_api]">
                    <option value="coin_gecko" ' . (($apikey == 'coin_gecko') ? 'selected' : '') . ' >Coin Gecko</option>
                    <option value="coin_paprika" ' . (($apikey == 'coin_paprika') ? 'selected' : '') . '>Coin Paprika</option>
            </select>';
        }

        public function Settings_callback()
        {
            ?>

			<?php settings_errors();?>
			<form method="post" action="options.php">
			<?php settings_fields('ccew_option_group');?>
			<?php do_settings_sections('ccew-api-settings');?>
			<?php submit_button();?>
			</form>
			<?php

            $get_setings = isset($_GET['page']) ? $_GET['page'] : '';
            $update_setings = isset($_GET['settings-updated']) ? $_GET['settings-updated'] : '';

            if ($get_setings == 'ccew-settings' && $update_setings == 'true') {
                $db = new ccew_database();
                $db->truncate_table();
                delete_transient('ccew-saved-coindata');
                delete_option('ccew_data_save');

            }

        }
        public function ccew_delete_transient()
        {
            // Check for nonce security
            if (!wp_verify_nonce($_POST['nonce'], 'ccew-nonce')) {
                die('You don\'t have permission to delete the cache.');
            }
            // Delete cache if user has permssion to delete it.
            if (current_user_can('manage_options')) {
                delete_transient('ccew-saved-coindata');
                delete_option('ccew_data_save');
                wp_send_json_success();
            }

        }

    }

    /**
     *
     * initialize the main dashboard class with all required parameters
     */

    $Openexchange = CCWE_Settings::init();
    $Openexchange->ccwe_init_hooks();

}
