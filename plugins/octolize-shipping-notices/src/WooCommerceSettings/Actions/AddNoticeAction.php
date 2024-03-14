<?php
/**
 * Assets.
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Actions;

use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\WooCommerceSettings\SettingsActionLinks;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class AddNoticeAction implements Hookable {

	/**
	 * @var SettingsActionLinks
	 */
	private $settings_action_links;

	/**
	 * @param SettingsActionLinks $settings_action_links
	 */
	public function __construct( SettingsActionLinks $settings_action_links ) {
		$this->settings_action_links = $settings_action_links;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'admin_init', [ $this, 'add_notice' ] );
	}

	/**
	 * @return void
	 */
	public function add_notice(): void {
		if ( ! isset( $_GET[ SettingsActionLinks::NOTICE_ADD_NEW ] ) ) {
			return;
		}

		check_admin_referer( SettingsActionLinks::NONCE_ACTION, SettingsActionLinks::NOTICE_ADD_NEW );

		$notice_id = wp_insert_post(
			[
				'post_type' => CustomPostType::POST_TYPE,
			],
			true
		);

		if ( is_wp_error( $notice_id ) ) {
			wp_die( wp_kses_post( $notice_id->get_error_message() ) );
		} else {
			wp_safe_redirect( $this->settings_action_links->get_edit_notice_url( $notice_id ) );
			$this->end_request();
		}
	}

	/**
	 * @codeCoverageIgnore
	 */
	protected function end_request(): void {
		die();
	}
}
