<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_DATA {
	private $params;
	private $default;
	private $_wpnonce;
	protected static $instance = null;
	protected static $allow_html = null;

	/**
	 * VI_WOO_ORDERS_TRACKING_DATA constructor.
	 * Init setting
	 */
	public function __construct() {
		global $woo_orders_tracking_settings;
		if ( ! $woo_orders_tracking_settings ) {
			$woo_orders_tracking_settings = get_option( 'woo_orders_tracking_settings', array() );
		}
		$this->default = array(
			'export_settings_filename'                        => 'orders-%y-%m-%d_%h-%i-%s.csv',
			'export_settings_filter-order-date'               => 'date_created',
			'export_settings_filter-order-date-from'          => '',
			'export_settings_filter-order-date-to'            => '',
			'export_settings_filter-order-status'             => array(),
			'export_settings_filter-order-billing-address'    => array(),
			'export_settings_filter-order-shipping-address'   => array(),
			'export_settings_filter-order-payment-method'     => array(),
			'export_settings_filter-order-shipping-method'    => array(),
			'export_settings_filter-order-sort-order'         => 'order_id',
			'export_settings_filter-order-sort-order-in'      => 'ASC',
			'export_settings_filter-order-export-set-fields'  => array(
				'order_id',
				'order_item_id',
				'tracking_number',
				'carrier_slug',
			),
			'shipping_carrier_default'                        => '',
			'active_carriers'                                 => array(),
			'custom_carriers_list'                            => vi_wot_json_encode( array() ),
			'shipping_carriers_define_list'                   => vi_wot_json_encode( self::shipping_carriers() ),
			'service_carrier_enable'                          => 0,
			'service_carrier_type'                            => 'trackingmore',
			'service_carrier_api_key'                         => '',
			'service_tracking_page'                           => get_option( 'vi_woo_orders_tracking_page_track_order' ),
			'service_cache_request'                           => 1,
			'service_add_tracking_if_not_exist'               => '1',
			/*Timeline*/
			'timeline_track_info_sort_event'                  => 'most_recent_to_oldest',
			'timeline_track_info_date_format'                 => 'j F, Y',
			'timeline_track_info_time_format'                 => 'g:i a',
			'timeline_track_info_datetime_format_locale'      => '',
			'timeline_track_info_template'                    => '1',
			'timeline_track_info_title'                       => '{carrier_name}: {tracking_number}',
			'timeline_track_info_title_alignment'             => 'center',
			'timeline_track_info_title_color'                 => '#222',
			'timeline_track_info_title_font_size'             => '26',
			'timeline_track_info_status_color'                => '#fff',
			'timeline_track_info_status_background_delivered' => '#4cbb87',
			'timeline_track_info_status_background_pickup'    => '#f5a551',
			'timeline_track_info_status_background_transit'   => '#65aee0',
			'timeline_track_info_status_background_pending'   => '#f1f1f1',
			'timeline_track_info_status_background_alert'     => '#ff0000',
			'default_track_info_enable'                       => '',
			'default_track_info_number'                       => '',
			'default_track_info_message'                      => array(
				array(
					'time'           => 0,
					'description'    => 'Processing',
					'location'       => '',
					'status'         => 'pending',
					'order_statuses' => array( 'wc-processing', 'wc-completed' ),
				),
			),
			'default_track_info_carrier'                      => 'ePacket',
			'default_track_info_position'                     => 'after_order_table',
			'default_track_info_content'                      => '<p><a href="{tracking_url}" target="_blank" rel="nofollow">Track your order</a></p>',
			'timeline_track_info_template_one'                => array(
				'icon_delivered'          => '2',
				'icon_delivered_color'    => '#4cbb87',
				'icon_pickup'             => '14',
				'icon_pickup_color'       => '#fff',
				'icon_pickup_background'  => '#f5a551',
				'icon_transit'            => '21',
				'icon_transit_color'      => '#fff',
				'icon_transit_background' => '#65aee0',
			),
			'custom_css'                                      => '',
			'paypal_enable'                                   => '',
			'paypal_sandbox_enable'                           => array( '' ),
			'paypal_client_id_live'                           => array( '' ),
			'paypal_client_id_sandbox'                        => array( '' ),
			'paypal_secret_live'                              => array( '' ),
			'paypal_secret_sandbox'                           => array( '' ),
			'paypal_method'                                   => array( '' ),
			'paypal_debug'                                    => '',
			/*Email WOO*/
			'email_woo_enable'                                => '1',
			'email_woo_status'                                => array( 'customer_completed_order' ),
			'email_woo_position'                              => 'after_order_table',
			/*Email*/
			'email_enable'                                    => '1',
			'email_send_all_order_items'                      => '1',
			'email_time_send'                                 => '1',
			'email_time_send_type'                            => 'hour',
			'email_number_send'                               => '10',
			'email_subject'                                   => 'Order tracking updated #{order_id}',
			'email_heading'                                   => 'Tracking info of order #{order_id}',
			'email_content'                                   => 'Dear {billing_first_name},

Your order #{order_id} has been updated as below:

{tracking_table}

Your sincerely',
			'supported_paypal_gateways'                       => array(
				'paypal',
				'ppec_paypal',
				'ppcp-gateway'
			),
			'orders_per_request'                              => 10,
			'custom_start'                                    => 2,
			'order_status'                                    => 'wc-completed',
			'cron_update_tracking'                            => '',
			'cron_update_tracking_interval'                   => '1',
			'cron_update_tracking_hour'                       => '0',
			'cron_update_tracking_minute'                     => '0',
			'cron_update_tracking_second'                     => '0',
			'cron_update_tracking_range'                      => '60',
			/*Tracking form*/
			'tracking_form_button_track_color'                => '#ffffff',
			'tracking_form_button_track_bg_color'             => 'red',
		);

		$this->params = apply_filters( 'woo_orders_tracking_settings', wp_parse_args( $woo_orders_tracking_settings, $this->default ) );
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_params( $name = '', $name_sub = '', $language = '' ) {
		$language = apply_filters( 'woo_orders_tracking_settings_language', $language, $name, $name_sub );
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			if ( $name_sub ) {
				if ( isset( $this->params[ $name ][ $name_sub ] ) ) {
					if ( $language ) {
						$name_language = $name_sub . '_' . $language;
						if ( isset( $this->params[ $name ][ $name_language ] ) ) {
							return apply_filters( 'woo_orders_tracking_settings-' . $name . '__' . $name_language, $this->params[ $name ][ $name_language ] );
						} else {
							return apply_filters( 'woo_orders_tracking_settings-' . $name . '__' . $name_language, $this->params[ $name ][ $name_sub ] );
						}
					} else {
						return apply_filters( 'woo_orders_tracking_settings-' . $name . '__' . $name_sub, $this->params[ $name ] [ $name_sub ] );
					}
				} elseif ( $this->default[ $name ] [ $name_sub ] ) {
					return apply_filters( 'woo_orders_tracking_settings-' . $name . '__' . $name_sub, $this->default[ $name ] [ $name_sub ] );
				} else {
					return false;
				}
			} else {
				if ( $language ) {
					$name_language = $name . '_' . $language;
					if ( isset( $this->params[ $name_language ] ) ) {
						return apply_filters( 'woo_orders_tracking_settings-' . $name_language, $this->params[ $name_language ] );
					} else {
						return apply_filters( 'woo_orders_tracking_settings-' . $name_language, $this->params[ $name ] );
					}
				} else {
					return apply_filters( 'woo_orders_tracking_settings-' . $name, $this->params[ $name ] );
				}
			}
		} else {
			return false;
		}
	}

	public function get_default( $name = '', $type = '' ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_orders_tracking_settings_default-' . $name, $this->default[ $name ] );
		} elseif ( $type && isset( $this->default[ $type ][ $name ] ) ) {
			return apply_filters( 'woo_orders_tracking_settings_default-' . $type . '-' . $name, $this->default[ $type ][ $name ] );
		} else {
			return false;
		}
	}

	public static function set( $name, $set_name = false, $prefix = 'woo-orders-tracking-' ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( 'VI_WOO_ORDERS_TRACKING_DATA', 'set' ), $name ) );
		} else {
			if ( $set_name ) {
				return esc_attr( str_replace( '-', '_', $prefix . $name ) );

			} else {
				return esc_attr( $prefix . $name );

			}
		}
	}

	/**
	 * @param $slug
	 * @param string $type
	 *
	 * @return array|bool
	 */
	public function get_shipping_carrier_by_slug( $slug, $type = '' ) {
		$result = false;
		if ( ! $type ) {
			if ( substr( $slug, 0, 7 ) === 'custom_' || substr( $slug, 0, 14 ) === 'custom-carrier' ) {
				/*since v1.0.14(Pro), slugs of custom carriers can be set while creating instead of always starting with custom_ like before*/
				$type = 'custom-carrier';
			}
		}
		if ( $type ) {
			switch ( $type ) {
				case 'custom-carrier':
					$result = $this->get_custom_carrier_by_slug( $slug );
					break;
				case 'define-carrier':
					$result = $this->get_defined_carrier_by_slug( $slug );
					break;
				default:
			}
		} else {
			$result = $this->get_defined_carrier_by_slug( $slug );
			if ( ! $result ) {
				$result = $this->get_custom_carrier_by_slug( $slug );
			}
		}

		return $result;
	}

	/**
	 * @param $slug
	 *
	 * @return bool|array
	 */
	public function get_defined_carrier_by_slug( $slug ) {
		$result   = false;
		$carriers = self::get_defined_carriers();
		foreach ( $carriers as $carrier ) {
			if ( $carrier['slug'] === $slug ) {
				$carrier['carrier_type'] = 'define-carrier';
				$result                  = $carrier;
				break;
			}
		}

		return $result;
	}

	/**
	 * @param $slug
	 *
	 * @return bool|array
	 */
	public function get_custom_carrier_by_slug( $slug ) {
		$result   = false;
		$carriers = self::get_custom_carriers();
		if ( count( $carriers ) ) {
			foreach ( $carriers as $carrier ) {
				if ( $carrier['slug'] === $slug ) {
					$carrier['carrier_type'] = 'custom-carrier';
					$result                  = $carrier;
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * @param $slug
	 * @param string $type
	 *
	 * @return mixed|string
	 */
	public function get_shipping_carrier_url( $slug, $type = '' ) {
		$result  = '';
		$carrier = $this->get_shipping_carrier_by_slug( $slug, $type );
		if ( is_array( $carrier ) && count( $carrier ) ) {
			$result = $carrier['url'];
		}

		return $result;
	}

	/**
	 * @param $url
	 * @param $tracking_number
	 * @param $slug
	 * @param string $postal_code
	 * @param bool $return_carrier_url
	 * @param bool $add_nonce
	 * @param string $order_id
	 *
	 * @return string
	 */
	public function get_url_tracking( $url, $tracking_number, $slug, $postal_code = '', $return_carrier_url = false, $add_nonce = true, $order_id = '' ) {
		if ( ! $tracking_number ) {
			return '';
		}
		$tracking_link = '';
		if ( ! $return_carrier_url && $this->get_params( 'service_carrier_enable' ) ) {
			$service_tracking_page = $this->get_params( 'service_tracking_page' );
			if ( $service_tracking_page && $service_tracking_page_url = get_the_permalink( $service_tracking_page ) ) {
				$tracking_link = add_query_arg( array( 'tracking_id' => $tracking_number ), $service_tracking_page_url );
				if ( $add_nonce ) {
					$this->_wpnonce = wp_create_nonce( 'woo_orders_tracking_nonce_action' );
					$tracking_link  = add_query_arg( array( 'woo_orders_tracking_nonce' => $this->_wpnonce ), $tracking_link );
				}
			} else {
				switch ( $this->get_params( 'service_carrier_type' ) ) {
					case 'trackingmore':
						$tracking_link = 'https://www.trackingmore.com/track/en/' . $tracking_number;
						break;
					case '17track':
						$tracking_link = "https://t.17track.net/en#nums={$tracking_number}";
						break;
					case 'aftership':
						$tracking_link = 'https://track.aftership.com/' . $tracking_number;
						break;
					case 'cainiao':
						$tracking_link = 'https://global.cainiao.com/detail.htm?mailNoList=' . $tracking_number . '&spm=a3708.7860688.0.d01';
						break;
					default:
				}
			}
		}
		if ( ! $tracking_link ) {
			if ( ! $url ) {
				$url = $this->get_shipping_carrier_url( $slug, 'define-carrier' );
			}
			$tracking_link = str_replace( array( '{tracking_number}', '{postal_code}' ), array(
				$tracking_number,
				$postal_code
			), $url );
		}

		return $tracking_link;
	}

	public static function shipping_carriers( $filter = true ) {
		$carriers = array(
			array(
				'name'               => 'Austrian Post',
				'slug'               => 'austria-post',
				'url'                => 'https://www.post.at/sendungsverfolgung.php?pnum1={tracking_number}',
				'country'            => 'AT',
				'active'             => '',
				'tracking_more_slug' => 'austria-post',
			),
			array(
				'name'               => 'DHL Austria',
				'slug'               => 'dhl-at',
				'url'                => 'https://www.logistics.dhl/at-en/home/tracking/tracking-ecommerce.html?tracking-id={tracking_number}',
				'country'            => 'AT',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DPD Austria',
				'slug'               => 'dpd-at',
				'url'                => 'https://tracking.dpd.de/parcelstatus?locale=de_AT&query={tracking_number}',
				'country'            => 'AT',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'Australia Post',
				'slug'               => 'australia-post',
				'url'                => 'https://auspost.com.au/track/track.html?id={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'australia-post',
			),
			array(
				'name'               => 'Australia EMS',
				'slug'               => 'australia-ems',
				'url'                => 'https://auspost.com.au/track/track.html?id={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'australia-ems',
			),
			array(
				'name'               => 'Fastway Australia',
				'slug'               => 'fastway-au',
				'url'                => 'https://www.fastway.com.au/tools/track/?l={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'fastway-au',
			),
			array(
				'name'               => 'Dai Post',
				'slug'               => 'dai-post-au',
				'url'                => 'https://daiglobaltrack.com/tracking.aspx?custtracknbr={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'MyToll Australia',
				'slug'               => 'mytoll-au',
				'url'                => 'https://online.toll.com.au/trackandtrace/traceConsignments.do?consignments={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'StarTrack',
				'slug'               => 'startrack-au',
				'url'                => 'https://sttrackandtrace.startrack.com.au/{tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'star-track',
			),
			array(
				'name'               => 'Couriers Please',
				'slug'               => 'couriers-please-au',
				'url'                => 'https://www.couriersplease.com.au/tools-track/no/{tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'couriers-please',
			),
			array(
				'name'               => 'Sendle',
				'slug'               => 'sendle-au',
				'url'                => 'https://track.sendle.com/tracking?ref={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'sendle',
			),
			array(
				'name'               => 'TNT Australia',
				'slug'               => 'tnt-au',
				'url'                => 'https://www.tnt.com/express/en_au/site/shipping-tools/tracking.html?respCountry=au&respLang=en&cons={tracking_number}',
				'country'            => 'AU',
				'active'             => '',
				'tracking_more_slug' => 'tnt-au',
			),
			array(
				'name'               => 'Belgium Post',
				'slug'               => 'belgium-post',
				'url'                => 'https://track.bpost.be/btr/web/#/search?itemCode={tracking_number}',
				'country'            => 'BE',
				'active'             => '',
				'tracking_more_slug' => 'belgium-post',
			),
			array(
				'name'               => 'Brazil Correios',
				'slug'               => 'brazil-correios',
				'url'                => 'https://www.correios.com.br/',
				'country'            => 'BR',
				'active'             => '',
				'tracking_more_slug' => 'brazil-correios',
			),
			array(
				'name'               => 'Canada Post',
				'slug'               => 'canada-post',
				'url'                => 'https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber={tracking_number}&LOCALE=en',
				'country'            => 'CA',
				'active'             => '',
				'tracking_more_slug' => 'canada-post',
			),
			array(
				'name'               => 'Canpar Courier',
				'slug'               => 'Courier-ca',
				'url'                => 'https://www.canpar.ca/en/track/tracking.jsp',
				'country'            => 'CA',
				'active'             => '',
				'tracking_more_slug' => 'canpar',
			),
			array(
				'name'               => 'Swiss Post',
				'slug'               => 'swiss-post',
				'url'                => 'https://service.post.ch/EasyTrack/submitParcelData.do?p_language=en&formattedParcelCodes={tracking_number}',
				'country'            => 'CH',
				'active'             => '',
				'tracking_more_slug' => 'swiss-post',
			),
			array(
				'name'               => 'Ivory Coast EMS',
				'slug'               => 'ivory-coast-ems',
				'url'                => 'https://laposte.ci.post/tracking-colis?identifiant={tracking_number}',
				'country'            => 'CI',
				'active'             => '',
				'tracking_more_slug' => 'ivory-coast-ems',
			),
			array(
				'name'               => 'Correos Chile',
				'slug'               => 'correos-chile',
				'url'                => 'https://www.correos.cl/web/guest/seguimiento-en-linea?codigos={tracking_number}',
				'country'            => 'CL',
				'active'             => '',
				'tracking_more_slug' => 'correos-chile',
			),
			array(
				'name'               => 'China Post',
				'slug'               => 'china-post',
				'url'                => 'english.chinapost.com.cn',
				'country'            => 'CN',
				'active'             => '',
				'tracking_more_slug' => 'china-post',
			),
			array(
				'name'               => 'China EMS( ePacket )',
				'slug'               => 'china-ems',
				'url'                => 'https://www.11183.com.cn/english.html',
				'country'            => 'CN',
				'active'             => '',
				'tracking_more_slug' => 'china-ems',
			),
			array(
				'name'               => 'S.F Express',
				'slug'               => 'sf-express-cn',
				'url'                => 'https://www.sf-express.com/cn/en/dynamic_function/waybill/#search/bill-number/{tracking_number}',
				'country'            => 'CN',
				'active'             => '',
				'tracking_more_slug' => 'sf-express',
			),
			array(
				'name'               => 'Yun Express',
				'slug'               => 'yun-express-cn',
				'url'                => 'http://www.yuntrack.com/Track/Detail/{tracking_number}',
				'country'            => 'CN',
				'active'             => '',
				'tracking_more_slug' => 'yunexpress',
			),
			array(
				'name'               => 'Cyprus Post',
				'slug'               => 'cyprus-post',
				'url'                => 'https://www.cypruspost.post/en/track-n-trace-results?code={tracking_number}',
				'country'            => 'CY',
				'active'             => '',
				'tracking_more_slug' => 'cyprus-post',
			),
			array(
				'name'               => 'DHL CZ',
				'slug'               => 'dhl-cz',
				'url'                => 'https://www.dhl.cz/cs/express/sledovani_zasilek.html?AWB={tracking_number}&brand=DHL',
				'country'            => 'CZ',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DPD CZ',
				'slug'               => 'dpd-cz',
				'url'                => 'https://tracking.dpd.de/parcelstatus?locale=cs_CZ&query={tracking_number}',
				'country'            => 'CZ',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'Deutsche Post DHL',
				'slug'               => 'dhl-de',
				'url'                => 'https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc={tracking_number}',
				'country'            => 'DE',
				'tracking_more_slug' => '',
				'active'             => '',
			),
			array(
				'name'               => 'Hermes Germany',
				'slug'               => 'hermes-de',
				'url'                => 'https://www.myhermes.de/empfangen/sendungsverfolgung/?suche={tracking_number}',
				'country'            => 'DE',
				'active'             => '',
				'tracking_more_slug' => 'hermes-de',
			),
			array(
				'name'               => 'UPS DE',
				'slug'               => 'ups-de',
				'url'                => 'https://wwwapps.ups.com/WebTracking?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1={tracking_number}',
				'country'            => 'DE',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DPD DE',
				'slug'               => 'dpd-de',
				'url'                => 'https://tracking.dpd.de/parcelstatus?query={tracking_number}&locale=en_DE',
				'country'            => 'DE',
				'active'             => '',
				'tracking_more_slug' => 'dpd-de',
			),
			array(
				'name'               => 'Deutsche Post',
				'slug'               => 'deutsche-post',
				'url'                => 'https://www.deutschepost.de/sendung/simpleQuery.html',
				'country'            => 'DE',
				'active'             => '',
				'tracking_more_slug' => 'deutsche-post',
			),
			array(
				'name'               => 'Denmark Post',
				'slug'               => 'denmark-post',
				'url'                => 'https://www.postnord.dk/en/track-and-trace#dynamicloading=true&shipmentid={tracking_number}',
				'country'            => 'DK',
				'active'             => '',
				'tracking_more_slug' => 'denmark-post',
			),
			array(
				'name'               => 'Colissimo',
				'slug'               => 'colissimo-fr',
				'url'                => 'https://www.laposte.fr/outils/suivre-vos-envois?code={tracking_number}',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'colissimo',
			),
			array(
				'name'               => 'Chronopost France',
				'slug'               => 'chronopost-fr',
				'url'                => 'https://www.chronopost.fr/fr/chrono_suivi_search?listeNumerosLT={tracking_number}',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'Chronopost',
			),
			array(
				'name'               => 'Colis Privé',
				'slug'               => 'colis-prive',
				'url'                => 'https://www.colisprive.fr/',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'colis-prive',
			),
			array(
				'name'               => 'La Poste',
				'slug'               => 'la-poste-fr',
				'url'                => 'https://www.laposte.fr/outils/track-a-parcel',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'laposte',
			),
			array(
				'name'               => 'Mondial Relay',
				'slug'               => 'mondial-relay-fr',
				'url'                => 'https://www.mondialrelay.fr/suivi-de-colis?numeroExpedition={tracking_number}',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'mondialrelay',
			),
			array(
				'name'               => 'TNT France',
				'slug'               => 'tnt-fr',
				'url'                => 'https://www.tnt.fr/public/suivi_colis/recherche/visubontransport.do',
				'country'            => 'FR',
				'active'             => '',
				'tracking_more_slug' => 'tnt-fr',
			),
			array(
				'name'               => 'DPD UK',
				'slug'               => 'dpd-uk',
				'url'                => 'https://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber={tracking_number}',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'dpd-uk',
			),
			array(
				'name'               => 'Parcelforce UK',
				'slug'               => 'parcelforce-uk',
				'url'                => 'https://www.parcelforce.com/portal/pw/track?trackNumber={tracking_number}',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'parcel-force',
			),
			array(
				'name'               => 'Royal Mail',
				'slug'               => 'royal-mail',
				'url'                => 'https://www.royalmail.com/track-your-item#/tracking-results/{tracking_number}',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'royal-mail',
			),
			array(
				'name'               => 'ArrowXL',
				'slug'               => 'arrowxl',
				'url'                => 'https://www.arrowxl.co.uk/',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'arrowxl',
			),
			array(
				'name'               => 'TNT UK',
				'slug'               => 'tnt-uk',
				'url'                => 'https://www.tnt.com/?searchType=con&cons={tracking_number}',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'tnt-uk',
			),
			array(
				'name'               => 'TNT Reference',
				'slug'               => 'tnt-reference-uk',
				'url'                => 'https://www.tnt.com/express/en_gb/site/shipping-tools/tracking.html?searchType=con&cons={tracking_number}',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => 'tnt-reference',
			),
			array(
				'name'               => 'DHL UK',
				'slug'               => 'dhl-uk',
				'url'                => 'https://www.dhl.co.uk/en/express/tracking.html?AWB={tracking_number}&brand=DHL',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'myHermes UK',
				'slug'               => 'myhermes-uk',
				'url'                => 'https://new.myhermes.co.uk/track.html#/parcel/{tracking_number}/details',
				'country'            => 'GB',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'Aliexpress Standard Shipping',
				'slug'               => 'aliexpress-standard-shipping',
				'url'                => 'https://global.cainiao.com/detail.htm?mailNoList={tracking_number}&spm=a3708.7860688.0.d01',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'cainiao',
			),
			array(
				'name'               => 'DHL Express',
				'slug'               => 'dhl',
				'url'                => 'https://www.dhl.com/en/express/tracking.html?AWB={tracking_number}&brand=DHL',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'dhl',
			),
			array(
				'name'               => 'Aramex',
				'slug'               => 'aramex',
				'url'                => 'https://www.aramex.com/track_results_multiple.aspx?ShipmentNumber={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'aramex',
			),
			array(
				'name'               => 'Direct Link',
				'slug'               => 'direct-link',
				'url'                => 'https://tracking.directlink.com/?itemNumber={tracking_number}&locale=en',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DHL Logistics',
				'slug'               => 'dhl-logistics',
				'url'                => 'https://www.logistics.dhl/global-en/home/tracking/tracking-freight.html?tracking-id={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'GLS',
				'slug'               => 'gls',
				'url'                => 'https://gls-group.eu/EU/en/parcel-tracking?match={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'gls',
			),
			array(
				'name'               => 'Fedex',
				'slug'               => 'fedex',
				'url'                => 'https://www.fedex.com/fedextrack/?cntry_code=us&tracknumbers={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'fedex',
			),
			array(
				'name'               => 'UPS',
				'slug'               => 'ups',
				'url'                => 'https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'ups',
			),
			array(
				'name'               => 'USPS',
				'slug'               => 'usps',
				'url'                => 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'usps',
			),
			array(
				'name'               => 'TNT',
				'slug'               => 'tnt',
				'url'                => 'https://www.tnt.com/?searchType=con&cons={tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'tnt',
			),
			array(
				'name'               => 'Hong Kong Post',
				'slug'               => 'hong-kong-post',
				'url'                => 'https://www.hongkongpost.hk/en/mail_tracking/index.html',
				'country'            => 'HK',
				'active'             => '',
				'tracking_more_slug' => 'hong-kong-post',
			),
			array(
				'name'               => 'Croatia Post',
				'slug'               => 'hrvatska-posta',
				'url'                => 'https://www.posta.hr/tracktrace.aspx?broj={tracking_number}',
				'country'            => 'HR',
				'active'             => '',
				'tracking_more_slug' => 'hrvatska-posta',
			),
			array(
				'name'               => 'DPD IE',
				'slug'               => 'dpd-ie',
				'url'                => 'https://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/{tracking_number}/Default.aspx',
				'country'            => 'IE',
				'active'             => '',
				'tracking_more_slug' => 'dpd-ireland',
			),
			array(
				'name'               => 'An Post',
				'slug'               => 'an-post',
				'url'                => 'https://www.anpost.com/Post-Parcels/Track/History?item={tracking_number}',
				'country'            => 'IE',
				'active'             => '',
				'tracking_more_slug' => 'an-post',
			),
			array(
				'name'               => 'Israel Post',
				'slug'               => 'israel-post',
				'url'                => 'https://mypost.israelpost.co.il/itemtrace?itemcode={tracking_number}',
				'country'            => 'IL',
				'active'             => '',
				'tracking_more_slug' => 'israel-post',
			),
			array(
				'name'               => 'India Post',
				'slug'               => 'india-post',
				'url'                => 'https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx',
				'country'            => 'IN',
				'active'             => '',
				'tracking_more_slug' => 'india-post',
			),
			array(
				'name'               => 'ABF',
				'slug'               => 'abf',
				'url'                => 'https://arcb.com/tools/tracking.html',
				'country'            => 'IN',
				'active'             => '',
				'tracking_more_slug' => 'abf',
			),
			array(
				'name'               => 'Delhivery',
				'slug'               => 'delhivery',
				'url'                => 'https://www.delhivery.com/track/package/{tracking_number}',
				'country'            => 'IN',
				'active'             => '',
				'tracking_more_slug' => 'delhivery',
			),
			array(
				'name'               => 'Ecom Express',
				'slug'               => 'ecom-express',
				'url'                => 'https://ecomexpress.in/tracking/?tflag=0&awb_field={tracking_number}',
				'country'            => 'IN',
				'active'             => '',
				'tracking_more_slug' => 'ecom-express',
			),
			array(
				'name'               => 'DTDC IN',
				'slug'               => 'dtdc-in',
				'url'                => 'https://www.dtdc.in/tracking/tracking_results.asp?Ttype=awb_no&strCnno={tracking_number}&TrkType2=awb_no',
				'country'            => 'IN',
				'active'             => '',
				'tracking_more_slug' => 'dtdc',
			),
			array(
				'name'               => 'TNT IT',
				'slug'               => 'tnt-it',
				'url'                => 'https://www.tnt.it/tracking/Tracking.do',
				'country'            => 'IT',
				'active'             => '',
				'tracking_more_slug' => 'tnt-it',
			),
			array(
				'name'               => 'GLS IT',
				'slug'               => 'gls-it',
				'url'                => 'https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione={tracking_number}&tipo_codice=nazionale',
				'country'            => 'IT',
				'active'             => '',
				'tracking_more_slug' => 'gls-italy',
			),
			array(
				'name'               => 'Japan Post',
				'slug'               => 'japan-post',
				'url'                => 'https://trackings.post.japanpost.jp/services/srv/sequenceNoSearch/?requestNo={tracking_number}&count=100&sequenceNoSearch.x=94&sequenceNoSearch.y=10&locale=en',
				'country'            => 'JP',
				'active'             => '',
				'tracking_more_slug' => 'japan-post',
			),
			array(
				'name'               => 'Korea Post',
				'slug'               => 'korea-post',
				'url'                => 'https://service.epost.go.kr/trace.RetrieveEmsRigiTraceList.comm?ems_gubun=E&sid1=&POST_CODE={tracking_number}',
				'country'            => 'KR',
				'active'             => '',
				'tracking_more_slug' => 'korea-post',
			),
			array(
				'name'               => 'Latvijas Pasts',
				'slug'               => 'latvijas-pasts',
				'url'                => 'https://www.pasts.lv/en/Category/Tracking_of_Postal_Items/',
				'country'            => 'LV',
				'active'             => '',
				'tracking_more_slug' => 'latvijas-pasts',
			),
			array(
				'name'               => 'Monaco EMS',
				'slug'               => 'monaco-ems',
				'url'                => 'https://www.lapostemonaco.mc/',
				'country'            => 'MC',
				'active'             => '',
				'tracking_more_slug' => 'monaco-ems',
			),
			array(
				'name'               => 'Malaysia Post',
				'slug'               => 'malaysia-post',
				'url'                => 'https://www.pos.com.my/postal-services/quick-access/?track-trace',
				'country'            => 'MY',
				'active'             => '',
				'tracking_more_slug' => 'malaysia-post',
			),
			array(
				'name'               => 'Netherlands Post( PostNL )',
				'slug'               => 'postnl',
				'url'                => 'https://jouw.postnl.nl/#!/track-en-trace/{tracking_number}/NL/',
				'country'            => 'NL',
				'active'             => '',
				'tracking_more_slug' => 'netherlands-post',
			),
			array(
				'name'               => 'DPD NL',
				'slug'               => 'dpd-nl',
				'url'                => 'https://track.dpdnl.nl/?parcelnumber={tracking_number}',
				'country'            => 'NL',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DPD Netherlands',
				'slug'               => 'dpd-parcel-nl',
				'url'                => 'https://www.logistics.dhl/nl-en/home/tracking/tracking-parcel.html?tracking-id={tracking_number}',
				'country'            => 'NL',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'Fastway NZ',
				'slug'               => 'fastway-nz',
				'url'                => 'https://www.fastway.co.nz/tools/track?l={tracking_number}',
				'country'            => 'NZ',
				'active'             => '',
				'tracking_more_slug' => 'fastway-nz',
			),
			array(
				'name'               => 'Portugal Post - CTT',
				'slug'               => 'portugal-post-ctt',
				'url'                => 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx?objects={tracking_number}',
				'country'            => 'PT',
				'active'             => '',
				'tracking_more_slug' => 'ctt',
			),
			array(
				'name'               => 'DPD RO',
				'slug'               => 'dpd-ro',
				'url'                => 'https://tracking.dpd.de/parcelstatus?query={tracking_number}&locale=ro_RO',
				'country'            => 'RO',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DHL SE',
				'slug'               => 'dhl-se',
				'url'                => 'https://www.dhl.se/content/se/sv/express/godssoekning.shtml?AWB={tracking_number}&brand=DHL',
				'country'            => 'SE',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'UPS SE',
				'slug'               => 'ups-se',
				'url'                => 'https://wwwapps.ups.com/WebTracking/track?track=yes&loc=sv_SE&trackNums={tracking_number}',
				'country'            => 'SE',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'Singapore Post',
				'slug'               => 'singapore-post',
				'url'                => 'https://www.singpost.com/track-items',
				'country'            => 'SG',
				'active'             => '',
				'tracking_more_slug' => 'singapore-post',
			),
			array(
				'name'               => 'Ninja Van',
				'slug'               => 'ninja-van-sg',
				'url'                => 'https://www.ninjavan.co/en-sg/?tracking_id={tracking_number}',
				'country'            => 'SG',
				'active'             => '',
				'tracking_more_slug' => 'ninjavan',
			),
			array(
				'name'               => 'Roadbull',
				'slug'               => 'roadbull-sg',
				'url'                => 'https://cds.roadbull.com/order/track/{tracking_number}',
				'country'            => 'SG',
				'active'             => '',
				'tracking_more_slug' => 'roadbull',
			),
			array(
				'name'               => 'Ukraine EMS',
				'slug'               => 'ukraine-ems',
				'url'                => 'https://dpsz.ua/',
				'country'            => 'UA',
				'active'             => '',
				'tracking_more_slug' => 'ukraine-ems',
			),
			array(
				'name'               => 'Ukrposhta',
				'slug'               => 'ukrposhta',
				'url'                => 'https://ukrposhta.ua/en/vidslidkuvati-forma-poshuku',
				'country'            => 'UA',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'GSO',
				'slug'               => 'gso',
				'url'                => 'https://www.gso.com/tracking',
				'country'            => 'US',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'DHL Parcel US',
				'slug'               => 'dhl-parcel-us',
				'url'                => 'https://www.logistics.dhl/us-en/home/tracking/tracking-ecommerce.html?tracking-id={tracking_number}',
				'country'            => 'US',
				'active'             => '',
				'tracking_more_slug' => '',
			),
			array(
				'name'               => 'VietNam Post',
				'slug'               => 'vietnam-post',
				'url'                => 'https://www.vnpost.vn/en-us/dinh-vi/buu-pham?key={tracking_number}',
				'country'            => 'VN',
				'active'             => '',
				'tracking_more_slug' => 'vietnam-post',
			),
			array(
				'name'               => '4PX',
				'slug'               => '4px',
				'url'                => 'http://track.4px.com/query/{tracking_number}',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => '4px',
			),
			array(
				'name'               => 'YANWEN',
				'slug'               => 'yanwen',
				'url'                => 'https://track.yw56.com.cn/',
				'country'            => 'Global',
				'active'             => '',
				'tracking_more_slug' => 'yanwen',
			),
			array(
				'name'               => 'DHL ECommerce',
				'slug'               => 'dhlglobalmail',
				'url'                => 'http://webtrack.dhlglobalmail.com/?trackingnumber={tracking_number}',
				'country'            => 'DE',
				'active'             => '',
				'tracking_more_slug' => 'dhlglobalmail',
			),
		);
		if ( $filter ) {
			return apply_filters( 'woo_orders_tracking_defined_shipping_carriers', $carriers );
		} else {
			return $carriers;
		}
	}

	public static function get_delivered_icons() {
		return array(
			'1'  => 'vi_wot_shipment_icons-verified',
			'2'  => 'vi_wot_shipment_icons-tick',
			'3'  => 'vi_wot_shipment_icons-tick-inside-circle',
			'4'  => 'vi_wot_shipment_icons-circle-with-check-symbol',
			'5'  => 'vi_wot_shipment_icons-check-mark',
			'6'  => 'vi_wot_shipment_icons-check',
			'7'  => 'vi_wot_shipment_icons-checkmark',
			'8'  => 'vi_wot_shipment_icons-verified-1',
			'9'  => 'vi_wot_shipment_icons-verification-circular-symbol',
			'10' => 'vi_wot_shipment_icons-checked',
			'11' => 'vi_wot_shipment_icons-checked-1',
			'12' => 'vi_wot_shipment_icons-check-circular-button',
			'13' => 'vi_wot_shipment_icons-check-mark-1',
			'14' => 'vi_wot_shipment_icons-accept-symbol',
			'15' => 'vi_wot_shipment_icons-check-circle',
			'16' => 'vi_wot_shipment_icons-button',
			'17' => 'vi_wot_shipment_icons-time',
			'18' => 'vi_wot_shipment_icons-check-box',
			'19' => 'vi_wot_shipment_icons-tick-1',
			'20' => 'vi_wot_shipment_icons-check-1',
			'21' => 'vi_wot_shipment_icons-check-mark-in-a-circle',
		);
	}

	public static function get_pickup_icons() {
		return array(
			'1'  => 'vi_wot_shipment_icons-delivery-package-opened',
			'2'  => 'vi_wot_shipment_icons-box',
			'3'  => 'vi_wot_shipment_icons-boxes',
			'4'  => 'vi_wot_shipment_icons-trolley',
			'5'  => 'vi_wot_shipment_icons-trolley-1',
			'6'  => 'vi_wot_shipment_icons-trolley-2',
			'7'  => 'vi_wot_shipment_icons-box-1',
			'8'  => 'vi_wot_shipment_icons-box-2',
			'9'  => 'vi_wot_shipment_icons-box-3',
			'10' => 'vi_wot_shipment_icons-cart',
			'11' => 'vi_wot_shipment_icons-box-4',
			'12' => 'vi_wot_shipment_icons-box-5',
			'13' => 'vi_wot_shipment_icons-trolley-3',
			'14' => 'vi_wot_shipment_icons-box-6',
			'15' => 'vi_wot_shipment_icons-trolley-4',
			'16' => 'vi_wot_shipment_icons-cart-1',
			'17' => 'vi_wot_shipment_icons-box-7',
			'18' => 'vi_wot_shipment_icons-trolley-5',
			'19' => 'vi_wot_shipment_icons-box-8',
			'20' => 'vi_wot_shipment_icons-cargo-1',
		);
	}

	public static function get_transit_icons() {
		return array(
			'1'  => 'vi_wot_shipment_icons-fast-delivery',
			'2'  => 'vi_wot_shipment_icons-fast-delivery-1',
			'3'  => 'vi_wot_shipment_icons-fast-delivery-2',
			'4'  => 'vi_wot_shipment_icons-fast-delivery-3',
			'5'  => 'vi_wot_shipment_icons-truck',
			'6'  => 'vi_wot_shipment_icons-delivery',
			'7'  => 'vi_wot_shipment_icons-tracking',
			'8'  => 'vi_wot_shipment_icons-shipped',
			'9'  => 'vi_wot_shipment_icons-delivery-truck',
			'10' => 'vi_wot_shipment_icons-delivery-truck-1',
			'11' => 'vi_wot_shipment_icons-delivery-truck-2',
			'12' => 'vi_wot_shipment_icons-delivery-1',
			'13' => 'vi_wot_shipment_icons-truck-2',
			'14' => 'vi_wot_shipment_icons-lorry',
			'15' => 'vi_wot_shipment_icons-delivery-2',
			'16' => 'vi_wot_shipment_icons-truck-3',
			'17' => 'vi_wot_shipment_icons-truck-4',
			'18' => 'vi_wot_shipment_icons-van',
			'19' => 'vi_wot_shipment_icons-truck-1',
			'20' => 'vi_wot_shipment_icons-shipping',
			'21' => 'vi_wot_shipment_icons-delivery-3',
			'22' => 'vi_wot_shipment_icons-delivery-4',
			'23' => 'vi_wot_shipment_icons-cargo',
			'24' => 'vi_wot_shipment_icons-shipping-1',
		);
	}

	public static function status_text() {
		return array(
			'pending'   => esc_html__( 'Pending', 'woo-orders-tracking' ),
			'transit'   => esc_html__( 'In Transit', 'woo-orders-tracking' ),
			'pickup'    => esc_html__( 'Pickup', 'woo-orders-tracking' ),
			'delivered' => esc_html__( 'Delivered', 'woo-orders-tracking' ),
			'alert'     => esc_html__( 'Alert', 'woo-orders-tracking' ),
		);
	}

	public static function get_status_text( $status ) {
		$statuses = self::status_text();

		return isset( $statuses[ $status ] ) ? $statuses[ $status ] : '';
	}

	public static function convert_status( $status ) {
		if ( ! $status ) {
			return $status;
		} else {
			$status   = strtolower( $status );
			$statuses = array(
				/*cainiao*/
				'pending'                              => 'pending',
				'pickedup'                             => 'pickup',
				'shipping'                             => 'transit',
				'depart_from_original_country'         => 'transit',
				'arrived_at_dest_country'              => 'transit',
				'signin'                               => 'delivered',
				'wait4pickup'                          => 'pickup',
				'wait4signin'                          => 'pickup',
				'order_not_exists'                     => 'alert',
				'signin_exc'                           => 'alert',
				'return'                               => 'alert',
				'depart_from_original_country_exc'     => 'alert',
				'arrived_at_dest_country_exc'          => 'alert',
				'not_lazada_order'                     => 'alert',
				// OWS LIGHT map
				'ows_whcaccept'                        => 'transit',
				'ows_whcoutbound'                      => 'transit',
				'ows_cpaccept'                         => 'transit',
				'ows_delivering'                       => 'transit',
				'ows_wait4signin'                      => 'transit',
				'ows_deliver_fail'                     => 'alert',
				'ows_signin'                           => 'delivered',
				// LTL LIGHT map
				'ltl_consign'                          => 'pickup',
				'ltl_shipping'                         => 'transit',
				'ltl_delivering'                       => 'transit',
				'ltl_wait4signin'                      => 'transit',
				'ltl_deliver_fail'                     => 'alert',
				'ltl_signin'                           => 'delivered',
				// CWS LIGHT map
				'cws_whcaccept'                        => 'pickup',
				'cws_outbound'                         => 'pickup',
				'cws_depart_from_original_country'     => 'transit',
				'cws_arrived_at_dest_country'          => 'transit',
				'cws_depart_from_original_country_exc' => 'alert',
				'cws_arrived_at_dest_country_exc'      => 'alert',
				'cws_wait4signin'                      => 'transit',
				'cws_signin_exc'                       => 'alert',
				'cws_signin'                           => 'delivered',
				// RETURN map
				'returned_stage_start'                 => 'alert',
				'returned_stage_middle'                => 'alert',
				'returned_stage_end'                   => 'alert',
				// DESTORY map
				'rdestoryed_stage_start'               => 'alert',
				'rdestoryed_stage_middle'              => 'alert',
				'rdestoryed_stage_end'                 => 'alert',
			);
			$statuses = array_merge( $statuses, VI_WOO_ORDERS_TRACKING_TRACKINGMORE::map_statuses() );

			return isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
		}
	}

	public static function wp_remote_post( $url, $args = array() ) {
		$return         = array(
			'status' => '',
			'data'   => '',
			'code'   => '',
		);
		$args           = wp_parse_args( $args, array(
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'timeout'    => 10,
			)
		);
		$request        = wp_remote_post( $url, $args );
		$return['code'] = wp_remote_retrieve_response_code( $request );
		if ( ! is_wp_error( $request ) ) {
			$return['status'] = 'success';
			$return['data']   = $request['body'];
		} else {
			$return['status'] = 'error';
			$return['data']   = $request->get_error_message();
		}

		return $return;
	}

	public static function wp_remote_get( $url, $args = array() ) {
		$return         = array(
			'status' => '',
			'data'   => '',
			'code'   => '',
		);
		$args           = wp_parse_args( $args, array(
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'timeout'    => 10,
			)
		);
		$request        = wp_remote_get( $url, $args );
		$return['code'] = wp_remote_retrieve_response_code( $request );
		if ( ! is_wp_error( $request ) ) {
			$return['status'] = 'success';
			$return['data']   = $request['body'];
		} else {
			$return['status'] = 'error';
			$return['data']   = $request->get_error_message();
		}

		return $return;
	}

	/**
	 * @param string $tracking_code
	 * @param string $order_id
	 * @param string $email
	 * @param string $carrier_slug
	 * @param bool $unique When a tracking number is added for more than 1 order item, only return the first found item if true. Otherwise, return all matched items
	 *
	 * @return array
	 */
	public static function search_order_item_by_tracking_number( $tracking_code = '', $order_id = '', $email = '', $carrier_slug = '', $unique = true ) {
		global $wpdb;
		$args  = array();
		$where = array();
		if ( $tracking_code ) {
			$where[] = "vi_wot_woocommerce_order_itemmeta.meta_key IN ('_vi_wot_order_item_tracking_data','_vi_wot_order_item_tracking_data_by_quantity')";
			$where[] = "vi_wot_woocommerce_order_itemmeta.meta_value like %s";
			$args[]  = '%' . $wpdb->esc_like( $tracking_code ) . '%';
		} else {
			$where[] = "vi_wot_woocommerce_order_itemmeta.meta_key IN ('_vi_wot_order_item_tracking_data','_vi_wot_order_item_tracking_data_by_quantity')";
			$where[] = "vi_wot_woocommerce_order_itemmeta.meta_value != ''";
		}
		if ( $order_id ) {
			$where[] = "vi_wot_woocommerce_order_items.order_id=%s";
			$args[]  = $order_id;
		}
		if ( $email ) {
			$query   = "SELECT vi_wot_woocommerce_order_items.*,vi_wot_woocommerce_order_itemmeta.* FROM {$wpdb->prefix}woocommerce_order_items as vi_wot_woocommerce_order_items JOIN {$wpdb->prefix}woocommerce_order_itemmeta as vi_wot_woocommerce_order_itemmeta on vi_wot_woocommerce_order_items.order_item_id=vi_wot_woocommerce_order_itemmeta.order_item_id";
			if (get_option( 'woocommerce_feature_custom_order_tables_enabled' ) === 'yes' && get_option( 'woocommerce_custom_orders_table_data_sync_enabled' ) === 'no'){
				$query .=" JOIN {$wpdb->prefix}wc_orders as vi_wot_postmeta on vi_wot_woocommerce_order_items.order_id=vi_wot_postmeta.id";
				$where[]      = "vi_wot_postmeta.billing_email = %s";
			}else{
				$query .=" JOIN {$wpdb->prefix}postmeta as vi_wot_postmeta on vi_wot_woocommerce_order_items.order_id=vi_wot_postmeta.post_id";
				$where[]      = "vi_wot_postmeta.meta_key='_billing_email'";
				$where[]      = "vi_wot_postmeta.meta_value = %s";
			}
			$args[]  = $email;
		} else {
			$query = "SELECT vi_wot_woocommerce_order_items.*,vi_wot_woocommerce_order_itemmeta.* FROM {$wpdb->prefix}woocommerce_order_items as vi_wot_woocommerce_order_items JOIN {$wpdb->prefix}woocommerce_order_itemmeta as vi_wot_woocommerce_order_itemmeta on vi_wot_woocommerce_order_items.order_item_id=vi_wot_woocommerce_order_itemmeta.order_item_id";
		}
		if ( count( $where ) ) {
			$query .= ' WHERE ' . implode( ' AND ', $where );
		}
		if ( count( $args ) ) {
			$query = $wpdb->prepare( $query, $args );
		}
		$results = $wpdb->get_results( $query, ARRAY_A );
		$return  = array();
		if ( count( $results ) ) {
			if ( $unique ) {
				$found_trackings = array();
				if ( $tracking_code ) {
					if ( $carrier_slug ) {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									$found_tracking        = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
									if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] === $carrier_slug && ! in_array( $found_tracking, $found_trackings ) ) {
										$found_trackings[]                      = $found_tracking;
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$found_tracking = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
										if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] === $carrier_slug && ! in_array( $found_tracking, $found_trackings ) ) {
											$found_trackings[]                      = $found_tracking;
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}
							}
						}
					} else {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									$found_tracking        = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
									/*test*/
									if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] && ! in_array( $found_tracking, $found_trackings ) ) {
//									if ( $current_tracking_data['tracking_number'] == $tracking_code && ! in_array( $found_tracking, $found_trackings ) ) {
										$found_trackings[]                      = $found_tracking;
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$found_tracking = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
										/*test*/
										if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] && ! in_array( $found_tracking, $found_trackings ) ) {
//										if ( $current_tracking_data['tracking_number'] == $tracking_code && ! in_array( $found_tracking, $found_trackings ) ) {
											$found_trackings[]                      = $found_tracking;
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					}
				} else {
					if ( $carrier_slug ) {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									$found_tracking        = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
									if ( $current_tracking_data['carrier_slug'] === $carrier_slug && ! in_array( $found_tracking, $found_trackings ) ) {
										$found_trackings[]                      = $found_tracking;
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$found_tracking = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
										if ( $current_tracking_data['carrier_slug'] === $carrier_slug && ! in_array( $found_tracking, $found_trackings ) ) {
											$found_trackings[]                      = $found_tracking;
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					} else {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									$found_tracking        = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
									if ( ! in_array( $found_tracking, $found_trackings ) ) {
										$found_trackings[]                      = $found_tracking;
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$found_tracking = "{$current_tracking_data['tracking_number']}|{$current_tracking_data['carrier_slug']}";
										if ( ! in_array( $found_tracking, $found_trackings ) ) {
											$found_trackings[]                      = $found_tracking;
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					}
				}
			} else {
				if ( $tracking_code ) {
					if ( $carrier_slug ) {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] === $carrier_slug ) {
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] === $carrier_slug ) {
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					} else {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] ) {
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$current_tracking_data = array_pop( $item_tracking_data );
										if ( $current_tracking_data['tracking_number'] == $tracking_code && $current_tracking_data['carrier_slug'] ) {
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					}
				} else {
					if ( $carrier_slug ) {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data = array_pop( $item_tracking_data );
									if ( $current_tracking_data['carrier_slug'] === $carrier_slug ) {
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
									}
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										if ( $current_tracking_data['carrier_slug'] === $carrier_slug ) {
											$result['tracking_number']              = $current_tracking_data['tracking_number'];
											$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
											$result['tracking_number_carrier_pair'] = array(
												'tracking_number' => $current_tracking_data['tracking_number'],
												'carrier_slug'    => $current_tracking_data['carrier_slug'],
											);
											$return[]                               = $result;
//											break;
										}
									}
								}

							}
						}
					} else {
						foreach ( $results as $result ) {
							if ( $result['meta_value'] ) {
								$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
								if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
									$current_tracking_data                  = array_pop( $item_tracking_data );
									$result['tracking_number']              = $current_tracking_data['tracking_number'];
									$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
									$result['tracking_number_carrier_pair'] = array(
										'tracking_number' => $current_tracking_data['tracking_number'],
										'carrier_slug'    => $current_tracking_data['carrier_slug'],
									);
									$return[]                               = $result;
								} else {
									foreach ( $item_tracking_data as $current_tracking_data ) {
										$result['tracking_number']              = $current_tracking_data['tracking_number'];
										$result['carrier_slug']                 = $current_tracking_data['carrier_slug'];
										$result['tracking_number_carrier_pair'] = array(
											'tracking_number' => $current_tracking_data['tracking_number'],
											'carrier_slug'    => $current_tracking_data['carrier_slug'],
										);
										$return[]                               = $result;
//										break;
									}
								}

							}
						}
					}
				}
			}
		}

		return apply_filters( 'wot_search_order_item_by_tracking_number', $return, $tracking_code, $order_id, $email, $carrier_slug );
	}

	public static function array_search_case_insensitive( $search, $array, $to_lower = true ) {
		$return = false;
		if ( is_array( $array ) && count( $array ) ) {
			if ( function_exists( 'mb_strtolower' ) ) {
				$search = mb_strtolower( $search );
				foreach ( $array as $key => $value ) {
					if ( $search === mb_strtolower( $value ) ) {
						$return = $key;
						if ( $to_lower ) {
							$return = mb_strtolower( $return );
						}
						break;
					}
				}
			} else {
				$search = strtolower( $search );
				foreach ( $array as $key => $value ) {
					if ( $search === strtolower( $value ) ) {
						$return = $key;
						if ( $to_lower ) {
							$return = strtolower( $return );
						}
						break;
					}
				}
			}

		}

		return $return;
	}

	/**
	 * @param string $name
	 *
	 * @return array|mixed|string
	 */
	public static function service_carriers_list( $name = '' ) {
		$list = array(
			'cainiao'      => 'Cainiao',
			'trackingmore' => 'TrackingMore',
			'easypost'     => 'EasyPost',
			'aftership'    => 'AfterShip',
			'17track'      => '17Track',
		);
		if ( $name ) {
			return isset( $list[ $name ] ) ? $list[ $name ] : '';
		} else {
			return $list;
		}
	}

	public static function get_defined_carriers() {
		$instance = self::get_instance();

		return vi_wot_json_decode( $instance->get_params( 'shipping_carriers_define_list' ) );
	}

	public static function get_custom_carriers() {
		$instance = self::get_instance();

		return vi_wot_json_decode( $instance->get_params( 'custom_carriers_list' ) );
	}

	public static function generate_custom_carrier_slug( $carrier_name ) {
		$carriers     = self::get_carriers();
		$carrier_slug = '';
		if ( $carrier_name ) {
			$carrier_slug = sanitize_title( $carrier_name );
			$exist        = array_search( $carrier_slug, array_column( $carriers, 'slug' ) );
			if ( $exist !== false ) {
				$carrier_slug = 'custom_' . time();
			}
		}

		return $carrier_slug;
	}

	/**
	 * @param bool $only_active
	 *
	 * @return array
	 */
	public static function get_carriers( $only_active = false ) {
		$carriers = array_values( array_merge( self::get_defined_carriers(), self::get_custom_carriers() ) );
		if ( $only_active ) {
			$instance         = self::get_instance();
			$active_carriers  = $instance->get_params( 'active_carriers' );
			$active_carriers_ = array();
			if ( $active_carriers ) {
				foreach ( $carriers as $carrier ) {
					if ( in_array( $carrier['slug'], $active_carriers ) ) {
						$active_carriers_[] = $carrier;
					}
				}
			}

			return $active_carriers_;
		} else {
			return $carriers;
		}
	}

	/**
	 * Escape html content
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public static function wp_kses_post( $content ) {
		if ( self::$allow_html === null ) {
			self::$allow_html = wp_kses_allowed_html( 'post' );
			self::$allow_html = array_merge_recursive( self::$allow_html, array(
					'input'  => array(
						'type'         => 1,
						'id'           => 1,
						'name'         => 1,
						'class'        => 1,
						'placeholder'  => 1,
						'autocomplete' => 1,
						'style'        => 1,
						'value'        => 1,
						'size'         => 1,
						'checked'      => 1,
						'disabled'     => 1,
						'readonly'     => 1,
						'data-*'       => 1,
					),
					'form'   => array(
						'method' => 1,
						'id'     => 1,
						'class'  => 1,
						'action' => 1,
						'data-*' => 1,
					),
					'select' => array(
						'id'       => 1,
						'name'     => 1,
						'class'    => 1,
						'multiple' => 1,
						'data-*'   => 1,
					),
					'option' => array(
						'value'    => 1,
						'selected' => 1,
						'data-*'   => 1,
					),
				)
			);
			foreach ( self::$allow_html as $key => $value ) {
				if ( $key === 'input' ) {
					self::$allow_html[ $key ]['data-*']   = 1;
					self::$allow_html[ $key ]['checked']  = 1;
					self::$allow_html[ $key ]['disabled'] = 1;
					self::$allow_html[ $key ]['readonly'] = 1;
				} elseif ( in_array( $key, array( 'div', 'span', 'a', 'form', 'select', 'option', 'tr', 'td' ) ) ) {
					self::$allow_html[ $key ]['data-*'] = 1;
				}
			}
		}

		return wp_kses( $content, self::$allow_html );
	}

	/**
	 * For escaping styles
	 *
	 * @return array
	 */
	public static function extend_post_allowed_style_html() {
		return array_merge( wp_kses_allowed_html( 'post' ), array(
				'style' => array(
					'type'  => 1,
					'id'    => 1,
					'name'  => 1,
					'class' => 1,
				),
			)
		);
	}

	public function get_cache_request_time() {
		return absint( HOUR_IN_SECONDS * $this::get_params( 'service_cache_request' ) );
	}

	public function get_status_text_by_service_carrier( $status ) {
		$status               = strtolower( $status );
		$service_carrier_type = $this->get_params( 'service_carrier_type' );
		$status_text          = '';
		switch ( $service_carrier_type ) {
			case 'trackingmore':
				$status_text = VI_WOO_ORDERS_TRACKING_TRACKINGMORE::get_status_text( $status );
				break;
			default:
		}
		if ( ! $status_text ) {
			$status_text = self::get_status_text( self::convert_status( $status ) );
		}

		return $status_text;
	}
}