<?php
/**
 * @package Woo Disable Email Notification
 */
namespace Woo_Disable_Email_Notification;

class SettingsLink {

	const SUPPORT_LINK = 'https://brightplugins.com/support/';

	public function __construct() {
		$this->register();
	}

	/**
	 * Registers the plugin links of options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register() {

		add_filter( "plugin_action_links_" . BRIGHT_WDEN_FULL_NAME, [$this, 'add_setting_links'] );
		add_filter( 'plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2 );
	}

	/**
	 * Added the links established in the function
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param  array $links links already established by the core wordpress
	 * @return array $links new setting the links established
	 */
	public function add_setting_links( $links ) {

		$link              = get_admin_url( null, 'admin.php?page=' . Bootstrap::PREFIX );
		$links['settings'] = '<a href="' . $link . '">' . esc_html__( 'Settings', '' ) . '</a>';

		return $links;
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {

		if ( BRIGHT_WDEN_FULL_NAME === $file ) {

			$settings_link = "<a style='color: red;' target='_blank' href='" . self::SUPPORT_LINK . "'>Support</a>";
			array_push( $links, $settings_link );
		}

		return (array) $links;
	}

}