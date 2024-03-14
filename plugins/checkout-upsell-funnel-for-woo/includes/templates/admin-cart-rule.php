<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$item_index          = $item_index ?? '';
$item_index          = $item_index ?: '{item_index}';
$index               = $index ?? '';
$index               = $index ?: '{index}';
$prefix              = $prefix ?? '';
$prefix              = $prefix ?: '{prefix}';
$params              = isset($params) && is_array($params) ? $params : array();
$type                = $type ?? 'cart_item_include';
$woo_currency_symbol = $woo_currency_symbol ?? get_woocommerce_currency_symbol();
if ( empty( $woo_countries ) ) {
	$woo_countries = new WC_Countries();
	$woo_countries = $woo_countries->__get( 'countries' );
}
$conditions                      = array(
	'Cart Items'       => array(
		'cart_item_include' => esc_html__( 'Include Cart Items', 'checkout-upsell-funnel-for-woo' ),
		'cart_item_exclude' => esc_html__( 'Exclude Cart Items', 'checkout-upsell-funnel-for-woo' ),
		'cart_cats_include' => esc_html__( 'Include Cart Items by Categories', 'checkout-upsell-funnel-for-woo' ),
		'cart_cats_exclude' => esc_html__( 'Exclude Cart Items by Categories', 'checkout-upsell-funnel-for-woo' ),
	),
	'Applied Coupon'   => array(
		'cart_coupon_include' => esc_html__( 'Include Coupon', 'checkout-upsell-funnel-for-woo' ),
		'cart_coupon_exclude' => esc_html__( 'Exclude Coupon', 'checkout-upsell-funnel-for-woo' ),
	),
	'Billing Address'  => array(
		'billing_countries_include' => esc_html__( 'Include Billing Countries', 'checkout-upsell-funnel-for-woo' ),
		'billing_countries_exclude' => esc_html__( 'Exclude Billing Countries', 'checkout-upsell-funnel-for-woo' ),
	),
	'Shipping Address' => array(
		'shipping_countries_include' => esc_html__( 'Include Shipping Countries', 'checkout-upsell-funnel-for-woo' ),
		'shipping_countries_exclude' => esc_html__( 'Exclude Shipping Countries', 'checkout-upsell-funnel-for-woo' ),
	),
);
$cart_item_include               = isset($cart_item_include) && is_array($cart_item_include) ?$cart_item_include : array();
$cart_item_exclude               = isset($cart_item_exclude) && is_array($cart_item_exclude) ? $cart_item_exclude : array();
$cart_cats_include               = isset($cart_cats_include) && is_array($cart_cats_include) ?$cart_cats_include : array();
$cart_cats_exclude               = isset($cart_cats_exclude) && is_array($cart_cats_exclude) ? $cart_cats_exclude : array();
$cart_coupon_include             = isset($cart_coupon_include) && is_array($cart_coupon_include) ? $cart_coupon_include : array();
$cart_coupon_exclude             = isset($cart_coupon_exclude) && is_array($cart_coupon_exclude) ? $cart_coupon_exclude : array();
$billing_countries_include       = isset($billing_countries_include) && is_array($billing_countries_include) ? $billing_countries_include : array();
$billing_countries_exclude       = isset($billing_countries_exclude) && is_array($billing_countries_exclude) ? $billing_countries_exclude : array();
$shipping_countries_include      = isset($shipping_countries_include) && is_array($shipping_countries_include) ? $shipping_countries_include : array();
$shipping_countries_exclude      = isset($shipping_countries_exclude) && is_array($shipping_countries_exclude) ? $shipping_countries_exclude : array();
$name_condition_type             = $prefix . 'cart_rule_type[' . $index . '][]';
$name_cart_subtotal_min          = $prefix . 'cart_subtotal[' . $index . '][min]';
$name_cart_subtotal_max          = $prefix . 'cart_subtotal[' . $index . '][max]';
$name_cart_total_min             = $prefix . 'cart_total[' . $index . '][min]';
$name_cart_total_max             = $prefix . 'cart_total[' . $index . '][max]';
$name_cart_item_include          = $prefix . 'cart_item_include[' . $index . '][]';
$name_cart_item_exclude          = $prefix . 'cart_item_exclude[' . $index . '][]';
$name_cart_cats_include          = $prefix . 'cart_cats_include[' . $index . '][]';
$name_cart_cats_exclude          = $prefix . 'cart_cats_exclude[' . $index . '][]';
$name_cart_coupon_include        = $prefix . 'cart_coupon_include[' . $index . '][]';
$name_cart_coupon_exclude        = $prefix . 'cart_coupon_exclude[' . $index . '][]';
$name_billing_countries_include  = $prefix . 'billing_countries_include[' . $index . '][]';
$name_billing_countries_exclude  = $prefix . 'billing_countries_exclude[' . $index . '][]';
$name_shipping_countries_include = $prefix . 'shipping_countries_include[' . $index . '][]';
$name_shipping_countries_exclude = $prefix . 'shipping_countries_exclude[' . $index . '][]';
?>
<div class="vi-ui placeholder segment vi-wcuf-condition-wrap-wrap vi-wcuf-cart-condition-wrap-wrap">
    <div class="fields">
        <div class="four wide field">
            <select class="vi-ui fluid dropdown vi-wcuf-condition-type vi-wcuf-cart-condition-cart_rule_type"
                    data-wcuf_name="<?php echo esc_attr( $name_condition_type ) ?>"
                    data-wcuf_name_default="{prefix_default}cart_rule_type[{index_default}][]"
                    data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                    name="<?php echo esc_attr( $name_condition_type ) ?>">
                <optgroup disabled label="<?php esc_attr_e( 'Cart Total', 'checkout-upsell-funnel-for-woo' ); ?>">
                    <option disabled><?php esc_html_e( 'Cart Subtotal( total of products)', 'checkout-upsell-funnel-for-woo' ); ?></option>
                    <option disabled><?php esc_html_e( 'Cart Total', 'checkout-upsell-funnel-for-woo' ); ?></option>
                </optgroup>
				<?php
				foreach ( $conditions as $condition_group => $condition_arg ) {
					?>
                    <optgroup label="<?php esc_attr_e( $condition_group, 'checkout-upsell-funnel-for-woo' ) ?>">
						<?php
						foreach ( $condition_arg as $condition_k => $condition_v ) {
							$check = '';
							if ( $type == $condition_k ) {
								$check = 'selected';
							}
							echo sprintf( '<option value="%s" %s >%s</option>', $condition_k, $check, esc_html( $condition_v ) );
						}
						?>
                    </optgroup>
					<?php
				}
				?>
            </select>
        </div>
        <div class="thirteen wide field vi-wcuf-condition-value-wrap-wrap">
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_item_include-wrap <?php echo esc_attr( $type === 'cart_item_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-product vi-wcuf-cart-condition-cart_item_include vi-wcuf-condition-value"
                        data-type_select2="product"
                        name="<?php echo esc_attr( $type === 'cart_item_include' ? $name_cart_item_include : '' ); ?>"
                        data-wcuf_name_default="{prefix_default}cart_item_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_item_include ) ?>" multiple>
					<?php
					if ( $cart_item_include && is_array( $cart_item_include ) && count( $cart_item_include ) ) {
						foreach ( $cart_item_include as $pd_id ) {
							$product = wc_get_product( $pd_id );
							if ( $product ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $pd_id, $product->get_formatted_name() );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_item_exclude-wrap <?php echo esc_attr( $type === 'cart_item_exclude' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'cart_item_exclude' ? $name_cart_item_exclude : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_item_exclude ) ?>"
                        data-wcuf_name_default="{prefix_default}cart_item_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="product"
                        class="vi-wcuf-search-select2 vi-wcuf-search-product vi-wcuf-cart-condition-cart_item_exclude vi-wcuf-condition-value" multiple>
					<?php
					if ( $cart_item_exclude && is_array( $cart_item_exclude ) && count( $cart_item_exclude ) ) {
						foreach ( $cart_item_exclude as $pd_id ) {
							$product = wc_get_product( $pd_id );
							if ( $product ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $pd_id, $product->get_formatted_name() );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_cats_include-wrap <?php echo esc_attr( $type === 'cart_cats_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'cart_cats_include' ? $name_cart_cats_include : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_cats_include ) ?>"
                        data-wcuf_name_default="{prefix_default}cart_cats_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="category"
                        class="vi-wcuf-search-select2 vi-wcuf-search-category vi-wcuf-cart-condition-cart_cats_include vi-wcuf-condition-value" multiple>
					<?php
					if ( $cart_cats_include && is_array( $cart_cats_include ) && count( $cart_cats_include ) ) {
						foreach ( $cart_cats_include as $cart_id ) {
							$term = get_term( $cart_id );
							if ( $term ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $cart_id, $term->name );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_cats_exclude-wrap <?php echo esc_attr( $type === 'cart_cats_exclude' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'cart_cats_exclude' ? $name_cart_cats_exclude : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_cats_exclude ) ?>"
                        data-wcuf_name_default="{prefix_default}cart_cats_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="category"
                        class="vi-wcuf-search-select2 vi-wcuf-search-category vi-wcuf-cart-condition-cart_cats_exclude vi-wcuf-condition-value" multiple>
					<?php
					if ( $cart_cats_exclude && is_array( $cart_cats_exclude ) && count( $cart_cats_exclude ) ) {
						foreach ( $cart_cats_exclude as $cart_id ) {
							$term = get_term( $cart_id );
							if ( $term ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $cart_id, $term->name );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_coupon_include-wrap <?php echo esc_attr( $type === 'cart_coupon_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'cart_coupon_include' ? $name_cart_coupon_include : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_coupon_include ) ?>"
                        data-wcuf_name_default="{prefix_default}cart_coupon_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="coupon"
                        class="vi-wcuf-search-select2 vi-wcuf-search-coupon vi-wcuf-cart-condition-cart_coupon_include vi-wcuf-condition-value" multiple>
					<?php
					if ( $cart_coupon_include && is_array( $cart_coupon_include ) && count( $cart_coupon_include ) ) {
						foreach ( $cart_coupon_include as $coupon_code ) {
							echo sprintf( '<option value="%s" selected>%s</option>', $coupon_code, esc_html( strtoupper( $coupon_code ) ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-cart_coupon_exclude-wrap <?php echo esc_attr( $type === 'cart_coupon_exclude' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'cart_coupon_exclude' ? $name_cart_coupon_exclude : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cart_coupon_exclude ) ?>"
                        data-wcuf_name_default="{prefix_default}cart_coupon_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="coupon"
                        class="vi-wcuf-search-select2 vi-wcuf-search-coupon vi-wcuf-cart-condition-cart_coupon_exclude vi-wcuf-condition-value" multiple>
					<?php
					if ( $cart_coupon_exclude && is_array( $cart_coupon_exclude ) && count( $cart_coupon_exclude ) ) {
						foreach ( $cart_coupon_exclude as $coupon_code ) {
							echo sprintf( '<option value="%s" selected>%s</option>', $coupon_code, esc_html( strtoupper( $coupon_code ) ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-billing_countries_include-wrap <?php echo esc_attr( $type === 'billing_countries_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'billing_countries_include' ? $name_billing_countries_include : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_billing_countries_include ) ?>"
                        data-wcuf_name_default="{prefix_default}billing_countries_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="country"
                        class="vi-wcuf-search-select2 vi-wcuf-search-country vi-wcuf-cart-condition-billing_countries_include vi-wcuf-condition-value" multiple>
					<?php
					if ( $woo_countries && is_array( $woo_countries ) && count( $woo_countries ) ) {
						foreach ( $woo_countries as $country_id => $country_name ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $country_id, esc_attr( in_array( $country_id, $billing_countries_include ) ? 'selected' : '' ), esc_html( $country_name ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-billing_countries_exclude-wrap <?php echo esc_attr( $type === 'billing_countries_exclude' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'billing_countries_exclude' ? $name_billing_countries_exclude : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_billing_countries_exclude ) ?>"
                        data-wcuf_name_default="{prefix_default}billing_countries_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="country"
                        class="vi-wcuf-search-select2 vi-wcuf-search-country vi-wcuf-cart-condition-billing_countries_exclude vi-wcuf-condition-value" multiple>
					<?php
					if ( $woo_countries && is_array( $woo_countries ) && count( $woo_countries ) ) {
						foreach ( $woo_countries as $country_id => $country_name ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $country_id, selected( in_array( $country_id, $billing_countries_exclude ), true ), esc_html( $country_name ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-shipping_countries_include-wrap <?php echo esc_attr( $type === 'shipping_countries_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'shipping_countries_include' ? $name_shipping_countries_include : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_shipping_countries_include ) ?>"
                        data-wcuf_name_default="{prefix_default}shipping_countries_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="country"
                        class="vi-wcuf-search-select2 vi-wcuf-search-country vi-wcuf-cart-condition-shipping_countries_include vi-wcuf-condition-value" multiple>
					<?php
					if ( $woo_countries && is_array( $woo_countries ) && count( $woo_countries ) ) {
						foreach ( $woo_countries as $country_id => $country_name ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $country_id, selected( in_array( $country_id, $shipping_countries_include ), true ), esc_html( $country_name ) );
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-cart-condition-wrap vi-wcuf-condition-shipping_countries_exclude-wrap <?php echo esc_attr( $type === 'shipping_countries_exclude' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select name="<?php echo esc_attr( $type === 'shipping_countries_exclude' ? $name_shipping_countries_exclude : '' ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_shipping_countries_exclude ) ?>"
                        data-wcuf_name_default="{prefix_default}shipping_countries_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-type_select2="country"
                        class="vi-wcuf-search-select2 vi-wcuf-search-country vi-wcuf-cart-condition-shipping_countries_exclude vi-wcuf-condition-value" multiple>
					<?php
					if ( $woo_countries && is_array( $woo_countries ) && count( $woo_countries ) ) {
						foreach ( $woo_countries as $country_id => $country_name ) {
							echo sprintf( '<option value="%s" %s>%s</option>', $country_id, selected( in_array( $country_id, $shipping_countries_exclude ), true ), esc_html( $country_name ) );
						}
					}
					?>
                </select>
            </div>
        </div>
        <div class="field vi-wcuf-revmove-condition-btn-wrap">
             <span class="vi-wcuf-revmove-condition-btn vi-wcuf-pd_cart_rule-revmove-condition"
                   data-tooltip="<?php esc_html_e( 'Remove', 'checkout-upsell-funnel-for-woo' ); ?>">
                 <i class="times icon"></i>
             </span>
        </div>
    </div>
</div>
