<?php

namespace WPDesk\FlexibleWishlist\Notice;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;

/**
 * Notice about new extension - Flexible Wishlist - Analytics & Emails.
 */
class AnalyticsExtensionNotice implements Notice {

	const ACTIVATION_OPTION_NAME = 'plugin_activation_%s';
	const NOTICE_OPTION_NAME     = 'notice_flexible_wishlist_analytics_%s';
	const NOTICE_NAME            = 'notice_flexible_wishlist_analytics';

	/**
	 * @var AbstractPlugin
	 */
	private $plugin;

	public function __construct( AbstractPlugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_notice_name(): string {
		return self::NOTICE_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_active(): bool {
		if ( basename( $_SERVER['PHP_SELF'] ?? '' ) !== 'index.php' ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			return false;
		}

		$option_notice = sprintf( self::NOTICE_OPTION_NAME, $this->plugin->get_plugin_file_path() );
		$notice_date   = strtotime( get_option( $option_notice, false ) );
		$min_date      = strtotime( current_time( 'mysql' ) );

		if ( ( $notice_date !== false ) && ( $notice_date > $min_date ) ) {
			return false;
		}

		$option_activation = sprintf( self::ACTIVATION_OPTION_NAME, $this->plugin->get_plugin_file_path() );
		$activation_date   = strtotime( get_option( $option_activation, current_time( 'mysql' ) ) );
		$min_date          = strtotime( current_time( 'mysql' ) . ' -14 days' );

		if ( $activation_date > $min_date ) {
			return false;
		}

		return ( ! is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_path(): string {
		return 'notices/analytics-extension';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_vars_for_view(): array {
		return [
			'image_url'   => untrailingslashit( $this->plugin->get_plugin_assets_url() ) . '/img/flexible-wishlist.png',
			'install_url' => wp_nonce_url(
				add_query_arg(
					[
						'action' => 'install-plugin',
						'plugin' => 'flexible-wishlist',
					],
					admin_url( 'update.php' )
				),
				'install-plugin_flexible-wishlist'
			),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_notice_as_hidden( bool $is_permanently ) {
		$option_name = sprintf( self::NOTICE_OPTION_NAME, $this->plugin->get_plugin_file_path() );
		$notice_time = strtotime( current_time( 'mysql' ) . ( ( $is_permanently ) ? ' +10 years' : ' +1 month' ) );
		$notice_date = gmdate( 'Y-m-d H:i:s', $notice_time );

		update_option( $option_name, $notice_date, true );
	}
}
