<?php

/**
 * Blocks
 *
 * @link    https://pluginsware.com
 * @since   1.6.1
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ACADP_Blocks class.
 *
 * @since 1.6.1
 */
class ACADP_Blocks {

	/**
	 * Register our custom block category.
	 *
	 * @since  1.6.1
	 * @param  array $categories Default Gutenberg block categories.
	 * @return array             Modified Gutenberg block categories.
	 */
	public function block_categories( $categories ) {		
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'advanced-classifieds-and-directory-pro',
					'title' => __( 'Advanced Classifieds and Directory Pro', 'advanced-classifieds-and-directory-pro' ),
				),
			)
		);		
	}

	/**
	 * Enqueue block assets for backend editor.
	 *
	 * @since 1.6.1
	 */
	public function enqueue_block_editor_assets() {
		$general_settings    = get_option( 'acadp_general_settings' );	
		$listings_settings   = get_option( 'acadp_listings_settings' );
		$locations_settings  = get_option( 'acadp_locations_settings' ); 
		$categories_settings = get_option( 'acadp_categories_settings' );			
	
		$editor_properties = array(
			'base_location' => max( 0, $general_settings['base_location'] ),
			'listings' => array(
				'view'               => $listings_settings['default_view'],
				'category'           => 0,
				'location'           => 0,					
				'columns'            => $listings_settings['columns'],
				'listings_per_page'  => ! empty( $listings_settings['listings_per_page'] ) ? $listings_settings['listings_per_page'] : -1,
				'filterby'           => '',
				'orderby'            => $listings_settings['orderby'],
				'order'              => $listings_settings['order'],
				'featured'           => true,
				'header'             => true,
				'show_excerpt'       => isset( $listings_settings['display_in_listing'] ) && in_array( 'excerpt', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_category'      => isset( $listings_settings['display_in_listing'] ) && in_array( 'category', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_location'      => isset( $listings_settings['display_in_listing'] ) && in_array( 'location', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_price'         => isset( $listings_settings['display_in_listing'] ) && in_array( 'price', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_date'          => isset( $listings_settings['display_in_listing'] ) && in_array( 'date', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_user'          => isset( $listings_settings['display_in_listing'] ) && in_array( 'user', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_views'         => isset( $listings_settings['display_in_listing'] ) && in_array( 'views', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_custom_fields' => isset( $listings_settings['display_in_listing'] ) && in_array( 'custom_fields', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'pagination'         => true,
			),
			'locations' => array(
				'parent'     => max( 0, $general_settings['base_location'] ),
				'columns'    => $locations_settings['columns'],
				'depth'      => $locations_settings['depth'],
				'orderby'    => $locations_settings['orderby'],
				'order'      => $locations_settings['order'],
				'show_count' => empty( $locations_settings['show_count'] ) ? 0 : 1,
				'hide_empty' => empty( $locations_settings['hide_empty'] ) ? 0 : 1
			),
			'categories' => array(				
				'view'       => $categories_settings['view'],
				'parent'     => 0,
				'columns'    => $categories_settings['columns'],
				'depth'      => $categories_settings['depth'],
				'orderby'    => $categories_settings['orderby'],
				'order'      => $categories_settings['order'],
				'show_count' => empty( $categories_settings['show_count'] ) ? 0 : 1,
				'hide_empty' => empty( $categories_settings['hide_empty'] ) ? 0 : 1
			),
			'search_form' => array(
				'style'         => 'inline',
				'keyword'       => 1,
				'location'      => empty( $general_settings['has_location'] ) ? 0 : 1,
				'category'      => 1,
				'custom_fields' => 1,
				'price'         => empty( $general_settings['has_price'] ) ? 0 : 1  
			)
		);

		wp_localize_script( 
			'wp-block-editor', 
			'acadp_blocks', 
			$editor_properties
		);	
		
		do_action( 'acadp_enqueue_block_editor_assets' );
	}	

	/**
	 * Register our custom blocks.
	 * 
	 * @since 1.6.1
	 */
	public function register_block_types() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return false;
		}

		$this->register_locations_block();
		$this->register_categories_block();
		$this->register_listings_block();
		$this->register_search_form_block();
		$this->register_listing_form_block();
	}

	/**
	 * Register the locations block.
	 *
	 * @since 2.0.0
	 */
	private function register_locations_block() {
		$attributes = array(
			'parent' => array(
				'type' => 'number'
			),
			'columns' => array(
				'type' => 'number'
			),
			'depth' => array(
				'type' => 'number'
			),
			'orderby' => array(
				'type' => 'string'
			),
			'order' => array(
				'type' => 'string'
			),
			'show_count' => array(
				'type' => 'boolean'
			),
			'hide_empty' => array(
				'type' => 'boolean'
			)				
		);

		register_block_type( __DIR__ . '/build/locations', array(
			'attributes' => $attributes,
			'render_callback' => array( $this, 'render_locations_block' )
		) );
	}

	/**
	 * Register the categories block.
	 *
	 * @since 2.0.0
	 */
	private function register_categories_block() {
		$attributes = array(
			'view' => array(
				'type' => 'string'
			),
			'parent' => array(
				'type' => 'number'
			),			
			'columns' => array(
				'type' => 'number'
			),
			'depth' => array(
				'type' => 'number'
			),
			'orderby' => array(
				'type' => 'string'
			),
			'order' => array(
				'type' => 'string'
			),
			'show_count' => array(
				'type' => 'boolean'
			),
			'hide_empty' => array(
				'type' => 'boolean'
			)				
		);

		register_block_type( __DIR__ . '/build/categories', array(
			'attributes' => $attributes,
			'render_callback' => array( $this, 'render_categories_block' )
		) );
	}

	/**
	 * Register the listings block.
	 *
	 * @since 2.0.0
	 */
	private function register_listings_block() {
		$attributes = array(
			'view' => array(
				'type' => 'string'
			),
			'location' => array(
				'type' => 'number'
			),
			'category' => array(
				'type' => 'number'
			),	
			'columns' => array(
				'type' => 'number'
			),
			'listings_per_page' => array(
				'type' => 'number'
			),							
			'filterby' => array(
				'type' => 'string'
			),
			'orderby' => array(
				'type' => 'string'
			),
			'order' => array(
				'type' => 'string'
			),			
			'featured' => array(
				'type' => 'boolean'
			),
			'header' => array(
				'type' => 'boolean'
			),
			'show_excerpt' => array(
				'type' => 'boolean'
			),
			'show_category' => array(
				'type' => 'boolean'
			),
			'show_location' => array(
				'type' => 'boolean'
			),
			'show_price' => array(
				'type' => 'boolean'
			),
			'show_date' => array(
				'type' => 'boolean'
			),
			'show_user' => array(
				'type' => 'boolean'
			),
			'show_views' => array(
				'type' => 'boolean'
			),
			'show_custom_fields' => array(
				'type' => 'boolean'
			),
			'pagination' => array(
				'type' => 'boolean'
			)				
		);

		register_block_type( __DIR__ . '/build/listings', array(
			'attributes' => $attributes,
			'render_callback' => array( $this, 'render_listings_block' ),
		) );
	}

	/**
	 * Register the search form block.
	 *
	 * @since 2.0.0
	 */
	private function register_search_form_block() {
		$attributes = array(
			'style' => array(
				'type' => 'string'
			),
			'keyword' => array(
				'type' => 'boolean'
			),
			'location' => array(
				'type' => 'boolean'
			),
			'category' => array(
				'type' => 'boolean'
			),
			'custom_fields' => array(
				'type' => 'boolean'
			),
			'price' => array(
				'type' => 'boolean'
			)
		);

		register_block_type( __DIR__ . '/build/search-form', array(				
			'attributes' => $attributes,
			'render_callback' => array( $this, 'render_search_form_block' ),
		) );
	}

	/**
	 * Register the listing form block.
	 *
	 * @since 2.0.0
	 */
	private function register_listing_form_block() {
		register_block_type( __DIR__ . '/build/listing-form', array(				
			'render_callback' => array( $this, 'render_listing_form_block' ),
		) );
	}

	/**
	 * Render the locations block frontend.
	 *
	 * @since  1.6.1
	 * @return string Locations block output.
	 */
	public function render_locations_block() {
		$output  = '<div ' . get_block_wrapper_attributes() . '>';
		$output .= do_shortcode( '[acadp_locations]' );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render the categories block frontend.
	 *
	 * @since  1.6.1
	 * @return string Categories block output.
	 */
	public function render_categories_block() {
		$output  = '<div ' . get_block_wrapper_attributes() . '>';
		$output .= do_shortcode( '[acadp_categories]' );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render the listings block frontend.
	 *
	 * @since  1.6.1
	 * @param  array  $atts An associative array of attributes.
	 * @return string       Listings block output.
	 */
	public function render_listings_block( $atts ) {
		$output  = '<div ' . get_block_wrapper_attributes() . '>';
		$output .= do_shortcode( '[acadp_listings ' . $this->build_shortcode_attributes( $atts ) . ']' );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render the search form block frontend.
	 *
	 * @since  1.6.1
	 * @param  array  $atts An associative array of attributes.
	 * @return string       Search form block output.
	 */
	public function render_search_form_block( $atts ) {
		$output  = '<div ' . get_block_wrapper_attributes() . '>';
		$output .= do_shortcode( '[acadp_search_form ' . $this->build_shortcode_attributes( $atts ) . ']' );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render the listing form block frontend.
	 *
	 * @since  1.6.1
	 * @return string Search form block output.
	 */
	public function render_listing_form_block() {
		$output  = '<div ' . get_block_wrapper_attributes() . '>';
		$output .= do_shortcode( '[acadp_listing_form]' );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Build shortcode attributes string.
	 * 
	 * @since  1.6.1
	 * @access private
	 * @param  array   $atts Array of attributes.
	 * @return string        Shortcode attributes string.
	 */
	private function build_shortcode_attributes( $atts ) {
		$attributes = array();
		
		foreach ( $atts as $key => $value ) {
			if ( is_null( $value ) ) {
				continue;
			}

			if ( is_bool( $value ) ) {
				$value = ( true === $value ) ? 1 : 0;
			}

			if ( is_array( $value ) ) {
				$value = implode( ',', $value );
			}

			$attributes[] = sprintf( '%s="%s"', $key, $value );
		}
		
		return implode( ' ', $attributes );
	}

}
