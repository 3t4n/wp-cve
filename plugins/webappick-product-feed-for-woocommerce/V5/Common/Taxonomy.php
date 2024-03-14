<?php

namespace CTXFeed\V5\Common;

use ArrayObject;
use FilterIterator;
use Walker_Category_Checklist;

class Taxonomy {
	public function __construct() {
		add_action( 'init', [ $this, 'register_custom_taxonomy' ] );
		add_filter( 'wp_terms_checklist_args', [ $this, 'brand_term_radio_checklist' ] );
	}
	
	/**
	 * @return void
	 */
	public function register_custom_taxonomy() {
		$custom_fields            = woo_feed_product_custom_fields();
		$custom_taxonomies_filter = new TaxonomyFilter( $custom_fields );
		$custom_taxonomies        = iterator_to_array( $custom_taxonomies_filter );
		
		$settings = woo_feed_get_options( 'all' );
		if ( isset( $settings['woo_feed_taxonomy'], $settings['woo_feed_identifier'] ) ) {
			$custom_attributes = array_merge( $settings['woo_feed_taxonomy'], $settings['woo_feed_identifier'] );
		} else {
			$custom_attributes = $settings['woo_feed_taxonomy'];
		}
		
		if ( isset( $custom_attributes['brand'] ) && 'enable' === $custom_attributes['brand'] ) {
			if ( ! empty( $custom_taxonomies ) ) {
				foreach ( $custom_taxonomies as $key => $value ) {
					$taxonomy_name = esc_html( $value[0] );
					
					$labels       = array(
						'name'                       => $taxonomy_name . ' ' . __( 'by CTX Feed', 'woo-feed' ),
						'singular_name'              => $taxonomy_name,
						'menu_name'                  => $taxonomy_name . 's ' . __( 'by CTX Feed', 'woo-feed' ),
						'all_items'                  => __( 'All', 'woo-feed' ) . ' ' . $taxonomy_name . 's',
						'parent_item'                => __( 'Parent', 'woo-feed' ) . $taxonomy_name,
						'parent_item_colon'          => __( 'Parent:', 'woo-feed' ) . $taxonomy_name . ':',
						'new_item_name'              => __( 'New', 'woo-feed' ) . ' ' . $taxonomy_name . ' ' . __( 'Name', 'woo-feed' ),
						'add_new_item'               => __( 'Add New', 'woo-feed' ) . ' ' . $taxonomy_name,
						'edit_item'                  => __( 'Edit', 'woo-feed' ) . ' ' . $taxonomy_name,
						'update_item'                => __( 'Update', 'woo-feed' ) . ' ' . $taxonomy_name,
						'separate_items_with_commas' => __( 'Separate', 'woo-feed' ) . ' ' . $taxonomy_name . ' ' . __( 'with commas', 'woo-feed' ),
						'search_items'               => __( 'Search', 'woo-feed' ) . ' ' . $taxonomy_name,
						'add_or_remove_items'        => __( 'Add or remove', 'woo-feed' ) . ' ' . $taxonomy_name,
						'choose_from_most_used'      => __( 'Choose from the most used', 'woo-feed' ) . ' ' . $taxonomy_name . 's',
					);
					$args         = array(
						'labels'             => $labels,
						'hierarchical'       => true,
						'public'             => true,
						'show_ui'            => true,
						'show_admin_column'  => false,
						'show_in_rest'       => true,
						'show_in_nav_menus'  => true,
						'show_tagcloud'      => true,
						'show_in_quick_edit' => false,
					);
					$taxonomy_key = sprintf( 'woo-feed-%s', strtolower( $key ) );
					
					register_taxonomy( $taxonomy_key, 'product', $args );
				}
			}
		}
		
	}
	
	/**
	 * Use radio inputs product brand taxonomies
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function brand_term_radio_checklist( $args ) {
		if ( ! empty( $args['taxonomy'] ) && 'woo-feed-brand' === $args['taxonomy'] && ( empty( $args['walker'] ) || $args['walker'] instanceof \Walker ) ) {
			
			$args['walker'] = new Woo_Feed_Brand_Walker_Category_Radio_Checklist();
		}
		
		return $args;
	}
}

//Init Brand Taxonomy
$CTXBrandTaxonomy = new Taxonomy();





/**
 * Woo_Feed_Custom_Taxonomy_Filter is special extension class of FilterIterator
 *
 * @since 4.3.93
 */
class TaxonomyFilter extends FilterIterator {
	
	public function __construct( array $items ) {
		$object = new ArrayObject( $items );
		parent::__construct( $object->getIterator() );
	}
	
	public function accept() {
		return array_key_exists( 2, $this->current() ) ? $this->current()[2] : false;
	}
}


/**
 * Custom walker for switching checkbox inputs to radio.
 *
 * @see Walker_Category_Checklist
 */
class Woo_Feed_Brand_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
	public function walk( $elements, $max_depth, ...$args ) {
		$output = parent::walk( $elements, $max_depth, ...$args );
		
		return str_replace(
			array( 'type="checkbox"', "type='checkbox'" ),
			array( 'type="radio"', "type='radio'" ),
			$output
		);
	}
}
