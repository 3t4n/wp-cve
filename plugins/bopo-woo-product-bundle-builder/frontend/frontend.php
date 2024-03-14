<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VI_WOO_BOPO_BUNDLE_Frontend' ) ) {
	class VI_WOO_BOPO_BUNDLE_Frontend {
		protected $settings;
		protected static $ajax_loading = false;
		protected static $_instance = null;
		protected $language;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {
			$this->settings = VI_WOO_BOPO_BUNDLE_DATA::get_instance();
			$this->language = '';
			// Shortcode
			add_shortcode( 'bopobb_bundle', array( $this, 'bopobb_shortcode_bundle' ) );

			// Elementor
			add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'wp_enqueue_scripts_elementor' ) );

			// Enqueue frontend scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'bopobb_wp_enqueue_scripts' ) );

			// Register popup
			add_action( 'wp_footer', array( $this, 'bopobb_footer' ) );

			// Bundle in single product
			add_action( 'woocommerce_single_product_summary', array(
				$this,
				'bopobb_single_product_summary_bundled'
			), 6 );

			// Add to cart form & button
			add_filter( 'woocommerce_is_purchasable', array( $this, 'bopobb_is_purchasable' ), 20, 2 );
			add_action( 'woocommerce_bopobb_add_to_cart', array( $this, 'bopobb_add_to_cart_form' ) );
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'bopobb_add_to_cart_button' ) );

			// Check validation
			add_filter( 'woocommerce_add_to_cart_validation', array(
				$this,
				'bopobb_add_to_cart_validation'
			), 10, 2 );

			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'bopobb_add_cart_item_data' ), 10, 2 );
			add_action( 'woocommerce_add_to_cart', array( $this, 'bopobb_add_to_cart' ), 10, 6 );
			add_filter( 'woocommerce_get_cart_item_from_session', array(
				$this,
				'bopobb_get_cart_item_from_session'
			), 10, 2 );

			// Cart item
			add_filter( 'woocommerce_cart_item_quantity', array( $this, 'bopobb_cart_item_quantity' ), 10, 3 );
			add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'bopobb_cart_item_remove_link' ), 10, 2 );
			add_action( 'woocommerce_cart_item_removed', array( $this, 'bopobb_cart_item_removed' ), 10, 2 );
			add_filter( 'woocommerce_cart_item_price', array( $this, 'bopobb_cart_item_price' ), 10, 2 );
			add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'bopobb_cart_item_subtotal' ), 10, 2 );
			add_filter( 'woocommerce_cart_contents_count', array( $this, 'bopobb_cart_contents_count' ) );

			add_filter( 'woocommerce_cart_item_class', array( $this, 'bopobb_item_class' ), 10, 2 );
			add_filter( 'woocommerce_mini_cart_item_class', array( $this, 'bopobb_item_class' ), 10, 2 );

			// Undo remove
			add_action( 'woocommerce_cart_item_restored', array( $this, 'bopobb_cart_item_restored' ), 10, 2 );

			// Order
			add_filter( 'woocommerce_order_formatted_line_subtotal', array(
				$this,
				'bopobb_order_formatted_line_subtotal'
			), 10, 2 );
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'bopobb_checkout_create_order_line_item' ), 10, 3 );
			add_filter( 'woocommerce_order_item_class', array( $this, 'bopobb_item_class' ), 10, 2 );

			// Order again
			add_filter( 'woocommerce_order_again_cart_item_data', array(
				$this,
				'bopobb_order_again_cart_item_data'
			), 10, 2 );
			add_action( 'woocommerce_cart_loaded_from_session', array( $this, 'bopobb_cart_loaded_from_session' ) );

			// Admin order
			add_action( 'woocommerce_ajax_add_order_item_meta', array(
				$this,
				'bopobb_ajax_add_order_item_meta'
			), 10, 3 );
			add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'bopobb_hidden_order_item_meta' ), 10, 1 );
			add_action( 'woocommerce_before_order_itemmeta', array( $this, 'bopobb_before_order_item_meta' ), 10, 1 );

			// Shipping
			add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'bopobb_cart_shipping_packages' ), 99, 1 );

			// Coupons
			add_filter( 'woocommerce_coupon_is_valid_for_product', array(
				$this,
				'bopobb_coupon_is_valid_for_product'
			), 10, 4 );

			// Loop add-to-cart
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'bopobb_loop_add_to_cart_link' ), 99, 2 );

			// Use woocommerce_get_cart_contents instead of woocommerce_before_calculate_totals, prevent price error on mini-cart
			add_filter( 'woocommerce_get_cart_contents', array( $this, 'bopobb_get_cart_contents' ), 10, 1 );

			// Price html
			add_filter( 'woocommerce_get_price_html', array( $this, 'bopobb_get_price_html' ), 99, 2 );

			// Admin
			add_filter( 'display_post_states', array( $this, 'bopobb_display_post_states' ), 10, 2 );


			// Ajax
			add_action( 'wp_ajax_bopobb_product_list', array( $this, 'bopobb_product_list' ) );
			add_action( 'wp_ajax_nopriv_bopobb_product_list', array( $this, 'bopobb_product_list' ) );
			add_action( 'wp_ajax_bopobb_product_variations', array( $this, 'bopobb_product_variations' ) );
			add_action( 'wp_ajax_nopriv_bopobb_product_variations', array( $this, 'bopobb_product_variations' ) );
			add_action( 'wp_ajax_bopobb_product_gallery', array( $this, 'bopobb_product_gallery' ) );
			add_action( 'wp_ajax_nopriv_bopobb_product_gallery', array( $this, 'bopobb_product_gallery' ) );
		}

		public function bopobb_footer() {
			?>
            <div class="bopobb-area" id="bopobb-area">
                <div class="bopobb-inner">
                    <div class="bopobb-overlay"></div>
                    <div class="bopobb-popup">
                        <div class="bopobb-popup-inner">
                            <div class="bopobb-popup-header">
                                <div class="bopobb-popup-header-left">
                                    <span class="bopobb-btn-back bopobb-icon-previous" title="<?php esc_attr_e( 'Back', 'woo-bopo-bundle' ); ?>"></span></div>
                                <div class="bopobb-popup-title"><?php if ( empty( $this->language ) ) { echo esc_html( $this->settings->get_params( 'bopobb_popup_title' ) ); } else
                                    echo esc_html( $this->settings->get_params( 'bopobb_popup_title_' . $this->language ) ) ?></div>
                                <div class="bopobb-popup-header-right">
                                    <span class="bopobb-btn-close bopobb-icon-cross1" title="<?php esc_attr_e( 'Close', 'woo-bopo-bundle' ); ?>"></span></div>
                            </div>
                            <div class="bopobb-products-wrap">
                                <div class="bopobb-product-list" data-item=""></div>
                                <div class="bopobb-variation-list" data-item="" data-product=""></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

		function bopobb_shortcode_bundle( $atts ) {
			global $bopobb_shortcode_id;
			$is_current = false;
			$attributes = shortcode_atts(
				array(
					'id'          => '',
					'title'       => '',
					'title_size'  => '',
					'title_color' => '',
					'template'    => '',
				),
				$atts
			);
			if ( ! $attributes['id'] ) {
                global $product;
                if ( isset( $product ) && ! empty( $product ) ) {
                    //Elementor product
                    $is_current = true;
                    $_product = $product;
                } else {
				    return false;
				}
			} else {
				$_product = wc_get_product( absint( $attributes['id'] ) );
			}
			if ( ! $_product || ! $_product->is_type( 'bopobb' ) ) {
				return false;
			}
			$bundle_title       = $attributes['title'] ? sanitize_text_field( $attributes['title'] ) : $_product->get_title();
			$bundle_title_size  = $attributes['title_size'] ? sanitize_text_field( $attributes['title_size'] ) : '';
			$bundle_title_color = $attributes['title_color'] ? sanitize_text_field( $attributes['title_color'] ) : '';
			$bundle_template    = $attributes['template'] ? sanitize_text_field( $attributes['template'] ) : 'vertical-bundle';
			if ( ! in_array( $bundle_template, $this->settings->get_params( 'bopobb_template_title' ) ) ) {
				$bundle_template = 'vertical-bundle';
			}
			if ( $bopobb_shortcode_id === null ) {
				$bopobb_shortcode_id = 1;
			} else {
				$bopobb_shortcode_id ++;
			}
			$bopo_shortcode_class = 'bopobb-shortcode-' . $bopobb_shortcode_id;
			ob_start();
			?>
            <div class="bopobb-shortcode-form <?php echo esc_attr( $bopo_shortcode_class ) ?>">
            <?php if ( ! $is_current ) { ?>
                    <div class="bopobb-shortcode-title" style="<?php if ( $bundle_title_size ) {
                        echo esc_attr( 'font-size:' . $bundle_title_size );
                    }
                    if ( $bundle_title_color )
                        echo esc_attr( 'color:' . $bundle_title_color ) ?>"><?php echo esc_html( $bundle_title ); ?>
                    </div>
				<?php
                }
				$this->bopobb_show_bundle( $_product, $bopo_shortcode_class, $bundle_template );
				?>
                <p class="price">
					<?php
					echo wp_kses_post( $_product->get_price_html() );
					?>
                </p>
				<?php
				$this->bopobb_shortcode_cart_form( $_product );
				?>
            </div>
			<?php
			$bundled = ob_get_contents();
			ob_end_clean();

			return $bundled;
		}

		function bopobb_single_product_summary_bundled() {
			$this->bopobb_show_bundled();
		}

		function bopobb_show_bundled( $product = null, $mode = 'single' ) {
			if ( ! $product ) {
				global $product;
			}

			if ( ! $product || ! $product->is_type( 'bopobb' ) ) {
				return;
			}
			$tax_rates['include'] = wc_prices_include_tax() ? 1 : 0;
			$tax_rates['view'] = 'incl' === get_option( 'woocommerce_tax_display_shop' ) ? 1 : 0;
			$tax_rates['rate'] = VI_WOO_BOPO_BUNDLE_Helper::bopobb_tax_rate( $product );
			if ( ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() ) {
			    $tax_rates['exempt'] = 1;
			} else {
			    $tax_rates['exempt'] = 0;
			}

			$bundle_id = $product->get_id();

			if ( $items = $product->get_items() ) {

				$items_data       = $items['items'];
				$bopo_fixed_price = $items['fixed'];
				if ( $product->is_on_sale() ) {
					$bopo_fixed_sale = $items['fixed'];
				} else {
					$bopo_fixed_sale = '';
				}
				$product_arr = array();

				if ( isset( $_POST['bopobb_ids'] ) ) {
					$post_ids   = VI_WOO_BOPO_BUNDLE_Helper::bopohp_clean_ids( sanitize_text_field( $_POST['bopobb_ids'] ) );
					$post_items = $product->build_items( $post_ids );
					for ( $i = 0; $i < count( $post_items ); $i ++ ) {
						$post_prd = wc_get_product( $post_items[ $i ]['id'] );

						if (isset($items_data[$i])) {
                            $item_can_change = $this->bopobb_search_product( 2, 0, $items_data[$i] );
                        } else {
                            $item_can_change = true;
                        }

						if ( isset( $post_items[ $i ]['variations'] ) ) {
							$variation_title = VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $post_items[ $i ]['variations'], 1 );
						} else {
							$variation_title = '';
						}
						$post_title = $post_prd->get_title() . $variation_title;
						$post_image           = $post_prd->get_image( 'medium' );
						$post_discount_type   = isset( $items_data[ $i ]['bopobb_bpi_discount'] ) ? $items_data[ $i ]['bopobb_bpi_discount'] : '';
						$post_discount_number = isset( $items_data[ $i ]['bopobb_bpi_discount_number'] ) ? $items_data[ $i ]['bopobb_bpi_discount_number'] : 0;
						if ($post_discount_type == 1) {
						    $post_discount_number = apply_filters('bopobb_convert_currency_price', $post_discount_number);
						}
						$post_quantity        = isset( $items_data[ $i ]['bopobb_bpi_quantity'] ) ? $items_data[ $i ]['bopobb_bpi_quantity'] : 1;
						$post_stock           = $post_prd->get_stock_quantity();
						if ( $post_prd->get_manage_stock() ) {
							( ! empty( $post_prd->get_stock_quantity() ) ) ? $post_stock = $post_prd->get_stock_quantity() : $post_stock = 0;
						} else {
							$post_stock = - 1;
						};
						$post_price = round( wc_get_price_excluding_tax( $post_prd ), wc_get_price_decimals() );
						$bopo_product_variations = isset( $post_items[ $i ]['variations'] ) ? $post_items[ $i ]['variations'] : '';
						if ( $post_prd && $post_items[ $i ]['id'] && $post_items[ $i ]['qty'] ) {
							array_push( $product_arr, [
								$post_prd,
								$post_items[ $i ]['id'],
								$post_title,
								$post_image,
								$post_quantity,
								$post_price,
								$bopo_fixed_price,
								$bopo_fixed_sale,
								$post_discount_type,
								$post_discount_number,
								$post_stock,
								$bopo_product_variations,
								$item_can_change
							] );
						}
					}
				} else {
					for ( $i = 0; $i < $items['count']; $i ++ ) {

					    if (isset($items_data[$i])) {
                            $item_can_change = $this->bopobb_search_product( 2, 0, $items_data[$i] );
                        } else {
					        $item_can_change = true;
                        }

						$prd_data = $this->bopobb_get_item_product( $items_data, $i );
                        $bopo_product    = $prd_data['product'];
                        $bopo_product_id = $prd_data['product_id'];
                        $bopo_product_title = $prd_data['product_title'];
                        $bopo_product_image = $prd_data['product_image'];
                        $bopo_product_price = $prd_data['product_price'];
                        $bopo_product_stock = $prd_data['product_stock'];
                        $bopo_product_variations = $prd_data['product_variation'];
                        $discount_type   = isset( $items_data[ $i ]['bopobb_bpi_discount'] ) ? $items_data[ $i ]['bopobb_bpi_discount'] : '';
                        $discount_number = isset( $items_data[ $i ]['bopobb_bpi_discount_number'] ) ? $items_data[ $i ]['bopobb_bpi_discount_number'] : 0;
                        if ($discount_type) {
                            $discount_number = apply_filters('bopobb_convert_currency_price', $discount_number);
                        }
                        $quantity        = isset( $items_data[ $i ]['bopobb_bpi_quantity'] ) ? $items_data[ $i ]['bopobb_bpi_quantity'] : 1;
                        array_push( $product_arr, [
                            $bopo_product,
                            $bopo_product_id,
                            $bopo_product_title,
                            $bopo_product_image,
                            $quantity,
                            $bopo_product_price,
                            $bopo_fixed_price,
                            $bopo_fixed_sale,
                            $discount_type,
                            $discount_number,
                            $bopo_product_stock,
                            $bopo_product_variations,
                            $item_can_change
                        ] );
					}
				}
                if ( $this->settings->get_params( 'bopobb_single_template' ) == 1 ) {
                    $bopobb_template = 'vertical-bundle.php';
                } else {
                    $bopobb_template = 'horizontal-bundle.php';
                }
                bopobb_get_template( $bopobb_template, array(
                    'items'            => $items,
                    'bundle_id'        => $bundle_id,
                    'product_array'    => $product_arr,
                    'bopo_fixed_price' => $bopo_fixed_price,
                    'bopo_fixed_sale'  => $bopo_fixed_sale,
                    'bopo_tax_array'   => $tax_rates
                ), '', VI_WOO_BOPO_BUNDLE_TEMP_DIR );
			}
		}

		function bopobb_show_bundle( $product = null, $mode = '', $temp = '' ) {
			if ( ! $product ) {
				global $product;
			}

			if ( ! $product || ! $product->is_type( 'bopobb' ) ) {
				return;
			}
            $tax_rates['include'] = wc_prices_include_tax() ? 1 : 0;
            $tax_rates['view'] = 'incl' === get_option( 'woocommerce_tax_display_shop' ) ? 1 : 0;
			$tax_rates['rate'] = VI_WOO_BOPO_BUNDLE_Helper::bopobb_tax_rate( $product );
			if ( ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() ) {
			    $tax_rates['exempt'] = 1;
			} else {
			    $tax_rates['exempt'] = 0;
			}

			$bundle_id = $product->get_id();

			if ( $items = $product->get_items() ) {
				$items_data       = $items['items'];
				$bopo_fixed_price = $items['fixed'];
				if ( $product->is_on_sale() ) {
					$bopo_fixed_sale = $items['fixed'];
				} else {
					$bopo_fixed_sale = '';
				}
				$product_arr = array();

				for ( $i = 0; $i < $items['count']; $i ++ ) {
				    if (isset($items_data[$i])) {
                        $item_can_change = $this->bopobb_search_product( 2, 0, $items_data[$i] );
                    } else {
                        $item_can_change = true;
                    }
					$prd_data        = $this->bopobb_get_item_product( $items_data, $i );
					$bopo_product    = $prd_data['product'];
					$bopo_product_id = $prd_data['product_id'];
					$bopo_product_title = $prd_data['product_title'];
					$bopo_product_image = $prd_data['product_image'];
					$bopo_product_price = $prd_data['product_price'];
					$bopo_product_stock = $prd_data['product_stock'];
					$bopo_product_variations = $prd_data['product_variation'];
					$discount_type   = isset( $items_data[ $i ]['bopobb_bpi_discount'] ) ? $items_data[ $i ]['bopobb_bpi_discount'] : '';
					$discount_number = isset( $items_data[ $i ]['bopobb_bpi_discount_number'] ) ? $items_data[ $i ]['bopobb_bpi_discount_number'] : 0;
					if ($discount_type == 1) {
					    $discount_number = apply_filters('bopobb_convert_currency_price', $discount_number);
					}
					$quantity        = isset( $items_data[ $i ]['bopobb_bpi_quantity'] ) ? $items_data[ $i ]['bopobb_bpi_quantity'] : 1;
					array_push( $product_arr, [
						$bopo_product,
						$bopo_product_id,
						$bopo_product_title,
						$bopo_product_image,
						$quantity,
						$bopo_product_price,
						$bopo_fixed_price,
						$bopo_fixed_sale,
						$discount_type,
						$discount_number,
						$bopo_product_stock,
						$bopo_product_variations,
						$item_can_change
					] );
				}
				if ( $temp ) {
					$bopobb_template = $temp . '.php';
				} else {
					if ( $this->settings->get_params( 'bopobb_single_template' ) == 1 ) {
						$bopobb_template = 'vertical-bundle.php';
					} else {
						$bopobb_template = 'horizontal-bundle.php';
					}
				}
				bopobb_get_template( $bopobb_template, array(
					'items'            => $items,
					'bundle_id'        => $bundle_id,
					'product_array'    => $product_arr,
					'bopo_fixed_price' => $bopo_fixed_price,
					'bopo_fixed_sale'  => $bopo_fixed_sale,
					'bopo_tax_array'   => $tax_rates,
					'bopo_mode'        => $mode,
					'bopo_template'    => str_replace( '.php', '', $bopobb_template )
				), '', VI_WOO_BOPO_BUNDLE_TEMP_DIR );
			}
		}

		function bopobb_get_price_html( $price, $product ) {
			if ( $product && $product->is_type( 'bopobb' ) && ( $items = $product->get_items() ) ) {
				$product_id = $product->get_id();
				$include_tax = wc_prices_include_tax() ? 1 : 0;
				$show_tax = 'incl' === get_option( 'woocommerce_tax_display_shop' ) ? 1 : 0;

				if ( ! $product->is_fixed_price() ) {
					$discount_amount = $product->get_discount_amount();

					$price      = $price_sale = 0;
					$isSetPrice = true;
					for ( $i = 0; $i < $items['count']; $i ++ ) {
						if ( isset( $items['items'][ $i ] ) ) {
							if ( ! $items['items'][ $i ]['bopobb_bpi_set_default'] ) {
								$isSetPrice = false;
							}
						} else {
							$isSetPrice = false;
						}
					}
					if ( $isSetPrice ) {
						foreach ( $items['items'] as $item ) {
						    $_product = '';
							if ( isset( $item['bopobb_bpi_default_product'] ) ) {
								$_product = wc_get_product( $item['bopobb_bpi_default_product'] );

							} else {
								//query get default;
								$_product_array = $this->bopobb_search_product( 1, 0, $item );
								if ( $_product_array ) {
                                    $_productID     = $_product_array['product_id'];
                                    $_product       = wc_get_product( $_productID );
								}
							}
							if ( $_product ) {
								$_price = VI_WOO_BOPO_BUNDLE_Helper::bopohp_get_original_price_total( $_product, $item['bopobb_bpi_quantity'], 'min' );
								$price += $_price;

								if ( ! $item['bopobb_bpi_discount'] ) {
									// if haven't discount_amount, apply discount percentage
									$price_sale += round( ( $_price / 100 ) * $item['bopobb_bpi_discount_number'], wc_get_price_decimals() );
								} else {
									$price_sale += $item['bopobb_bpi_discount_number'];
								}
							}
						}

						$price_sale = $price - $price_sale;
						$price = VI_WOO_BOPO_BUNDLE_Helper::bopobb_price_show($product, $price);
						$price_sale = VI_WOO_BOPO_BUNDLE_Helper::bopobb_price_show($product, $price_sale);

						if ( $price_sale && $price_sale != $price ) {
							return wc_format_sale_price( wc_price( $price ), wc_price( $price_sale ) ) . $product->get_price_suffix();
						}

						return wc_price( $price ) . $product->get_price_suffix();
					}
				}
			}

			return $price;
		}

		function bopobb_display_post_states( $states, $post ) {
			if ( 'product' == get_post_type( $post->ID ) ) {
				if ( ( $_product = wc_get_product( $post->ID ) ) && $_product->is_type( 'bopobb' ) ) {
					$count = 0;

					if ( $items = $_product->get_items() ) {
						$count = count( $items['items'] );
					}

					$states[] = apply_filters( 'bopobb_post_states', '<span class="bopobb-state">' . sprintf( esc_html__( 'Bundle (%s)', 'woo-bopo-bundle' ), $count ) . '</span>', $count, $_product );
				}
			}

			return $states;
		}

		function bopobb_search_product( $val = 0, $get_variable = 1, $arg_input = [] ) {
			$cat     = isset( $arg_input['bopobb_pbi_category'] ) ? $arg_input['bopobb_pbi_category'] : '';
			$ex_cat  = isset( $arg_input['bopobb_pbi_category_exclude'] ) ? $arg_input['bopobb_pbi_category_exclude'] : '';
			$tag     = isset( $arg_input['bopobb_pbi_tag'] ) ? $arg_input['bopobb_pbi_tag'] : '';
			$ex_tag  = isset( $arg_input['bopobb_pbi_tag_exclude'] ) ? $arg_input['bopobb_pbi_tag_exclude'] : '';
			$prod    = isset( $arg_input['bopobb_pbi_title'] ) ? $arg_input['bopobb_pbi_title'] : '';
			$ex_prod = isset( $arg_input['bopobb_pbi_title_exclude'] ) ? $arg_input['bopobb_pbi_title_exclude'] : '';
			$sort    = isset( $arg_input['bopobb_bpi_sort'] ) ? $arg_input['bopobb_bpi_sort'] : '';
			$order   = isset( $arg_input['bopobb_bpi_order'] ) ? $arg_input['bopobb_bpi_order'] : '';
			$founded_count = 0;

			$arg     = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => - 1,
			);
			$tax_arr = [];
			if ( ! empty( $cat ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat,
				];
			}
			if ( ! empty( $ex_cat ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $ex_cat,
					'operator' => 'NOT IN',
				];
			}
			if ( ! empty( $tag ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
				];
			}
			if ( ! empty( $ex_tag ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $ex_tag,
					'operator' => 'NOT IN',
				];
			}
			if ( ! empty( $tax_arr ) ) {
				$arg['tax_query'] = $tax_arr;
			}
			if ( ! empty( $prod ) ) {
				$arg['post__in'] = $prod;
			}

			switch ( $sort ) {
				case 'ratting':
					$arg['orderby']  = 'meta_value_num';
					$arg['meta_key'] = '_wc_average_rating';
					$arg['order']    = $order;
					break;
				case 'price':
					$arg['orderby']  = 'meta_value_num';
					$arg['meta_key'] = '_price';
					$arg['order']    = $order;
					break;
				default:
					$arg['orderby'] = 'title';
					$arg['order']   = $order;
					break;
			}

			$the_query      = new WP_Query( $arg );
			$founded_count = $the_query->found_posts;
			if ( $val == 2 && $founded_count > 1 ) {
			    return true;
			}
			$found_products = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$prd          = wc_get_product( get_the_ID() );
					$s_product_id = get_the_ID();

					if ( ! $prd->is_purchasable() ) {
						continue;
					}

					if ( ! empty( $ex_prod ) ) {
						if ( in_array( strval( $s_product_id ), array_values( $ex_prod ), true ) ) {
						    if ( $val == 2 && $founded_count == 1 ) {
						        wp_reset_postdata();

						        return true;
						    }
							continue;
						}
					}

					if ( ! in_array( $prd->get_type(), $this->settings->get_params( 'bopobb_type_include' ), true ) ) {
						continue;
					}

					if ( $val == 2 ) {
					    if ($prd->is_type('variable')) {
					        $product_children = $prd->get_children();
					        if ( count( $product_children ) == 1 ) {
                                $valriation_prd = wc_get_product( $product_children[ 0 ] );
                                $variation_any                 = false;
                                $child_wc_variation_attributes = $valriation_prd->get_variation_attributes();

                                foreach ( $child_wc_variation_attributes as $attr_v ) {
                                    if ( empty( $attr_v ) ) {
                                        $variation_any = true;
                                    }
                                }
                                if (!$variation_any && $valriation_prd->is_purchasable()) {
                                    wp_reset_postdata();

                                    return false;
                                } else {
                                    wp_reset_postdata();

					                return true;
                                }
					        } else {
					            wp_reset_postdata();

					            return true;
					        }
					    } else {
					        if ($prd->is_purchasable()) {
                                wp_reset_postdata();

                                return false;
					        } else {
					            wp_reset_postdata();

					            return true;
					        }
					    }
						continue;
					}

					// Get default
					if ( $val == 1 ) {
						if ( $get_variable ) {
							wp_reset_postdata();

							return $s_product_id;
						}
						if ( $prd->is_type( 'variable' ) ) {
							$product_children = $prd->get_children();
							// Check child valid for sale
							if ( count( $product_children ) ) {
								for ( $i = 0; $i < count( $product_children ); $i ++ ) {
									$valriation_prd = wc_get_product( $product_children[ $i ] );
									if ( $valriation_prd->is_purchasable() && $valriation_prd->get_price() &&
									     ( ( $valriation_prd->get_manage_stock() && $valriation_prd->get_stock_quantity() ) || ( ! $valriation_prd->get_manage_stock() ) ) ) {
										$variation_any                 = false;
										$child_wc_variation_attributes = $valriation_prd->get_variation_attributes();

										foreach ( $child_wc_variation_attributes as $attr_v ) {
											if ( empty( $attr_v ) ) {
												$variation_any = true;
											}
										}

										if ( $variation_any ) {
											// Variation any
											$any_variations = VI_WOO_BOPO_BUNDLE_Helper::bopobb_get_variation_default( $prd, $valriation_prd, $child_wc_variation_attributes );
											$set_arr        = array();

											if ( ! empty( $any_variations ) ) {
												$key_arr = array_keys( $child_wc_variation_attributes );
												if ( is_array( $any_variations ) || is_object( $any_variations ) ) {
													if ( count( $key_arr ) == count( $any_variations ) ) {
														$set_arr = array_combine( $key_arr, $any_variations );
													}
												} else {
													$any_v = [ $any_variations ];
													if ( count( $key_arr ) == count( $any_variations ) ) {
														$set_arr = array_combine( $key_arr, $any_variations );
													}
												}
											} else {
												continue;
											}

											$default_array['product_id']        = $product_children[ $i ];
											$default_array['product_variation'] = VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $set_arr );
											wp_reset_postdata();

											return $default_array;
										} else {
											// Variation adequate
											$get_atts = $valriation_prd->get_variation_attributes();

											$default_array['product_id']        = $product_children[ $i ];
											$default_array['product_variation'] = VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $get_atts );
											wp_reset_postdata();

											return $default_array;
										}
									}
								}
								continue;
							} else {
								continue;
							}

						} else if ( $prd->is_purchasable() ) {
							// Simple
							wp_reset_postdata();
							$default_array['product_id'] = $s_product_id;

							return $default_array;
						} else {
							continue;
						}
					}
					$product_title = get_the_title();
					$the_product   = new WC_Product( $s_product_id );
					if ( ! $the_product->is_in_stock() ) {
						$product_title .= ' (out-of-stock)';
					}
					$found_products[] = $s_product_id;
				}
				wp_reset_postdata();

				return $found_products;
			}
			wp_reset_postdata();

			return null;
		}

		function bopobb_product_list() {
			check_ajax_referer( 'bopo-nonce', 'nonce' );
			if ( isset( $_POST['item'] ) && ( $_POST['item'] !== '' ) ) {
				$item_index = absint( $_POST['item'] );
			} else {
				return;
			}
			if ( isset( $_POST['product'] ) && ( $_POST['product'] !== '' ) ) {
				$p_product_id = absint( $_POST['product'] );
			} else {
				return;
			}
			$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
			$per_page = intval( $this->settings->get_params('bopobb_popup_page_items') ) != 0 ? intval( $this->settings->get_params('bopobb_popup_page_items') ) : 32;
			$product_bundle = wc_get_product( $p_product_id );
			if ( $items = $product_bundle->get_items() ) {
				$items_data    = $items['items'][ $item_index ];
				$i_product_ids = $this->bopobb_search_product( 0, 1, $items_data );
				if ( $i_product_ids ) {
				    $page_wrap = intval( $page * $per_page );
				    $page_end = $page_wrap > count( $i_product_ids ) ? count( $i_product_ids ) : $page_wrap;
				    $page_start = $page_wrap - $per_page;
					for ( $i = $page_start; $i < $page_end; $i ++ ) {
						$wc_product = wc_get_product( $i_product_ids[ $i ] );
						if ( ! $wc_product->is_purchasable() ) {
							continue;
						}
						if ( $wc_product->get_manage_stock() ) {
							( ! empty( $wc_product->get_stock_quantity() ) ) ? $wc_product_stock = $wc_product->get_stock_quantity() : $wc_product_stock = 0;
						} else {
							$wc_product_stock = - 1;
						};
						if ( empty( $wc_product_stock ) ) {
							continue;
						}
						if ( ! $wc_product->is_in_stock() ) {
						    continue;
						}
						?>
                        <div class="bopobb-product bopobb-product-id-<?php echo esc_attr( $i_product_ids[ $i ] ) ?>" data-product="<?php echo esc_attr( $i_product_ids[ $i ] ) ?>" data-type="<?php echo esc_attr( $wc_product->get_type() ); ?>"
                             data-price="<?php echo esc_attr( round( wc_get_price_excluding_tax( $wc_product ), wc_get_price_decimals() ) ); ?>">
                            <div class="bopobb-product-img-wrap" title="<?php esc_attr_e( 'Add', 'woo-bopo-bundle' ); ?>">
								<?php echo wp_kses_post( $wc_product->get_image( 'medium' ) ); ?>
                            </div>
                            <div class="bopobb-product-title-wrap">
                                <a class="bopobb-product-title" href="<?php echo esc_url( $wc_product->get_permalink() ) ?>" target="_blank">
									<?php echo wp_kses_post( $wc_product->get_title() ); ?>
                                </a>
                            </div>
                            <div class="bopobb-product-ratting">
								<?php if ( ! empty( $wc_product->get_rating_count() ) && $this->settings->get_params( 'bopobb_view_ratting' ) ) {
									echo wp_kses_post( wc_get_rating_html( $wc_product->get_average_rating() ) );
								}
								?>
                            </div>
                            <div class="bopobb-product-price">
								<?php echo wp_kses_post( $wc_product->get_price_html() );
								?>
                            </div>
                            <div class="bopobb-product-stock" data-stock="<?php echo esc_attr( $wc_product_stock ) ?>">
								<?php echo wp_kses_post( wc_get_stock_html( $wc_product ) ); ?>
                            </div>
							<?php
							if ( $this->settings->get_params( 'bopobb_view_description' ) ) { ?>
                                <div class="bopobb-product-description">
									<?php echo wp_kses_post( $wc_product->get_short_description() ); ?>
                                </div>
							<?php } ?>
                        </div>
						<?php
					}
					if ( count( $i_product_ids ) > $per_page ) {
					    $total_page = count( $i_product_ids ) % $per_page == 0 ?
					        intdiv( count( $i_product_ids ), $per_page) :
					        intdiv( count( $i_product_ids ), $per_page) + 1;
					    ?>
					    <div class="bopobb-product-paging-wrap"><div class="bopobb-product-paging">
					    <?php for ( $p = 1; $p <= $total_page; $p++ ) {
					        if ( $p == 1 || $p == $total_page) { ?>
					        <div class="bopobb-product-pages <?php
					        if ( $p == $page ) echo esc_attr('bopobb-product-page-active')?>" data-page="<?php
					        echo esc_attr( $p )?>"><?php echo esc_html( $p )?></div>
					    <?php continue; }
					        if ( $p <= $page ) {
					            if ( $p < $page - 2 ) continue;
					            if ( $p > $page - 2 ) {
					                //show
					                ?>
					                <div class="bopobb-product-pages <?php
                                    if ( $p == $page ) echo esc_attr('bopobb-product-page-active')?>" data-page="<?php
                                    echo esc_attr( $p )?>"><?php echo esc_html( $p )?></div>
					                <?php
					                continue;
					            }
					            if ( $p = $page - 2 ) {
					                //show
					                if ( $p != 2 ) {
					                ?>
					                    <div class="bopobb-product-pages-break">...</div>
					                <?php } ?>
					                <div class="bopobb-product-pages <?php
                                    if ( $p == $page ) echo esc_attr('bopobb-product-page-active')?>" data-page="<?php
                                    echo esc_attr( $p )?>"><?php echo esc_html( $p )?></div>
					                <?php
					                continue;
					            }
					        } else {
					            if ( $p > $page + 2 ) continue;
					            if ( $p < $page + 2 ) {
					                //show
					                ?>
					                <div class="bopobb-product-pages <?php
                                    if ( $p == $page ) echo esc_attr('bopobb-product-page-active')?>" data-page="<?php
                                    echo esc_attr( $p )?>"><?php echo esc_html( $p )?></div>
					                <?php
					                continue;
					            }
					            if ( $p == $page + 2 ) {
					                //show
					                ?>
					                <div class="bopobb-product-pages <?php
                                    if ( $p == $page ) echo esc_attr('bopobb-product-page-active')?>" data-page="<?php
                                    echo esc_attr( $p )?>"><?php echo esc_html( $p )?></div>
                                    <?php if ( $p + 1 != $total_page ) {
					                ?>
					                    <div class="bopobb-product-pages-break">...</div>
					                <?php }
					                continue;
					            }
					        }
					    } ?>
                        </div></div>
					<?php }
				}
			}
			wp_die();
		}

		function bopobb_product_variations() {
			check_ajax_referer( 'bopo-nonce', 'nonce' );
			if ( isset( $_POST['product'] ) && ( $_POST['product'] !== '' ) ) {
				$p_product_id = absint( $_POST['product'] );
			} else {
				return;
			}
			$product_variable = wc_get_product( $p_product_id );
			if ( $product_variable->has_child() && $product_variable->is_type( 'variable' ) ) {

				$product_children = $product_variable->get_children();
				$variation_arr    = [];

				if ( count( $product_children ) ) {
					foreach ( $product_children as $product_child ) {
						$child_wc = wc_get_product( $product_child );
						if ( ! $child_wc->is_purchasable() ) {
							continue;
						}
						if ( ! $child_wc->is_in_stock() ) {
						    continue;
						}
						$variation_any                 = false;
						$child_wc_variation_attributes = $child_wc->get_variation_attributes();

						foreach ( $child_wc_variation_attributes as $attr_k => $attr_v ) {
							if ( empty( $attr_v ) ) {
								$variation_any = true;
							}
						}
						if ( $variation_any ) {

							// simple compare array
							$any_variations = VI_WOO_BOPO_BUNDLE_Helper::bopobb_get_variations( $product_variable, $product_child, $child_wc_variation_attributes, $variation_arr );

							if ( ! empty( $any_variations ) ) {
								$any_to_all    = VI_WOO_BOPO_BUNDLE_Helper::bopobb_set_array( $any_variations, $product_child );
								$variation_arr = array_merge( $variation_arr, $any_to_all );
								foreach ( $any_variations as $any_v ) {
									$key_arr = array_keys( $child_wc_variation_attributes );
									if ( is_array( $any_v ) || is_object( $any_v ) ) {
										if ( count( $key_arr ) == count( $any_v ) ) {
											$set_arr = array_combine( $key_arr, $any_v );
											self::bopobb_get_variations_html( $p_product_id, $product_child, $set_arr );
										}
									} else {
										$any_v = [ $any_v ];
										if ( count( $key_arr ) == count( $any_v ) ) {
											$set_arr = array_combine( $key_arr, $any_v );
											self::bopobb_get_variations_html( $p_product_id, $product_child, $set_arr );
										}
									}
								}
							}
						} else {
							$achieve_arr        = [];
							$achieve_arr['id']  = $product_child;
							$child_wc_title     = '';
							$variable_title     = $product_variable->get_title();
							$get_atts           = $child_wc->get_variation_attributes();
							$is_variation_valid = VI_WOO_BOPO_BUNDLE_Helper::bopobb_is_variation_allow( $product_variable, $product_child, $get_atts, $variation_arr );
							if ( ! $is_variation_valid ) {
								continue;
							}
							foreach ( $get_atts as $att_k => $att_v ) {
								$cur_key       = substr( $att_k, 10 );
								$cur_term      = get_term_by( 'slug', $att_v, $cur_key );
								$achieve_arr[] = $att_v;
								if ( ! empty( $cur_term ) ) {
									$tax_name = wc_attribute_label( $cur_term->taxonomy );
									if ( empty( $child_wc_title ) ) {
										$child_wc_title = $tax_name . ': ' . $cur_term->name;
									} else {
										$child_wc_title .= ', ' . $tax_name . ': ' . $cur_term->name;
									}
									$variable_title .= ' - ' . $cur_term->name;
								} else {
									if ( empty( $child_wc_title ) ) {
										$child_wc_title = ucfirst( $cur_key ) . ': ' . $att_v;
									} else {
										$child_wc_title .= ', ' . ucfirst( $cur_key ) . ': ' . $att_v;
									}
									$variable_title .= ' - ' . $att_v;
								}
							}
							$variation_arr = array_merge( $variation_arr, [ $achieve_arr ] );

							if ( $child_wc->get_manage_stock() ) {
								( ! empty( $child_wc->get_stock_quantity() ) ) ? $wc_product_stock = $child_wc->get_stock_quantity() : $wc_product_stock = 0;
							} else {
								$wc_product_stock = - 1;
							};
							?>
                            <div class="bopobb-product" data-product="<?php echo esc_attr( $product_child ) ?>" data-type="variation"
                                 data-price="<?php echo esc_attr( round( wc_get_price_excluding_tax( $child_wc ), wc_get_price_decimals() ) ); ?>" <?php
							foreach ( $child_wc_variation_attributes as $attr_k => $attr_v ) {
								?> data-<?php echo esc_attr( $attr_k ) ?>="<?php echo esc_attr( $attr_v ) ?>"
								<?php
							}
							?> >
                                <input class="bopobb-product-variations" value="<?php echo esc_attr( VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $get_atts ) ) ?>"
                                       readonly="readonly">
                                <div class="bopobb-product-img-wrap" title="<?php esc_attr_e( 'Add', 'woo-bopo-bundle' ); ?>">
									<?php echo wp_kses_post( $child_wc->get_image( 'medium' ) ); ?>
                                </div>
                                <div class="bopobb-product-title-wrap">
                                    <a class="bopobb-product-title">
										<?php echo esc_html( $variable_title ); ?>
                                    </a>
                                    <a class="bopobb-variation-title" href="<?php echo esc_url( $child_wc->get_permalink() ) ?>" target="_blank">
										<?php echo esc_html( $child_wc_title ); ?>
                                    </a>
                                </div>
                                <div class="bopobb-product-ratting">
									<?php if ( ! empty( $product_variable->get_rating_count() ) && $this->settings->get_params( 'bopobb_view_ratting' ) ) {
										echo wp_kses_post( wc_get_rating_html( $product_variable->get_average_rating() ) );
									}
									?>
                                </div>
                                <div class="bopobb-product-price">
									<?php echo wp_kses_post( $child_wc->get_price_html() );
									?>
                                </div>
                                <div class="bopobb-product-stock" data-stock="<?php echo esc_attr( $wc_product_stock ) ?>">
									<?php if ( $this->settings->get_params( 'bopobb_view_stock' ) ) {
										echo wp_kses_post( wc_get_stock_html( $child_wc ) );
									}
									?>
                                </div>
								<?php
								if ( $this->settings->get_params( 'bopobb_view_description' ) ) { ?>
                                    <div class="bopobb-product-description">
										<?php echo esc_html( $product_variable->get_short_description() ); ?>
                                    </div>
								<?php } ?>
                            </div>
							<?php
						}
					}
					?>
                    <div class="bopobb-product-filter">
                        <div class="bopobb-filter-variations" cellspacing="0">
							<?php foreach ( $product_variable->get_variation_attributes() as $attribute_name => $options ) : ?>
                                <div class="bopobb-filter-variation">
                                    <div class="bopobb-attr-value">
                                        <?php
                                        $attribute_name_o = strtolower( $attribute_name );
                                        $attribute_name_o = preg_replace('/\s+/', '-', $attribute_name_o);
                                        ?>
                                        <select id="<?php echo esc_attr($attribute_name_o) ?>" name="<?php echo esc_attr( 'attribute_' . $attribute_name_o ) ?>"
                                        class="<?php echo esc_attr('bopobb-attr-select') ?>" data-attribute-name="<?php echo esc_attr('attribute_' . $attribute_name_o) ?>">
                                            <option value=""><?php esc_html_e('Filter by '); echo esc_html(wc_attribute_label( $attribute_name ))?></option>
                                            <?php foreach ($options as $option) {
                                                $cur_term      = get_term_by( 'slug', $option, $attribute_name );
                                                if ( $cur_term ) {
                                                    echo '<option value="' . esc_attr($option) . '">' . esc_attr($cur_term->name) . '</option>';
                                                } else {
													echo '<option value="' . esc_attr( $option ) . '">' . esc_html( $option ) . '</option>';
												}
                                            } ?>
                                        </select>
                                    </div>
                                </div>
							<?php endforeach;?>
                        </di>
                    </div>
                    <?php
				}
			}
			wp_die();
		}

		function bopobb_product_gallery() {
		    check_ajax_referer( 'bopo-nonce', 'nonce' );
		    if ( isset( $_POST['item'] ) && ( $_POST['item'] !== '' ) ) {
				$item_list = absint( $_POST['item'] );
			} else {
				return;
			}
			if ( isset( $_POST['product'] ) && ( $_POST['product'] !== '' ) ) {
				$p_product_id = absint( $_POST['product'] );
			} else {
				return;
			}
			global $post, $product;
			$post = get_post( $p_product_id );
			$product = wc_get_product($p_product_id);
			wc_get_template( 'single-product/product-image.php' );
			wp_reset_postdata();

			wp_die();
		}

		function bopobb_get_variations_html( $p_product_id, $product_child, $variation_arr ) {
			$product_variable              = wc_get_product( $p_product_id );
			$child_wc                      = wc_get_product( $product_child );
			$child_wc_title                = '';
			$child_variable_title          = $product_variable->get_title();

			foreach ( $variation_arr as $att_k => $att_v ) {
				$cur_key  = substr( $att_k, 10 );
				$cur_term = get_term_by( 'slug', $att_v, $cur_key );
				if ( ! empty( $cur_term ) ) {
					$tax_name = wc_attribute_label( $cur_term->taxonomy );
					if ( empty( $child_wc_title ) ) {
						$child_wc_title = $tax_name . ': ' . $cur_term->name;
					} else {
						$child_wc_title .= ', ' . $tax_name . ': ' . $cur_term->name;
					}
					$child_variable_title .= ' - ' . $cur_term->name;
				} else {
					if ( empty( $child_wc_title ) ) {
						$child_wc_title = ucfirst( $cur_key ) . ': ' . $att_v;
					} else {
						$child_wc_title .= ', ' . ucfirst( $cur_key ) . ': ' . $att_v;
					}
					$child_variable_title .= ' - ' . $att_v;
				}
			}
			if ( $child_wc->get_manage_stock() ) {
				( ! empty( $child_wc->get_stock_quantity() ) ) ? $wc_product_stock = $child_wc->get_stock_quantity() : $wc_product_stock = 0;
			} else {
				$wc_product_stock = - 1;
			};
			?>
            <div class="bopobb-product" data-product="<?php echo esc_attr( $product_child ) ?>" data-type="variation"
                 data-price="<?php echo esc_attr( round( wc_get_price_excluding_tax( $child_wc ), wc_get_price_decimals() ) ); ?>" <?php
			foreach ( $variation_arr as $attr_k => $attr_v ) {
				?> data-<?php echo esc_attr( $attr_k ) ?>="<?php echo esc_attr( $attr_v ) ?>"
				<?php
			}
			?> >
                <input class="bopobb-product-variations" value="<?php echo esc_attr( VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $variation_arr ) ) ?>" readonly="readonly">
                <div class="bopobb-product-img-wrap" title="<?php esc_attr_e( 'Add', 'woo-bopo-bundle' ); ?>">
					<?php echo wp_kses_post( $child_wc->get_image( 'medium' ) ); ?>
                </div>
                <div class="bopobb-product-title-wrap">
                    <a class="bopobb-product-title">
						<?php echo esc_html( $child_variable_title ); ?>
                    </a>
                    <a class="bopobb-variation-title" href="<?php echo esc_url( $child_wc->get_permalink() ) ?>" target="_blank">
						<?php echo esc_html( $child_wc_title ); ?>
                    </a>
                </div>
                <div class="bopobb-product-ratting">
					<?php if ( ! empty( $product_variable->get_rating_count() ) && $this->settings->get_params( 'bopobb_view_ratting' ) ) {
						echo wp_kses_post( wc_get_rating_html( $product_variable->get_average_rating() ) );
					}
					?>
                </div>
                <div class="bopobb-product-price">
					<?php echo wp_kses_post( $child_wc->get_price_html() );
					?>
                </div>
                <div class="bopobb-product-stock" data-stock="<?php echo esc_attr( $wc_product_stock ) ?>">
					<?php if ( $this->settings->get_params( 'bopobb_view_stock' ) ) {
						echo wp_kses_post( wc_get_stock_html( $child_wc ) );
					} ?>
                </div>
				<?php
				if ( $this->settings->get_params( 'bopobb_view_description' ) ) { ?>
                    <div class="bopobb-product-description">
						<?php echo esc_html( $product_variable->get_short_description() ); ?>
                    </div>
				<?php } ?>
            </div>
			<?php
		}

		protected function bopobb_get_item_product( $items_data, $index ) {
			$o_array                      = [];
			$o_array['product']           = '';
			$o_array['product_id']        = '';
			$o_array['product_title']     = '';
			$o_array['product_image']     = '';
			$o_array['product_price']     = 0;
			$o_array['product_stock']     = 0;
			$o_array['product_variation'] = '';
			if ( isset( $items_data[ $index ]['bopobb_bpi_set_default'] ) && $items_data[ $index ]['bopobb_bpi_set_default'] ) {
			    if ( isset( $items_data[ $index ]['bopobb_bpi_default_product'] ) ) {
			        $id_array = explode( '/', $items_data[ $index ]['bopobb_bpi_default_product'] );
			        $o_prd = wc_get_product( $id_array[0] );
			        if ( !$o_prd->is_purchasable() ) {$o_prd = '';}
			    } else {$o_prd = '';}
				if ( ! isset( $items_data[ $index ]['bopobb_bpi_default_product'] ) || empty( $o_prd ) ) {
					$i_product_array = $this->bopobb_search_product( 1, 0, $items_data[ $index ] );
					if ($i_product_array) {
                        $get_product     = wc_get_product( $i_product_array['product_id'] );
                        if ( isset( $i_product_array['product_variation'] ) ) {
                            // Variation product
                            $variable_id              = $get_product->get_parent_id();
                            $variable_prd             = wc_get_product( $variable_id );
                            $variation_title          = $variable_prd->get_title();
                            $variation_title          .= VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $i_product_array['product_variation'], 1 );
                            $o_array['product']       = $get_product;
                            $o_array['product_id']    = $i_product_array['product_id'];
                            $o_array['product_title'] = $variation_title;
                            $o_array['product_image'] = $get_product->get_image( 'medium' );
                            $o_array['product_price'] = round( wc_get_price_excluding_tax( $get_product ), wc_get_price_decimals() );

                            if ( $get_product->get_manage_stock() ) {
                                ( ! empty( $get_product->get_stock_quantity() ) ) ? $o_array['product_stock'] = $get_product->get_stock_quantity() : $o_array['product_stock'] = 0;
                            } else {
                                $o_array['product_stock'] = - 1;
                            };
                            $o_array['product_variation'] = $i_product_array['product_variation'];

                            return $o_array;

                        } else {
                            // Simple product
                            $o_array['product']       = $get_product;
                            $o_array['product_id']    = $i_product_array['product_id'];
                            $o_array['product_title'] = $get_product->get_title();
                            $o_array['product_image'] = $get_product->get_image( 'medium' );
                            $o_array['product_price'] = round( wc_get_price_excluding_tax( $get_product ), wc_get_price_decimals() );

                            if ( $get_product->get_manage_stock() ) {
                                ( ! empty( $get_product->get_stock_quantity() ) ) ? $o_array['product_stock'] = $get_product->get_stock_quantity() : $o_array['product_stock'] = 0;
                            } else {
                                $o_array['product_stock'] = - 1;
                            };
                            $o_array['product_variation'] = '';

                            return $o_array;
                        }
                    } else {

					    return $o_array;
                    }
				} else {
					// Meta default product
					$variation_title  = '';
					$variation_string = '';
					if ( count( $id_array ) > 1 ) {
						$variation_title  .= VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $id_array[1], 1 );
						$variation_string = $id_array[1];
					}
                    $o_array['product']           = wc_get_product( $id_array[0] );
                    $o_array['product_id']        = $id_array[0];
                    if ($o_prd->is_type('variation')) {
                        $variable_id              = $o_prd->get_parent_id();
                        $variable_prd             = wc_get_product( $variable_id );
                        $item_title = $variable_prd->get_title();
                        $o_array['product_price'] = round( wc_get_price_excluding_tax( $variable_prd ), wc_get_price_decimals() );
                    } else {
                        $item_title = $o_prd->get_title();
                        $o_array['product_price'] = round( wc_get_price_excluding_tax( $o_prd ), wc_get_price_decimals() );
                    }
                    $o_array['product_title']     = $item_title . $variation_title;
                    $o_array['product_image']     = $o_prd->get_image( 'medium' );
                    $o_array['product_variation'] = $variation_string;
                    if ( $o_prd->get_manage_stock() ) {
                        ( ! empty( $o_prd->get_stock_quantity() ) ) ? $o_array['product_stock'] = $o_prd->get_stock_quantity() : $o_array['product_stock'] = 0;
                    } else {
                        $o_array['product_stock'] = - 1;
                    };

					return $o_array;
				}
			}

			return $o_array;
		}

		function bopobb_is_bundled( $p_id, $vr_str ) {
			$p_product = wc_get_product( $p_id );
			if ( $p_product && $p_product->is_type( 'variable' ) ) {
				$child_atts = VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $vr_str );
				if ( $child_atts ) {
					$child_arr      = array_values( $child_atts );
					$aval_atts      = array();
					$child_products = $p_product->get_children();
					if ( count( $child_products ) ) {
						foreach ( $child_products as $product_child ) {
							$child_wc                      = wc_get_product( $product_child );
							$variation_any                 = false;
							$child_wc_variation_attributes = $child_wc->get_variation_attributes();

							foreach ( $child_wc_variation_attributes as $attr_k => $attr_v ) {
								if ( empty( $attr_v ) ) {
									$variation_any = true;
								}
							}
							if ( $variation_any ) {
								$any_variations = VI_WOO_BOPO_BUNDLE_Helper::bopobb_get_variations( $p_product, $product_child, $child_wc_variation_attributes, $aval_atts );
								if ( $any_variations ) {
									$any_to_all = VI_WOO_BOPO_BUNDLE_Helper::bopobb_set_array( $any_variations, $product_child );
									$aval_atts  = array_merge( $aval_atts, $any_to_all );
								}
							} else {
								$achieve_arr        = [];
								$achieve_arr['id']  = $product_child;
								$child_wc_title     = '';
								$get_atts           = $child_wc->get_variation_attributes();
								$is_variation_valid = VI_WOO_BOPO_BUNDLE_Helper::bopobb_is_variation_allow( $p_product, $product_child, $get_atts, $aval_atts );
								if ( ! $is_variation_valid ) {
									continue;
								}
								foreach ( $get_atts as $att_k => $att_v ) {
									$achieve_arr[] = $att_v;
								}
								$aval_atts = array_merge( $aval_atts, [ $achieve_arr ] );
							}
						}
					}
					$aval_atts = VI_WOO_BOPO_BUNDLE_Helper::bopobb_get_simple_compare( $aval_atts );
					if ( in_array( $child_arr, $aval_atts ) ) {
						return true;
					}
				}
			}

			return false;
		}

		function bopobb_calc_price( $id, $index, $quantity, $discount = array() ) {
			$price      = array();
			$_price     = 0;
			$price_sale = 0;
			$_product   = wc_get_product( $id );
			if ( $_product ) {
				$_price = VI_WOO_BOPO_BUNDLE_Helper::bopohp_get_original_price_total( $_product, $quantity, 'min' );

				if ( $discount[ $index ]['by'] == 0 ) {
					$price_sale += round( ( $_price / 100 ) * $discount[ $index ]['number'], wc_get_price_decimals() );
				} else {
					$price_sale += $discount[ $index ]['number'] >= $_price ? $_price : $discount[ $index ]['number'];
				}
			}

			$price_sale     = $_price - $price_sale;
			$price['price'] = $_price;
			$price['sale']  = $price_sale;

			return $price;
		}

		function bopobb_is_purchasable( $purchasable, $product ) {
			if ( $product->exists() && ( 'publish' === $product->get_status() || current_user_can( 'edit_post', $product->get_id() ) ) ) {
				if ( $product->get_price() === '' && ! $product->is_type( 'bopobb' ) ) {return $purchasable;} else {return true;}
			}

			return $purchasable;
		}

		function woocommerce_product_get_gallery_image_ids( $value, $this_object ) {
		    if (!$this_object->is_type( 'bopobb' )) {
		        return $value;
		    }

		    if ( $items = $this_object->get_items() ) {
		        if ( isset( $_POST['item'] ) && $_POST['item'] !== '' ) {
		            $p_items = (array) sanitize_text_field( $_POST['item'] );
		            $p_items = array_map( 'esc_attr', $p_items );
		            foreach ($p_items as $p_item) {
		                $post_prd = wc_get_product( $p_item );

                        $image_id = $post_prd->get_image_id();
                            // set val
                        if (!empty($image_id)) {
                            if (!in_array($image_id, $value)) {
                                $value[] = $image_id;
                            }
                        }

                        if ($post_prd->is_type('variation')) {
                            $variation_id = $post_prd->get_parent_id();
                            $variation_prd = wc_get_product($variation_id);
                            $gallery_ids = $variation_prd->get_gallery_image_ids();
                        } else {
                            $gallery_ids = $post_prd->get_gallery_image_ids();
                        }
                        if (count($gallery_ids) > 0) {
                            foreach ($gallery_ids as $gallery_id) {
                                if (!in_array($gallery_id, $value)) {
                                    $value[] = $gallery_id;
                                }
                            }
                        }
		            }

                    return $value;
		        }
                if ( isset( $_POST['bopobb_ids'] ) ) {
                    // reload previous items images
                    global $product;
                    $items_data       = $items['items'];
                    $post_ids   = VI_WOO_BOPO_BUNDLE_Helper::bopohp_clean_ids( sanitize_text_field( $_POST['bopobb_ids'] ) );
                    $post_items = $product->build_items( $post_ids );
                    for ( $i = 0; $i < count( $post_items ); $i ++ ) {
                        $post_prd = wc_get_product( $post_items[ $i ]['id'] );

                        $image_id = $post_prd->get_image_id();
                            // set val
                        if (!empty($image_id)) {
                            if (!in_array($image_id, $value)) {
                                $value[] = $image_id;
                            }
                        }

                        if ($post_prd->is_type('variation')) {
                            $variation_id = $post_prd->get_parent_id();
                            $variation_prd = wc_get_product($variation_id);
                            $gallery_ids = $variation_prd->get_gallery_image_ids();
                        } else {
                            $gallery_ids = $post_prd->get_gallery_image_ids();
                        }
                        if (count($gallery_ids) > 0) {
                            foreach ($gallery_ids as $gallery_id) {
                                if (!in_array($gallery_id, $value)) {
                                    $value[] = $gallery_id;
                                }
                            }
                        }
                    }
                } else {
                    // default load
                    foreach ( $items['items'] as $item ) {
                        if ( isset($item['bopobb_bpi_set_default']) && $item['bopobb_bpi_set_default'] == 1 ) {
                            if ( isset( $item['bopobb_bpi_default_product'] ) && !empty( ( $item['bopobb_bpi_default_product'] ) ) ) {
                                // set def
                                $id_array         = explode( '/', $item['bopobb_bpi_default_product'] );
                                if (!isset($id_array[0])) {
                                    continue;
                                }
                                $_product = wc_get_product(intval( $id_array[0] ));

                            } else {
                                // search def
                                $i_product_array = $this->bopobb_search_product( 1, 0, $item );
                                $_product     = wc_get_product( $i_product_array['product_id'] );
                            }

                            $image_id = $_product->get_image_id();
                                // set val
                            if (!empty($image_id)) {
                                if (!in_array($image_id, $value)) {
                                    $value[] = $image_id;
                                }
                            }

                            if ($_product->is_type('variation')) {
                                $variation_id = $_product->get_parent_id();
                                $variation_prd = wc_get_product($variation_id);
                                $gallery_ids = $variation_prd->get_gallery_image_ids();
                            } else {
                                $gallery_ids = $_product->get_gallery_image_ids();
                            }
                            if (count($gallery_ids) > 0) {
                                foreach ($gallery_ids as $gallery_id) {
                                    if (!in_array($gallery_id, $value)) {
                                        $value[] = $gallery_id;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $value;
		}

		function bopobb_single_product_image_thumbnail_html( $html , $post_thumbnail_id ) {

            global $product;
		    if ( ! $product || ! $product->is_type( 'bopobb' ) ) {
				return $html;
			}
            if ( isset( $_POST['bopobb_ids'] ) ) {
                // reload previous items images
            } else {
                // default load
                if ( $items = $product->get_items() ) {
                    foreach ( $items['items'] as $item ) {
                        if ( isset($item['bopobb_bpi_set_default']) && $item['bopobb_bpi_set_default'] == 1 ) {
                            if ( isset( $item['bopobb_bpi_default_product'] ) && !empty( ( $item['bopobb_bpi_default_product'] ) ) ) {
                                // set def
                                $id_array         = explode( '/', $item['bopobb_bpi_default_product'] );
                                if (!isset($id_array[0])) {
                                    return $html;
                                }
                                $_product = wc_get_product(intval( $id_array[0] ));
                                $image_id = $_product->get_image_id();

                                if ($_product->is_type('variation')) {
                                    $variation_id = $_product->get_parent_id();
                                    $variation_prd = wc_get_product($variation_id);
                                    $gallery_ids = $variation_prd->get_gallery_image_ids();
                                } else {
                                    $gallery_ids = $_product->get_gallery_image_ids();
                                }

                                if ($product->get_image_id() != $post_thumbnail_id) {

                                    $is_gallery = strpos($html,'<div class="col">');

                                    if ($is_gallery == 0 && $is_gallery != '' ) {
                                        $gallery_thumbnail = wc_get_image_size( apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_gallery_thumbnail' ) );
                                        $image       = wp_get_attachment_image_src( $image_id, apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_' . 'gallery_thumbnail' ) );
                                        $image_alt   = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                                        $image       = '<img src="' . $image[0] . '" alt="' . $image_alt . '" width="' . $gallery_thumbnail['width'] . '" height="' . $gallery_thumbnail['height'] . '"  class="attachment-woocommerce_thumbnail" />';

                                        $html .= sprintf( '<div class="col"><a>%s</a></div>', $image );
                                        if (count($gallery_ids) > 0) {
                                            foreach ($gallery_ids as $gallery_id) {
                                                $image       = wp_get_attachment_image_src( $gallery_id, apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_' . 'gallery_thumbnail' ) );
                                                $image_alt   = get_post_meta( $gallery_id, '_wp_attachment_image_alt', true );
                                                $image       = '<img src="' . $image[0] . '" alt="' . $image_alt . '" width="' . $gallery_thumbnail['width'] . '" height="' . $gallery_thumbnail['height'] . '"  class="attachment-woocommerce_thumbnail" />';

                                                $html .= sprintf( '<div class="col"><a>%s</a></div>', $image );
                                            }
                                        }
                                    } else {
                                        $html .= wc_get_gallery_image_html($image_id);
                                        if (count($gallery_ids) > 0) {
                                            foreach ($gallery_ids as $gallery_id) {
                                                $html .= wc_get_gallery_image_html($gallery_id);
                                            }
                                        }
                                    }
                                }
                            } else {
                                // search def

                            }
                        }
                    }
                }
            }

            return $html;
		}

		function bopobb_shortcode_cart_form( $product = null ) {

			if ( ! $product || ! $product->is_type( 'bopobb' ) ) {
				return;
			}

			wc_setup_product_data( $product->get_id() );
			wc_get_template( 'single-product/add-to-cart/simple.php' );

			wp_reset_postdata();
		}

		function bopobb_add_to_cart_form() {
			global $product;

			if ( ! $product || ! $product->is_type( 'bopobb' ) ) {
				return;
			}

			wc_get_template( 'single-product/add-to-cart/simple.php' );
		}

		function bopobb_add_to_cart_button() {
			global $product;
			$ids = '';
			if ( $product && $product->is_type( 'bopobb' ) ) {
				if ( $items = $product->get_items() ) {
					$isDefault    = true;
					$isGetDefault = true;
					foreach ( $items['items'] as $item ) {
						if ( $item['bopobb_bpi_set_default'] != 1 || ! isset( $item['bopobb_bpi_default_product'] ) ) {
							$isDefault = false;
						}
						if ( $item['bopobb_bpi_set_default'] != 1 ) {
							$isGetDefault = false;
						}
					}
					if ( $isDefault ) {
						// All item has default in meta
						$ids = $product->get_ids();
					} else if ( $isGetDefault ) {
						// Search default
						$ids = '';
						foreach ( $items['items'] as $item ) {
							$cart_product_array = $this->bopobb_search_product( 1, 0, $item );
							if ( empty( $cart_product_array ) ) {
								break;
							}
							$cart_product_id       = $cart_product_array['product_id'];
							$cart_variation_string = isset( $cart_product_array['product_variation'] ) ? '/' . $cart_product_array['product_variation'] : '';

							if ( $ids == '' ) {
								$ids .= $cart_product_id . '/' . $item['bopobb_bpi_quantity'] . $cart_variation_string;
							} else {
								$ids .= ',' . $cart_product_id . '/' . $item['bopobb_bpi_quantity'] . $cart_variation_string;
							}
						}
					} else {
						$ids = '';
					}

					echo '<input name="bopobb_ids" class="bopobb_ids bopobb-ids" type="hidden" value="' . esc_attr( $ids ) . '"/>';
				}
			}
		}

		function bopobb_check_in_cart( $product_id ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( $cart_item['product_id'] === $product_id ) {
					return true;
				}
			}

			return false;
		}

		function bopobb_add_to_cart_validation( $passed, $product_id ) {
			$b_product = wc_get_product( $product_id );

			if ( $b_product && $b_product->is_type( 'bopobb' ) ) {
				$items = '';
				if ( isset( $_REQUEST['bopobb_ids'] ) ) {
					$ids   = VI_WOO_BOPO_BUNDLE_Helper::bopohp_clean_ids( sanitize_text_field( $_REQUEST['bopobb_ids'] ) );
					$items = $b_product->build_items( $ids );
				}
				if ( !isset( $ids ) || empty( $ids ) ) {
				    wc_add_notice( esc_html__( 'Bundled products is unavailable.', 'woo-bopo-bundle' ), 'error' );
                    wc_add_notice( esc_html__( 'Please select all bundle items before add to cart.', 'woo-bopo-bundle' ), 'error' );

                    return false;
				}
				$bundles_data = $b_product->get_items();
				$qty = isset( $_REQUEST['quantity'] ) ? absint( $_REQUEST['quantity'] ) : 1;

				if ( ! empty( $items ) ) {
					foreach ( $items as $items_i => $item ) {
						$_id  = $item['id'];
						$_qty = $item['qty'];
						if ( ! isset( $bundles_data['items'][ $items_i ] ) ) {
							wc_add_notice( esc_html__( 'One of the bundled products is unavailable.', 'woo-bopo-bundle' ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}
						$items_data    = $bundles_data['items'][ $items_i ];
						$i_product_ids = $this->bopobb_search_product( 0, 1, $items_data );
						$_variant      = isset( $item['variations'] ) ? $item['variations'] : '';
						$_product      = wc_get_product( $item['id'] );

						if ( ! $_product ) {
							wc_add_notice( esc_html__( 'One of the bundled products is unavailable.', 'woo-bopo-bundle' ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( $_product->is_type( 'variable' ) || $_product->is_type( 'bopobb' ) ) {
							wc_add_notice( sprintf( esc_html__( '"%s" is un-purchasable.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( $_product->is_type( 'variation' ) ) {
							$variable_id = $_product->get_parent_id();
							if ( $variable_id && $i_product_ids && ! in_array( $variable_id, $i_product_ids ) ) {
								wc_add_notice( sprintf( esc_html__( '"%s" is not bundled of "%s".', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ), esc_html( $b_product->get_name() ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

								return false;
							}
							if ( $variable_id && ! self::bopobb_is_bundled( $variable_id, $_variant ) ) {
								wc_add_notice( sprintf( esc_html__( '"%s" is invalid variation.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

								return false;
							}
						}

						if ( ! $_product->is_type( 'variation' ) ) {
							if ( $i_product_ids && ! in_array( $_id, $i_product_ids ) ) {
								wc_add_notice( sprintf( esc_html__( '"%s" is not bundled of "%s".', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ), esc_html( $b_product->get_name() ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

								return false;
							}
						}

						if ( $_qty != $items_data['bopobb_bpi_quantity'] ) {
							wc_add_notice( sprintf( esc_html__( '"%s" requested quantity is not available in this bundle.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( ! $_product->is_in_stock() || ! $_product->is_purchasable() ) {
							wc_add_notice( sprintf( esc_html__( '"%s" is un-purchasable.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( ! $_product->has_enough_stock( $_qty * $qty ) ) {
							wc_add_notice( sprintf( esc_html__( '"%s" has not enough stock.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( $_product->is_sold_individually() && $this->bopobb_check_in_cart( $_id ) ) {
							wc_add_notice( sprintf( esc_html__( 'You cannot add another "%s" to the cart.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}

						if ( $_product->managing_stock() ) {
							$products_qty_in_cart = WC()->cart->get_cart_item_quantities();

							if ( isset( $products_qty_in_cart[ $_product->get_stock_managed_by_id() ] ) && ! $_product->has_enough_stock( $products_qty_in_cart[ $_product->get_stock_managed_by_id() ] + $_qty * $qty ) ) {
								wc_add_notice( sprintf( esc_html__( '"%s" has not enough stock.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

								return false;
							}
						}

						if ( post_password_required( $_id ) ) {
							wc_add_notice( sprintf( esc_html__( '"%s" is protected and cannot be purchased.', 'woo-bopo-bundle' ), esc_html( $_product->get_name() ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this bundle to the cart.', 'woo-bopo-bundle' ), 'error' );

							return false;
						}
					}
				} else {

					return false;
				}
			}

			return $passed;
		}


		function bopobb_add_cart_item_data( $cart_item_data, $product_id ) {
			$_product = wc_get_product( $product_id );

			if ( $_product && $_product->is_type( 'bopobb' ) ) {
				$ids = $_product->get_ids();
				// make sure that is bundle
				if ( isset( $_REQUEST['bopobb_ids'] ) ) {
					$ids = VI_WOO_BOPO_BUNDLE_Helper::bopohp_clean_ids( sanitize_text_field( $_REQUEST['bopobb_ids'] ) );
					unset( $_REQUEST['bopobb_ids'] );
				}

				if ( ! empty( $ids ) ) {
					$cart_item_data['bopobb_ids'] = $ids;
				}
			}

			return $cart_item_data;
		}

		function bopobb_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
			if ( ! empty( $cart_item_data['bopobb_ids'] ) ) {
				$items = WC()->cart->cart_contents[ $cart_item_key ]['data']->build_items( $cart_item_data['bopobb_ids'] );
				$this->bopobb_add_to_cart_items( $items, $cart_item_key, $product_id, $quantity );
			}
		}

		function bopobb_add_custom_price( $cart_object ) {
			foreach ( $cart_object->get_cart() as $cart_key => $cart_item_data ) {
				if ( isset( $cart_item_data['bopobb_ids'] ) && isset( $cart_item_data['bopobb_price'] ) &&
				     isset( $cart_item_data['bopobb_fixed_price'] ) && ! $cart_item_data['bopobb_fixed_price'] ) {
					$cart_item_data['data']->set_price( $cart_item_data['bopobb_price'] );
				}
			}
		}

		function bopobb_add_to_cart_items( $items, $cart_item_key, $product_id, $quantity ) {
			$items = VI_WOO_BOPO_BUNDLE_Helper::bopohp_minify_items( $items );

			$fixed_price    = WC()->cart->cart_contents[ $cart_item_key ]['data']->is_fixed_price();
			$discount_array = WC()->cart->cart_contents[ $cart_item_key ]['data']->get_discount_amount();

			// save current key associated with bopobb_parent_key
			WC()->cart->cart_contents[ $cart_item_key ]['bopobb_key']             = $cart_item_key;
			WC()->cart->cart_contents[ $cart_item_key ]['bopobb_fixed_price']     = $fixed_price;
			WC()->cart->cart_contents[ $cart_item_key ]['bopobb_discount_amount'] = $discount_array;
			if ( isset( $fixed_price ) && empty( $fixed_price ) ) {
			}
			if ( is_array( $items ) && ( count( $items ) > 0 ) ) {
				for ( $i = 0; $i < count( $items ); $i ++ ) {
					$_id      = $items[ $i ]['id'];
					$_qty     = $items[ $i ]['qty'];
					$_variant = isset( $items[ $i ]['variations'] ) ? $items[ $i ]['variations'] : '';
					$_product = wc_get_product( $items[ $i ]['id'] );

					if ( ! $_product || ( $_qty <= 0 ) || in_array( $_product->get_type(), $this->settings->get_params( 'bopobb_type_exclude' ), true ) ) {
						continue;
					}

					$_variation_id = '';
					$_variation    = array();
					$_price        = VI_WOO_BOPO_BUNDLE_Helper::bopohp_get_price( $_product, 'src' );

					if ( $_product instanceof WC_Product_Variation ) {
						// ensure we don't add a variation to the cart directly by variation ID
						$_variation_id = $_id;
						$_id           = $_product->get_parent_id();
						$_variation = VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $_variant );
					}


					if ( ! $fixed_price && is_array( $discount_array ) ) {
						if ( ! empty( $_variation_id ) ) {
							$calc_price = self::bopobb_calc_price( $_variation_id, $i, $_qty, $discount_array );
						} else {
							$calc_price = self::bopobb_calc_price( $_id, $i, $_qty, $discount_array );
						}
						$_price = $calc_price['sale'];
					}

					// add to cart
					$_data = array(
						'bopobb_qty'            => $_qty,
						'bopobb_price'          => $_price,
						'bopobb_parent_id'      => $product_id,
						'bopobb_parent_key'     => $cart_item_key,
						'bopobb_fixed_price'    => $fixed_price,
						'bopobb_discount_array' => $discount_array,
					);

					$_key = WC()->cart->add_to_cart( $_id, $_qty * $quantity, $_variation_id, $_variation, $_data );

					if ( empty( $_key ) ) {
						// can't add the bundled product
						if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['bopobb_keys'] ) ) {
							$keys = WC()->cart->cart_contents[ $cart_item_key ]['bopobb_keys'];

							foreach ( $keys as $key ) {
								// remove all bundled products
								WC()->cart->remove_cart_item( $key );
							}

							// remove the bundle
							WC()->cart->remove_cart_item( $cart_item_key );

							// break out of the loop
							break;
						}
					} elseif ( ! isset( WC()->cart->cart_contents[ $cart_item_key ]['bopobb_keys'] ) || ! in_array( $_key, WC()->cart->cart_contents[ $cart_item_key ]['bopobb_keys'], true ) ) {
						// save current key
						WC()->cart->cart_contents[ $_key ]['bopobb_key'] = $_key;

						// add keys for parent
						WC()->cart->cart_contents[ $cart_item_key ]['bopobb_keys'][] = $_key;
					}
				} // end foreach
			}
		}

		public function bopobb_cart_item_restored( $cart_item_key, $cart ) {
			if ( ! empty( $cart->cart_contents[ $cart_item_key ]['bopobb_ids'] ) ) {
				$bundled_item_cart_keys = $cart->cart_contents[ $cart_item_key ]['bopobb_keys'];
				foreach ( $bundled_item_cart_keys as $bundled_item_cart_key ) {
					$cart->restore_cart_item( $bundled_item_cart_key );
				}
			}
		}

		function bopobb_get_cart_item_from_session( $cart_item, $session_values ) {
			if ( isset( $session_values['bopobb_ids'] ) && ! empty( $session_values['bopobb_ids'] ) ) {
				$cart_item['bopobb_ids'] = $session_values['bopobb_ids'];
			}

			if ( isset( $session_values['bopobb_parent_id'] ) ) {
				$cart_item['bopobb_parent_id']  = $session_values['bopobb_parent_id'];
				$cart_item['bopobb_parent_key'] = $session_values['bopobb_parent_key'];
				$cart_item['bopobb_qty']        = $session_values['bopobb_qty'];
			}

			return $cart_item;
		}

		function bopobb_loop_add_to_cart_link( $link, $product ) {
			if ( $product->is_type( 'bopobb' ) ) {
				$link = str_replace( 'ajax_add_to_cart', '', $link );
			}

			return $link;
		}

		function bopobb_get_cart_contents( $cart_contents ) {
			foreach ( $cart_contents as $cart_item_key => $cart_item ) {
				// bundled products
				if ( ! empty( $cart_item['bopobb_parent_id'] ) ) {
					// remove orphaned bundled products
					if ( isset( $cart_item['bopobb_parent_key'] ) && ! empty( $cart_item['bopobb_parent_key'] ) ) {
						$parent_key = $cart_item['bopobb_parent_key'];

						if ( ! isset( $cart_contents[ $parent_key ] ) && ! ( $cart_item['data'] instanceof WC_Product_Subscription ) && ! ( $cart_item['data'] instanceof WC_Product_Subscription_Variation ) ) {
							unset( $cart_contents[ $cart_item_key ] );
							continue;
						}
					}

					// set price
					$cart_item['data']->set_price( 0 );

					// sync quantity
					if ( ! empty( $cart_item['bopobb_parent_key'] ) && ! empty( $cart_item['bopobb_qty'] ) ) {
						$parent_key = $cart_item['bopobb_parent_key'];

						if ( isset( $cart_contents[ $parent_key ] ) ) {
							$cart_contents[ $cart_item_key ]['quantity'] = $cart_item['bopobb_qty'] * $cart_contents[ $parent_key ]['quantity'];
						} elseif ( ( $parent_new_key = array_search( $parent_key, array_column( $cart_contents, 'bopobb_key', 'key' ) ) ) !== false ) {
							$cart_contents[ $cart_item_key ]['quantity'] = $cart_item['bopobb_qty'] * $cart_contents[ $parent_new_key ]['quantity'];
						}
					}
				}

				// bundles
				if ( ! empty( $cart_item['bopobb_ids'] ) && isset( $cart_item['bopobb_fixed_price'] ) && ! $cart_item['bopobb_fixed_price'] ) {
					// set price zero, calculate later

					if ( ! empty( $cart_item['bopobb_keys'] ) ) {
						$bundle_price = 0;

						foreach ( $cart_item['bopobb_keys'] as $key ) {
							if ( isset( $cart_contents[ $key ] ) ) {
//								$bundle_price += wc_get_price_excluding_tax( $cart_contents[ $key ]['data'], array(
//									'qty'   => 1,
//									'price' => $cart_contents[ $key ]['bopobb_price']
//								) );
								$bundle_price += floatval( $cart_contents[ $key ]['bopobb_price'] );
							}
						}
                        $bundle_price = VI_WOO_BOPO_BUNDLE_Helper::bopobb_price_standard( $cart_item['data'], $bundle_price );
						if ( $cart_item['quantity'] > 0 ) {
							$cart_contents[ $cart_item_key ]['bopobb_price'] = round( $bundle_price, wc_get_price_decimals() );
						}
					}
					if ( isset( $cart_contents[ $cart_item_key ]['bopobb_price'] ) && $cart_contents[ $cart_item_key ]['bopobb_price'] ) {
						$cart_item['data']->set_price( $cart_contents[ $cart_item_key ]['bopobb_price'] );
					} else {
						$cart_item['data']->set_price( 0 );
					}
				}
			}

			return $cart_contents;
		}

		function bopobb_cart_item_name( $name, $cart_item ) {
			if ( isset( $cart_item['bopobb_parent_id'] ) && ! empty( $cart_item['bopobb_parent_id'] ) ) {
				$variation_names = isset( $cart_item['variation'] ) ? VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_title( $cart_item['variation'] ) : '';
				if ( isset( $cart_item['product_id'] ) && ! empty( $cart_item['product_id'] ) && isset( $cart_item['variation_id'] ) && ! empty( $cart_item['variation_id'] ) ) {
					$name = '<a href="' . get_permalink( $cart_item['product_id'] ) . '">' . get_the_title( $cart_item['product_id'] ) . $variation_names . '</a>';
				}
				if ( ( strpos( $name, '</a>' ) !== false ) ) {
					return $name;
				}

				return strip_tags( $name );
			}

			return $name;
		}

		public function bopobb_cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
			if ( isset( $cart_item['bopobb_parent_id'] ) ) {
				return $cart_item['quantity'];
			}

			return $quantity;

		}

		public function bopobb_cart_item_remove_link( $link, $cart_item_key ) {
			if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['bopobb_parent_key'] ) ) {
				$parent_key = WC()->cart->cart_contents[ $cart_item_key ]['bopobb_parent_key'];

				if ( isset( WC()->cart->cart_contents[ $parent_key ] ) || array_search( $parent_key, array_column( WC()->cart->cart_contents, 'bopobb_key' ) ) !== false ) {
					return '';
				}
			}

			return $link;
		}

		// Set price for bundled item
		public function bopobb_cart_item_price( $price, $cart_item ) {
			if ( isset( $cart_item['bopobb_parent_id'], $cart_item['bopobb_price'], $cart_item['bopobb_fixed_price'] ) ) {
				return '';
			}

			return $price;
		}

		// Set subtotal price for bundled item
		function bopobb_cart_item_subtotal( $subtotal, $cart_item = null ) {
			$new_subtotal = false;

			if ( isset( $cart_item['bopobb_parent_id'], $cart_item['bopobb_price'], $cart_item['bopobb_fixed_price'] ) ) {
				$new_subtotal = true;
				$subtotal     = '';
			}

			if ( $new_subtotal && ( $cart_product = $cart_item['data'] ) ) {
				if ( $cart_product->is_taxable() ) {
					if ( WC()->cart->display_prices_including_tax() ) {
						if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
							$subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
						}
					} else {
						if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
							$subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
						}
					}
				}
			}

			return $subtotal;
		}

		function bopobb_cart_item_removed( $cart_item_key, $cart ) {
			if ( isset( $cart->removed_cart_contents[ $cart_item_key ]['bopobb_keys'] ) ) {
				$keys = $cart->removed_cart_contents[ $cart_item_key ]['bopobb_keys'];

				foreach ( $keys as $key ) {
					$cart->remove_cart_item( $key );

					if ( ( $new_key = array_search( $key, array_column( $cart->cart_contents, 'bopobb_key', 'key' ) ) ) !== false ) {
						$cart->remove_cart_item( $new_key );
					}
				}
			}
		}

		function bopobb_cart_contents_count( $count ) {
			// count for cart contents
			$cart_count = get_option( '_bopobb_cart_contents_count', 'bundle' );

			if ( $cart_count !== 'both' ) {
				$cart_contents = WC()->cart->cart_contents;

				foreach ( $cart_contents as $cart_item_key => $cart_item ) {
					if ( ( $cart_count === 'bundled_products' ) && ! empty( $cart_item['bopobb_ids'] ) ) {
						$count -= $cart_item['quantity'];
					}

					if ( ( $cart_count === 'bundle' ) && ! empty( $cart_item['bopobb_parent_id'] ) ) {
						$count -= $cart_item['quantity'];
					}
				}
			}

			return $count;
		}

		function bopobb_order_formatted_line_subtotal( $subtotal, $order_item ) {
			if ( isset( $order_item['_bopobb_parent_id'] ) ) {
				return '';
			}

			return $subtotal;
		}

		public function bopobb_add_order_item_meta( $item_id, $values, $cart_item_key ) {
			if ( isset( $values['bopobb_parent_id'] ) ) {
				wc_add_order_item_meta( $item_id, '_bopobb_parent_id', $values['bopobb_parent_id'] );
			}

			if ( isset( $values['bopobb_ids'] ) ) {
				wc_add_order_item_meta( $item_id, '_bopobb_ids', $values['bopobb_ids'] );
			}

			if ( isset( $values['bopobb_price'] ) ) {
				// use _ to hide the data
				wc_add_order_item_meta( $item_id, '_bopobb_price', $values['bopobb_price'] );
			}
		}

		function bopobb_checkout_create_order_line_item( $order_item, $cart_item_key, $values ) {
			if ( isset( $values['bopobb_parent_id'] ) ) {
				// use _ to hide the data
				$order_item->update_meta_data( '_bopobb_parent_id', $values['bopobb_parent_id'] );
			}

			if ( isset( $values['bopobb_ids'] ) ) {
				// use _ to hide the data
				$order_item->update_meta_data( '_bopobb_ids', $values['bopobb_ids'] );
			}

			if ( isset( $values['bopobb_price'] ) ) {
				// use _ to hide the data
				$order_item->update_meta_data( '_bopobb_price', $values['bopobb_price'] );
			}
		}

		function bopobb_item_class( $class, $cart_item ) {
			if ( isset( $cart_item['bopobb_parent_id'] ) ) {
				$class .= ' bopobb-cart-item bopobb-cart-child';
			} elseif ( isset( $cart_item['bopobb_ids'] ) ) {
				$class .= ' bopobb-cart-item bopobb-cart-parent';
			}

			return $class;
		}

		function bopobb_order_again_cart_item_data( $data, $cart_item ) {
			if ( isset( $cart_item['bopobb_ids'] ) ) {
				$data['bopobb_order_again'] = 'yes';
				$data['bopobb_ids']         = $cart_item['bopobb_ids'];
			}

			if ( isset( $cart_item['bopobb_parent_id'] ) ) {
				$data['bopobb_order_again'] = 'yes';
				$data['bopobb_parent_id']   = $cart_item['bopobb_parent_id'];
			}

			return $data;
		}

		function bopobb_cart_loaded_from_session() {
			foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
				if ( isset( $cart_item['bopobb_order_again'], $cart_item['bopobb_parent_id'] ) ) {
					WC()->cart->remove_cart_item( $cart_item_key );
				}

				if ( isset( $cart_item['bopobb_order_again'], $cart_item['bopobb_ids'] ) ) {
					$cart_item['data']->build_items( $cart_item['bopobb_ids'] );
					$items = $cart_item['data']->get_items();
					$this->bopobb_add_to_cart_items( $items, $cart_item_key, $cart_item['product_id'], $cart_item['quantity'] );
				}
			}
		}

		function bopobb_ajax_add_order_item_meta( $order_item_id, $order_item, $order ) {
			$quantity = $order_item->get_quantity();

			if ( 'line_item' === $order_item->get_type() ) {
				$product    = $order_item->get_product();
				$product_id = $product->get_id();

				if ( $product && $product->is_type( 'bopobb' ) && ( $items = $product->get_items() ) ) {
					$items = VI_WOO_BOPO_BUNDLE_Helper::bopohp_minify_items( $items );

					// get bundle info
					$fixed_price    = $product->is_fixed_price();
					$discount_array = $product->get_discount_amount();

					// add the bundle

					$order_id = $order->add_product( $product, $quantity );

					for ( $i = 0; $i < count( $items ); $i ++ ) {
						$_product = wc_get_product( $items[ $i ]['id'] );

						if ( ! $_product || in_array( $_product->get_type(), $this->settings->get_params( 'bopobb_type_exclude' ), true ) ) {
							continue;
						}

						if ( $fixed_price ) {
							$_product->set_price( 0 );
						} elseif ( is_array( $discount_array ) ) {
							$calc_price = self::bopobb_calc_price( $items[ $i ]['id'], $i, $items[ $i ]['qty'], $discount_array );
							$_price     = $calc_price['sale'];

							$_product->set_price( $_price );
						}

						// add bundled products
						$_order_item_id = $order->add_product( $_product, $items[ $i ]['qty'] * $quantity );

						if ( ! $_order_item_id ) {
							continue;
						}

						$_order_items = $order->get_items( 'line_item' );
						$_order_item  = $_order_items[ $_order_item_id ];
						$_order_item->add_meta_data( '_bopobb_parent_id', $product_id, true );
						$_order_item->save();
					}

					// remove the old bundle
					if ( $order_id ) {
						$order->remove_item( $order_item_id );
					}
				}

				$order->save();
			}
		}

		function bopobb_hidden_order_item_meta( $hidden ) {
			return array_merge( $hidden, array(
				'_bopobb_parent_id',
				'_bopobb_ids',
				'_bopobb_price',
				'bopobb_parent_id',
				'bopobb_ids',
				'bopobb_price'
			) );
		}

		function bopobb_before_order_item_meta( $order_item_id ) {
			if ( $parent_id = wc_get_order_item_meta( $order_item_id, '_bopobb_parent_id', true ) ) {
				echo sprintf( esc_html__( '(bundled in %s)', 'woo-bopo-bundle' ), get_the_title( $parent_id ) );
			}
		}

		function bopobb_cart_shipping_packages( $packages ) {
			if ( ! empty( $packages ) ) {
				foreach ( $packages as $package_key => $package ) {
					if ( ! empty( $package['contents'] ) ) {
						// Shipping single
						foreach ( $package['contents'] as $cart_item_key => $cart_item ) {
							if ( ! empty( $cart_item['bopobb_parent_id'] ) ) {
								$parent_product = wc_get_product( $cart_item['bopobb_parent_id'] );
								if ( $parent_product->get_meta('bopobb_shipping_fee', true ) !== 'each' ) {
									unset( $packages[ $package_key ]['contents'][ $cart_item_key ] );
								}
							}

							if ( ! empty( $cart_item['bopobb_ids'] ) ) {
								$base_product = wc_get_product( $cart_item['data']->get_id() );
								if ( $base_product->get_meta('bopobb_shipping_fee', true ) === 'each' ) {
								    unset( $packages[ $package_key ]['contents'][ $cart_item_key ] );
							    }
							}
						}
					}
				}
			}

			return $packages;
		}

		function bopobb_coupon_is_valid_for_product( $valid, $product, $coupon, $cart_item ) {
			if ( ( $this->settings->get_params( 'bopobb_coupon_res' ) === 'both' ) && ( isset( $cart_item['bopobb_parent_id'] ) || isset( $cart_item['bopobb_ids'] ) ) ) {
				// exclude both bundles and bundled products
				return false;
			}

			if ( ( $this->settings->get_params( 'bopobb_coupon_res' ) === 'bundles' ) && isset( $cart_item['bopobb_ids'] ) ) {
				// exclude bundles
				return false;
			}

			if ( ( $this->settings->get_params( 'bopobb_coupon_res' ) === 'bundled' ) && isset( $cart_item['bopobb_parent_id'] ) ) {
				// exclude bundled products
				return false;
			}

			return $valid;
		}

		function wp_enqueue_scripts_elementor() {
		    $suffix = WP_DEBUG ? '' : 'min.';
		    wp_enqueue_script( 'woo-bopo-bundle-shortcode-js', VI_WOO_BOPO_BUNDLE_JS . 'shortcode-scripts.' . $suffix . 'js', array( 'jquery' ) );

		    global $_wp_additional_image_sizes;
            $image_size = isset( $_wp_additional_image_sizes[$this->settings->get_params('bopobb_image_size')] ) ? $_wp_additional_image_sizes[$this->settings->get_params('bopobb_image_size')] : array('width'=>80, 'height'=>80);
			$original_size = [intval( $image_size['width'] ),intval( $image_size['height'] )];
			$image_rate = 1;
			if ( intval( $original_size[0] ) && intval( $original_size[1] ) ) {
			    $image_rate = floatval( $original_size[1] ) / intval( $original_size[0] );
			    $normal_size = intval( $image_rate * 80 );
			} else {
			    $normal_size = 80;
			}
		    wp_localize_script( 'woo-bopo-bundle-shortcode-js', 'bopobbShortcodeVars', array(
					'image_rate'               => esc_attr( $image_rate ),
					'image_height'             => esc_attr( $normal_size ),
					'alert_empty'              => esc_html__( 'Please select product for all item of bundle.', 'woo-bopo-bundle' ),
				)
			);
		}

		function bopobb_wp_enqueue_scripts() {
		    if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
                $default_lang     = apply_filters( 'wpml_default_language', null );
                $current_language = apply_filters( 'wpml_current_language', null );

                if ( $current_language && $current_language !== $default_lang ) {
                    $this->language = $current_language;
                }
            } else if ( class_exists( 'Polylang' ) ) {
                $default_lang     = pll_default_language( 'slug' );
                $current_language = pll_current_language( 'slug' );
                if ( $current_language && $current_language !== $default_lang ) {
                    $this->language = $current_language;
                }
            }
		    if ( empty( $this->language ) ) {
		        $title_popup = $this->settings->get_params( 'bopobb_popup_title' );
		    } else {
		        $title_popup = $this->settings->get_params( 'bopobb_popup_title_' . $this->language );
		    }

			if ( WP_DEBUG ) {
			    wp_enqueue_style( 'woo-bopo-bundle-frontend', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-frontend.css' );
			    wp_enqueue_style( 'woo-bopo-bundle-icons', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-icon.css' );
	            wp_enqueue_script( 'woo-bopo-bundle-frontend', VI_WOO_BOPO_BUNDLE_JS . 'bopo-frontend.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_JS );
            } else {
			    wp_enqueue_style( 'woo-bopo-bundle-frontend', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-frontend.min.css' );
			    wp_enqueue_style( 'woo-bopo-bundle-icons', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-icon.min.css' );
	            wp_enqueue_script( 'woo-bopo-bundle-frontend', VI_WOO_BOPO_BUNDLE_JS . 'bopo-frontend.min.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_JS );
            }
			$image_size = wc_get_image_size('thumbnail');
			$original_size = [intval( $image_size['width'] ),intval( $image_size['height'] )];
			$normal_size = [intval( $original_size[0] / 1.6 ), intval( $original_size[1] / 1.6 )];
			$mob_size = [intval( $original_size[0] / 1.8 ),intval( $original_size[1] / 1.8 )];

			wp_localize_script( 'woo-bopo-bundle-frontend', 'bopobbVars', array(
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'user_id'                  => md5( 'bopo' . get_current_user_id() ),
					'nonce'                    => wp_create_nonce( 'bopo-nonce' ),
					'bopobb_popup_title'       => esc_attr( $title_popup ),
					'bopobb_template_change'   => esc_attr__( 'Add product to bundle', 'woo-bopo-bundle' ),
					'bopobb_link_individual'   => esc_attr( $this->settings->get_params( 'bopobb_link_individual' ) ),
					'bopobb_view_quantity'     => esc_attr( $this->settings->get_params( 'bopobb_view_quantity' ) ),
					'bundled_price'            => esc_attr( $this->settings->get_params( 'bopobb_price_by' ) ),
					'image_width'              => esc_attr( $image_size['width'] ),
					'image_height'             => esc_attr( $image_size['height'] ),
					'price_format'             => get_woocommerce_price_format(),
					'price_decimals'           => wc_get_price_decimals(),
					'price_thousand_separator' => wc_get_price_thousand_separator(),
					'price_decimal_separator'  => wc_get_price_decimal_separator(),
					'currency_symbol'          => get_woocommerce_currency_symbol(),
					'alert_empty'              => esc_html__( 'Please select product for all item of bundle.', 'woo-bopo-bundle' ),
					'alert_stock'              => esc_html__( 'Please select product available.', 'woo-bopo-bundle' ),
					'alert_no_item'            => esc_html__( 'No product available to change.', 'woo-bopo-bundle' ),
					'saved_text'               => '(' . esc_html__( 'saved', 'woo-bopo-bundle' ) . ' [d])',
				)
			);

			$css = '';
			$css .= '.bopobb-area .bopobb-popup .bopobb-product .bopobb-product-title,
                    .bopobb-area .bopobb-popup .bopobb-product .bopobb-variation-title,
			        .bopobb-area .bopobb-popup .bopobb-product .bopobb-product-price,
			        .bopobb-area .bopobb-popup .bopobb-product .bopobb-product-price span.woocommerce-Price-amount,
			        .bopobb-area .bopobb-popup .bopobb-product .bopobb-product-price span.woocommerce-Price-amount span.woocommerce-Price-currencySymbol {';
			$css .= 'color:' . esc_attr( $this->settings->get_params( 'bopobb_popup_color' ) ) . ';';
			$css .= '}';
			$css .= '.bopobb-area .bopobb-popup .bopobb-product-list,
			        .bopobb-area .bopobb-popup .bopobb-variation-list {';
			$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'bopobb_popup_background' ) ) . ';';
			$css .= '}';
			$css .= '.bopobb-single-wrap .bopobb-items-top-wrap .bopobb-item-change-wrap .bopobb-item-change {';
			$css .= 'color:' . esc_attr( $this->settings->get_params( 'bopobb_swap_color' ) ) . ';';
			$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'bopobb_swap_background' ) ) . ';';
			$css .= '}';
			$css .= '.bopobb-single-wrap .bopobb-items-bottom-wrap .bopobb-item-product .bopobb-item-change-wrap .bopobb-item-change {';
			$css .= 'color:' . esc_attr( $this->settings->get_params( 'bopobb_swap_color' ) ) . ';';
			$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'bopobb_swap_background' ) ) . ';';
			$css .= '}';
//			$css .= '.bopobb-items-top-wrap .bopobb-item-top .bopobb-item-img-wrap {';
//			$css .= 'width:' . esc_attr( $normal_size[0] ) . 'px;';
//			$css .= 'height:' . esc_attr( $normal_size[1] ) . 'px;';
//			$css .= '}';
//			$css .= '.bopobb-items-top-wrap .bopobb-item-img-separate-wrap .bopobb-item-img-separate-top {';
//			$css .= 'height:' . esc_attr( $normal_size[1] ) . 'px;';
//			$css .= '}';
//			$css .= '@media screen and (max-width: 600px) {';
//			$css .= '.bopobb-items-top-wrap .bopobb-item-top .bopobb-item-img-wrap {';
//			$css .= 'width:' . esc_attr( $mob_size[0] ) . 'px;';
//			$css .= 'height:' . esc_attr( $mob_size[1] ) . 'px;';
//			$css .= '}';
//			$css .= '.bopobb-items-top-wrap .bopobb-item-img-separate-wrap .bopobb-item-img-separate-top {';
//			$css .= 'height:' . esc_attr( $mob_size[1] ) . 'px;';
//			$css .= '}';
			$css .= '}';
			$css .= esc_attr( $this->settings->get_params( 'bopobb_custom_css' ) );

			wp_add_inline_style( 'woo-bopo-bundle-frontend', esc_attr( $css ) );
		}
	}
}