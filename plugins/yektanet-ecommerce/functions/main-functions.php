<?php
defined( 'ABSPATH' ) || exit;

// this function use for process admin_setting_form
function yektanet_process_admin_setting_form() {
	$app_id = sanitize_text_field( $_POST['yektanet_app_id'] );
	if ( strlen( $app_id ) == 8 ) {
		update_option( 'yektanet_app_id', $app_id );
	} else {
		yektanet_send_error( 'app_id_count_error' );
	}

}

// this function use for sending error
function yektanet_send_error( $key ) {
	$error_text = yektanet_get_error_text_by_key( $key );
	echo "<h4 class='yektanet__setting__error__text'>$error_text</h4>";
}

// this function use for set error text by key
function yektanet_get_error_text_by_key( $key ) {
	$text = '';
	switch ( $key ) {
		case 'app_id_count_error':
			$text = __( 'length of app id must be 8 char.' );
	}

	return $text;
}

//this function add yektanet main script in site
function yektanet_add_script() {
	?>
    <script>
        !function (t, e, n) {
            const d = new Date();
            d.setTime(d.getTime() + (4 * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            t.yektanetAnalyticsObject = n
            t[n] = t[n] || function () {
                t[n].q.push(arguments)
            }
            t[n].q = t[n].q || [];
            var a = new Date
            var app_id = '<?php echo get_option( 'yektanet_app_id', true ); ?>';
            r = a.getFullYear().toString() + "0" + a.getMonth() + "0" + a.getDate() + "0" + a.getHours()
            c = e.getElementsByTagName("script")[0]
            s = e.createElement("script");
            s.id = "ua-script-" + app_id;
            s.dataset.analyticsobject = n;
            s.async = 1;
            s.type = "text/javascript";
            s.src = "https://cdn.yektanet.com/rg_woebegone/scripts_v4/" + app_id + "/complete.js?v=" + r
            c.parentNode.insertBefore(s, c)
        }(window, document, "yektanet");
    </script>
<?php }

// this function add yektanet custom style in site
function yektanet_add_custom_style_in_admin() {
	global $plugin_dir_path;
	wp_enqueue_style( 'yn-admin-styles', $plugin_dir_path . '/assets/css/styles.css' );
}

// this function use for check version of plugin
function yektanet_check_version() {
	if ( YEKTANET_ECOMMERCE_PLUGIN_VERSION !== get_option( 'YEKTANET_ECOMMERCE_PLUGIN_VERSION' ) ) {
		update_option( 'YEKTANET_ECOMMERCE_PLUGIN_VERSION', YEKTANET_ECOMMERCE_PLUGIN_VERSION );
	}
}

// this function use for analyze items added to cart
function yektanet_add_to_cart_handler( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	yektanet_send_products_data_to_ua( $product_id, 'add', $quantity );
}

// this function use for analyze orders
function yektanet_order_status_change( $order_id, $this_status_transition_from, $this_status_transition_to, $instance ) {
	$order = wc_get_order( $order_id );
	yektanet_send_order_data_to_ua( $order, $this_status_transition_from, $this_status_transition_to );
}

// this function use for analyze products viewed
function yektanet_detail_handler() {
	global $product;
	$id = $product->get_id();
	yektanet_send_products_data_to_ua( $id, 'detail' );
}

// this function use for get sku of product
function yektanet_get_product_sku( $product_id ) {
	$product = wc_get_product( $product_id );
	$sku     = $product->get_sku();
	if ( ! $sku ) {
		$sku = $product_id;
	}

	return $sku;
}

// this function ue for get all categories of product
function yektanet_get_product_category( $product_id ): string {
	$categories      = get_the_terms( $product_id, 'product_cat' )[0];
	$parentcats      = get_ancestors( $categories->term_id, 'product_cat' );
	$category_data   = [];
	$category_data[] = $categories->name;
	foreach ( $parentcats as $cat ) {
		$category_data[] = get_term_by( 'id', $cat, 'product_cat' )->name;
	}

	return join( ",", $category_data );
}

// this function use for get discount of product
function yektanet_get_product_discount( $product_id ) {
	$product  = wc_get_product( $product_id );
	$regular  = $product->get_regular_price();
	$sale     = $product->get_sale_price();
	$discount = 0;
	if ( $regular && $sale ) {
		$discount = $regular - $sale;
	}

	return $discount;
}

// this function send products analysis to UA
function yektanet_send_products_data_to_ua( $product_id, $type, $quantity = 0 ) {
	global $ua_url;
	$product     = wc_get_product( $product_id );
	$utm_data = sanitize_text_field($_COOKIE['analytics_campaign']) ?: sanitize_text_field($_COOKIE['_ynsrc']);
	$utm_data = json_decode( stripslashes( $utm_data ), true );

	$params      = $_SERVER['QUERY_STRING'];
	$params      = explode( '&', $params );
	$params_data = [];
	foreach ( $params as $par ) {
		$par_data = explode( '=', $par );
		if ( count( $par_data ) > 1 ) {
			$params_data[ $par_data[0] ] = $par_data[1];
		}
	}
	$data = array(
		'acm' => $type,
		'aa'  => 'product',
		'aca' => $product->get_title(),
		'acb' => yektanet_get_product_sku( $product_id ),
		'acc' => yektanet_get_product_category( $product_id ),
		'acd' => $quantity,
		'ace' => $product->get_price(),
		'ach' => yektanet_get_product_discount( $product_id ),
		'aco' => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),
		'acq' => $product->is_in_stock(),
		'ac'  => get_permalink( $product->get_id() ),
		'ae'  => json_encode( $params_data ),
		'ad'  => get_site_url(),
		'ba'  => sanitize_text_field($_COOKIE['_yngt']),
		'as'  => $product->get_title(),
		'aef' => get_option( 'yektanet_app_id', true ),
		'aaa' => $utm_data['source'],
		'aab' => $utm_data['medium'],
		'aac' => array_key_exists( 'content', $utm_data ) ? $utm_data['content'] : '',
		'aad' => array_key_exists( 'campaign', $utm_data ) ? $utm_data['campaign'] : '',
		'aae' => array_key_exists( 'term', $utm_data ) ? $utm_data['term'] : '',
		'abi' => array_key_exists( 'yn', $utm_data ) ? $utm_data['yn'] : '',
		'uys' => array_key_exists( 'yn_source', $utm_data ) ? $utm_data['yn_source'] : '',
		'uyd' => array_key_exists( 'yn_data', $utm_data ) ? $utm_data['yn_data'] : '',
		'ai'  => sanitize_text_field($_COOKIE['analytics_session_token']),
		'af'  => wp_get_referer(),
		'ag'  => explode( '/', wp_get_referer() )[2],
	);
	wp_remote_post( $ua_url . http_build_query( $data ), yektanet_set_args_to_send_request() );
}

// this function send orders analysis to UA
function yektanet_send_order_data_to_ua( $order, $previous_status, $new_status ) {
	global $ua_url;
	$items_data                    = array();
	$items_data['previous_status'] = $previous_status;
	$items_data['status']          = $new_status;
	$utm_data = sanitize_text_field($_COOKIE['analytics_campaign']) ?: sanitize_text_field($_COOKIE['_ynsrc']);
	$utm_data = json_decode( stripslashes( $utm_data ), true );

	$counter = 0;
	foreach ( $order->get_items() as $item_id => $item ) {
		$product         = wc_get_product( $item->get_product_id() );
		$categories      = get_the_terms( $product->get_id(), 'product_cat' );
		$categories_data = array();
		foreach ( $categories as $category ) {
			$category_data         = array();
			$category_data['name'] = $category->name;
			$category_data['id']   = $category->term_id;
			$parentcats            = get_ancestors( $category->term_id, 'product_cat' );
			foreach ( $parentcats as $cat ) {
				$category_data['name'] = get_term_by( 'id', $cat, 'product_cat' )->name;
				$category_data['id']   = $cat;
			}
			$categories_data[] = $category_data;
		}
		$item_data = array(
			'price'      => $product->get_price(),
			'quantity'   => $item->get_quantity(),
			'product_id' => $product->get_id(),
			'sku'        => yektanet_get_product_sku($product->get_id()),
			'total'      => $item->get_total(),
			'url'        => get_permalink( $product->get_id() ),
			'title'      => $product->get_title(),
			'discount'   => yektanet_get_product_discount( $item->get_product_id() )
		);
		$image     = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
		if ( $image ) {
			$item_data['image'] = $image;
		}

		$item_data['categories']         = $categories_data;
		$items_data['items'][ $counter ] = $item_data;
		$counter ++;
	}

	$data = array(
		'acm' => 'purchase',
		'aa'  => 'product',
		'aef' => get_option( 'yektanet_app_id', true ),
		'acb' => $order->get_id(),
		'ad'  => get_site_url(),
		'ac'  => $order->get_checkout_order_received_url(),
		'ace' => $order->get_total(),
		'ba'  => sanitize_text_field($_COOKIE['_yngt']),
		'ai'  => sanitize_text_field($_COOKIE['analytics_session_token']),
		'aaa' => $utm_data['source'],
		'aab' => $utm_data['medium'],
		'ip'  => $order->get_customer_ip_address(),
		'abg' => $order->get_customer_user_agent(),
		'acs' => json_encode( $items_data ),
		'acn' => $order->get_id(),
		'acf' => $order->get_currency(),
		'ach' => $order->get_total_discount(),
	);
	wp_remote_post( $ua_url . http_build_query( $data ), yektanet_set_args_to_send_request() );
}

// this function send new product data after update in admin panel to yektanet
function yektanet_product_update( $product_id ) {
	global $product_update_api_url;
	$product     = wc_get_product( $product_id );
	$categories      = get_the_terms( $product->get_id(), 'product_cat' )[0];
	$parentcats      = get_ancestors( $categories->term_id, 'product_cat' );
	$category_data   = [];
	$category_data[] = $categories->name;
	foreach ( $parentcats as $cat ) {
		$category_data[] = get_term_by( 'id', $cat, 'product_cat' )->name;
	}
	$data = array(
		'appId'              => get_option( 'yektanet_app_id', true ),
		'productSku'         => yektanet_get_product_sku( $product_id ),
		'host'               => get_site_url(),
		'url'                => get_permalink( $product_id ),
		'productTitle'       => $product->get_title(),
		'productImage'       => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),
		'productCategory'    => array_reverse( $category_data ),
		'productDiscount'    => yektanet_get_product_discount( $product_id ),
		'productPrice'       => $product->get_regular_price(),
		'productCurrency'    => get_woocommerce_currency(),
		'productIsAvailable' => $product->is_in_stock(),
	);
	$args = array(
		'headers' => array(
			'Content-type' => 'text/plain;charset=UTF-8'
		),
		'timeout' => 3,
		'method'  => 'PUT',
		'body'    => json_encode( $data )
	);
	wp_remote_request( $product_update_api_url, $args );
}

// this function set args to send request
function yektanet_set_args_to_send_request(): array {
	return array(
		'headers' => array(
			'Content-type' => 'text/plain;charset=UTF-8',
			'user-agent'   => $_SERVER['HTTP_USER_AGENT'],
			'origin'       => get_site_url(),
			'referer'      => wp_get_referer(),
		),
		'timeout' => 1,
	);
}