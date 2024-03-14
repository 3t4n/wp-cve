<?php
/**
 * PeachPay Express Checkout functions
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * The PeachPay express checkout should not show in the page navigation. This function removes it.
 *
 * @param array $page_ids The page ids to hide.
 */
function pp_checkout_hide_navigation( $page_ids ) {
	global $pp_checkout_page_id;

	if ( is_int( $pp_checkout_page_id ) ) {
		array_push( $page_ids, strval( $pp_checkout_page_id ) );
	}

	return $page_ids;
}

/**
 * Loads the correct page template for the PeachPay express checkout page.
 *
 * @param string $template The template path to load.
 */
function pp_checkout_template_loader( $template ) {
	if ( is_singular() ) {
		if ( pp_is_express_checkout() ) {
			return peachpay_locate_template( 'html-express-checkout.php', PEACHPAY_ABSPATH . 'core/modules/express-checkout/templates/' );
		}
	}

	return $template;
}

/**
 * Returns whether the current page is the PeachPay express checkout page.
 */
function pp_is_express_checkout() {
	global $pp_checkout_page_id;

	if ( is_int( $pp_checkout_page_id ) ) {
		return ( function_exists( 'is_page' ) && is_page( $pp_checkout_page_id ) ) || ( function_exists( 'is_single' ) && is_single( $pp_checkout_page_id ) );
	}

	return false;
}

/**
 * Get the data required for the PeachPay express checkout button script on the frontend.
 */
function pp_checkout_button_data() {
	/**
	 * Allow for customizing the data sent to the frontend for the PeachPay express checkout button.
	 *
	 * @param array $data The to be JSON encoded data to send to the frontend for button init.
	 */
	return apply_filters(
		'pp_checkout_button_data',
		array(
			'add_to_cart_url'           => WC_AJAX::get_endpoint( 'add-to-cart' ),
			'express_checkout_url'      => pp_checkout_permalink(),
			'express_checkout_fragment' => pp_checkout_iframe_container(),
			'translations'              => array(
				'add_product_fail' => __( 'Adding the product to the cart failed. Please try again.', 'peachpay-for-woocommerce' ),
			),
		)
	);
}

/**
 * Get the data required for the PeachPay express checkout script on the frontend.
 */
function pp_checkout_page_data() {
	// This filter is to allow plugin compatibility to allow plugins to add meta data dynamically so we can 1 reduce
	// what we have to send but also be loosely coupled with plugins we support. If the data will always be present
	// then it should be added directly here.
	return apply_filters(
		'peachpay_script_data',
		array(
			'version'                   => PEACHPAY_VERSION,
			'feature_support'           => peachpay_feature_support_record(),
			'plugin_asset_url'          => peachpay_url( '' ),
			'peachpay_api_url'          => peachpay_api_url(),

			'merchant_name'             => get_bloginfo( 'name' ),
			'merchant_id'               => peachpay_plugin_merchant_id(),
			'wp_hostname'               => preg_replace( '(^https?://)', '', home_url() ),

			'cart_calculation_response' => peachpay_cart_calculation(),

			'num_shipping_zones'        => count( WC_Shipping_Zones::get_zones() ),
			'merchant_customer_account' => peachpay_get_merchant_customer_account(),
			'currency_info'             => peachpay_get_currency_info(),

			'wc_tax_price_display'      => ( isset( WC()->cart ) && '' !== WC()->cart && method_exists( 'WC_Cart', 'get_tax_price_display_mode' ) ) ? WC()->cart->get_tax_price_display_mode() : '',
			'wc_location_info'          => peachpay_location_details(),
			'wc_terms_conditions'       => peachpay_wc_terms_condition(),

			'country_state_options'     => wp_json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
			'country_field_locale'      => wp_json_encode( WC()->countries->get_country_locale() ),
		)
	);
}

/**
 * Renders the PeachPay express checkout iframe container.
 */
function pp_checkout_iframe_container() {
	ob_start();
	?>
	<div id="peachpay-checkout-container">
		<div id="peachpay-checkout-backdrop" style="display:none;">
			<img class="loading-spinner" src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" alt="Throbber">
			<div class="loading-messages">
				<p class="slow-loading message hide">
					<?php esc_html_e( "We're still loading, hang tight for a few seconds.", 'peachpay-for-woocommerce' ); ?>
				</p>
				<p class="error-loading message hide">
					<?php esc_html_e( 'Something went wrong loading the checkout.', 'peachpay-for-woocommerce' ); ?>
					<br/>
					<br/>
					<a href="<?php echo esc_attr( wc_get_checkout_url() ); ?>">
						<?php esc_html_e( 'Please check out here instead', 'peachpay-for-woocommerce' ); ?>
					</a>
				</p>
				<a href="#" class="close-loading message">
					<?php esc_html_e( 'Close', 'peachpay-for-woocommerce' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Renders the PeachPay express checkout button for the Product Page
 */
function pp_checkout_product_page_button() {
	$product = wc_get_product();
	if ( ! $product || $product->is_type( 'external' ) ) {
		return;
	}

	$enabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'display_on_product_page' );
	if ( ! $enabled || ! pp_should_display_public() ) {
		return;
	}

	/**
	 * Filter whether the PeachPay express checkout button should be hidden on the product page.
	 *
	 * @param bool $hidden Whether the PeachPay express checkout button should be hidden on the product page.
	 */
	$hide_button = apply_filters( 'pp_checkout_product_page_button_hide', false );
	if ( $hide_button ) {
		return;
	}

	/**
	 * Perform action before the PeachPay express checkout button is rendered on the product page.
	 */
	do_action( 'pp_checkout_before_product_page_button_html' );

	$text                            = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express Checkout', 'peachpay-for-woocommerce' ) );
	$text_color                      = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR );
	$background_color                = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR );
	$icon                            = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' );
	$effect                          = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' );
	$border_radius                   = intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) );
	$width                           = intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_width_product_page', 220 ) );
	$display_available_payment_icons = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false );
	$custom_styles                   = '';
	$positioning                     = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'product_button_position', 'after' );
	if ( 'after' === $positioning ) {
		$alignment = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'product_button_alignment' );

		// Make the button take the next line with display:block and make sure it does not touch the previous line using the top margin.
		$custom_styles = 'margin-top:16px;display:block;clear:both;';

		if ( 'left' === $alignment ) {
			$custom_styles .= 'margin-right: auto;';
		} elseif ( 'right' === $alignment ) {
			$custom_styles .= 'margin-left: auto;';
		} elseif ( 'center' === $alignment ) {
			$custom_styles .= 'margin-left: auto; margin-right: auto;';
		} elseif ( 'full' === $alignment ) {
			$width = '100%';
		}
	} else {
		// If the button is set to be inline then we do not want to show the payment icons and the width should always be "auto".
		$display_available_payment_icons = false;
		$width                           = 'auto';
	}

	$initial_args = array(
		'text'                            => $text,
		'border_radius'                   => $border_radius,
		'text_color'                      => $text_color,
		'background_color'                => $background_color,
		'width'                           => $width,
		'icon_class'                      => $icon,
		'effect_class'                    => $effect,
		'display_available_payment_icons' => $display_available_payment_icons,
		'custom_styles'                   => $custom_styles,
		'custom_classes'                  => 'single_add_to_cart_button',
		'custom_attributes'               => array(
			'type'                    => 'submit',
			'data-activation-trigger' => 'manual',
		),
		'element_type'                    => 'button',
	);

	$product = wc_get_product();
	if ( ! is_null( $product ) ) {
		$initial_args['custom_attributes']['formaction'] = $product->get_permalink() . '?open-express-checkout';

		if ( 'simple' === $product->get_type() ) {
			$initial_args['custom_attributes']['name']  = 'add-to-cart';
			$initial_args['custom_attributes']['value'] = $product->get_id();
		}
	}

	/**
	 * Customize the PeachPay express checkout button on the product page.
	 *
	 * @param array $args Arguments to define how to render the button on the product page
	 */
	$args = apply_filters(
		'pp_checkout_product_page_button_args',
		$initial_args
	);

	//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo pp_checkout_button_template( $args );

	/**
	 * Perform action after the PeachPay express checkout button is rendered on the product page.
	 */
	do_action( 'pp_checkout_after_product_page_button_html' );
}

/**
 * Redirects to the PeachPay express checkout page if the frontend javascript fails to enhance the checkout with a iframe popup overlay.
 *
 * @param string $url The fallback url.
 */
function pp_checkout_product_page_fallback_redirect( $url ) {
	if ( isset( $_GET["open-express-checkout"] ) ) {//PHPCS:ignore
		return pp_checkout_permalink();
	}

	return $url;
}

/**
 * Renders the PeachPay express checkout button for the Cart Page
 */
function pp_checkout_cart_page_button() {
	if ( did_action( 'woocommerce_proceed_to_checkout' ) > 1 ) {
		return;
	}

	$enabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'cart_page_enabled' );
	if ( ! $enabled || ! pp_should_display_public() ) {
		return;
	}

	/**
	 * Filter whether the PeachPay express checkout button should be hidden on the cart page.
	 *
	 * @param bool $hidden Whether the PeachPay express checkout button should be hidden on the cart page.
	 */
	$hide_button = apply_filters( 'pp_checkout_cart_page_button_hide', false );

	if ( $hide_button ) {
		return;
	}

	/**
	 * Perform action before the PeachPay express checkout button is rendered on the cart page.
	 */
	do_action( 'pp_checkout_before_cart_page_button_html' );

	$text                            = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express Checkout', 'peachpay-for-woocommerce' ) );
	$text_color                      = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR );
	$background_color                = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR );
	$icon                            = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' );
	$effect                          = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' );
	$border_radius                   = intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) );
	$width                           = intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_width_cart_page', 220 ) );
	$display_available_payment_icons = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false );

	$custom_styles = '';
	$alignment     = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'cart_button_alignment', 'full' );
	if ( 'left' === $alignment ) {
		$custom_styles = 'margin-right: auto;';
	} elseif ( 'right' === $alignment ) {
		$custom_styles = 'margin-left: auto;';
	} elseif ( 'center' === $alignment ) {
		$custom_styles = 'margin-left: auto; margin-right: auto;';
	} elseif ( 'full' === $alignment ) {
		$width = '100%';
	}

	/**
	 * Customize the PeachPay express checkout button on the cart page.
	 *
	 * @param array $args Arguments to define how to render the button on the cart page
	 */
	$button_args = apply_filters(
		'pp_checkout_cart_page_button_args',
		array(
			'text'                            => $text,
			'text_color'                      => $text_color,
			'background_color'                => $background_color,
			'icon_class'                      => $icon,
			'effect_class'                    => $effect,
			'border_radius'                   => $border_radius,
			'width'                           => $width,
			'custom_styles'                   => $custom_styles,
			'display_available_payment_icons' => $display_available_payment_icons,
			'custom_attributes'               => array(
				'href' => pp_checkout_permalink(),
			),
		)
	);

	//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo pp_checkout_button_template( $button_args );

	/**
	 * Perform action after the PeachPay express checkout button is rendered on the cart page.
	 */
	do_action( 'pp_checkout_after_cart_page_button_html' );
}

/**
 * Renders the PeachPay express checkout button for the mini cart(WC cart widget)
 */
function pp_checkout_mini_cart_button() {
	if ( did_action( 'woocommerce_widget_shopping_cart_buttons' ) > 1 ) {
		return;
	}

	$enabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'mini_cart_enabled' );
	if ( ! $enabled || ! pp_should_display_public() ) {
		return;
	}

	/**
	 * Filter whether the PeachPay express checkout button should be hidden on the product page.
	 *
	 * @param bool $hidden Whether the PeachPay express checkout button should be hidden on the product page.
	 */
	$hide_button = apply_filters( 'pp_checkout_mini_cart_button_hide', false );

	if ( $hide_button ) {
		return;
	}

	/**
	 * Perform action before the PeachPay express checkout button is rendered in the mini cart.
	 */
	do_action( 'pp_checkout_before_mini_cart_button_html' );

	/**
	 * Customize the PeachPay express checkout button in the mini cart.
	 *
	 * @param array $args Arguments to define how to render the button in the mini cart
	 */
	$button_args = apply_filters(
		'pp_checkout_mini_cart_button_args',
		array(
			'text'                            => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express Checkout', 'peachpay-for-woocommerce' ) ),
			'text_color'                      => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
			'background_color'                => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),
			'icon_class'                      => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' ),
			'effect_class'                    => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),
			'border_radius'                   => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) ),
			'width'                           => '100%',

			'display_available_payment_icons' => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false ),

			'custom_attributes'               => array(
				'href' => pp_checkout_permalink(),
			),
		)
	);

	//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo pp_checkout_button_template( $button_args );

	/**
	 * Perform action after the PeachPay express checkout button is rendered in the mini cart.
	 */
	do_action( 'pp_checkout_after_mini_cart_button_html' );
}

/**
 * Renders the PeachPay express checkout button for the mini cart(WC cart widget)
 */
function pp_checkout_checkout_page_button() {
	if ( did_action( 'woocommerce_before_checkout_form' ) > 1 ) {
		return;
	}

	$enabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'checkout_page_enabled' );
	if ( ! $enabled || ! pp_should_display_public() ) {
		return;
	}

	/**
	 * Filter whether the PeachPay express checkout button should be hidden on the product page.
	 *
	 * @param bool $hidden Whether the PeachPay express checkout button should be hidden on the product page.
	 */
	$hide_button = apply_filters( 'pp_checkout_checkout_page_button_hide', false );
	if ( $hide_button ) {
		return;
	}

	/**
	 * Customize the PeachPay express checkout button in the mini cart.
	 *
	 * @param array $args Arguments to define how to render the button in the mini cart
	 */
	$button_args = apply_filters(
		'pp_checkout_checkout_page_button_args',
		array(
			'text'                            => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'peachpay_button_text', __( 'Express Checkout', 'peachpay-for-woocommerce' ) ),
			'text_color'                      => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
			'background_color'                => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),
			'icon_class'                      => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_icon', '' ),
			'effect_class'                    => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),
			'border_radius'                   => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_border_radius', 5 ) ),
			'width'                           => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_width_checkout_page', 220 ) ),
			'custom_styles'                   => 'margin: 0 auto 8px;',

			'display_available_payment_icons' => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_display_payment_method_icons', false ),

			'header_text'                     => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'checkout_header_text', '' ),
			'additional_text'                 => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'checkout_subtext_text', '' ),

			'custom_attributes'               => array(
				'href' => pp_checkout_permalink(),
			),
		)
	);

	?>
	<div class="peachpay-express-checkout-options">
		<?php if ( $button_args['header_text'] ) : ?>
			<div style="text-align: center; margin: 16px 0;">
				<span><?php echo esc_html( $button_args['header_text'] ); ?></span>
			</div>
		<?php endif; ?>
		<div style="display:flex;">
			<?php

			/**
			 * Perform action before the PeachPay express checkout button is rendered on the checkout page.
			 */
			do_action( 'pp_checkout_before_checkout_page_button_html' );

			//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo pp_checkout_button_template( $button_args );

			/**
			 * Perform action after the PeachPay express checkout button is rendered on the checkout page.
			 */
			do_action( 'pp_checkout_after_checkout_page_button_html' );
			?>
		</div>
		<?php if ( $button_args['additional_text'] ) : ?>
			<div style="text-align: center; margin: 16px 0;">
				<span><?php echo esc_html( $button_args['additional_text'] ); ?></span>
			</div>
		<?php endif; ?>
		<div style="display:flex; flex-direction: row; align-items: center; margin: 16px 0;">
			<hr style="flex: 1;margin: unset;"/>
			<span style="margin: 0 12px;">
				<?php esc_html_e( 'OR', 'peachpay-for-woocommerce' ); ?>
			</span>
			<hr style="flex: 1;margin: unset;"/>
		</div>
	</div>
	<?php
}

/**
 * Checks if this is the WooCommerce blocks checkout and add Express Checkout button if necesary.
 *
 * @param string $filter_string Specific WordPress content string.
 *
 * @return string $filter_string Updated filter string which conditionally has the Express Checkout button.
 */
function pp_checkout_blocks_checkout_page_button( $filter_string ) {
	// Check for woocommerce checkout block specificaiton.
	$has_data_attribute = strpos( $filter_string, 'data-block-name="woocommerce/checkout"' );
	// Also checks for products in the cart, must have at least one item in the cart for this to show.
	if ( $has_data_attribute && ( isset( WC()->cart ) && '' !== WC()->cart && is_array( WC()->cart->cart_contents ) && count( WC()->cart->cart_contents ) ) ) {
		// Collect Express Checkout button template and place into WooCommerce checkout.
		ob_start();
		pp_checkout_checkout_page_button();
		$express_checkout_string = ob_get_clean();

		$filter_string = substr_replace( $filter_string, $express_checkout_string, 0, 0 );
	}

	return $filter_string;
}

/**
 * Returns the PeachPay express checkout button HTML template using the provided arguments.
 *
 * @param array $args Arguments to define how to render the express checkout button.
 */
function pp_checkout_button_template( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'element_type'                    => 'a',
			'text'                            => __( 'Express checkout', 'peachpay-for-woocommerce' ),
			'icon_class'                      => '',
			'effect_class'                    => '',
			'background_color'                => PEACHPAY_DEFAULT_BACKGROUND_COLOR,
			'text_color'                      => PEACHPAY_DEFAULT_TEXT_COLOR,
			'width'                           => 'auto',
			'border_radius'                   => 0,
			'custom_classes'                  => '',
			'custom_styles'                   => '',
			'custom_attributes'               => array(),

			'display_available_payment_icons' => false,
			'available_payment_icons_maximum' => 4,

			'preview'                         => false,
		)
	);

	if ( $args['display_available_payment_icons'] ) {
		// 41px bottom margin because the icons are 25px tall and we want 8px of space between the button and the icons and below the icons.
		$args['custom_styles'] .= 'margin-bottom: 41px;';
	}

	return sprintf(
		'<%1$s
			data-peachpay-button="true"
			class="checkout-button button %2$s"
			style="width: %6$s;position: relative;border-radius: %5$s;--pp-button-text-color: %3$s;--pp-button-background-color: %4$s;%7$s"
			%8$s
		>
			%9$s
			%10$s
			%11$s
		</%1$s>
		',
		esc_html( $args['element_type'] ),
		esc_attr( $args['custom_classes'] . ' ' . $args['effect_class'] ),
		esc_attr( $args['text_color'] ),
		esc_attr( $args['background_color'] ),
		esc_attr( is_int( $args['border_radius'] ) ? $args['border_radius'] . 'px' : $args['border_radius'] ),
		esc_attr( is_int( $args['width'] ) ? $args['width'] . 'px' : $args['width'] ),
		esc_attr( $args['custom_styles'] ),
		peachpay_html_build_attributes( $args['custom_attributes'] ),
		esc_html( $args['text'] ),
		( $args['preview'] || ( $args['icon_class'] && peachpay_starts_with( $args['icon_class'], 'pp-icon-' ) && 'pp-icon-disabled' !== $args['icon_class'] ) ) ? '<i class="' . $args['icon_class'] . '" style="line-height:inherit;vertical-align:middle;"></i>' : '',
		( $args['preview'] || $args['display_available_payment_icons'] ) ? pp_checkout_available_gateway_icons_template(
			array(
				'maximum_icons' => $args['available_payment_icons_maximum'],
				'display'       => $args['display_available_payment_icons'] ? '' : 'hide',
			)
		) : ''
	);
}

/**
 * Renders the PeachPay express checkout floating button.
 */
function pp_checkout_floating_button() {
	if ( is_object( WC()->cart ) && 0 >= WC()->cart->cart_contents_count ) {
		?>
		<div id="peachpay-floating-button-container"></div>
		<?php
		return;
	}

	$enabled = peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_enabled' );
	if ( ! $enabled || ! pp_should_display_public() ) {
		return;
	}

	/**
	 * Filter whether the PeachPay express checkout button should hide the floating button.
	 *
	 * @param bool $hidden Whether the PeachPay express checkout button should hide the floating.
	 */
	$hide_button = apply_filters( 'pp_checkout_floating_button_hide', false );
	if ( $hide_button ) {
		return;
	}

	/**
	 * Customize the PeachPay express checkout floating button
	 *
	 * @param array $args Arguments to define how to render the floating button
	 */
	$button_args = apply_filters(
		'pp_checkout_floating_button_args',
		array(
			'icon_class'        => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_icon', 'pp-icon-lock' ),
			'effect_class'      => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'button_effect', 'none' ),

			'text_color'        => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_text_color', PEACHPAY_DEFAULT_TEXT_COLOR ),
			'background_color'  => peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'button_color', PEACHPAY_DEFAULT_BACKGROUND_COLOR ),

			'button_length'     => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_size', 70 ) ),
			'icon_length'       => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_icon_size', 35 ) ),

			'position'          => peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_alignment', 'right' ),
			'bottom_gap'        => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_bottom_gap', 27 ) ),
			'side_gap'          => intval( peachpay_get_settings_option( 'peachpay_express_checkout_button', 'floating_button_side_gap', 45 ) ),

			'custom_attributes' => array(
				'href' => pp_checkout_permalink(),
			),
		)
	);

	if ( 'left' === $button_args['position'] ) {
		$button_args['custom_styles'] = 'left: ' . $button_args['side_gap'] . 'px;';
	} elseif ( 'right' === $button_args['position'] ) {
		$button_args['custom_styles'] = 'right: ' . $button_args['side_gap'] . 'px;';
	}

	?>
	<div id="peachpay-floating-button-container">
		<?php
		//PHPCS:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo pp_checkout_floating_button_template( $button_args );
		?>
	</div>
	<?php
}

/**
 * Returns the PeachPay express checkout button HTML template using the provided arguments.
 *
 * @param array $args Arguments to define how to render the express checkout button.
 */
function pp_checkout_floating_button_template( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'icon_class'        => 'pp-icon-lock',
			'effect_class'      => 'none',

			'text_color'        => PEACHPAY_DEFAULT_TEXT_COLOR,
			'background_color'  => PEACHPAY_DEFAULT_BACKGROUND_COLOR,

			'button_length'     => 70,
			'icon_length'       => 35,

			'position'          => 'right',
			'bottom_gap'        => 27,
			'side_gap'          => 45,

			'custom_classes'    => '',
			'custom_styles'     => '',
			'custom_attributes' => array(),

			'preview'           => false,
		)
	);

	return sprintf(
		'<a
			id="peachpay-floating-button"
			data-peachpay-button="true"
			class="%1$s"
			style="position: %11$s;display: flex;height: %4$s;width: %4$s;align-items: center;justify-content: center;bottom: %5$s;border-radius: 50%%;z-index: 100000;--pp-button-text-color: %2$s;--pp-button-background-color: %3$s;%6$s"
			%7$s
		>
			<i 
				class="%8$s"
				style="font-size: %9$s;"
			></i>
			%10$s
		</a>
		',
		esc_attr( $args['custom_classes'] . ' ' . $args['effect_class'] ),
		esc_attr( $args['text_color'] ),
		esc_attr( $args['background_color'] ),
		esc_attr( $args['button_length'] . 'px' ),
		$args['preview'] ? '0px' : esc_attr( $args['bottom_gap'] . 'px' ),
		esc_attr( $args['custom_styles'] ),
		peachpay_html_build_attributes( $args['custom_attributes'] ),
		esc_attr( peachpay_starts_with( $args['icon_class'], 'pp-icon-' ) ? $args['icon_class'] : 'pp-icon-lock' ),
		esc_attr( $args['icon_length'] . 'px' ),
		pp_checkout_floating_button_cart_total_template( $args['button_length'] / 2 ),
		$args['preview'] ? esc_attr( 'relative' ) : esc_attr( 'fixed' )
	);
}

/**
 * Returns the HTML for display the cart contents count for the floating button.
 *
 * @param int $radius The radius of the floating button.
 */
function pp_checkout_floating_button_cart_total_template( $radius ) {
	$offset = ( $radius + $radius * cos( 45 * pi() / 180 ) ) . 'px';
	$count  = 1;
	if ( is_object( WC()->cart ) ) {
		$count = WC()->cart->cart_contents_count;
	}

	return sprintf(
		'<span 
			class="peachpay-floating-button-cart-total"
			style="position:absolute;top:%1$s;left:%1$s;border-radius:50px;font-size:13px;background-color:black;color:white;height:2em;min-width:2em;width:fit-content;padding:0 4px;display:flex;align-items:center;justify-content:center;transform:translate3d(-50%%,-50%%,0);"
		>
			%2$s
		</span>',
		esc_attr( $offset ),
		esc_html( $count )
	);
}

/**
 * Updates the floating button whenever items are added to the cart.
 *
 * @param array $fragments The HTML fragments that we can update.
 */
function pp_checkout_floating_button_cart_fragments( $fragments ) {
	if ( ! pp_should_display_public() ) {
		return $fragments;
	}

	ob_start();
	pp_checkout_floating_button();
	$fragments['#peachpay-floating-button-container'] = ob_get_clean();
	return $fragments;
}

/**
 * Render the PeachPay express checkout available gateway icons.
 *
 * @param array $args Arguments to define how to render the available gateway icons.
 */
function pp_checkout_available_gateway_icons_template( $args = array() ) {
	$defaults = array(
		'maximum_icons' => 4,
		'display'       => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$icons              = '';
	$icons_url          = '';
	$total_icons        = 0;
	$remaining_icons    = 0;
	$available_gateways = PeachPay_Payment::available_gateways();
	foreach ( $available_gateways as $gateway ) {
		if ( $gateway->is_available() ) {
			$current_icon     = $gateway->get_icon();
			$current_icon_url = $gateway->get_icon_url() . ' ';

			// If the gateway does not have an icon then just include it in the remaining.
			if ( ! $current_icon ) {
				++$remaining_icons;
				continue;
			}

			// Remove duplicates
			if ( strpos( $icons_url, $current_icon_url ) !== false ) {
				++$remaining_icons;
				continue;
			}

			// Only show maximum requested icons and then record remaining.
			if ( $total_icons >= $args['maximum_icons'] ) {
				++$remaining_icons;
				continue;
			}

			++$total_icons;
			$icons_url .= $current_icon_url;
			$icons     .= $current_icon;
		}
	}

	if ( $remaining_icons > 0 && '' !== $icons ) {
		$icons .= '<span class="peachpay-gateway-icons" style="gap:0.2rem;margin-left:0.4rem;text-align:center;justify-content:center;align-items:center;height:calc(1.4rem - 2px);width:calc(1.4rem - 2px);font-size:13px;">+' . $remaining_icons . '</span>';
	}

	return '<span class="available-payment-icons ' . $args['display'] . '" style="position:absolute;top:calc(100% + 8px);left:0;right:0;pointer-events:none;display:flex;flex-direction:row;color:#6d6d6d;justify-content:center;">' . $icons . '</span>';
}

/**
 * Generate a string of HTML attributes
 *
 * @param  array         $attr     Associative array of attribute names and values.
 * @param  callable|null $callback Callback function to escape values for HTML attributes.
 *                                 Defaults to `htmlspecialchars()`.
 * @return string  Returns a string of HTML attributes.
 */
function peachpay_html_build_attributes( array $attr, callable $callback = null ) {
	if ( ! count( $attr ) ) {
		return '';
	}

	$html = array_map(
		function ( $val, $key ) use ( $callback ) {
			if ( is_bool( $val ) ) {
				return ( $val ? $key : '' );
			} elseif ( isset( $val ) ) {
				if ( $val instanceof Closure ) {
					$val = $val();
				} elseif ( $val instanceof JsonSerializable ) {
					$val = wp_json_encode(
						$val->jsonSerialize(),
						( JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
					);
				} elseif ( is_callable( array( $val, 'toArray' ) ) ) {
					$val = $val->toArray();
				} elseif ( is_callable( array( $val, '__toString' ) ) ) {
					$val = strval( $val );
				}

				if ( is_array( $val ) ) {
					if ( function_exists( 'is_blank' ) ) {
						$filter = function ( $var ) {
							return ! is_blank( $var );
						};
					} else {
						$filter = function ( $var ) {
							return ! empty( $var ) || is_numeric( $var );
						};
					}
					$val = implode( ' ', array_filter( $val, $filter ) );
				}

				if ( is_callable( $callback ) ) {
					$val = call_user_func( $callback, $val );
				} elseif ( function_exists( 'esc_attr' ) ) {
					$val = esc_attr( $val );
				} else {
					$val = htmlspecialchars( $val, ENT_QUOTES );
				}

				if ( is_string( $val ) ) {
					return sprintf( '%1$s="%2$s"', $key, $val );
				}
			}
		},
		$attr,
		array_keys( $attr )
	);

	return implode( ' ', $html );
}

/**
 * Returns the PeachPay express checkout URL.
 */
function pp_checkout_permalink() {
	global $pp_checkout_page_id;

	if ( is_int( $pp_checkout_page_id ) ) {
		return get_permalink( $pp_checkout_page_id );
	}

	return '';
}

/**
 * The PeachPay version of `woocommerce_form_field` function.
 *
 * @param string $key Key.
 * @param mixed  $args Arguments.
 * @param string $value (default: null).
 * @return string
 */
function peachpay_form_field( $key, $args, $value = null ) {
	$defaults = array(
		'type'              => 'text',
		'label'             => '',
		'description'       => '',
		'placeholder'       => '',
		'maxlength'         => false,
		'minlength'         => false,
		'required'          => false,
		'autocomplete'      => false,
		'id'                => $key,
		'class'             => array(),
		'label_class'       => array(),
		'input_class'       => array(),
		'return'            => false,
		'options'           => array(),
		'custom_attributes' => array(),
		'validate'          => array(),
		'default'           => '',
		'autofocus'         => '',
		'priority'          => '',
	);

	$args = wp_parse_args( $args, $defaults );
	/**
	 * Allow field args to be filtered. PeachPay Express Checkout filter equivalent of 'woocommerce_form_field_args'.
	 */
	$args = apply_filters( 'pp_checkout_form_field_args', $args, $key, $value );

	if ( is_string( $args['class'] ) ) {
		$args['class'] = array( $args['class'] );
	}

	if ( $args['required'] ) {
		$args['class'][]                       = 'validate-required';
		$required                              = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'peachpay-for-woocommerce' ) . '">*</abbr>';
		$args['custom_attributes']['required'] = 'required';
	} else {
		$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'peachpay-for-woocommerce' ) . ')</span>';
	}

	if ( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if ( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling.
	$custom_attributes         = array();
	$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

	if ( $args['maxlength'] ) {
		$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
	}

	if ( $args['minlength'] ) {
		$args['custom_attributes']['minlength'] = absint( $args['minlength'] );
	}

	if ( ! empty( $args['autocomplete'] ) ) {
		$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
	}

	if ( true === $args['autofocus'] ) {
		$args['custom_attributes']['autofocus'] = 'autofocus';
	}

	if ( $args['description'] ) {
		$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
	}

	if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if ( ! empty( $args['validate'] ) ) {
		foreach ( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field           = '';
	$label_id        = $args['id'];
	$sort            = $args['priority'] ? $args['priority'] : '';
	$field_container = '<div class="flex col relative %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</div>';
	$countries       = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

	switch ( $args['type'] ) {
		case 'country':
			if ( 1 === count( $countries ) ) {

				$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

				$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

			} else {
				$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

				$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' ' . $data_label . '><option value="">' . esc_html__( 'Select a country / region&hellip;', 'peachpay-for-woocommerce' ) . '</option>';

				foreach ( $countries as $ckey => $cvalue ) {
					$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
				}

				$field .= '</select>';
			}

			break;
		case 'state':
			/* Get country this state field is representing */
			$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
			$states      = WC()->countries->get_states( $for_country );

			if ( is_array( $states ) && empty( $states ) ) {

				$field_container = '<div class="flex relative %1$s" id="%2$s" style="display: none">%3$s</div>';

				$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" />';

			} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
				$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '  ' . $data_label . '>
                    <option value="">' . esc_html__( 'Select an option&hellip;', 'peachpay-for-woocommerce' ) . '</option>';

				foreach ( $states as $ckey => $cvalue ) {
					$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
				}

				$field .= '</select>';

			} else {

				$field .= '<input type="text" class="text-input ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder=" " name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

			}

			break;
		case 'textarea':
			$field .= '<textarea name="' . esc_attr( $key ) . '" class="text-input ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

			break;
		case 'checkbox':
			$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
                    <input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

			break;
		case 'text':
		case 'password':
		case 'datetime':
		case 'datetime-local':
		case 'date':
		case 'month':
		case 'time':
		case 'week':
		case 'number':
		case 'email':
		case 'url':
		case 'tel':
			$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="text-input ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder=" "  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

			break;
		case 'hidden':
			$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

			break;
		case 'select':
			$field   = '';
			$options = '';

			if ( ! empty( $args['options'] ) ) {
				foreach ( $args['options'] as $option_key => $option_text ) {
					if ( '' === $option_key ) {
						// If we have a blank option, select2 needs a placeholder.
						if ( empty( $args['placeholder'] ) ) {
							$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'peachpay-for-woocommerce' );
						}
						$custom_attributes[] = 'data-allow_clear="true"';
					}
					$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
				}

				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '>
                        ' . $options . '
                    </select>';
			}

			break;
		case 'radio':
			$label_id .= '_' . current( array_keys( $args['options'] ) );

			if ( ! empty( $args['options'] ) ) {
				foreach ( $args['options'] as $option_key => $option_text ) {
					$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
					$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . esc_html( $option_text ) . '</label>';
				}

				$field = '<div class="flex radio-wrapper">' . $field . '</div>';
			}

			break;
	}

	if ( ! empty( $field ) ) {
		$field_html = $field;
		if ( $args['label'] && 'radio' !== $args['type'] && 'checkbox' !== $args['type'] && 'state' !== $args['type'] && 'country' !== $args['type'] && 'select' !== $args['type'] && 'textarea' !== $args['type'] ) {
			$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label no-wrap text-ellipsis w-80">' . wp_kses_post( $args['label'] ) . $required . '</label>';
		} elseif ( 'select' === $args['type'] || 'state' === $args['type'] ) {
			$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label pp-select-label no-wrap text-ellipsis w-70">' . wp_kses_post( $args['label'] ) . $required . '</label><div class="pp-form-chevron"><img src="' . peachpay_version_url( '/public/img/chevron-down-solid.svg', false ) . '"></div>';
		} elseif ( 'textarea' === $args['type'] ) {
			$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label pp-select-label no-wrap text-ellipsis w-70">' . wp_kses_post( $args['label'] ) . $required . '</label>';
		} elseif ( 'radio' === $args['type'] ) {
			$field_html = '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label radio no-wrap text-ellipsis w-100">' . wp_kses_post( $args['label'] ) . $required . '</label>' . $field_html;
		} elseif ( 'country' === $args['type'] ) {
			if ( 1 === count( $countries ) ) {
				$field_html  = '<span style="position:absolute;bottom:4px;left:5%;">' . $field_html . '</span>';
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label pp-select-label no-wrap text-ellipsis w-70">' . wp_kses_post( $args['label'] ) . $required . '</label>';
			} else {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="pp-form-label pp-select-label no-wrap text-ellipsis w-70">' . wp_kses_post( $args['label'] ) . $required . '</label><div class="pp-form-chevron"><img src="' . peachpay_version_url( '/public/img/chevron-down-solid.svg', false ) . '"></div>';
			}
		}

		$container_class = esc_attr( implode( ' ', $args['class'] ) );
		$container_id    = esc_attr( $args['id'] ) . '_field';
		$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
	}

	/**
	 * Filter by type. PeachPay Express Checkout filter equivalent of `woocommerce_form_field_{$type}`.
	 */
	$field = apply_filters( 'pp_checkout_form_field_' . $args['type'], $field, $key, $args, $value );

	/**
	 * General filter on form fields. PeachPay Express Checkout filter equivalent of `woocommerce_form_field`.
	 */
	$field = apply_filters( 'pp_checkout_form_field', $field, $key, $args, $value );

	if ( $args['return'] ) {
		return $field;
	} else {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $field;
	}
}
