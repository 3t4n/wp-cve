<?php
namespace SG_Email_Marketing\Renderer;

use SG_Email_Marketing\Integrations\Gutenberg;
/**
 * Renderer class.
 */
class Renderer {

	public static $instance;
	/**
	 * Default attributes.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	const DEFAULT_ATTRIBUTES = array(
		'formId'                => '',
		'formSize'              => 'medium',
		'formBackgroundColor'   => '#ffffff',
		'formAlignment'         => 'center',
		'formOrientation'       => 'column',
		'fieldBorderRadius'     => '3px',
		'fieldBackgroundColor'  => '#ffffff',
		'fieldBorderColor'      => 'rgba( 0, 0, 0, 0.25 )',
		'fieldTextColor'        => '#000000',
		'fieldPlaceholderColor' => 'rgba( 0, 0, 0, 0.60 )',
		'labelColor'            => 'rgba( 0, 0, 0, 0.85 )',
		'buttonBorderRadius'    => '3px',
		'buttonBackgroundColor' => '#066aab',
		'buttonTextColor'       => '#ffffff',
		'labelSublabelColor'    => 'rgb(212, 17, 72)',
	);

	const SIZE_OPTIONS = array(
		'small'  => array(
			// Input.
			'field-input-height'     => '40px',
			'field-input-spacing'    => '20px',
			'field-input-font-size'  => '16px',
			'field-padding-v'        => '9px',
			'field-padding-h'        => '20px',

			// Label.
			'field-font-size-label'  => '12px',
			'field-sublabel-spacing' => '5px',

			// General.
			'field-line-height'      => '18px',
			'field-checkbox-size'    => '14px',
			'field-icon-size'        => '0.75',

			'button-padding-h'       => '16px',
			'button-height'          => '40px',
			'button-font-size'       => '14px',
		),
		'medium' => array(
			// Input.
			'field-input-height'     => '48px',
			'field-input-spacing'    => '20px',
			'field-input-font-size'  => '16px',
			'field-padding-v'        => '13px',
			'field-padding-h'        => '20px',

			// Label.
			'field-font-size-label'  => '12px',
			'field-sublabel-spacing' => '5px',

			// General.
			'field-line-height'      => '22px',
			'field-checkbox-size'    => '14px',
			'field-icon-size'        => '0.75',
			'button-padding-h'       => '24px',
			'button-height'          => '48px',
			'button-font-size'       => '16px',
		),
		'large'  => array(
			// Input.
			'field-input-height'     => '56px',
			'field-input-spacing'    => '20px',
			'field-input-font-size'  => '18px',
			'field-padding-v'        => '16px',
			'field-padding-h'        => '30px',

			// Label.
			'field-font-size-label'  => '12px',
			'field-sublabel-spacing' => '5px',

			// General.
			'field-line-height'      => '27px',
			'field-checkbox-size'    => '14px',
			'field-icon-size'        => '0.75',

			'button-padding-h'       => '32px',
			'button-height'          => '56px',
			'button-font-size'       => '16px',
		),
	);

	const ALIGNMENT_OPTIONS = array(
		'right' => array(
			'align-items' => 'end',
		),
		'left' => array(
			'align-items' => 'start',
		),
		'center' => array(
			'align-items' => 'center',
		),
	);

	const ORIENTATION_OPTIONS = array(
		'column' => array(
			'flex-direction' => 'column',
		),
		'row' => array(
			'flex-direction' => 'row',
		),
	);

	/**
	 * The constructor.
	 */
	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Get the singleton instance.
	 *
	 * @since 1.0.0
	 *
	 * @return  The singleton instance.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Render form.
	 *
	 * @since 1.0.0
	 *
	 * @param  int   $form_id Form id.
	 * @param  array $attr    Form attributes.
	 *
	 * @return string         Markup, used for the form.
	 */
	public function render( $form_id, $attr ) {
		$form         = get_post( $form_id );
		$fields       = json_decode( $form->post_content );
		$attr['hash'] = bin2hex( random_bytes( 18 ) );
		$html         = self::render_css( $attr );
		$orientation  = isset( $attr['formOrientation'] ) ? 'sg-marketing-form-container-' . $attr['formOrientation'] : 'sg-marketing-form-container-column';

		$html .= '<form class="sg-marketing-form sg-email-marketing-form-' . $form_id . '-' . $attr['hash'] . '">';
		$html .= \wp_nonce_field( 'sg-email-marketing-form', '_wpnonce', true, false );
		$html .= '<div class="sg-email-marketing-form-' . $form_id . '-' . $attr['hash'] . ' sg-marketing-form-submit_message sg-marketing-form-submit_message--hidden sg-marketing-form-submit_message--success">' . $fields->settings->success_message . '</div>';
		$html .= '<div class="sg-email-marketing-form-' . $form_id . '-' . $attr['hash'] . ' sg-marketing-form-submit_message sg-marketing-form-submit_message--hidden sg-marketing-form-submit_message--error">' . __( 'There was an issue submitting the form!', 'siteground-email-marketing' ) . '</div>';
		$html .= '<fieldset id="sg-email-marketing-' . esc_attr( $form_id ) . '" class="sg-marketing-form-container sg-email-marketing-form-' . $form_id . '-' . $attr['hash'] . ' ' . esc_attr( $orientation ) . '">';

		// Check if $fields->title is set and is an array.
		if ( isset( $fields->title ) && is_array( $fields->title ) ) {
			foreach ( $fields->title as $title_field ) {

				$required = ! empty( $title_field->required ) ? 'required' : '';

				if ( empty( $required ) ) {
					continue;
				}

				$type_class = 'sg-marketing-form-' . esc_attr( $title_field->{'sg-form-type'} );
				$html      .= '<div class="sg-marketing-form-title-and-description-fields">';

				if ( $title_field->{'sg-form-type'} === 'title' ) {
					$html .= '<h2 class="' . $type_class . '">' . esc_html( $title_field->placeholder ) . '</h2>';
				} else {
					$html .= '<p class="' . $type_class . '">' . esc_html( $title_field->placeholder ) . '</p>';
				}

				$html .= '</div>';
			}
		}

		// Define the order of the fields.
		$field_order = array( 'first-name', 'last-name', 'email' );

		foreach ( $field_order as $field_name ) {
			foreach ( $fields->fields as $field ) {
				if ( $field->{'sg-form-type'} === $field_name ) {
					$required = ! empty( $field->required ) ? 'required' : '';

					if ( empty( $required ) ) {
						continue;
					}

					if ( 'button' === $field->type ) {
						continue;
					}

					$html .= '<div class="sg-input-container">';
					if ( ! empty( $field->label ) ) {
						$html .= '<label for="input-' . esc_attr( $field->id ) . $attr['hash'] . '"> ' . $field->label;
							$html .= ( $required ? ' <span class="sg-marketing-form-required-label" aria-hidden="true">*</span>' : '' );
						$html .= '</label>';
					}

					$html .= '<input id="input-' . esc_attr( $field->id ) . $attr['hash'] . '" type="' . esc_attr( $field->type ) . '" name="' . esc_attr( $field->{'sg-form-type'} ) . '" placeholder="' . esc_attr( $field->placeholder ) . '"' . esc_attr( $required ) . '>
					<span class="sg-marketing-form-sublabel"></span>
					</div>';
				}
			}
		}

		$html .= '<input name="form-id" type="hidden" value="' . esc_attr( $form_id ) . '">';
		$html .= '<input name="spam-protection" type="hidden">';

		$html .= '<button type="submit">' . esc_html( $fields->settings->submit_text ) . '</button>';
		$html .= '</fieldset></form>';

		return $html;
	}


	/**
	 * Renders css for Gutenberg block.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $attr The attributes list for the block.
	 *
	 * @return string       Style tag, used for the CSS of the form.
	 */
	public function render_css( $attr ) {
		$form_id   = ! empty( $attr['formId'] ) ? absint( $attr['formId'] ) : 0;

		return sprintf(
			'<style id="sg-email-marketing-css-vars-' . $form_id . '">.sg-email-marketing-form-' . $form_id . '-' . $attr['hash'] . ' { %s }</style>',
			esc_html( $this->get_css_vars( $attr ) )
		);
	}

	/**
	 * Get CSS vars from the customized Gutenberg block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attr Attributes passed by SG Email Marketing block.
	 *
	 * @return array
	 */
	public function get_css_vars( $attr ) {
		$attributes = array_merge( self::DEFAULT_ATTRIBUTES, $attr );

		if ( ! empty( $attributes['formSize'] ) ) {
			$attributes = array_merge( $this->prefix_vars( self::SIZE_OPTIONS[ $attributes['formSize'] ], 'form-size-' ), $attributes );
		}

		if ( ! empty( $attributes['formAlignment'] ) ) {
			$attributes = array_merge( self::ALIGNMENT_OPTIONS[ $attributes['formAlignment'] ], $attributes );
		}

		$css_vars = array();

		if ( isset( $attributes['style']['spacing']['padding'] ) ) {
			$padding = \wp_style_engine_get_styles( $attributes['style'] );
			if ( ! empty( $padding['declarations'] ) ) {
				if (
					isset( $padding['declarations']['padding-top'] ) ||
					isset( $padding['declarations']['padding-bottom'] ) ||
					isset( $padding['declarations']['padding-left'] ) ||
					isset( $padding['declarations']['padding-right'] )
				) {
					$padding['declarations']['padding-top']    = $padding['declarations']['padding-top'] ?? 0;
					$padding['declarations']['padding-bottom'] = $padding['declarations']['padding-bottom'] ?? 0;
					$padding['declarations']['padding-left']   = $padding['declarations']['padding-left'] ?? 0;
					$padding['declarations']['padding-right']  = $padding['declarations']['padding-right'] ?? 0;
				}
				$css_vars['form-padding'] = "{$padding['declarations']['padding-top']} {$padding['declarations']['padding-right']} {$padding['declarations']['padding-bottom']} {$padding['declarations']['padding-left']}";
			}
		}

		if ( isset( $attributes['style']['spacing']['margin'] ) ) {
			$margin = \wp_style_engine_get_styles( $attributes['style'] );
			if ( ! empty( $margin['declarations'] ) ) {
				if (
					isset( $margin['declarations']['margin-top'] ) ||
					isset( $margin['declarations']['margin-bottom'] ) ||
					isset( $margin['declarations']['margin-left'] ) ||
					isset( $margin['declarations']['margin-right'] )
				) {
					$margin['declarations']['margin-top']    = $margin['declarations']['margin-top'] ?? 0;
					$margin['declarations']['margin-bottom'] = $margin['declarations']['margin-bottom'] ?? 0;
					$margin['declarations']['margin-left']   = $margin['declarations']['margin-left'] ?? 0;
					$margin['declarations']['margin-right']  = $margin['declarations']['margin-right'] ?? 0;
				}
				$css_vars['form-margin'] = "{$margin['declarations']['margin-top']} {$margin['declarations']['margin-right']} {$margin['declarations']['margin-bottom']} {$margin['declarations']['margin-left']}";
			}
		}
		$attr_to_skip = array( 'clientId', 'formId', 'style' );

		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, $attr_to_skip ) ) {
				continue;
			}

			$var_name = strtolower( preg_replace( '/[A-Z]/', '-$0', $key ) );
			$css_vars[ $var_name ] = $value;
		}

		return $this->export_css_vars( $css_vars );
	}


	private function prefix_vars( $data, $prefix ) {
		$new = array();
		foreach ( $data as $key => $value ) {
			$new[ $prefix . $key ] = $value;
		}
		return $new;
	}

	/**
	 * Export CSS variables from a list of variables.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $vars Variables list.
	 *
	 * @return string      String with the css variables listed with their corresponding values.
	 */
	private function export_css_vars( $vars ) {
		$result = '';

		foreach ( $vars as $name => $value ) {
			if ( is_array( $value ) ) {
				continue;
			}
			$result .= "--sg-email-marketing-form-{$name}: {$value};\n";
		}

		return $result;
	}

	/**
	 * Load SG Email Marketing Gutenberg form styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_form_styling() {
		wp_enqueue_style(
			'sg-email-marketing-gutenberg-form-selector',
			\SG_Email_Marketing\URL . '/assets/css/sg-email-marketing-form.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);
	}

	/**
	 * Register the forms short-code.
	 *
	 * @param  array $atts Array with all attributes.
	 */
		public function register_sgform_shortcode( $atts ) {
		if ( empty( $atts['id'] ) ) {
			return;
		}

		return $this->render( $atts['id'], array( 'formId' => $atts['id'] ) );
	}
}
