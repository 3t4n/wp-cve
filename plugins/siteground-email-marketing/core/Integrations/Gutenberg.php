<?php

namespace SG_Email_Marketing\Integrations;

use SG_Email_Marketing\Post_Types\Forms as Forms;
use SG_Email_Marketing\Renderer\Renderer;

/**
 * Form Selector Gutenberg block with live preview.
 *
 * @since 1.0.0
 */
class Gutenberg extends Integrations {

	/**
	 * The integration id.
	 *
	 * @var string
	 */
	public $id = 'gutenberg_form';

	/**
	 * The renderer instance.
	 *
	 * @var Renderer Renderer Object.
	 */
	private $renderer;

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param {Mailer_Api} $mailer_api Background service instance.
	 * @param {Renderer}   $renderer   Renderer object.
	 */
	public function __construct( $mailer_api, $renderer ) {
		parent::__construct( $mailer_api );
		$this->renderer = $renderer;
	}

	/**
	 * Get the integration data.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled' => version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ? 1 : 0,
				'labels' => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system' => 1,
				'name' => $this->id,
			)
		);

		$settings['title'] = __( 'Gutenberg', 'siteground-email-marketing' );
		$settings['description'] = __( 'You can use the SiteGround Email Marketing block found in the Widgets block section when creating content with Gutenberg.', 'siteground-email-marketing' );

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
		// Get the integration data.
		$settings = $this->fetch_settings();

		// Return the status of the integration.
		return intval( $settings['enabled'] );
	}

	/**
	 * Register SG Email Marketing Gutenberg block on the backend.
	 *
	 * @since 1.0.0
	 */
	public function register_block() {

		$attributes = array(
			'clientId'              => array(
				'type' => 'string',
			),
			'formId'                => array(
				'type' => 'string',
			),
			'formSize'              => array(
				'type' => 'string',
			),
			'formBackgroundColor'   => array(
				'type' => 'string',
			),
			'fieldBorderRadius'     => array(
				'type' => 'string',
			),
			'fieldBorderColor'      => array(
				'type' => 'string',
			),
			'fieldBackgroundColor'  => array(
				'type' => 'string',
			),
			'fieldPlaceholderColor' => array(
				'type' => 'string',
			),
			'labelColor'            => array(
				'type' => 'string',
			),
			'labelSublabelColor'    => array(
				'type' => 'string',
			),
			'fieldTextColor'        => array(
				'type' => 'string',
			),
			'labelColor'            => array(
				'type' => 'string',
			),
			'buttonBorderRadius'    => array(
				'type' => 'string',
			),
			'buttonTextColor'       => array(
				'type' => 'string',
			),
			'buttonBackgroundColor' => array(
				'type' => 'string',
			),
			'className'             => array(
				'type' => 'string',
			),
			'formAlignment'         => array(
				'type' => 'string',
			),
			'formOrientation'       => array(
				'type' => 'string',
			),
			'style'                 => array(
				'type' => 'object',
				'default' => array(),
			),
		);

		register_block_type(
			'sg-email-marketing/form-selector',
			array(
				'title'           => 'SG Email Marketing',
				'attributes'      => $attributes,
				'render_callback' => array( $this, 'get_form_content' ),
			)
		);
	}

	/**
	 * Load SG Email Marketing Gutenberg block scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_block_editor_assets() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		global $current_screen;

		if (
			! isset( $current_screen ) ||
			! method_exists( $current_screen, 'is_block_editor' ) ||
			! $current_screen->is_block_editor()
		) {
			return;
		}

		wp_enqueue_style(
			'sg-email-marketing-gutenberg-form-selector',
			\SG_Email_Marketing\URL . '/assets/css/sg-email-marketing-form.css',
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

		wp_enqueue_script(
			'sg-email-marketing-gutenberg-form-selector',
			\SG_Email_Marketing\URL . '/assets/js/sg-email-marketing-block.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			\SG_Email_Marketing\VERSION,
			true
		);

		wp_localize_script(
			'sg-email-marketing-gutenberg-form-selector',
			'sg_email_marketing_gutenberg_form_selector',
			$this->get_localize_data()
		);

		wp_localize_script(
			'sg-email-marketing-design',
			'wpData',
			array(
				'errors' => array(
					'email' => __( 'Please provide a valid email address', 'siteground-email-marketing' ),
					'default' => __( 'This field is required', 'siteground-email-marketing' ),
				),
			)
		);
	}

	/**
	 * Get localized data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_localize_data() {
		$forms             = Forms::get_all_forms();
		$localized_strings = array(
			'title'                       => esc_html__( 'SG Email Marketing', 'siteground-email-marketing' ),
			'description'                 => esc_html__( 'Select and display one of your forms.', 'siteground-email-marketing' ),
			'form_keywords'               => array(
				esc_html__( 'form', 'siteground-email-marketing' ),
				esc_html__( 'contact', 'siteground-email-marketing' ),
			),
			'form_background_color'       => esc_html__( 'Form background', 'siteground-email-marketing' ),
			'form_select'                 => esc_html__( 'Select a Form', 'siteground-email-marketing' ),
			'form_styles'                 => esc_html__( 'Form Styles', 'siteground-email-marketing' ),
			'alignment'                   => esc_html__( 'Alignment', 'siteground-email-marketing' ),
			'orientation'                 => esc_html__( 'Orientation', 'siteground-email-marketing' ),
			'field_styles'                => esc_html__( 'Field Styles', 'siteground-email-marketing' ),
			'button_styles'               => esc_html__( 'Button Styles', 'siteground-email-marketing' ),
			'advanced'                    => esc_html__( 'Advanced', 'siteground-email-marketing' ),
			'additional_css_classes'      => esc_html__( 'Additional CSS Classes', 'siteground-email-marketing' ),
			'form_selected'               => esc_html__( 'Form', 'siteground-email-marketing' ),
			'show_non_required'           => esc_html__( 'Hide Non-Required fields', 'siteground-email-marketing' ),
			'size'                        => esc_html__( 'Size', 'siteground-email-marketing' ),
			'background'                  => esc_html__( 'Background', 'siteground-email-marketing' ),
			'placeholder'                 => esc_html__( 'Placeholder', 'siteground-email-marketing' ),
			'border'                      => esc_html__( 'Border', 'siteground-email-marketing' ),
			'text'                        => esc_html__( 'Text', 'siteground-email-marketing' ),
			'border_radius'               => esc_html__( 'Border Radius', 'siteground-email-marketing' ),
			'colors'                      => esc_html__( 'Colors', 'siteground-email-marketing' ),
			'label'                       => esc_html__( 'Label', 'siteground-email-marketing' ),
			'sublabel_hints'              => esc_html__( 'Field errors', 'siteground-email-marketing' ),
			'error_message'               => esc_html__( 'Error Message', 'siteground-email-marketing' ),
			'small'                       => esc_html__( 'Small', 'siteground-email-marketing' ),
			'medium'                      => esc_html__( 'Medium', 'siteground-email-marketing' ),
			'large'                       => esc_html__( 'Large', 'siteground-email-marketing' ),
			'reset_style_settings'        => esc_html__( 'Reset Style Settings', 'siteground-email-marketing' ),
			'reset_settings_confirm_text' => esc_html__( 'Are you sure you want to reset the style settings for this form? All your current styling will be removed and canʼt be recovered.', 'siteground-email-marketing' ),
			'btn_yes_reset'               => esc_html__( 'Yes, Reset', 'siteground-email-marketing' ),
			'btn_no'                      => esc_html__( 'No', 'siteground-email-marketing' ),
		);

		return array(
			'wpnonce'             => wp_create_nonce( 'sg-email-marketing-gutenberg-form-selector' ),
			'forms'               => $forms,
			'logo_url'            => \SG_Email_Marketing\URL . '/assets/img/mailer_plugin_icon.svg',
			'defaults'            => Renderer::DEFAULT_ATTRIBUTES,
			'is_full_styling'     => 1,
			'strings'             => $localized_strings,
			'size_options'        => Renderer::SIZE_OPTIONS,
			'alignment_options'   => Renderer::ALIGNMENT_OPTIONS,
			'orientation_options' => Renderer::ORIENTATION_OPTIONS,
		);
	}

	/**
	 * Get content.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attr Attributes passed by SG Email Marketing Gutenberg block.
	 *
	 * @return string
	 */
	public function get_form_content( $attr ) {
		$form_id = ! empty( $attr['formId'] ) ? absint( $attr['formId'] ) : 0;

		if ( empty( $form_id ) ) {
			return '';
		}

		$content = $this->renderer->render( $form_id, $attr );

		if ( ! empty( $content ) ) {
			return $content;
		}

		return '<div class="components-placeholder"><div class="components-placeholder__label"></div>' .
					'<div class="components-placeholder__fieldset">' .
						esc_html__( 'The form cannot be displayed.', 'siteground-email-marketing' ) .
					'</div></div>';
	}
}
