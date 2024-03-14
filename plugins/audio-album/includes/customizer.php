<?php


/**
* Exit if accessed directly
*
*/
if ( ! defined( 'ABSPATH' ) ) exit;


/**
* Customizer
*
*/
function cc_audioalbum_customize_register( $wp_customize ){

	/**
	* Customizer Section
	*
	*/
	$wp_customize->add_section(
		'cc_audioalbum_section', array(
			'priority'			=> '80',
			'title'				=> __( 'Audio Album', 'audio-album' ),
			'capability'		=> 'edit_theme_options',
			'description'		=>  __( '', 'audio-album' ),
		)
	);

	/**
	* Album Background
	*
	*/
	$wp_customize->add_setting( 'cc_audioalbum[bgcol]', array(
			'default'			=> '#434a54',
			'sanitize_callback'	=> 'sanitize_hex_color',
			'capability'		=> 'edit_theme_options',
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'bgcol', array(
			'label'			=> __( 'Album background', 'audio-album' ),
			'description'	=> __( 'The album background', 'audio-album' ),
			'section'		=> 'cc_audioalbum_section',
			'priority'		=> '20',
			'settings'		=> 'cc_audioalbum[bgcol]',
			)
		)
	);

	/**
	* Player
	*
	*/
	$wp_customize->add_setting( 'cc_audioalbum[playr]', array(
			'default'			=> '#2e3137',
			'sanitize_callback'	=> 'sanitize_hex_color',
			'capability'		=> 'edit_theme_options',
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'playr', array(
			'label'			=> __( 'Player Background', 'audio-album' ),
			'description'	=> __( 'The background of the audio player', 'audio-album' ),
			'section'		=> 'cc_audioalbum_section',
			'priority'		=> '30',
			'settings'		=> 'cc_audioalbum[playr]',
			)
		)
	);


	/**
	* Text & Button colour
	*
	*/
	$wp_customize->add_setting( 'cc_audioalbum[txtbt]', array(
			'default'			=> '#ffffff',
			'sanitize_callback'	=> 'sanitize_hex_color',
			'capability'		=> 'edit_theme_options',
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'txtbt', array(
			'label'			=> __( 'Text &amp; buttons', 'audio-album' ),
			'description'	=> __( 'All text plus the play/pause &amp; mute/unmute buttons', 'audio-album' ),
			'section'		=> 'cc_audioalbum_section',
			'priority'		=> '40',
			'settings'		=> 'cc_audioalbum[txtbt]',
			)
		)
	);


	/**
	* Time & Volume Bar
	*
	*/
	$wp_customize->add_setting( 'cc_audioalbum[tvcol]', array(
			'default'			=> '#ffffff',
			'sanitize_callback'	=> 'sanitize_hex_color',
			'capability'		=> 'edit_theme_options',
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'tvcol', array(
			'label'			=> __( 'Time &amp; volume bars', 'audio-album' ),
			'description'	=> __( 'The time and volume indicator bar, the play/pause &amp; mute/unmute buttons on hover', 'audio-album' ),
			'section'		=> 'cc_audioalbum_section',
			'priority'		=> '50',
			'settings'		=> 'cc_audioalbum[tvcol]',
			)
		)
	);


	/**
	* Manual CSS
	*
	*/
	$wp_customize->add_setting( 'cc_audioalbum[manualcss]',
		array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'cc_audioalbum_sanitize_checkbox',
			'type'				=> 'option',
			'transport'			=> 'refresh',
		)
	);

	$wp_customize->add_control( 'manualcss', array(
		'label'				=> __( 'Manual CSS', 'audio-album' ),
		'description'		=> __( 'Remove the plugin\'s styles', 'audio-album' ),
		'section'			=> 'cc_audioalbum_section',
		'settings'			=> 'cc_audioalbum[manualcss]',
		'type'				=> 'checkbox',
		'priority'			=> '100',
		)
	);

}

add_action( 'customize_register', 'cc_audioalbum_customize_register' );


/**
* Set up the customizer's color picker with custom options
*
*/
function cc_audioalbum_customizer_colourpicker() {

	?>
	<script>
	jQuery( document ).ready(function($){
		$.wp.wpColorPicker.prototype.options = {
			color: true,
			mode: 'hsl',
			controls: {horiz: "l", vert: "s", strip: "h"},
			hide: true,
			border: false,
			target: false,
			width: 260,
			rows: 2,
			palettes: [ '#434a54','#2c3138','#FFCE54','#ED5565','#FC6E51','#A0D468','#4FC1E9','#5D9CEC','#8067B7','#EC87C0','#8E8271','#FFFFFF' ]
		};
	});
	</script>
	<?php
}

add_action('customize_controls_print_footer_scripts','cc_audioalbum_customizer_colourpicker' );


/**
* When the 'Manual CSS' control is selected, hide the other controls
*
*/
function cc_audioalbum_customizer_showhide() {
	wp_enqueue_script( 'cc_audioalbum_showhide', plugin_dir_url( dirname(__FILE__) ) . 'js/customizer-showhide.js', array( 'jquery', 'customize-controls' ), CC_AUDIOALBUM_VERSION, true );
}

add_action( 'customize_controls_enqueue_scripts', 'cc_audioalbum_customizer_showhide' );


/**
* Bind JS handlers to enable instant preview of customizer changes
*
*/
function cc_audioalbum_preview(){
	wp_enqueue_script( 'cc_audioalbum_preview', plugin_dir_url( dirname(__FILE__) ) . 'js/customizer-preview.js', array( 'jquery', 'customize-preview' ), CC_AUDIOALBUM_VERSION, true );
}

add_action( 'customize_preview_init', 'cc_audioalbum_preview' );