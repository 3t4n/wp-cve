<?php

namespace SG_Email_Marketing\Integrations\ThirdParty\WPForms;

use SG_Email_Marketing\Loader\Loader;
/**
 * SGWPMAIL Field for WPForms.
 *
 * @since 1.1.4.
 */
class SGWPMAIL_WPForms_Field extends \WPForms_Field {

	/**
	 * The Checkbox Label.
	 *
	 * @since 1.1.4
	 *
	 * @var string
	 */
	public $label;


	/**
	 * Constructor
	 *
	 * @since 1.1.4
	 *
	 * @param string $label The label for the checkbox.
	 */
	public function __construct( $label ) {
		$this->label = $label;

		parent::__construct();
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.4
	 */
	public function init() {
		$this->name     = 'SG Email Marketing';
		$this->type     = 'sg_email_marketing';
		$this->icon     = 'fa-envelope-o';
		$this->order    = 21;
		$this->defaults = array(
			array(
				'label'   => $this->label,
				'value'   => '1',
				'default' => '',
			),
		);
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.1.4
	 *
	 * @param array $field The field that we want to change.
	 */
	public function field_options( $field ) {
		// Options open markup.
		$this->field_option( 'basic-options', $field, array( 'markup' => 'open' ) );

		$fld  = $this->field_element(
			'label',
			$field,
			array(
				'class'   => 'sg_email_marketing_groups_label',
				'slug'    => 'sg_email_marketing_groups',
				'value'   => __( 'Groups', 'siteground-email-marketing' ),
				'tooltip' => __( 'Eligible users submitting this form will be added to the selected groups', 'siteground-email-marketing' ),
			),
			false
		);

		$fld .= $this->field_element_select2(
			$field,
			array(
				'slug'     => 'sg_email_marketing_groups',
				'desc'     => __( 'Groups', 'siteground-email-marketing' ),
				'options'  => $this->get_labels(),
				'class'    => 'sg_email_marketing_groups',
				'attrs'    => array(
					'multiple'      => '',
					'data-selected' => $field['sg_email_marketing_groups'],
				),
				'multiple' => true,
			),
		);

		$args = array(
			'slug'    => 'sg_email_marketing_groups',
			'content' => $fld,
		);

		$this->field_element( 'row', $field, $args );

		$fld = $this->field_element(
			'toggle',
			$field,
			array(
				'slug'    => 'sg_email_marketing_checkbox_toggle',
				'value'   => $field['sg_email_marketing_checkbox_toggle'],
				'class'   => 'sg_email_marketing_checkbox_toggle',
				'desc'    => __( 'Display Consent Checkbox', 'siteground-email-marketing' ),
				'tooltip' => __( 'Recommended to be switched on if subscription is not the main purpose of the form', 'siteground-email-marketing' ),
			),
			false
		);

		$args = array(
			'slug'    => 'sg_email_marketing_checkbox_toggle',
			'content' => $fld,
		);

		$this->field_element( 'row', $field, $args );

		$fld = $this->field_element(
			'label',
			$field,
			array(
				'class'   => 'sg_email_marketing_checkbox_label',
				'slug'    => 'sg_email_marketing_checkbox_text',
				'value'   => __( 'Consent Checkbox Text', 'siteground-email-marketing' ),
			),
			false
		);

		$text = isset( $field['sg_email_marketing_checkbox_text'] ) ? $field['sg_email_marketing_checkbox_text'] : __( 'Subscribe to our Newsletter', 'siteground-email-marketing' );

		$fld .= $this->field_element(
			'text',
			$field,
			array(
				'class'   => 'sg_email_marketing_checkbox_text',
				'slug'    => 'sg_email_marketing_checkbox_text',
				'default' => __( 'Subscribe to our Newsletter', 'siteground-email-marketing' ),
				'value'   => $text,
			),
			false
		);

		$args = array(
			'slug'    => 'sg_email_marketing_checkbox_text',
			'content' => $fld,
		);

		$this->field_element( 'row', $field, $args );

		// Options close markup.
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );
	}

	/**
	 * Markup for the Groups select option.
	 *
	 * @since 1.1.4
	 *
	 * @param array $field Field properties.
	 * @param array $args  Arguments for the output of the field.
	 */
	public function field_element_select2( $field, $args ) {
		$id    = $field['id'];
		$slug  = $args['slug'];
		$attrs = '';

		if ( ! empty( $args['attrs'] ) ) {
			foreach ( $args['attrs'] as $arg_key => $val ) {
				if ( is_array( $val ) ) {
					$val = wp_json_encode( $val );
				}
				$attrs .= $arg_key . '=\'' . $val . '\'';
			}
		}

		$class   = $args['class'];
		$options = $args['options'];
		$value   = isset( $args['value'] ) ? $args['value'] : '';
		$output  = sprintf( '<select class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s][]" %s>', $class, $id, $slug, $id, $slug, $attrs );

		foreach ( $options as $arg_key => $arg_option ) {
			$output .= sprintf( '<option value="%s" %s>%s</option>', esc_attr( $arg_key ), selected( $arg_key, $value, false ), $arg_option );
		}
		$output .= '</select>';
		ob_start();
		include_once \SG_Email_Marketing\DIR . '/templates/WPForms_Checkbox_Scripts.tpl';
		$output .= ob_get_clean();
		return $output;
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.1.4
	 *
	 * @param array $field Field to be previewed.
	 */
	public function field_preview( $field ) {
			echo '<h4 class="sg-email-marketing-checkbox-disabled"><i class="fa fa-eye-slash"></i> ' .
				__( 'Consent checkbox is NOT ADDED. We recommend adding a consent checkbox if the main purpose of the form is not subscription. To include a consent checkbox, simply click on this alert and activate the Consent Checkbox option from the settings menu.<br>Note: This message is for administrative purposes and will not be visible to users.', 'siteground-email-marketing' ) .
				'</h4>';
			printf( '<div class="sg-email-marketing-checkbox-enabled"><input type="checkbox" disabled><span class="sg_email_marketing_field_preview_label">%s</span></div>', $field['sg_email_marketing_checkbox_text'] );

	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.1.4
	 *
	 * @param array $field      Field array.
	 * @param array $field_atts Field attributes.
	 * @param array $form_data  The form's data.
	 */
	public function field_display( $field, $field_atts, $form_data ) {
		if ( ! isset( $field['sg_email_marketing_checkbox_toggle'] ) ) {
			return;
		}
		// Setup and sanitize the necessary data.
		$field_id   = $field['properties']['inputs']['primary']['id'];
		$field_name = $field['properties']['inputs']['primary']['attr']['name'];
		printf( '<input id="%s" value="1" name="%s" type="checkbox"><label for="%s" class="sg_email_marketing_field_preview_label">%s</span>', $field_id, $field_name, $field_id, $field['sg_email_marketing_checkbox_text'] );

	}

	/**
	 * Formats and sanitizes field.
	 *
	 * @since 1.1.4
	 *
	 * @param int   $field_id     The ID of the field.
	 * @param array $field_submit The submitted data.
	 * @param array $form_data    The submitted form data.
	 */
	public function format( $field_id, $field_submit, $form_data ) {
		$data = array(
			'value' => empty( $field_submit ) ? 0 : 1,
			'id'    => absint( $field_id ),
			'type'  => $this->type,
		);

		wpforms()->process->fields[ $field_id ] = $data;
	}
	/**
	 * Retrieve the labels added in SG Email Marketing tool.
	 *
	 * @since 1.1.4
	 *
	 * @return array Array of labels.
	 */
	public function get_labels() {
		$labels_list = Loader::get_instance()->mailer_api->get_labels();
		$labels      = array();
		if ( empty( $labels_list['data'] ) ) {
			return array();
		}
		foreach ( $labels_list['data'] as $label ) {
			$labels[ $label['name'] ] = $label['name'];
		}

		return $labels;

	}
}
