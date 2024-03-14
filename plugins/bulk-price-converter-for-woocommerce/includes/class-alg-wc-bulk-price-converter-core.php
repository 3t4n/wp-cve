<?php
/**
 * Bulk Price Converter - Core Class
 *
 * @version 1.5.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Bulk_Price_Converter_Core' ) ) :

class Alg_WC_Bulk_Price_Converter_Core {

	public $attribute_taxonomies;
	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 */
	function __construct() {
		$this->attribute_taxonomies = $this->alg_wc_get_attribute_taxonomies();
		return true;
	}
	
	function alg_wc_get_attribute_taxonomies(){
		global $wpdb;
		$raw_attribute_taxonomies = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;" );
		return $raw_attribute_taxonomies;
	}

	/**
	 * change_price.
	 *
	 * @version 1.5.0
	 */
	function change_price( $product_id, $parent_product_id, $price_type, $min_price = 0, $max_price = 0 ) {
		// Price calculation
		$price          = get_post_meta( $product_id, '_' . $price_type, true );
		$modified_price = $price;
		// Direct price
		if ( '' !== $this->atts['direct_price'] ) {
			$modified_price = $this->atts['direct_price'];
		}
		if ( '' !== $modified_price ) {
			// Multiplication
			if ( '' !== $this->atts['multiply_prices_by'] ) {
				$modified_price = $modified_price * $this->atts['multiply_prices_by'];
			}
			// Divide
			if ( '' !== $this->atts['divide_prices_by'] ) {
				$modified_price = $modified_price / $this->atts['divide_prices_by'];
			}
			// Addition
			if ( '' != $this->atts['add_to_price'] ) {
				$modified_price = $modified_price + $this->atts['add_to_price'];
			}
			// Subtract
			if ( '' != $this->atts['minus_to_price'] ) {
				$modified_price = $modified_price - $this->atts['minus_to_price'];
			}
			// Rounding
			if ( 'none' != $this->atts['round_function'] ) {
				$modified_price = apply_filters( 'alg_wc_bpc_round_price', $modified_price, $this->atts['round_function'], $this->atts['round_coef'] );
			}
			// Final rounding
			$precision          = get_option( 'woocommerce_price_num_decimals', 2 );
			$modified_price     = round( $modified_price, $precision );
			// "Pretty price"
			if ( $this->atts['pretty_prices_threshold'] > 0 ) {
				$modified_price = apply_filters( 'alg_wc_bpc_pretty_price', $modified_price, $this->atts['pretty_prices_threshold'] );
			}
			// Negative, min & max
			if ( $modified_price < 0 ) {
				$modified_price = 0;
			}
			if ( 0 != $min_price && $modified_price < $min_price ) {
				$modified_price = $min_price;
			}
			if ( 0 != $max_price && $modified_price > $max_price ) {
				$modified_price = $max_price;
			}
			// Maybe update to new price
			if ( ! $this->atts['is_preview'] ) {
				update_post_meta( $product_id, '_' . $price_type, $modified_price );
			}
		}
		// Output
		if ( '' != $price || '' != $modified_price ) {
			$_product_cats = array();
			$product_terms = get_the_terms( $parent_product_id, 'product_cat' );
			if ( ! is_wp_error( $product_terms ) && is_array( $product_terms ) ) {
				foreach ( $product_terms as $term ) {
					$_product_cats[] = esc_html( $term->name );
				}
			}
			$_product_tags = array();
			$product_terms = get_the_terms( $parent_product_id, 'product_tag' );
			if ( ! is_wp_error( $product_terms ) && is_array( $product_terms ) ) {
				foreach ( $product_terms as $term ) {
					$_product_tags[] = esc_html( $term->name );
				}
			}
			$this->result[] = array(
				get_the_title( $product_id ) . ' (#' . $product_id . ')',
				implode( ', ', $_product_cats ),
				implode( ', ', $_product_tags ),
				'<code>' . str_replace( '_', ' ', $price_type ) . '</code>',
				( is_numeric( $price ) ? wc_price( $price ) : $price ),
				'<span' . ( $modified_price != $price ? ' style="color:orange;"' : '' ) . '>' . ( is_numeric( $modified_price ) ? wc_price( $modified_price ) : $modified_price ) . '</span>',
			);
		}
	}

	/**
	 * change_all_products_prices.
	 *
	 * @version 1.5.0
	 * @todo    [dev] (maybe) `sale_prices`: remove `( get_post_meta( $_product_id, '_' . 'price', true ) === get_post_meta( $_product_id, '_' . 'sale_price', true ) )` check
	 * @todo    [dev] (maybe) `regular_prices`: remove `( $price !== $sale_price || $atts['multiply_prices_by'] <= 1 )` check
	 */
	function change_all_products_prices( $atts ) {
		if ( $atts['multiply_prices_by'] <= 0 ) {
			return;
		}
		$offset       = 0;
		$block_size   = get_option( 'alg_wc_bulk_price_converter_block_size', 1024 );
		$time_limit   = get_option( 'alg_wc_bulk_price_converter_time_limit', -1 );
		$this->result = array();
		$this->atts   = $atts;
		while ( true ) {
			if ( -1 != $time_limit ) {
				set_time_limit( $time_limit );
			}
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'fields'         => 'ids',
			);
			if ( 'any' != $atts['product_cats'] ) {
				// $args = apply_filters( 'alg_wc_bpc_product_query', $args, 'product_cat', $atts['product_cats'] );
				$terms = $atts['product_cats'];
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => array( $terms ),
					'operator' => ( 'none' != $terms ? 'IN' : 'NOT EXISTS' ),
				);
			}
			if ( 'any' != $atts['product_tags'] ) {
				$args = apply_filters( 'alg_wc_bpc_product_query', $args, 'product_tag', $atts['product_tags'] );
			}
			if(isset($this->attribute_taxonomies) && !empty($this->attribute_taxonomies)){
				foreach($this->attribute_taxonomies as $taxn){
					$attr_slug = 'pa_'.$taxn->attribute_name;
					if ( 'any' != $atts[$attr_slug] ) {
						$args = apply_filters( 'alg_wc_bpc_product_query', $args, $attr_slug, $atts[$attr_slug] );
					}
				}
			}
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $product_id ) {
				// Getting all product IDs (including variations for variable products)
				$product_ids = array( $product_id );
				$product     = wc_get_product( $product_id );
				if ( $product->is_type( 'variable' ) ) {
					$product_ids = array_merge( $product_ids, $product->get_children() );
				}
				// Changing prices
				foreach ( $product_ids as $_product_id ) {
					switch ( $atts['price_types'] ) {
						case 'both':
							$this->change_price( $_product_id, $product_id, 'price',         0, 0 );
							$this->change_price( $_product_id, $product_id, 'sale_price',    0, 0 );
							$this->change_price( $_product_id, $product_id, 'regular_price', 0, 0 );
							break;
						case 'sale_prices':
							$regular_price = get_post_meta( $_product_id, '_' . 'regular_price', true );
							if ( get_post_meta( $_product_id, '_' . 'price', true ) === get_post_meta( $_product_id, '_' . 'sale_price', true ) ) {
								$this->change_price( $_product_id, $product_id, 'price',  0, $regular_price );
							}
							$this->change_price( $_product_id, $product_id, 'sale_price', 0, $regular_price );
							break;
						case 'regular_prices':
							$sale_price = get_post_meta( $_product_id, '_' . 'sale_price', true );
							$price      = get_post_meta( $_product_id, '_' . 'price', true );
							if ( $price === get_post_meta( $_product_id, '_' . 'regular_price', true ) && ( $price !== $sale_price || $atts['multiply_prices_by'] <= 1 ) ) {
								$this->change_price( $_product_id, $product_id, 'price', $sale_price, 0 );
							}
							$this->change_price( $_product_id, $product_id, 'regular_price', $sale_price, 0 );
							break;
					}
				}
			}
			$offset += $block_size;
		}
		return $this->result;
	}

}

endif;

return new Alg_WC_Bulk_Price_Converter_Core();
