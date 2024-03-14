<?php
/**
 * Assets.
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings\Actions;

use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\WooCommerceSettings\SettingsActionLinks;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WC_Admin_Settings;

/**
 * .
 */
class DeleteNoticeAction implements Hookable {

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
		add_action( 'admin_init', [ $this, 'handle_action' ] );
		add_action( 'admin_init', [ $this, 'add_message' ] );
	}

	/**
	 * @return void
	 */
	public function handle_action(): void {
		if ( ! isset( $_GET[ SettingsActionLinks::NOTICE_DELETE ], $_GET[ SettingsActionLinks::NOTICE_ID ] ) ) {
			return;
		}

		check_admin_referer( SettingsActionLinks::NONCE_ACTION, SettingsActionLinks::NOTICE_DELETE );

		// @phpstan-ignore-next-line
		$notice_id = (int) $_GET[ SettingsActionLinks::NOTICE_ID ];

		if ( get_post_type( $notice_id ) !== CustomPostType::POST_TYPE ) {
			wp_die( wp_kses_post( __( 'You can\'t delete the notice because it wasn\'t found.', 'octolize-shipping-notices' ) ) );
		} else {
			wp_delete_post( $notice_id, true );

			// todo: delete option.
			wp_safe_redirect( $this->settings_action_links->get_deleted_notice_url() );

			$this->end_request();
		}
	}

	/**
	 * .
	 */
	public function add_message(): void {
		if ( isset( $_GET[ SettingsActionLinks::NOTICE_DELETED ] ) ) {
			$this->add_wc_settings_message( __( 'Shipping Notice deleted.', 'octolize-shipping-notices' ) );
		}
	}

	/**
	 * @param string $message_text .
	 *
	 * @codeCoverageIgnore
	 */
	protected function add_wc_settings_message( string $message_text ): void {
		WC_Admin_Settings::add_message( $message_text );
	}

	/**
	 * @codeCoverageIgnore
	 */
	protected function end_request(): void {
		die();
	}
}
