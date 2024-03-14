<?php
/**
 * Plugin Name: Slideshow
 * Plugin URI: http://devpress.com/plugins/slideshow
 * Description: Allows users to show a slideshow of image attachments using the [slideshow] shortcode.
 * Version: 0.1
 * Author: DevPress
 * Author URI: http://devpress.com
 *
 * The slideshow plugin allows users to enter the [slideshow] shortcode within the post (or any post type) editor
 * to display the post's image attachments.
 *
 * @copyright 2010
 * @version 0.1
 * @author DevPress
 * @link http://devpress.com/plugins/slideshow
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Slideshow
 */

/* Set up the plugin. */
add_action( 'plugins_loaded', 'slideshow_setup' );

/**
 * Slideshow plugin setup function.
 *
 * @since 0.1.0
 */
function slideshow_setup() {

	/* Load translations on the frontend. */
	if ( !is_admin() )
		load_plugin_textdomain( 'slideshow', false, 'slideshow/languages' );

	/* Get the plugin directory URI. */
	define( 'SLIDESHOW_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

	/* Register the shortcode. */
	add_action( 'init', 'slideshow_register_shortcodes' );

	/* Register the stylesheet. */
	add_action( 'template_redirect', 'slideshow_register_stylesheets' );

	/* Register the JavaScript. */
	add_action( 'template_redirect', 'slideshow_register_javascript' );
}

/**
 * Adds the slideshow stylesheet.
 *
 * @since 0.1.0
 */
function slideshow_register_stylesheets() {
	wp_enqueue_style( 'slideshow', SLIDESHOW_URI . 'slideshow.css', false, 0.1, 'all' );
}

/**
 * Adds the slideshow JavaScript.
 *
 * @since 0.1.0
 */
function slideshow_register_javascript() {
	wp_enqueue_script( 'slideshow', SLIDESHOW_URI . 'slideshow.js', array( 'jquery' ), 0.1, true );
}

/**
 * Registers new shortcodes.
 *
 * @since 0.1.0
 */
function slideshow_register_shortcodes() {
	add_shortcode( 'slideshow', 'slideshow_shortcode' );
}

/**
 * Slideshow shortcode.
 *
 * @since 0.1.0
 */
function slideshow_shortcode( $attr ) {
	global $post;

	/* Set up the defaults for the slideshow shortcode. */
	$defaults = array(
		'order' => 'ASC',
		'orderby' => 'menu_order ID',
		'id' => $post->ID,
		'size' => 'large',
		'include' => '',
		'exclude' => '',
		'numberposts' => -1,
	);
	$attr = shortcode_atts( $defaults, $attr );

	/* Allow users to overwrite the default args. */
	extract( apply_filters( 'slideshow_shortcode_args', $attr ) );

	/* Arguments for get_children(). */
	$children = array(
		'post_parent' => intval( $id ),
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'order' => $order,
		'orderby' => $orderby,
		'exclude' => absint( $exclude ),
		'include' => absint( $include ),
		'numberposts' => intval( $numberposts ),
	);

	/* Get image attachments. If none, return. */
	$attachments = get_children( $children );

	if ( empty( $attachments ) )
		return '';

	/* If is feed, leave the default WP settings. We're only worried about on-site presentation. */
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link( $id, $size, true ) . "\n";
		return $output;
	}

	$slideshow = '<div class="slideshow-set"><div class="slideshow-items">';

	$i = 0;

	foreach ( $attachments as $attachment ) {

		/* Open item. */
		$slideshow .= '<div class="slideshow-item item item-' . ++$i . '">';

		/* Get image. */
		$slideshow .= wp_get_attachment_link( $attachment->ID, $size, true, false );

		/* Check for caption. */
		if ( !empty( $attachment->post_excerpt ) )
			$caption = $attachment->post_excerpt;
		elseif ( !empty( $attachment->post_content ) )
			$caption = $attachment->post_content;
		else
			$caption = '';

		if ( !empty( $caption ) ) {
			$slideshow .= '<div class="slideshow-caption">';
			$slideshow .= '<a class="slideshow-caption-control">' . __( 'Caption', 'slideshow' ) . '</a>';
			$slideshow .= '<div class="slideshow-caption-text">' . $caption . '</div>';
			$slideshow .= '</div>';
		}

		$slideshow .= '</div>';
	}

	$slideshow .= '</div><div class="slideshow-controls">';

		$slideshow .= '<div class="slideshow-pager"></div>';
		$slideshow .= '<div class="slideshow-nav">';
			$slideshow .= '<a class="slider-prev">' . __( 'Previous', 'slideshow' ) . '</a>';
			$slideshow .= '<a class="slider-next">' . __( 'Next', 'slideshow' ) . '</a>';
		$slideshow .= '</div>';

	$slideshow .= '</div>';

	$slideshow .= '</div><!-- End slideshow. -->';

	return apply_filters( 'slideshow_shortcode', $slideshow );
}

?>