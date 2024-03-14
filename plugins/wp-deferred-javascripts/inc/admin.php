<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}


/* ---------------------------------------------------------------------------------------------- */
/* !I18N ======================================================================================== */
/* ---------------------------------------------------------------------------------------------- */

/*
 * Plugin i18n init.
 *
 * @since 2.0.3
 */

add_action( 'plugins_loaded', 'wdjs_lang_init' );

function wdjs_lang_init() {
	load_plugin_textdomain( 'wp-deferred-javascripts', false, basename( dirname( WDJS_PLUGIN_FILE ) ) . '/languages/' );
}


/*-----------------------------------------------------------------------------------*/
/* !SETTINGS. ====================================================================== */
/* @since 2.0.3 admin panel to add do_not_defer                                      */
/*-----------------------------------------------------------------------------------*/

// !Register a "do not defer" option.

add_action( 'admin_init', 'wdjs_register_settings' );

function wdjs_register_settings() {
	register_setting( 'wdjs_option_group', 'wdjs_do_not_defer_opt', 'wdjs_do_not_defer_before_save' ); 
} 


// !Register a "do not defer" option.

add_action( 'admin_menu', 'wdjs_admin_page' );

function wdjs_admin_page() {
	add_options_page( __( 'WP Deferred Javascripts', 'wp-deferred-javascripts' ), __( 'WP Deferred Javascripts', 'wp-deferred-javascripts' ), 'manage_options', 'wp-deferred-javascripts', 'wdjs_option_page' );
}


// !Do not defer option page

function wdjs_option_page() {
	add_settings_section( 
		'wdjs-settings', 
		false,
		false,
		'wdjs-settings' 
		);

	add_settings_field( 
		'wdjs_do_not_defer_opt', 
		__( 'Javascript files to not defer', 'wp-deferred-javascripts' ), 
		'wdjs_settings_fields', 
		'wdjs-settings', 
		'wdjs-settings', 
		array( 
			'label_for' => 'wdjs_do_not_defer_opt',
			'name'      => 'wdjs_do_not_defer_opt',
			'infos'     => nl2br( __( "Add here JS handles or URL which will not be defered by WP Deferred Javascripts. \rOne script handle or URI per line.", 'wp-deferred-javascripts' ) ),
		    ) 
		);

	echo '<div class="wrap">';
	echo '<h2>' . __( 'WP Deferred Javascripts settings', 'wp-deferred-javascripts' ) . '</h2>';
	echo '<form method="post" action="options.php">';
		settings_fields( 'wdjs_option_group' );
		do_settings_sections( 'wdjs-settings' );
		submit_button( __( 'Save settings', 'wp-deferred-javascripts' ) );
	echo '</form>';
	echo '</div>';
}


// !Do not defer field

function wdjs_settings_fields( $args ) {
	$value = get_option( $args['name'], array() );
	$value = implode( PHP_EOL, $value );

	echo '<textarea class="widefat" name="' 
		. esc_attr( $args['name'] ) . '" id="' 
		. esc_attr( $args['label_for'] ) . '" rows="6">';
	echo esc_textarea( $value );
	echo '</textarea>';
	if ( isset( $args['infos'] ) && $args['infos'] ) {
		echo '<p><em>' . $args['infos'] . '</em></p>';
	}
}


// !Sanitize textarea values before save

function wdjs_do_not_defer_before_save( $value ) {
	$values = array_filter( explode( PHP_EOL, $value ) );
	$values = array_map( 'trim', $values );
	$values = array_map( 'sanitize_text_field', $values );
	$values = array_map( 'wp_filter_kses', $values );
	$values = array_filter( $values );
	return $values;
}

/**/