<?php
/**
 * The plugin bootstrap file.
 *
 * Plugin Name:       Nelio Content
 * Plugin URI:        https://neliosoftware.com/content/
 * Description:       Auto-post, schedule, and share your posts on Twitter, Facebook, LinkedIn, Instagram, and other social networks. Save time with useful automations.
 * Version:           3.2.1
 *
 * Author:            Nelio Software
 * Author URI:        https://neliosoftware.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain:       nelio-content
 *
 * @package Nelio_Content
 * @author  David Aguilera <david.aguilera@neliosoftware.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

define( 'NELIO_CONTENT', true );

function nelio_content() {
	return Nelio_Content::instance();
}//end nelio_content()

/**
 * Main class.
 */
class Nelio_Content {

	private static $instance = null;

	public $plugin_file;
	public $plugin_path;
	public $plugin_url;
	public $plugin_name;
	public $plugin_version;
	public $plugin_slug;
	public $plugin_name_sanitized;
	public $rest_namespace;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->load_dependencies();
			self::$instance->install();
			self::$instance->init();
		}//end if

		return self::$instance;

	}//end instance()

	private function load_dependencies() {

		$this->plugin_path    = untrailingslashit( plugin_dir_path( __FILE__ ) );
		$this->plugin_url     = untrailingslashit( plugin_dir_url( __FILE__ ) );
		$this->plugin_file    = 'nelio-content/nelio-content.php';
		$this->rest_namespace = 'nelio-content/v1';

		require_once $this->plugin_path . '/vendor/autoload.php';

		require_once $this->plugin_path . '/includes/lib/nelio/helpers/index.php';

		require_once $this->plugin_path . '/includes/utils/functions/api.php';
		require_once $this->plugin_path . '/includes/utils/functions/core.php';
		require_once $this->plugin_path . '/includes/utils/functions/helpers.php';
		require_once $this->plugin_path . '/includes/utils/functions/subscription.php';
		require_once $this->plugin_path . '/includes/utils/functions/validators.php';

	}//end load_dependencies()

	private function install() {

		add_action( 'plugins_loaded', array( $this, 'load_i18n_strings' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'plugin_data_init' ), 1 );

		if ( nc_is_staging() ) {
			add_action( 'after_plugin_row_nelio-content/nelio-content.php', array( $this, 'add_staging_warning' ) );
			return;
		}//end if

		$aux = Nelio_Content_Install::instance();
		$aux->init();

		$aux = Nelio_Content_Settings::instance();
		$aux->init();

		$aux = Nelio_Content_Admin::instance();
		$aux->init();

		$aux = Nelio_Content_Account_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_External_Featured_Image_Admin::instance();
		$aux->init();

		if ( is_admin() ) {
			$aux = Nelio_Content_Overview_Widget::instance();
			$aux->init();
		}//end if
	}//end install()

	private function init() {

		if ( ! $this->is_ready() ) {
			return;
		}//end if

		$this->init_common_helpers();
		$this->init_rest_controllers();
		$this->init_compat_fixes();

		if ( ! is_admin() ) {
			$aux = Nelio_Content_Public::instance();
			$aux->init();

			$aux = Nelio_Content_Meta_Tags::instance();
			$aux->init();
		}//end if

		$aux = Nelio_Content_External_Featured_Image_Public::instance();
		$aux->init();

	}//end init()

	public function is_ready() {

		return ! nc_is_staging() && ! empty( nc_get_site_id() );

	}//end is_ready()

	private function init_common_helpers() {

		$aux = Nelio_Content_Classic_Editor::instance();
		$aux->init();

		$aux = Nelio_Content_Gutenberg::instance();
		$aux->init();

		$aux = Nelio_Content_Analytics_Helper::instance();
		$aux->init();

		$aux = Nelio_Content_Auto_Sharer::instance();
		$aux->init();

		$aux = Nelio_Content_Cloud::instance();
		$aux->init();

		$aux = Nelio_Content_Post_Saving::instance();
		$aux->init();

		$aux = Nelio_Content_Notifications::instance();
		$aux->init();

		$aux = Nelio_Content_Missed_Schedule_Handler::instance();
		$aux->init();

		$aux = Nelio_Content_Ics_Calendar::instance();
		$aux->init();

	}//end init_common_helpers()

	private function init_rest_controllers() {

		$aux = Nelio_Content_Analytics_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Author_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_External_Calendar_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Feed_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Generic_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Internal_Events_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Placeholders_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Post_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Reference_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Shared_Link_REST_Controller::instance();
		$aux->init();

		$aux = Nelio_Content_Task_Presets_REST_Controller::instance();
		$aux->init();

	}//end init_rest_controllers()

	private function init_compat_fixes() {

		require_once nelio_content()->plugin_path . '/includes/compat/index.php';

	}//end init_compat_fixes()

	public function load_i18n_strings() {

		load_plugin_textdomain( 'nelio-content' );

	}//end load_i18n_strings()

	public function plugin_data_init() {

		$data = get_file_data( __FILE__, array( 'Plugin Name', 'Version' ), 'plugin' );

		$this->plugin_name           = $data[0];
		$this->plugin_version        = $data[1];
		$this->plugin_slug           = plugin_basename( __FILE__, '.php' );
		$this->plugin_name_sanitized = basename( __FILE__, '.php' );

	}//end plugin_data_init()

	public function add_staging_warning() {
		echo '<tr class="plugin-update-tr active" id="nelio-content-staging-warning" data-slug="nelio-content" data-plugin="nelio-content.php">';
		echo '<td colspan="4" class="plugin-update colspanchange">';
		echo '<div class="notice inline notice-warning notice-alt">';
		echo '<p>';

		printf(
			/* translators: a URL */
			_x( '<strong>Warning!</strong> This site has been identified as a <strong>staging site</strong> and, as a result, you can’t use any of Nelio Content’s features. If this is not correct and you want to use Nelio Content normally, please <a href="%s">follow these instructions</a>.', 'user', 'nelio-content' ), // phpcs:ignore
			add_query_arg( // phpcs:ignore
				array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'support',
					'utm_content'  => 'staging-warning',
				),
				__( 'https://neliosoftware.com/content/help/modify-list-of-staging-urls/', 'nelio-content' )
			)
		);

		echo '</p></div></td></tr>';
		echo '<script>(function(){document.getElementById("nelio-content-staging-warning").previousElementSibling.classList.add("update");})();</script>';
	}//end add_staging_warning()

}//end class

// Start plugin.
nelio_content();
