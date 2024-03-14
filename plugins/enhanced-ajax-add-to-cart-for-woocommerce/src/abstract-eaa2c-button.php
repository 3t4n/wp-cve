<?php
/**
 * Abstract class for the AJAX Add to Cart button and associated fields.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 *
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/admin
 * @author     TheRiteSites <contact@theritesites.com>
 */

namespace TRS\EAA2C;

if ( ! class_exists( 'TRS\EAA2C\Abstract_Button' ) ) {
	abstract class Abstract_Button {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		protected $meta = array(
			'contentVisibility' => array(
				'title'  => true,
				'price'  => false,
				'quantity' => true,
				'button' => true,
				'separator' => false,
			),
			'contentOrder' => array(
				'title',
				'separator',
				'price',
				'quantity',
				'button',
			),
			'quantity' => array(
				'default' => 1,
				'min' => 1,
				'max' => -1,
			),
			'buttonText' => '',
			'products' => array(),
			'variations' => array(),
			'titleType' => 'full',
			'align'	=> '',
			'className' => '',
		);

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Blocks
		 */
		public function render() {
			$raw_attributes	  = $this->meta;
			$attributes 	  = $this->parse_attributes( $raw_attributes );
			// $this->query_args = $this->parse_query_args();
			// $products         = $this->get_products();
			// $classes          = $this->get_container_classes();

			wp_enqueue_style( EAA2C_NAME );
			wp_enqueue_script( EAA2C_NAME . '-js-bundle' );

			if ( /*WP_DEBUG ||*/ EAA2C_DEBUG ) {
				error_log( "checking attributes" );
				error_log( wc_print_r( $attributes, true ) ) ;
			}

			return $this->renderHtml( $attributes );
		}

		public function get_att_title( $product_variation = null ) {
			$att_title = array();
			if ( $product_variation instanceof \WC_Product  ) {
				foreach ( $product_variation->get_attributes() as $key => $attribute ) {
					$termTitle = $attribute;
					if ( taxonomy_exists( $key ) ) {
						$term = get_term_by( 'slug', $attribute, $key );
						if ( ! is_wp_error( $term ) && ! empty( $term->name ) ) {
							$termTitle = $term->name;
						}
					}
					if ( $termTitle instanceof \WC_Product_Attribute ) {
						$att_title[] = $termTitle->get_name();
					} elseif ( is_string( $termTitle ) ) {
						$att_title[] = $termTitle;
					} elseif ( is_array( $termTitle ) ) {
						$att_title[] = implode( ', ', $termTitle );
					} else {
						if ( EAA2C_DEBUG ) {
							error_log( "There was an issue trying to generate the attribute title. Neither a string nor a WC_Product_Attribute were found:" );
							error_log( wc_print_r( $termTitle, true ) );
						}
					}
				}
			}
			else {
				if ( EAA2C_DEBUG ) {
					error_log( "There was an issue trying to generate the attribute title. A product was not passed in." );
				}
			}
			$att_title = implode( ', ', $att_title );
			return $att_title;

			// $titleDisplay = $product->get_name();
			// if ( ! is_null( $variation ) && $variation !== false ) {

			// 	$att_title = array();
			// 	foreach ( $variation->get_attributes() as $key => $attribute ) {
			// 		$termTitle = $attribute;
			// 		if ( taxonomy_exists( $key ) ) {
			// 			$term = get_term_by( 'slug', $attribute, $key );
			// 			if ( ! is_wp_error( $term ) && ! empty( $term->name ) ) {
			// 				$termTitle = $term->name;
			// 			}
			// 		}
			// 		$att_title[] = $termTitle;
			// 	}
			// 	$att_title = implode( ', ', $att_title );

			// 	if ( strcmp( $titleType, 'full' ) === 0 ) {
			// 		// In this scenario, we need to add the attribute title to the "full" title.
			// 		// This is due to the fact that product and variation are provided, and the
			// 		// above $product->get_name() only gets the "base" name
			// 		if ( ! empty( $att_title ) ) {
			// 			$titleDisplay .= ' - ' . $att_title;
			// 		}
			// 	}
			// 	elseif ( strcmp( $titleType, 'att' ) === 0 ) {
			// 		$titleDisplay = array();
			// 		if ( $variation instanceof \WC_Product ) {
			// 			$titleDisplay = $att_title;
			// 		}
			// 	}
			// } else {
			// 	$att_title = array();
			// 	foreach ( $product->get_attributes() as $key => $attribute ) {
			// 		// For product specific attributes, there is no "taxonomy" - treat it as default
			// 		$termTitle = $attribute;
			// 		if ( taxonomy_exists( $key ) ) {
			// 			// For global attributes, we need to do some magic to get the title
			// 			$term = get_term_by( 'slug', $attribute, $key );
			// 			if ( ! is_wp_error( $term ) && ! empty( $term->name ) ) {
			// 				$termTitle = $term->name;
			// 			}
			// 		}
			// 		$att_title[] = $termTitle;
			// 	}
			// 	$att_title = implode( ', ', $att_title );
			// 	if ( strcmp( $titleType, 'base' ) === 0 ) {
			// 		// In this scenario, we need to remove the attribute title from the "full" title.
			// 		// This is due to the fact that ponly the product is provided, and the
			// 		// above $product->get_name() gets the "full" name
			// 		$titleDisplay = $product->get_title();
			// 	}
			// 	elseif ( strcmp( $titleType, 'att' ) === 0 ) {
			// 		$titleDisplay = $att_title;
			// 	}
			// }
		}

		/**
		 * This function reats in an array of attributes. This array is already sanitized but not validated.
		 * 
		 * @since 2.0.0
		 */
		protected function renderHtml( $attributes = array() ) {
			$contentOrder 	    = $attributes['contentOrder'];
			$contentVisibility  = $attributes['contentVisibility'];

			$available_elements = [ 'title', 'separator', 'price', 'quantity', 'button' ];
			if ( get_option( 'a2cp_image_field' ) === 'on' ) {
				$available_elements[] = 'image';
			}
			if ( get_option( 'a2cp_custom_field' ) === 'on' ) {
				$available_elements[] = 'custom';
			}
			if ( get_option( 'a2cp_short_description' ) === 'on' ) {
				$available_elements[] = 'short_description';
			}

			ob_start();
				
			if ( is_array( $attributes['products'] ) && isset( $attributes['products'][0] ) ) {

				// If there is more than 1 product, by definition its a group block. We should wrap this block.
				// by default, and apply a filter allowing for an override of the wrap flag. Return false to disable the wrap.
				$wrap_group = apply_filters( 'a2cp_button_row_wrap_override', 1 < count( $attributes['products'] ), count( $attributes['products'] ) );
				if ( $wrap_group === true ) {
					?>
					<div class="a2cp-group">
					<?php
				}
				foreach( $attributes['products'] as $product_raw ) {
					// $product_raw   = $attributes['products'][0];
					$product_id		= isset( $product_raw['id'] ) ? $product_raw['id'] : 0;
					$product		= wc_get_product( $product_id );
					$variation_raw	= isset( $attributes['variations'][0] ) ? $attributes['variations'][0] : array();
					$variation_id 	= isset( $variation_raw['id'] ) ? $variation_raw['id'] : 0;
					$variation 		= wc_get_product( $variation_id );
					$buttonText		= $attributes['buttonText'];
					$quantity 	  	= $attributes['quantity'];
					$extraClasses	= isset( $attributes['className'] ) ? $attributes['className'] : '';
					$extraClasses  .= isset( $attributes['align'] ) && ! empty( $attributes['align'] ) ? ' align' . $attributes['align'] : '';
					$extraClasses  .= empty( get_option( 'a2cp_custom_class') ) ? '' : ' ' . get_option( 'a2cp_custom_class' );
					$extraClasses  .= empty( $product ) ? '' : ' ' . $product->get_type();
					$titleType 	    = isset( $attributes['titleType'] ) ? $attributes['titleType'] : 'full';
					$titleAction	= isset( $attributes['titleAction'] ) ? $attributes['titleAction'] : '';
					$titleDisplay	= '';

					$customText		= '';
					$image			= array();
					$imageType		= '';

					if ( $product instanceof \WC_Product ) {

						if ( $variation !== false && ! is_null( $variation ) ) {
							$max_value  = apply_filters( 'woocommerce_quantity_input_max', $variation->get_max_purchase_quantity(), $variation );
							$min_value  = apply_filters( 'woocommerce_quantity_input_min', $variation->get_min_purchase_quantity(), $variation );
							$step       = apply_filters( 'woocommerce_quantity_input_step', 1, $variation );
							$input_id   = 'product_' . $variation_id . '_qty';
						} else {
							$max_value  = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
							$min_value  = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
							$step       = apply_filters( 'woocommerce_quantity_input_step', 1, $product );
							$input_id   = 'product_' . $product_id . '_qty';
						}
	
						$pattern        = apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' );
						$inputmode      = apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' );
						
						$disable_button = '';

						$out_of_stock_check = empty( get_option( 'a2cp_out_of_stock') ) ? false : get_option( 'a2cp_out_of_stock' );
						if ( false === $out_of_stock_check || strcmp( 'false', $out_of_stock_check ) === 0 ) {
							if ( $variation !== false && ! is_null( $variation ) && $variation instanceof \WC_Product_Variation ) {
								if ( false === $variation->is_in_stock() || false === $variation->is_purchasable() ) {
									$buttonText = __( 'Out of stock', 'woocommerce' );
									$disable_button = 'disabled';
								}
							}
							elseif ( $product !== false && ( $variation === false || is_null( $variation ) ) && $product instanceof \WC_Product ) {
								if ( false === $product->is_in_stock() || false === $product->is_purchasable() ) {
									$buttonText = __( 'Out of stock', 'woocommerce' );
									$disable_button = 'disabled';
								}
							}
						}

						if ( true ) {
							if ( $quantity['min'] > $min_value ) {
								$min_value = $quantity['min'];
							}
							if ( $quantity['max'] ) {
								$max_value = $quantity['max'];
							}
						}

						if ( $contentVisibility[ 'price' ] === true ) {
	
							$priceDisplay = wc_price( $product->get_price() );
							if ( ! is_null( $variation ) && $variation !== false ) {
								// TODO This price display needs to come out and be according to the content visibility of the price.
								$priceDisplay = wc_price( $variation->get_price() );
							}
						}

						if ( $contentVisibility[ 'title' ] === true ) {
							$titleDisplay = $product->get_name();
							if ( ! is_null( $variation ) && $variation !== false ) {

								if ( strcmp( $titleType, 'full' ) === 0 ) {
									// In this scenario, we need to add the attribute title to the "full" title.
									// This is due to the fact that product and variation are provided, and the
									// above $product->get_name() only gets the "base" name
									if ( ! empty( $att_title ) ) {
										$titleDisplay .= ' - ' . $this->get_att_title( $variation );
									}
								}
								elseif ( strcmp( $titleType, 'att' ) === 0 ) {
									if ( $variation instanceof \WC_Product ) {
										$titleDisplay = $this->get_att_title( $variation );
									}
								}
							} else {
								
								if ( strcmp( $titleType, 'base' ) === 0 ) {
									// In this scenario, we need to remove the attribute title from the "full" title.
									// This is due to the fact that ponly the product is provided, and the
									// above $product->get_name() gets the "full" name
									$titleDisplay = $product->get_title();
								}
								elseif ( strcmp( $titleType, 'att' ) === 0 ) {
									$titleDisplay = $this->get_att_title( $product );
								}
							}
						}

						$titleDisplay = esc_html( $titleDisplay );
						if ( '' !== $titleAction ) {
							if ( strcmp( $titleAction, 'link' ) === 0 ) {
								$titleDisplay = '<a href="' . $product->get_permalink() . '">' . $titleDisplay . '</a>'; 
							}
						}

						?>
						<div class="add-to-cart-pro <?php echo esc_attr( $extraClasses ); ?>">
							<?php foreach( $contentOrder as $item ) : ?>
								<?php if ( in_array( $item, $available_elements ) ) : ?>
									<?php if ( strcmp( $item, 'title' ) === 0 && $contentVisibility[ $item ] === true  ) : ?>
										<span class="ea-line ea-text ea-title">
											<span><?php echo $titleDisplay; ?></span>
										</span>
									<?php endif; ?>
									<?php if ( strcmp( $item, 'price' ) === 0 && $contentVisibility[ $item ] === true   ) : ?>
										<span class="ea-line ea-text ea-price">
											<?php echo $priceDisplay; ?>
										</span>
									<?php endif; ?>
									<?php if ( strcmp( $item, 'quantity' ) === 0 ) : ?>
										<?php $hidden = ( $contentVisibility[ $item ] ? 'number' : 'hidden' ); ?>
										<span class="ea-line quantity-container">
											<div class="quantity">
												<input
													type="<?php esc_attr_e( $hidden ); ?>"
													id="<?php esc_attr_e( $input_id ); ?>"
													class="input-text qty text"
													value="<?php esc_attr_e( $quantity['default'] ); ?>"
													step="<?php esc_attr_e( $step ) ?>"
													min="<?php esc_attr_e( $min_value ); ?>"
													max="<?php esc_attr_e( (int)$max_value === -1 ? '' : $max_value ); ?>"
													name="quantity"
													title="<?php esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>"
													size="4"
													pattern="<?php esc_attr_e( $pattern ) ?>"
													inputmode="<?php esc_attr_e( $inputmode ) ?>"
												/>
											</div>
										</span>
									<?php endif; ?>
									<?php if( strcmp( $item, 'separator' ) === 0 && true === $contentVisibility[ $item ] ) : ?>
										<span class="ea-line ea-separator">
										</span>
									<?php endif; ?>
									<?php if( strcmp( $item, 'button' ) === 0 && true === $contentVisibility[ $item ] ) : ?>
										<button
											type="submit"
											class="a2cp_button button alt <?php echo esc_attr( $product->get_type() );?>"
											data-pid="<?php esc_attr_e( $product_id ); ?>"
											data-vid="<?php esc_attr_e( $variation_id ); ?>"
											<?php esc_attr_e( $disable_button ) ?>
										>
											<?php esc_html_e( $buttonText ); ?>
										</button>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
							<?php echo apply_filters( 'a2cp_button_row_additional_fields', '', $product_id, $variation_id ); ?>
						</div>
						<?php
					}
				}
				if ( $wrap_group === true ) {
					?>
					</div>
					<?php
				}
			}
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
			wp_die();
		}

		protected function parse_attributes( $attributes ) {
			// These should match what's set in JS `registerBlockType`.
			$buttonText = get_option( 'a2cp_default_text' );
			$buttonText = empty( $buttonText ) || false == $buttonText ? __( 'Add to cart', 'woocommerce' ) : $buttonText;

			$defaults = array(
				'contentVisibility' => array(
					'title'  => true,
					'price'  => true,
					'quantity' => true,
					'button' => true,
					'separator' => true,
				),
				'contentOrder' => array(
					'title',
					'separator',
					'price',
					'quantity',
					'button',
				),
				'quantity' => array(
					'default' => 1,
					'min' => 1,
					'max' => -1,
				),
				'buttonText' => $buttonText,
				'products' => array(),
				'variations' => array(),
				'titleType' => 'full',
				'align'	=> '',
				'className' => '',
			);

			return wp_parse_args( $attributes, $defaults );
		}

		// Is this actually being used correctly or did we regress further than expected?
		// TODO: make more generic
		protected function create_content_order_from_shortcode( $args ) {

			$contentOrder = array();
			$args = strtolower( $args );
			if ( strpos( $args, 'b' ) !== false ) {
				$contentOrder = array(
					'price',
					'separator',
					'title',
					'quantity',
					'button',
				);
			}
			elseif ( strpos( $args, 'a' ) !== false ) {
				$contentOrder = array(
					'title',
					'separator',
					'price',
					'quantity',
					'button',
				);
			}
			elseif ( strpos( $args, 'r' ) !== false ) {
				$contentOrder = array(
					'title',
					'quantity',
					'button',
					'separator',
					'price',
				);
			}

			return $contentOrder;
		}

		protected function set_visibility( $element, $visibility = true ) {
			$this->meta['contentVisibility'][$element] = $visibility;
		}

		protected function set_none_visible() {
			foreach ( $this->meta['contentVisibility'] as $element => $visibility ) {
				$this->meta['contentVisibility'][$element] = false;
			}
		}

		protected function set_content_order( $contentOrder ) {
			$this->meta['contentOrder'] = $contentOrder;
		}

		/**
		 * 
		 *		'title',
		 *		'separator',
		 *		'price',
		 *		'quantity',
		 *		'button',
		 *		'custom',
		 *		'image',
		 *		'short_description'
		 */
		protected function create_block_display_from_order( $order_string ) {
			$contentOrder = array();
			$this->set_none_visible();
			$args_long = strtolower( $order_string );
			$args = explode( ',', $args_long );
			foreach ( $args as $element ) {
				if ( strpos( $element, 't' ) !== false ) {
					$contentOrder[] = 'title';
					$this->set_visibility( 'title', true );
				}
				if ( strpos( $element, 's' ) !== false ) {
					$contentOrder[] = 'separator';
					$this->set_visibility( 'separator', true );
				}
				if ( strpos( $element, 'p' ) !== false ) {
					$contentOrder[] = 'price';
					$this->set_visibility( 'price', true );
				}
				if ( strpos( $element, 'q' ) !== false ) {
					$contentOrder[] = 'quantity';
					$this->set_visibility( 'quantity', true );
				}
				if ( strpos( $element, 'b' ) !== false ) {
					$contentOrder[] = 'button';
					$this->set_visibility( 'button', true );
				}
			}
			$this->set_content_order( $contentOrder );

			$returnData = array( 'contentOrder' => $this->meta['contentOrder'], 'contentVisibility' => $this->meta['contentVisibility'] );
			return $returnData;
		}

	}
}