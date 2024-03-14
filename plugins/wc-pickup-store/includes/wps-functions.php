<?php
/**
 * Store table row layout
 */
function wps_store_row_layout() {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$post_data = wps_validate_ajax_request();
	$checkout_notification = WPS()->checkout_notification;
	$show_store_details = (isset(WPS()->hide_store_details) && WPS()->hide_store_details == 'yes') ? false : true;

	$store_default = get_theme_mod('wps_store_default');

	if ( is_wps_chosen_shipping_method() && is_checkout() ) :
		// If is different to the shipping label 
		if ( $store_default != 1 ) {
			$store_default = is_user_logged_in() ? apply_filters('wps_first_store', get_user_meta($user_id, '_shipping_pickup_stores', true)) : $store_default;
		}
		
		// If is changing stores on the Checkout 
		if ( !empty($post_data['shipping_pickup_stores']) ) {
			$store_default = $post_data['shipping_pickup_stores'];
		}
		$ship_to_store = !empty($post_data['shipping_by_store']) ? $post_data['shipping_by_store'] : '';

		if ( wps_check_countries_count() ) {
			$country_to_filter = ( isset( $post_data['ship_to_different_address'] ) ) ? $post_data['shipping_country'] : ( isset( $post_data['billing_country'] ) ? $post_data['billing_country'] : wps_get_wc_default_country() );
			$get_stores = wps_stores_filtering_by_country( $country_to_filter );
		} else {
			$get_stores = wps_store_get_store_admin(true);
		}
		?>
		<tr class="shipping-pickup-store">
			<?php if (count($get_stores) > 0) : ?>
				<?php if (WPS()->enable_store_select == 'yes') : ?>
					<th><strong><?php echo apply_filters('wps_store_checkout_label', WPS()->title); ?></strong></th>
					<td>
						<?php ob_start(); ?>
						<select id="shipping-pickup-store-select" class="<?= (WPS()->costs_per_store == 'yes') ? 'wps-costs-per-store' : 'wps-no-costs' ?>" name="shipping_pickup_stores" data-store="<?= $store_default ?>">
							<?php if ( empty($store_default) || $store_default == 1 ) : ?>
								<option value=""><?= apply_filters( 'wps_store_select_first_option', __( 'Select a store', 'wc-pickup-store' ) ) ?></option>
							<?php endif; ?>
							<?php
							foreach ($get_stores as $post_id => $store) {
								$store_shipping_cost = wps_get_post_meta($post_id, 'store_shipping_cost');
								$store_country = wps_get_post_meta($post_id, 'store_country');
								$cost = WPS()->wps_get_calculated_costs($store_shipping_cost);
								$formatted_title = ($cost > 0) ? $store . ': ' . wc_price($cost) : $store;
								$ship_to_store = ($store_default == $store) ? $cost : $ship_to_store;
								?>
								<option data-cost="<?= $cost ?>" data-id="<?= $post_id ?>" data-country="<?= $store_country ?>" value="<?= $store; ?>" <?php selected($store, $store_default); ?>><?= $formatted_title; ?></option>
								<?php
							}
							?>
						</select>
						<?php
							/** @version 1.6.1 */
							echo apply_filters('wps_stores_dropdown', ob_get_clean(), $get_stores, $store_default);
						?>
						<input type="hidden" id="store_shipping_cost" name="shipping_by_store" value="<?= $ship_to_store ?>">
						<?php if($ship_to_store > 0 && !isset($post_data['shipping_pickup_stores'])) : ?>
							<script type="text/javascript">
								jQuery('body').trigger('update_checkout');
							</script>
						<?php endif; ?>
					</td>
				<?php elseif (!empty($store_default) && in_array($store_default, $get_stores)) : ?>
					<th><strong><?php echo apply_filters('wps_store_checkout_label', WPS()->title); ?></strong></th>
					<td>
						<strong><?= $store_default ?></strong>
						<input type="hidden" name="shipping_pickup_stores" value="<?= $store_default; ?>">
					</td>
				<?php else: ?>
					<td colspan="2">
						<span class="no-store-default"><?= wps_no_stores_availables_message(); ?></span>
					</td>
				<?php endif; ?>
			<?php else : ?>
				<td colspan="2">
					<span class="no-store-available"><?= wps_no_stores_availables_message(); ?></span>
				</td>
			<?php endif; ?>
		</tr>
		
		<?php if ($show_store_details || !empty($checkout_notification)) : ?>
			<tr class="shipping-pickup-store">
				<td colspan="2">
					<?php  if ($show_store_details) : ?>
						<div class="store-template">
							<?php
								$template_file = wps_locate_template('selected-store-details.php');
								include $template_file;
							?>
						</div>
					<?php endif; ?>

					<?php if(!empty($checkout_notification)) : ?>
						<span class="store-message"><?= sanitize_textarea_field($checkout_notification) ?></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
		
		<?php
	endif;
}
add_action('woocommerce_review_order_after_shipping', 'wps_store_row_layout');

/**
 * Order detail styles
 */
function wps_store_style() {
	?>
	<style type="text/css">
		.shipping-pickup-store td .title {
			float: left;
			line-height: 30px;
		}
		.shipping-pickup-store td span.text {
			float: right;
		}
		.shipping-pickup-store td span.description {
			clear: both;
		}
		.shipping-pickup-store td > span:not([class*="select"]) {
			display: block;
			font-size: 14px;
			font-weight: normal;
			line-height: 1.4;
			margin-bottom: 0;
			padding: 6px 0;
			text-align: justify;
		}
		.shipping-pickup-store td #shipping-pickup-store-select {
			width: 100%;
		}
		.wps-store-details iframe {
			width: 100%;
		}
	</style>
	<?php
}
add_action('wp_head', 'wps_store_style');

/**
 * Remove cart shipping label
 * 
 * @version 1.7.0
 * @since 1.5.24
 */
function wps_shipping_method_label( $label, $method ) {
	if ( $method->get_method_id() == 'wc_pickup_store' ) {
		$label = apply_filters( 'wps_shipping_method_label', $method->get_label() );
	}

	return $label;
}
add_filter('woocommerce_cart_shipping_method_full_label', 'wps_shipping_method_label', 10, 2);

/**
 * Validate ajax request
 */
function wps_validate_ajax_request() {
	if(!$_POST || (is_admin() && !is_ajax()))
		return;

	if(isset($_POST['post_data'])) {
		parse_str($_POST['post_data'], $post_data);
	} else {	
		$post_data = $_POST;
	}

	return $post_data;
}

/**
 * Get chosen shipping method
 * 
 * @version 1.8.2
 * @since 1.5.x
 * 
 * @return array Chosen shipping methods or empty array
 */
function wps_get_chosen_shipping_method() {
	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
	
	return is_array( $chosen_methods ) ? $chosen_methods[0] : array();
}

/**
 * Check is chosen shipping is wc_pickup_store
 * @version 1.6.1
 * @return bool True is chosen shipping is wc_pickup_store
 */
function is_wps_chosen_shipping_method() {
	$chosen_shipping = wps_get_chosen_shipping_method();

	return in_array( $chosen_shipping, array( WPS()->id ) );
}

/**
 * Add CSS/JS
 */
function wps_store_enqueue_styles() {
	$min = ( !preg_match( '/localhost/', site_url() ) ) ? '.min' : '';

	if ( $bootstrap_cdn = WPS()->wps_get_library_version_or_cdn( 'bootstrap', WPS()->bootstrap_version ) ) {
		wp_enqueue_style( 'wps_bootstrap', $bootstrap_cdn );
	}
	
	if ( $font_awesome_cdn = WPS()->wps_get_library_version_or_cdn( 'font_awesome', WPS()->font_awesome_version ) ) {
		wp_enqueue_style( 'wps_fontawesome', $font_awesome_cdn );
	}
	
	if( !isset( WPS()->local_css ) || ( isset (WPS()->local_css ) && WPS()->local_css != 'yes' ) ) {
		wp_enqueue_style( 'store-styles', WPS_PLUGIN_DIR_URL . 'assets/css/stores' . $min . '.css' );
	}

	if ( is_checkout() ) {
		wp_enqueue_script('wp-util');
		wp_enqueue_script( 'store-checkout', WPS_PLUGIN_DIR_URL . 'assets/js/stores' . $min . '.js', array( 'jquery', 'wp-util' ), null);

		$localize_script = array(
			'stores' => wps_stores_fields()
		);

		if ( !isset( WPS()->disable_select2 ) || ( isset( WPS()->disable_select2 ) && WPS()->disable_select2 != 'yes' ) ) {
			$localize_script['disable_select2'] = 1;
			$localize_script['demo'] = 1;
		}
		wp_localize_script( 'store-checkout', 'wps_ajax', apply_filters( 'wps_localize_script', $localize_script ) );
	}
}
add_action('wp_enqueue_scripts', 'wps_store_enqueue_styles');

/**
 * Add store shipping cost to cart amount
 * 
 * @version 1.7.0
 * @since 1.5.21
 */
function wps_add_store_shipping_to_cart( $cart ) {
	$post_data = wps_validate_ajax_request();

	if ( is_wps_chosen_shipping_method() ) {
		$tax_class = '';
		$is_taxable = ( bool ) ( wps_get_tax_status() == 'taxable' );

		if ( isset( $post_data['shipping_pickup_stores'] ) ) {
			$chosen_store = $post_data['shipping_pickup_stores'];
			WC()->session->set('shipping_store', $chosen_store);
		}
		
		if ( isset( $post_data['shipping_by_store'] ) && $post_data['shipping_by_store'] > 0 ) {
			$amount = $post_data['shipping_by_store'];

			if ( $is_taxable ) {
				// Set the chosen class for shipping taxes calculation
				$shipping_tax_class = get_option( 'woocommerce_shipping_tax_class' );
				if ( 'inherit' !== $shipping_tax_class ) {
					$tax_class = $shipping_tax_class;
				}
			}
			
			WC()->cart->add_fee(
				apply_filters( 'wps_store_pickup_cost_label', sprintf( __('Ship to %s', 'wc-pickup-store'), $chosen_store ), $chosen_store ),
				$amount,
				$is_taxable,
				$tax_class
			);
		}
	}
}
add_action('woocommerce_cart_calculate_fees', 'wps_add_store_shipping_to_cart');

/**
** Adding CC to email order notification
**/
function wps_cc_email_headers($headers, $email_id, $order) {
	$cc_on_email_types = apply_filters('wps_cc_on_email_types', array('new_order'));

	if (in_array($email_id, $cc_on_email_types)) {
		if ($custom_email = wps_get_email_address($order)) {
			// Add Cc to headers
			$headers .= 'Cc: ' . implode(',', $custom_email) . "\r\n";
		}
	}

	return $headers;
}
add_filter('woocommerce_email_headers', 'wps_cc_email_headers', 10, 3);

/**
 * Get email address from store
 * 
 * @version 1.8.6
 * @since 1.5.24
 * 
 * @param WC_Order $order
 * @param int $store_id				Optional, if empty gets the store_id from order
 * @param bool $get_first_email		Check true to get the first email address added to the store
 * 
 * @return mixed First email address, all email addresses or false if email address field is empty
 */
function wps_get_email_address( $order, $store_id = 0, $get_first_email = false ) {
	if ( $store_id == 0 ) {
		$store_name = $order->get_meta( '_shipping_pickup_stores' ); // Get store title for this order
		$store_id = wps_get_store_id_by_name( $store_name );
	}

	$store_order_email = wps_get_post_meta( $store_id, 'store_order_email' );
	$enable_order_email = wps_get_post_meta( $store_id, 'enable_order_email' );

	if ( !empty( $store_order_email ) ) {
		$store_order_email = explode(',', $store_order_email);

		if ( is_array( $store_order_email ) ) {
			if ( $get_first_email ) {
				return $store_order_email[0];
			}
	
			if ( $enable_order_email == 1 ) {
				return array_map( 'trim', $store_order_email );
			}
		}
	}

	return false;
}

/**
 * Get all stores or store_id with its custom fields
 * 
 * @version 1.7.1
 * @since 1.5.22
 * 
 * @param int $store_id Optional
 * 
 * @return array Key value array with store(s) data
 */
function wps_stores_fields( $store_id = 0 ) {
	$the_fields = array();
	$custom_fields = apply_filters('wps_get_store_custom_fields', 
		array('city', 'phone', 'address', 'map')
	);

	if ( $store_id > 0 ) {
		$custom_fields = wp_parse_args( array('store_country'), $custom_fields );
		$the_fields['id'] = $store_id;
		foreach ($custom_fields as $key => $custom_field) {
			if ( $the_field = wps_get_post_meta($store_id, $custom_field) ) {
				$the_fields[ $custom_field ] = ( $custom_field == 'store_country' && empty( $the_field ) && $the_field == -1 ) ? wps_get_wc_default_country() : $the_field;
			}
		}
	} else {
		$the_stores = wps_store_get_store_admin( true );
	
		foreach ($the_stores as $store_id => $store) {
			$store_country = wps_get_post_meta( $store_id, 'store_country' );
			$store_country = (!empty($store_country) && $store_country != -1) ? $store_country : wps_get_wc_default_country();
	
			$the_fields[$store_country][$store_id] = array(
				array(
					'key' => 'title',
					'value' => $store
				)
			);
			
			foreach ($custom_fields as $key => $custom_field) {
				if ($the_field = wps_get_post_meta($store_id, $custom_field)) {
					$the_fields[$store_country][$store_id][] = array(
						'key' => $custom_field,
						'value' => $the_field
					);
				}
			}
		}
	}

	return apply_filters('wps_stores_fields', $the_fields);
}

/**
 * Get WPS template
 * 
 * @version 1.8.6
 * @since 1.5.22
 */
function wps_locate_template( $template_name ) {
	$template_name = ltrim( $template_name, '/' );
	$template_file = trailingslashit( plugin_dir_path( __DIR__ ) ) . 'templates/' . $template_name;
	
	if ( locate_template( 'template-parts/' . $template_name ) ) {
		$template_file = locate_template( 'template-parts/' . $template_name, false );
	}

	if ( file_exists( $template_file ) ) {
		return $template_file;
	}
	
	return false;
}

/**
 * Custom function to return post meta using a filter
 * 
 * @version 1.5.24
 */
function wps_get_post_meta( $post_id, $custom_field ) {
	return apply_filters( 'wps_get_post_meta', get_post_meta( $post_id, $custom_field, true ), $post_id, $custom_field );
}
			
/**
 * Set message on cart page
 * 
 * @version 1.6.1
 */
function wps_wc_cart_totals_before_order_total() {
	if ( is_wps_chosen_shipping_method() ) {
		$cart_message = apply_filters('wps_cart_message', __('Choose a store for picking up your order on the Checkout page.', 'wc-pickup-store'));

		if ( !empty($cart_message) ) {
			?>
			<tr class="shipping-pickup-store">
				<td colspan="2">
					<p class="message"><?= $cart_message ?></p>
				</td>
			</tr>
			<?php
		}
	}
}
add_action('woocommerce_cart_totals_before_order_total', 'wps_wc_cart_totals_before_order_total');

/**
 * Get store ID by store name
 * 
 * @version 1.8.5
 * @since 1.6.3
 * 
 * @param string $store_name
 * 
 * @return int $store_id
 */
function wps_get_store_id_by_name( $store_name = '' ) {
	$store_id = 0;
	if ( !empty( $store_name ) ) {
		$store = wps_get_page_by_title( $store_name, 'store' );
		$store_id = isset( $store->ID ) ? $store->ID : 0;
	}
	
	return $store_id;
}

/**
 * Replacement for deprecated function
 * 
 * @version 1.8.5
 * 
 * @param string $title
 * @param string $post_type
 * 
 * @return bool|WP_Post
 */
function wps_get_page_by_title( $title, $post_type = 'page' ) {
	$posts = get_posts(
		array(
			'post_type' => $post_type,
			'title' => $title,
			'post_status' => 'all',
			'numberposts' => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,           
			'orderby' => 'post_date ID',
			'order' => 'ASC',
		)
	);
	 
	if ( ! empty( $posts ) ) {
		return $posts[0];
	}

	return false;
}


/**
 * Get tax status configured on settings page
 * 
 * @version 1.7.0
 * 
 * @return mixed Chosen tax status type or false if calc_taxes option is disabled
 */
function wps_get_tax_status_option() {
	if ( wc_tax_enabled() ) {
		return ( ! empty( WPS()->wps_tax_status ) ) ? WPS()->wps_tax_status : 'none';
	}
	
	return false;
}

/**
 * Get tax status
 * 
 * @version 1.7.0
 */
function wps_get_tax_status() {
	if ( wps_get_tax_status_option() ) {
		$tax_status = 'none';
		if ( WPS()->wps_tax_status == 'taxable_per_store' ) {
			$post_data = wps_validate_ajax_request();

			if ( isset( $post_data['shipping_pickup_stores'] ) ) {
				$store_id = wps_get_store_id_by_name( $post_data['shipping_pickup_stores'] );
				$taxable_store = wps_get_post_meta( $store_id, 'taxable_store' );
				$tax_status = !empty( $taxable_store ) ? 'taxable' : $tax_status;
			}
		} else {
			$tax_status = WPS()->wps_tax_status;
		}
		return $tax_status;
	}
	
	return '';
}

/**
 * Formatting shipping address for wc_pickup_store method
 * 
 * @version 1.8.6
 * @since 1.7.1
 * 
 * @return string Address with store information
 */
function wps_wc_order_get_formatted_shipping_address( $address, $raw_address, $order ) {
	$store_name = $order->get_meta( '_shipping_pickup_stores' ); // Get store title for this order
	$store_id = wps_get_store_id_by_name( $store_name );
	$store = wps_stores_fields( $store_id );

	if ( $store_id != 0 && $order->has_shipping_method( 'wc_pickup_store' ) ) {
		$address = WC()->countries->get_formatted_address( array(
			'company' => sprintf( '%1$s: %2$s', apply_filters( 'wps_store_checkout_label', WPS()->title), $store_name ),
			'address_1' => isset( $store['address'] ) ? wp_strip_all_tags( $store['address'] ) : '',
			'city' => $store['city'] ?? '',
			'phone' => $store['phone'] ?? '',
			'country' => $store['store_country'] ?? '',
			'postcode' => ''
		) );
		
		if ( $store_email = wps_get_email_address( $order, $store_id, true ) ) {
			$address .= '<br>' . sprintf( '%1$s: %2$s', __( 'Store email', 'wc-pickup-store' ), $store_email );
		}
	}
	
	return $address;
}
add_filter( 'woocommerce_order_get_formatted_shipping_address', 'wps_wc_order_get_formatted_shipping_address', 10, 3 );