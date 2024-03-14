<?php
defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id );
if ( ! $order instanceof WC_Order ) {
	return '';
}

$xlwcty_fb_gb_event_fire = $order->get_meta( 'xlwcty_fb_gb_event_fire', true );
if ( 'yes' === $xlwcty_fb_gb_event_fire ) {
	return '';
}

$pixel_id            = $this->facebook_pixel_enabled();
$google_analytics_id = $this->google_analytics_enabled();
$get_tracking_codes  = ! empty( $google_analytics_id ) ? explode( ",", $google_analytics_id ) : [];

$facebook_tracking_event                   = XLWCTY_Core()->data->get_option( 'enable_fb_pageview_event' );
$facebook_purchase_event                   = XLWCTY_Core()->data->get_option( 'enable_fb_purchase_event' );
$facebook_purchase_event_conversion        = XLWCTY_Core()->data->get_option( 'enable_fb_purchase_event_conversion_val' );
$facebook_purchase_advanced_matching_event = XLWCTY_Core()->data->get_option( 'enable_fb_advanced_matching_event' );
$ga4_tracking_fired                        = false; // Flag to track if GA4 tracking event is fired
if ( ! empty( $google_analytics_id ) && ! $ga4_tracking_fired ) {

	$items          = [];
	$transaction_id = $order_id;
	$value          = $order->get_total();
	$tax            = $order->get_total_tax();
	$shipping       = $order->get_shipping_total();
	$currency       = $order->get_currency();
	$coupons        = $order->get_coupon_codes();
	$coupon_codes   = implode( ', ', $coupons );
	// Prepare the items array
	foreach ( $order->get_items() as $item ) {
		$product    = $item->get_product();
		$item_id    = $product->get_id();
		$item_name  = $product->get_name();
		$item_price = $product->get_price();
		$quantity   = $item->get_quantity();
		// Calculate the discounted amount
		$regular_price     = $product->get_regular_price();
		$sale_price        = $product->get_sale_price();
		$sku               = $product->get_sku();
		$discounted_amount = 0;
		if ( $regular_price && $sale_price ) {
			$discounted_amount = ( floatval( $regular_price ) - floatval( $sale_price ) ) * intval( $quantity );
		}

		$category_names = [];
		$category       = $product->get_category_ids();
		if ( is_array( $category ) && count( $category ) > 0 ) {
			foreach ( $category as $cat_id ) {
				$cat_term = get_term_by( 'id', $cat_id, 'product_cat' );
				if ( $cat_term ) {
					$category_names[] = $cat_term->name;
				}
			}
		}

		$item_categories = implode( ', ', $category_names );

		$items[] = [
			'item_id'       => $item_id,
			'item_name'     => $item_name,
			'sku'           => $sku,
			'discount'      => $discounted_amount,
			'item_category' => $item_categories,
			'price'         => $item_price,
			'quantity'      => $quantity,
		];
	}
	?>
    <script>
		<?php
		foreach ( $get_tracking_codes as $tracking_code ) {
			echo "gtag('config', '" . esc_js( trim( $tracking_code ) ) . "');";
		}
		$ga4_tracking_fired = true; // Set the flag to true after firing the event
		?>
        gtag('event', 'purchase', {
            transaction_id: '<?php echo $transaction_id; ?>',
            value: <?php echo $value; ?>,
            tax: <?php echo $tax; ?>,
            shipping: <?php echo $shipping; ?>,
            currency: '<?php echo $currency; ?>',
            coupon: '<?php echo $coupon_codes; ?>',
            items: <?php echo json_encode( $items ); ?>,
        });
    </script>
	<?php
}

if ( $pixel_id > 0 || ! empty( $get_tracking_codes[0] ) ) {
	$items     = $order->get_items( 'line_item' );
	$products  = array();
	$gproducts = array();

	$order_total          = $order->get_total();
	$order_shipping_total = $order->get_shipping_total();
	$order_tax            = $order->get_total_tax();

	foreach ( $items as $item ) {
		$pid        = $item->get_product_id();
		$product    = wc_get_product( $pid );
		$item_price = 0;
		if ( $product instanceof WC_product ) {
			$item_price    = $product->get_price();
			$category      = $product->get_category_ids();
			$category_name = '';
			if ( is_array( $category ) && count( $category ) > 0 ) {
				$category_id = $category[0];
				if ( is_numeric( $category_id ) && $category_id > 0 ) {
					$cat_term = get_term_by( 'id', $category_id, 'product_cat' );
					if ( $cat_term ) {
						$category_name = $cat_term->name;
					}
				}
			}
			$products[ $pid ]  = array(
				'name'       => $product->get_title(),
				'category'   => $category_name,
				'id'         => $pid,
				'quantity'   => $item->get_quantity(),
				'item_price' => $item_price,
			);
			$gproducts[ $pid ] = array(
				'id'       => $pid,
				'sku'      => $product->get_sku(),
				'category' => $category_name,
				'name'     => $product->get_title(),
				'quantity' => $item->get_quantity(),
				'price'    => $item_price,
			);
		}
	}

	if ( is_array( $products ) && count( $products ) === 0 ) {
		return '';
	}

	if ( $pixel_id > 0 ) {
		if ( 'on' === $facebook_purchase_advanced_matching_event ) {
			$fb_pa         = array();
			$billing_email = XLWCTY_Compatibility::get_order_data( $order, 'billing_email' );
			if ( ! empty( $billing_email ) ) {
				$fb_pa['em'] = $billing_email;
			}
			$billing_phone = XLWCTY_Compatibility::get_order_data( $order, 'billing_phone' );
			if ( ! empty( $billing_phone ) ) {
				$fb_pa['ph'] = $billing_phone;
			}
			$shipping_first_name = XLWCTY_Compatibility::get_order_data( $order, 'shipping_first_name' );
			if ( ! empty( $shipping_first_name ) ) {
				$fb_pa['fn'] = $shipping_first_name;
			}
			$shipping_last_name = XLWCTY_Compatibility::get_order_data( $order, 'shipping_last_name' );
			if ( ! empty( $shipping_last_name ) ) {
				$fb_pa['ln'] = $shipping_last_name;
			}
			$shipping_city = XLWCTY_Compatibility::get_order_data( $order, 'shipping_city' );
			if ( ! empty( $shipping_city ) ) {
				$fb_pa['ct'] = $shipping_city;
			}
			$shipping_state = XLWCTY_Compatibility::get_order_data( $order, 'shipping_state' );
			if ( ! empty( $shipping_state ) ) {
				$fb_pa['st'] = $shipping_state;
			}
			$shipping_postcode = XLWCTY_Compatibility::get_order_data( $order, 'shipping_postcode' );
			if ( ! empty( $shipping_postcode ) ) {
				$fb_pa['zp'] = $shipping_postcode;
			}
		}
		$order->update_meta_data( 'xlwcty_fb_gb_event_fire', 'yes' );
		$order->save();
	}
}
?>
    <script>
        var xlwcty_fab_ecom = {
            'pixel_id': '<?php echo isset( $pixel_id ) ? $pixel_id : ''; ?>',
            'fb_pa_count': '<?php echo isset( $fb_pa ) ? count( $fb_pa ) : ''; ?>',
            'fb_pa_data': '<?php echo isset( $fb_pa ) ? wp_json_encode( $fb_pa ) : ''; ?>',
            'facebook_tracking_event': '<?php echo isset( $facebook_tracking_event ) ? $facebook_tracking_event : ''; ?>',
            'facebook_purchase_event': '<?php echo isset( $facebook_purchase_event ) ? $facebook_purchase_event : ''; ?>',
            'facebook_purchase_advanced_matching_event': '<?php echo isset( $facebook_purchase_advanced_matching_event ) ? $facebook_purchase_advanced_matching_event : ''; ?>',
            'facebook_purchase_event_conversion': '<?php echo isset( $facebook_purchase_event_conversion ) ? $facebook_purchase_event_conversion : ''; ?>',
            'products': '<?php echo isset( $products ) ? wp_json_encode( $products ) : ''; ?>',
            'order_id': '<?php echo isset( $order_id ) ? $order_id : ''; ?>',
            'order_total': '<?php echo isset( $order_total ) ? $order_total : ''; ?>',
            'currency': '<?php echo get_woocommerce_currency(); ?>',
            'shipping_total': '<?php echo isset( $order_shipping_total ) ? $order_shipping_total : ''; ?>',
            'order_tax': '<?php echo isset( $order_tax ) ? $order_tax : ''; ?>',
            'affiliation': '<?php bloginfo( 'name' ); ?>',
            'gproducts': '<?php echo isset( $gproducts ) ? wp_json_encode( $gproducts ) : ''; ?>',
        };
    </script>
<?php
