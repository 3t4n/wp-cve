<?php
if ( ! function_exists( 'qi_addons_for_elementor_get_contact_form_7_forms' ) ) {
	/**
	 * Function that return array of contact form 7 forms
	 *
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_contact_form_7_forms() {
		$options       = array();
		$contact_forms = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

		if ( ! empty( $contact_forms ) ) {
			foreach ( $contact_forms as $contact_form ) {
				$options[ $contact_form->ID ] = esc_html( $contact_form->post_title );
			}
		} else {
			$options[0] = esc_html__( 'No contact forms found', 'qi-addons-for-elementor' );
		}

		return $options;
	}
}
