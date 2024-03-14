<?php
namespace SG_Email_Marketing\Integrations\WooCommerce;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class Woo_Checkout_Block_Integration implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @version 1.1.4
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sg-email-marketing-woo-checkbox';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 *
	 * @version 1.1.4
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_block_frontend_scripts();
		$this->register_block_editor_scripts();
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @version 1.1.4
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'sg-email-marketing-woo-checkbox-frontend' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @version 1.1.4
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'sg-email-marketing-woo-checkbox-editor' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @version 1.1.4
	 *
	 * @return array
	 */
	public function get_script_data() {
		return array();
	}

	/**
	 * Register scripts for delivery date block editor.
	 *
	 * @version 1.1.4
	 *
	 * @return void
	 */
	public function register_block_editor_scripts() {
		wp_register_script(
			'sg-email-marketing-woo-checkbox-editor',
			\SG_Email_Marketing\URL . '/assets/js/sg-email-marketing-woo-block.js',
			array( 'wp-blocks', 'wp-components', 'wp-element' ),
			\SG_Email_Marketing\VERSION,
			true
		);
	}

	/**
	 * Register scripts for frontend block.
	 *
	 * @version 1.1.4
	 *
	 * @return void
	 */
	public function register_block_frontend_scripts() {
		wp_register_script(
			'sg-email-marketing-woo-checkbox-frontend',
			\SG_Email_Marketing\URL . '/assets/js/sg-email-marketing-woo-block-frontend.js',
			array( 'wc-blocks-checkout', 'wp-components', 'wc-blocks-components', 'wp-element', 'wp-i18n' ),
			\SG_Email_Marketing\VERSION,
			true
		);

		// Pass the label to the script
		wp_localize_script(
			'sg-email-marketing-woo-checkbox-frontend',
			'sgEmailMarketingWooBlockFrontend',
			array(
				'checkboxLabel' => apply_filters( 'sg_email_marketing_woo_checkbox_label', __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ) ),
			)
		);

		// Enqueue the script
		wp_enqueue_script( 'sg-email-marketing-woo-checkbox-frontend' );
	}
}
