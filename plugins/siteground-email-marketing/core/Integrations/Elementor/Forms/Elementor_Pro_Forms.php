<?php
namespace SG_Email_Marketing\Integrations\Elementor\Forms;

use SG_Email_Marketing\Integrations\Integrations;
use SG_Email_Marketing\Integrations\Elementor\Forms\Elementor_Forms_Integration;
use SG_Email_Marketing\Integrations\Elementor\Forms\SGWPMAIL_Elementor_Forms_Checkbox_Field;

/**
 * Class managing all Elementor Forms integrations.
 */
class Elementor_Pro_Forms extends Integrations {

	/**
	 * The integration id.
	 *
	 * @since 1.1.3
	 *
	 * @var string
	 */
	public $id = 'elementor_pro_forms';

	/**
	 * Fetch integration's settings
	 *
	 * @since 1.1.3
	 *
	 * @return array
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => class_exists( '\ElementorPro\Plugin' ) ? 1 : 2,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 1,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'Elementor Pro Forms', 'siteground-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to any form created with Elementor Pro, enabling users to sign up for your mailing list. Enable this integration by adding action after submit "SG Email Marketing" in Elementor Pro forms settings.', 'siteground-email-marketing' );

		return $settings;
	}

	/**
	 * Check if integration is active or inactive.
	 *
	 * @since 1.1.3
	 *
	 * @return boolean If integration is active or inactive.
	 */
	public function is_active() {
		// Bail if we do not have the elementor plugin.
		if ( class_exists( '\ElementorPro\Plugin' ) ) {
			return true;
		}

		// Get the integration data.
		$settings = $this->fetch_settings();

		// Return the status of the integration.
		return intval( $settings['enabled'] );
	}

	/**
	 * Enqueue the scripts on the frontend.
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'sg-email-marketing-elementor-forms-integration-frontend-scripts',
			\SG_Email_Marketing\URL . '/assets/js/integrations/elementor/elementor-pro-forms-frontend.js',
			array( 'elementor-frontend', 'jquery', 'wp-util' ),
			\SG_Email_Marketing\VERSION,
			true
		);
	}

	/**
	 * Load the assets for the editor.
	 *
	 * @since 1.1.3
	 *
	 * @return void
	 */
	public function enqueue_editor_styles() {
		if ( empty( $_GET['action'] ) || $_GET['action'] !== 'elementor' ) { //phpcs:ignore
			return;
		}

		wp_enqueue_style(
			'sg-email-marketing-elementor-integration-forms-styles',
			\SG_Email_Marketing\URL . '/assets/css/integrations/elementor/elementor-pro-forms.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);

	}


	/**
	 * Initialize the SGWPMAIL Elementor Pro Forms action integration.
	 *
	 * @since 1.1.3
	 *
	 * @param object $form_actions_registrar The form action's registrar object.
	 *
	 * @return void
	 */
	public function add_sgwpmail_form_action( $form_actions_registrar ) {
		$integration = new Elementor_Forms_Integration();
		$form_actions_registrar->register( $integration );
	}

	/**
	 * Initialize the SGWPMAIL Elementor Pro Forms checkbox integration.
	 *
	 * @since 1.1.3
	 *
	 * @param  object $form_fields_registrar The form fields' registrar object.
	 *
	 * @return void
	 */
	public function add_sgwpmail_form_fields( $form_fields_registrar ) {
		$form_fields_registrar->register( new SGWPMAIL_Elementor_Forms_Checkbox_Field() );
	}
}
