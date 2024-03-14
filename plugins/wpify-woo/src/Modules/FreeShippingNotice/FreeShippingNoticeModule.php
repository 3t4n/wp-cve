<?php

namespace WpifyWoo\Modules\FreeShippingNotice;

use WpifyWoo\Abstracts\AbstractModule;

class FreeShippingNoticeModule extends AbstractModule {

	/**
	 * Setup
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );

		if ( ! empty( $this->get_setting( 'positions' ) ) ) {
			foreach ( $this->get_setting( 'positions' ) as $position ) {
				add_action( $position, array( $this, 'render_free_shipping_notice' ) );
			}
		}

		add_shortcode( 'wpify_woo_free_shipping_notice', array( $this, 'free_shipping_notice_shortcode' ) );
		add_shortcode( 'wpify_woo_amount_for_free_shipping', array( $this, 'render_amount_for_free_shipping' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_fragments' ) );
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'add_to_fragments' ) );

		if ( $this->get_setting( 'free_shipping_if_any_method_free' ) ) {
			add_filter( 'wpify_woo_free_shipping_is_free', [ $this, 'maybe_set_free_shipping_notice' ] );
		}

		add_action('wp_enqueue_scripts', function() {
			if ( ! wp_script_is( 'wc-cart-fragments', 'enqueued' ) && wp_script_is( 'wc-cart-fragments', 'registered' ) ) {
				wp_enqueue_script( 'wc-cart-fragments' );
			}
		}, 10000);
	}

	/**
	 * Module ID
	 * @return string
	 */
	public function id(): string {
		return 'free_shipping_notice';
	}


	/**
	 * Settings
	 * @return array[]
	 */
	public function settings(): array {
		return array(
				array(
						'id'    => 'free_shipping_title',
						'type'  => 'title',
						'label' => __( 'Free shipping notice', 'wpify-woo' ),
						'desc'  => __( 'Use this module to display the remaining amount to get free shipping in your store. You can pick from one of the available positions, or use shortcode [wpify_woo_free_shipping_notice] to display the notice anywhere on your site.',
								'wpify-woo' ),
				),
				array(
						'id'      => 'free_shipping_amount',
						'type'    => 'text',
						'label'   => __( 'Free shipping cart subtotal', 'wpify-woo' ),
						'desc'    => __( 'Enter the the cart subtotal price to get free shipping', 'wpify-woo' ),
						'default' => '10000',
				),
				array(
						'id'    => 'woo_free_shipping_amount',
						'type'  => 'switch',
						'label' => __( 'Load amount from Free shipping settings', 'wpify-woo' ),
						'desc'  => __( 'Check to load amount from WooCommerce free shipping settings.', 'wpify-woo' ),
				),
				array(
						'id'      => 'cart_amount_to_count',
						'type'    => 'select',
						'label'   => __( 'Which cart amount to count on', 'wpify-woo' ),
						'desc'    => __( 'Select from which shopping cart value you calculate free shipping.', 'wpify-woo' ),
						'options' => [
								[
										'label' => __( 'From Cart subtotal in VAT', 'wpify-woo' ),
										'value' => 'subtotal',
								],
								[
										'label' => __( 'From Cart subtotal ex VAT', 'wpify-woo' ),
										'value' => 'subtotal_ex_vat',
								],
								[
										'label' => __( 'From Cart Subtotal after discount in VAT', 'wpify-woo' ),
										'value' => 'subtotal_discount',
								],
								[
										'label' => __( 'From Cart Subtotal after discount ex VAT', 'wpify-woo' ),
										'value' => 'subtotal_discount_ex_vat',
								],
								[
										'label' => __( 'From Cart total', 'wpify-woo' ),
										'value' => 'total',
								],
						],
						'default' => 'subtotal',
				),
				array(
						'id'      => 'free_shipping_message',
						'type'    => 'text',
						'label'   => __( 'Free shipping message', 'wpify-woo' ),
						'desc'    => __( 'Enter the message that should be displayed. The {price} will be replaced with the price to get free shippping', 'wpify-woo' ),
						'default' => __( 'Buy for {price} more to get free shipping', 'wpify-woo' ),
				),
				array(
						'id'      => 'free_shipping_confirmation_message',
						'type'    => 'text',
						'label'   => __( 'Free shipping confirmation message', 'wpify-woo' ),
						'desc'    => __( 'Enter the message that should be displayed when the user has free shipping.', 'wpify-woo' ),
						'default' => __( 'Great, you have the shipping free!', 'wpify-woo' ),
				),
				array(
						'id'      => 'minimum_cart_amount_to_display',
						'type'    => 'text',
						'label'   => __( 'Minimum cart amount to display', 'wpify-woo' ),
						'desc'    => __( 'Enter the minimum cart subtotal to display the message', 'wpify-woo' ),
						'default' => '0',
				),
				array(
						'id'      => 'always_show',
						'type'    => 'switch',
						'label'   => __( 'Always show', 'wpify-woo' ),
						'desc'    => __( 'If not checked, the notification bar will only be displayed if some item in cart needs shipping. Check to always show the notification, even if the cart is empty. ', 'wpify-woo' ),
						'default' => '0',
				),
				array(
						'id'    => 'free_shipping_if_any_method_free',
						'type'  => 'switch',
						'label' => __( 'Set free shipping if any shipping method is free', 'wpify-woo' ),
						'desc'  => __( 'Check if you wish to display Free shipping confirmation message if any of the shipping methods is free.', 'wpify-woo' ),
				),
				array(
						'id'      => 'positions',
						'type'    => 'multiswitch',
						'label'   => __( 'Positions', 'wpify-woo' ),
						'desc'    => __( 'Check the positions where you would like to get the message displayed', 'wpify-woo' ),
						'options' => array(
								array(
										'label' => __( 'Above cart', 'wpify-woo' ),
										'value' => 'woocommerce_before_cart',
								),
								array(
										'label' => __( 'Bellow cart', 'wpify-woo' ),
										'value' => 'woocommerce_after_cart',
								),
								array(
										'label' => __( 'Top of the checkout', 'wpify-woo' ),
										'value' => 'woocommerce_before_checkout_form',
								),
								array(
										'label' => __( 'Bottom of the checkout', 'wpify-woo' ),
										'value' => 'woocommerce_after_checkout_form',
								),
						),
						'default' => array(),
				),
				array(
						'id'    => 'background_color',
						'type'  => 'colorpicker',
						'label' => __( 'Background color', 'wpify-woo' ),
						'desc'  => __( 'Select the background color', 'wpify-woo' ),
				),
				array(
						'id'    => 'text_color',
						'type'  => 'colorpicker',
						'label' => __( 'Text color', 'wpify-woo' ),
						'desc'  => __( 'Select the background color', 'wpify-woo' ),
				),
				array(
						'id'    => 'show_icon',
						'type'  => 'switch',
						'label' => __( 'Show icon', 'wpify-woo' ),
						'desc'  => __( 'Check to display the icon', 'wpify-woo' ),
				),
				array(
						'id'      => 'icon_color',
						'type'    => 'colorpicker',
						'label'   => __( 'Icon color', 'wpify-woo' ),
						'desc'    => __( 'Select the icon color', 'wpify-woo' ),
						'default' => '#000000',
				),
				array(
						'id'      => 'progressbar_color',
						'type'    => 'colorpicker',
						'label'   => __( 'Progressbar color', 'wpify-woo' ),
						'desc'    => __( 'Select the progressbar color', 'wpify-woo' ),
						'default' => '#008B8B',
				),
		);
	}

	public function add_to_fragments( $fragments ) {
		$fragments['.wpify-woo-free-shipping-notice__wrapper'] = $this->free_shipping_notice_shortcode();

		return $fragments;
	}

	/**
	 * Render the free shipping notice shortcode.
	 * @return string
	 */
	public function free_shipping_notice_shortcode(): string {
		ob_start();
		$this->render_free_shipping_notice();

		return ob_get_clean();
	}

	/**
	 * Render the free shipping notice
	 */
	public function render_free_shipping_notice() {
		if ( empty( $this->get_setting( 'free_shipping_message' ) )
			 || apply_filters( 'wpify_woo_free_shipping_render_notice', true ) === false ) {
			return;
		}

		if ( ( ! WC()->cart || ! WC()->cart->needs_shipping() ) && ! $this->get_setting( 'always_show' ) ) {
			return;
		}

		$minimum_cart_amount_to_display = (float) $this->get_setting( 'minimum_cart_amount_to_display' );

		if ( $minimum_cart_amount_to_display && $this->get_cart_amount() < $minimum_cart_amount_to_display ) {
			return;
		}

		$style = 'padding: 20px; display: flex;';
		if ( ! empty( $this->get_setting( 'background_color' ) ) ) {
			$style .= sprintf( 'background-color: %s; ', esc_attr( $this->get_setting( 'background_color' ) ) );
		}
		if ( ! empty( $this->get_setting( 'text_color' ) ) ) {
			$style .= sprintf( 'color: %s; ', esc_attr( $this->get_setting( 'text_color' ) ) );
		}

		?>
		<style type="text/css">
			.progress {
				background: rgba(255, 255, 255, 0.1);
				justify-content: flex-start;
				border-radius: 100px;
				align-items: center;
				position: relative;
				padding: 0 5px;
				display: flex;
				height: 10px;
				width: 100%;
			}

			.progress-value {
				animation: load 1.5s normal forwards;
				box-shadow: 0 10px 40px -10px #fff;
				border-radius: 100px;
				background: <?php echo $this->get_setting( 'progressbar_color' ) ?: '#fff'; ?>;
				height: 5px;
				width: 0;
			}

			@keyframes load {
				0% {
					width: 0;
				}
				100% {
					width: <?php echo $this->get_percentage_for_free_shipping(); ?>%;
				}
			}
		</style>
		<?php
		if ( $this->get_amount_for_free_shipping() < 0 && empty( $this->get_setting( 'free_shipping_confirmation_message' ) ) ) {
			?>
			<div class="wpify-woo-free-shipping-notice__wrapper"></div>
			<?php
			return;
		}
		?>
		<div class="wpify-woo-free-shipping-notice__wrapper">
			<div class="wpify-woo-free-shipping-notice" style="<?php echo esc_attr( $style ); ?>">
				<div>
					<?php if ( ! empty( $this->get_setting( 'show_icon' ) ) ) { ?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 61.03"
							 style="width: 60px; height: auto; display: inline-block; vertical-align: middle; margin-right: 10px;">
							<path style="<?php echo $this->get_setting( 'icon_color' ) ? sprintf( 'fill: %s;', $this->get_setting( 'icon_color' ) ) : ''; ?>"
								  d="M99.15 15.38l-14.77-.09V5.36c0-1.48-.61-2.82-1.57-3.78C81.84.61 80.51 0 79.02 0H23.46c-1.48 0-2.82.61-3.78 1.57a5.32 5.32 0 00-1.57 3.78c0 .82.64 1.45 1.45 1.45s1.45-.64 1.45-1.45c0-.67.27-1.3.73-1.73.45-.45 1.06-.73 1.73-.73h55.56c.67 0 1.27.27 1.73.73.45.45.73 1.06.73 1.73v45.5H70.02c-.82 0-1.45.64-1.45 1.45 0 .79.64 1.45 1.45 1.45h12.91c.82 0 1.45-.64 1.45-1.45v-1.67h8.78c.67-15.14 22.37-17.22 24.58 0h4.78c.63-3.36.4-7.28-.48-11.6-1.02-5.04-.72-4.06-5.43-5.87l-9.6-4.3-7.86-13.48zM18.4 31.46c.81 0 1.46.66 1.46 1.46 0 .81-.66 1.46-1.46 1.46H8.02c-.81 0-1.46-.66-1.46-1.46 0-.81.66-1.46 1.46-1.46H18.4zm0-9.57c.81 0 1.46.66 1.46 1.46 0 .81-.66 1.46-1.46 1.46H5.27c-.81 0-1.46-.66-1.46-1.46 0-.81.66-1.46 1.46-1.46H18.4zm0-9.57c.81 0 1.46.66 1.46 1.46 0 .81-.66 1.46-1.46 1.46H1.46c-.8.01-1.46-.65-1.46-1.45 0-.81.66-1.46 1.46-1.46H18.4v-.01zM65.07 12h9.58v4.24h-3.83v4.06h3.58v4.03h-3.58V29h4.22v4.24h-9.97V12zm-12.1 0h9.58v4.24h-3.83v4.06h3.58v4.03h-3.58V29h4.22v4.24h-9.97V12zm-15.53 0h4.06c2.71 0 4.54.1 5.5.3.96.2 1.74.72 2.35 1.54.61.82.91 2.14.91 3.95 0 1.65-.21 2.76-.64 3.33-.42.57-1.27.91-2.52 1.02 1.14.27 1.9.64 2.29 1.09.39.45.63.87.73 1.25.1.38.14 1.43.14 3.14v5.61h-5.33v-7.07c0-1.14-.09-1.84-.28-2.11-.18-.27-.67-.41-1.45-.41v9.59h-5.75V12h-.01zm5.75 3.63v4.73c.64 0 1.09-.08 1.35-.26.26-.17.39-.73.39-1.66v-1.17c0-.68-.13-1.12-.37-1.33-.26-.21-.71-.31-1.37-.31zM25.83 12h9.72v4.24h-3.97v4.06h3.52v4.03h-3.52v8.92h-5.75V12zm14.28 38.86a1.451 1.451 0 010 2.9H23.49c-1.48 0-2.82-.64-3.78-1.63a5.57 5.57 0 01-1.57-3.84v-5.81c0-.79.64-1.45 1.45-1.45.82 0 1.45.64 1.45 1.45v5.81c0 .7.3 1.36.76 1.85.45.45 1.03.76 1.7.76l16.61-.04zm14.77-9.51c-5.45 0-9.84 4.42-9.84 9.84 0 5.45 4.42 9.84 9.84 9.84 5.45 0 9.84-4.42 9.84-9.84 0-5.45-4.42-9.84-9.84-9.84zm0 6.06c-2.09 0-3.78 1.7-3.78 3.78 0 2.09 1.7 3.78 3.78 3.78 2.09 0 3.78-1.7 3.78-3.78.01-2.09-1.69-3.78-3.78-3.78zm50.57-6.06c-5.45 0-9.84 4.42-9.84 9.84 0 5.45 4.42 9.84 9.84 9.84 5.45 0 9.84-4.42 9.84-9.84 0-5.45-4.42-9.84-9.84-9.84zm0 6.06c-2.09 0-3.78 1.7-3.78 3.78 0 2.09 1.7 3.78 3.78 3.78 2.09 0 3.78-1.7 3.78-3.78 0-2.09-1.69-3.78-3.78-3.78zM95.37 19.53l-7.33-.09v9.44h12.29l-4.96-9.35z"
								  fill-rule="evenodd" clip-rule="evenodd"/>
						</svg>
					<?php } ?>
				</div>
				<div>

					<?php echo $this->get_free_shipping_message(); ?>
					<div class="progress">
						<div class="progress-value"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_cart_amount() {
		if ( empty( WC()->cart ) ) {
			return 0;
		}

		if ( $this->get_setting( 'cart_amount_to_count' ) === 'subtotal_discount_ex_vat' ) {
			return WC()->cart->get_subtotal() - WC()->cart->get_cart_discount_total();
		} elseif ( $this->get_setting( 'cart_amount_to_count' ) === 'subtotal_discount' ) {
			return ( WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax() ) - ( WC()->cart->get_cart_discount_total() + WC()->cart->get_cart_discount_tax_total() );
		} elseif ( $this->get_setting( 'cart_amount_to_count' ) === 'total' ) {
			return WC()->cart->total;
		} elseif ( $this->get_setting( 'cart_amount_to_count' ) === 'subtotal_ex_vat' ) {
			return WC()->cart->get_subtotal();
		} else {
			return WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
		}
	}

	public function get_percentage_for_free_shipping() {
		return round( ( ( $this->get_cart_amount() ) / $this->get_free_shipping_amount() ) * 100 );
	}

	public function get_free_shipping_amount() {
		$amount = $this->get_setting( 'free_shipping_amount' );

		if ( $this->get_setting( 'woo_free_shipping_amount' ) ) {
			$shipping_packages = WC()->cart->get_shipping_packages();
			$shipping_zone     = wc_get_shipping_zone( reset( $shipping_packages ) );
			$shipping_methods  = $shipping_zone->get_shipping_methods();

			if ( is_array( $shipping_methods ) ) {
				foreach ( $shipping_methods as $i => $shipping_method ) {
					if ( is_numeric( $i ) ) {
						if ( $shipping_method->id == 'free_shipping' && $shipping_method->enabled == 'yes' ) {
							if ( $shipping_method->min_amount <= 0 ) {
								continue;
							}

							$amount = $shipping_method->min_amount;
						}
					}
				}
			}
		}

		// Compatibility with Woo Currency Switcher
		$amount = apply_filters( 'woocs_exchange_value', $amount );

		return apply_filters( 'wpify_woo_free_shipping_amount', $amount, $this );
	}

	/**
	 * Get the free shipping amount
	 * @return float|mixed
	 */
	public function get_amount_for_free_shipping(): float {
		return apply_filters( 'wpify_woo_free_shipping_amount_for_free_shipping', $this->get_free_shipping_amount() - ( $this->get_cart_amount() ) );
	}

	/**
	 * Render free shipping message
	 * @return string
	 */
	public function get_free_shipping_message(): string {
		$amount        = $this->get_amount_for_free_shipping();
		$free_shipping = apply_filters( 'wpify_woo_free_shipping_is_free', $amount <= 0 );

		if ( $free_shipping ) {
			return $this->get_setting( 'free_shipping_confirmation_message' );
		}

		return ! empty( $this->get_setting( 'free_shipping_message' ) ) ? str_replace( '{price}', wc_price( $this->get_amount_for_free_shipping() ), $this->get_setting( 'free_shipping_message' ) ) : '';
	}

	public function render_amount_for_free_shipping(): string {
		return wc_price( $this->get_amount_for_free_shipping() );
	}

	public function name() {
		return __( 'Free shipping notice', 'wpify-woo' );
	}

	public function maybe_set_free_shipping_notice( $free ) {
		if ( ! WC()->session ) {
			return $free;
		}

		foreach ( WC()->session->get_session_data() as $key => $data ) {
			if ( strpos( $key, 'package' ) !== false ) {
				$item = WC()->session->get( $key );
				if ( empty( $item['rates'] ) ) {
					continue;
				}

				foreach ( $item['rates'] as $rate ) {
					if ( ! floatval( $rate->get_cost() ) ) {
						$free = true;
						break 2;
					}
				}
			}
		}
		if ( $free ) {
			add_filter( 'wpify_woo_free_shipping_amount_for_free_shipping', function () {
				return 0;
			} );
		}

		return $free;
	}
}
