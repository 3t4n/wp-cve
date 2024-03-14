<?php
/**
 * The file that defines the woo category slider post type.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post class to register the slider.
 */
class Woo_Category_Slider_Post_Type {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.1.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register Post Type for Category Slider
	 */
	public function register_post_type() {

		if ( post_type_exists( 'sp_wcslider' ) ) {
			return;
		}
		$capability = apply_filters( 'sp_wcslider_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		// Set the WordPress carousel post type labels.
		$labels = apply_filters(
			'woo_category_slider_post_type_labels',
			array(
				'name'               => esc_html__( 'All Category Sliders', 'woo-category-slider-grid' ),
				'singular_name'      => esc_html__( 'Category Slider', 'woo-category-slider-grid' ),
				'menu_name'          => esc_html__( 'Woo Cat Slider', 'woo-category-slider-grid' ),
				'add_new'            => esc_html__( 'Add New', 'woo-category-slider-grid' ),
				'add_new_item'       => esc_html__( 'Add New Slider', 'woo-category-slider-grid' ),
				'edit'               => esc_html__( 'Edit', 'woo-category-slider-grid' ),
				'edit_item'          => esc_html__( 'Edit Slider', 'woo-category-slider-grid' ),
				'new_item'           => esc_html__( 'New Slider', 'woo-category-slider-grid' ),
				'view'               => esc_html__( 'View Slider', 'woo-category-slider-grid' ),
				'view_item'          => esc_html__( 'View Slider', 'woo-category-slider-grid' ),
				'search_items'       => esc_html__( 'Search Slider', 'woo-category-slider-grid' ),
				'not_found'          => esc_html__( 'No Category Slider Found', 'woo-category-slider-grid' ),
				'not_found_in_trash' => esc_html__( 'No Category Slider Found in Trash', 'woo-category-slider-grid' ),
				'parent'             => esc_html__( 'Parent Category Slider', 'woo-category-slider-grid' ),
				'all_items'          => esc_html__( 'Manage Sliders', 'woo-category-slider-grid' ),
			)
		);
		// Base 64 encoded SVG image.
		$_menu_icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<?xml version="1.0" encoding="utf-8"?>
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 144 144" enable-background="new 0 0 144 144" xml:space="preserve">
		<g> <path fill="#A0A5AA" d="M108.6,60.7c1.8,2.6,5.5,3.1,8.1,1.3s3.1-5.5,1.3-8.1l-27.8-38c-1.8-2.6-5.5-3.1-8.1-1.3s-3.1,5.5-1.3,8.1
				L108.6,60.7z"/>
			<path fill="#A0A5AA" d="M27.2,62.1c2.6,1.8,6.3,1.3,8.1-1.3l27.5-38c1.8-2.6,1.3-6.3-1.3-8.1c-2.6-1.8-6.3-1.3-8.1,1.3L25.9,54
				C24.1,56.5,24.6,60.2,27.2,62.1z"/>
			<path fill="#A0A5AA" d="M130.1,69.7H13.9c-5.6,0-10.1-4.5-10.1-10.1l0,0c0-5.6,4.5-10.1,10.1-10.1h116.2c5.6,0,10.1,4.5,10.1,10.1
				l0,0C140.2,65.2,135.7,69.7,130.1,69.7z"/>
			<path fill="#A0A5AA" d="M14.7,69.7l14.6,54.9c1,3.7,4.1,6,7.8,6h70.1c3.6,0,6.7-2.3,7.8-6l14.3-54.9
				C129.3,69.7,14.7,69.7,14.7,69.7z M80.7,101.6c0,1.1-0.8,1.9-1.7,1.9H52.9c-0.9,0-1.7-0.9-1.7-1.9v-23c0-1.1,0.8-2,1.7-2H79
				c0.9,0,1.7,0.9,1.7,2V101.6z M92.9,101.6c0,1.1-0.8,1.9-1.7,1.9h-6.9V76.6h6.9c0.9,0,1.7,0.9,1.7,2V101.6z"/>
		</g>
		</svg> '
		);
		// Set the WordPress carousel post type arguments.
		$args = apply_filters(
			'woo_category_slider_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'has_archive'         => false,
				'publicaly_queryable' => false,
				'show_ui'             => $show_ui,
				'show_in_menu'        => true,
				'menu_icon'           => $_menu_icon,
				'hierarchical'        => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'menu_position'       => 25,
				'capability_type'     => 'post',
			)
		);

		register_post_type( 'sp_wcslider', $args );
	}
}
