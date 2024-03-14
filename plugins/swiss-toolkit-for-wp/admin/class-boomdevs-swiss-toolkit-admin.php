<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Include the settings class to retrieve settings from the settings panel.
 */
//require_once BDSTFW_SWISS_TOOLKIT_PATH . '/includes/class-boomdevs-swiss-toolkit-settings.php';


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boomdevs_Swiss_Toolkit
 * @subpackage Boomdevs_Swiss_Toolkit/admin
 * @author     BoomDevs <contact@boomdevs.com>
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Admin')) {
	class BDSTFW_Swiss_Toolkit_Admin
	{
		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version The version of this plugin.
		 * @since    1.0.0
		 */
		public function __construct($plugin_name, $version)
		{
			$this->plugin_name = $plugin_name;
			$this->version = $version;
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            if (isset($settings['boomdevs_swiss_edit_username_switch'])) {
                if ($settings['boomdevs_swiss_edit_username_switch'] === '1') { ?>
                    <style>
                        .user-user-login-wrap {
                            display: none;
                        }
                    </style>
                <?php }
            }

            if (isset($settings['boomdevs_swiss_avatar_uploader_switcher'])) {
                if ($settings['boomdevs_swiss_avatar_uploader_switcher'] === '1') { ?>
                    <style>
                        .user-profile-picture {
                            display: none;
                        }

                        @media only screen and (min-width: 783px) {
                            #wpadminbar #wp-admin-bar-my-account.with-avatar>a img {
                                width: 16px !important;
                            }
                        }
                    </style>
                    <?php
                }
            }

			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/boomdevs-swiss-toolkit-admin.css', array(), time(), 'all');
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			global $current_user;
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/boomdevs-swiss-toolkit-admin.js', array('jquery'), time(), true);
			wp_localize_script($this->plugin_name, 'localize_object', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('generate_login_url'),
				'admin_url' => admin_url(),
				'default_avatar' => BDSTFW_SWISS_TOOLKIT_URL . 'admin/img/default-avatar.png',
				'current_user_id' => $current_user->data->ID
			));
		}
	}
}
