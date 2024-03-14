<?php

/**
 * The product-specific functionality of the plugin.
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/Product
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WT_Facebook_Catalog_Product' ) ) :

	class WT_Facebook_Catalog_Product {

		public static $checkout_url_products	 = array(
			'simple',
			'variable',
			'variation'
		);

		public function __construct() {

			$this->sync_description_type = (string) apply_filters( 'wt_facebook_product_description_mode', 'short' );
		}

		/**
		 * Gets a list of image URLs to use for this product in Facebook sync.
		 *
		 * @return array
		 */
		public function get_all_image_urls( $product ) {

			$attachment_ids = $product->get_gallery_image_ids();

			$image_urls = array();

			if ( $product->get_image_id() ) {
				$image_urls[ 0 ] = wp_get_attachment_url( $product->get_image_id() );
			}

			foreach ( $attachment_ids as $attachment_id ) {
				$image_urls[] = wp_get_attachment_url( $attachment_id );
			}

			if ( empty( $image_urls ) ) {

				$image_urls[] = "https://via.placeholder.com/300";
			}

			return $image_urls;
		}

		public function add_sale_price( $product, $product_data ) {

			$sale_price = $product->get_sale_price();

			if ( !is_numeric( $sale_price ) ) {

				$sale_start									 = '1970-01-29T00:00+00:00';
				$sale_end									 = '1970-01-30T23:59+00:00';
				
				//$product_data[ 'sale_price_start_date' ] = '1970-01-29T00:00+00:00';
				//$product_data[ 'sale_price_end_date' ]	 = '1970-01-30T23:59+00:00';
				$product_data[ 'sale_price_effective_date' ] = $sale_start . '/' . $sale_end;
				$product_data[ 'sale_price' ]				 = $product_data[ 'price' ];
			} else {
				//$sale_price = intval( round( $this->get_price_plus_tax( $sale_price, $product ) * 100 ) );
				//$sale_price = (string) ( round( $this->get_price_plus_tax( $sale_price, $product ) / (float) 100, 2 ) ) . get_woocommerce_currency();
				$sale_price = $this->get_price_plus_tax( $sale_price, $product ) . ' ' . get_woocommerce_currency();

				$sale_start	 = ( $dates_from	 = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $dates_from ) . 'T00:00+00:00' : '1970-01-29T00:00+00:00';
				$sale_end	 = ( $dates_to	 = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $dates_to ) . 'T23:59+00:00' : '2022-01-17T23:59+00:00';

				//$product_data[ 'sale_price_start_date' ] = $sale_start;
				//$product_data[ 'sale_price_end_date' ]	 = $sale_end;
				$product_data[ 'sale_price' ]				 = $sale_price;
				$product_data[ 'sale_price_effective_date' ] = $sale_start . '/' . $sale_end;
			}
			return $product_data;
		}

		public static function get_inventory( $product ) {

			if ( $product->is_type( 'variation' ) ) {

				$variation_obj	 = new WC_Product_variation( $product->get_id() );
				$stock			 = $variation_obj->get_stock_quantity();
			} else {
				$stock = $product->get_stock_quantity();
			}
			return $stock;
		}

		public function get_price_plus_tax( $price, $product ) {

			if ( function_exists( 'wc_get_price_including_tax' ) ) {
				$args = array(
					'qty'	 => 1,
					'price'	 => $price,
				);
				return get_option( 'woocommerce_tax_display_shop' ) !== 'incl' ? wc_get_price_including_tax( $product, $args ) : wc_get_price_excluding_tax( $product, $args );
			} else {
				return get_option( 'woocommerce_tax_display_shop' ) !== 'incl' ? $product->get_price_including_tax( 1, $price ) : $product->get_price_excluding_tax( 1, $price );
			}
		}

		public function get_variant_option_name( $product_id, $label, $default_value ) {

			$meta			 = get_post_meta( $product_id, $label, true );
			$attribute_name	 = str_replace( 'attribute_', '', $label );
			$term			 = get_term_by( 'slug', $meta, $attribute_name );
			return ( $term && $term->name ) ? $term->name : $default_value;
		}

		public function get_product_description( $product ) {

			$description = ($this->sync_description_type === 'short') ? $product->get_short_description() : $product->get_description();
			if ( !$description && self::is_variation_type( $product->get_type() ) ) {
				$id			 = $product->get_parent_id();
				$product	 = wc_get_product( $id );
				$description = ($product->get_short_description()) ? $product->get_short_description() : $product->get_description();
			}
			if ( !$description ) {
				$description = ($product->get_short_description()) ? $product->get_short_description() : $product->get_description();
			}
			return strip_tags( $description );
		}

		public function prepare_product( $product ) {


			$retailer_id = self::get_fb_retailer_id( $product );
			$image_urls	 = $this->get_all_image_urls( $product );

			$product_url = str_replace( '&amp%3B', '&', html_entity_decode( get_permalink( $product->get_id() ) ) );

			$id = $product->get_id();
			if ( self::is_variation_type( $product->get_type() ) ) {
				//$id = $product->get_parent_id();
			}

			$categories = self::get_product_categories( $id );

			// Brand from product edit screen
			$brand = get_post_meta($id, '_wt_facebook_brand', true);
                        if( '' === $brand ){
                            $brand = get_post_meta($id, '_wt_feed_brand', true);
                        }
			if( '' === $brand && is_plugin_active( 'woocommerce-brands/woocommerce-brands.php' ) ){
				$brand	 = get_the_term_list( $id, 'product_brand', '', ', ' );
				$brand	 = is_wp_error( $brand ) || !$brand ? wp_strip_all_tags( self::get_store_name() ) : self::clean_string( $brand );
			}
                        if( '' === $brand && ( is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php') || defined( 'PWB_PLUGIN_FILE' ) ) ) {
                                $brand = get_the_term_list( $id , 'pwb-brand', '', ', ');
                                $brand	 = is_wp_error( $brand ) || !$brand ? wp_strip_all_tags( self::get_store_name() ) : self::clean_string( $brand );
                        }
                        if( '' === $brand ){
                            $brand = wp_strip_all_tags( self::get_store_name() );
                        }                        
			$product_description = $this->get_product_description( $product );

			$product_data = array(
				//'name'					 => self::clean_product_name( $product->get_name() ),
				'title'					 => self::clean_product_name( $product->get_name() ),
				'description'			 => $product_description,
				//'image_url'				 => $image_urls[ 0 ], // The array can't be empty.
				'image_link'			 => $image_urls[ 0 ], // The array can't be empty.
				//'additional_image_urls'	 => array_slice( $image_urls, 1 ),
				'additional_image_link'	 => array_slice( $image_urls, 1 ),
				//'url'                   => "https://www.webtoffee.com",//$product_url,
				//'url'					 => $product_url,
				'link'					 => $product_url, //"https://www.webtoffee.com",
				//'category'				 => $categories[ 'categories' ],
				'brand'					 => self::prepare_brand( $brand, 100 ),
				'id'					 => $retailer_id,
				//'retailer_id'			 => $retailer_id,
				//'price'					 => intval( round( $product->get_price() * 100 ) ),
				//'currency'				 => get_woocommerce_currency(),
				//'price'					=>	(string) ( round( $product->get_price() / (float) 100, 2 ) ) . get_woocommerce_currency(),
				//'price'					 => intval( round( $product->get_price() * 100 ) ). ' '.get_woocommerce_currency(),
				'price'					 => $product->get_price() . ' ' . get_woocommerce_currency(),
                                //'price'					 => $this->get_price_plus_tax($product->get_regular_price(), $product) . ' ' . get_woocommerce_currency(),
				'availability'			 => $product->is_in_stock() ? 'in stock' : 'out of stock',
				'visibility'			 => ( 'hidden' === $product->get_catalog_visibility() ) ? 'staging' : 'published',
			);
// Age group from product edit screen
			$custom_agegroup = get_post_meta($id, '_wt_facebook_agegroup', true);
                        if( '' == $custom_agegroup ){
                            $custom_agegroup = get_post_meta($id, '_wt_feed_agegroup', true);
                        }                        
			if( '' != $custom_agegroup ){
				$product_data[ 'age_group' ] = $custom_agegroup;
			}

			// Size from product edit screen
			$custom_size = get_post_meta($id, '_wt_facebook_size', true);
                        if( '' == $custom_size ){
                            $custom_size = get_post_meta($id, '_wt_feed_size', true);
                        }                         
			if( '' != $custom_size ){
				$product_data[ 'size' ] = $custom_size;
			}
			// Color from product edit screen
			$custom_color = get_post_meta($id, '_wt_facebook_color', true);
                        if( '' == $custom_color ){
                            $custom_color = get_post_meta($id, '_wt_feed_color', true);
                        }                        
			if( '' != $custom_color ){
				$product_data[ 'color' ] = $custom_color;
			}	
			// Pattern from product edit screen
			$custom_pattern = get_post_meta($id, '_wt_facebook_pattern', true);
                        if( '' == $custom_pattern ){
                            $custom_pattern = get_post_meta($id, '_wt_feed_pattern', true);
                        }                          
			if( '' != $custom_pattern ){
				$product_data[ 'pattern' ] = $custom_pattern;
			}
			// Gender from product edit screen
			$custom_gender = get_post_meta($id, '_wt_facebook_gender', true);
                        if( '' == $custom_gender ){
                            $custom_gender = get_post_meta($id, '_wt_feed_gender', true);
                        }                          
			if( '' != $custom_gender ){
				$product_data[ 'gender' ] = $custom_gender;
			}                        
			
			if ( !empty( $categories[ 'fb_product_category' ] ) ) {
				$product_data[ 'fb_product_category' ] = $categories[ 'fb_product_category' ];
				//$product_data[ 'category' ] = $categories[ 'fb_product_category' ];
			}

			$inventory = self::get_inventory( $product );
			if ( $inventory ) {
				$product_data[ 'inventory' ] = $inventory;
			}

			$checkout_url = $this->get_checkout_url( $product, $product_url );
			if ( $checkout_url ) {
				$product_data[ 'checkout_url' ] = $checkout_url;
			}

			$product_data = $this->add_sale_price( $product, $product_data );

			if ( true === $product->get_virtual() ) {
				$product_data[ 'visibility' ] = 'staging';
			}

			if ( self::is_all_caps( $product_data[ 'description' ] ) ) {
				$product_data[ 'description' ] = mb_strtolower( $product_data[ 'description' ] );
			}

                        return apply_filters( 'wt_fbfeed_prepare_product_data', $product_data, $id );
		}

		public function get_checkout_url( $product, $product_url ) {

			$product_type = $product->get_type();
			if ( !$product_type || !in_array( $product_type, self::$checkout_url_products )) {
				$checkout_url = $product_url;
			} elseif ( function_exists( 'wc_get_cart_url' ) && wc_get_cart_url() ) {
				$char = '?';

				if ( strpos( wc_get_cart_url(), '?' ) !== false ) {
					$char = '&';
				}

				$checkout_url = self::make_url(
				wc_get_cart_url() . $char
				);

				if ( self::is_variation_type( $product_type ) ) {
					$query_data = array(
						'add-to-cart'	 => $product->get_parent_id(),
						'variation_id'	 => $product->get_id(),
					);

					$query_data = array_merge(
					$query_data, $product->get_variation_attributes()
					);
				} else {
					$query_data = array(
						'add-to-cart' => $product->get_id(),
					);
				}

				$checkout_url = $checkout_url . http_build_query( $query_data );
			} else {
				$checkout_url = null;
			}
			return $checkout_url;
		}

		public static function is_all_caps( $value ) {

			if ( $value === null || $value === '' ) {
				return true;
			}
			//https://stackoverflow.com/q/36984891/1117368
			if ( preg_match( '/[^\\p{Common}\\p{Latin}]/u', $value ) ) {
				return false;
			}
			$latin_string = preg_replace( '/[^\\p{Latin}]/u', '', $value );
			if ( $latin_string === '' ) {
				return true;
			}
			return strtoupper( $latin_string ) === $latin_string;
		}

               	public static function clean_string($string) {
			$string = do_shortcode($string);
			$string = str_replace(array('&amp%3B', '&amp;'), '&', $string);
			$string = str_replace(array("\r", '&nbsp;', "\t"), ' ', $string);
			$string = wp_strip_all_tags($string, false); // true == remove line breaks
			return $string;
		} 
                
		public static function get_fb_retailer_id( $product ) {

			$product_id = $product->get_id();
			$fb_retailer_id = $product->get_sku() ? $product->get_sku() . '_' . $product_id : 'wc_post_id_' . $product_id;
			return apply_filters('wt_fb_sync_product_retailer_id', $fb_retailer_id, $product);
		}

		public static function get_product_categories( $product_id ) {

			$category_path = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'all' ) );

			$fb_product_category = [];
			$categories_list	 = [];
			foreach ( $category_path as $category ) {
				$fb_category_id = get_term_meta( $category->term_id, 'wt_fb_category', true );
				if ( $fb_category_id ) {
					$fb_category			 = WT_Fb_Catalog_Manager_Settings::get_fb_categories( $fb_category_id );
					$categories_list[]		 = $fb_category;
					$fb_product_category[]	 = $fb_category;
				} else {
					$categories_list[] = $category->name;
				}
			}

			$categories			 = empty( $categories_list ) ? '""' : implode( ', ', $categories_list );
			$fb_product_category = empty( $fb_product_category ) ? '' : implode( ', ', $fb_product_category );

			return array(
				'categories'			 => $categories,
				'fb_product_category'	 => $fb_product_category
			);
		}

		public static function get_store_name() {

			$url = get_bloginfo('name');
			return ( $url) ? ( $url ) : 'My Store';
		}

		public function prepare_variants_for_item( &$product_data, $product ) {


			if ( !$product->is_type( 'variation' ) ) {
				return [];
			}

			$attributes = $product->get_variation_attributes();


			if ( !$attributes ) {
				return [];
			}

			$variant_names	 = array_keys( $attributes );
			$variant_data	 = [];

			foreach ( $variant_names as $original_variant_name ) {


				$label = wc_attribute_label( $original_variant_name, $product );

				$new_name = str_replace( 'custom_data:', '', self::sanitize_variant_name( $original_variant_name ) );


				if ( $options = $this->get_variant_option_name( $product->get_id(), $label, $attributes[ $original_variant_name ] ) ) {

					if ( is_array( $options ) ) {

						$option_values = array_values( $options );
					} else {

						$option_values = [ $options ];

						if ( count( $option_values ) === 1 && empty( $option_values[ 0 ] ) ) {
							$option_values[ 0 ]				 = 'any';
							$product_data[ 'checkout_url' ]	 = $product_data[ 'url' ];
						}
					}

					if ( 'gender' === $new_name ) {

						$product_data[ $new_name ] = $option_values[ 0 ];
					}

					switch ( $new_name ) {

						case 'size':
						case 'color':
						case 'pattern':

							$variant_data[] = [
								'product_field'	 => $new_name,
								'label'			 => $label,
								'options'		 => $option_values,
							];

							$product_data[ $new_name ] = $option_values[ 0 ];

							break;

						case 'gender':

							if ( $product_data[ $new_name ] ) {

								$variant_data[] = [
									'product_field'	 => $new_name,
									'label'			 => $label,
									'options'		 => $option_values,
								];
							}

							break;

						default:

							if ( !isset( $product_data[ 'custom_data' ] ) ) {
								$product_data[ 'custom_data' ] = [];
							}

							$product_data[ 'custom_data' ][ $new_name ] = urldecode( $option_values[ 0 ] );

							break;
					}
				} else {

					continue;
				}
			}

			return $variant_data;
		}

		public static function prepare_brand( $string, $length ) {

			if ( extension_loaded( 'mbstring' ) ) {

				if ( mb_strlen( $string, 'UTF-8' ) <= $length ) {
					return $string;
				}

				$length -= mb_strlen( '...', 'UTF-8' );

				return mb_substr( $string, 0, $length, 'UTF-8' ) . '...';
			} else {

				$string	 = filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
				$string	 = filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );

				if ( strlen( $string ) <= $length ) {
					return $string;
				}

				$length -= strlen( '...' );

				return substr( $string, 0, $length ) . '...';
			}
		}

		public static function sanitize_variant_name( $name ) {

			$name = str_replace( array( 'attribute_', 'pa_' ), '', strtolower( $name ) );

			if ( 'colour' === $name ) {
				$name = 'color';
			}

			switch ( $name ) {
				case 'size':
				case 'color':
				case 'gender':
				case 'pattern':
					break;
				default:
					$name = 'custom_data:' . strtolower( $name );
					break;
			}

			return $name;
		}

		public static function make_url( $url ) {


			if ( !filter_var( $url, FILTER_VALIDATE_URL ) && substr( $url, 0, 4 ) !== 'http' ) {
				return get_site_url() . $url;
			} else {
				return $url;
			}
		}

		public static function clean_product_name( $string ) {

			$string	 = do_shortcode( $string );
			$string	 = str_replace( array( '&amp%3B', '&amp;' ), '&', $string );
			$string	 = str_replace( array( "\r", '&nbsp;', "\t" ), ' ', $string );
			$string	 = wp_strip_all_tags( $string, true );
			return $string;
		}

		public function process_item_update( $product_id ) {

			$product = wc_get_product( $product_id );

			if ( !$product->get_price() ) {
				return [];
			}
                        // Do not send parent product to FB.
			if ( $product->is_type( 'variable' ) ) {
				return [];
			}
			if ( $product->is_type( 'variation' ) ) {
                                $parent_product = wc_get_product( $product->get_parent_id() );
                                if ( !$parent_product instanceof WC_Product ) {
                                    return [];
                                }
				$product_data = $this->prepare_product_variation_data( $product );
			} else {
                                
				$product_data = $this->prepare_product_data( $product );
			}
			//$retailer_id = $product_data[ 'retailer_id' ];
			//unset( $product_data[ 'retailer_id' ] );

			$request = [
				//'retailer_id'	 => $retailer_id,
				'method' => 'UPDATE',
				'data'	 => $product_data,
			];

			return apply_filters( 'wt_fbfeed_item_update_request', $request, $product );
		}

		private function prepare_product_variation_data( $product ) {


			$parent_product = wc_get_product( $product->get_parent_id() );

			$data = $this->prepare_product( $product );
			$this->prepare_variants_for_item( $data, $product );

			//$data[ 'retailer_product_group_id' ] = self::get_fb_retailer_id( $parent_product );
			$data[ 'item_group_id' ] = self::get_fb_retailer_id( $parent_product );

			$product_data = $this->prepare_additional_product_data( $data );
                        
			// Condition from product edit screen
			$custom_condition = get_post_meta($product->get_id(), '_wt_facebook_condition', true);
                        if( '' == $custom_condition ){
                            $custom_condition = get_post_meta($product->get_id(), '_wt_feed_condition', true);
                        }                        
			if( '' != $custom_condition ){
				$product_data[ 'condition' ] = $custom_condition;
			}
                        for ( $i =0; $i<=4; $i++ ) {

                             $product_data[ 'custom_label_' . $i ] = get_post_meta($product->get_id(), '_wt_feed_custom_label_'.$i, true);
                                
                        }        
                        
                        $product_data['additional_variant_attribute'] = $this->additional_variant_attribute($product);
                        
                        return $product_data;
		}

		/**
		 * 
		 * @param $data product data
		 * @return array
		 */
		private function prepare_additional_product_data( $data ) {

			// Allowed values are 'new', 'refurbished', 'used', 'used_like_new', 'used_good', 'used_fair', 'cpo' and 'open_box_new'
			$data[ 'condition' ] = 'new';

			//$data[ 'product_type' ] = $data[ 'category' ];

			// Attributes other than size, color, pattern, or gender need to be included in the custom_label_ field
			if ( isset( $data[ 'custom_data' ] ) && is_array( $data[ 'custom_data' ] ) ) {

				$i = 0;
				foreach ( $data[ 'custom_data' ] as $key => $value ) {
					if ( $i <= 4 ) {
						$data[ 'custom_label_' . $i ] = $key . ':' . $value;
					}
				}

				unset( $data[ 'custom_data' ] );
			}

			return $data;
		}

		private function prepare_product_data( $product ) {

			$data					 = $this->prepare_product( $product );
			$data[ 'item_group_id' ]	 = $data[ 'id' ];
			//$data[ 'retailer_product_group_id' ]	 = $data[ 'retailer_id' ];
			$product_data = $this->prepare_additional_product_data( $data );
                        
			// Condition from product edit screen
			$custom_condition = get_post_meta($product->get_id(), '_wt_facebook_condition', true);
                        if( '' == $custom_condition ){
                            $custom_condition = get_post_meta($product->get_id(), '_wt_feed_condition', true);
                        }                        
			if( '' != $custom_condition ){
				$product_data[ 'condition' ] = $custom_condition;
			}
                        for ( $i =0; $i<=4; $i++ ) {

                             $product_data[ 'custom_label_' . $i ] = get_post_meta($product->get_id(), '_wt_feed_custom_label_'.$i, true);
                                
                        }                        
                        
                        return $product_data;
		}

		public static function is_variation_type( $type ) {
			return $type == 'variation' || $type == 'subscription_variation';
		}

		public static function is_variable_type( $type ) {
			return $type == 'variable' || $type == 'variable-subscription';
		}
                
                
                /**
                 * Get additional attributes for variation other than color, size, gender and pattern.
                 *
                 * @return mixed|void
                 */
                public function additional_variant_attribute($product) {

                    if ( !$product->is_type( 'variation' ) ) {
                                        return '';
                                }

                                $attributes = $product->get_variation_attributes();


                                if ( !$attributes ) {
                                        return [];
                                }
                                $product_data = [];
                                $variant_names	 = array_keys( $attributes );
                                $variant_data	 = [];

                                foreach ( $variant_names as $original_variant_name ) {


                                        $label = wc_attribute_label( $original_variant_name, $product );

                                        $new_name = str_replace( 'custom_data:', '', self::sanitize_variant_name( $original_variant_name ) );


                                        if ( $options = $this->get_variant_option_name( $product->get_id(), $label, $attributes[ $original_variant_name ] ) ) {

                                                if ( is_array( $options ) ) {

                                                        $option_values = array_values( $options );
                                                } else {

                                                        $option_values = [ $options ];

                                                        if ( count( $option_values ) === 1 && empty( $option_values[ 0 ] ) ) {
                                                                $option_values[ 0 ]				 = 'any';
                                                                $product_data[ 'checkout_url' ]	 = $product_data[ 'url' ];
                                                        }
                                                }

                                                if ( 'gender' === $new_name ) {

                                                        $product_data[ $new_name ] = $option_values[ 0 ];
                                                }

                                                switch ( $new_name ) {

                                                        case 'size':
                                                        case 'color':
                                                            break;
                                                        case 'pattern':

                                                                $variant_data[] = [
                                                                        'product_field'	 => $new_name,
                                                                        'label'			 => $label,
                                                                        'options'		 => $option_values,
                                                                ];

                                                                $product_data[ $new_name ] = $option_values[ 0 ];

                                                                break;

                                                        case 'gender':

                                                                if ( $product_data[ $new_name ] ) {

                                                                        $variant_data[] = [
                                                                                'product_field'	 => $new_name,
                                                                                'label'			 => $label,
                                                                                'options'		 => $option_values,
                                                                        ];
                                                                }

                                                                break;

                                                        default:

                                                                if(!isset($product_data)){
                                                                    $product_data = [];
                                                                }
                                                                $variant_details = array(
                                                                    'label' => ucwords( $new_name ) ,
                                                                    'value' => urldecode( $option_values[ 0 ] ),
                                                                );

                                                                array_push($product_data, $variant_details);//							
                                                                break;
                                                }
                                            } else {

                                                continue;
                                        }
                                }
                                
                                    $product_data_str = '';
                                    foreach($product_data as $product_data_single){
                                        $product_data_str.= $product_data_single['label'].':'.$product_data_single['value'].',';
                                    }
                                    $product_data = rtrim(trim($product_data_str), ',');
                               

                    return apply_filters("wt_facebook_sync_additional_variant_attributes", $product_data, $product);
                }                                

	}

endif;
