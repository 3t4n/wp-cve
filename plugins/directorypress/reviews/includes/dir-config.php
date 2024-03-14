<?php defined( 'ABSPATH' ) or die;

$basepath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;

$debug = false;
if ( isset( $_GET['debug'] ) && $_GET['debug'] == 'true' ) {
	$debug = true;
}

$debug = true;

return array(
	'plugin-name'     => 'direviews',
	'settings-key'    => 'direviews_settings',
	'textdomain'      => 'comments-ratings',
	'template-paths'  => array(
		$basepath . 'includes/main/views/form-partials/',
		$basepath . 'views/form-partials/',
	),
	'fields'          => array(
		'hiddens' => include DIRS_DIR. 'settings/hiddens' . EXT,
		'labels'  => include DIRS_DIR. 'settings/labels' . EXT,
		'general' => include DIRS_DIR. 'settings/general' . EXT,
	),
	'processor'       => array(
		// callback signature: (array $input, designinventoProcessor $processor)
		'preupdate' => array(
			// callbacks to run before update process
			// cleanup and validation has been performed on data
		),
//		'postupdate' => array(
//			'save_settings'
//		),
	),
	'cleanup'         => array(
		'switch' => array( 'switch_not_available' ),
	),
	'checks'          => array(
		'counter' => array( 'is_numeric', 'not_empty' ),
	),
	'errors'          => array(
		'not_empty' => esc_html__( 'Invalid Value.', 'comments-ratings' ),
	),
//	'callbacks'             => array(
//		'save_settings' => 'save_customizer_plugin_settings'
//	),
	// shows exception traces on error
	'debug'           => $debug,
	/**
	 * DEFAULTS - The default plugin options
	 */
	'default_options' => array()

); # config
