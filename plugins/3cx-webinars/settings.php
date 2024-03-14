<?php

require_once WP3CXW_PLUGIN_DIR . '/includes/functions.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/l10n.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/formatting.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/capabilities.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/webinar-form.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/webinar-form-functions.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/config-validator.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/webinar.php';
require_once WP3CXW_PLUGIN_DIR . '/includes/tpl/templates.php';

if ( is_admin() ) {
	require_once WP3CXW_PLUGIN_DIR . '/admin/admin.php';
} else {
	require_once WP3CXW_PLUGIN_DIR . '/includes/controller.php';
}

class WP3CXW {

	public static function get_option( $name, $default = false ) {
		$option = get_option( 'wp3cxw' );

		if ( false === $option ) {
			return $default;
		}

		if ( isset( $option[$name] ) ) {
			return $option[$name];
		} else {
			return $default;
		}
	}

	public static function update_option( $name, $value ) {
		$option = get_option( 'wp3cxw' );
		$option = ( false === $option ) ? array() : (array) $option;
		$option = array_merge( $option, array( $name => $value ) );
		update_option( 'wp3cxw', $option );
	}
}

add_action( 'plugins_loaded', 'wp3cxw' );

function wp3cxw() {
	wp3cxw_load_textdomain();

	/* Shortcodes */
	add_shortcode( '3cx-webinar', 'wp3cxw_webinar_form_tag_func' );
	add_shortcode( 'webinar-form', 'wp3cxw_webinar_form_tag_func' );
}

add_action( 'init', 'wp3cxw_init' );

function wp3cxw_init() {
	wp3cxw_register_post_types();
	do_action( 'wp3cxw_init' );
}

add_action( 'admin_init', 'wp3cxw_upgrade' );

function wp3cxw_upgrade() {
	$old_ver = WP3CXW::get_option( 'version', '0' );
	$new_ver = WP3CXW_VERSION;

	if ( $old_ver == $new_ver ) {
		return;
	}

	do_action( 'wp3cxw_upgrade', $new_ver, $old_ver );

	WP3CXW::update_option( 'version', $new_ver );
}

/* Install and default settings */

add_action( 'activate_' . WP3CXW_PLUGIN_BASENAME, 'wp3cxw_install' );

function wp3cxw_register_subscribe_route() {
  register_rest_route('3cx-webinar', '/subscribe', array(
       'methods'=>'POST',
       'callback'=>'wp3cxw_subscribe_request'
  ));
}
add_action('rest_api_init', 'wp3cxw_register_subscribe_route');

function wp3cxw_install() {
	if ( $opt = get_option( 'wp3cxw' ) ) {
		return;
	}

	wp3cxw_load_textdomain();
	wp3cxw_register_post_types();
	wp3cxw_upgrade();

	if ( get_posts( array( 'post_type' => 'wp3cxw_webinar_form' ) ) ) {
		return;
	}

	$webinar_form = WP3CXW_WebinarForm::get_template(
		array(
			'title' =>
				/* translators: title of your first Webinar form. %d: number fixed to '1' */
				sprintf( __( 'Webinar form %d', '3cx-webinar' ), 1 ),
		)
	);

	$webinar_form->save();

	WP3CXW::update_option( 'bulk_validate',
		array(
			'timestamp' => current_time( 'timestamp' ),
			'version' => WP3CXW_VERSION,
			'count_valid' => 1,
			'count_invalid' => 0,
		)
	);
}
