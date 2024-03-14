<?php

namespace Shop_Ready\extension\elegrid\hooks\product;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Shop_Ready\helpers\classes\WooCommerce_Product as Wready_Utils;

/**
 * WooCommerece Archive Flip Grid Style
 * Preset One
 *
 * @since 1.0
 */

class Grid_Flip_Center_Structure {


	public $meta_key = 'wready_swatch_color';
	public $style    = 'side_flip_center';

	public function register() {
		$grid_style = get_option( 'wooready_products_archive_shop_grid_style', 'side_flip_center' );

		if ( $grid_style != $this->style ) {

			return;
		}

		add_action( 'shop_ready_loop_product_thumb_inner', array( $this, 'loop_add_to_cart' ), 5 );
		add_action( 'shop_ready_grid_thumbnail', array( $this, 'thumnnail' ), 10 );
		add_action( 'shop_ready_grid_loop_ontent', array( $this, 'loop_product_below' ), 20 );
	}

	function loop_add_to_cart( $args = array() ) {
		global $product;
		$icon_active = WReady_Helper::get_global_setting( 'wooready_products_archive_shop_grid_cart_icon_enable' );
		$_icon       = WReady_Helper::get_global_setting( 'wooready_products_archive_shop_grid_cart_icon' );
		$cart_text   = WReady_Helper::get_global_setting( 'wooready_products_archive_shop_grid_cart_text' );

		if ( $product ) {
			$defaults = array(

				'quantity'   => 1,
				'class'      => implode(
					' ',
					array_filter(
						array(
							'wr-icon',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ajax_add_to_cart ' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);

			$args = apply_filters( 'wready_layout_1_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

			if ( isset( $args['attributes']['aria-label'] ) ) {
				$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
			}
			echo wp_kses_post( sprintf( '<li class="wready-product-loop-cart-link">' ) );

			echo wp_kses_post(
				sprintf(
					'<a href="%s" data-quantity="%s" class="%s" %s>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( isset( $args['quantity'] ) ? esc_attr( $args['quantity'] ) : esc_attr( 1 ) ),
					esc_attr( isset( $args['class'] ) ? esc_attr( $args['class'] ) : esc_attr( 'wr-icon ajax_add_to_cart' ) ),
					isset( $args['attributes'] ) ? esc_attr( wc_implode_html_attributes( $args['attributes'] ) ) : ''
				)
			);

			if ( $icon_active == 'yes' ) {
				echo wp_kses_post( shop_ready_render_icons( $_icon, 'wready-icons' ) );
			}

			echo esc_html( $cart_text );

			echo wp_kses_post( '</a>' );
			echo wp_kses_post( '</li>' );
		}
	}

	function loop_price() {
		echo sprintf( wp_kses_post( '<div class="wooready_price_box display:flex justify-content:center order:3 sr-grid-price">' ) );
		wc_get_template( 'loop/price.php' );
		echo wp_kses_post( '</div>' );
	}

	public function get_graph_value( $product ) {
		$fill          = '0%';
		$text          = '';
		$progress      = '100';
		$in_stock      = $product->get_stock_quantity();
		$total_sales   = $product->get_total_sales();
		$total_product = (int) ( $in_stock + $total_sales );

		if ( ! Wready_Utils::has_enough_stock( $product ) || ! $product->is_in_stock() ) {
			$fill     = '100%';
			$progress = 100;
		} else {

			$fragment = ( $total_sales / $total_product );
			$fill     = number_format( $fragment * 100, 0 ) . '%';
			$progress = number_format( $fragment * 100, 0 );
		}

		return array(
			'fill'          => $fill,
			'progress'      => $progress,
			'total_product' => $total_product,
			'total_sales'   => $total_sales,
		);
	}

	public function stock_range() {
		global $product;

		if ( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) {
			return;
		}

		if ( ! $product->get_manage_stock() ) {
			return;
		}

		$graph = $this->get_graph_value( $product );

		$stock_seperator = WReady_Helper::get_global_setting( 'woo_ready_product_grid_stock_seperator' );
		$left_count      = 30;
		$total_stock     = 130;

		echo wp_kses_post(
			sprintf(
				'<div class="wooready_product_sold_range margin-top:10 margin-bottom:10 order:5">
        <p class="wooready_product_sold_count">%s %s%s%s</p>
        <div style="--wready-stock: %s" class="wooready_range">
            <span></span>
        </div>
    </div>',
				esc_html__( 'Sold:', 'shopready-elementor-addon' ),
				esc_html( $graph['total_sales'] ),
				esc_html( $stock_seperator ),
				esc_html( $graph['total_product'] ),
				wp_kses_post( $graph['fill'] )
			)
		);
	}

	function variation_color_price() {
		global $product;
		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		echo wp_kses_post( '<div class="wooready_product_color margin-top:15 order:4 display:flex">' );

		$attributes          = $product->get_variation_attributes();
		$selected_attributes = $product->get_default_attributes();

		foreach ( $attributes as $attribute_name => $options ) {

			$attributes_id_arr      = wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_id', 'attribute_name' );
			$remove_suffix          = preg_replace( '/^pa_/', '', $attribute_name );
			$woo_ready_color_id     = isset( $attributes_id_arr[ $remove_suffix ] ) ? $attributes_id_arr[ $remove_suffix ] : null;
			$attribute_wrea         = get_option( 'woo_ready_product_attributes' ) ? get_option( 'woo_ready_product_attributes' ) : array();
			$woo_ready_display_type = sanitize_text_field( isset( $_POST['woo_ready_display_type'] ) ? sanitize_text_field( $_POST['woo_ready_display_type'] ) : ( isset( $attribute_wrea[ $woo_ready_color_id ] ) ? sanitize_text_field( $attribute_wrea[ $woo_ready_color_id ] ) : '' ) );
			$name                   = 'attribute_' . sanitize_title( $attribute_name );

			if ( $woo_ready_display_type == 'variation_color' ) {
				echo wp_kses_post( sprintf( '<a href="%s" class="wready-product-loop-color-wrapper display:flex gap:10 align-items:center %s">', esc_url( get_permalink( $product->get_id() ) ), esc_attr( $product->get_type() ) ) );
				if ( ! empty( $options ) ) {

					if ( $product && taxonomy_exists( $attribute_name ) ) {

						$terms = wc_get_product_terms(
							$product->get_id(),
							$attribute_name,
							array(
								'fields' => 'all',
							)
						);

						foreach ( $terms as $term ) {

							$cls   = $woo_ready_display_type == 'variation_color' ? ' border-radius:100%' : '';
							$color = 'background-color:' . get_term_meta( $term->term_id, $attribute_name . '_' . $this->meta_key . '_color', true );

							if ( in_array( $term->slug, $options ) ) {

								$id = $name . '-' . $term->slug;
								if ( $woo_ready_display_type == 'variation_color' ) {
									echo wp_kses_post(
										sprintf(
											'<label class="%s" style="%s" for="%s">' . '</label>',
											esc_attr( $cls ),
											wp_kses_post( $color ),
											esc_attr( $id )
										)
									);
								} else {

									echo wp_kses_post( '<label class="' . esc_attr( $cls ) . '" for="' . esc_attr( $id ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', esc_html( $term->name ), esc_html( $woo_ready_display_type ) ) ) . '</label>' );
								}
							}
						} // end forach
					}
				}
				echo wp_kses_post( '</a>' );
			}
		}

		echo wp_kses_post( '</div>' );
	}
	function get_price( $price, $cls = 'wooready_price_discount', $args = array() ) {
		$args = apply_filters(
			'wc_price_args',
			wp_parse_args(
				$args,
				array(
					'ex_tax_label'       => false,
					'currency'           => '',
					'decimal_separator'  => wc_get_price_decimal_separator(),
					'thousand_separator' => wc_get_price_thousand_separator(),
					'decimals'           => wc_get_price_decimals(),
					'price_format'       => get_woocommerce_price_format(),
				)
			)
		);

		$original_price = $price;

		// Convert to float to avoid issues on PHP 8.
		$price = (float) $price;

		$unformatted_price = $price;
		$negative          = $price < 0;

		$price = apply_filters( 'woo_ready_layout1_price', $negative ? $price * -1 : $price, $original_price );

		$price = apply_filters( 'formatted_woo_ready_layout1_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $original_price );

		if ( apply_filters( 'woo_ready_layout1_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $args['currency'] ) . '</span>', $price );
		$return          = '<span class="' . $cls . '"><bdi>' . $formatted_price . '</bdi></span>';

		if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
			$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		return apply_filters( 'woo_ready_layout_one_price', $return, $price, $args, $unformatted_price, $original_price );
	}

	function loop_rating() {
		echo wp_kses_post( sprintf( '<div class="wooready_review display:flex order:1 sr-grid-review-wrapper">' ) );
		wc_get_template( 'loop/rating.php' );
		echo wp_kses_post( '</div>' );
	}

	function loop_product_below() {
		 global $product;

		echo wp_kses_post( '<div class="wooready_product_content_box display:flex flex-direction:column text-align:center margin-top:15">' );
		$this->loop_rating();
		$this->title();
		$this->loop_price();
		$this->variation_color_price();
		$this->stock_range();
		echo wp_kses_post( '</div>' );
	}

	function title() {
		global $product;
		$link = apply_filters( 'woo_ready_loop_product_link', esc_url( get_the_permalink() ), $product );
		echo wp_kses_post( '<div class="wooready_title order:2">' );
		echo wp_kses_post( sprintf( '<h3 class="title margin:1"><a href="%s">%s</a></h3>', esc_url( $link ), esc_html( $product->get_name() ) ) );
		echo wp_kses_post( '</div>' );
	}

	/**
	 * wrapper_open
	 *
	 * @return void
	 */
	public function woocommerce_template_loop_product_wrapper_open() {  }

	/**
	 * Wrapper Close
	 */
	public function woocommerce_template_loop_product_tag_close() {     }

	public function thumnnail() {
		global $product;

		if ( $product ) {
			echo sprintf( '<div class="wooready_product_thumb text-align:center position:relative border-radius:20 overflow:hidden overflow:hidden">' );

			echo wp_kses_post( woocommerce_get_product_thumbnail() );

			$this->loop_sale_flash();

			$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
			echo wp_kses_post( '<div class="wooready_product_thumb_overlay position:absolute left:0 top:0 height:100% width:100%">' );
			echo wp_kses_post( '<div class="wooready_list display:flex align-items:center height:100%">' );
			echo wp_kses_post( '<ul class="flex-basis:100 wready-extra-icons">' );
			do_action( 'shop_ready_loop_product_thumb_inner' );
			echo wp_kses_post( '</ul>' );
			echo wp_kses_post( '</div>' );
			echo wp_kses_post( '</div>' );

			echo wp_kses_post( '</div>' );
		}
	}
	function loop_sale_flash() {
		if ( ! shop_ready_sysytem_module_options_is_active( 'product_badge' ) ) {
			return;
		}

		global $post, $product;

		$sales_badge = get_post_meta( $product->get_id(), '_saleflash_text', true );
		$sale        = $sales_badge == '' ? esc_html__( 'Sale', 'shopready-elementor-addon' ) : $sales_badge;

		if ( ( is_numeric( $product->get_sale_price() ) && ! empty( $product->get_sale_price() ) ) && ( is_numeric( $product->get_regular_price() ) && ! empty( $product->get_regular_price() ) ) ) {
			$sale = '-' . number_format( (float) ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() * 100, 1, '.', '' ) . '%';
		}

		if ( $product->is_on_sale() ) {
			echo wp_kses_post(
				sprintf(
					'<span class="wooready_sell_discount position:absolute top:15 right:15">%s</span>',
					esc_html( $sale )
				)
			);
		}
	}
}
