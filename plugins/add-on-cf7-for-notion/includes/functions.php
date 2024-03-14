<?php
/**
 * Global plugin's functions.
 * No namespace, these globally-available-functions are prefixed.
 *
 * @package add-on-cf7-for-notion
 */

use WPC_WPCF7_NTN\API_Notion;
use WPC_WPCF7_NTN\Options;
use WPC_WPCF7_NTN\WPCF7_Notion_Service;

defined( 'ABSPATH' ) || exit;


/**
 * Get an instance of the Notion API.
 *
 * @param string $key Api key.
 * @return API_Notion
 */
function wpconnect_wpcf7_notion_get_api( $key = null ) {
	$service = WPCF7_Notion_Service::get_instance();

	return new API_Notion( is_null( $key ) ? $service->get_api_key() : $key );
}

/**
 * Is the API Key valid?
 *
 * @return boolean
 */
function wpconnect_wpcf7_notion_api_key_is_valid() {
	return Options\get_plugin_option( 'notion_api_key_is_valid' ) > 0;
}

/**
 * Register WPCF7 Notion service
 *
 * @return void
 */
function wpconnect_wpcf7_notion_register_service() {
	$integration = WPCF7_Integration::get_instance();

	$integration->add_service(
		'wpc_notion',
		WPCF7_Notion_Service::get_instance()
	);
}

/**
 * Save plugin version
 *
 * @return void
 */
function wpconnect_wpcf7_notion_save_plugin_version() {
	Options\update_plugin_option( 'version', WPCONNECT_WPCF7_NTN_VERSION );
}

/**
 * Show admin notice
 *
 * @return void
 */
function wpconnect_wpcf7_notion_admin_notice__info() {
	$allowed_pages_notices = array(
		'contact_page_wpcf7-integration',
		'contact_page_wpcf7-new',
		'toplevel_page_wpcf7',
	);
	if ( ! get_user_meta( get_current_user_id(), 'cf7_notion_addon_notice_dismissed' ) && in_array( get_current_screen()->id, $allowed_pages_notices, true ) ) {
		$dismiss_url = wp_nonce_url(
			add_query_arg(
				'cf7_notion_addon-dismissed',
				'1',
				get_permalink( get_current_screen()->id )
			),
			'wpc-nonce'
		);
		?>
	<div class="notice notice-info is-dismissible">
		<p style="margin-top:20px;"><b><?php echo esc_html( __( 'Thank you for downloading CF7 to Notion Integration!', 'add-on-cf7-for-notion' ) ); ?></b></p>
		<p><?php echo esc_html( __( 'Love our plugin? ', 'add-on-cf7-for-notion' ) ); ?><a href="https://wordpress.org/support/plugin/add-on-cf7-for-notion/reviews/#new-post" target="_blank"><?php echo esc_html( __( 'Please leave us a review!', 'add-on-cf7-for-notion' ) ); ?></a></p>
		<p style="margin-bottom:20px;"><?php echo esc_html( __( 'Discover all our products at: ', 'add-on-cf7-for-notion' ) ); ?><a href="https://wpconnect.co" target="_blank">https://wpconnect.co</a></p>
		<a href="<?php echo esc_url( $dismiss_url ); ?>" style="color: #3c434a;" class="wpc-wpcf7-notion-dismiss-link" ><?php echo esc_html( __( 'Dismiss this notice', 'add-on-cf7-for-notion' ) ); ?></a>
	</div>
		<?php
	}
}
add_action( 'admin_notices', 'wpconnect_wpcf7_notion_admin_notice__info' );

/**
 * Dismiss notice for user
 *
 * @return void
 */
function wpconnect_wpcf7_notion_cf7_notice_dismissed() {
	if ( isset( $_GET['cf7_notion_addon-dismissed'] ) ) {
		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'wpc-nonce' ) ) {
			add_user_meta( get_current_user_id(), 'cf7_notion_addon_notice_dismissed', 'true', true );
		} else {
			die( esc_html( __( 'Nonce is invalid', 'add-on-cf7-for-notion' ) ) );
		}
	}
}
add_action( 'admin_init', 'wpconnect_wpcf7_notion_cf7_notice_dismissed' );
