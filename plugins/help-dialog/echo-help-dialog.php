<?php
/**
 * Plugin Name: Help Dialog
 * Plugin URI: https://www.helpdialog.com/
 * Description: Let customers contact you through a WhatsApp chat or get answers from FAQs that you can compose using a ChatGPT-like AI.
 * Version: 2.3.2
 * Author: Echo Plugins
 * Author URI: https://www.helpdialog.com/about-us/
 * Text Domain: help-dialog
 * Domain Path: /languages
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Help Dialog is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Help Dialog is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Help Dialog. If not, see <http://www.gnu.org/licenses/>.
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'EPHD_PLUGIN_NAME' ) ) {
	define( 'EPHD_PLUGIN_NAME', 'Help Dialog Chat' );
}

/**
 * Main class to load the plugin.
 *
 * Singleton
 */
final class Echo_Help_Dialog {

	/* @var Echo_Help_Dialog */
	private static $instance;
	public static $version = '2.3.2';
	public static $plugin_dir;
	public static $plugin_url;
	public static $plugin_file = __FILE__;

	/* @var EPHD_Config_DB */
	public $global_config_obj;
	/* @var EPHD_Widgets_DB */
	public $widgets_config_obj;
	/* @var EPHD_Config_DB */
	public $notification_rules_config_obj;

	/**
	 * Initialise the plugin
	 */
	private function __construct() {
		self::$plugin_dir = plugin_dir_path(  __FILE__ );
		self::$plugin_url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Retrieve or create a new instance of this main class (avoid global vars)
	 *
	 * @static
	 * @return Echo_Help_Dialog
	 */
	public static function instance() {

		if ( ! empty( self::$instance ) && ( self::$instance instanceof Echo_Help_Dialog ) ) {
			return self::$instance;
		}

		self::$instance = new Echo_Help_Dialog();
		self::$instance->setup_system();
		self::$instance->setup_plugin();

		add_action( 'plugins_loaded', array( self::$instance, 'load_text_domain' ), 11 );

		return self::$instance;
	}

	/**
	 * Setup class auto-loading and other support functions. Setup custom core features.
	 */
	private function setup_system() {

		// autoload classes ONLY when needed by executed code rather than on every page request
		require_once self::$plugin_dir . 'includes/system/class-ephd-autoloader.php';

		// register settings
		self::$instance->global_config_obj = new EPHD_Config_DB( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );
		self::$instance->widgets_config_obj = new EPHD_Widgets_DB();
		self::$instance->notification_rules_config_obj = new EPHD_Config_DB( EPHD_Config_DB::EPHD_NOTIFICATION_RULES_CONFIG_NAME );

		// load non-classes
		require_once self::$plugin_dir . 'includes/system/plugin-setup.php';
		require_once self::$plugin_dir . 'includes/system/scripts-registration.php';
		require_once self::$plugin_dir . 'includes/system/plugin-links.php';

		add_action( 'init', array( self::$instance, 'ephd_stop_heartbeat' ), 1 );

		new EPHD_Upgrades();

		new EPHD_Help_Dialog_View();
	}

	/**
	 * Setup plugin before it runs. Include functions and instantiate classes based on user action
	 */
	private function setup_plugin() {

		$action = EPHD_Utilities::get( 'action' );

		// process action request if any
		if ( ! empty( $action ) ) {
			$this->handle_action_request( $action );
		}

		// handle AJAX front & back-end requests (no admin, no admin bar)
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->handle_ajax_requests( $action );
			return;
		}

		// ADMIN or CLI
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {	// || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
            if ( $this->is_hd_plugin_active_for_network() ) {
                add_action( 'plugins_loaded', array( self::$instance, 'setup_backend_classes' ), 11 );
            } else {
                $this->setup_backend_classes();
            }
			return;
		}

		// FRONT-END (no ajax, possibly admin bar)
	}

	/**
	 * Handle plugin actions here such as saving settings
	 * @param $action
	 */
	private function handle_action_request( $action ) {

		if ( $action == EPHD_Settings_Controller::DOWNLOAD_DEBUG_INFO_ACTION ) {
			new EPHD_Settings_Controller();
			return;
		}
	}

	/**
	 * Handle AJAX requests coming from front-end and back-end
	 * @param $action
	 */
	private function handle_ajax_requests( $action ) {

        if ( empty($action) ) {
            return;
        }

		if ( $action == 'ephd_dismiss_ongoing_notice' ) {
			new EPHD_Admin_Notices( true );
			return;
		}

		if ( in_array( $action, [ 'ephd_search', 'ephd_get_post_content' ] ) ) {
			new EPHD_Search();
			return;
		}

		if ( $action == 'ephd_help_dialog_contact' ) {
			new EPHD_Help_Dialog_Front_Ctrl();
			return;
		}

		if ( $action == 'ephd_deactivate_feedback' ) {
			new EPHD_Deactivate_Feedback();
			return;
		}

		if ( in_array( $action, [ 'ephd_save_global_settings', 'ephd_create_design', 'ephd_delete_design', 'ephd_duplicate_design', 'ephd_save_design_name' ] ) ) {
			new EPHD_Admin_Ctrl();
			return;
		}

		if ( in_array( $action, [ 'ephd_create_widget', 'ephd_update_widget', 'ephd_delete_widget', 'ephd_update_preview', 'ephd_search_locations', 'ephd_load_widget_form', 'ephd_copy_design_to', 'ephd_tiny_mce_input_save' ] ) ) {
			new EPHD_Widgets_Ctrl();
			return;
		}

		if ( in_array( $action, [ 'ephd_save_question_data', 'ephd_get_question_data', 'ephd_delete_question', 'ephd_save_faqs', 'ephd_load_faqs_form', 'ephd_update_faqs_preview' ] ) ) {
			new EPHD_FAQs_Ctrl();
			return;
		}

		if ( in_array( $action, [ 'ephd_submissions_delete_all', 'ephd_submissions_load_more', 'ephd_save_contact_form', 'ephd_delete_contact_form', 'ephd_load_contact_form', 'ephd_load_contact_form_preview', 'ephd_save_contact_form_settings' ] ) ) {
			new EPHD_Contact_Form_Ctrl();
			return;
		}

		if ( in_array( $action, [ 'ephd_fix_question_spelling_and_grammar', 'ephd_fix_answer_spelling_and_grammar', 'ephd_create_five_question_alternatives', 'ephd_create_five_answer_alternatives', 'ephd_create_answer_based_on_question' ] ) ) {
			new EPHD_AI_Help_Sidebar_Ctrl();
			return;
		}

		if ( in_array( $action, [ 'ephd_count_invocations_action', 'ephd_save_analytics_settings' ] ) ) {
			new EPHD_Analytics_Ctrl();
			return;
		}

		if ( in_array( $action, [ EPHD_Settings_Controller::TOGGLE_DEBUG_ACTION ] ) ) {
			new EPHD_Settings_Controller();
			return;
		}
	}

	/**
	 * Setup up classes when on ADMIN pages
	 */
	public function setup_backend_classes()	{
		global $pagenow;

		$request_page = empty($_REQUEST['page']) ? '' : sanitize_key($_REQUEST['page']);

		// article new or edit page
		if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
			new EPHD_Admin_Ctrl();
		}

		// admin core classes
		require_once self::$plugin_dir . 'includes/admin/admin-menu.php';
		require_once self::$plugin_dir . 'includes/admin/admin-functions.php';

		// Delete expired events
		$analytics = new EPHD_Analytics_DB();
		$analytics->delete_expired_analytics_records();

		// Help Dialog request
		if (EPHD_Core_Utilities::is_help_dialog_admin_page($request_page)) {
			add_action('admin_enqueue_scripts', 'ephd_load_admin_plugin_pages_resources');

			new EPHD_Admin_Notices();

			switch ($request_page) {

				// Widgets page
				case 'ephd-help-dialog-widgets':
					add_action('admin_enqueue_scripts', 'ephd_load_admin_help_dialog_widgets_script');
					break;

				// FAQs/Articles page
				case 'ephd-help-dialog-faqs':
					add_action('admin_enqueue_scripts', 'ephd_load_admin_help_dialog_faqs_articles_script');
					break;

				// Contact Form page
				case 'ephd-help-dialog-contact-form':
					add_action('admin_enqueue_scripts', 'ephd_load_admin_help_dialog_contact_form_script');
					break;

				// Analytics page
				case 'ephd-plugin-analytics':
					add_action('admin_enqueue_scripts', 'ephd_load_admin_help_dialog_analytics_script');
					break;

				// Configuration page
				case 'ephd-help-dialog-advanced-config':
					add_action('admin_enqueue_scripts', 'ephd_load_admin_help_dialog_config_script');
					break;

				default:
					break;
			}
		}

		if (!empty($pagenow) && in_array($pagenow, ['plugins.php', 'plugins-network.php'])) {
			new EPHD_Deactivate_Feedback();
		}
	}

	/**
	 * Loads the plugin language files from ./languages directory.
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'help-dialog', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	// Don't allow this singleton to be cloned.
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'Invalid (#1)', '4.0' );
	}

	// Don't allow un-serializing of the class except when testing
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, 'Invalid (#1)', '4.0' );
	}

	/**
	 * When developing and debugging we don't need heartbeat
	 */
	public function ephd_stop_heartbeat() {
		if ( defined( 'RUNTIME_ENVIRONMENT' ) && RUNTIME_ENVIRONMENT == 'ECHODEV' ) {
			wp_deregister_script( 'heartbeat' );
			// EPHD_Utilities::save_wp_option( EPHD_Settings_Controller::EPHD_DEBUG, true );
		}
	}

    private function is_hd_plugin_active_for_network() {
	    if ( ! is_multisite() ) {
            return false;
        }

        $plugins = get_site_option( 'active_sitewide_plugins' );
        if ( isset( $plugins['help-dialog/echo-help-dialog.php'] ) ) {
            return true;
        }

        return false;
    }
}

/**
 * Returns the single instance of this class
 *
 * @return Echo_Help_Dialog - this class instance
 */
function ephd_get_instance() {
	// TODO remove
	if ( class_exists( 'Echo_Help_Dialog_Pro' ) && version_compare( Echo_Help_Dialog_Pro::$version, '1.1.0', '<' ) ) {
		return null;
	}
	return Echo_Help_Dialog::instance();
}
ephd_get_instance();
