<?php

add_action( 'nikan_setting_form_submit_general_settings', 'generalsubmitButton' );

function generalsubmitButton() {
	submit_button();
}

add_filter ('nikan_settings_sections', 'add_nikan_general_tab');

function add_nikan_general_tab ($sections) {
			
	$sections[] = array(
		'id'    => 'general_settings',
		'title' => __( 'General', 'nik-base' ),
		'subtitle' => __( 'Free features', 'nik-base' )
	);
			
	return $sections;
}
		
add_filter ('nikan_settings_section_content', 'add_nikan_general_tab_settings');

function add_nikan_general_tab_settings ($settings_fields) {
		
	$settings_fields['general_settings'] = array(

		array(
			'name'  => 'disable_password_strength',
			'label' => __( 'Disable Password Strength', 'nik-base' ),
			'desc'  => __( 'If you want to disable woocommerce password strength, Check this option.', 'nik-base' ),
			'type'  => 'checkbox',
		),
		array(
			'name'  => 'persian_postal',
			'label' => __( 'Persian Postalcode', 'nik-base' ),
			'desc'  => __( 'Enable this option if you want users can use persian numbers in woocommerce postal code field.', 'nik-base' ),
			'type'  => 'checkbox',
		),
		array(
			'name'  => 'disable_woo_tracking',
			'label' => __( 'Disable Woocommerce Tracking', 'nik-base' ),
			'desc'  => __( 'Enable this option if your wp admin is low and try to load stats.wp.com', 'nik-base' ),
			'type'  => 'checkbox',
		),

	);
	
	return $settings_fields;
}

if ( NIKANHELP()->Nikan_Options('disable_password_strength','general_settings') == 'on' ) {
	add_action( 'wp_print_scripts', 'nikan_remove_password_strength', 10 );
}

function nikan_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}

if ( NIKANHELP()->Nikan_Options('persian_postal','general_settings') == 'on' ) {
	add_filter( 'woocommerce_format_postcode', 'nikan_woocommerce_persian_postcode', 2, 10 );
}
 
function nikan_woocommerce_persian_postcode( $postcode, $country ) {
	return NIKANHELP()->Nikan_English_Num( $postcode );
}

if ( NIKANHELP()->Nikan_Options('disable_woo_tracking','general_settings') == 'on' ) {
	add_filter( 'woocommerce_apply_user_tracking', '__return_false' );
}