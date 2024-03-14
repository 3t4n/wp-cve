<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
	if ( ! class_exists( 'Variation_Duplicator_For_Woocommerce_Variation_Image_Clone', false ) ):
		
		class Variation_Duplicator_For_Woocommerce_Variation_Image_Clone {
			
			protected static $_instance = null;
			
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}
				
				return self::$_instance;
			}
			
			public function __construct() {
				$this->hooks();
				do_action( 'variation_duplicator_for_woocommerce_variation_image_clone_loaded', $this );
			}
			
			public function hooks() {
				add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'form' ), 10, 3 );
				add_action( 'woocommerce_save_product_variation', array( $this, 'prepare' ), 999 );
				add_action( 'woo_variation_duplicator_load_variations', array( $this, 'notice' ) );
			}
			
			public static function format_attribute_summary( $summary ) {
				// Like: Color: Blue, Logo: No, Size:  One
				$summary_chunk = explode( ',', $summary );
				
				// Like: Size:  One
				$summary_arr = array_map( function ( $chunk ) {
					$parts = explode( ':', $chunk );
					
					return trim( end( $parts ) );
				}, $summary_chunk );
				
				$summary_text = implode( ', ', $summary_arr );
				
				return ( strlen( $summary_text ) > 55 ) ? substr( $summary_text, 0, 55 ) . '...' : $summary_text;
			}
			
			public function form( $loop, $variation_data, $variation ) {
				
				if ( apply_filters( 'disable_variation_duplicator_for_woocommerce_image_clone', false, $loop, $variation_data, $variation ) ) {
					return true;
				}
				
				$variation_id = absint( $variation->ID );
				$parent_id    = wp_get_post_parent_id( $variation_id );
				$product      = wc_get_product( $parent_id );
				$child_ids    = $product->get_children();
				$child_ids    = array_diff( $child_ids, [ $variation_id ] );
				$image_id     = isset( $variation_data[ '_thumbnail_id' ] ) ? $variation_data[ '_thumbnail_id' ] : 0;
				
				
				$selected_variation          = wc_get_product_object( 'variation', $variation_id );
				$selected_variation_image_id = absint( $selected_variation->get_image_id() );
				
				include dirname( __FILE__ ) . '/html-variation-duplicator-form.php';
			}
			
			public function notice() {
				$results = get_transient( 'woo_variation_duplicator_image_cloned' );
				
				if ( $results ) {
					printf( '<div class="inline notice variation-duplicator-for-woocommerce-notice"><p>%s</p></div>', esc_html__( 'Variation image cloned.', 'variation-duplicator-for-woocommerce' ) );
					delete_transient( 'woo_variation_duplicator_image_cloned' );
				}
			}
			
			public function prepare( $variation_id ) {
				
				if ( ! isset( $_POST[ 'variable_image_duplicate_type' ] ) ) {
					return;
				}
				
				$current_variation     = $variation = wc_get_product_object( 'variation', $variation_id );
				$variation_data        = $variation->get_data();
				$parent_product_id     = $variation->get_parent_id();
				$current_variation_img = absint( $variation_data[ 'image_id' ] );
				// $current_variation_img = $current_variation_img > 0 ? $current_variation_img : absint( $current_variation->get_image_id() );
				
				$clone_type = sanitize_text_field( $_POST[ 'variable_image_duplicate_type' ][ $variation_id ] );
				
				// To Post Data
				$variable_image_to_post_data = ( isset( $_POST[ 'variable_image_duplicate_to' ] ) && isset( $_POST[ 'variable_image_duplicate_to' ][ $variation_id ] ) && is_array( $_POST[ 'variable_image_duplicate_to' ][ $variation_id ] ) ) ? $_POST[ 'variable_image_duplicate_to' ][ $variation_id ] : [];
				$variation_img_to            = array_map( 'absint', $variable_image_to_post_data );
				
				// From Post Data
				$variable_image_from_post_data = ( isset( $_POST[ 'variable_image_duplicate_from' ] ) && isset( $_POST[ 'variable_image_duplicate_from' ][ $variation_id ] ) && ! empty( $_POST[ 'variable_image_duplicate_from' ][ $variation_id ] ) ) ? $_POST[ 'variable_image_duplicate_from' ][ $variation_id ] : 0;
				$variation_img_from            = absint( $variable_image_from_post_data );
				
				do_action( 'woo_variation_duplicator_prepare', $current_variation, $clone_type, $variation_img_to, $variation_img_from );
				
				// Set (this) variation image to given variations
				$is_cloned = false;
				if ( 'to' === $clone_type && ! empty( $variation_img_to ) ) {
					foreach ( $variation_img_to as $id ) {
						
						$selected_variation      = wc_get_product_object( 'variation', $id );
						$selected_variation_data = $selected_variation->get_data();
						$selected_variation_img  = absint( $selected_variation_data[ 'image_id' ] );
						
						// if ( empty( $current_variation_img ) || $current_variation_img == $selected_variation_img ) {
						if ( empty( $current_variation_img ) ) {
							continue;
						}
						
						$is_cloned = true;
						
						$this->save( $selected_variation, $current_variation_img, $current_variation );
						// woo_variation_duplicator_image_saved $selected_variation to $current_variation
						do_action( 'woo_variation_duplicator_image_saved_to', $selected_variation, $current_variation, $current_variation_img, $parent_product_id );
						clean_post_cache( $selected_variation->get_id() );
					}
					
					if ( $is_cloned ) {
						set_transient( 'woo_variation_duplicator_image_cloned', 'yes' );
					}
				}
				
				// Set (this) variation image from given variation or product featured image
				if ( 'from' === $clone_type && ! empty( $variation_img_from ) ) {
					
					// VARIATION PRODUCT
					if ( 'product_variation' == get_post_type( $variation_img_from ) ) {
						$selected_variation      = wc_get_product_object( 'variation', $variation_img_from );
						$selected_variation_data = $selected_variation->get_data();
						$selected_variation_img  = absint( $selected_variation_data[ 'image_id' ] );
						$selected_variation_img  = ( ( $selected_variation_img > 0 ) ? $selected_variation_img : absint( $selected_variation->get_image_id() ) );
					} else {
						$selected_variation     = wc_get_product_object( 'variable', $variation_img_from );
						$selected_variation_img = absint( $selected_variation->get_image_id() );
					}
					
					// if ( empty( $selected_variation_img ) || $current_variation_img == $selected_variation_img ) {
					if ( empty( $selected_variation_img ) ) {
						return;
					}
					
					$this->save( $current_variation, $selected_variation_img, $selected_variation );
					
					// woo_variation_duplicator_image_saved from $current_variation to $selected_variation
					do_action( 'woo_variation_duplicator_image_saved_from', $current_variation, $selected_variation, $selected_variation_img, $parent_product_id );
					clean_post_cache( $current_variation->get_id() );
					set_transient( 'woo_variation_duplicator_image_cloned', 'yes' );
				}
				
				clean_post_cache( $parent_product_id );
			}
			
			public function save( $variation, $image_id, $selected_variation ) {
				
				if ( ! is_ajax() ) {
					return;
				}
				
				$variation->set_props( array(
					                       'image_id' => apply_filters( 'woo_variation_duplicator_image_id', $image_id, $variation, $selected_variation ),
				                       ) );
				
				$variation->save();
			}
		}
	endif;