<?php
/**
 * It is template of pos client
 *
 * @var \VitePos_Lite\Modules\POS_Settings $this
 *
 * @package vitepos
 */

?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="theme-color" content="<?php echo esc_html( $this->get_pos_color_code() ); ?>">
	<link rel="icon" href="<?php echo esc_url( $this->get_favicon() ); ?>">
	<link rel="manifest" href="<?php echo esc_url( $this->get_manifest_link() ); ?>">
	<title>pos</title>
	<script>
		var vitePosBase="<?php echo esc_url( get_rest_url( null, 'vitepos/v1' ) ); ?>/";
		var viteposSWJs="<?php echo esc_url( $this->get_sw_link() ); ?>";
		var vitePos= {
			version:"<?php echo esc_html( $this->kernel_object->plugin_version ); ?>",
			heart_bit: 30000,
			currencySymbol: '<?php echo esc_html( html_entity_decode( get_woocommerce_currency_symbol() ) ); ?>',
			decimalPlaces: <?php echo esc_attr( wc_get_price_decimals() ); ?>,
			login_type:"<?php echo esc_html( strtoupper( \VitePos_Lite\Modules\POS_Settings::get_module_option( 'login_type' ) ) ); ?>",
			ca_prefix:"<?php echo esc_attr( hash( 'crc32b', site_url() ) . '_' ); ?>",
			pos_link:"<?php echo esc_html( \VitePos_Lite\Modules\POS_Settings::get_module_instance()->get_pos_link( true ) ); ?>",
			wcnonce: "<?php echo esc_html( wp_create_nonce( 'wp_rest' ) ); ?>",
			date_format: "<?php echo esc_html( vitepos_get_client_date_format() ); ?>",
			time_format: "<?php echo esc_html( vitepos_get_client_time_format() ); ?>",
			wc_amount: function ($amount) {
				try {
					if(isNaN($amount)){
						return 0.0;
					}
					return $amount.toFixed(vitePos.decimalPlaces);
				}catch (e) {
					$amount=parseFloat($amount);
					return $amount.toFixed(vitePos.decimalPlaces);
				}
			},
			wc_price: function ($amount) {
				$amount = parseFloat($amount);
				$amount=vitePos.wc_amount($amount)
				var price_format=<?php echo wp_json_encode( $this->get_price_format_settigns() ); ?>;
				var rx=  /(\d+)(\d{3})/;
				if(price_format.thousand_separator && price_format.thousand_separator != "") {
					$amount = String($amount).replace(/^\d+/, function (w) {
						while (rx.test(w)) {
							w = w.replace(rx, '$1' + price_format.thousand_separator + '$2');
						}
						return w;
					});
				}
				return price_format.price_format.replace('{{amt}}', $amount);
			},
			roundingFactor: "D",//D=Discount, F=Fee, N=none
			assets_path:'<?php $this->get_plugin_esc_url( 'templates/pos-assets' ); ?>/',
			urls: {
				sys_login:"",
				heart_bit: vitePosBase + "system/heart-bit",
				country_list: vitePosBase + "basic/countries",
				settings: vitePosBase + "basic/settings",
				current_user: vitePosBase + "user/current-user",
				get_logged_user: vitePosBase + "user/get-logged-user",
				product_list: vitePosBase + "product/list",
				list_variation: vitePosBase + "product/list-variation",
				order_list: vitePosBase + "order/order-list",
				order_details: vitePosBase + "order/details",
				initials_data: vitePosBase + "product/initial-data",
				make_payment: vitePosBase + "order/make-payment",
				sync_offline_order: vitePosBase + "order/sync-offline-order",
				product_details: vitePosBase + "product/details",
				create_product: vitePosBase + "product/create",
				update_product: vitePosBase + "product/update",
				category_list: vitePosBase + "product/categories",
				all_category_list: vitePosBase + "product/all-categories",
				attributes_list: vitePosBase + "product/attributes",
				get_stock: vitePosBase + "product/getStock",
				scan_product: vitePosBase + "product/scan-product",
				vendor_list: vitePosBase + "vendor/list",
				vendor_details: vitePosBase + "vendor/details",
				create_vendor: vitePosBase + "vendor/create",
				update_vendor_status: vitePosBase + "vendor/update_status",
				delete_vendor: vitePosBase + "vendor/delete-vendor",
				delete_customer: vitePosBase + "customer/delete-customer",
				delete_user: vitePosBase + "user/delete-user",
				close_cashDrawer: vitePosBase + "user/close-cash-drawer",
				delete_product: vitePosBase + "product/delete-product",
				outlet_list: vitePosBase + "outlet/list",
				all_outlet_list: vitePosBase + "outlet/all-outlet-list",
				cash_drawer_info: vitePosBase + "outlet/cash-drawer-info",
				cash_drawer_log: vitePosBase + "outlet/cash-drawer-log",
				withdraw_cash: vitePosBase + "outlet/withdraw-cash",
				close_drawer: vitePosBase + "outlet/close-drawer",
				drawer_log_details: vitePosBase + "outlet/details",
				drawer_summary: vitePosBase + "outlet/summary",
				purchase_list: vitePosBase + "purchase/list",
				create_purchase: vitePosBase + "purchase/create",
				purchase_details: vitePosBase + "purchase/details",
				updated_price_list: vitePosBase + "purchase/updated-price-list",
				create_customer: vitePosBase + "customer/create",
				check_unique: vitePosBase + "customer/check-unique",
				create_user: vitePosBase + "user/create",
				user_login: vitePosBase + "user/login",
				user_logout: vitePosBase + "user/logout",
				change_pass: vitePosBase + "user/change-pass",
				change_pass_force: vitePosBase + "user/change-pass-force",
				customer_list: vitePosBase + "customer/list",
				customerList: vitePosBase + "customer/customer-list",
				user_list: vitePosBase + "user/list",
				role_list: vitePosBase + "user/roles",
				customer_details: vitePosBase + "customer/details",
				user_details: vitePosBase + "user/details",
				outlet_panel: vitePosBase + "user/outlet-panel",
				//restaurant
				sync_order_list: vitePosBase + "restaurant/sync-order-list",
				canned_message: vitePosBase + "restaurant/canned-message",
				send_to_kitchen: vitePosBase + "restaurant/send-to-kitchen",
				resto_details: vitePosBase + "restaurant/details",
				make_served: vitePosBase + "restaurant/make-served",
				cancel_order: vitePosBase + "restaurant/cancel-order",
				cancel_order_request: vitePosBase + "restaurant/cancel-order-request",
				cancel_request_ans: vitePosBase + "restaurant/cancel-request-ans",

				//Cashier
				served_list: vitePosBase + "restaurant/served-list",
				cashier_details: vitePosBase + "restaurant/cashier-details",
				restaurant_payment: vitePosBase + "restaurant/restaurant-payment",
				send_email: vitePosBase + "order/email",

				all_taxes: vitePosBase + "product/all-taxes",
			},
			translationObj: {
				availableLanguages: {
					en_US: "American English"
				},
				defaultLanguage: "en_US",
				translations: {
					"en_US": <?php echo wp_json_encode( \VitePos_Lite\Libs\Client_Language::get_pos_languages( $this->kernel_object ) ); ?>
				}
			}
		}</script>
	<?php
	/**
	 * Its for pos client header
	 *
	 * @since 1.0
	 */
	do_action( 'vitepos-client-header' );
	?>
</head>
	<body><noscript><strong> <?php echo esc_html( $this->kernel_object->__( "We're sorry but pos doesn't work properly without JavaScript enabled. Please enable it to continue." ) ); ?></strong></noscript>
		<div id="app">
			<div class="pre-loader">
				<?php echo esc_html( $this->kernel_object->__( 'Please wait ..' ) ); ?>
			</div>
		</div>
	<?php
	/**
	 * Its for pos client header
	 *
	 * @since 1.0
	 */
	do_action( 'vitepos-client-footer' );
	?>
	</body>
	</html>
