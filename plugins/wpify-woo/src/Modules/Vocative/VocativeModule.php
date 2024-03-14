<?php

namespace WpifyWoo\Modules\Vocative;

use WpifyWoo\Abstracts\AbstractModule;
use WpifyWooDeps\Inflection;

class VocativeModule extends AbstractModule {
	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_filter( 'woocommerce_mail_callback_params', array( $this, 'change_name_to_vocative' ), 20, 2 );
	}

	/**
	 * Module ID
	 * @return string
	 */
	public function id(): string {
		return 'vocative';
	}

	/**
	 * Module settings
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
			array(
				'id'    => 'vocative_title',
				'type'  => 'title',
				'label' => __( 'Vocative in Woo Emails', 'wpify-woo' ),
				'desc'  => __( 'Automatically changes the salutation in Woo emails to vocative.', 'wpify-woo' ),
			),
			array(
				'id'    => 'replace_first_name',
				'type'  => 'text',
				'label' => __( 'Replace first name', 'wpify-woo' ),
				'desc'  => sprintf( __( 'By default, WooCommerce uses first name only in the emails. You can set here to replace the "Hi {first_name}" text. Use {first_name}, {last_name} and {full_name} tags, ie set "Hi {full_name}" as the value.',
					'wpify-woo' ) ),
			),
		);

		return $settings;
	}

	/**
	 * Module name
	 * @return string
	 */
	public function name(): string {
		return __( 'Emails Vocative', 'wpify-woo' );
	}

	/**
	 * Change name to vocative
	 *
	 * @param $params
	 * @param $email
	 *
	 * @return mixed
	 */
	public function change_name_to_vocative( $params, $email ) {
		if ( ! is_a( $email->object, '\Automattic\WooCommerce\Admin\Overrides\Order' ) ) {
			return $params;
		}

		$first_name              = $email->object->get_billing_first_name();
		$original_text           = sprintf( __( 'Hi %s,', 'woocommerce' ), $first_name );
		$inflection              = new Inflection();
		$to_inflect              = $first_name;
		$replace_first_name_text = $this->get_setting( 'replace_first_name' );

		if ( $replace_first_name_text ) {
			$replaces = [
				'{first_name}' => $this->get_vocative( $inflection, $email->object->get_billing_first_name() ),
				'{last_name}'  => $this->get_vocative( $inflection, $email->object->get_billing_last_name() ),
				'{full_name}'  => $this->get_vocative( $inflection, $email->object->get_formatted_billing_full_name() ),
			];
			$text     = str_replace( array_keys( $replaces ), array_values( $replaces ), $replace_first_name_text );
		} else {
			$inflected = $this->get_vocative( $inflection, $to_inflect );
			$text      = str_replace( $first_name, $inflected, $original_text );
		}

		$params[2] = str_replace( $original_text, $text, $params[2] );

		return $params;
	}

	public function get_vocative( Inflection $inflection, $name ) {
		// Exceptions
		if ( preg_match( "/nis$/", $name ) ) {
			// Yannis/Janis
			return preg_replace( "/nis$/", "nisi", $name );
		}

		$inflected = $inflection->inflect( $name );

		return $inflected[5];
	}
}
