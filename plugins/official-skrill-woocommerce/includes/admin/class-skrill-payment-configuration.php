<?php
/**
 * Skrill Plugin Installation Process
 *
 * This class is used for Payment Methods Configuration
 * Copyright (c) Skrill
 *
 * @class   Skrill_Payment_Configuration
 * @package Skrill/Classes
 * @located at  /includes/admin/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill_Payment_Configuration
 */
class Skrill_Payment_Configuration {


	/**
	 * Render payment configuration at backend
	 *
	 * @param  string $payment_method_id          - payment method id.
	 * @param  string $payment_method_description - payment method description.
	 * @return array
	 */
	public static function render_payment_configuration( $payment_method_id, $payment_method_description = '' ) {
		$form_fields = array(
			'enabled' => array(
				'title'       => __( 'Enabled', 'wc-skrill' ),
				'type'        => 'checkbox',
				'default'     => 'yes',
				'description' => $payment_method_description,
			),
		);

		if ( 'skrill_acc' === $payment_method_id ) {
			$form_fields['show_separately'] = array(
				'title'   => __( 'Show separately', 'wc-skrill' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			);
		}

		return $form_fields;
	}
}
