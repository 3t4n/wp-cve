<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, methods that enqueue the js and css files, and methods that visualy impat the public-facing of the website.
 *
 * @package    Cargus
 * @subpackage Cargus/public
 * @author     Cargus <contact@cargus.ro>
 */
class Cargus_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->hooks();
	}

	/**
	 * Load hooks.
	 *
	 * @since    1.0.0
	 */
	public function hooks() {
		/** Load all neccessary dependencies */
		add_action( 'wp_loaded', array( $this, 'load_dependencies' ) );
	}

	/**
	 * Include the cargus shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function load_dependencies() {

		/**
		 * The class responsible for creating the Cargus api connection.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cargus-api.php';
		/**
		 * The class responsible for creating cargus locations cache.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cargus-cache.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cargus_enqueue_styles() {

		if ( is_cart() || is_checkout() ) {
			wp_enqueue_style(
				'bootstrap-custom',
				plugin_dir_url( __FILE__ ) . 'css/bootstrap/bootstrap-custom.css',
				array(),
				'5.2.3',
				'all'
			);

			wp_enqueue_style(
				'ship_and_go',
				plugin_dir_url( __FILE__ ) . 'cargus-widget/assets/style/all.min.css',
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_style(
				'cargus_widget',
				plugin_dir_url( __FILE__ ) . 'cargus-widget/assets/style/CargusWidget.css',
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_style(
				'leaflet',
				plugin_dir_url( __FILE__ ) . 'cargus-widget/assets/style/leaflet.min.css',
				array(),
				$this->version,
				'all'
			);
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cargus_enqueue_scripts() {

		$cargus_options = get_option( 'woocommerce_cargus_settings' );

		if ( ( is_cart() || is_checkout() ) ) {
            wp_enqueue_script(
                'bootstrap',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/bootstrap.bundle.min.js',
                array( 'jquery' ),
                '5.3.0',
                false
            );

            wp_enqueue_script(
                'fuse',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/fuse.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'hyperlist',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/hyperlist.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'leaflet',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/leaflet.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'leaflet_canvas_markers',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/leaflet-canvas-markers.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'lodash',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/lodash.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'turf',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/lib/turf.min.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'cargus_ship_and_go_widget',
                plugin_dir_url( __FILE__ ) . 'cargus-widget/carguswidget.js',
                array( 'jquery', 'fuse', 'hyperlist', 'leaflet', 'leaflet_canvas_markers', 'lodash', 'turf' ),
                $this->version,
                false
            );

            wp_enqueue_script(
                'cargus_ship_and_go',
                plugin_dir_url( __FILE__ ) . 'js/cargus-ship-and-go.js',
                array( 'jquery', 'cargus_ship_and_go_widget', 'bootstrap' ),
                $this->version,
                false
            );

            wp_localize_script(
                'cargus_ship_and_go',
                'ajax_var_ship_and_go',
                array(
                    'url'        => admin_url( 'admin-ajax.php' ),
                    'pluginURL'  => plugin_dir_url( dirname( __FILE__ ) ),
                    'nonce'      => wp_create_nonce( 'ajax_cargus_nonce' ),
                    'pudoPoints' => plugin_dir_url( dirname( __FILE__ ) ) . 'admin/locations/pudo_locations.json',
                    'assetsPath' => plugin_dir_url( __FILE__ ) . 'cargus-widget/assets/icons',
                )
            );
		}

		if ( ( is_cart() || is_checkout() || is_account_page() ) ) {
            wp_enqueue_script(
                'cargus',
                plugin_dir_url( __FILE__ ) . 'js/cargus.js',
                array( 'jquery' ),
                $this->version,
                false
            );

            wp_localize_script(
                'cargus',
                'ajax_var',
                array(
                    'url'                 => admin_url( 'admin-ajax.php' ),
                    'locationsDropdown'   => 'yes' === $cargus_options['locations-select'],
                    'streetDropdown'      => ! isset( $cargus_options['street-select'] ) || 'yes' === $cargus_options['street-select'],
                    'fixedPrice'          => ( $cargus_options['fixed'] && '' !== $cargus_options['fixed'] ) ? $cargus_options['fixed'] : '',
                    'fixedPriceBucharest' => ( $cargus_options['buc_fixed'] && '' !== $cargus_options['buc_fixed'] ) ? $cargus_options['buc_fixed'] : '',
                    'freeShippingPrice'   => ( $cargus_options['free'] && '' !== $cargus_options['free'] ) ? $cargus_options['free'] : '',
                    'nonce'               => wp_create_nonce( 'ajax_cargus_nonce' ),
                    'cartSubtotal'        => WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax(),
                )
            );
		}

        if ( is_account_page() ) {
	        wp_enqueue_script(
		        'qr-creator',
		        plugin_dir_url( __FILE__ ) . 'js/jquery.qrcode.min.js',
		        array( 'jquery' ),
		        $this->version,
		        false
	        );

	        wp_enqueue_script(
		        'cargus-my-account',
		        plugin_dir_url( __FILE__ ) . 'js/cargus-my-account.js',
		        array( 'jquery', 'qr-creator' ),
		        $this->version,
		        false
	        );

        }
	}

	/**
	 * Get and display the county regions.
	 *
	 * @since 1.0.0
	 */
	public function cargus_get_cities() {

		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		if ( isset( $_POST['type'] ) && 'SELECT' === $_POST['type'] ) {
			$options = array( '-' => array( '0', null ) );
		}

		if ( isset( $_POST['judet'] ) && '' !== $_POST['judet'] ) {
			// generez options pentru dropdown.
			$cargus = new Cargus_Api();
			if ( property_exists( $cargus, 'token' ) && ! is_array( $cargus->token ) && ! is_object( $cargus->token ) ) {
				// obtin lista de judete din fisier json.
				$counties_json = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/locations/counties.json' );
				// Convert to array.
				$counties     = json_decode( $counties_json, true );
				$cargus_cache = new Cargus_Cache();
				foreach ( $counties as $county ) {
					if ( trim( addslashes( sanitize_text_field( $_POST['judet'] ) ) ) === $county['Abbreviation'] ) { //phpcs:ignore
						$localities = $cargus_cache->get_localities( $county['CountyId'] ); //phpcs:ignore
						foreach ( json_decode( $localities ) as $locality ) { //phpcs:ignore
							$options[ $locality->Name ] = array( $locality->InNetwork ? 0 : ( ! $locality->ExtraKm ? 0 : $locality->ExtraKm ), $locality->LocalityId, $locality->Name, $locality->PostalCode, $locality->SaturdayDelivery );//phpcs:ignore
						}
						break;
					}
				}
				echo wp_json_encode( $options );
			}

			wp_die();
		}
	}

	/**
	 * Get and display the county regions.
	 *
	 * @since 1.0.0
	 */
	public function cargus_get_streets() {

		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		$cargus = new Cargus_Api();
		if ( property_exists( $cargus, 'token' ) && ! is_array( $cargus->token ) && ! is_object( $cargus->token ) ) {
			// obtin lista de judete din fisier json.
			$cargus_cache = new Cargus_Cache();
			if ( isset( $_POST['city'] ) && '' !== $_POST['city'] ) {
				$streets = $cargus_cache->get_streets( sanitize_text_field( wp_unslash( $_POST['city'] ) ) );
				$options = array();

				if ( '"No street retrieved!"' !== $streets ) {
					// generez options pentru dropdown.
					foreach ( json_decode( $streets ) as $street ) {
						$street_name = str_replace( ', Strada', '', $street->Name ); //phpcs:ignore
						if ( isset( $_POST['q'] ) && strpos( strtolower( $street_name ), strtolower( sanitize_text_field( wp_unslash( $_POST['q'] ) ) ) ) !== false ) {
							$options[] = array(
								'StreetName'    => $street_name,
								'StreetId'      => $street->StreetId, //phpcs:ignore
								'PostalNumbers' => json_encode( $street->PostalNumbers ), //phpcs:ignore
							);
						}

						if ( isset( $_POST['val'] ) ) {
							$deff_value = str_replace( 'Str. ', '', sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
							if ( strpos( strtolower( $street_name ), strtolower( $deff_value ) ) !== false ) {
								$options[] = array(
									'StreetName'    => $street_name,
									'StreetId'      => $street->StreetId, //phpcs:ignore
									'PostalNumbers' => json_encode( $street->PostalNumbers ), //phpcs:ignore
								);
							}
						}
					}
					echo wp_json_encode( $options );
				} else {
					echo 'null';
				}
			}
			wp_die();
		}
	}

	/**
	 * Get the locality streets and postal code.
	 *
	 * @since 1.1.3
	 */
	public function cargus_ajax_get_streets() {
		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		if ( isset( $_POST['locality'] ) && '' !== $_POST['locality'] ) {
			$cargus_cache = new Cargus_Cache();

			$json = $cargus_cache->get_streets( sanitize_text_field( wp_unslash( $_POST['locality'] ) ) );
		}

		wp_die( $json ); //phpcs:ignore

	}
	/**
	 * Check if the shipping method is active.
	 *
	 * @since 1.1.0
	 * @param string $shipping_method_id The id of the shipping method.
	 */
	private function cargus_check_active_shipping_method( $shipping_method_id ) {
		$available_zones = WC_Shipping_Zones::get_zones();
		$valid_zones     = array();

		foreach ( (array) $available_zones as $key => $the_zone ) {
			foreach ( $the_zone['shipping_methods'] as $value ) {
				if ( $value->is_available( WC()->cart->get_shipping_packages() ) && $shipping_method_id === $value->id ) {
					$valid_zones[] = $the_zone['zone_name'];
				}
			}
		}

		return $valid_zones;
	}

	/**
	 * Display the select pudo location map and button.
	 *
	 * @since 1.0.0
	 */
	public function cargus_display_map_button() {
		if ( is_cart() || is_checkout() ) {
			$current_shipping_zone_name = WC_Shipping_Zones::get_zone_matching_package( WC()->cart->get_shipping_packages()[0] )->get_zone_name();
			if ( in_array( Cargus_Admin::cargus_normalize( strtolower( $current_shipping_zone_name ) ), array( 'romania', 'ro'), true ) ) {
				echo wp_kses_post(
                    '<tr class="cargus-ship-and-go-select d-none">
					    <th>Alege cel mai apropiat Ship&Go!</th>
                        <td colspan="2">
                            <button type="button" class="btn btn-primary" id="cargus-open-map-btn">
                                Alege punct
                            </button>
                        </td>
                    </tr>
                    <tr class="cargus-ship-and-go-selected-point d-none">
                    </tr>'
                );
			}
		}
	}

	/**
	 * Display the select pudo location map.
	 *
	 * @since 1.0.0
	 */
	public function cargus_display_map() {
		if ( is_cart() || is_checkout() ) {
			$current_shipping_zone_name = WC_Shipping_Zones::get_zone_matching_package( WC()->cart->get_shipping_packages()[0] )->get_zone_name();
			if ( in_array( Cargus_Admin::cargus_normalize( strtolower( $current_shipping_zone_name ) ), array( 'romania', 'ro'), true ) ) {
				?>
                <div class="cargus-map-widget" id="shipgomap-modal" style="display: none;"></div>
				<?php
			}
		}
	}

	public function cargus_map_hidden_details() {
		if ( is_cart() || is_checkout() ) {
            ?>
			<input type="hidden" name="location_id" value="<?php echo esc_attr( WC()->session ? ( WC()->session->get( 'location_id' ) ) ? WC()->session->get( 'location_id' ) : ''  : '' ); ?>">
            <input type="hidden" name="location_name" value="<?php echo esc_attr( WC()->session ? ( WC()->session->get( 'location_name' ) ) ? WC()->session->get( 'location_name' ) : '' : '' ); ?>">
            <input type="hidden" name="location_service_cod" value="<?php echo esc_attr( WC()->session ? ( WC()->session->get( 'location_service_cod' ) ) ? WC()->session->get( 'location_service_cod' ) : '' : '' ); ?>">
            <input type="hidden" name="location_accepted_payment_type" value="<?php echo esc_attr( WC()->session ? ( WC()->session->get( 'location_accepted_payment_type' ) ) ? wp_json_encode( WC()->session->get( 'location_accepted_payment_type' ) ) : '' : '' ); ?>">
            <?php
            do_action( 'cargus_ship_and_go_before_map' );
		}
	}

	/**
	 * Add mobile app banner in customer email.
	 *
	 * @param Obj  $order The woocommerce order option.
	 * @param Bool $is_admin_email Check f the email is sent to the site admin.
	 * @since    1.0.0
	 */
	public function cargus_customize_email_banner( $order, $is_admin_email ) {

		if ( $is_admin_email ) {
			return;
		}

		$cargus_options = get_option( 'woocommerce_cargus_settings' );
		if ( isset( $cargus_options['email-banner'] ) && 'yes' === $cargus_options['email-banner'] ) {
			if ( $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus' ) ) {
				do_action( 'cargus_before_email_banner' );
				echo wp_kses_post(
					'<div class="nl-container" style="padding-bottm: 30px;">
						<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
							style="mso-table-lspace:0;mso-table-rspace:0;background-color:transparent;padding-bottm: 30px">
							<tbody>
								<tr>
									<td style="padding: 0">
										<table class="row row-1 mobile_hide" align="center" width="100%" border="0" cellpadding="0"
											cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
											<tbody>
												<tr>
													<td style="padding: 0">
														<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0"
															role="presentation"
															style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:900px"
															width="900">
															<tbody>
																<tr>
																	<td class="column column-1" width="100%"
																		style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;padding-top:0;padding-bottom:0;padding-left: 0; padding-right: 0;border-top:0;border-right:0;border-bottom:0;border-left:0">
																		<table class="image_block" width="100%" border="0" cellpadding="0"
																			cellspacing="0" role="presentation"
																			style="mso-table-lspace:0;mso-table-rspace:0">
																			<tbody>
																				<tr>
																					<td style="width:100%;padding: 0;padding:0;">
																						<div align="center" style="line-height:10px"><img
																								class="big"
																								src="' . esc_url( plugin_dir_url( __FILE__ ) . 'images/email_cargus_banner_top.png' ) . '"
																								style="display:block;height:auto;border:0;width:900px;max-width:100%"
																								width="900"></div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table class="row row-2 mobile_hide" align="center" width="100%" border="0" cellpadding="0"
											cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
											<tbody>
												<tr>
													<td style="padding: 0">
														<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0"
															role="presentation"
															style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:900px"
															width="900">
															<tbody>
																<tr>
																	<td class="column column-1" width="58.333333333333336%"
																		style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0;padding:0;">
																		<table class="image_block" width="100%" border="0" cellpadding="0"
																			cellspacing="0" role="presentation"
																			style="mso-table-lspace:0;mso-table-rspace:0">
																			<tbody>
																				<tr>
																					<td style="width:100%;padding:0;;padding:0;">
																						<div align="center" style="line-height:10px"><img
																								src="' . esc_url( plugin_dir_url( __FILE__ ) . 'images/email_cargus_banner_mid.png' ) . '"
																								style="display:block;height:auto;border:0;width:525px;max-width:100%"
																								width="525"></div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td class="column column-2" width="16.666666666666668%"
																		style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0;padding:0;">
																		<table class="image_block" width="100%" border="0" cellpadding="0"
																			cellspacing="0" role="presentation"
																			style="mso-table-lspace:0;mso-table-rspace:0">
																			<tbody>
																				<tr>
																					<td style="width:100%;padding:0;;padding:0;">
																						<div align="center" style="line-height:10px"><a
																								href="' . esc_url( 'https://play.google.com/store/apps/details?id=com.cargus.cma&amp;referrer=utm_source%3Demail_notification_android%26utm_medium%3Dbanner' ) . '"
																								target="_blank" style="outline:none"
																								tabindex="-1"><img
																									src="' . esc_url( plugin_dir_url( __FILE__ ) . 'images/email_cargus_banner_mid_gpay.png' ) . '"
																									style="display:block;height:auto;border:0;width:150px;max-width:100%"
																									width="150"></a></div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td class="column column-3" width="24.9%"
																		style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0;padding:0;">
																		<table class="image_block" width="100%" border="0" cellpadding="0"
																			cellspacing="0" role="presentation"
																			style="mso-table-lspace:0;mso-table-rspace:0">
																			<tbody>
																				<tr>
																					<td style="width:100%;padding:0;">
																						<div align="center" style="line-height:10px"><a
																								href="' . esc_url( 'https://apps.apple.com/app/apple-store/id1589429151?pt=118116014&amp;ct=email_notifications_ios&amp;mt=8' ) . '"
																								target="_blank" style="outline:none"
																								tabindex="-1"><img
																									src="' . esc_url( plugin_dir_url( __FILE__ ) . 'images/email_cargus_banner_mid_appstore.png' ) . '"
																									style="display:block;height:auto;border:0;width:225px;max-width:100%"
																									width="225"></a></div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table class="row row-3 mobile_hide" align="center" width="100%" border="0" cellpadding="0"
											cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
											<tbody>
												<tr>
													<td style="padding: 0">
														<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0"
															role="presentation"
															style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:900px"
															width="900">
															<tbody>
																<tr>
																	<td class="column column-1" width="100%"
																		style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;padding:0;border-top:0;border-right:0;border-bottom:0;border-left:0">
																		<table class="image_block" width="100%" border="0" cellpadding="0"
																			cellspacing="0" role="presentation"
																			style="mso-table-lspace:0;mso-table-rspace:0">
																			<tbody>
																				<tr>
																					<td style="width:100%;padding:0;">
																						<div align="center" style="line-height:10px"><img
																								class="big"
																								src="' . esc_url( plugin_dir_url( __FILE__ ) . 'images/email_cargus_banner_bot.png' ) . '"
																								style="display:block;height:auto;border:0;width:900px;max-width:100%"
																								width="900"></div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>'
				);
				do_action( 'cargus_after_email_banner' );
			}
		}
	}

	/**
	 * Customize order details email.
	 *
	 * @param Obj  $order The woocommerce order option.
	 * @param Bool $is_admin_email Check f the email is sent to the site admin.
	 * @since    1.0.0
	 */
	public function cargus_customize_email_ramburs_payment( $order, $is_admin_email ) {

		if ( $is_admin_email ) {
			return;
		}

		if ( $order->has_shipping_method( 'cargus_ship_and_go' ) && $order->get_payment_method() === 'cargus_ship_and_go_payment' ) {
			do_action( 'cargus_before_ramburs_message' );
			?>
			<p><?php esc_html_e( 'Plata se va face pe telefon printr-un link de plată trimis la momentul livrării.', 'cargus' ); ?></p>
			<?php
			do_action( 'cargus_after_ramburs_message' );
		}
	}

	/**
	 * Add Ship and go selected location to the Order details email.
	 *
	 * @param Obj $order The woocommerce order option.
	 * @since    1.0.0
	 */
	public function cargus_customize_email_ship_and_go_location( $order ) {

		if ( $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
			?>
			<p> 
				<?php
				/* translators: %s is replaced with the ship and go location name */
				printf( __( 'Locația Ship & Go aleasă este <b>%s</b>.', 'cargus' ), esc_html( get_post_meta( $order->get_id(), '_selected_cargus_location_name', true ) ) ); //phpcs:ignore
				?>
			</p>
			<?php
		}
	}


	/**
	 * Display the selected location on ThankYou page.
	 *
	 * @param int $order_id The woocommerce order id.
	 */
	public function cargus_add_point_name_on_checkout( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
			?>
			<p> 
				<?php
				/* translators: %s is replaced with the ship and go location name */
				printf( __( 'Locația Ship & Go aleasă este <b>%s</b>.', 'cargus' ), esc_html( WC()->session ? WC()->session->get( 'location_name' ) : '' ) ); //phpcs:ignore
				?>
			</p>
			<?php
		}
	}

	/**
	 * Change the woocommerce shipping/billing state field order.
	 *
	 * @param array $fields The woocommerce chakout fields array.
	 */
	public function cargus_reorder_checkout_fields( $fields ) {
		$fields['state']['priority'] = 65;
		$fields['city']['priority']  = 70;

		$cargus_options = get_option( 'woocommerce_cargus_settings' );
		if ( isset( $cargus_options['locations-select'] ) && 'yes' === $cargus_options['locations-select'] && ! is_admin() ) {
			$fields['address_2']['priority']    = 73;
			$fields['address_2']['label']       = __( 'Adresa', 'cargus' );
			$fields['address_2']['label_class'] = array();

			$fields['street'] = array(
				'label'       => __( 'Strada', 'cargus' ),
				'placeholder' => __( 'Unirii', 'cargus' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' ),
				'clear'       => true,
				'type'        => 'text',
				'priority'    => 71,
				'placeholder' => '',
			);

            if ( WC()->session ) {
	            if ( WC()->session->get( 'billing_street' ) ) {
		            $fields['street']['default'] = WC()->session->get( 'billing_street' );
	            }
            }

			$fields['street_number'] = array(
				'label'       => __( 'Numar', 'cargus' ),
				'placeholder' => __( '1', 'cargus' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' ),
				'clear'       => true,
				'type'        => 'text',
				'priority'    => 72,
				'placeholder' => '',
			);

			if ( WC()->session ) {
				if ( WC()->session->get( 'street_number' ) ) {
					$fields['street']['default'] = WC()->session->get( 'street_number' );
				}
			}
		}
		return $fields;
	}

	/**
	 * Load the default data for the new address custom fields.
	 *
	 * @param array $fields The woocommerce address fields array.
	 */
	public function cargus_load_default_data_billing_fields( $fields ) {
		$cargus_options = get_option( 'woocommerce_cargus_settings' );
		if ( isset( $cargus_options['locations-select'] ) && 'yes' === $cargus_options['locations-select'] && ! is_admin() ) {
			$billing_street                    = get_user_meta( get_current_user_id(), 'billing_street', true );
			$fields['billing_street']['value'] = WC()->session ? ( WC()->session->get( 'billing_street' ) ) ? WC()->session->get( 'billing_street' ) : ( ( $billing_street ) ? $billing_street : 0 ) : ( ( $billing_street ) ? $billing_street : 0 );//phpcs:ignore

			$billing_street_number                    = get_user_meta( get_current_user_id(), 'billing_street_number', true );
			$fields['billing_street_number']['value'] = WC()->session ? ( WC()->session->get( 'billing_street_number' ) ) ? WC()->session->get( 'billing_street_number' ) : ( ( $billing_street_number ) ? $billing_street_number : 0 ) : ( ( $billing_street ) ? $billing_street : 0 );//phpcs:ignore
        }

		return $fields;
	}

	/**
	 * Load the default data for the new address custom fields.
	 *
	 * @param array $fields The woocommerce address fields array.
	 */
	public function cargus_load_default_data_shipping_fields( $fields ) {
		$cargus_options = get_option( 'woocommerce_cargus_settings' );
		if ( isset( $cargus_options['locations-select'] ) && 'yes' === $cargus_options['locations-select'] && ! is_admin() ) {
			$shipping_street                    = get_user_meta( get_current_user_id(), 'shipping_street', true );
			$fields['shipping_street']['value'] = WC()->session ? ( WC()->session->get( 'shipping_street' ) ) ? WC()->session->get( 'shipping_street' ) : ( ( $shipping_street ) ? $shipping_street : 0 ) : ( ( $shipping_street ) ? $shipping_street : 0 );//phpcs:ignore


			$shipping_street_number                    = get_user_meta( get_current_user_id(), 'shipping_street_number', true );
			$fields['shipping_street_number']['value'] = WC()->session ? ( WC()->session->get( 'shipping_street_number' ) ) ? WC()->session->get( 'shipping_street_number' ) : ( ( $shipping_street_number ) ? $shipping_street_number : 0 ) : ( ( $billing_street ) ? $billing_street : 0 );//phpcs:ignore
		}
		return $fields;
	}

	/**
	 * Set if the custom fields are required or not.
	 *
	 * @param array $fields The woocommerce address fields array.
	 */
	public function cargus_checkout_disable_required( $fields ) {
		$cargus_options = get_option( 'woocommerce_cargus_settings' );
		if ( isset( $cargus_options['locations-select'] ) && 'yes' === $cargus_options['locations-select'] ) {
			if ( ( isset( $_POST['billing_country'] ) && 'RO' !== $_POST['billing_country'] ) || ( isset( $_POST['shipping_country'] ) && 'RO' !== $_POST['shipping_country'] ) ) { //phpcs:ignore
				$fields['billing']['billing_street']['required']          = false;
				$fields['billing']['billing_street_number']['required']   = false;
				$fields['shipping']['shipping_street']['required']        = false;
				$fields['shipping']['shipping_street_number']['required'] = false;

			} else {
				$fields['billing']['billing_street']['required']          = true;
				$fields['billing']['billing_street_number']['required']   = true;
				$fields['shipping']['shipping_street']['required']        = true;
				$fields['shipping']['shipping_street_number']['required'] = true;
			}
		}

		return $fields;
	}

	/**
	 * Set the deffault data for the new address custom fields.
	 *
	 * @param array $post_data The woocommerce address fields array.
	 */
	public function cargus_checkout_update_order_review( $post_data ) {

		// Convert $post_data string to array and clean it.
		$post_arr = array();
		parse_str( $post_data, $post_arr );
		wc_clean( $post_arr );

		if ( isset( $post_arr['billing_street'] ) && WC()->session ) {
			WC()->session->set( 'billing_street', $post_arr['billing_street'] );
		}

		if ( isset( $post_arr['billing_street_number'] ) && WC()->session ) {
			WC()->session->set( 'billing_street_number', $post_arr['billing_street_number'] );
		}

		if ( isset( $post_arr['shipping_street'] ) && WC()->session ) {
			WC()->session->set( 'shipping_street', $post_arr['shipping_street'] );
		}

		if ( isset( $post_arr['shipping_street_number'] ) && WC()->session ) {
			WC()->session->set( 'shipping_street_number', $post_arr['shipping_street_number'] );
		}
	}

	/**
	 * Update the order meta with field value
	 *
	 * @param int $user_id The current user id.
	 */
	public function cargus_checkout_fields_meta_save( $user_id ) {
		//phpcs:disable
		if ( ! empty( $_POST['billing_street'] ) ) {
			update_user_meta( $user_id, 'billing_street', sanitize_text_field( wp_unslash( $_POST['billing_street'] ) ) );
		}

		if ( ! empty( $_POST['shipping_street'] ) ) {
			update_user_meta( $user_id, 'shipping_street', sanitize_text_field( wp_unslash( $_POST['shipping_street'] ) ) );
		}

		if ( ! empty( $_POST['billing_street_number'] ) ) {
			update_user_meta( $user_id, 'billing_street_number', sanitize_text_field( wp_unslash( $_POST['billing_street_number'] ) ) );
		}

		if ( ! empty( $_POST['shipping_street_number'] ) ) {
			update_user_meta( $user_id, 'shipping_street_number', sanitize_text_field( wp_unslash( $_POST['shipping_street_number'] ) ) );
		}
		//phpcs:enable
	}

	/**
	 * Disable cargus ship and go payment gateway when byer is outside RO.
	 *
	 * @param array $available_gateways The available payment gateways.
	 */
	public function cargus_disable_payment_gateway( $available_gateways ) {
		if ( is_cart() || is_checkout() ) {
			$current_shipping_zone_name = WC_Shipping_Zones::get_zone_matching_package( WC()->cart->get_shipping_packages()[0] )->get_zone_name();
			if ( ! in_array( Cargus_Admin::cargus_normalize( strtolower( $current_shipping_zone_name ) ), array( 'romania', 'ro'), true ) ) {

				if ( isset( $available_gateways['cargus_ship_and_go_payment'] ) ) {
					unset( $available_gateways['cargus_ship_and_go_payment'] );
				}
			}
		}

		return $available_gateways;
	}

	/**
	 * Change defalt checkout state.
	 */
	public function change_default_checkout_state() {
		if ( WC()->customer->get_billing_state() ) {
			return WC()->customer->get_billing_state();
		} else {
			return ''; // set state code if you want to set it otherwise leave it blank.
		}
	}

	/**
	 * Add Awb Return Code if it exists on View order page.
	 *
	 * @param int $order_id The woocommerce order id.
	 */
	public function display_return_awb_code( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ( $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus' ) ) && get_post_meta( $order_id, '_cargus_return_code', true ) ) {
			?>
			<p> 
				<?php
				/* translators: %s is replaced with the ship and go location name */
				printf( __( 'Codul de retur este <b>%s</b>.', 'cargus' ), esc_html( get_post_meta( $order_id, '_cargus_return_code', true ) ) ); //phpcs:ignore
				?>
			</p>
			<?php
		}
	}

	/**
	 * Add Awb Return Code if it exists on View order page.
	 *
	 * @param int $order_id The woocommerce order id.
	 */
	public function display_return_awb_code_qr( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ( $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus' ) ) && get_post_meta( $order_id, '_cargus_return_code', true ) ) {
			?>
            <div id="qr-code">
            </div>
			<?php
		}
	}

	/**
	 * Add Ship and go selected point on View order page.
	 *
	 * @param int $order_id The woocommerce order id.
	 */
	public function display_ship_and_go_point( $order_id ) {
		$order                         = wc_get_order( $order_id );
		$selected_cargus_pudo_point_id = get_post_meta( $order_id, '_selected_cargus_location', true );
		if ( $order->has_shipping_method( 'cargus_ship_and_go' ) && $selected_cargus_pudo_point_id ) {
			$pudo_locations = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/locations/pudo_locations.json' );
			// Convert to array.
			$pudo_locations_array = json_decode( $pudo_locations, true );
			foreach ( $pudo_locations_array as $pudo_location ) {
				if ( $pudo_location['Id'] == $selected_cargus_pudo_point_id ) {
					?>
					<p> 
						<?php
						/* translators: %s is replaced with the ship and go location name */
						printf( __( 'Locatia Ship and go aleasa este <b>%s</b>.', 'cargus' ), esc_html( $pudo_location['Name'] ) ); //phpcs:ignore
						?>
					</p>
					<?php
				}
			}
		}
	}

	/**
	 * Add cargus shipment status on order details page.
	 *
	 * @param int $order_id The woocommerce order id.
	 */
	public function display_cargus_shipment_status( $order_id ) {
		$order     = wc_get_order( $order_id );
        $order_awb = get_post_meta( $order_id, '_cargus_awb', true );
		if ( ( $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus' ) ) && $order_awb ) {
			$cargus = new Cargus_Api();
            $shipment_status = $cargus->get_shipment_status( $order_awb )->StatusExpression;

            if ( ! in_array( $shipment_status, array( '', null ) ) ) {
	            ?>
                <p>
		            <?php
		            /* translators: %s is replaced with the ship and go location name */
		            printf( __( 'Statusul comenzii este: <b>%s</b>.', 'cargus' ), esc_html( $shipment_status ) ); //phpcs:ignore
		            ?>
                </p>
	            <?php
            }
		}
    }

	/**
	 * Ajax set the billing/shipping city details.
	 *
	 * @since 1.0.0
	 */
	public function cargus_set_city_details() {

		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		if ( isset( $_POST['type'] ) && 'SELECT' === $_POST['type'] ) {
			$options = array( '-' => array( '0', null ) );
		}

		if ( isset( $_POST['judet'] ) && '' !== $_POST['judet'] ) {
			// generez options pentru dropdown.
			$cargus = new Cargus_Api();
			if ( property_exists( $cargus, 'token' ) && ! is_array( $cargus->token ) && ! is_object( $cargus->token ) ) {
				// obtin lista de judete din fisier json.
				$counties_json = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/locations/counties.json' );
				// Convert to array.
				$counties     = json_decode( $counties_json, true );
				$cargus_cache = new Cargus_Cache();
				foreach ( $counties as $county ) {
					if ( trim( addslashes( sanitize_text_field( $_POST['judet'] ) ) ) === $county['Abbreviation'] ) { //phpcs:ignore
						$localities = $cargus_cache->get_localities( $county['CountyId'] ); //phpcs:ignore
						foreach ( json_decode( $localities ) as $locality ) { //phpcs:ignore
							$options[ $locality->Name ] = array( $locality->InNetwork ? 0 : ( ! $locality->ExtraKm ? 0 : $locality->ExtraKm ), $locality->LocalityId, $locality->Name, $locality->PostalCode, $locality->SaturdayDelivery );//phpcs:ignore
						}
						break;
					}
				}
				echo wp_json_encode( $options );
			}

			wp_die();
		}
	}

	/**
	 * Set woocommerce session value for cargus saturday delivery (true/false) NOT USED.
	 *
	 * @since 1.0.0
	 */
	public function cargus_set_saturday_delivery() {

		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		if ( isset( $_POST[ 'saturday_delivery' ] ) ) {
			WC()->session->set('saturday_delivery', $_POST[ 'saturday_delivery' ] );
		}

		wp_die();
	}

	/**
	 * Get cargus pre10 and pre12 delivery value (true/false).
	 *
	 * @since 1.0.0
	 */
	public function cargus_ajax_get_additional_delivery() {

        // check ajax nonce.
		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

        // check if the locality id was sent.
		if ( isset( $_POST[ 'locality_id' ] ) ) {
            // check if the pickup point is from Bucharest.
			$cargus_options       = get_option( 'woocommerce_cargus_settings' );
			$cargus_pickup_points = get_option( 'cargus_pickup_points' );
            $sender_from_b = false;
            foreach ( $cargus_pickup_points as $location ) {
                if ( $location->LocationId === intval( $cargus_options['pickup'] ) && 'Bucuresti' === $location->CountyName ) {
	                $sender_from_b = true;
                }
            }

			$additional_delivery = array( 'pre10' => false, 'pre12' => false );

            // get data from json file.
			$cttd = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/locations/cttd.json' );
			// Convert to array.
			$cttd_array = json_decode( $cttd, true );

            // loop through data and check if the pre10 ore pre12 delivery is available for the current locality.
            foreach ( $cttd_array['CountryTranzitTimeDestinatio'] as $location ) {
                if ( $location['IdOras'] === $_POST[ 'locality_id' ] ) {
                    if ( ( $sender_from_b && "1" === $location['Pre10FromB'] ) || ( ! $sender_from_b && "1" === $location['Pre10FromT'] ) ) {
	                    $additional_delivery['pre10'] = true;
                    }

                    if ( ( $sender_from_b && "1" === $location['Pre12FromB'] ) || ( ! $sender_from_b && "1" === $location['Pre12FromT'] ) ){
	                    $additional_delivery['pre12'] = true;
                    }
                }
            }

            echo wp_json_encode( $additional_delivery );
		}

		wp_die();
	}
}
