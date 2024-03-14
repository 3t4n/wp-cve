<?php

namespace SG_Email_Marketing\Integrations\ThirdParty\WPForms;

use SG_Email_Marketing\Integrations\ThirdParty\WPForms\SGWPMAIL_WPForms_Field;
use SG_Email_Marketing\Integrations\ThirdParty\Form_Parser;
use SG_Email_Marketing\Loader\Loader;
use SG_Email_Marketing\Traits\Ip_Trait;
/**
 * WP Forms integration.
 *
 * @since 2.0.0
 */
class WPForms extends \SG_Email_Marketing\Integrations\Integrations {
	use Ip_Trait;
	/**
	 * The integration id.
	 *
	 * @since 1.1.4
	 *
	 * @var string
	 */
	public $id = 'wp_forms';

	/**
	 * The checkbox name.
	 *
	 * @since 1.1.4
	 *
	 * @var string
	 */
	public $checkbox_name = 'sgwpmail_checkbox';

	/**
	 * Get the integration data.
	 *
	 * @since 1.1.4
	 *
	 * @return array Array containing integration data.
	 */
	public function fetch_settings() {
		$settings = get_option(
			$this->prefix . $this->id,
			array(
				'enabled'       => class_exists( '\WPForms_Field' ) ? 1 : 2,
				'labels'        => array(),
				'checkbox_text' => __( 'Sign me up for the newsletter!', 'siteground-email-marketing' ),
				'system'        => 1,
				'name'          => $this->id,
			)
		);

		$settings['title']       = __( 'WPForms', 'sg-email-marketing' );
		$settings['description'] = __( 'Add an optional checkbox to any form created with WPForms, enabling users to sign up for your mailing list. Enable this feature by adding the SG Email Marketing building block to any WPForms form.', 'sg-email-marketing' );

		return $settings;
	}

	/**
	 * Registering SGWPMAIL Field in the WPForms editor.
	 *
	 * @since 1.1.4
	 */
	public function register_custom_checkbox_field() {
		$settings = $this->fetch_settings();

		if ( ! class_exists( '\WPForms_Field' ) ) {
			return;
		}

		new SGWPMAIL_WPForms_Field( $settings['checkbox_text'] );
	}

	/**
	 * Enqueuing styles and scripts.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_styles_scripts() {
		wp_enqueue_script( 'selectize.js', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'selectize.js', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css' );
		wp_enqueue_style( 'googleFonts', '//fonts.googleapis.com/css2?family=Roboto&display=swap', array(), null );

		wp_enqueue_script(
			'sg-email-marketing-wp-forms-integration',
			\SG_Email_Marketing\URL . '/assets/js/integrations/wpforms/wpforms-editor.js',
			array( 'selectize.js', 'jquery' ),
			\SG_Email_Marketing\VERSION,
			true
		);
		wp_enqueue_style(
			'sg-email-marketing-wpforms-integration',
			\SG_Email_Marketing\URL . '/assets/css/integrations/wpforms/wpforms-editor.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);
	}
	/**
	 * Updating the post meta with the needed marketing groups.
	 *
	 * @since 1.1.4
	 *
	 * @return void
	 */
	public function save_form() {
		if ( isset( $_POST['form_id'] ) && isset( $_POST['sg_email_marketing_groups'] ) ) {
			update_post_meta( esc_attr( $_POST['form_id'] ), 'sg_email_marketing_groups', json_decode( stripslashes( $_POST['sg_email_marketing_groups'] ) ) );
		}
	}

	/**
	 * Updating the groups in the field options, as per the post_meta for the form.
	 *
	 * @since 1.1.4
	 *
	 * @param int   $form_id The ID of the form.
	 * @param array $data    The data of the form.
	 *
	 * @return void
	 */
	public function update_groups( $form_id, $data ) {
		foreach ( $data['fields'] as $key => $field ) {
			if ( 'sg_email_marketing' === $field['type'] ) {
				$data['fields'][ $key ]['sg_email_marketing_groups'] = get_post_meta( $form_id, 'sg_email_marketing_groups', true );
				$form_id = wpforms()->get( 'form' )->update( $form_id, $data, array( 'context' => 'save_form' ) );
				return;
			}
		}
	}

	/**
	 * Submission of the form
	 *
	 * @since 1.1.4
	 *
	 * @param  array $fields    Fields of the form.
	 * @param  array $entry     Current entry.
	 * @param  array $form_data Form data, containing properties of the form.
	 *
	 * @return void
	 */
	public function process_wpforms( $fields, $entry, $form_data ) {
		$fields_2 = array_map(
			function ( $item ) {
				$result = array();
				if ( 'sg_email_marketing' === $item['type'] ) {
					return array();
				}
				$result[ $item['type'] ] = $item['value'];
				return $result;
			},
			$fields
		);

		// Combine the results into a single array.
		$fields_2 = array_merge( ...$fields_2 );

		foreach ( $fields as $field_id => $field ) {
			if ( (
					'sg_email_marketing' === $field['type'] &&
					'1' === $form_data['fields'][ $field_id ]['sg_email_marketing_checkbox_toggle'] &&
					1 === $field['value']
				)
				|| (
					'sg_email_marketing' === $field['type'] &&
					! isset( $form_data['fields'][ $field_id ]['sg_email_marketing_checkbox_toggle'] )
				)
				) {
				$data = Form_Parser::extract_data( $fields_2 );
				// do nothing if no email was found.
				if ( empty( $data['email'] ) ) {
					return;
				}

				$data = array(
					'labels'    => $this->get_label_ids( $form_data['fields'][ $field_id ]['sg_email_marketing_groups'] ),
					'firstName' => $data['first_name'],
					'lastName'  => $data['last_name'],
					'email'     => $data['email'],
					'timestamp' => time(),
					'ip'        => $this->get_current_user_ip(),
				);

				$this->mailer_api->send_data( array( $data ) );
			}
		}
	}
	/**
	 * Get label ids from label names
	 *
	 * @since 1.1.4
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
