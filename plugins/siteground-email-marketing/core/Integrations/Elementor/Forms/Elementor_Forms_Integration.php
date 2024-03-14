<?php

namespace SG_Email_Marketing\Integrations\Elementor\Forms;

use SG_Email_Marketing\Loader\Loader;
use SG_Email_Marketing\Traits\Ip_Trait;

/**
 * Elementor Form Integration - SG Email Marketing Integration.
 *
 * Add a new "SG Email Marketing" action on submit to the Elementor form widget.
 *
 * @since 1.1.3
 */
class Elementor_Forms_Integration extends \ElementorPro\Modules\Forms\Classes\Integration_Base {
	use Ip_Trait;

	/**
	 * Retrieve SGWPMAIL action name.
	 *
	 * @since 1.1.3
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sgwpmail';
	}

	/**
	 * Retrieve SGWPMAIL action label.
	 *
	 * @since 1.1.3
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'SiteGround Email Marketing', 'siteground-email-marketing' );
	}

	/**
	 * Add input fields to allow the user to customize the action settings.
	 *
	 * @since 1.1.3
	 *
	 * @param \Elementor\Widget_Base $widget Elementor Widget to be modified.
	 */
	public function register_settings_section( $widget ) {

		$widget->start_controls_section(
			'section_sgwpmail',
			array(
				'label'     => esc_html__( 'SiteGround Email Marketing', 'siteground-email-marketing' ),
				'condition' => array(
					'submit_actions' => $this->get_name(),
				),
			)
		);

		$widget->add_control(
			'sgwpmail_elementor_forms_labels_list',
			array(
				'label'       => esc_html__( 'Groups', 'siteground-email-marketing' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_labels(),
				'description' => esc_html__( 'People subscribing through this form will be added to the selected groups', 'siteground-email-marketing' ),
			)
		);

		$widget->add_control(
			'sgwpmail_elementor_forms_checkbox_enabled_bool',
			array(
				'type' => \Elementor\Controls_Manager::HIDDEN,
			)
		);

		$widget->add_control(
			'sgwpmail_elementor_forms_checkbox_enabled',
			array(
				'label'       => __( 'Consent Checkbox is <span class="enabled">ADDED</span>', 'siteground-email-marketing' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => '',
				'label_off'   => '',
				'description' => __( 'Users submitting the form will be subscribed if they have provided consent.', 'siteground-email-marketing' ),
				'condition'   => array(
					'sgwpmail_elementor_forms_checkbox_enabled_bool' => 'true',
				),
				'render_type' => 'template',
			)
		);
		$widget->add_control(
			'sgwpmail_elementor_forms_checkbox_disabled',
			array(
				'label'       => __( 'Consent Checkbox is <span class="disabled">NOT ADDED</span>', 'siteground-email-marketing' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => '',
				'label_off'   => '',
				'description' => __( 'We recommend adding a consent checkbox if the main purpose of the form is not subscription. You can add a consent checkbox from "Form Fields", Type - SG Email Marketing Checkbox', 'siteground-email-marketing' ),
				'condition'   => array(
					'sgwpmail_elementor_forms_checkbox_enabled_bool!' => 'true',
				),
				'render_type' => 'template',
			)
		);

		$this->register_fields_map_control( $widget );

		$widget->end_controls_section();

	}

	/**
	 * Retrieve the labels added in SG Email Marketing tool.
	 *
	 * @since 1.1.3
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
	/**
	 * Runs the SGWPMAIL action after form submission.
	 *
	 * @since 1.1.3
	 *
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record       Form record that is to be saved.
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler Ajax Handler object that is in use.
	 */
	public function run( $record, $ajax_handler ) {

		$checkbox_field_id = false;
		$fields            = $record->get( 'fields' );
		$data              = $record->get( 'sent_data' );

		foreach ( $fields as $field ) {
			if ( 'sg-email-marketing-checkbox' === $field['type'] ) {
				$checkbox_field_id = $field['id'];
			}
		}
		if ( 
			( ! empty( $checkbox_field_id ) && ! empty( $data[ $checkbox_field_id ] ) ) ||
			empty( $checkbox_field_id ) 
		) {
			$settings   = $record->get( 'form_settings' );
			$field_map  = $settings['sgwpmail_fields_map'];
			$label_ids  = $this->get_label_ids( $settings['sgwpmail_elementor_forms_labels_list'] );
			$first_name = '';
			$last_name  = '';
			$first_name_localid  = '';
			$last_name_localid  = '';
			$email      = '';

			foreach ( $field_map as $field ) {
				if ( 'firstname' === $field['remote_id'] && '' !== $field['local_id'] ) {
					$first_name = $data[ $field['local_id'] ];
					$first_name_localid = $field['local_id'];
				}
				if ( 'lastname' === $field['remote_id'] && '' !== $field['local_id'] ) {
					$last_name = $data[ $field['local_id'] ];
					$last_name_localid = $field['local_id'];
				}
				if ( 'email' === $field['remote_id'] && '' !== $field['local_id'] ) {
					$email = $data[ $field['local_id'] ];
				}
			}

			if ( empty( $email ) ) {
				return true;
			}

			if (
				! empty( $first_name_localid ) &&
				! empty( $last_name_localid ) &&
				$first_name_localid === $last_name_localid
			) {
				$first_name = reset( explode( ' ', $first_name ) );
				$last_name = end( explode( ' ', $last_name ) );
			}

			$data = array(
				'labels'    => $label_ids,
				'firstName' => $first_name,
				'lastName'  => $last_name,
				'email'     => $email,
				'timestamp' => time(),
				'ip'        => $this->get_current_user_ip(),
			);

			Loader::get_instance()->mailer_api->send_data( array( $data ) );
			return true;
		}
	}

	/**
	 * Clears SGWPMAIL form settings/fields when exporting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $element Widget to be exported.
	 *
	 * @return array Widget, after it has been cleared.
	 */
	public function on_export( $element ) {

		unset(
			$element['sgwpmail_elementor_forms_labels_list'],
			$element['sgwpmail_elementor_forms_checkbox_enabled'],
			$element['sgwpmail_elementor_forms_checkbox_label'],
			$element['sgwpmail_fields_map'],
		);

		return $element;

	}

	/**
	 * Set fields map controls options.
	 *
	 * @since 1.1.3
	 *
	 * @return array Options to be added.
	 */
	protected function get_fields_map_control_options() {
		return array(
			'default' => array(
				array(
					'remote_id'    => 'firstname',
					'remote_label' => esc_html__( 'First Name', 'siteground-email-marketing' ),
					'remote_type'  => 'text',
				),
				array(
					'remote_id'    => 'lastname',
					'remote_label' => esc_html__( 'Last Name', 'siteground-email-marketing' ),
					'remote_type'  => 'text',
				),
				array(
					'remote_id'       => 'email',
					'remote_label'    => esc_html__( 'Email', 'siteground-email-marketing' ),
					'remote_type'     => 'email',
					'remote_required' => true,
				),
			),
		);
	}
	/**
	 * Get label ids from label names
	 *
	 * @since 1.1.3
	 *
	 * @param  array $label_names A list with the label names.
	 *
	 * @return array              A list with label ids.
	 */
	public function get_label_ids( $label_names ) {
		$labels_list = Loader::get_instance()->mailer_api->get_labels();

		$label_ids = array();

		foreach ( $labels_list['data'] as $label ) {
			if ( in_array( $label['name'], $label_names, true ) ) {
				$label_ids[] = $label['id'];
			}
		}
		return $label_ids;
	}
}
