<?php
/**
 * Admin Settings Handler.
 *
 * @link       http://jeanbaptisteaudras.com
 * @since      1.5
 */

/*
 * Filter the DPO email.
 */
function gdrf_filter_dpo_email( $admin_email ) {
	$gdrf_dpo_email = get_site_option( 'gdrf_dpo_email', '' );
	if ( isset( $gdrf_dpo_email ) && ! empty( $gdrf_dpo_email ) ) {
		$admin_email = sanitize_email( $gdrf_dpo_email );
	}
	return $admin_email;
}
add_filter( 'user_request_confirmed_email_to', 'gdrf_filter_dpo_email', 10, 1 );

/**
 * Enqueue a script in the WordPress admin on options-privacy.php.
 */
function gdrf_enqueue_admin_script( $hook ) {
	if ( 'options-privacy.php' != $hook ) {
		return;
	}

	if ( isset( $_POST['gdrf_email_setting'] ) && ! empty( $_POST['gdrf_email_setting'] ) ) {
		$dpo_email = sanitize_email( esc_html( $_POST['gdrf_email_setting'] ) );
		update_option( 'gdrf_dpo_email', $dpo_email );
		echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Email updated.', 'gdpr-data-request-form' ) . '</p></div>';
	}

	if ( ! empty( get_site_option( 'gdrf_dpo_email', '' ) ) ) {
		$dpo_email = sanitize_email( get_site_option( 'gdrf_dpo_email', '' ) );
	} else {
		$dpo_email = apply_filters( 'user_request_confirmed_email_to', get_site_option( 'admin_email' ) );
	}

	wp_enqueue_script( 'gdrf-admin', plugin_dir_url( __FILE__ ) . 'js/gdrf-admin.js', array( 'jquery' ) );

	$translation_array = array(
		'section_title'     => __( 'Data Protection Officer (DPO) email', 'gdpr-data-request-form' ),
		'input_label'       => __( 'Send data requests notifications to this email', 'gdpr-data-request-form' ),
		'input_value'       => $dpo_email,
		'save_button_label' => __( 'Save changes', 'gdpr-data-request-form' ),
	);

	wp_localize_script( 'gdrf-admin', 'gdrf_settings', $translation_array );

}
add_action( 'admin_enqueue_scripts', 'gdrf_enqueue_admin_script' );