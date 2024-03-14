<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Components\Persistence\JsonSerializedOptionsContainer;
use WPDesk\ShopMagic\Components\Persistence\WrappingPersistentContainer;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\Tracker\TrackerNotices;

/**
 * ShopMagic settings tab - form with fields to be stored in database.
 */
final class GeneralSettings extends FieldSettingsTab {
	/**
	 * @var string
	 */
	public const OUTCOMES_PURGE = 'enable_outcomes_purge';

	public static function get_tab_slug(): string {
		return 'general';
	}

	/** @todo Remove wrapper container in 4.0 */
	public static function get_settings_persistence(): PersistentContainer {
		$wrapping_container = new WrappingPersistentContainer(
			new JsonSerializedOptionsContainer('shopmagic_general_settings')
		);
		$wrapping_container->wrapContainer(new WordpressOptionsContainer());

		return $wrapping_container;
	}

	public function get_tab_name(): string {
		return __( 'General', 'shopmagic-for-woocommerce' );
	}

	/** @return \ShopMagicVendor\WPDesk\Forms\Field[] */
	public function get_fields(): array {
		return [
			( new CheckboxField() )
				->set_label( __( 'Usage Data', 'shopmagic-for-woocommerce' ) )
				->set_sublabel( __( 'Enable', 'shopmagic-for-woocommerce' ) )
				->set_description(
					__( 'Help us improve ShopMagic and allow us to collect insensitive plugin usage data', 'shopmagic-for-woocommerce' ) . ', ' .
					'<a href="' . TrackerNotices::USAGE_DATA_URL . '" target="_blank">' .
						__( 'read more', 'shopmagic-for-woocommerce' ) .
					'</a>.'
				)
				->set_name( 'wpdesk_tracker_agree' ),

			( new CheckboxField() )
				->set_label( __( 'Enable session tracking', 'shopmagic-for-woocommerce' ) )
				->set_default_value( 'yes' )
				->set_description( __( 'Session tracking uses cookies to remember users when they are not signed in. This means carts can be tracked when the user is signed out. ', 'shopmagic-for-woocommerce' ) )
				->set_name( 'enable_session_tracking' ),

			( new CheckboxField() )
				->set_label( __( 'Enable email tracking', 'shopmagic-for-woocommerce' ) )
				->set_default_value( '1' )
				->set_description( __( 'Track your emails performance by injecting tracking pixel in your messages and be aware of each clicked link.', 'shopmagic-for-woocommerce' ) )
				->set_name( 'enable_email_tracking' ),

			( new CheckboxField() )
				->set_label( __( 'Enable pre-submit data capture ', 'shopmagic-for-woocommerce' ) )
				->set_description( __( 'Capture guest customer data before forms are submitted e.g. during checkout. ', 'shopmagic-for-woocommerce' ) )
				->set_name( 'enable_pre_submit' ),

			( new CheckboxField() )
				->set_label( esc_html__( 'Enable Outcomes clear', 'shopmagic-for-woocommerce' ) )
				->set_description( esc_html__( 'Automatically clear Outcomes after 30 days.', 'shopmagic-for-woocommerce' ) )
				->set_name( self::OUTCOMES_PURGE ),

			( new InputTextField() )
				->set_label( __( '"From" name', 'shopmagic-for-woocommerce' ) )
				->set_name( 'shopmagic_email_from_name' ),

			( new InputTextField() )
				->set_label( __( '"From" email', 'shopmagic-for-woocommerce' ) )
				->set_name( 'shopmagic_email_from_address' ),
			( new CheckboxField() )
				->set_label( __( 'Enable compatibility mode', 'shopmagic-for-woocommerce' ) )
				->set_description(
					__(
						'Enables compatibility mode for the plugin to ensure server requests are working as expected.',
						'shopmagic-for-woocommerce'
					)
				)
				->set_name( 'request_compatibility_mode' ),
			( new CheckboxField() )
				->set_label( __( 'Enable logging', 'shopmagic-for-woocommerce' ) )
				->set_description(
					__(
						'Enables logger which traces ShopMagic actions and helps with debugging.',
						'shopmagic-for-woocommerce'
					)
				)
				->set_name( 'sm_enable_logs' ),
		];
	}
}
