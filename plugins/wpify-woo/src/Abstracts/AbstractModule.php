<?php

namespace WpifyWoo\Abstracts;

use WpifyWoo\Plugin;
use WpifyWoo\WooCommerceIntegration;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class AbstractModule
 *
 * @package WpifyWoo\Abstracts
 * @property Plugin $plugin
 */
abstract class AbstractModule extends AbstractComponent {
	protected $requires_activation = false;
	/**
	 * Module ID
	 *
	 * @var string $id Module ID.
	 */
	private $id = '';

	private $settings;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function __construct() {
		$this->id = $this->id();
		add_filter(
				'woocommerce_get_sections_' . WooCommerceIntegration::OPTION_NAME,
				array(
						$this,
						'add_settings_section',
				)
		);
		if ( $this->requires_activation && ! $this->is_activated() ) {
			add_action( 'admin_notices', array( $this, 'activation_notice' ) );
		}
		if ( $this->requires_activation && $this->is_activated() ) {
			add_action( 'init', array( $this, 'maybe_schedule_as_validate_action' ) );
			add_action( "wpify_woo_check_activation_{$this->id()}", array( $this, 'validate_license' ), 10, 2 );
		}
	}

	/**
	 * Module ID - use underscores
	 *
	 * @return mixed
	 */
	abstract public function id();

	public function is_activated() {
		return $this->decrypt_option_activated();
	}

	public function decrypt_option_activated() {
		$option     = $this->get_option_activated();
		$public_key = $this->get_option_public_key();
		if ( ! $option || ! $public_key ) {
			return null;
		}
		$decrypted = '';
		openssl_public_decrypt( base64_decode( $option ), $decrypted, base64_decode( $public_key ) );
		$result = json_decode( $decrypted );
		if ( ! $result ) {
			return null;
		}

		return $result;
	}

	/**
	 * @return string|null
	 */
	public function get_option_activated(): ?string {
		return get_option( $this->get_option_activated_name(), null );
	}

	public function get_option_activated_name() {
		return sprintf( 'wpify_woo_%s_activated', $this->id() );
	}

	public function get_option_public_key() {
		return get_option( $this->get_option_public_key_name() );
	}

	public function get_option_public_key_name() {
		return sprintf( 'wpify_woo_%s_public_key', $this->id() );
	}

	/**
	 * Maybe schedule the license validation AS event
	 */
	public function maybe_schedule_as_validate_action() {
		$option_activated = $this->decrypt_option_activated();
		if ( $this->id() && false === as_next_scheduled_action( "wpify_woo_check_activation_{$this->id()}" ) && $option_activated ) {
			$data              = (array) $option_activated;
			$data['slug']      = $data['plugin'];
			$data['module_id'] = $this->id();
			$data['site-url']  = defined( 'ICL_LANGUAGE_CODE' ) ? get_option( 'siteurl' ) : site_url();

			$args = array(
					'license' => $option_activated->license,
					'data'    => $data,
			);
			as_schedule_recurring_action( strtotime( 'tomorrow' ), DAY_IN_SECONDS, "wpify_woo_check_activation_{$this->id()}", $args );
		}
	}

	public function add_settings_section( $tabs ) {
		$tabs[ $this->id() ] = $this->name();

		return $tabs;
	}

	/**
	 * Module name
	 *
	 * @return mixed
	 */
	abstract public function name();

	/**
	 * Check if the module is enabled.
	 *
	 * @return bool
	 */
	public function is_module_enabled(): bool {
		return in_array( $this->get_id(), $this->plugin->get_woocommerce_integration()->get_enabled_modules(), true );
	}

	/**
	 * Get module ID
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @param $id
	 *
	 * @return mixed|null
	 */
	public function get_setting( $id, $raw = false ) {
		$setting = isset( $this->get_settings( $raw )[ $id ] ) ? $this->get_settings( $raw )[ $id ] : null;;
		$setting = apply_filters( 'wpify_woo_setting', $setting, $id, $this->id() );

		return apply_filters( "wpify_woo_setting_{$id}", $setting, $id, $this->id() );
	}

	/**
	 * Get module settings
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return get_option( $this->get_option_key(), array() );
	}

	public function get_option_key() {
		return sprintf( '%s-%s', WooCommerceIntegration::OPTION_NAME, $this->id() );
	}

	/**
	 * Module Settings
	 *
	 * @return array Settings.
	 */
	public function settings(): array {
		return array();
	}

	public function save_option_activated( $string ) {
		return update_option( $this->get_option_activated_name(), $string );
	}

	public function delete_option_activated() {
		return delete_option( $this->get_option_activated_name() );
	}

	public function save_option_public_key( $string ) {
		return update_option( $this->get_option_public_key_name(), $string );
	}

	public function save_option_license( $license ) {
		$option_key         = $this->get_option_key();
		$options            = get_option( $option_key );
		$options['license'] = $license;
		update_option( $option_key, $options );
	}

	public function delete_option_public_key() {
		return delete_option( $this->get_option_public_key_name() );
	}

	public function needs_activation() {
		return true;
		foreach ( $this->settings() as $setting ) {
			if ( ! empty( $setting['type'] ) && 'license' === $setting['type'] ) {
				return true;
			}
		}

		return false;
	}

	public function validate_license( $license, $data ) {
		$this->plugin->get_license()->validate_license( $license, $data );
	}

	/**
	 * Add activation notice if the license s not active yet.
	 */
	public function activation_notice() { ?>
		<div class="error notice">
			<p><?php printf( __( 'Your %1$s plugin licence is not activated yet. Please <a href="%2$s">enter your license key</a> to start using the plugin!', 'wpify-woo' ), $this->name(),
						admin_url( 'admin.php?page=wc-settings&tab=wpify-woo-settings&section=' . $this->get_id() ) ); ?></p>
		</div>
		<?php
	}

	public function get_settings_url() {
		return add_query_arg( [ 'section' => $this->id() ], admin_url( 'admin.php?page=wc-settings&tab=wpify-woo-settings' ) );
	}

}
