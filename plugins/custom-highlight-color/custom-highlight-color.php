<?php
/**
 * Plugin Name: Custom Highlight Color
 * Plugin URI: http://celloexpressions.com/plugins/custom-highlight-color
 * Description: Customize the highlight color seen when selecting content on your site.
 * Version: 1.1
 * Author: Nick Halsey
 * Author URI: http://nick.halsey.co/
 * Tags: custom color, color, highlight, selection
 * Text Domain: custom-highlight-color
 * License: GPL

=====================================================================================
Copyright (C) 2019 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

add_action( 'wp_head', 'custom_highlight_color', 50 );
function custom_highlight_color() {
?>
	<style type="text/css" id="custom-highlight-color" <?php if ( is_customize_preview() ) { ?> data-color="<?php get_option( 'custom_highlight_color', '#ff0' ); ?>" <?php } ?>>
		<?php echo custom_highlight_color_css(); ?>
	</style>
<?php }

function custom_highlight_color_css() {
	require_once( 'color-calculations.php' );
	$background = get_option( 'custom_highlight_color', '#ff0' );
	if ( custom_highlight_color_contrast_ratio( $background, '#000' ) > custom_highlight_color_contrast_ratio( $background, '#fff' ) ) {
		$color = '#000';
	} else {
		$color = '#fff';
	}
	
	$css = '
		::-moz-selection {
			background: ' . $background . ';
			color: ' . $color . ';
		}
		::selection {
			background: ' . $background . ';
			color: ' . $color . ';
		}';
	return $css;
}

function custom_highlight_color_customize( $wp_customize ) {
	$wp_customize->add_setting( 'custom_highlight_color', array(
		'type' => 'option',
		'default' => '#ff0',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_highlight_color', array(
		'section' => 'colors',
		'label'   => __( 'Highlight Color', 'custom-highlight-color' ),
		'priority' => 50, // Aim to land after most theme-defined controls.
	) ) );

	// Partial refresh for better user experience (faster loading of changes).
	// This is a supplement to the initial postMessage setting update, handling PHP 
	// logic that's more complex than basic color swaps in the CSS (such as contrast ratios).
	$wp_customize->selective_refresh->add_partial( 'custom_highlight_color', array(
		'selector'        => '#custom-highlight-color',
		'settings'        => array( 'custom_highlight_color' ),
		'render_callback' => 'custom_highlight_color_css',
	) );
}
add_action( 'customize_register', 'custom_highlight_color_customize' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since 1.0
 *
 * @return void
 */
function custom_highlight_color_customize_preview_js() {
	wp_enqueue_script( 'custom_highlight_color_customizer', plugins_url( '/customizer.js', __FILE__ ), array( 'customize-preview' ), '20160702', true );
}
add_action( 'customize_preview_init', 'custom_highlight_color_customize_preview_js' );

