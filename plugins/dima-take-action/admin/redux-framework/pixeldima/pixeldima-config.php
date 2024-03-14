<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
	return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "dima_ta_demo";

// Background Patterns Reader
$sample_patterns_path = ReduxFramework::$_dir . '../pixeldima/patterns/';
$sample_patterns_url  = ReduxFramework::$_url . '../pixeldima/patterns/';
$sample_patterns      = array();

if ( is_dir( $sample_patterns_path ) ) {

	if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
		$sample_patterns = array();

		while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

			if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
				$name              = explode( '.', $sample_patterns_file );
				$name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
				$sample_patterns[] = array(
					'alt' => $name,
					'img' => $sample_patterns_url . $sample_patterns_file
				);
			}
		}
	}
}

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */


$args = array(
	'opt_name'             => $opt_name,
	'display_name'         => DIMA_TAKE_ACTION_NAME,
	'display_version'      => DIMA_TAKE_ACTION_VERSION,
	'menu_type'            => 'menu',
	'allow_sub_menu'       => true,
	'intro_text'           => '',
	'menu_title'           => __( 'DIMA Take Action', 'dima-take-action' ),
	'page_title'           => __( 'DIMA Take Action', 'dima-take-action' ),
	'google_api_key'       => '',
	'google_update_weekly' => false,
	'async_typography'     => false,
	'admin_bar'            => false,
	'admin_bar_icon'       => 'dashicons-admin-generic',
	'global_variable'      => 'dima_ta_demo',
	'dev_mode'             => false,
	'update_notice'        => false,
	'customizer'           => false,
	'page_priority'        => null,
	'page_parent'          => 'themes.php',
	'page_permissions'     => 'manage_options',
	'menu_icon'            => '',
	'last_tab'             => '',
	'page_icon'            => 'icon-themes',
	'page_slug'            => '',
	'save_defaults'        => true,
	'default_show'         => false,
	'default_mark'         => '',
	'show_import_export'   => true,
	'transient_time'       => 60 * MINUTE_IN_SECONDS,
	'output'               => false,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag'           => false,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	'footer_credit'        => false,
	// Disable the footer credit of Redux. Please leave if you can help it.
	'footer_text'          => "",
	'show_import_export'   => true,
	'system_info'          => true,
	'database'             => '',
	'use_cdn'              => true,
);

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args['share_icons'][] = array(
	'url'   => 'https://www.facebook.com/pages/pixeldima',
	'title' => 'Like us on Facebook',
	'icon'  => 'el el-facebook'
);

$args['share_icons'][] = array(
	'url'   => 'http://twitter.com/pixeldima',
	'title' => 'Follow us on Twitter',
	'icon'  => 'el el-twitter'
);

$args['share_icons'][] = array(
	'url'   => 'https://pixeldima.com/',
	'title' => 'Visit our Website',
	'icon'  => 'el el-globe'
);

$args['share_icons'][] = array(
	'url'   => 'https://github.com/pixeldima',
	'title' => 'Visit us on GitHub',
	'icon'  => 'el el-github'
);

$args['share_icons'][] = array(
	'url'   => 'http://www.linkedin.com/company/pixeldima',
	'title' => 'Find us on LinkedIn',
	'icon'  => 'el el-linkedin'
);


Redux::setArgs( $opt_name, $args );


$tabs = array(
	array(
		'id'      => 'redux-help-tab-1',
		'title'   => __( 'Theme Information 1', 'dima-take-action' ),
		'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'dima-take-action' )
	),
	array(
		'id'      => 'redux-help-tab-2',
		'title'   => __( 'Theme Information 2', 'dima-take-action' ),
		'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'dima-take-action' )
	)
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'dima-take-action' );
Redux::setHelpSidebar( $opt_name, $content );


// -> START Basic Fields
Redux::setSection( $opt_name, array(
	'title'            => __( 'Settings', 'dima-take-action' ),
	'id'               => 'basic',
	'desc'             => __( 'These are really basic fields!', 'dima-take-action' ),
	'customizer_width' => '400px',
	'icon'             => 'el el-home'
) );

Redux::setSection( $opt_name, array(
	'title'            => __( 'General', 'dima-take-action' ),
	'id'               => 'basic-checkbox',
	'subsection'       => true,
	'customizer_width' => '450px',
	'desc'             => '',
	'fields'           => array(

		array(
			'id'      => 'dima-ta-banner-enabled',
			'type'    => 'switch',
			'title'   => __( 'Enabled Banner', 'dima-take-action' ),
			'default' => false,
		),

		array(
			'id'       => 'dima-ta-banner-campaign-name',
			'type'     => 'text',
			'title'    => __( 'Campaign Name', 'dima-take-action' ),
			'subtitle' => '',
			'desc'     => '',
			'validate' => 'text',
			'msg'      => '',
			'default'  => __( 'campaign', 'dima-take-action' ),
			'required' => array(
				array( 'dima-ta-use-button', 'equals', true ),
			)
		),

		array(
			'id'       => 'dima-ta-banner-campaign-id',
			'type'     => 'text',
			'title'    => __( 'Campaign ID', 'dima-take-action' ),
			'subtitle' => '',
			'desc'     => '',
			'validate' => 'text',
			'msg'      => '',
			'default'  => __( '123456', 'dima-take-action' ),
			'required' => array(
				array( 'dima-ta-use-button', 'equals', true ),
			)
		),
		array(
			'id'      => 'dima-ta-banner-pos',
			'type'    => 'button_set',
			'title'   => __( 'Select bnner Position', 'dima-take-action' ),
			'options' => array(
				'top'    => 'Top',
				'buttom' => 'Buttom',
			),
			'default' => 'top'
		),

		array(
			'id'       => 'dima-ta-use-close',
			'type'     => 'switch',
			'title'    => __( 'Close', 'dima-take-action' ),
			'subtitle' => __( 'Displays a close button at the top right corner of the bar.', 'dima-take-action' ),
			'default'  => true,
		),

		array(
			'id'      => 'dima-ta-use-button',
			'type'    => 'switch',
			'title'   => __( 'Use A Button', 'dima-take-action' ),
			'default' => true,
		),

		array(
			'id'       => 'dima-ta-button-txt',
			'type'     => 'text',
			'title'    => __( 'Button Text', 'dima-take-action' ),
			'subtitle' => '',
			'desc'     => '',
			'validate' => 'text',
			'msg'      => '',
			'default'  => __( 'Download Now', 'dima-take-action' ),
			'required' => array(
				array( 'dima-ta-use-button', 'equals', true ),
			)
		),

		array(
			'id'       => 'dima-ta-button-url',
			'type'     => 'text',
			'title'    => __( 'Button URL', 'dima-take-action' ),
			'subtitle' => '',
			'desc'     => '',
			'validate' => 'url',
			'msg'      => '',
			'default'  => 'https://pixeldima.com/',
			'required' => array(
				array( 'dima-ta-use-button', 'equals', true ),
			)
		),

		array(
			'id'      => 'dima-ta-button-target',
			'type'    => 'switch',
			'title'   => __( 'Open on New Window', 'dima-take-action' ),
			'default' => true,
			'required' => array(
				array( 'dima-ta-use-button', 'equals', true ),
			)
		),

		array(
			'id'      => 'dima-ta-banner-msg',
			'type'    => 'editor',
			'title'   => __( 'Message Text', 'dima-take-action' ),
			'default' => 'Powered by PixelDima.',
			'args'    => array(
				'teeny'         => true,
				'media_buttons' => false,
				'textarea_rows' => 10
			)
		),


	)
) );

//Baner style
Redux::setSection( $opt_name, array(
	'title'      => __( 'Banner Style', 'dima-take-action' ),
	'id'         => 'banner-style',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'            => 'dima-ta-banner-height',
			'type'          => 'slider',
			'title'         => __( 'Banner Height (px)', 'dima-take-action' ),
			"default"       => 70,
			"min"           => 50,
			"step"          => 5,
			"max"           => 200,
			'display_value' => 'text'
		),

		array(
			'id'      => 'dima-ta-banner-width-unite',
			'type'    => 'button_set',
			'title'   => __( 'Select Banner Width unite', 'dima-take-action' ),
			'options' => array(
				'px'  => 'PX',
				'per' => '%',
			),
			'default' => 'px'
		),

		array(
			'id'            => 'dima-ta-banner-width-per',
			'type'          => 'slider',
			'title'         => __( 'Banner Width (%)', 'dima-take-action' ),
			"default"       => 80,
			"min"           => 50,
			"step"          => 1,
			"max"           => 98,
			'required'      => array(
				array( 'dima-ta-banner-width-unite', 'equals', 'per' ),
			),
			'display_value' => 'text'
		),

		array(
			'id'            => 'dima-ta-banner-width',
			'type'          => 'slider',
			'title'         => __( 'Banner Width (px)', 'dima-take-action' ),
			"default"       => 1170,
			"min"           => 960,
			"step"          => 5,
			"max"           => 1600,
			'required'      => array(
				array( 'dima-ta-banner-width-unite', 'equals', 'px' ),
			),
			'display_value' => 'text'
		),

		array(
			'id'            => 'dima-ta-banner-font-size',
			'type'          => 'slider',
			'title'         => __( 'Font Size (px)', 'dima-take-action' ),
			"default"       => 14,
			"min"           => 12,
			"step"          => 1,
			"max"           => 35,
			'required'      => array(
				array( 'dima-ta-banner-width-unite', 'equals', 'px' ),
			),
			'display_value' => 'text'
		),

		array(
			'id'      => 'dima-ta-bg-type',
			'type'    => 'button_set',
			'title'   => __( 'Select Background Color Type', 'dima-take-action' ),
			'options' => array(
				'gradient' => 'Gradient',
				'color'    => 'Solid Color',
				'img'      => 'Image',
			),

			'default' => 'color'
		),

		array(
			'id'       => 'dima-ta-banner-img',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Background Image', 'dima-take-action' ),
			'compiler' => 'true',
			'default'  => '',
			'required' => array(
				array( 'dima-ta-bg-type', 'equals', 'img' ),
			)
		),

		array(
			'id'       => 'dima-ta-bg-color',
			'type'     => 'color_rgba',
			'title'    => 'Background Color',
			'default'  => array(
				'color' => '#333333',
				'alpha' => 1
			),
			'options'  => array(
				'show_input' => true,
			),
			'required' => array(
				array( 'dima-ta-bg-type', 'equals', 'color' ),
			)
		),

		array(
			'id'       => 'dima-ta-banner-gradient-color',
			'type'     => 'color_gradient',
			'title'    => __( 'Banner Gradient Background Color', 'dima-take-action' ),
			'validate' => 'color',
			'default'  => array(
				'from' => '#666666',
				'to'   => '#333333',
			),
			'required' => array(
				array( 'dima-ta-bg-type', 'equals', 'gradient' ),
			)
		),

		array(
			'id'      => 'dima-ta-text-color',
			'type'    => 'color_rgba',
			'title'   => 'Text Color',
			'default' => array(
				'color' => '#FFFFFF',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

	)
) );

//Baner style
Redux::setSection( $opt_name, array(
	'title'      => __( 'Banner button Style', 'dima-take-action' ),
	'id'         => 'banner-btn-style',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'            => 'dima-ta-banner-btn-height',
			'type'          => 'slider',
			'title'         => __( 'Button Height (px)', 'dima-take-action' ),
			"default"       => 40,
			"min"           => 30,
			"step"          => 1,
			"max"           => 80,
			'display_value' => 'text'
		),

		array(
			'id'      => 'dima-ta-btn-bg-color',
			'type'    => 'color_rgba',
			'title'   => 'Background Color',
			'default' => array(
				'color' => '#5b5e63',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

		array(
			'id'      => 'dima-ta-btn-text-color',
			'type'    => 'color_rgba',
			'title'   => 'Text Color',
			'default' => array(
				'color' => '#FFFFFF',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

	)
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Mobile', 'dima-take-action' ),
	'id'         => 'dima-ta-mobile',
	'class'      => 'dima_ta_mobile',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'dima-ta-use-banner-mobile',
			'type'    => 'switch',
			'title'   => __( 'Show banner on small screen', 'dima-take-action' ),
			'default' => true,
		),

		array(
			'id'       => 'dima-ta-banner-mobile-url',
			'type'     => 'text',
			'title'    => __( 'Message URL For Mobile', 'dima-take-action' ),
			'validate' => 'url',
			'msg'      => '',
			'default'  => 'https://pixeldima.com/',
			'required' => array(
				array( 'dima-ta-use-banner-mobile', 'equals', true ),
			)
		),

		array(
			'id'       => 'dima-ta-banner-mobile-msg',
			'type'     => 'editor',
			'title'    => __( 'Message Text For Mobile', 'dima-take-action' ),
			'default'  => 'Powered by PixelDima.',
			'args'     => array(
				'teeny'         => true,
				'media_buttons' => false,
				'textarea_rows' => 10
			),
			'required' => array(
				array( 'dima-ta-use-banner-mobile', 'equals', true ),
			)
		),

	)
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Float Button', 'dima-take-action' ),
	'id'         => 'float-button',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'dima-ta-float-button-enabled',
			'type'    => 'switch',
			'title'   => __( 'Enabled', 'dima-take-action' ),
			'default' => false,
		),

		array(
			'id'       => 'dima-ta-float-button-logo',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Logo', 'dima-take-action' ),
			'compiler' => 'true',
			'default'  => '',
		),

		array(
			'id'      => 'dima-ta-float-button-logo-bg-color',
			'type'    => 'color_rgba',
			'title'   => 'Logo Background Color',
			'default' => array(
				'color' => '#ffffff',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

		array(
			'id'      => 'dima-ta-float-button-txt',
			'type'    => 'text',
			'title'   => __( 'Button Text', 'dima-take-action' ),
			'default' => 'Buy Now.',
		),

		array(
			'id'       => 'dima-ta-float-button-url',
			'type'     => 'text',
			'title'    => __( 'Button URL', 'dima-take-action' ),
			'validate' => 'url',
			'msg'      => '',
			'default'  => 'https://pixeldima.com/',
		),

		array(
			'id'      => 'dima-ta-float-button-target',
			'type'    => 'switch',
			'title'   => __( 'Open on New Window', 'dima-take-action' ),
			'default' => true
		),

		array(
			'id'      => 'dima-ta-float-button-color',
			'type'    => 'color_rgba',
			'title'   => 'Background Color',
			'default' => array(
				'color' => '#00dcaf',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

		array(
			'id'      => 'dima-ta-float-button-txt-color',
			'type'    => 'color_rgba',
			'title'   => 'Text Color',
			'default' => array(
				'color' => '#ffffff',
				'alpha' => 1
			),
			'options' => array(
				'show_input' => true,
			),
		),

	)
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'About', 'dima-take-action' ),
	'desc'       => __( '<p class="description"><strong>We build Pixel-perfect WordPress Themes & Plugins.</strong><br> Get everything you need to power your online business and design process</p>', 'dima-take-action' ),
	'id'         => 'dima-ta-more',
	'class'      => 'dima_ta_more',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'opt-raw',
			'type'    => 'raw',
			'title'   => __( 'Creative themes', 'dima-take-action' ),
			'content' => file_get_contents( dirname( __FILE__ ) . '/info-html.html' )
		),
	)
) );

Redux::setSection( $opt_name, array(
	'icon'            => 'el el-list-alt',
	'title'           => __( 'Customizer Only', 'dima-take-action' ),
	'desc'            => __( '<p class="description">This Section should be visible only in Customizer</p>', 'dima-take-action' ),
	'customizer_only' => true,
	'fields'          => array(
		array(
			'id'              => 'opt-customizer-only',
			'type'            => 'select',
			'title'           => __( 'Customizer Only Option', 'dima-take-action' ),
			'subtitle'        => __( 'The subtitle is NOT visible in customizer', 'dima-take-action' ),
			'desc'            => __( 'The field desc is NOT visible in customizer.', 'dima-take-action' ),
			'customizer_only' => true,
			//Must provide key => value pairs for select options
			'options'         => array(
				'1' => 'Opt 1',
				'2' => 'Opt 2',
				'3' => 'Opt 3'
			),
			'default'         => '2'
		),
	)
) );

if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
	$section = array(
		'icon'   => 'el el-list-alt',
		'title'  => __( 'Documentation', 'dima-take-action' ),
		'fields' => array(
			array(
				'id'           => '17',
				'type'         => 'raw',
				'markdown'     => true,
				'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
				//'content' => 'Raw content here',
			),
		),
	);
	Redux::setSection( $opt_name, $section );
}

// If Redux is running as a plugin, this will remove the demo notice and links
add_action( 'redux/loaded', 'remove_demo' );

/**
 * This is a test function that will let you see when the compiler hook occurs.
 * It only runs if a field    set with compiler=>true is changed.
 * */
if ( ! function_exists( 'compiler_action' ) ) {
	function compiler_action( $options, $css, $changed_values ) {
		echo '<h1>The compiler hook has run!</h1>';
		echo "<pre>";
		print_r( $changed_values ); // Values that have changed since the last save
		echo "</pre>";
		//print_r($options); //Option values
		//print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
	}
}

/**
 * Custom function for the callback validation referenced above
 * */
if ( ! function_exists( 'redux_validate_callback_function' ) ) {
	function redux_validate_callback_function( $field, $value, $existing_value ) {
		$error   = false;
		$warning = false;

		//do your validation
		if ( $value == 1 ) {
			$error = true;
			$value = $existing_value;
		} elseif ( $value == 2 ) {
			$warning = true;
			$value   = $existing_value;
		}

		$return['value'] = $value;

		if ( $error == true ) {
			$field['msg']    = 'your custom error message';
			$return['error'] = $field;
		}

		if ( $warning == true ) {
			$field['msg']      = 'your custom warning message';
			$return['warning'] = $field;
		}

		return $return;
	}
}

/**
 * Custom function for the callback referenced above
 */
if ( ! function_exists( 'redux_my_custom_field' ) ) {
	function redux_my_custom_field( $field, $value ) {
		print_r( $field );
		echo '<br/>';
		print_r( $value );
	}
}

/**
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 * */
if ( ! function_exists( 'dynamic_section' ) ) {
	function dynamic_section( $sections ) {
		//$sections = array();
		$sections[] = array(
			'title'  => __( 'Section via hook', 'dima-take-action' ),
			'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'dima-take-action' ),
			'icon'   => 'el el-paper-clip',
			// Leave this as a blank section, no options just some intro text set above.
			'fields' => array()
		);

		return $sections;
	}
}

/**
 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
 * */
if ( ! function_exists( 'change_arguments' ) ) {
	function change_arguments( $args ) {
		return $args;
	}
}

/**
 * Filter hook for filtering the default value of any given field. Very useful in development mode.
 * */
if ( ! function_exists( 'change_defaults' ) ) {
	function change_defaults( $defaults ) {
		$defaults['str_replace'] = 'Testing filter hook!';

		return $defaults;
	}
}

/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'remove_demo' ) ) {
	function remove_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array(
				ReduxFrameworkPlugin::instance(),
				'plugin_metalinks'
			), null, 2 );

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}


add_action( 'admin_menu', 'dima_ta_remove_redux_menu', 12 );
function dima_ta_remove_redux_menu() {
	remove_submenu_page( 'tools.php', 'redux-about' );
}