<?php

add_filter( 'widget_text', 'wp3cxw_widget_text_filter', 9 );

function wp3cxw_widget_text_filter( $content ) {
	$pattern = '/\[[\r\n\t ]*webinar-form?[\r\n\t ].*?\]/';

	if ( ! preg_match( $pattern, $content ) ) {
		return $content;
	}

	$content = do_shortcode( $content );

	return $content;
}

add_action( 'wp_enqueue_scripts', 'wp3cxw_do_enqueue_scripts' );

function wp3cxw_do_enqueue_scripts() {
		wp3cxw_enqueue_scripts();
		wp3cxw_enqueue_styles();
}

function wp3cxw_enqueue_scripts() {
	$in_footer = true;

  $tcxmsg=array(
    'subscribeButton'=>__('Subscribe', '3cx-webinar'),
    'cancelButton'=>__('Cancel', '3cx-webinar'),
    'nameField'=>__('Name', '3cx-webinar'),
    'emailField'=>__('Email', '3cx-webinar'),
    'errorLength'=>__('Length of %1$s must be between %2$s and %3$s.', '3cx-webinar'),
    'errorName'=>__('Please enter your name without numbers or special characters', '3cx-webinar'),
    'errorEmail'=>__('Invalid email address', '3cx-webinar')
  );

	wp_enqueue_style('3cx-webinar-jquery-ui',wp3cxw_plugin_url( 'includes/css/jquery-ui.css'), null, WP3CXW_VERSION);
	wp_enqueue_style('3cx-webinar-flags',wp3cxw_plugin_url( 'includes/css/flags.css'), null, WP3CXW_VERSION);

	wp_enqueue_script('jquery-ui-dialog');

	wp_enqueue_script('3cx-webinar', wp3cxw_plugin_url( 'includes/js/scripts.js' ), array( 'jquery' ), WP3CXW_VERSION, $in_footer );
  wp_localize_script('3cx-webinar', 'tcxmsg', $tcxmsg );
  wp_localize_script('3cx-webinar', 'tcxtemplate', TCXWM_TEMPLATES );

	wp_enqueue_script('3cx-webinar-jsdd', wp3cxw_plugin_url( 'includes/js/jquery.dd.js' ), null, WP3CXW_VERSION);

	$wp3cxw = array(
		'apiSettings' => array(
			'root' => esc_url_raw( rest_url( '3cx-webinar/v1' ) ),
			'namespace' => '3cx-webinar/v1',
		),
	);

	if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
		$wp3cxw['cached'] = 1;
	}

	wp_localize_script( '3cx-webinar', 'wp3cxw', $wp3cxw );

	do_action( 'wp3cxw_enqueue_scripts' );
}

function wp3cxw_script_is() {
	return wp_script_is( '3cx-webinar' );
}

function wp3cxw_enqueue_styles() {
	wp_enqueue_style( '3cx-webinar',
		wp3cxw_plugin_url( 'includes/css/styles.css' ),
		array(), WP3CXW_VERSION, 'all' );

	if ( wp3cxw_is_rtl() ) {
		wp_enqueue_style( '3cx-webinar-rtl',
			wp3cxw_plugin_url( 'includes/css/styles-rtl.css' ),
			array(), WP3CXW_VERSION, 'all' );
	}

	do_action( 'wp3cxw_enqueue_styles' );
}

function wp3cxw_style_is() {
	return wp_style_is( '3cx-webinar' );
}
