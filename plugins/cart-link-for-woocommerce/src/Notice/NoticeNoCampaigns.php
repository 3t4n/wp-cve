<?php

namespace IC\Plugin\CartLinkWooCommerce\Notice;

use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;
use IC\Plugin\CartLinkWooCommerce\PluginData;

class NoticeNoCampaigns {
	public const OPTION_NAME = 'cart_link_campaign_created';

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data .
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'admin_notices', [ $this, 'display_notice' ] );
		add_action( 'ic_notice_dismiss/' . self::OPTION_NAME, [ $this, 'notice_dismiss' ] );
	}

	/**
	 * @return void
	 */
	public function notice_dismiss(): void {
		add_option( self::OPTION_NAME, true );
	}

	/**
	 * @return void
	 */
	public function display_notice(): void {
		if ( ! $this->should_display_notice() ) {
			return;
		}

		$url         = $this->get_url();
		$plugin_name = $this->plugin_data->get_plugin_name();
		$dismiss_url = $this->get_dismiss_action_url();

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-notice-no-campaigns.php' );
	}

	/**
	 * @return string
	 */
	private function get_dismiss_action_url(): string {
		$args = [
			'action'                     => NoticeAction::DISMISS_NOTICE_ACTION,
			NoticeAction::DISMISS_ACTION => self::OPTION_NAME,
		];

		$url = add_query_arg( $args, admin_url( 'admin-post.php' ) );

		return wp_nonce_url( $url, NoticeAction::NONCE_ACTION );
	}

	/**
	 * @return bool
	 */
	private function should_display_notice(): bool {
		return ! (bool) get_option( self::OPTION_NAME, false );
	}

	/**
	 * @return string
	 */
	private function get_url(): string {
		return admin_url( 'post-new.php?post_type=' . RegisterPostType::POST_TYPE );
	}
}
