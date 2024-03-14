<?php
/**
 * Admin Submenu of the Plugin
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
 */

/**
 * Admin Submenu of the Plugin
 */
class WP_Tabs_Admin_Menu {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Add plugin action menu
	 *
	 * @param array $links The action link.
	 * @param array $file The file link.
	 *
	 * @return array
	 */
	public function sptpro_plugin_action_links( $links, $file ) {

		if ( WP_TABS_BASENAME === $file ) {

			$new_links = array(
				sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=sp_wp_tabs' ), __( 'Add Tabs', 'wp-expand-tabs-free' ) ),
			);
			$links[]   = '<a href="https://wptabs.com/pricing/?ref=1" style="color: #35b747; font-weight: 700;">' . __( 'Go Pro!', 'wp-expand-tabs-free' ) . '</a>';

			return array_merge( $new_links, $links );
		}

		return $links;
	}

	/**
	 * Bottom review notice.
	 *
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sptpro_review_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_tabs' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/wp-expand-tabs-free/reviews/?filter=5#new-post';
			$text = sprintf( wp_kses_post( 'Enjoying <strong>WP Tabs?</strong> Please rate us <span class="sptabs-footer-text-star">★★★★★</span> <a href="%s" target="_blank">WordPress.org</a>. Your positive feedback will help us grow more. Thank you! 😊', 'wp-expand-tabs-free' ), esc_url( $url ) );
		}

		return $text;
	}

	/**
	 * Bottom version notice.
	 *
	 * @param string $text Version notice.
	 * @return string
	 */
	public function sptpro_version_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_tabs' === $screen->post_type ) {
			$text = 'WP Tabs ' . $this->version;
		}
		return $text;
	}

}
