<?php

if ( ! function_exists( 'wffn_clean' ) ) {
	function wffn_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'wffn_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'wffn_show_notice' ) ) {
	function wffn_show_notice( $args, $context ) {
		global $wffn_notices;

		ob_start();
		if ( $context === 'version_mismatch' ) {
			?>
            <div class="bwf-notice error">
                <p>
                    <strong><?php esc_html_e( 'Attention', 'woofunnels-upstroke-power-pack' ); ?></strong>
					<?php
					/* translators: %1$s: Plugin name %2$s Plugin name */
					echo sprintf( esc_html__( 'The %1$s version running your site is not compatible with the Funnel Builder plugin, Please update %1$s to the recent version. ', 'woofunnels-upstroke-power-pack' ), esc_attr( $args['pname'] ) );
					?>
                </p>
            </div>
			<?php

		} else {
			echo wp_kses_post( $args['text'] );

		}

		$wffn_notices[] = ob_get_clean();
	}
}

/**
 * Converts a string (e.g. 'yes' or 'no' , 'true') to a bool.
 *
 * @param $string
 *
 * @return bool
 */
if ( ! function_exists( 'wffn_string_to_bool' ) ) {
	function wffn_string_to_bool( $string ) {
		if ( is_null( $string ) ) {
			return false;
		}

		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}
}

/**
 * Converts a bool to a 'yes' or 'no'.
 *
 * @param bool $bool String to convert.
 *
 * @return string
 * @since 3.0.0
 */
if ( ! function_exists( 'wffn_bool_to_string' ) ) {
	function wffn_bool_to_string( $bool ) {
		if ( ! is_bool( $bool ) ) {
			$bool = wffn_string_to_bool( $bool );
		}

		return true === $bool ? 'yes' : 'no';
	}
}
if ( ! function_exists( 'wffn_maybe_import_funnel_in_background' ) ) {
	if ( ! function_exists( 'wffn_maybe_import_funnel_in_background' ) ) {
		function wffn_maybe_import_funnel_in_background() {
			$funnel_id = get_option( '_wffn_scheduled_funnel_id', 0 );
			BWF_Logger::get_instance()->log( "Running the callback wffn_maybe_import_funnel_in_background: $funnel_id ", 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			if ( $funnel_id > 0 ) {
				BWF_Logger::get_instance()->log( 'Importing template for funnel: ' . print_r( $funnel_id, true ) . '-fn- ' . __FUNCTION__, 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				$funnel = new WFFN_Funnel( $funnel_id );

				$funnel_steps = $funnel->get_steps();
				BWF_Logger::get_instance()->log( 'Funnel steps: ' . print_r( $funnel_steps, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				foreach ( $funnel_steps as $funnel_step ) {
					$get_object = WFFN_Core()->steps->get_integration_object( $funnel_step['type'] );
					if ( ! empty( $get_object ) ) {
						$has_scheduled = $get_object->has_import_scheduled( $funnel_step['id'] );

						if ( is_array( $has_scheduled ) ) {
							BWF_Logger::get_instance()->log( 'Ready to import, step ID: ' . $funnel_step['id'] . ', Template: ' . print_r( $has_scheduled, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

							$get_object->do_import( $funnel_step['id'] );
							$get_object->update_template_data( $funnel_step['id'], [
								'selected'      => $has_scheduled['template'],
								'selected_type' => $has_scheduled['template_type'],
							] );
						}
					}
				}
			}
		}
	}
}


if ( ! function_exists( 'wffn_price' ) ) {
	function wffn_price( $price, $args = array() ) {

		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $price, $args );
		}


		$currency_pos = 'left';
		$format       = '%1$s%2$s';


		$price_format = apply_filters( 'wffn_price_format', $format, $currency_pos );

		$args = apply_filters( 'wffn_price_args', wp_parse_args( $args, array(
			'ex_tax_label'       => false,
			'currency'           => '',
			'decimal_separator'  => apply_filters( 'wffn_get_price_decimal_separator', '.' ),
			'thousand_separator' => apply_filters( 'wffn_get_price_thousand_separator', '.' ),
			'decimals'           => apply_filters( 'wffn_get_price_thousand_separator', '2' ),
			'price_format'       => $price_format,
		) ) );

		$unformatted_price = $price;
		$negative          = $price < 0;
		$price             = apply_filters( 'wffn_raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price             = apply_filters( 'wffn_formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		if ( apply_filters( 'wffn_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = preg_replace( '/' . preg_quote( $args['decimal_separator'], '/' ) . '0++$/', '', $price );
		}
		$currency = $args['currency'];
		if ( ! $currency ) {
			$currency = 'USD';
		}

		$symbols = wffn_currency_symbols();

		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

		$symbol          = apply_filters( 'woocommerce_currency_symbol', $currency_symbol, $currency );
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '' . $symbol . '', $price );
		$return          = $formatted_price;


		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return Price HTML markup.
		 * @param string $price Formatted price.
		 * @param array $args Pass on the args.
		 * @param float $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 */
		return apply_filters( 'wffn_price', $return, $price, $args, $unformatted_price );
	}
}
if ( ! function_exists( 'wffn_currency_symbols' ) ) {
	function wffn_currency_symbols() {

		$symbols = apply_filters( 'wffn_currency_symbols', array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		) );

		return $symbols;
	}
}
if ( ! function_exists( 'wffn_is_valid_funnel' ) ) {
	function wffn_is_valid_funnel( $funnel ) {
		return ( $funnel instanceof WFFN_Funnel && 0 < $funnel->get_id() );
	}
}

if ( ! function_exists( 'wffn_is_wc_active' ) ) {
	function wffn_is_wc_active() {
		return wffn_is_plugin_active( 'woocommerce/woocommerce.php' );
	}
}

if ( ! function_exists( 'wffn_is_plugin_active' ) ) {
	function wffn_is_plugin_active( $plugin_basename ) {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( in_array( $plugin_basename, apply_filters( 'active_plugins', $active_plugins ), true ) || array_key_exists( $plugin_basename, apply_filters( 'active_plugins', $active_plugins ) ) ) {
			return true;
		}


		return false;

	}
}


if ( ! function_exists( 'wffn_get_ip_address' ) ) {
	function wffn_get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) ); //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {  //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );  //phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders,WordPressVIPMinimum.Variables.RestrictedVariables
		}

		return '127.0.0.1';
	}
}

if ( ! function_exists( 'wffn_get_user_agent' ) ) {
	function wffn_get_user_agent() {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? wffn_clean( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';  //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables
	}
}

if ( ! function_exists( 'wffn_fk_automations_active' ) ) {
	function wffn_fk_automations_active() {
		return class_exists( 'BWFAN_Core' );
	}
}

/**
 * conversion table migration functions
 */

if ( ! function_exists( 'wffn_run_conversion_migrator' ) ) {
	function wffn_run_conversion_migrator() {
		global $wpdb;
		$per_page             = 20;
		$conversion_threshold = absint( get_option( '_bwf_conversion_threshold', 0 ) );

		/**
		 * If threshold is 0 then we will set it to total number of orders
		 * reaching inside this condition also means that this is the first time we are running this migration
		 */
		if ( 0 === $conversion_threshold ) {

			/**
			 * delete all duplicate entries from checkout table
			 */
			$duplicate_col = $wpdb->get_col( "SELECT s1.ID FROM {$wpdb->prefix}wfacp_stats AS s1 LEFT JOIN ( SELECT MIN(ID) AS min_id FROM {$wpdb->prefix}wfacp_stats GROUP BY order_id ) AS s2 ON s1.ID = s2.min_id
WHERE s2.min_id IS NULL" );

			if ( is_array( $duplicate_col ) && count( $duplicate_col ) > 0 ) {
				$duplicate_ids = implode( ',', $duplicate_col );
				$wpdb->query( $wpdb->prepare( "DELETE from {$wpdb->prefix}wfacp_stats WHERE ID IN (%1s)", $duplicate_ids ) ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
				if ( ! empty( $wpdb->last_error ) ) {
					WFFN_Core()->logger->log( 'migration process delete duplicate entry from wfacp table query error ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
				}
			}

			/**
			 * delete all duplicate entries from bump table
			 */
			$ob_duplicate_col = $wpdb->get_col( "SELECT s1.ID FROM {$wpdb->prefix}wfob_stats AS s1 LEFT JOIN ( SELECT MIN(ID) AS min_id FROM {$wpdb->prefix}wfob_stats GROUP BY oid ) AS s2 ON s1.ID = s2.min_id
WHERE s2.min_id IS NULL" );

			if ( is_array( $ob_duplicate_col ) && count( $ob_duplicate_col ) > 0 ) {
				$ob_duplicate_ids = implode( ',', $ob_duplicate_col );
				$wpdb->query( $wpdb->prepare( "DELETE from {$wpdb->prefix}wfob_stats WHERE ID IN (%1s)", $ob_duplicate_ids ) ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
				if ( ! empty( $wpdb->last_error ) ) {
					WFFN_Core()->logger->log( 'migration process delete duplicate entry from bump table query error ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
				}
			}

			$number_of_order = $wpdb->get_var( "select count(ID) as order_count from {$wpdb->prefix}wfacp_stats WHERE ( fid != 0 OR fid != '' );" );
			WFFN_Core()->logger->log( 'migration process total number of order ' . $number_of_order, 'fk_conv_migration', true );

			if ( $number_of_order === 0 ) {
				WFFN_Core()->logger->log( 'no orders found in aero table', 'fk_conv_migration', true );

				return wffn_run_optin_conversion_migrator();
			}
			update_option( '_bwf_conversion_threshold', absint( $number_of_order ) );
			$conversion_threshold = $number_of_order;

			/**
			 * We are marking all the wc checkout funnel IDs to 0 so that after the process is done we could delete all those orphan rows that should no longer exists.
			 */
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'bwf_conversion_tracking SET funnel_id = %d WHERE type = %d', 0, 2 ) );

		}

		/**
		 * Get the offset from where we need to start the process
		 */
		$offset = absint( get_option( '_bwf_conversion_offset', 0 ) );

		if ( $offset > 0 && $conversion_threshold > 0 && $offset >= $conversion_threshold ) {

			/**
			 * Since we are inside here we must terminate the process as offset is equal to threshold
			 */
			WFFN_Core()->logger->log( 'no orders found', 'fk_conv_migration', true );

			return wffn_run_optin_conversion_migrator();

		}

		/**
		 * Let's proceed with the process and find out next batch of orders
		 */
		WFFN_Core()->logger->log( 'finding orders from wfacp_stats - offset: ' . $offset, 'fk_conv_migration', true );
		$number_of_order = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}wfacp_stats WHERE ( fid != 0 OR fid != '' ) limit %d,%d ", $offset, $per_page ), ARRAY_A );

		/**
		 * If we don't have any orders then we must terminate the process
		 */
		if ( empty( $number_of_order ) ) {
			WFFN_Core()->logger->log( 'no orders found', 'fk_conv_migration', true );

			return wffn_run_optin_conversion_migrator();
		}

		$need_to_delete_orders = [];
		$need_to_update_orders = [];
		$need_to_create_orders = [];
		$upsell_created_orders = [];
		$conversion_tracking   = BWF_Ecomm_Tracking_Common::get_instance();

		$order_ids_string = implode( ',', wp_list_pluck( $number_of_order, 'order_id' ) );


		/**
		 * delete all order from conversion if order not exists in wc_order_stats table
		 */

		$bumps_data   = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}wfob_stats where oid IN (%1s)", $order_ids_string ), ARRAY_A );
		$upsells_data = $wpdb->get_results( $wpdb->prepare( "SELECT sess.order_id,
    event_t.object_id AS offer_id, 
    event_t.action_type_id AS action_type,
    event_t.value AS total_revenue  
 FROM `{$wpdb->prefix}wfocu_session` AS sess JOIN `{$wpdb->prefix}wfocu_event` AS event_t ON sess.id = event_t.sess_id 
WHERE sess.order_id IN (%1s) AND event_t.action_type_id IN (4,6);", $order_ids_string ), ARRAY_A );


		/***
		 * prepare process order array
		 */
		foreach ( $number_of_order as $k => $item ) {

			$order_id = absint( $item['order_id'] );
			WFFN_Core()->logger->log( 'Processing order: ' . $order_id, 'fk_conv_migration', true );

			if ( in_array( $order_id, $upsell_created_orders, true ) ) {
				continue;
			}

			$offset ++;
			update_option( '_bwf_conversion_offset', $offset );

			/**
			 * Filter aero table order from wc_orders
			 * 1. Remove order from conversion table which have not paid status
			 * 2. Remove order from conversion table which not exists in wc table
			 */
			$is_delete = false;


			/**
			 * continue if maybe order need delete
			 */
			if ( $is_delete ) {
				unset( $number_of_order[ $k ] );
				continue;
			}

			/**
			 * merge bump data
			 */
			$accepted_bump = [];
			$rejected_bump = [];
			$bump_total    = 0;
			if ( ! empty( $bumps_data ) && is_array( $bumps_data ) ) {
				foreach ( $bumps_data as $bump ) {
					if ( absint( $item['order_id'] ) === absint( $bump['oid'] ) ) {
						if ( 1 === absint( $bump['converted'] ) ) {
							$accepted_bump[] = ( string ) $bump['bid'];
							$bump_total      += floatval( $bump['total'] );
						} else {
							$rejected_bump[] = ( string ) $bump['bid'];
						}
					}
				}
			}

			/**
			 * merge offer data
			 */
			$accepted_offer = [];
			$rejected_offer = [];
			$offer_total    = 0;

			if ( ! empty( $upsells_data ) ) {
				foreach ( $upsells_data as $u_data ) {
					if ( absint( $item['order_id'] ) === absint( $u_data['order_id'] ) ) {
						if ( 4 === absint( $u_data['action_type'] ) ) {
							$accepted_offer[] = $u_data['offer_id'];
							$offer_total      += floatval( $u_data['total_revenue'] );
						}
						if ( 6 === absint( $u_data['action_type'] ) ) {
							$rejected_offer[] = $u_data['offer_id'];
						}
					}
				}
			}

			$cid        = $item['cid'];
			$fid        = $item['fid'];
			$step_id    = $item['wfacp_id'];
			$aero_total = $item['total_revenue'];

			$maybe_upsell_orders = '';
			if ( ! empty( $item['accepted_offer'] ) ) {
				$maybe_upsell_orders = $wpdb->get_results( $wpdb->prepare( "SELECT metatable.meta_value as 'order_id', event.value as 'total_sales',event.timestamp as 'offertime', event.object_id as 'offer_id' FROM `" . $wpdb->prefix . "wfocu_event` as event  LEFT JOIN " . $wpdb->prefix . "wfocu_event_meta as metatable ON ( event.id = metatable.event_id AND metatable.meta_key = '_new_order') INNER JOIN " . $wpdb->prefix . "wfocu_session as session ON (event.sess_id = session.id) WHERE session.order_id = %d AND event.action_type_id = 4", $item['order_id'] ), ARRAY_A );
			}

			$conversion = $wpdb->get_row( $wpdb->prepare( "select * from {$wpdb->prefix}bwf_conversion_tracking where type=2 AND source=%d;", $item['order_id'] ), ARRAY_A );


			if ( ! empty( $conversion ) ) {
				/**
				 * if it's not a checkout order & it's not an order of upsells
				 */
				if ( empty( $fid ) && empty( $conversion['funnel_id'] ) ) {
					$need_to_delete_orders[] = $order_id;
					continue;
				}
				$conversion_data     = [];
				$save_bump_rejected  = ! empty( $conversion['bump_rejected'] ) ? $conversion['bump_rejected'] : '';
				$save_bump_accepted  = ! empty( $conversion['bump_accepted'] ) ? $conversion['bump_accepted'] : '';
				$save_offer_rejected = ! empty( $conversion['offer_rejected'] ) ? $conversion['offer_rejected'] : '';
				$save_offer_accepted = ! empty( $conversion['offer_accepted'] ) ? $conversion['offer_accepted'] : '';

				$conversion_data['id']             = $conversion['id'];
				$conversion_data['utm_source']     = $conversion_tracking->string_length( $conversion['utm_source'] );
				$conversion_data['utm_medium']     = $conversion_tracking->string_length( $conversion['utm_medium'] );
				$conversion_data['utm_campaign']   = $conversion_tracking->string_length( $conversion['utm_campaign'] );
				$conversion_data['utm_term']       = $conversion_tracking->string_length( $conversion['utm_term'] );
				$conversion_data['utm_content']    = $conversion_tracking->string_length( $conversion['utm_content'] );
				$conversion_data['bump_rejected']  = ! empty( $rejected_bump ) ? wp_json_encode( $rejected_bump ) : $save_bump_rejected;
				$conversion_data['bump_accepted']  = ! empty( $accepted_bump ) ? wp_json_encode( $accepted_bump ) : $save_bump_accepted;
				$conversion_data['offer_rejected'] = ! empty( $rejected_offer ) ? wp_json_encode( $rejected_offer ) : $save_offer_rejected;
				$conversion_data['offer_accepted'] = ! empty( $accepted_offer ) ? wp_json_encode( $accepted_offer ) : $save_offer_accepted;
				$conversion_data['offer_total']    = floatval( $offer_total );
				$conversion_data['referrer']       = $conversion_tracking->filter_referrer( $conversion['referrer'] );
				$conversion_data['bump_total']     = $bump_total;
				$conversion_data['funnel_id']      = ! empty( $fid ) ? $fid : $conversion['funnel_id'];
				$conversion_data['contact_id']     = ! empty( $cid ) ? $cid : $conversion['contact_id'];
				$conversion_data['checkout_total'] = floatval( $aero_total );
				$conversion_data['value']          = ( $conversion_data['checkout_total'] + $conversion_data['offer_total'] + $conversion_data['bump_total'] );

				// Run Update for conversion
				$need_to_update_orders[] = $conversion_data;
			} else {
				if ( empty( $fid ) ) {
					$need_to_delete_orders[] = $order_id;
					continue;
				}

				/**
				 * add upsell created order in conversion table
				 */

				$args = [
					'contact_id'        => '',
					'utm_source'        => '',
					'utm_medium'        => '',
					'utm_campaign'      => '',
					'utm_term'          => '',
					'utm_content'       => '',
					'click_id'          => '',
					'type'              => 2,
					'value'             => 0,
					'checkout_total'    => 0,
					'bump_total'        => 0,
					'offer_total'       => 0,
					'bump_accepted'     => '',
					'bump_rejected'     => '',
					'offer_accepted'    => '',
					'offer_rejected'    => '',
					'step_id'           => 0,
					'funnel_id'         => 0,
					'automation_id'     => 0,
					'first_click'       => '0000-00-00 00:00:00',
					'first_landing_url' => '',
					'referrer'          => '',
					'journey'           => '',
					'source'            => 0,
					'device'            => 'desktop',
					'browser'           => '',
					'country'           => '',
					'timestamp'         => '0000-00-00 00:00:00'
				];


				if ( is_array( $maybe_upsell_orders ) && count( $maybe_upsell_orders ) > 0 ) {
					foreach ( $maybe_upsell_orders as $upsell_order ) {
						$offer_id                      = ( string ) $upsell_order['offer_id'];
						$upsell_created_orders[]       = absint( $upsell_order['order_id'] );
						$upsell_args                   = $args;
						$upsell_args['funnel_id']      = $fid;
						$upsell_args['contact_id']     = $cid;
						$upsell_args['source']         = $upsell_order['order_id'];
						$upsell_args['offer_accepted'] = wp_json_encode( [ $offer_id ] );
						$upsell_args['timestamp']      = $upsell_order['offertime'];
						$upsell_args['step_id']        = $step_id;
						$upsell_args['offer_total']    = floatval( $upsell_order['total_sales'] );
						$upsell_args['utm_source']     = ! empty( $conversion ) ? $conversion_tracking->string_length( $conversion['utm_source'] ) : '';
						$upsell_args['utm_medium']     = ! empty( $conversion ) ? $conversion_tracking->string_length( $conversion['utm_medium'] ) : '';
						$upsell_args['utm_campaign']   = ! empty( $conversion ) ? $conversion_tracking->string_length( $conversion['utm_campaign'] ) : '';
						$upsell_args['utm_term']       = ! empty( $conversion ) ? $conversion_tracking->string_length( $conversion['utm_term'] ) : '';
						$upsell_args['utm_content']    = ! empty( $conversion ) ? $conversion_tracking->string_length( $conversion['utm_content'] ) : '';
						$upsell_args['value']          = $upsell_args['offer_total'];
						$need_to_create_orders[]       = $upsell_args;
					}
					/***
					 * remove offer data from primary order
					 */
					$accepted_offer = [];
					$offer_total    = 0;

				}

				$args['funnel_id']      = $fid;
				$args['contact_id']     = $cid;
				$args['source']         = $order_id;
				$args['bump_rejected']  = ! empty( $rejected_bump ) ? wp_json_encode( $rejected_bump ) : '';
				$args['bump_accepted']  = ! empty( $accepted_bump ) ? wp_json_encode( $accepted_bump ) : '';
				$args['offer_rejected'] = ! empty( $rejected_offer ) ? wp_json_encode( $rejected_offer ) : '';
				$args['offer_accepted'] = ! empty( $accepted_offer ) ? wp_json_encode( $accepted_offer ) : '';
				$args['bump_total']     = floatval( $bump_total );
				$args['checkout_total'] = floatval( $aero_total );
				$args['timestamp']      = $item['date'];
				$args['step_id']        = $step_id;
				$args['offer_total']    = floatval( $offer_total );
				$args['value']          = ( $args['checkout_total'] + $args['offer_total'] + $args['bump_total'] );

				$need_to_create_orders[] = $args;
			}


		}

		if ( ! empty( $need_to_delete_orders ) ) {
			$ids = implode( ',', $need_to_delete_orders );

			$wpdb->query( $wpdb->prepare( "delete from {$wpdb->prefix}bwf_conversion_tracking where type=2 and source IN (%1s)", $ids ) ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder

			if ( ! empty( $wpdb->last_error ) ) {
				WFFN_Core()->logger->log( 'migration process delete orders query error ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
			}

		}

		if ( ! empty( $need_to_update_orders ) ) {
			WFFN_Conversion_Tracking_Migrator::update_multiple_conversion_rows( $need_to_update_orders );
		}

		if ( ! empty( $need_to_create_orders ) ) {
			WFFN_Conversion_Tracking_Migrator::insert_multiple_conversion_rows( $need_to_create_orders );
		}

		return true;
	}
}


if ( ! function_exists( 'wffn_run_optin_conversion_migrator' ) ) {
	function wffn_run_optin_conversion_migrator() {
		global $wpdb;
		$per_page = 20;


		/**
		 * If threshold is 0 then we will set it to total number of optins
		 * reaching inside this condition also means that this is the first time we are running this migration
		 */
		$conversion_threshold = absint( get_option( '_bwf_optin_conversion_threshold', 0 ) );
		if ( 0 === $conversion_threshold ) {
			$number_of_entries = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bwf_optin_entries" );
			WFFN_Core()->logger->log( 'migration process total number of optins ' . $number_of_entries, 'fk_conv_migration', true );

			if ( $number_of_entries === 0 ) {
				return false;
			}
			update_option( '_bwf_optin_conversion_threshold', absint( $number_of_entries ) );
			$conversion_threshold = $number_of_entries;
		}


		$offset = absint( get_option( '_bwf_optin_conversion_offset', 0 ) );
		if ( $offset > 0 && $conversion_threshold > 0 && $offset >= $conversion_threshold ) {
			WFFN_Core()->logger->log( 'No optin record found', 'fk_conv_migration', true );

			return false;
		}

		WFFN_Core()->logger->log( 'finding entry from bwf_optin_entries - offset: ' . $offset, 'fk_conv_migration', true );

		$number_of_entries = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}bwf_optin_entries limit %d,%d ", $offset, $per_page ), ARRAY_A );

		if ( empty( $number_of_entries ) ) {
			WFFN_Core()->logger->log( 'no entries found', 'fk_conv_migration', true );

			return false;
		}

		$default             = [
			'contact_id'        => '',
			'utm_source'        => '',
			'utm_medium'        => '',
			'utm_campaign'      => '',
			'utm_term'          => '',
			'utm_content'       => '',
			'click_id'          => '',
			'type'              => 2,
			'value'             => 0,
			'checkout_total'    => 0,
			'bump_total'        => 0,
			'offer_total'       => 0,
			'bump_accepted'     => '',
			'bump_rejected'     => '',
			'offer_accepted'    => '',
			'offer_rejected'    => '',
			'step_id'           => 0,
			'funnel_id'         => 0,
			'automation_id'     => 0,
			'first_click'       => '0000-00-00 00:00:00',
			'first_landing_url' => '',
			'referrer'          => '',
			'journey'           => '',
			'source'            => 0,
			'device'            => 'desktop',
			'browser'           => '',
			'country'           => '',
			'timestamp'         => '0000-00-00 00:00:00'
		];
		$conversion_tracking = BWF_Ecomm_Tracking_Common::get_instance();
		$new_data            = [];
		$update_data         = [];
		foreach ( $number_of_entries as $entry ) {
			WFFN_Core()->logger->log( 'Processing optin : ' . $entry['id'], 'fk_conv_migration', true );
			$offset ++;
			update_option( '_bwf_optin_conversion_offset', $offset );

			$optin_id  = $entry['id'];
			$cid       = $entry['cid'];
			$step_id   = $entry['step_id'];
			$funnel_id = $entry['funnel_id'];


			$_conversion = $wpdb->get_row( $wpdb->prepare( "SELECT id,utm_source, utm_medium, utm_campaign, utm_term, utm_content, referrer FROM {$wpdb->prefix}bwf_conversion_tracking WHERE type=1 AND source = %d", $optin_id ), ARRAY_A );

			if ( ! empty( $_conversion ) ) {
				// Trim Reference
				foreach ( [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ] as $key ) {
					$_conversion[ $key ] = $conversion_tracking->string_length( $_conversion[ $key ] );
				}
				$_conversion['referrer'] = $conversion_tracking->filter_referrer( $_conversion['referrer'] );


				$update_data[ $_conversion['id'] ] = $_conversion;

			} else {


				$_conversion = $wpdb->get_row( $wpdb->prepare( "SELECT id,utm_source, utm_medium, utm_campaign, utm_term, utm_content,referrer,timestamp FROM {$wpdb->prefix}bwf_conversion_tracking WHERE step_id = %d AND contact_id = %d AND timestamp BETWEEN DATE_SUB(%s, INTERVAL 5 SECOND) AND DATE_ADD(%s, INTERVAL 5 SECOND)", $step_id, $cid, $entry['date'], $entry['date'] ), ARRAY_A );


				$new_data_entry = wp_parse_args( [
					'contact_id'  => $cid,
					'type'        => 1,
					'step_id'     => $step_id,
					'source'      => $entry['id'],
					'funnel_id'   => $funnel_id,
					'first_click' => $entry['date'],
					'timestamp'   => $entry['date']
				], $default );
				if ( ! empty( $_conversion ) ) {

					$_conversion['source'] = $optin_id;
					foreach ( [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ] as $key ) {
						$_conversion[ $key ] = $conversion_tracking->string_length( $_conversion[ $key ] );
					}
					$_conversion['referrer'] = $conversion_tracking->filter_referrer( $_conversion['referrer'] );

					$update_data[ $_conversion['id'] ] = $_conversion;

				} else {

					$new_data[] = $new_data_entry;
				}
			}


		}


		if ( ! empty( $update_data ) ) {
			WFFN_Conversion_Tracking_Migrator::update_multiple_conversion_rows( $update_data );
		}

		if ( ! empty( $new_data ) ) {
			WFFN_Conversion_Tracking_Migrator::insert_multiple_conversion_rows( $new_data );
		}

		return true;
	}
}