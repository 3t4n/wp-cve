<?php

if ( defined(WP_UNINSTALL_PLUGIN) )
{
	$flattr_options_to_remove = array(
		// From Flattr::default_options()
		'flattr_global_button',
		'flattr_post_types',
		'flattr_lng',
		'flattr_aut',
		'flattr_aut_page',
		'flattr_atags',
		'flattr_cat',
		'flattr_top',
		'flattr_compact',
		'flattr_popout_enabled',
		'flattr_button_style',
		'flattrss_custom_image_url',
		'user_based_flattr_buttons',
		'user_based_flattr_buttons_since_time',
		'flattrss_button_enabled',
		'flattrss_relpayment_enabled',
		'flattr_relpayment_enabled',
		'flattrss_relpayment_escaping_disabled',
		// From other places
		'flattrss_api_key',
		'flattrss_api_secret',
		'flattr_access_token',
	);

	foreach ( $flattr_options_to_remove as $flattr_option ) {
		delete_option( $flattr_option );
	}
}
