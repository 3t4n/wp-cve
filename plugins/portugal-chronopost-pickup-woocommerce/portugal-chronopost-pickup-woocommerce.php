<?php
/*
 * Plugin Name: Portugal DPD Pickup and Lockers network for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/rede-chronopost-pickup-portugal-woocommerce-wordpress/
 * Description: Lets you deliver on the DPD Portugal Pickup network of partners or Lockers. This is not a shipping method but rather an add-on for any WooCommerce shipping method you activate it on.
 * Version: 3.3
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: portugal-chronopost-pickup-woocommerce
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * WC requires at least: 5.0
 * WC tested up to: 8.2
*/

/* WooCommerce CRUD ready */

/**
 * Check if WooCommerce is active
 **/
// Get active network plugins - "Stolen" from Novalnet Payment Gateway
function cppw_active_nw_plugins() {
	if ( !is_multisite() )
		return false;
	$cppw_activePlugins = ( get_site_option( 'active_sitewide_plugins' ) ) ? array_keys( get_site_option( 'active_sitewide_plugins' ) ) : array();
	return $cppw_activePlugins;
}
if ( in_array( 'woocommerce/woocommerce.php', ( array ) get_option( 'active_plugins' ) ) || in_array( 'woocommerce/woocommerce.php', ( array ) cppw_active_nw_plugins() ) ) {

	/* Loads textdomain */
	add_action( 'init', 'cppw_load_textdomain' );
	function cppw_load_textdomain() {
		//load_plugin_textdomain( 'portugal-chronopost-pickup-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' );
		load_plugin_textdomain( 'portugal-chronopost-pickup-woocommerce' );
	}

	//Init everything
	add_action( 'plugins_loaded', 'cppw_init', 999 ); // 999 because of WooCommerce Table Rate
	function cppw_init() {
		//Only on WooCommerce >= 4.0
		if ( version_compare( WC_VERSION, '4.0', '>=' ) ) {
			//Cron
			cppw_cronstarter_activation();
			add_action( 'cppw_update_pickup_list', 'cppw_update_pickup_list_function' );
			//De-activate cron
			register_deactivation_hook( __FILE__, 'cppw_cronstarter_deactivate' );
			//Add our settings to the available shipping methods - should be a loop with all the available ones
				add_action( 'wp_loaded', 'cppw_fields_filters' );
				//WooCommerce Table Rate Shipping - http://bolderelements.net/plugins/table-rate-shipping-woocommerce/ - Not available at plugins_loaded time
				add_filter( 'woocommerce_shipping_instance_form_fields_betrs_shipping', 'cppw_woocommerce_shipping_instance_form_fields_betrs_shipping' );
				//WooCommerce Advanced Shipping - https://codecanyon.net/item/woocommerce-advanced-shipping/8634573 - Not available at plugins_loaded time
				add_filter( 'was_after_meta_box_settings', 'cppw_was_after_meta_box_settings' );
			//Add to checkout
			add_action( 'woocommerce_review_order_before_payment', 'cppw_woocommerce_review_order_before_payment' );
			//Add to checkout - Fragment
			add_filter( 'woocommerce_update_order_review_fragments', 'cppw_woocommerce_update_order_review_fragments' );
			//Validate
			add_action( 'woocommerce_after_checkout_validation', 'cppw_woocommerce_after_checkout_validation', 10, 2 );
			//Save order meta
			add_action( 'woocommerce_checkout_update_order_meta', 'cppw_save_extra_order_meta' );
			//Show order meta on order screen and order preview
			add_action( 'woocommerce_admin_order_data_after_shipping_address', 'cppw_woocommerce_admin_order_data_after_shipping_address' );
			add_action( 'woocommerce_admin_order_preview_end', 'cppw_woocommerce_admin_order_preview_end' );
			add_filter( 'woocommerce_admin_order_preview_get_order_details', 'cppw_woocommerce_admin_order_preview_get_order_details', 10, 2 );
			//Ajax for point details update
			add_action( 'wc_ajax_' . 'cppw_point_details', 'wc_ajax_' . 'cppw_point_details' );
			//Add information to emails and order details
			if ( get_option( 'cppw_email_info', 'yes' ) == 'yes' ) {
				//Ideally we would use the same space used by the shipping address, but it's not possible - https://github.com/woocommerce/woocommerce/issues/19258
				add_action( 'woocommerce_email_customer_details', 'cppw_woocommerce_email_customer_details', 30, 3 );
				add_action( 'woocommerce_order_details_after_order_table', 'cppw_woocommerce_order_details_after_order_table' , 11 );
			}
			//Hide shipping address
			if ( get_option( 'cppw_hide_shipping_address', 'yes' ) == 'yes' ) {
				add_filter( 'woocommerce_order_needs_shipping_address', 'cppw_woocommerce_order_needs_shipping_address', 10, 3 );
			}
			//Change orders list shipping address
			add_action( 'manage_shop_order_posts_custom_column', 'cppw_manage_shop_order_custom_column', 9, 2 ); //Posts
			add_action( 'woocommerce_shop_order_list_table_custom_column', 'cppw_manage_shop_order_custom_column', 9, 2 ); //HPOS
			//Add instructions to the checkout
			if ( trim( get_option( 'cppw_instructions', '' ) ) != '' ) {
				add_action( 'woocommerce_after_shipping_rate', 'cppw_woocommerce_after_shipping_rate', 10, 2 );
			}
			//Settings
			if ( is_admin() && !wp_doing_ajax() ) {
				add_filter( 'woocommerce_shipping_settings', 'cppw_woocommerce_shipping_settings' );
				add_action( 'admin_notices', 'cppw_admin_notices' );
			}
			//PRO Plugin integrations
			add_filter( 'cppw_point_is_locker', 'cppw_point_is_locker_filter', 10, 2 );
			add_filter( 'cppw_get_pickup_points', 'cppw_get_pickup_points' );
		}
	}

	//Scripts
	add_action( 'wp_enqueue_scripts', 'cppw_wp_enqueue_scripts' );
	function cppw_wp_enqueue_scripts() {
		if ( ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_cart' ) && is_cart() ) ) {
			if ( !function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$plugin_data = get_plugin_data( __FILE__ );
			wp_enqueue_style( 'cppw-css', plugins_url( '/assets/style.css', __FILE__ ), array(), $plugin_data['Version'] );
			if ( class_exists( 'Flatsome_Default' ) && apply_filters( 'cppw_fix_flatsome', true ) ) {
				wp_enqueue_style( 'cppw-flatsome-css', plugins_url( '/assets/style-flatsome.css', __FILE__ ), array(), $plugin_data['Version'] );
			}
			if ( is_checkout() ) {
				wp_enqueue_script( 'cppw-js', plugins_url( '/assets/functions.js', __FILE__ ), array( 'jquery' ), $plugin_data['Version'], true );
				wp_localize_script( 'cppw-js', 'cppw', array(
					'shipping_methods' => cppw_get_shipping_methods(),
					'shop_country'     => wc_get_base_location()['country'],
				) );
			}
		}
	}

	//Add fields to settings
	function cppw_fields_filters() {
		//Avoid fatal errors on some weird scenarios
		if ( is_null( WC()->countries ) ) WC()->countries = new WC_Countries();
		//Load our filters
		foreach ( WC()->shipping()->get_shipping_methods() as $method ) { //https://woocommerce.wp-a2z.org/oik_api/wc_shippingget_shipping_methods/
			if ( ! $method->supports( 'shipping-zones' ) ) {
				continue;
			}
			switch ( $method->id ) {
				// Flexible Shipping for WooCommerce - https://wordpress.org/plugins/flexible-shipping/
				case 'flexible_shipping':
				case 'flexible_shipping_single':
					add_filter( 'flexible_shipping_method_settings', 'cppw_woocommerce_shipping_instance_form_fields_flexible_shipping', 10, 2 );
					add_filter( 'flexible_shipping_process_admin_options', 'cppw_woocommerce_shipping_instance_form_fields_flexible_shipping_save' );
					break;
				// The WooCommerce or other standard methods that implement the 'woocommerce_shipping_instance_form_fields_' filter
				default:
					add_filter( 'woocommerce_shipping_instance_form_fields_'.$method->id, 'cppw_woocommerce_shipping_instance_form_fields' );
					break;
			}
		}
	}


	//Our field on each shipping method
	function cppw_woocommerce_shipping_instance_form_fields( $settings ) {
		if ( !is_array( $settings ) ) $settings = array();
		$settings['cppw'] = array( 
			'title'			=> __( 'DPD Pickup in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'type'			=> 'select',
			'description'	=> __( 'Shows a field to select a point from the DPD Pickup network in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'default'       => '',
			'options'		=> array( 
				''	=> __( 'No', 'portugal-chronopost-pickup-woocommerce' ),
				'1'	=> __( 'Yes', 'portugal-chronopost-pickup-woocommerce' ),
			 ),
			'desc_tip'		=> true,
		 );
		return $settings;
	}


	//Our field on Flexible Shipping for WooCommerce - https://wordpress.org/plugins/flexible-shipping/
	function cppw_woocommerce_shipping_instance_form_fields_flexible_shipping( $settings, $shipping_method ) {
		$settings['cppw'] = array(
			'title'         => __( 'DPD Pickup in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'type' 	        => 'select',
			'description'	=> __( 'Shows a field to select a point from the Chronopost Pickup network in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'default'       => isset($shipping_method['cppw']) && intval($shipping_method['cppw'])==1 ? '1' : '',
			'options'		=> array( 
				''	=> __( 'No', 'portugal-chronopost-pickup-woocommerce' ),
				'1'	=> __( 'Yes', 'portugal-chronopost-pickup-woocommerce' ),
			 ),
			'desc_tip'		=> true,
		);
		return $settings;
	}
	function cppw_woocommerce_shipping_instance_form_fields_flexible_shipping_save( $shipping_method ) {
		$shipping_method['cppw'] = $_POST['woocommerce_flexible_shipping_cppw'];
		return $shipping_method;
	}

	//Our field on WooCommerce Table Rate Shipping - http://bolderelements.net/plugins/table-rate-shipping-woocommerce/
	function cppw_woocommerce_shipping_instance_form_fields_betrs_shipping( $settings ) {
		$settings['general']['settings']['cppw'] = array(
			'title'         => __( 'DPD Pickup in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'type' 	        => 'select',
			'description'	=> __( 'Shows a field to select a point from the DPD Pickup network in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
			'default'       => '',
			'options'		=> array( 
				''	=> __( 'No', 'portugal-chronopost-pickup-woocommerce' ),
				'1'	=> __( 'Yes', 'portugal-chronopost-pickup-woocommerce' ),
			 ),
			'desc_tip'		=> true,
		);
		return $settings;
	}

	//Our field on WooCommerce Advanced Shipping - https://codecanyon.net/item/woocommerce-advanced-shipping/8634573
	function cppw_was_after_meta_box_settings( $settings ) {
		?>
		<p class='was-option'>
			<label for='tax'><?php _e( 'DPD Pickup in Portugal', 'portugal-chronopost-pickup-woocommerce' ); ?></label>
			<select name='_was_shipping_method[cppw]' style='width: 189px;'>
				<option value='' <?php @selected( $settings['cppw'], '' ); ?>><?php _e( 'No', 'portugal-chronopost-pickup-woocommerce' ); ?></option>
				<option value='1' <?php @selected( $settings['cppw'], '1' ); ?>><?php _e( 'Yes', 'portugal-chronopost-pickup-woocommerce' ); ?></option>
			</select>
		</p>
		<?php
	}


	//Get all shipping methods available
	function cppw_get_shipping_methods() {
		$shipping_methods = array();
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zone_methods" );
		foreach ( $results as $method ) {
			switch ( $method->method_id ) {
				// Flexible Shipping for WooCommerce - https://wordpress.org/plugins/flexible-shipping/
				case 'flexible_shipping':
					$options = get_option( 'flexible_shipping_methods_'.$method->instance_id, array() );
					foreach ($options as $key => $fl_options) {
						if ( isset( $fl_options['cppw'] ) && intval( $fl_options['cppw'] )==1 ) $shipping_methods[] = $method->method_id.'_'.$method->instance_id.'_'.$fl_options['id'];
					}
					break;
				// WooCommerce Table Rate Shipping - http://bolderelements.net/plugins/table-rate-shipping-woocommerce/
				case 'betrs_shipping':
					$options = get_option( 'woocommerce_betrs_shipping_'.$method->instance_id.'_settings', array() );
					if ( isset( $options['cppw'] ) && intval( $options['cppw'] ) == 1 ) {
						$options_instance = get_option( 'betrs_shipping_options-'.$method->instance_id, array() );
						if ( isset( $options_instance['settings'] ) && is_array( $options_instance['settings'] ) ) {
							foreach ( $options_instance['settings'] as $setting ) {
								if ( isset( $setting['option_id'] ) ) $shipping_methods[] = $method->method_id.':'.$method->instance_id.'-'.$setting['option_id'];
							}
						}
					}
					break;
				// Table Rate Shipping - https://woocommerce.com/products/table-rate-shipping/
				case 'table_rate':
					$options = get_option( 'woocommerce_table_rate_'.$method->instance_id.'_settings', array() );
					if ( isset( $options['cppw'] ) && intval( $options['cppw'] ) == 1 ) {
						$rates = $wpdb->get_results( sprintf( "SELECT rate_id FROM {$wpdb->prefix}woocommerce_shipping_table_rates WHERE shipping_method_id = %d ORDER BY rate_order ASC", $method->instance_id ) );
						foreach ( $rates as $rate ) {
							$shipping_methods[] = $method->method_id.':'.$method->instance_id.':'.$rate->rate_id;
						}
					}
					break;
				// The WooCommerce or other standard methods that implement the 'woocommerce_shipping_instance_form_fields_' filter
				default:
					$options = get_option( 'woocommerce_'.$method->method_id.'_'.$method->instance_id.'_settings', array() );
					if ( isset( $options['cppw'] ) && intval( $options['cppw'] )==1 ) $shipping_methods[] = $method->method_id.':'.$method->instance_id;
					break;
			}
		}
		//WooCommerce Advanced Shipping - https://codecanyon.net/item/woocommerce-advanced-shipping/8634573
		if ( class_exists( 'WooCommerce_Advanced_Shipping' ) ) {
			$methods = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'was', 'orderby' => 'menu_order', 'order' => 'ASC', 'suppress_filters' => false ) );
			foreach ( $methods as $method ) {
				$settings = get_post_meta( $method->ID, '_was_shipping_method', true );
				if ( is_array( $settings ) && isset( $settings['cppw'] ) && intval( $settings['cppw'] ) == 1 ) {
					$shipping_methods[] = (string)$method->ID;
				}
			}
		}
		//Filter and return them
		$shipping_methods = array_unique( apply_filters( 'cppw_get_shipping_methods', $shipping_methods ) );
		return $shipping_methods;
	}

	
	//Add our DIV to the checkout
	function cppw_woocommerce_review_order_before_payment() {
		$shipping_methods = cppw_get_shipping_methods();
		if ( count( $shipping_methods )>0 ) {
			?>
			<div id="cppw" style="display: none;">

				<p class="form-row form-row-wide <?php if ( get_option( 'cppw_checkout_default_empty' ) == 'yes' ) echo 'validate-required woocommerce-invalid'; ?>" id="cppw_field">
					<label for="cppw_point">
						<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/dpd_230_100.png' ); ?>" width="230" height="100" id="dpd_img"/>
						<?php _e( 'Select the DPD Pickup point', 'portugal-chronopost-pickup-woocommerce' ); ?>
						<span class="cppw-clear"></span>
					</label>
					<?php echo cppw_points_fragment(); ?>
				</p>
				
				<div class="cppw-clear"></div>

			</div>
			<?php
		}
	}

	//Add instructions to the checkout
	function cppw_woocommerce_after_shipping_rate( $method, $index ) {
		$show = false;
		switch ( $method->get_method_id() ) {
			case 'flexible_shipping':
				$options = get_option( 'flexible_shipping_methods_'.$method->get_instance_id(), array() );
				foreach ( $options as $key => $fl_options ) {
					$show = isset( $fl_options['cppw'] ) && ( intval( $fl_options['cppw'] ) == 1 );
				}
				break;
			/*case 'advanced_shipping':
				break;*/
			case 'table_rate':
				$options = get_option( 'woocommerce_table_rate_'.$method->get_instance_id().'_settings', array() );
				$show =  isset( $options['cppw'] ) && intval( $options['cppw'] ) == 1;
				break;
			default:
				$options = get_option( 'woocommerce_'.$method->get_method_id().'_'.$method->get_instance_id().'_settings', array() );
				$show =  isset( $options['cppw'] ) && intval( $options['cppw'] ) == 1;
				break;
		}
		if ( $show ) {
			?>
			<div class="cppw_shipping_method_instructions"><?php echo nl2br( trim( get_option( 'cppw_instructions', '' ) ) ); ?></div>
			<?php
		}
	}

	//Point is locker?
	function cppw_point_is_locker( $point ) {
		return stristr( $point['nome'], 'locker' ); //Big hack dear DPD people... big hack!
	}
	function cppw_point_is_locker_filter( $bool, $point ) {
		return cppw_point_is_locker( $point );
	}

	//Fragment
	function cppw_points_fragment() {
		$postcode = '';
		$country  = '';
		$nearby   = intval( get_option( 'cppw_nearby_points', 10 ) );
		$total    = intval( get_option( 'cppw_total_points', 50 ) );
		if ( isset( $_POST['s_postcode'] ) && trim( $_POST['s_postcode'] )!='' ) {
			$postcode = trim( sanitize_text_field( $_POST['s_postcode'] ) );
		} else {
			if ( isset( WC()->session ) ) {
				if ( $customer = WC()->session->get( 'customer' ) ) {
					$postcode = $customer['shipping_postcode'];
				}
			}
		}
		$postcode = wc_format_postcode( $postcode, 'PT' );
		if ( isset( $_POST['s_country'] ) && trim( $_POST['s_country'] ) != '' ) {
			$country = trim( sanitize_text_field( $_POST['s_country'] ) );
		} else {
			if ( isset( WC()->session ) ) {
				if ( $customer = WC()->session->get( 'customer' ) ) {
					$country = $customer['shipping_country'];
				}
			}
		}
		ob_start();
		?>
		<span class="cppw-points-fragment">
			<?php
			if ( $country == 'PT' ) {
				$points = cppw_get_pickup_points( $postcode );
				if ( is_array( $points ) && count( $points ) > 0 ) {
					//Developers can choose not to show all $points
					$points = apply_filters( 'cppw_available_points', $points, $postcode );
					//Remove lockers from list?
					if ( apply_filters( 'cppw_hide_lockers', false ) ) {
						foreach ( $points as $key => $ponto ) {
							if ( cppw_point_is_locker( $ponto ) ) {
								unset( $points[$key] );
							}
						}
					}
					//Let's do it then
					if ( count( $points ) > 0 ) {
						?>
						<select name="cppw_point" id="cppw_point">
							<?php if ( get_option( 'cppw_checkout_default_empty' ) == 'yes' ) { ?>
								<option value="">- <?php _e( 'Select point', 'portugal-chronopost-pickup-woocommerce' ); ?> -</option>
							<?php } ?>
							<optgroup label="<?php _e( 'Near you', 'portugal-chronopost-pickup-woocommerce' ); ?>">
							<?php
							$i = 0;
							foreach( $points as $ponto ) {
								$i++;
								if ( $i == 1 ) {
									$first = $ponto;
								}
								if ( $i == $nearby + 1 ) {
								?>
							</optgroup>
							<optgroup label="<?php _e( 'Other spots', 'portugal-chronopost-pickup-woocommerce' ); ?>">
								<?php
								}
								?>
								<option value="<?php echo $ponto['number']; ?>">
									<?php echo $ponto['localidade']; ?>
									-
									<?php echo $ponto['nome']; ?>
								</option>
								<?php
								if ( $i == $total ) {
									break;
								}
							}
							?>
							</optgroup>
						</select>
						<input type="hidden" name="cppw_point_active" id="cppw_point_active" value="0"/>
						<?php
						cppw_point_details( get_option( 'cppw_checkout_default_empty' ) == 'yes' ? null : $first );
					} else {
						?>
						<p><strong><?php _e( 'ERROR: No DPD points were found.', 'portugal-chronopost-pickup-woocommerce' ); ?></strong></p>
						<?php
					}
				} else {
					?>
					<p><strong><?php _e( 'ERROR: There are no DPD points in the database. The update process has not yet ended successfully.', 'portugal-chronopost-pickup-woocommerce' ); ?></strong></p>
					<?php
				}
			}
			?>
		</span>
		<?php
		return ob_get_clean();
	}

	//Update select with points on each checkout update
	function cppw_woocommerce_update_order_review_fragments( $fragments ) {
		$fragments['.cppw-points-fragment'] = cppw_points_fragment();
		return $fragments;
	}
	//Each point details
	function cppw_point_details( $point ) {
		if ( $point ) {
			$mapbox_public_token = trim( get_option( 'cppw_mapbox_public_token', '' ) );
			$google_api_key = trim( get_option( 'cppw_google_api_key', '' ) );
			$map_width = intval( apply_filters( 'cppw_map_width', 80 ) );
			$map_height = intval( apply_filters( 'cppw_map_height', 80 ) );
			$img_html = '<!-- No map because neither Mapbox public token or Google Maps API Key are filled in -->';
			if ( trim( $mapbox_public_token ) != '' ) {
					$img_html = sprintf(
						'<img src="https://api.mapbox.com/styles/v1/mapbox/streets-v10/static/pin-s+FF0000(%s,%s)/%s,%s,%d,0,0/%dx%d%s?access_token=%s" width="%d" height="%d"/>',
						esc_attr( trim( $point['gps_lon'] ) ),
						esc_attr( trim( $point['gps_lat'] ) ),
						esc_attr( trim( $point['gps_lon'] ) ),
						esc_attr( trim( $point['gps_lat'] ) ),
						apply_filters( 'cppw_map_zoom', 10 ),
						$map_width,
						$map_height,
						intval( apply_filters( 'cppw_map_scale', 2 ) == 2 ) ? '@2x' : '',
						esc_attr( $mapbox_public_token ),
						$map_width,
						$map_height
					);
			} elseif ( trim( $google_api_key ) != '' ) {
					$img_html = sprintf(
						'<img src="https://maps.googleapis.com/maps/api/staticmap?center=%s,%s&amp;markers=%s,%s&amp;size=%dx%d&amp;scale=%d&amp;zoom=%d&amp;language=%s&amp;key=%s" width="%d" height="%d"/>',
						esc_attr( trim( $point['gps_lat'] ) ),
						esc_attr( trim( $point['gps_lon'] ) ),
						esc_attr( trim( $point['gps_lat'] ) ),
						esc_attr( trim( $point['gps_lon'] ) ),
						$map_width,
						$map_height,
						intval( apply_filters( 'cppw_map_scale', 2 ) == 2 ) ? 2 : 1,
						apply_filters( 'cppw_map_zoom', 11 ),
						esc_attr( get_locale() ),
						esc_attr( $google_api_key ),
						$map_width,
						$map_height
					);
			}
			?>
			<span class="cppw-points-fragment-point-details">
				<span id="cppw-points-fragment-point-details-address">
					<span id="cppw-points-fragment-point-details-map">
						<a href="https://www.google.pt/maps?q=<?php echo esc_attr( trim( $point['gps_lat'] ) ); ?>,<?php echo esc_attr( trim( $point['gps_lon'] ) ); ?>" target="_blank">
							<?php echo $img_html; ?>
						</a>
					</span>
					<strong><?php echo $point['nome']; ?></strong>
					<br/>
					<?php echo $point['morada1']; ?>
					<br/>
					<?php echo $point['cod_postal']; ?>
					<?php echo $point['localidade']; ?>
					<?php if ( get_option( 'cppw_display_phone', 'yes' ) == 'yes' || get_option( 'cppw_display_schedule', 'yes' ) == 'yes' ) { ?>
						<small>
							<?php if ( get_option( 'cppw_display_phone', 'yes' ) == 'yes' && isset( $point['telefone'] ) && trim( $point['telefone'] ) != '' ) { ?>
								<br/>
								<?php _e( 'Phone:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['telefone']; ?>
							<?php } ?>
							<?php if ( get_option( 'cppw_display_schedule', 'yes' ) == 'yes' ) { ?>
								<?php if ( isset( $point['horario_semana'] ) && trim( $point['horario_semana'] ) != '' ) { ?>
									<br/>
									<?php _e( 'Work days:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_semana']; ?>
								<?php } ?>
								<?php if ( isset( $point['horario_sabado'] ) && trim( $point['horario_sabado'] ) != '' ) { ?>
									<br/>
									<?php _e( 'Saturday:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_sabado']; ?>
								<?php } ?>
								<?php if ( isset( $point['horario_domingo'] ) && trim( $point['horario_domingo'] ) != '' ) { ?>
									<br/>
									<?php _e( 'Sunday:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_domingo']; ?>
								<?php } ?>
							<?php } ?>
						</small>
					<?php } ?>
				</span>
				<span class="cppw-clear"></span>
				<input type="hidden" id="cppw_point_active_is_locker" value="<?php echo cppw_point_is_locker( $point ) ? 1 : 0; ?>"/>
			</span>
			<?php
		} else {
			?>
			<span class="cppw-points-fragment-point-details">
				<!-- empty -->
				<span class="cppw-clear"></span>
			</span>
			<?php
		}
	}
	//Each point details - AJAX
	function wc_ajax_cppw_point_details() {
		$fragments = array();
		if ( isset( $_POST['cppw_point'] ) ) {
			$cppw_point = trim( sanitize_text_field( $_POST['cppw_point'] ) );
			$points = cppw_get_pickup_points();
			if ( isset( $points[$cppw_point] ) ) {
				ob_start();
				cppw_point_details( $points[$cppw_point] );
				$fragments = array( 
					'.cppw-points-fragment-point-details' => ob_get_clean(),
				 );
			}
		}
		if ( count( $fragments ) == 0 ) {
			ob_start();
			cppw_point_details( null );
			$fragments = array( 
				'.cppw-points-fragment-point-details' => ob_get_clean(),
			);
		}
		wp_send_json( array( 
			'fragments' => $fragments
		 ) );
	}

	//Validate if point should be there and stop the checkout (if option true and active and empty field -> Error)
	function cppw_woocommerce_after_checkout_validation( $fields, $errors ) {
		if ( get_option( 'cppw_checkout_default_empty' ) == 'yes' ) {
			if ( isset( $_POST['cppw_point'] ) && ( trim( $_POST['cppw_point'] ) == '' ) && isset( $_POST['cppw_point_active'] ) && ( intval( $_POST['cppw_point_active'] ) == 1 ) ) {
				$errors->add(
					'cppw_point_validation',
					__( 'You need to select a <strong>DPD Pickup point</strong>.', 'portugal-chronopost-pickup-woocommerce' ),
					array( 'id' => 'cppw_point' )
				);
			}
		}
	}


	//Save chosen point to the order
	function cppw_save_extra_order_meta( $order_id ) {
		if ( isset( $_POST['cppw_point'] ) && ( trim( $_POST['cppw_point'] ) != '' ) && isset( $_POST['cppw_point_active'] ) && ( intval( $_POST['cppw_point_active'] ) == 1 ) ) {
			$cppw_point = trim( sanitize_text_field( $_POST['cppw_point'] ) );
			$order = new WC_Order( $order_id );
			$cppw_shipping_methods = cppw_get_shipping_methods();
			$order_shipping_method = $order->get_shipping_methods();
			$save = false;
			foreach( $order_shipping_method as $method ) {
				switch ( $method['method_id'] ) {
					case 'flexible_shipping':
						$options = get_option( 'flexible_shipping_methods_'.$method['instance_id'], array() );
						foreach ( $options as $key => $fl_options ) {
							if ( isset( $fl_options['cppw'] ) && intval( $fl_options['cppw'] ) == 1 && in_array( $method['method_id'].'_'.$method['instance_id'].'_'.$fl_options['id'], $cppw_shipping_methods ) ) {
								$save = true;
							}
						}
						break;
					case 'advanced_shipping':
						//We'll trust on intval( $_POST['cppw_point_active'] ) ==  1 because we got no way to identify which of the Advanced Shipping rules was used
						$save = true;
						break;
					case 'table_rate':
						$options = get_option( 'woocommerce_table_rate_'.$method['instance_id'].'_settings', array() );
						if ( isset( $options['cppw'] ) && intval( $options['cppw'] ) == 1 ) $save = true;
						break;
					case 'betrs_shipping':
						$options_instance = get_option( 'betrs_shipping_options-'.$method['instance_id'], array() );
						if ( isset( $options_instance['settings'] ) && is_array( $options_instance['settings'] ) ) {
							foreach ( $options_instance['settings'] as $setting ) {
								if ( isset( $setting['option_id'] ) && in_array( $method['method_id'].':'.$method['instance_id'].'-'.$setting['option_id'], $cppw_shipping_methods ) ) {
									$save = true;
									break;
								}
							}
						}
						break;
					default:
						//Others
						if ( in_array( $method['method_id'], $cppw_shipping_methods ) || in_array( $method['method_id'].':'.$method['instance_id'], $cppw_shipping_methods ) ) {
							$save = true;
						}
						break;
				}
				break; //Only one shipping method supported
			}
			if ( $save ) {
				//Save order meta
				$order->update_meta_data( 'cppw_point', $cppw_point );
				$order->save();
			}
		}
	}


	//Show chosen point at the order screen
	function cppw_woocommerce_admin_order_data_after_shipping_address( $order ) {
		$cppw_point = $order->get_meta( 'cppw_point' );
		if ( trim( $cppw_point )!='' ) {
			?>
			<h3><?php _e( 'DPD Pickup point', 'portugal-chronopost-pickup-woocommerce' ); ?></h3>
			<p><strong><?php echo $cppw_point; ?></strong></p>
			<?php
			$points = cppw_get_pickup_points();
			if ( isset( $points[trim( $cppw_point )] ) ) {
				$point = $points[trim( $cppw_point )];
				cppw_point_information( $point, false, true, true );
			} else {
				?>
				<p><?php _e( 'Unable to find point on the database', 'portugal-chronopost-pickup-woocommerce' ); ?></p>
				<?php
			}
		}
	}


	//Check if points are still not loaded on admin
	function cppw_admin_notices() {
		global $pagenow;
		if ( $pagenow=='admin.php' && isset($_GET['page']) && trim($_GET['page'])=='wc-settings' ) {
			$points = cppw_get_pickup_points();
			if ( count($points)==0 ) {
				if ( isset($_GET['cppw_force_update']) ) {
					if ( cppw_update_pickup_list_function() ) {
						?>
						<div class="notice notice-success">
							<p><?php _e( 'DPD Pickup points updated.', 'portugal-chronopost-pickup-woocommerce' ); ?></p>
						</div>
						<?php
					} else {
						?>
						<div class="notice notice-error">
							<p><?php _e( 'It was not possible to update the DPD Pickup points.', 'portugal-chronopost-pickup-woocommerce' ); ?></p>
						</div>
						<?php
					}
				} else {
					?>
					<div class="notice notice-error">
						<p><?php _e( 'ERROR: There are no DPD points in the database. The update process has not yet ended successfully.', 'portugal-chronopost-pickup-woocommerce' ); ?></p>
						<p><a href="admin.php?page=wc-settings&amp;cppw_force_update"><strong><?php _e( 'Click here to force the update process', 'portugal-chronopost-pickup-woocommerce' ); ?></strong></a></p>
					</div>
					<?php
				}
			}
		}
	}


	//Update points from Chronopost webservice
	function cppw_update_pickup_list_function() {
		$urls = array(
			'https://webservices.chronopost.pt:7554/PUDOPoints/rest/PUDOPoints/Country/PT',
			'https://chronopost.webdados.pt/webservice_proxy.php',
			//'https://chronopost.kaksimedia.com/webservice_proxy.php', //2023-01-17 - No longer exists
		);
		shuffle( $urls ); //Random order
		$args = array( 
			'headers'	=> array( 
				'Accept' => 'application/json',
			 ),
			'sslverify'	=> false,
			'timeout' => 25,
		);
		update_option( 'cppw_points_last_update_try_datetime', date_i18n( 'Y-m-d H:i:s' ), false );
		$done = false;
		foreach ( $urls as $url ) {
			$response = wp_remote_get( $url, $args );
			if( ( !is_wp_error( $response ) ) && is_array( $response ) && $response['response']['code']=='200' ) {
				if ( $body = json_decode( $response['body'] ) ) {
					if ( is_array( $body->B2CPointsArr ) && count($body->B2CPointsArr)>1 ) {
						$points = array();
						foreach( $body->B2CPointsArr as $point ) {
							$points[ trim( $point->number ) ] = array( 
								'number'          => cppw_fix_spot_text( $point->number ),
								'nome'            => cppw_fix_spot_text( $point->name ),
								'morada1'         => cppw_fix_spot_text( $point->address ),
								'cod_postal'      => cppw_fill_postcode( $point->postalCode ),
								'localidade'      => cppw_fix_spot_text( $point->postalCodeLocation ),
								'gps_lat'         => cppw_fix_spot_text( $point->latitude ),
								'gps_lon'         => cppw_fix_spot_text( $point->longitude ),
								'telefone'        => cppw_fix_spot_text( $point->phoneNumber ),
								'horario_semana'  => cppw_get_spot_schedule( $point, '2' ),
								'horario_sabado'  => cppw_get_spot_schedule( $point, 'S' ),
								'horario_domingo' => cppw_get_spot_schedule( $point, 'D' ),
							);
						}
						update_option( 'cppw_points', $points, false );
						update_option( 'cppw_points_last_update_datetime', date_i18n( 'Y-m-d H:i:s' ), false );
						update_option( 'cppw_points_last_update_server', $url, false );
						$done = true;
						return true;
						break;
					} else {
						if ( apply_filters( 'cppw_update_pickup_list_error_log', false ) ) {
							error_log( '[DPD Portugal Pickup WooCommerce] It was not possible to get the points update: no points array in response ('.$url.')' );
						}
					}
				} else {
					if ( apply_filters( 'cppw_update_pickup_list_error_log', false ) ) {
						error_log( '[DPD Portugal Pickup WooCommerce] It was not possible to get the points update: no body in response ('.$url.')' );
					}
				}
			} else {
				if ( apply_filters( 'cppw_update_pickup_list_error_log', false ) ) {
					error_log( '[DPD Portugal Pickup WooCommerce] It was not possible to get the points update via webservice: ('.$url.') '.(  is_wp_error( $response ) ? print_r( $response, true ) : 'unknown error' ) );
				}
			}
		}
		if ( $done ) {
			//NICE!
		} else {
			//FTP fallback
			$ftp_error = true;
			if ( $conn_id = ftp_connect( 'ftp.chronopost.pt' ) ) {
				if ( ftp_login( $conn_id, 'pickme', 'pickme' ) ) {
					ftp_pasv( $conn_id, true );
					$h = fopen('php://temp', 'r+');
					if ( ftp_fget( $conn_id, $h, 'lojaspickme.txt', FTP_ASCII, 0 ) ) {
						$fstats = fstat( $h );
						fseek( $h, 0 );
						$contents = fread( $h, $fstats['size'] );
						fclose( $h );
						ftp_close( $conn_id );
						if ( trim($contents)!='' ) {
							$contents = utf8_encode( $contents );
							$temp_points = explode( PHP_EOL, $contents );
							if ( count( $temp_points ) > 1 ) {
								$ftp_error = false;
								$points = array();
								foreach( $temp_points as $temp_point ) {
									$temp_point = trim( $temp_point );
									if ( $temp_point!='' ) {
										$point_number = substr( $temp_point, 0, 5 );
										if ( trim( $point_number )!='' ) {
											$points[$point_number] = array( 
												'number'          => cppw_fix_spot_text( $point_number ),
												'nome'            => cppw_fix_spot_text( substr($temp_point, 5, 32) ),
												'morada1'         => cppw_fix_spot_text( substr($temp_point, 37, 64) ),
												'cod_postal'      => cppw_fill_postcode( substr($temp_point, 101, 10) ),
												'localidade'      => cppw_fix_spot_text( substr($temp_point, 111, 26) ),
												'gps_lat'         => cppw_fix_spot_text( substr($temp_point, 137, 9) ),
												'gps_lon'         => cppw_fix_spot_text( substr($temp_point, 146, 9) ),
												//No phone or schedule via FTP - We leave it blank to avoid notices
												'telefone'        => '',
												'horario_semana'  => '',
												'horario_sabado'  => '',
												'horario_domingo' => '',
											);
										}
									}
								}
								update_option( 'cppw_points', $points, false );
								return true;
							}
						}
					}
					
				}
			}
			if ( $ftp_error && apply_filters( 'cppw_update_pickup_list_error_log', false ) ) {
				error_log( '[DPD Portugal Pickup WooCommerce] It was not possible to get the points update via ftp' );
			}
			return false;
		}
	}
	//Fix text
	function cppw_fix_spot_text( $string ) {
		$string = strtolower( $string );
		$string = ucwords( $string );
		$org = array( 'Ç', ' Da ', ' De ', ' Do ', 'Ii', ' E ', 'dpd' );
		$rep = array( 'ç', ' da ', ' de ', ' do ', 'II', ' e ', 'DPD' );
		$string = str_ireplace( $org, $rep, $string );
		return trim( $string );
	}
	//Fix postcode
	function cppw_fill_postcode( $cp ) {
		$cp = trim( $cp );
		//Até 4
		if ( strlen( $cp )<4 ) {
			$cp=str_pad( $cp,4,'0' );
		}
		if ( strlen( $cp )==4 ) {
			$cp.='-';
		}
		if ( strlen( $cp )<8 ) {
			$cp=str_pad( $cp,8,'0' );
		}
		return trim( $cp );
	}
	//Fix schedule
	function cppw_get_spot_schedule( $point, $day ) {
		$morningOpenHour = 'morningOpenHour'.$day;
		$morningCloseHour = 'morningCloseHour'.$day;
		$afterNoonOpenHour = 'afterNoonOpenHour'.$day;
		$afterNoonCloseHour = 'afterNoonCloseHour'.$day;
		if ( $point->{$morningOpenHour} != '0' && $point->{$morningCloseHour} != '0' ) {
			$horario = cppw_fix_spot_schedule( $point->{$morningOpenHour} );
			if ( $point->{$morningCloseHour} == $point->{$afterNoonOpenHour} ) { //No closing for lunch
				$horario .='-'.cppw_fix_spot_schedule( $point->{$afterNoonCloseHour} );
			} else {
				if ( $point->{$afterNoonOpenHour} != '0' && $point->{$afterNoonCloseHour} != '0' ) {
					$horario .= '-'.cppw_fix_spot_schedule( $point->{$morningCloseHour} ). ', '.cppw_fix_spot_schedule( $point->{$afterNoonOpenHour} ).'-'.cppw_fix_spot_schedule( $point->{$afterNoonCloseHour} );
				} else {
					$horario .= '-'.cppw_fix_spot_schedule( $point->{$morningCloseHour} );
				}
			}
		} else {
			$horario = '';
		}
		return $horario;
	}
	function cppw_fix_spot_schedule( $string ) {
		$minutos = trim( substr( $string , -2 ) );
		$horas = trim( substr( $string , 0, -2) );
		if ( strlen( $horas ) == 1 ) $horas = '0'.$horas;
		return trim( $horas.':'.$minutos );
	}


	//Daily cron to update points list
	function cppw_cronstarter_activation() {
		if( ! wp_next_scheduled( 'cppw_update_pickup_list' ) ) {
			//Schedule
			wp_schedule_event( time(), 'daily', 'cppw_update_pickup_list' );
			//And run now - just in case
			do_action( 'cppw_update_pickup_list' );
		}
	}
	//Deactivate cron on plugin deactivation
	function cppw_cronstarter_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled( 'cppw_update_pickup_list' );
		// unschedule previous event if any
		wp_unschedule_event( $timestamp, 'cppw_update_pickup_list' );
	}


	//Get all points from the database
	function cppw_get_pickup_points( $postcode = '' ) {
		$points = get_option( 'cppw_points', array() );
		if ( is_array( $points ) && count( $points ) > 0 ) {
			//SORT by postcode ?
			if ( $postcode != '' ) {
				$postcode = cppw_fill_postcode( $postcode );
				$postcode = intval( str_replace( '-', '', $postcode ) );
				$points_sorted = array();
				$cp_order = array();
				//Sort by post code mathematically
				foreach( $points as $key => $ponto ) {
						$diff=abs( $postcode-intval( str_replace( '-', '', $ponto['cod_postal'] ) ) );
						$points_sorted[$key]=$ponto;
						$points_sorted[$key]['cp_order']=$diff;
						$cp_order[$key]=$diff;
				}
				array_multisort( $cp_order, SORT_ASC, $points_sorted );
				//Now by GPS distance
				$pontos2 = array();
				$distancia = array();
				foreach( $points_sorted as $ponto ) {
					$gps_lat = $ponto['gps_lat'];
					$gps_lon = $ponto['gps_lon'];
					break;
				}
				$i = 0;
				foreach( $points_sorted as $key => $ponto ) {
					if ( $i == 0 ) {
						$points_sorted[$key]['distancia'] = 0.0;
						$distancia[$key]['distancia'] = 0.0;
					} else {
						$points_sorted[$key]['distancia'] = cppw_gps_distance( $gps_lat, $gps_lon, $ponto['gps_lat'], $ponto['gps_lon'] );
						$distancia[$key]['distancia'] = cppw_gps_distance( $gps_lat, $gps_lon, $ponto['gps_lat'], $ponto['gps_lon'] );
					}
					$i++;
				}
				array_multisort( $distancia, SORT_ASC, $points_sorted );
				return $points_sorted;
			} else {
				return $points;
			}
		} else {
			return array();
		}
	}
	//Points distance by GPS
	function cppw_gps_distance( $lat1, $lon1, $lat2, $lon2 ) {
		$lat1 = floatval($lat1);
		$lon1 = floatval($lon1);
		$lat2 = floatval($lat2);
		$lon2 = floatval($lon2);
		$theta = $lon1 - $lon2;
		$dist = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) +  cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
		$dist = acos( $dist );
		$dist = rad2deg( $dist );
		$miles = $dist * 60 * 1.1515;
		return ( $miles * 1.609344 ); //Km
	}


	//Plugin settings
	function cppw_woocommerce_shipping_settings( $settings ) {
		$updated_settings = array();
		foreach ( $settings as $section ) {
			if ( isset( $section['id'] ) && 'shipping_options' == $section['id'] && isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
				$updated_settings[] = array( 
					'title'		=> __( 'DPD Pickup network in Portugal', 'portugal-chronopost-pickup-woocommerce' ),
					'desc'		=> __( 'Total of points to show', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_total_points',
					'default'	=> 50,
					'type'		=> 'number',
					'autoload'	=> false,
					'css'		=> 'width: 60px;',
				 );
				$updated_settings[] = array( 
					'desc'		=> __( 'Near by points to show', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_nearby_points',
					'default'	=> 10,
					'type'		=> 'number',
					'autoload'	=> false,
					'css'		=> 'width: 60px;',
				 );
				$updated_settings[] = array( 
					'desc'		=> __( 'Do not pre-select a point in the DPD Pickup field and force the client to choose it', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_checkout_default_empty',
					'default'	=> 0,
					'type'		=> 'checkbox',
					'autoload'	=> false,
				);
				$updated_settings[] = array( 
					'desc'		=> __( 'Instructions for clients', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_instructions',
					'default'	=> __( 'Pick up your order in one of the more than 600 DPD Pickup points available in Portugal mainland', 'portugal-chronopost-pickup-woocommerce' ),
					'desc_tip'	=> __( 'If you are using the mixed service, you should use this field to inform the client the Pickup point will only be used if DPD fails to deliver the order on the shipping address', 'portugal-chronopost-pickup-woocommerce' ),
					'type'		=> 'textarea',
					'autoload'	=> false,
				);
				$updated_settings[] = array( 
					'desc'		=> __( 'Mapbox Public Token (recommended)', 'portugal-chronopost-pickup-woocommerce' ).' (<a href="https://www.mapbox.com/account/access-tokens" target="_blank">'.__( 'Get one', 'portugal-chronopost-pickup-woocommerce' ).'</a>)',
					'desc_tip'	=> __( 'Go to your Mapbox account and get a Public Token, if you want to use this service static maps instead of Google Maps', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_mapbox_public_token',
					'default'	=> '',
					'type'		=> 'text',
					'autoload'	=> false,
					'css'		=> 'min-width: 350px;',
				);
				$updated_settings[] = array( 
					'desc'		=> __( 'Google Maps API Key', 'portugal-chronopost-pickup-woocommerce' ).' (<a href="https://developers.google.com/maps/documentation/maps-static/get-api-key" target="_blank">'.__( 'Get one', 'portugal-chronopost-pickup-woocommerce' ).'</a>)',
					'desc_tip'	=> __( 'Go to the Google APIs Console and create a project, then go to the Static Maps API documentation website and click on Get a key, choose your project and generate a new key (if the Mapbox public token is filled in, this will be ignored and can be left blank)', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_google_api_key',
					'default'	=> '',
					'type'		=> 'text',
					'autoload'	=> false,
					'css'		=> 'min-width: 350px;',
				);
				$updated_settings[] = array(
					'desc'		=> __( 'Add DPD Pickup point information on emails sent to the customer and order details on the "My Account" page', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_email_info',
					'default'	=> 1,
					'type'		=> 'checkbox',
					'autoload'	=> false,
				);
				$updated_settings[] = array( 
					'desc'		=> __( 'Hide shipping address on order details and emails sent to the customer', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_hide_shipping_address',
					'default'	=> 1,
					'type'		=> 'checkbox',
					'autoload'	=> false,
				);
				$updated_settings[] = array(
					'desc'		=> __( 'Display the DPD Pickup point phone number (if available) on the checkout', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_display_phone',
					'default'	=> 1,
					'type'		=> 'checkbox',
					'autoload'	=> false,
				);
				$updated_settings[] = array(
					'desc'		=> __( 'Display the DPD Pickup point opening/closing hours (if available) on the checkout', 'portugal-chronopost-pickup-woocommerce' ),
					'id'		=> 'cppw_display_schedule',
					'default'	=> 1,
					'type'		=> 'checkbox',
					'autoload'	=> false,
				);
			}
			$updated_settings[] = $section;
		}
		return $updated_settings;
	}

	//Information basics
	function cppw_point_information( $point, $plain_text = false, $echo = true, $order_screen = false ) {
		ob_start();
		?>
		<p>
			<?php echo $point['nome']; ?>
			<br/>
			<?php echo $point['morada1']; ?>
			<br/>
			<?php echo $point['cod_postal']; ?> <?php echo $point['localidade']; ?>
			<?php if ( get_option( 'cppw_display_phone', 'yes' ) == 'yes' || get_option( 'cppw_display_schedule', 'yes' ) == 'yes' ) { ?>
				<small>
					<?php if ( get_option( 'cppw_display_phone', 'yes' ) == 'yes' && trim( $point['telefone'] ) != '' ) { ?>
						<br/>
						<?php _e( 'Phone:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['telefone']; ?>
					<?php } ?>
					<?php if ( get_option( 'cppw_display_schedule', 'yes' ) == 'yes' ) { ?>
						<?php if ( trim( $point['horario_semana'] ) != '' ) { ?>
							<br/>
							<?php _e( 'Work days:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_semana']; ?>
						<?php } ?>
						<?php if ( trim( $point['horario_sabado'] ) != '' ) { ?>
							<br/>
							<?php _e( 'Saturday:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_sabado']; ?>
						<?php } ?>
						<?php if ( trim( $point['horario_domingo'] ) != '' ) { ?>
							<br/>
							<?php _e( 'Sunday:', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $point['horario_domingo']; ?>
						<?php } ?>
					<?php } ?>
				</small>
			<?php } ?>
		</p>
		<?php
		$html = ob_get_clean();
		if ( $plain_text ) {
			$html = strip_tags( str_replace( "\t", '', $html ) ) . "\n";
			$html = "\n" . strtoupper( __( 'DPD Pickup point', 'portugal-chronopost-pickup-woocommerce' ) ) . "\n" . $point['number'] . "\n" . $html;
		} else {
			if ( ! $order_screen ) {
				$html = '<h2>'.__( 'DPD Pickup point', 'portugal-chronopost-pickup-woocommerce' ).'</h2><p><strong>'.$point['number'].'</strong></p>' . $html;
			}
		}
		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
	//Information on emails
	function cppw_woocommerce_email_customer_details( $order, $sent_to_admin = false, $plain_text = false ) {
		$cppw_point = $order->get_meta( 'cppw_point' );
		if ( trim( $cppw_point )!='' ) {
			$points = cppw_get_pickup_points();
			if ( isset( $points[trim( $cppw_point )] ) ) {
				$point = $points[trim( $cppw_point )];
				cppw_point_information( $point, $plain_text );
			}
		}
	}
	//Information on the order details
	function cppw_woocommerce_order_details_after_order_table( $order ) {
		$cppw_point = $order->get_meta( 'cppw_point' );
		if ( trim( $cppw_point )!='' ) {
			$points = cppw_get_pickup_points();
			if ( isset( $points[trim( $cppw_point )] ) ) {
				$point = $points[trim( $cppw_point )];
				?>
				<section>
					<?php cppw_point_information( $point ); ?>
				</section>
				<?php
			}
		}
	}
	//Information on the admin order preview
	function cppw_woocommerce_admin_order_preview_end() {
		?>
		{{{ data.cppw_info }}}
		<?php
	}
	function cppw_woocommerce_admin_order_preview_get_order_details( $data, $order ) {
		$data['cppw_info'] = '';
		$cppw_point = $order->get_meta( 'cppw_point' );
		if ( trim( $cppw_point )!='' ) {
			$points = cppw_get_pickup_points();
			if ( isset( $points[trim( $cppw_point )] ) ) {
				$point = $points[trim( $cppw_point )];
				ob_start();
				?>
				<div class="wc-order-preview-addresses">
					<div class="wc-order-preview-note">
						<?php cppw_point_information( $point ); ?>
					</div>
				</div>
				<?php
				$data['cppw_info'] = ob_get_clean();
			}
		}
		return $data;
	}

	//Hide shipping address
	function cppw_woocommerce_order_needs_shipping_address( $needs_address, $hide, $order ) {
		$cppw_point = $order->get_meta( 'cppw_point' );
		if ( trim( $cppw_point ) != '' ) {
			$needs_address = false;
		}
		return $needs_address;
	}

	//Change order table shipping address
	function cppw_manage_shop_order_custom_column( $column_name, $postid_or_order ) {
		if ( $column_name == 'shipping_address' ) {
			$order = is_a( $postid_or_order, 'WC_Order' ) ? $postid_or_order : wc_get_order( $postid_or_order );
			$cppw_point = $order->get_meta( 'cppw_point' );
			if ( trim( $cppw_point ) != '' ) {
				?>
				<style type="text/css">
					#order-<?php echo $order->get_id(); ?> .column-shipping_address a,
					#post-<?php echo $order->get_id(); ?> .column-shipping_address a {
						display: none;
					}
				</style>
				<p>
					<?php _e( 'DPD Pickup point', 'portugal-chronopost-pickup-woocommerce' ); ?> <?php echo $cppw_point; ?>
					<br/>
					<?php
					$points = cppw_get_pickup_points();
					if ( isset( $points[trim( $cppw_point )] ) ) {
						$point = $points[trim( $cppw_point )];
						
						echo $point['nome']; ?>
						<br/>
						<?php echo $point['morada1']; ?>
						<br/>
						<?php echo $point['cod_postal']; ?> <?php echo $point['localidade'];
					} else {
						_e( 'Unable to find point on the database', 'portugal-chronopost-pickup-woocommerce' );
					}
					?>
				</p>
				<?php
			}
		}
	}



	/* DPD Portugal for WooCommerce nag */
	add_action( 'admin_init', function() {
		if (
			( ! defined( 'WEBDADOS_DPD_PRO_NAG' ) )
			&&
			( ! class_exists( 'Woo_DPD_Portugal' ) )
			&&
			empty( get_transient( 'webdados_dpd_portugal_pro_nag' ) )
		) {
			define( 'WEBDADOS_DPD_PRO_NAG', true );
			require_once( 'pro_nag/pro_nag.php' );
		}
		if (
			( ! defined( 'WEBDADOS_DPD_PICKUP_PRO_NAG' ) )
			&&
			( ! class_exists( 'Woo_DPD_Pickup' ) )
			&&
			empty( get_transient( 'webdados_dpd_pickup_pro_nag' ) )
		) {
			define( 'WEBDADOS_DPD_PICKUP_PRO_NAG', true );
			require_once( 'pro_nag/pro_pickup_nag.php' );
		}
	} );

}

/* HPOS Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );


/* If you’re reading this you must know what you’re doing ;- ) Greetings from sunny Portugal! */

