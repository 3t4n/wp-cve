<?php
/**
 * @package Splitit_WooCommerce_Plugin
 *
 * Trait SplitIt_FlexFields_Payment_Plugin_UpstreamMessagingTrait
 */
trait SplitIt_FlexFields_Payment_Plugin_UpstreamMessagingTrait {

	/**
	 * Method for checking pages for Upstream Messaging
	 */
	public function is_enabled_on_page() {
		$home     = $this->is_upstream_messaging_selection( 'home_page_banner' ) ? is_home() : false;
		$shop     = $this->is_upstream_messaging_selection( 'shop' ) ? is_shop() || is_product_category() || is_tax( 'product_brand' ) : false;
		$product  = $this->is_upstream_messaging_selection( 'product' ) ? is_product() : false;
		$cart     = $this->is_upstream_messaging_selection( 'cart' ) ? is_cart() : false;
		$checkout = $this->is_upstream_messaging_selection( 'checkout' ) ? is_checkout() : false;
		$footer   = $this->is_upstream_messaging_selection( 'footer' );

		return $home || $shop || $product || $cart || $checkout || $footer;
	}


	/**
	 * Method for initiate Upstream Messaging
	 */
	public function upstream_messaging_script() {
		if ( $this->is_enabled() && $this->is_enabled_on_page() ) {
			?>
			<script
					async=""
					defer=""
					type="text/javascript"
					src="https://web-components.splitit.com/upstream.js"
					env='<?php echo $this->splitit_environment; ?>'
					api-key='<?php echo esc_attr( get_option( 'api_key' ) ? get_option( 'api_key' ) : $this->settings['splitit_api_key'] ); ?>'
					lang='<?php echo esc_attr( str_replace( '_', '-', get_locale() ) ); ?>'
					currency='<?php echo esc_attr( get_woocommerce_currency() ); ?>'
					default-installments="4"
			></script>
			<?php
		}
	}

	/**
	 * Method for insert custom styles for Upstream Messaging from settings page
	 */
	public function upstream_messaging_custom_styles() {
		if ( $this->is_enabled() && $this->is_enabled_on_page() ) {
			echo '<style>' . htmlspecialchars( wp_strip_all_tags( $this->settings['splitit_upstream_messaging_css'] ) ) . '</style>';
		}
	}

	/**
	 * Method for initiate function for footer credit card
	 */
	public function init_footer_credit_cards() {
		if ( $this->is_enabled() ) {
			if ( $this->is_upstream_messaging_selection( 'footer' ) ) {
				add_action( 'wp_footer', array( $this, 'footer_credit_cards' ) );
			}
		}
	}

	/**
	 * Method for output credit carts in footer
	 */
	public function footer_credit_cards() {
		if ( ! empty( $this->settings['splitit_footer_allowed_card_brands'] ) ) {
			$credit_cards = implode( ',', $this->settings['splitit_footer_allowed_card_brands'] );

			if ( ! empty( $credit_cards ) ) {

				$position = 'left';
				if ( isset( $this->splitit_upstream_messaging_position_footer ) ) {
					$position = $this->splitit_upstream_messaging_position_footer;
				}

				?>
				<div class="splitit_footer_card_brands splitit_position_<?php echo esc_attr( $position ); ?>">
					<fieldset data-splitit-placeholder='cards' data-splitit-style-banner-border="none"
						data-splitit-cards="<?php echo esc_attr( $credit_cards ); ?>"
						class="splitit_footer_cards_banner"></fieldset>
				</div>
				<?php
			}
		}
	}

	/**
	 * Method for initiate function for home page banner
	 */
	public function init_home_page_banner() {
		if ( $this->is_enabled() ) {
			add_action( 'wp_head', array( $this, 'home_page_banner' ) );
		}
	}

	/**
	 * Method for output home page banner
	 */
	public function home_page_banner() {
		if ( $this->is_upstream_messaging_selection( 'home_page_banner' ) && ( is_home() || is_front_page() ) ) {
			if ( is_array( $this->splitit_upstream_messaging_position_home_page ) ) {
				$custom_selector = false;

				$page_config = $this->splitit_upstream_messaging_position_home_page;
				if ( $page_config['banner'] && $page_config['banner']['enable_banner'] && $page_config['banner']['regular'] && $page_config['banner']['regular'] !== '' ) {
					$this->display_um_with_custom_selector( 'spt-banner', $page_config['banner']['regular'], wp_json_encode( $this->generate_um_block( 'home_page', $this->splitit_upstream_messaging_position_home_page ) ) );
					$custom_selector = true;
				}

				if ( ! $custom_selector ) {
					echo $this->generate_um_block( 'home_page', $this->splitit_upstream_messaging_position_home_page );
				}
			}
		}
	}

	/**
	 * Method for initiate function for banner on shop page
	 */
	public function init_shop_page() {
		if ( $this->is_enabled() ) {
			add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_page' ) );
			add_filter( 'woocommerce_get_price_html', array( $this, 'add_splitit_banner_price_to_product_price_on_shop_page' ), 1000, 3 );
			add_filter( 'woocommerce_variable_price_html', array( $this, 'add_splitit_banner_price_to_product_price_on_shop_page_for_variable' ), 1000, 2 );
		}
	}

	/**
	 * Add Splitit banner price to product price on shop page
	 *
	 * @param string     $price Price.
	 * @param WC_Product $product Product.
	 * @return mixed|string
	 */
	public function add_splitit_banner_price_to_product_price_on_shop_page( $price, $product ) {
		global $product;

		if ( ( is_shop() || is_product_category() || is_tax( 'product_brand' ) ) && is_object( $product ) && ! $product->is_type( 'variable' ) ) {
			if ( 1 == $this->splitit_upstream_messaging_position_shop_page['one_liner']['enable_one_liner'] || 1 == $this->splitit_upstream_messaging_position_shop_page['logo']['enable_logo'] ) {
				$price .= $this->um_on_shop_page();
			}
		}

		return $price;
	}

	/**
	 * Add Splitit banner price to product price on shop page
	 *
	 * @param string     $price Price.
	 * @param WC_Product $product Product.
	 * @return mixed|string
	 */
	public function add_splitit_banner_price_to_product_price_on_shop_page_for_variable( $price, $product ) {
		global $product;

		if ( ( is_shop() || is_product_category() || is_tax( 'product_brand' ) ) && is_object( $product ) && $product->is_type( 'variable' ) ) {
			if ( 1 == $this->splitit_upstream_messaging_position_shop_page['one_liner']['enable_one_liner'] || 1 == $this->splitit_upstream_messaging_position_shop_page['logo']['enable_logo'] ) {
				$price .= $this->um_on_shop_page();
			}
		}

		return $price;
	}

	/**
	 * Method for output price break on the shop page
	 */
	public function um_on_shop_page() {
		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'shop' ) && ( is_shop() || is_product_category() || is_tax( 'product_brand' ) ) ) {
			$product    = wc_get_product();
            $product_id = $product->get_id();
			$price      = wc_get_price_to_display( $product, array( 'array' => $product->get_price() ) );
			if ( 'variable' === $product->get_type() ) {
				$prices = $product->get_variation_prices( true );
				if ( ! empty( $prices['price'] ) ) {
					$price = (float) current( $prices['price'] );
				}
			}

			$price        = custom_wc_price_value( $price );
			$installments = $this->get_installment_by_price( $price );

			$total_in_range        = $this->check_if_sum_in_range( $price );
			$is_allowed_um         = $this->is_allowed_um_per_products_for_product_page( $product->get_id() );
			$hide_upstream_message = empty( $installments ) || ! $total_in_range || ! $is_allowed_um ? ' style="display:none"' : '';

			$um_block = is_array( $this->splitit_upstream_messaging_position_shop_page ) ? $this->generate_um_block( 'shop_page', $this->splitit_upstream_messaging_position_shop_page, $price, $installments, $hide_upstream_message, '_' . $product_id . '' ) : '';

			return $um_block;
		}

		return '';
	}

	/**
	 * Method for output banner on shop page
	 */
	public function shop_page() {
		if ( $this->is_upstream_messaging_selection( 'shop' ) && is_shop() ) {
			if ( is_array( $this->splitit_upstream_messaging_position_shop_page ) ) {
				if ( 1 != $this->splitit_upstream_messaging_position_shop_page['one_liner']['enable_one_liner'] && 1 != $this->splitit_upstream_messaging_position_shop_page['logo']['enable_logo'] ) {

					$custom_selector = false;

					$page_config = $this->splitit_upstream_messaging_position_shop_page;
					if ( $page_config['banner'] && $page_config['banner']['enable_banner'] && $page_config['banner']['regular'] && $page_config['banner']['regular'] !== '' ) {
						$this->display_um_with_custom_selector( 'spt-banner', $page_config['banner']['regular'], wp_json_encode( $this->generate_um_block( 'home_page', $this->splitit_upstream_messaging_position_shop_page ) ) );
						$custom_selector = true;
					}

					if ( ! $custom_selector ) {
						echo $this->generate_um_block( 'shop_page', $this->splitit_upstream_messaging_position_shop_page );
					}
				}
			}
		}
	}

	/**
	 * Method for initiate function for price break on the product page
	 */
	public function init_product_page() {
		if ( $this->is_enabled() ) {
			add_filter( 'woocommerce_get_price_html', array( $this, 'add_splitit_banner_price_to_product_price' ), 1000, 3 );
			add_filter( 'woocommerce_variable_price_html', array( $this, 'add_splitit_banner_price_to_product_price_for_variable' ), 1000, 2 );
		}
	}

	/**
	 * Method for checking is in range total price on the product page
	 */
	public function check_if_total_in_range() {
		if ( empty( $this->splitit_inst_conf ) ) {
			return true;
		}

		$contents_total = WC()->cart->get_cart_contents_total();

		$range_from = $this->splitit_inst_conf['ic_from'];
		$range_to   = $this->splitit_inst_conf['ic_to'];

		$in_range = 0;

		if ( $contents_total ) {
			foreach ( $range_from as $index => $value ) {
				if ( (float) $value <= (float) $contents_total && (float) $contents_total <= (float) $range_to[ $index ] ) {
					$in_range++;
				}
			}
		}

		return ( 0 !== $in_range );
	}

	/**
	 * Method for checking is sum in range
	 *
	 * @param float $price Price.
	 * @return bool
	 */
	public function check_if_sum_in_range( $price ) {
		if ( empty( $this->splitit_inst_conf ) ) {
			return true;
		}

		$range_from = $this->splitit_inst_conf['ic_from'];
		$range_to   = $this->splitit_inst_conf['ic_to'];

		$in_range = 0;

		if ( $price ) {
			foreach ( $range_from as $index => $value ) {
				if ( (float) $value <= (float) $price && (float) $price <= (float) $range_to[ $index ] ) {
					$in_range++;
				}
			}
		}

		return ( 0 !== $in_range );
	}

	/**
	 * Method for generate UM block
	 */
	public function generate_um_block( $page, $page_config, $price = '', $installments = '', $hide_upstream_message = '', $product_id = '' ) {
		$block = '<div id="on_site_message_block_' . $page . '"></div>';

		if ( $page_config['strip'] && $page_config['strip']['enable_strip'] ) {
			$tag_name = 'spt-strip';

			if ( 'custom' !== $page_config['strip']['um_text_type'] ) {
				$page_config['strip']['strip_text'] = '';
			}

			$strip_um = self::make_block( $tag_name, $page_config['strip'], $hide_upstream_message );

			if ( 'checkout_page' === $page ) {

				$block  = '<div id="on_site_message_block_' . $page . '" style="position: relative; width: 100%; height: auto; min-width: 50px; min-height: 50px; margin-bottom: 15px; margin-top: 75px">';
				$block .= $strip_um;
				$block .= '</div>';

			} elseif ( 'product_page' === $page ) {

				$block  = '<div id="on_site_message_block_' . $page . '">';
				$block .= $strip_um;
				$block .= '</div>';

			} elseif ( 'home_page' === $page || 'shop_page' === $page ) {

				$block = '<div id="on_site_message_block_' . $page . '">' . $strip_um . '</div>';

			} else {

				$block  = '<div id="on_site_message_block_' . $page . '" style="position: relative; width: 100%; height: auto; min-width: 50px; min-height: 50px; margin-bottom: 15px">';
				$block .= $strip_um;
				$block .= '</div>';

			}
		} elseif ( $page_config['banner'] && $page_config['banner']['enable_banner'] ) {
			$tag_name = 'spt-banner';

			if ( $page_config['banner']['um_text_type'] !== 'custom' ) {
				$page_config['banner']['text_main'] = '';
			}

			$banner_um = self::make_block( $tag_name, $page_config['banner'], $hide_upstream_message );

			if ( $page === 'product_page' || $page === 'cart_page' || $page === 'checkout_page' ) {

				$block  = '<div id="on_site_message_block_' . $page . '" style="position: relative; width: 100%; height: auto; min-width: 50px; min-height: 50px; margin-bottom: 15px">';
				$block .= $banner_um;
				$block .= '</div>';

			} else {

				$block = '<div id="on_site_message_block_' . $page . '">' . $banner_um . '</div>';

			}
		} elseif ( $page_config['logo'] && $page_config['logo']['enable_logo'] ) {
			$tag_name = 'spt-floating-logo';

			if ( $page_config['logo']['um_text_type'] !== 'custom' ) {
				$page_config['logo']['logo_text'] = '';
			}

			$page_config['logo']['amount']       = $price;
			$page_config['logo']['installments'] = $installments;

			$logo_um = self::make_block( $tag_name, $page_config['logo'], $hide_upstream_message );

			if ( $page === 'product_page' || $page === 'cart_page' || $page === 'checkout_page' ) {

				$block  = '<div id="on_site_message_block_' . $page . '' . $product_id . '" style="position: relative; width: 100%; height: auto; min-width: 50px; min-height: 50px; margin-bottom: 15px">';
				$block .= $logo_um;
				$block .= '</div>';

			} else {

				$block = '<div id="on_site_message_block_' . $page . '' . $product_id . '">' . $logo_um . '</div>';

			}
		} elseif ( $page_config['one_liner'] && $page_config['one_liner']['enable_one_liner'] ) {
			$tag_name = 'spt-one-liner';

			if ( $page_config['one_liner']['text_option'] !== 'custom' ) {
				$page_config['one_liner']['text_custom'] = '';
			}

			$page_config['one_liner']['amount']       = $price;
			$page_config['one_liner']['installments'] = $installments;

			$one_liner_um = self::make_block( $tag_name, $page_config['one_liner'], $hide_upstream_message );

			if ( $page === 'product_page' || $page === 'cart_page' || $page === 'checkout_page' ) {

				$block  = '<div id="on_site_message_block_' . $page . '' . $product_id . '" style="position: relative; width: 100%; height: auto; min-width: 50px; min-height: 50px">';
				$block .= $one_liner_um;
				$block .= '</div>';

			} else {

				$block = '<div id="on_site_message_block_' . $page . '' . $product_id . '">' . $one_liner_um . '</div>';

			}
		}

		return $block;
	}

	/**
	 * Method for creation UM block
	 */
	private static function make_block( $tag_name, $page_config, $hide_upstream_message = '' ) {
		$block = '<' . $tag_name . ' ' . $hide_upstream_message . ' ';
		foreach ( $page_config as $key => $config ) {
            if ( 'enable_one_liner' == $key ) continue;
            if ( 'regular' == $key ) continue;
            if ( '' == $config ) continue;

			if ( ( 'text_size' == $key || 'text_main_size' == $key || 'banner_width' == $key || 'banner_height' == $key ) && is_numeric( $config ) ) {
				$config = $config . 'px';
			} elseif ( 'hide_learn_more' == $key && "1" == $config ) {
				$config = true;
			} elseif ( 'hide_icon' == $key && "1" == $config ) {
				$config = true;
			}

			$block .= ' ' . $key . '=' . wp_json_encode( $config );
		}

		if ( 'spt-strip' == $tag_name && 'top' == $page_config ['position'] ) {
			$block .= 'is_solid=true';
		}

		$block .= '></' . $tag_name . '>';

		return $block;
	}

	/**
	 * Method for output price break on the product page
	 */
	public function product_page( $is_current_product = false, $is_custom = false ) {
		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'product' ) && is_product() ) {
			$product = wc_get_product();
			$price   = wc_get_price_to_display( $product, array( 'array' => $product->get_price() ) );
			if ( 'variable' === $product->get_type() ) {
				$prices = $product->get_variation_prices( true );
				if ( ! empty( $prices['price'] ) ) {
					$price = (float) current( $prices['price'] );
				}
			}

			$price        = custom_wc_price_value( $price );
			$installments = $this->get_installment_by_price( $price );

			$total_in_range        = $this->check_if_sum_in_range( $price );
			$is_allowed_um         = $this->is_allowed_um_per_products_for_product_page( $product->get_id() );
			$hide_upstream_message = empty( $installments ) || ! $total_in_range || ! $is_allowed_um ? ' style="display:none"' : '';

			$um_block = is_array( $this->splitit_upstream_messaging_position_product_page ) ? $this->generate_um_block( 'product_page', $this->splitit_upstream_messaging_position_product_page, $price, $installments, $hide_upstream_message ) : '';

			if ( $is_custom ) {
				return $um_block .
				"<script type='text/javascript'>
                    function updateSplititUpstreamMessage(price = null, variation_id = null) {
                        jQuery.ajax({
                            type: 'POST',
                            url: getSplititAjaxURL('calculate_new_installment_price_product_page'),
                            dataType: 'json',
                            data: {
                                'price': price,
                                'product_id': " . wc_get_product()->get_id() . ",
                                'variation_id': variation_id,
                                'action': 'calculate_new_installment_price_product_page',
                            },
                            success: function (response) {
                                jQuery('spt-floating-logo, spt-one-liner')
                                    .attr('amount', response.price)
                                    .attr('installments', response.installments);
                                if (response.installments && response.show_um_product && response.is_allowed_um) {
                                    jQuery('spt-floating-logo, spt-one-liner').show();
                                    jQuery('spt-floating-logo, spt-one-liner').attr('style', '');
                                } else {
                                    jQuery('spt-floating-logo, spt-one-liner').hide();
                                }
                            },
                        });
                    }
    
                    jQuery(document).on('found_variation', function (event, value) {
                        updateSplititUpstreamMessage(value.display_price, value.variation_id);
                    });
    
                    jQuery(document).on('woocommerce_variation_has_changed', function (event) {
                        if ( event.target[0] && event.target[0].value === '' ) {
                            updateSplititUpstreamMessage('choose an option');
                        }
                    });
    
                    //woocommerce-tm-extra-product-options compatibility
                    jQuery(window).on('tc-epo-after-update', function(event, value){
                        updateSplititUpstreamMessage(value.data.product_total_price);
                    });
                </script>";
			} else {
				return '<br/>' . $um_block . "
            
                <script type='text/javascript'>
                    <!--
                    function updateSplititUpstreamMessage(price = null, variation_id = null) {
                        jQuery.ajax({
                            type: 'POST',
                            url: getSplititAjaxURL('calculate_new_installment_price_product_page'),
                            dataType: 'json',
                            data: {
                                'price': price,
                                'product_id': " . wc_get_product()->get_id() . ",
                                'variation_id': variation_id,
                                'action': 'calculate_new_installment_price_product_page',
                            },
                            success: function (response) {
                                jQuery('spt-floating-logo, spt-one-liner')
                                    .attr('amount', response.price)
                                    .attr('installments', response.installments);
                                if (response.installments && response.show_um_product && response.is_allowed_um) {
                                    jQuery('spt-floating-logo, spt-one-liner').show();
                                    jQuery('spt-floating-logo, spt-one-liner').attr('style', '');
                                } else {
                                    jQuery('spt-floating-logo, spt-one-liner').hide();
                                }
                            },
                        });
                    }
    
                    jQuery(document).on('found_variation', function (event, value) {
                        updateSplititUpstreamMessage(value.display_price, value.variation_id);
                    });
    
                    jQuery(document).on('woocommerce_variation_has_changed', function (event) {
                        if ( event.target[0] && event.target[0].value === '' ) {
                            updateSplititUpstreamMessage('choose an option');
                        }
                    });
    
                    //woocommerce-tm-extra-product-options compatibility
                    jQuery(window).on('tc-epo-after-update', function(event, value){
                        updateSplititUpstreamMessage(value.data.product_total_price);
                    });
                    // -->
                </script>";
			}
		}

		return '';
	}

	/**
	 * Method for initiate function for price break on the cart page
	 */
	public function init_cart_page() {
		if ( $this->is_enabled() ) {
			add_filter( 'woocommerce_cart_subtotal', array( $this, 'add_splitit_banner_price_to_cart_price' ), 1000, 3 );
		}
	}

	/**
	 * Add splitit banner price to product price for variable
	 *
	 * @param string     $price Price.
	 * @param WC_Product $product Product object.
	 *
	 * @return mixed|string
	 */
	public function add_splitit_banner_price_to_product_price_for_variable( $price, $product ) {
		global $woocommerce_loop;
		global $product;

		if ( is_product() && is_object( $product ) && $product->is_type( 'variable' ) ) {
			try {
				$is_current_product = ( $product->get_slug() === sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() )->post_name );
			} catch ( \Exception $e ) {
				$is_current_product = ! $woocommerce_loop['name'];
			}

			if ( $is_current_product ) {

                $custom_selector = $this->generate_um_with_custom_selector( $this->splitit_upstream_messaging_position_product_page, wp_json_encode( $this->product_page( true ) ) );

                if ( ! $custom_selector ) {
                    $price .= $this->product_page( true );
                }
            }
		}

		return $price;
	}

	/**
	 * Add Splitit banner price to product price
	 *
	 * @param string     $price Price.
	 * @param WC_Product $product Product.
	 * @return mixed|string
	 */
	public function add_splitit_banner_price_to_product_price( $price, $product ) {
		global $woocommerce_loop;
		global $product;

		if ( is_product() && is_object( $product ) && ! $product->is_type( 'variable' ) ) {
			try {
				$is_current_product = ( $product->get_slug() === sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() )->post_name );
			} catch ( \Exception $e ) {
				$is_current_product = ! $woocommerce_loop['name'];
			}

			if ( $is_current_product ) {

				$custom_selector = $this->generate_um_with_custom_selector( $this->splitit_upstream_messaging_position_product_page, wp_json_encode( $this->product_page( true ) ) );

				if ( ! $custom_selector ) {
					$price .= $this->product_page( true );
				}
			}
		}

		return $price;
	}

	/**
	 * The method checks if the custom selector is set in the settings and generates UM
	 * @param $page_config
	 * @param $um
	 *
	 * @return bool
	 */
	public function generate_um_with_custom_selector( $page_config, $um ) {
		$custom_selector = false;

		if ( is_array( $page_config ) ) {
			if ( $page_config['banner'] && $page_config['banner']['enable_banner'] && $page_config['banner']['regular'] && $page_config['banner']['regular'] !== '' ) {

				$this->display_um_with_custom_selector( 'spt-banner', $page_config['banner']['regular'], $um );
				$custom_selector = true;

			} elseif ( $page_config['logo'] && $page_config['logo']['enable_logo'] && $page_config['logo']['regular'] && $page_config['logo']['regular'] !== '' ) {

				$this->display_um_with_custom_selector( 'spt-floating-logo', $page_config['logo']['regular'], $um );
				$custom_selector = true;

			} elseif ( $page_config['one_liner'] && $page_config['one_liner']['enable_one_liner'] && $page_config['one_liner']['regular'] && $page_config['one_liner']['regular'] !== '' ) {

				$this->display_um_with_custom_selector( 'spt-one-liner', $page_config['one_liner']['regular'], $um );
				$custom_selector = true;

			}
        }

        return $custom_selector;
    }

	/**
	 * The method generates UM based on the custom selector
	 * @param $type
	 * @param $selector
	 * @param $um
	 */
	public function display_um_with_custom_selector ( $type, $selector, $um ) {
	    ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {

                var um_element = jQuery('<?php echo $type; ?>')

                if ( ! um_element.length ) {

                    var um_type = '<?php echo $type; ?>'
                    var selector = '<?php echo $selector; ?>'
                    var custom_selector = document.querySelector( '<?php echo $selector; ?>' )

                    if ( custom_selector && custom_selector !== undefined ) {

                        localStorage.setItem( 'um_type', um_type )
                        localStorage.setItem( 'custom_selector', selector )
                        localStorage.setItem( 'um', <?php echo $um; ?> )

                        jQuery(custom_selector).append( <?php echo $um; ?> )
                    }
                }
            })
        </script>
	    <?php
    }

	/**
	 * Add Splitit banner price to cart price
	 *
	 * @param float $price Price.
	 * @return mixed|string
	 */
	public function add_splitit_banner_price_to_cart_price( $price ) {
		if ( is_cart() ) {

			$custom_selector = $this->generate_um_with_custom_selector( $this->splitit_upstream_messaging_position_cart_page, wp_json_encode( $this->cart_page( true ) ) );

			if ( ! $custom_selector ) {
				$price .= '</td>' . $this->cart_page() . '<td style="display: none">';
			}
		}

		return $price;
	}

	/**
	 * Method for output price break on the cart page
	 *
	 * @return string
	 */
	public function cart_page( $custom_selector = false ) {
		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'cart' ) && is_cart() ) {
			$message_block = '';
			$price         = $this->get_cart_total();
			$price         = custom_wc_price_value( $price );
			$installments  = $this->get_installment_by_price( $price );

			$total_in_range               = $this->check_if_total_in_range();
			$current_order_total_in_range = $this->check_if_sum_in_range( $this->get_current_order_total() );
			$is_allowed_um                = $this->is_allowed_um_per_products_for_card_and_checkout_pages();
			$hide_upstream_message        = empty( $installments ) || ! $total_in_range || ! $current_order_total_in_range || ! $is_allowed_um ? ' style="display:none"' : '';

			if ( isset( $installments ) ) {
				$message_block = is_array( $this->splitit_upstream_messaging_position_cart_page ) ? $this->generate_um_block( 'cart_page', $this->splitit_upstream_messaging_position_cart_page, $price, $installments, $hide_upstream_message ) : '';
			}

            if ( $custom_selector ) {
	            add_action('woocommerce_after_cart', array( $this, 'custom_content_after_cart_with_custom_selector' ));
            } else {
	            add_action('woocommerce_after_cart', array( $this, 'custom_content_after_cart' ));
            }

			return $message_block;
		}

		return '';
	}

	/**
	 * Add Splitit banner script to cart page
	 */
	public function custom_content_after_cart() {
		?>
        <script>
            jQuery(document.body).off('updated_cart_totals');
            jQuery(document.body).on('updated_cart_totals', function () {
                jQuery.ajax({
                    type: 'POST',
                    url: getSplititAjaxURL('calculate_new_installment_price_cart_page'),
                    dataType: 'json',
                    data: {
                        'action': 'data',
                    },
                    success: function (response) {

                        jQuery('spt-floating-logo, spt-one-liner')
                            .attr('amount', response.price)
                            .attr('installments', response.installments);

                        if (response.installments && response.current_order_total_in_range && response.is_allowed_um) {

                            jQuery('spt-floating-logo, spt-one-liner').show();
                            jQuery('spt-floating-logo, spt-one-liner').attr('style', '');

                        } else {
                            jQuery('spt-floating-logo, spt-one-liner').hide();
                        }
                    },
                });
            });
        </script>
		<?php
	}

	/**
	 * Add Splitit banner script to cart page with custom selector
	 */
	public function custom_content_after_cart_with_custom_selector() {
		?>
        <script>
            jQuery(document.body).off('updated_cart_totals');
            jQuery(document.body).on('updated_cart_totals', function () {
                jQuery.ajax({
                    type: 'POST',
                    url: getSplititAjaxURL('calculate_new_installment_price_cart_page'),
                    dataType: 'json',
                    data: {
                        'action': 'data',
                    },
                    success: function (response) {
                        var um_type   = localStorage.getItem( 'um_type' )
                        var um_banner = jQuery( um_type )

                        if ( !um_banner.length ) {
                            var custom_selector = localStorage.getItem( 'custom_selector' )
                            var um              = localStorage.getItem( 'um' )

                            var element         = jQuery( custom_selector )

                            if (element.length) {
                                element.append(um)
                            }
                        }

                        jQuery('spt-floating-logo, spt-one-liner')
                            .attr('amount', response.price)
                            .attr('installments', response.installments);

                        if (response.installments && response.current_order_total_in_range && response.is_allowed_um) {

                            jQuery('spt-floating-logo, spt-one-liner').show();
                            jQuery('spt-floating-logo, spt-one-liner').attr('style', '');

                        } else {
                            jQuery('spt-floating-logo, spt-one-liner').hide();
                        }
                    },
                });
            });
        </script>
		<?php
	}

	/**
	 * Method for initiate function for banner on the checkout page
	 */
	public function init_checkout_page_after_cart_totals() {
		if ( $this->is_enabled() ) {
			add_action( 'woocommerce_review_order_before_payment', array( $this, 'checkout_page_after_cart_totals' ) );
		}
	}

	/**
	 * Method for output banner on the checkout page
	 */
	public function checkout_page_after_cart_totals() {
		// upstream_banner_with_calculations
		if ( $this->is_upstream_messaging_selection( 'checkout' ) && is_checkout() ) {

			$total = $this->get_current_order_total();

			$price        = $total;
			$installments = $this->get_installment_by_price( $price );

			$total_in_range               = $this->check_if_total_in_range();
			$current_order_total_in_range = $this->check_if_sum_in_range( $price );
			$is_allowed_um                = $this->is_allowed_um_per_products_for_card_and_checkout_pages();
			$hide_upstream_message        = empty( $installments ) || ! $total_in_range || ! $current_order_total_in_range || ! $is_allowed_um ? 'style=display:none' : '';

			if ( is_array( $this->splitit_upstream_messaging_position_checkout_page ) ) {

				$custom_selector = $this->generate_um_with_custom_selector( $this->splitit_upstream_messaging_position_checkout_page, wp_json_encode( $this->generate_um_block( 'checkout_page', $this->splitit_upstream_messaging_position_checkout_page, $price, $installments, $hide_upstream_message ) ) );

				if ( ! $custom_selector ) {
					echo $this->generate_um_block( 'checkout_page', $this->splitit_upstream_messaging_position_checkout_page, $price, $installments, $hide_upstream_message );
				}
			}

			?>
			<!--upstream_banner_with_calculations-->

			<script type='text/javascript'>
				function updateSplititUpstreamMessageCheckout() {
					jQuery.ajax({
						type: 'POST',
						url: getSplititAjaxURL('calculate_new_installment_price_checkout_page'),
						dataType: 'json',
						success: function (response) {
							jQuery('spt-floating-logo, spt-one-liner')
								.attr('amount', response.price)
								.attr('installments', response.installments);
							if (response.installments && response.show_um && response.is_allowed_um) {
								jQuery('spt-floating-logo, spt-one-liner').show();
								jQuery('spt-floating-logo, spt-one-liner').attr('style', '');
							} else {
								jQuery('spt-floating-logo, spt-one-liner').hide();
							}
						}
					});
				}

				jQuery(document.body).on('updated_checkout', function () {
					updateSplititUpstreamMessageCheckout();
				});
			</script>
			<?php
		}
	}

	/**
	 * Method for update price and installments for price break on the product page
	 */
	public function calculate_new_installment_price_product_page() {
		$price        = 0;
		$installments = 0;
		$product_id   = 0;
		$variation_id = null;

		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'product' ) ) {
			$post_fields = stripslashes_deep( $_POST );
			$price       = wc_clean( $post_fields['price'] ) ?? null;

			$product_id   = wc_clean( $post_fields['product_id'] );
			$variation_id = wc_clean( $post_fields['variation_id'] ) ?? null;

			if ( 'choose an option' == $price ) {
				$product = wc_get_product( $product_id );
				$price   = wc_get_price_to_display( $product, array( 'array' => $product->get_price() ) );
			}

			if ( isset( $price ) && ! empty( $price ) && 'choose an option' !== $price ) {
				$installments = $this->get_installment_by_price( $price );
			} else {
				$price        = 0;
				$installments = 0;
			}
		}

		$show_um_product = $this->check_if_sum_in_range( $price );

		$is_allowed_um = $this->is_allowed_um_per_products_for_product_page( $product_id, $variation_id );

		echo wp_json_encode(
			array(
				'price'           => $price,
				'installments'    => $installments,
				'show_um_product' => $show_um_product,
				'is_allowed_um'   => $is_allowed_um,
			)
		);

		wp_die();
	}

	/**
	 * Method for update price and installments for price break on the checkout page
	 */
	public function calculate_new_installment_price_checkout_page() {
		$price        = 0;
		$installments = 0;

		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'checkout' ) ) {
			$price         = $this->get_current_order_total();
			$installments  = $this->get_installment_by_price( $price );
			$show_um       = $this->check_if_sum_in_range( $price );
			$is_allowed_um = $this->is_allowed_um_per_products_for_card_and_checkout_pages();
		}

		echo wp_json_encode(
			array(
				'price'         => $price,
				'installments'  => $installments,
				'show_um'       => $show_um,
				'is_allowed_um' => $is_allowed_um,
			)
		);

		wp_die();
	}

	/**
	 * Method for update price and installments for price break on the cart page
	 */
	public function calculate_new_installment_price_cart_page() {
		$price        = 0;
		$installments = 0;

		$current_order_total_in_range = $this->check_if_sum_in_range( $this->get_current_order_total() );
		$is_allowed_um                = $this->is_allowed_um_per_products_for_card_and_checkout_pages();

		if ( $this->is_enabled() && $this->is_upstream_messaging_selection( 'cart' ) ) {
			$price        = $this->get_cart_total();
			$installments = $this->get_installment_by_price( $price );
		}

		echo wp_json_encode(
			array(
				'price'                        => $price,
				'installments'                 => $installments,
				'current_order_total_in_range' => $current_order_total_in_range,
				'is_allowed_um'                => $is_allowed_um,
			)
		);

		wp_die();
	}

	/**
	 * Method for getting last installments in range by price
	 *
	 * @param float $price Price amount.
	 * @return mixed|string
	 */
	public function get_installment_by_price( $price ) {
		if ( $this->is_enabled() ) {
			if ( isset( $this->settings['splitit_upstream_default_installments'] ) && ! empty( $this->settings['splitit_upstream_default_installments'] ) ) {
				return (int) $this->settings['splitit_upstream_default_installments'];
			}

			if ( isset( $this->splitit_inst_conf['ic_installment'] ) ) {
				$key = $this->get_installment_ic_to_by_price( $this->splitit_inst_conf['ic_to'], $price, $this->splitit_inst_conf['ic_from'] );
				if ( -1 === $key ) {
					// We have configured range, but product price is not in range.
					return null;
				}
				if ( array_key_exists( $key, $this->splitit_inst_conf['ic_installment'] ) ) {
					$installment = $this->splitit_inst_conf['ic_installment'][ $key ];
					$explode     = explode( ',', $installment );
					return max( $explode );
				}
			}
		}

		return self::DEFAULT_INSTALMENT_PLAN;
	}

	/**
	 * Method for getting array of installments by price
	 *
	 * @param float $price Price amount.
	 * @return array|false|string[]
	 */
	public function get_array_of_installments( $price ) {
		if ( $this->is_enabled() && ! empty( $this->splitit_inst_conf ) ) {
			$key = $this->get_installment_ic_to_by_price( $this->splitit_inst_conf['ic_to'], $price, $this->splitit_inst_conf['ic_from'] );

			if ( isset( $this->splitit_inst_conf['ic_installment'] ) ) {
				if ( array_key_exists( $key, $this->splitit_inst_conf['ic_installment'] ) ) {
					$installment = $this->splitit_inst_conf['ic_installment'][ $key ];
					$installment = explode( ',', $installment );
					return array_unique( $installment );
				}
			}
		}

		return false;
	}

	/**
	 * Method for check if price in installments range
	 *
	 * @return array|false|string[]
	 */
	public function check_if_price_in_range() {
		if ( empty( $this->splitit_inst_conf ) ) {
			return true;
		}

		$price = $this->get_cart_total();
		$key   = $this->get_installment_ic_to_by_price( $this->splitit_inst_conf['ic_to'], $price, $this->splitit_inst_conf['ic_from'] );

		if ( isset( $this->splitit_inst_conf['ic_installment'] ) && array_key_exists( $key, $this->splitit_inst_conf['ic_installment'] ) ) {
			$installment = $this->splitit_inst_conf['ic_installment'][ $key ];
			return ! empty( $installment );
		}

		return false;
	}

	/**
	 * Method for getting installment range key
	 *
	 * @param array $installments Installments array.
	 * @param float $price_product Product price.
	 * @param array $installments_from Installments from array.
	 * @return false|int|string
	 */
	public function get_installment_ic_to_by_price( $installments, $price_product, $installments_from ) {
		if ( $this->is_enabled() && isset( $installments ) && isset( $price_product ) && isset( $installments_from ) ) {
			$orig_installments = $installments;
			sort( $installments );
			sort( $installments_from );

			foreach ( $installments as $key => $price ) {
				if ( $price_product <= $price
					&& ( isset( $installments_from[ $key ] ) && $installments_from[ $key ] <= $price_product )
				) {
					return array_search( $price, $orig_installments );
				}
			}
		}
		return -1;
	}

	/**
	 * Add custom js and css
	 */
	public function init_styles_and_scripts() {
		add_action( 'wp_body_open', array( $this, 'custom_loader_on_the_checkout_page_html' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_css_on_the_checkout_page' ) );
	}

	/**
	 * Register and enqueue custom CSS
	 */
	public function custom_css_on_the_checkout_page() {
		wp_register_style( 'custom_splitit_checkout_page_css', plugins_url( 'assets/css/style.css', dirname( __DIR__ ) ) );
		wp_enqueue_style( 'custom_splitit_checkout_page_css' );
	}

	/**
	 * Add custom loader HTML code
	 */
	public function custom_loader_on_the_checkout_page_html() {
		if ( is_checkout() ) {
			?>
			<div class="splitit_custom_checkout_page_loader">Loading&#8230;</div>
			<?php
		}
	}

	/**
	 * Check is payment method is allowed to display
	 *
	 * @return bool
	 */
	public function is_allowed_payment() {
		return $this->is_enabled()
			&& $this->check_if_price_in_range()
			&& $this->is_allowed_payment_per_products();
	}

	/**
	 * Get cart total
	 *
	 * @return float
	 */
	private function get_cart_total() {
		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );
			$price    = $order ? $order->get_total() : '';
		} else {
			global $woocommerce;
			$price = ( WC()->cart && WC()->cart->total ) ? WC()->cart->total : $woocommerce->cart->total;
		}

		return (float) $price;
	}

	/**
	 * Get cart items
	 *
	 * @return array|WC_Order_Item[]
	 */
	private function get_cart_items() {

		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );
			$items    = $order->get_items();
		} else {
			$items = WC()->cart ? WC()->cart->get_cart() : array();
		}

		return $items;
	}

	/**
	 * Check is payment method enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return 'yes' === $this->enabled;
	}

	/**
	 * Check is upstream messaging enabled for selected position
	 *
	 * @param string $selection Selection name.
	 * @return bool
	 */
	public function is_upstream_messaging_selection( $selection ) {
		return isset( $this->splitit_upstream_messaging_selection )
			&& ! empty( $this->splitit_upstream_messaging_selection )
			&& in_array( $selection, $this->splitit_upstream_messaging_selection );
	}

	/**
	 * Get splitit per product settings
	 *
	 * @return array
	 */
	public function get_splitit_per_product_settings() {
		$option      = isset( $this->settings['splitit_product_option'] ) ? (int) $this->settings['splitit_product_option'] : 0;
		$product_ids = isset( $this->settings['splitit_products_list'] ) ? $this->settings['splitit_products_list'] : array();
		$disabled    = false;

		if ( 0 === $option || empty( $product_ids ) ) {
			$disabled = true;
		}

		return array( $disabled, $option, $product_ids );
	}

	/**
	 * Is allowed UM per products for product page
	 *
	 * @param $product_id
	 * @param null       $variation_id
	 * @return bool
	 */
	public function is_allowed_um_per_products_for_product_page( $product_id, $variation_id = null ) {
		list($disabled, $option, $product_ids) = $this->get_splitit_per_product_settings();

		if ( $disabled ) {
			return true;
		}
		$product = $variation_id ? wc_get_product( $variation_id ) : wc_get_product( $product_id );

		$sku = $product->get_sku();

		$product_skus = $this->get_product_skus_from_ids( $product_ids );

		return ( $sku ? ( in_array( $sku, $product_skus ) || in_array( $product->get_id(), $product_ids ) ) : in_array( $product->get_id(), $product_ids ) );
	}

	/**
	 * Is allowed UM per products for card and checkout pages
	 *
	 * @return bool
	 */
	public function is_allowed_um_per_products_for_card_and_checkout_pages() {
		return $this->is_allowed_payment_per_products();
	}

	/**
	 * Is allowed payment per products
	 *
	 * @return bool
	 */
	public function is_allowed_payment_per_products() {
		list($disabled, $option, $product_ids) = $this->get_splitit_per_product_settings();

		if ( $disabled ) {
			return true;
		}

		$cart_items = $this->get_cart_items();

		if ( 0 === count( $cart_items ) ) {
			return true;
		}

		$product_skus     = $this->get_product_skus_from_ids( $product_ids );
		$matched_products = 0;

		foreach ( $cart_items as $cart_item ) {
			$sku = isset( $cart_item['data'] ) && $cart_item['data'] ? $cart_item['data']->get_sku() : '';

			$product_id = empty( $cart_item['variation_id'] ) ? $cart_item['product_id'] : $cart_item['variation_id'];

			if ( ( ! empty( $sku ) && in_array( $sku, $product_skus ) )
				|| ( empty( $sku ) && in_array( $product_id, $product_ids ) )
			) {
				$matched_products++;
			}
		}

		return ( 1 === $option && count( $cart_items ) == $matched_products )
			|| ( 2 === $option && $matched_products > 0 );
	}

	/**
	 * Get product skus from ids
	 *
	 * @param array $product_ids Array of Product IDs.
	 * @return array
	 */
	private function get_product_skus_from_ids( $product_ids ) {
		$product_skus = array();
		foreach ( $product_ids as $product_id ) {
			$product        = wc_get_product( $product_id );
			$product_skus[] = $product->get_sku();
		}
		return $product_skus;
	}

	/**
	 * Get current order total
	 *
	 * @return float
	 */
	private function get_current_order_total() {
		$_POST    = stripslashes_deep( $_POST );
		$order_id = isset( $_POST['order_id'] ) ? wc_clean( $_POST['order_id'] ) : null;

		$order = empty( $order_id ) ? null : wc_get_order( $order_id );
		WC()->cart->calculate_totals();

		if ( $order ) {
			$total = (float) $order->get_total();
		} else {
			$total = $this->get_order_total();
		}

		return custom_wc_price_value( $total );
	}

	/**
	 * Get the order total in checkout and pay_for_order.
	 *
	 * @return float
	 */
	protected function get_order_total() {
		$total    = 0;
		$order_id = absint( get_query_var( 'order-pay' ) );

		// Gets order total from "pay for order" page.
		if ( 0 < $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$total = (float) $order->get_total();
			}
		} elseif ( 0 < WC()->cart->total ) {
			// Gets order total from cart/checkout.
			$total = (float) WC()->cart->total;
		}

		return $total;
	}
}
