<?php

/**
 * Locations
 *
 * @link    https://pluginsware.com
 * @since   1.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
 
// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ACADP_Public_Locations Class.
 *
 * @since 1.0.0
 */
class ACADP_Public_Locations {

	/**
	 * Get things going.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {		
		// Register shortcodes used by the location(s) page
		add_shortcode( "acadp_locations", array( $this, "run_shortcode_locations" ) );
		add_shortcode( "acadp_location", array( $this, "run_shortcode_location" ) );
	}
	
	/**
	 * Run the shortcode [acadp_locations].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_locations( $atts ) {	
		$shortcode = 'acadp_locations';
		
		$general_settings   = get_option( 'acadp_general_settings' );
		$listings_settings  = get_option( 'acadp_listings_settings' );
		$locations_settings = get_option( 'acadp_locations_settings' );		
		$has_location       = empty( $general_settings['has_location'] ) ? false : true;
		
		if ( $has_location ) {		
			// Enqueue style dependencies
			wp_enqueue_style( ACADP_PLUGIN_NAME );
			
			// ...		
			$attributes = shortcode_atts( 
				array(
					'parent'     => max( 0, $general_settings['base_location'] ),
					'columns'    => $locations_settings['columns'],
					'depth'      => $locations_settings['depth'],
					'orderby'    => $locations_settings['orderby'], 
					'order'      => $locations_settings['order'],					
					'show_count' => ! empty( $locations_settings['show_count'] ) ? 1 : 0,
					'pad_counts' => isset( $listings_settings['include_results_from'] ) && in_array( 'child_locations', $listings_settings['include_results_from'] ) ? true : false,
					'hide_empty' => ! empty( $locations_settings['hide_empty'] ) ? 1 : 0
				),
				$atts
  			);
		
			$terms = get_terms( 
				'acadp_locations',
				array(
					'parent'       => (int) $attributes['parent'],
					'orderby'      => sanitize_text_field( $attributes['orderby'] ), 
					'order'        => sanitize_text_field( $attributes['order'] ),
					'hide_empty'   => ! empty( $attributes['hide_empty'] ) ? 1 : 0,				
					'hierarchical' => ! empty( $attributes['hide_empty'] ) ? true : false,
				)
			);
			 
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {	
				// Backward compatibility (version < 1.7.3)	
				$locations_settings = $attributes;

				ob_start();
				$deprecated = array( 'template' => 'locations/acadp-public-locations-display.php' );
				include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/locations/layout-list.php', array( 'deprecated' => $deprecated ) );
				return ob_get_clean();			
			}		
		}
		
		return '<span>' . __( 'No locations found', 'advanced-classifieds-and-directory-pro' ) . '</span>';		
	}
	
	/**
	 * Run the shortcode [acadp_location].
	 *
	 * @since 1.0.0
	 * @param array $atts An associative array of attributes.
	 */
	public function run_shortcode_location( $atts ) {	
		$shortcode = 'acadp_location';
		
		$general_settings = get_option( 'acadp_general_settings' );
		$has_location = empty( $general_settings['has_location'] ) ? false : true;
		$term_slug = '';
		$term = '';
		
		if ( $has_location ) {
			$term_slug = get_query_var( 'acadp_location' );
			if ( '' == $term_slug && ! empty( $atts['id'] ) ) {		
				$term = get_term_by( 'id', (int) $atts['id'], 'acadp_locations' );
        		$term_slug = $term->slug;		
			} elseif ( '' != $term_slug ) {
				$term = get_term_by( 'slug', $term_slug, 'acadp_locations' );
			}
		}
		
		if ( '' != $term_slug ) {			
			$listings_settings = get_option( 'acadp_listings_settings' );
			$featured_listing_settings = get_option( 'acadp_featured_listing_settings' );

			$atts = shortcode_atts( array(
				'view'               => $listings_settings['default_view'],
				'location'           => 0,
				'category'           => 0,			
				'filterby'           => '',			
				'orderby'            => $listings_settings['orderby'],
				'order'              => $listings_settings['order'],
				'columns'            => $listings_settings['columns'],
				'listings_per_page'  => ! empty( $listings_settings['listings_per_page'] ) ? $listings_settings['listings_per_page'] : -1,
				'featured'           => 1,
				'header'             => 1,
				'show_excerpt'       => isset( $listings_settings['display_in_listing'] ) && in_array( 'excerpt', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_category'      => isset( $listings_settings['display_in_listing'] ) && in_array( 'category', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_location'      => isset( $listings_settings['display_in_listing'] ) && in_array( 'location', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_price'         => isset( $listings_settings['display_in_listing'] ) && in_array( 'price', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_date'          => isset( $listings_settings['display_in_listing'] ) && in_array( 'date', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_user'          => isset( $listings_settings['display_in_listing'] ) && in_array( 'user', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_views'         => isset( $listings_settings['display_in_listing'] ) && in_array( 'views', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'show_custom_fields' => isset( $listings_settings['display_in_listing'] ) && in_array( 'custom_fields', $listings_settings['display_in_listing'] ) ? 1 : 0,
				'pagination'         => 1
			), $atts );
			
			$view = acadp_get_listings_current_view_name( $atts['view'] );
			
			// Enqueue style dependencies	
			if ( 'map' == $view ) {
				wp_enqueue_style( ACADP_PLUGIN_NAME . '-markerclusterer' );				
			}

			wp_enqueue_style( ACADP_PLUGIN_NAME );
		
			// Enqueue script dependencies
			if ( 'map' == $view ) {
				wp_enqueue_script( ACADP_PLUGIN_NAME . '-markerclusterer' );
				wp_enqueue_script( ACADP_PLUGIN_NAME );
			}			
		
			// ...
			$pre_content = ! empty( $term ) && ! empty( $term->description ) ? nl2br( $term->description ) : '';

			$can_show_header           = empty( $listings_settings['display_in_header'] ) ? 0 : (int) $atts['header'];
			$can_show_listings_count   = $can_show_header && in_array( 'listings_count', $listings_settings['display_in_header'] ) ? true : false;
			$can_show_views_selector   = $can_show_header && in_array( 'views_selector', $listings_settings['display_in_header'] ) ? true : false;
			$can_show_orderby_dropdown = $can_show_header && in_array( 'orderby_dropdown', $listings_settings['display_in_header'] ) ? true : false;
		
			$can_show_excerpt       = (int) $atts['show_excerpt'];
			$can_show_location      = (int) $atts['show_location'];
			$can_show_category      = (int) $atts['show_category'];
			$can_show_price         = (int) $atts['show_price'];
			$can_show_date          = (int) $atts['show_date'];
			$can_show_user          = (int) $atts['show_user'];
			$can_show_views         = (int) $atts['show_views'];
			$can_show_custom_fields = (int) $atts['show_custom_fields'];		

			if ( ! $has_location ) {
				$can_show_location = 0;
			}

			$has_price = empty( $general_settings['has_price'] ) ? false : true;
			if ( ! $has_price ) {
				$can_show_price = 0;
			}	

			$can_show_images = empty( $general_settings['has_images'] ) ? false : true;
			
			$has_featured = apply_filters( 'acadp_has_featured', empty( $featured_listing_settings['enabled'] ) ? false : true );
			if ( $has_featured ) {
				$has_featured = (int) $atts['featured'];
			}
			
			$current_order = acadp_get_listings_current_order( $atts['orderby'] . '-' . $atts['order'] );

			$can_show_pagination = (int) $atts['pagination'];
		
			$span = 12;
			if ( $can_show_images ) $span = $span - 2;
			if ( $can_show_price ) $span = $span - 3;
			$span_middle = 'col-md-' . $span;

			// Define the query
			$paged = acadp_get_page_number();
			
			$args = array(				
				'post_type'      => 'acadp_listings',
				'post_status'    => 'publish',
				'posts_per_page' => (int) $atts['listings_per_page'],
				'paged'          => $paged,		
	  		);
			
			if ( $has_location ) {		
				$args['tax_query'] = array(
					array(
						'taxonomy'         => 'acadp_locations',
						'field'            => 'slug',
						'terms'            => $term_slug,
						'include_children' => isset( $listings_settings['include_results_from'] ) && in_array( 'child_locations', $listings_settings['include_results_from'] ) ? true : false,
					),
				);				
			}
			
			$meta_queries = array();
			
			if ( 'map' == $view ) {
				$meta_queries['hide_map'] = array(
					'key'     => 'hide_map',
					'value'   => 0,
					'compare' => '='
				);
			}
			
			if ( $has_featured ) {			
				if ( 'featured' == $atts['filterby'] ) {
					$meta_queries['featured'] = array(
						'key'     => 'featured',
						'value'   => 1,
						'compare' => '='
					);
				} else {
					$meta_queries['featured'] = array(
						'key'     => 'featured',
						'type'    => 'NUMERIC',
						'compare' => 'EXISTS',
					);
				}				
			}
			
			switch ( $current_order ) {
				case 'title-asc' :
					if ( $has_featured ) {
						$args['meta_key'] = 'featured';
						$args['orderby']  = array(
							'meta_value_num' => 'DESC',
							'title'          => 'ASC',
						);
					} else {
						$args['orderby'] = 'title';
						$args['order']   = 'ASC';
					};
					break;
				case 'title-desc' :
					if ( $has_featured ) {
						$args['meta_key'] = 'featured';
						$args['orderby']  = array(
							'meta_value_num' => 'DESC',
							'title'          => 'DESC',
						);
					} else {
						$args['orderby'] = 'title';
						$args['order']   = 'DESC';
					};
					break;
				case 'date-asc' :
					if ( $has_featured ) {
						$args['meta_key'] = 'featured';
						$args['orderby']  = array(
							'meta_value_num' => 'DESC',
							'date'           => 'ASC',
						);
					} else {
						$args['orderby'] = 'date';
						$args['order']   = 'ASC';
					};
					break;
				case 'date-desc' :
					if ( $has_featured ) {
						$args['meta_key'] = 'featured';
						$args['orderby']  = array(
							'meta_value_num' => 'DESC',
							'date'           => 'DESC',
						);
					} else {
						$args['orderby'] = 'date';
						$args['order']   = 'DESC';
					};
					break;
				case 'price-asc' :
					if ( $has_featured ) {
						$meta_queries['price'] = array(
							'key'     => 'price',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						);

						$args['orderby']  = array( 
							'featured' => 'DESC',
							'price'    => 'ASC',
						);
					} else {
						$args['meta_key'] = 'price';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'ASC';
					};
					break;
				case 'price-desc' :
					if ( $has_featured ) {
						$meta_queries['price'] = array(
							'key'     => 'price',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						);

						$args['orderby']  = array( 
							'featured' => 'DESC',
							'price'    => 'DESC',
						);
					} else {
						$args['meta_key'] = 'price';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'DESC';
					};
					break;
				case 'views-asc' :
					if ( $has_featured ) {
						$meta_queries['views'] = array(
							'key'     => 'views',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						);

						$args['orderby']  = array( 
							'featured' => 'DESC',
							'views'    => 'ASC',
						);
					} else {
						$args['meta_key'] = 'views';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'ASC';
					};
					break;
				case 'views-desc' :
					if ( $has_featured ) {
						$meta_queries['views'] = array(
							'key'     => 'views',
							'type'    => 'NUMERIC',
							'compare' => 'EXISTS',
						);

						$args['orderby']  = array( 
							'featured' => 'DESC',
							'views'    => 'DESC',
						);
					} else {
						$args['meta_key'] = 'views';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = 'DESC';
					};
					break;
				case 'rand-asc' :
				case 'rand-desc' :
					if ( $has_featured ) {
						$args['meta_key'] = 'featured';
						$args['orderby']  = 'meta_value_num rand';
					} else {
						$args['orderby'] = 'rand';
					};
					break;
			}

			$count_meta_queries = count( $meta_queries );
			if ( $count_meta_queries ) {
				$args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
			}
		
			$args = apply_filters( 'acadp_query_args', $args, $shortcode );
			$acadp_query = new WP_Query( $args );
			
			// Start the Loop
			global $post;
			
			// Process output
			if ( $acadp_query->have_posts() ) {
				ob_start();
				$deprecated = array( 'template' => "listings/acadp-public-listings-{$view}-display.php" );
				include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . "public/templates/listings/layout-{$view}.php", array( 'deprecated' => $deprecated ) );
				return ob_get_clean();			
			} elseif ( ! empty( $pre_content ) ) {			
				return '<span>' . $pre_content . '</span>';				
			}		
		}
		
		return '<span>' . __( 'No listings found', 'advanced-classifieds-and-directory-pro' ) . '</span>';		
	}

}
