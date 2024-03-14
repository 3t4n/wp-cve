<?php
/**
 * Plugin Name: Lana Breadcrumb
 * Plugin URI: http://lana.codes/lana-product/lana-breadcrumb/
 * Description: Indicate the current page's location within a navigational hierarchy.
 * Version: 1.1.0
 * Author: Lana Codes
 * Author URI: http://lana.codes/
 * Text Domain: lana-breadcrumb
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die();
define( 'LANA_BREADCRUMB_VERSION', '1.1.0' );
define( 'LANA_BREADCRUMB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'LANA_BREADCRUMB_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Language
 * load
 */
load_plugin_textdomain( 'lana-breadcrumb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Styles
 * load in plugin
 */
function lana_breadcrumb_bootstrap_styles() {

	if ( ! wp_style_is( 'bootstrap' ) ) {

		wp_register_style( 'lana-breadcrumb', LANA_BREADCRUMB_DIR_URL . '/assets/css/lana-breadcrumb.css', array(), LANA_BREADCRUMB_VERSION );
		wp_enqueue_style( 'lana-breadcrumb' );
	}
}

add_action( 'wp_enqueue_scripts', 'lana_breadcrumb_bootstrap_styles', 1001 );

/**
 * Lana Breadcrumb
 * with Bootstrap
 */
function lana_breadcrumb() {
	global $post;

	/**
	 * Html output
	 */
	$output = '';

	/**
	 * Breadcrumb html
	 * tags
	 */
	$breadcrumb_before              = '<ol class="breadcrumb lana-breadcrumb">';
	$breadcrumb_after               = '</ol>';
	$breadcrumb_element_before      = '<li class="breadcrumb-item">';
	$breadcrumb_element_after       = '</li>';
	$breadcrumb_element_link_before = '<a href="%s">';
	$breadcrumb_element_link_after  = '</a>';
	$breadcrumb_elements            = array();

	/**
	 * Breadcrumb
	 * home element
	 */
	$breadcrumb_elements['home'] = array(
		'href' => home_url( '/' ),
		'text' => get_bloginfo( 'name' ),
	);

	/**
	 * Page
	 * parents
	 */
	if ( is_page() ) {
		$ancestors = get_post_ancestors( $post );
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );

			foreach ( $ancestors as $ancestor ) {
				$breadcrumb_elements[ 'pages-' . $ancestor ] = array(
					'href' => get_permalink( $ancestor ),
					'text' => get_the_title( $ancestor ),
				);
			}
		}
	}

	/**
	 * 404
	 */
	if ( is_404() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => __( 'Page not found', 'lana-breadcrumb' ),
		);
	}

	/**
	 * Search
	 */
	if ( is_search() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => sprintf( __( 'Search Results for &#8220;%s&#8221;', 'lana-breadcrumb' ), get_search_query() ),
		);
	}

	/**
	 * Singular
	 */
	if ( is_singular() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => get_the_title(),
		);
	}

	/**
	 * Home
	 */
	if ( is_home() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => single_post_title( '', false ),
		);
	}

	/**
	 * Tax
	 */
	if ( is_tax() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => single_term_title( '', false ),
		);
	}

	/**
	 * Category
	 */
	if ( is_category() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => single_cat_title( '', false ),
		);
	}

	/**
	 * Tag
	 */
	if ( is_tag() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => single_tag_title( '', false ),
		);
	}

	/**
	 * Date
	 */
	if ( is_date() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => get_the_archive_title(),
		);
	}

	/**
	 * Post type archive
	 */
	if ( is_post_type_archive() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => get_the_archive_title(),
		);
	}

	/**
	 * Post format
	 * aside, video, gallery etc.
	 */
	if ( is_tax( 'post_format' ) ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => get_the_archive_title(),
		);
	}

	/**
	 * Author
	 */
	if ( is_author() ) {
		$breadcrumb_elements['active'] = array(
			'href' => '',
			'text' => get_the_author_meta( 'display_name' ),
		);
	}

	/**
	 * Front Page
	 * remove active
	 */
	if ( is_front_page() ) {
		unset( $breadcrumb_elements['active'] );
	}

	$breadcrumb_elements = apply_filters( 'lana_breadcrumb_elements', $breadcrumb_elements );

	/**
	 * Generate
	 * output
	 */
	$output .= $breadcrumb_before;
	if ( ! empty( $breadcrumb_elements ) ) {
		foreach ( $breadcrumb_elements as $breadcrumb_element ) {

			$output .= $breadcrumb_element_before;

			if ( ! empty( $breadcrumb_element['href'] ) ) {
				$output .= sprintf( $breadcrumb_element_link_before, $breadcrumb_element['href'] );
			}

			$output .= $breadcrumb_element['text'];

			if ( ! empty( $breadcrumb_element['href'] ) ) {
				$output .= $breadcrumb_element_link_after;
			}

			$output .= $breadcrumb_element_after;
		}
	}
	$output .= $breadcrumb_after;

	return $output;
}

/**
 * Lana Breadcrumb Shortcode
 * @return string
 */
function lana_breadcrumb_shortcode() {
	return lana_breadcrumb();
}

add_shortcode( 'lana_breadcrumb', 'lana_breadcrumb_shortcode' );

/**
 * Init Widget
 */
add_action( 'widgets_init', function () {
	include_once LANA_BREADCRUMB_DIR_PATH . '/includes/class-lana-breadcrumb-widget.php';
	register_widget( 'Lana_Breadcrumb_Widget' );
} );