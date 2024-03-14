<?php
namespace SG_Email_Marketing\Integrations\Elementor;

use SG_Email_Marketing\Integrations\Integrations;
use Elementor\Plugin as ElementorPlugin;

/**
 * Class managing all Elementor Forms integrations.
 */
class Elementor_Form extends Integrations {

	/**
	 * The integration id.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $id = 'elementor_form';

	/**
	 * Fetch integration's settings
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => class_exists( '\Elementor\Plugin' ) ? 1 : 2,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 1,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'Elementor', 'siteground-email-marketing' );
		$settings['description'] = __( 'You can use the SiteGround Email Marketing component when creating content with Elementor.', 'siteground-email-marketing' );

		return $settings;
	}

	/**
	 * Check if integration is active or inactive.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean If integration is active or inactive.
	 */
	public function is_active() {
		// Bail if we do not have the elementor plugin.
		if ( class_exists( '\Elementor\Plugin' ) ) {
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
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		if ( $this->is_elementor_preview_mode() ) {
			return;
		}

		wp_enqueue_script(
			'sg-email-marketing-elementor-integration-frontend-scripts',
			\SG_Email_Marketing\URL . '/assets/js/integrations/elementor/elementor-frontend.min.js',
			array( 'elementor-frontend', 'jquery', 'wp-util' ),
			\SG_Email_Marketing\VERSION,
			true
		);

		wp_localize_script(
			'sg-email-marketing-elementor',
			'sg-email-marketingElementorVars',
			array(),
		);
	}

	/**
	 * Load the assets for the editor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_editor_styles() {
		if ( empty( $_GET['action'] ) || $_GET['action'] !== 'elementor' ) { //phpcs:ignore
			return;
		}

		wp_enqueue_style(
			'sg-email-marketing-elementor-integration-styles',
			\SG_Email_Marketing\URL . '/assets/css/integrations/elementor/elementor-preview.min.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);

		wp_enqueue_script(
			'sg-email-marketing-design',
			\SG_Email_Marketing\URL . '/assets/js/design.js',
			array( 'jquery' ),
			\SG_Email_Marketing\VERSION,
			true
		);

		wp_localize_script(
			'sg-email-marketing-design',
			'wpData',
			array(
				'errors' => array(
					'email'   => __( 'Please provide a valid email address', 'siteground-email-marketing' ),
					'default' => __( 'This field is required', 'siteground-email-marketing' ),
				),
			)
		);
	}

	/**
	 * Check if it is Elementor preview mode.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Returns true, if preview mode is enabled.
	 */
	public function is_elementor_preview_mode() {
		return ElementorPlugin::$instance->preview->is_preview_mode();
	}

	/**
	 * Integrate the form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function integrate_form() {
		ElementorPlugin::instance()->widgets_manager->register( new Widget() );
	}

	/**
	 * Prepare the ajax selector.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ajax_selector() {
		check_ajax_referer( 'sg-email-marketing-elementor', 'nonce' );
		wp_send_json_success( ( new Widget() )->get_form_selector_options() );
	}

}
