<?php

namespace SG_Email_Marketing\Integrations;

use SiteGround_Helper\Helper_Service;
use SG_Email_Marketing\Traits\Ip_Trait;
/**
 * Abstract Integration class.
 */
abstract class Integrations {
	use Ip_Trait;

	/**
	 * The integrations prefix.
	 *
	 * @var string
	 */
	public $prefix = 'sg_mail_integration_';

	/**
	 * The integration id.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Background Process service.
	 *
	 * @var string
	 */
	protected $mailer_api;

	/**
	 * Background Process service.
	 *
	 * @var string
	 */
	protected $helper;

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param {Mailer_Api} $mailer_api Background service instance.
	 */
	public function __construct( $mailer_api ) {
		$this->mailer_api = $mailer_api;
		$this->helper = new Helper_Service();
	}

	/**
	 * Check if integration is active or inactive.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean If integration is active or inactive.
	 */
	public function is_active() {
		if ( empty( get_option( 'sg_email_marketing_token', false ) ) ) {
			return;
		}

		// Get the integration data.
		$settings = $this->fetch_settings();

		// Return the status of the integration.
		return intval( $settings['enabled'] );
	}

	/**
	 * Get the integration data.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		return get_option(
			$this->prefix . $this->id,
			array(
				'enabled' => 0,
				'title' => 'Mail Integration',
				'description' => '',
				'labels' => array(),
				'checkbox_text' => '',
				'system' => 0,
				'name' => $this->id,
			)
		);
	}

	/**
	 * Update the integration data.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $settings Array containing the integration.
	 * @return boolean
	 */
	public function update_settings( $settings ) {
		update_option( $this->prefix . $this->id, $settings );

		return $settings;
	}

	/**
	 * Prepare first and last names based on single input.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $names The names input.
	 * @return array         The modified first and last name based on the input.
	 */
	public function split_names( $names ) {
		$names = explode( ' ', $names, 2 );

		return array(
			'firstName' => $names[0],
			'lastName'  => isset( $names[1] ) ? $names[1] : '',
		);
	}

	/**
	 * Get label ids.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $labels Lables.
	 *
	 * @return array         Label ids.
	 */
	public function get_label_ids( $labels ) {
		$label_ids = array();

		foreach ( $labels['labels'] as $label ) {
			$label_ids[] = $label['id'];
		}

		return $label_ids;
	}
}
