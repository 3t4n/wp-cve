<?php
/*
Plugin Name: Simple Spoiler
Plugin URI: https://webliberty.ru/simple-spoiler/
Description: The plugin allows to create simple spoilers with shortcode.
Version: 1.2
Author: Webliberty
Author URI: https://webliberty.ru/
Text Domain: simple-spoiler
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'admin_menu', 'simple_spoiler_menu' );
function simple_spoiler_menu() {
	add_menu_page( __( 'Plugin Simple Spoiler', 'simple-spoiler' ), 'Simple Spoiler', 'manage_options', 'simple-spoiler', 'simple_spoiler_menu_output' );
}

function simple_spoiler_menu_output() {
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'option_group' );
				do_settings_sections( 'simple_spoiler_page' );
				submit_button();
			?>
		
		</form>
	</div>
	<?php
}

add_action( 'admin_notices', 'simple_spoiler_notice' );
function simple_spoiler_notice() {
	if (isset($_GET['settings-updated'])) {
		?>
		<div class="updated">
			<p><?php _e( 'Settings updated', 'simple-spoiler' ); ?></p>
		</div>
		<?php 
	}
}	

add_action( 'admin_init', 'simple_spoiler_settings' );
function simple_spoiler_settings() {
	register_setting( 'option_group', 'simple_spoiler_bg_wrap', 'sanitize_callback' );
	register_setting( 'option_group', 'simple_spoiler_bg_body', 'sanitize_callback' );
	register_setting( 'option_group', 'simple_spoiler_br_color', 'sanitize_callback' );

	add_settings_section( 'simple_spoiler_section', __( 'Color settings', 'simple-spoiler' ), '', 'simple_spoiler_page' ); 

	add_settings_field( 'spoiler_wrap', __( 'Background spoiler headline', 'simple-spoiler' ), 'spoiler_bg_wrap', 'simple_spoiler_page', 'simple_spoiler_section' );
	add_settings_field( 'spoiler_body', __( 'Background spoiler body', 'simple-spoiler' ), 'spoiler_bg_body', 'simple_spoiler_page', 'simple_spoiler_section' );
	add_settings_field( 'spoiler_border', __( 'Spoiler border color', 'simple-spoiler' ), 'spoiler_br_color', 'simple_spoiler_page', 'simple_spoiler_section' );
}

function spoiler_bg_wrap() {
	$val = get_option( 'simple_spoiler_bg_wrap' );
	$val = $val ? $val['input'] : '#f1f1f1';
	?>
	<input type="text" name="simple_spoiler_bg_wrap[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function spoiler_bg_body() {
	$val = get_option('simple_spoiler_bg_body');
	$val = $val ? $val['input'] : '#fbfbfb';
	?>
	<input type="text" name="simple_spoiler_bg_body[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function spoiler_br_color() {
	$val = get_option('simple_spoiler_br_color');
	$val = $val ? $val['input'] : '#dddddd';
	?>
	<input type="text" name="simple_spoiler_br_color[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

function sanitize_callback( $options ) { 
	foreach( $options as $name => & $val ) {
		if( $name == 'input' )
			$val = strip_tags( $val );
	}
	return $options;
}

function simple_spoiler_shortcode($atts, $content) {
	if ( ! isset($atts['title']) ) {
		$sp_name = __( 'Spoiler', 'simple-spoiler' );
	} else {
		$sp_name = $atts['title'];
	}
	return '<div class="spoiler-wrap">
				<div class="spoiler-head folded">'.$sp_name.'</div>
				<div class="spoiler-body">'.$content.'</div>
			</div>';
}
add_shortcode( 'spoiler', 'simple_spoiler_shortcode' );
add_filter( 'comment_text', 'do_shortcode' );

add_action( 'wp_enqueue_scripts', 'simple_spoiler_head' );
function simple_spoiler_head() {
	global $post;
	wp_register_style( 'simple_spoiler_style', plugins_url( 'css/simple-spoiler.min.css', __FILE__ ), null, '1.2' );
	wp_register_script( 'simple_spoiler_script', plugins_url( 'js/simple-spoiler.min.js', __FILE__ ), array( 'jquery' ), '1.2', true );
		wp_enqueue_style( 'simple_spoiler_style' );
		wp_enqueue_script( 'simple_spoiler_script' );
}

add_action( 'wp_head', 'simple_spoiler_css' );
function simple_spoiler_css() {
	global $post;
		$bg_wrap = get_option( 'simple_spoiler_bg_wrap' );
		$bg_body = get_option( 'simple_spoiler_bg_body' );
		$br_color = get_option( 'simple_spoiler_br_color' );

		$spoiler_wrap = empty( $bg_wrap['input'] ) ? '#f1f1f1' : $bg_wrap['input'];
		$spoiler_body = empty( $bg_body['input'] ) ? '#fbfbfb' : $bg_body['input'];
		$spoiler_border = empty( $br_color['input'] ) ? '#dddddd' : $br_color['input'];

		?>
		<style type="text/css">
			.spoiler-head {background: <?php echo esc_attr($spoiler_wrap); ?>; border: 1px solid <?php echo esc_attr($spoiler_border); ?>;}
			.spoiler-body {background: <?php echo esc_attr($spoiler_body); ?>; border-width: 0 1px 1px 1px; border-style: solid; border-color: <?php echo esc_attr($spoiler_border); ?>;}
		</style>
		<?php
}