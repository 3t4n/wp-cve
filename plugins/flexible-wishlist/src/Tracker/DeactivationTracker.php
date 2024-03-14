<?php

namespace WPDesk\FlexibleWishlist\Tracker;

use FlexibleWishlistVendor\WPDesk\DeactivationModal;
use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;

/**
 * Handles the modal displayed when the plugin is deactivated.
 */
class DeactivationTracker implements Hookable {

	const PLUGIN_SLUG            = 'flexible-wishlist';
	const ACTIVATION_OPTION_NAME = 'plugin_activation_%s';

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info .
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_deactivation_modal' ] );
	}

	/**
	 * @return void
	 * @throws DeactivationModal\Exception\DuplicatedFormValueKeyException
	 * @internal
	 */
	public function load_deactivation_modal() {
		new DeactivationModal\Modal(
			self::PLUGIN_SLUG,
			( new DeactivationModal\Model\FormTemplate( $this->plugin_info->get_plugin_name() ) ),
			( new DeactivationModal\Model\DefaultFormOptions() ),
			( new DeactivationModal\Model\FormValues() )
				->set_value(
					new DeactivationModal\Model\FormValue(
						'is_localhost',
						[ $this, 'is_localhost' ]
					)
				)
				->set_value(
					new DeactivationModal\Model\FormValue(
						'plugin_using_time',
						[ $this, 'get_time_of_plugin_using' ]
					)
				)
				->set_value(
					new DeactivationModal\Model\FormValue(
						'settings_saved',
						[ $this, 'check_if_plugin_settings_saved' ]
					)
				),
			new DeactivationModal\Sender\DataWpdeskSender(
				$this->plugin_info->get_plugin_file_name(),
				$this->plugin_info->get_plugin_name()
			)
		);
	}

	/**
	 * @internal
	 */
	public function is_localhost(): bool {
		return ( in_array( $_SERVER['REMOTE_ADDR'] ?? '', [ '127.0.0.1', '::1' ], true ) );
	}

	/**
	 * @return int|null
	 * @internal
	 */
	public function get_time_of_plugin_using() {
		$option_activation = sprintf( self::ACTIVATION_OPTION_NAME, $this->plugin_info->get_plugin_file_name() );
		$activation_date   = get_option( $option_activation, null );
		if ( $activation_date === null ) {
			return null;
		}

		$current_date = current_time( 'mysql' );
		return ( strtotime( (string) $current_date ) - strtotime( $activation_date ) );
	}

	/**
	 * @internal
	 */
	public function check_if_plugin_settings_saved(): bool {
		$settings = get_option( SettingsRepository::PLUGIN_SETTINGS_OPTION_NAME, null );
		return ( $settings !== null );
	}
}
