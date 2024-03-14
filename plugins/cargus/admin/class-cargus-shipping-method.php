<?php
/**
 * Add woocommerce cargus shipping method.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'Cargus_Shipping_Method' ) ) {
	/**
	 * Add woocommerce cargus shipping method.
	 *
	 * @link       https://cargus.ro/
	 * @since      1.0.0
	 *
	 * @package    Cargus
	 * @subpackage Cargus/admin
	 */
	#[AllowDynamicProperties]
	class Cargus_Shipping_Method extends WC_Shipping_Method {

		/**
		 * The api integration part.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $api    The cargus api object.
		 */
		public $api;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param Int $instance_id The woocommerce shipping method instance id.
		 * @since    1.0.0
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id                 = 'cargus';
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = __( 'Livrare Cargus', 'cargus' );
			$this->method_description = __( 'Livrare la domiciliu Cargus.', 'cargus' );
			$this->supports           = array(
				'shipping-zones',
				'settings',
			);

			$this->load_dependencies();
			$this->init();

			$this->title                   = isset( $this->settings['title'] ) ? $this->settings['title'] : null;
			$this->webservice              = isset( $this->settings['webservice'] ) ? $this->settings['webservice'] : null;
			$this->apikey                  = isset( $this->settings['apikey'] ) ? $this->settings['apikey'] : null;
			$this->username                = isset( $this->settings['username'] ) ? $this->settings['username'] : null;
			$this->password                = isset( $this->settings['password'] ) ? $this->settings['password'] : null;
			$this->pickup                  = isset( $this->settings['pickup'] ) ? $this->settings['pickup'] : null;
			$this->priceplan               = isset( $this->settings['priceplan'] ) ? $this->settings['priceplan'] : null;
			$this->insurance               = isset( $this->settings['insurance'] ) ? $this->settings['insurance'] : null;
			$this->open                    = isset( $this->settings['open'] ) ? $this->settings['open'] : null;
			$this->email_banner            = isset( $this->settings['email-banner'] ) ? $this->settings['email-banner'] : null;
			$this->locations_select        = isset( $this->settings['locations-select'] ) ? $this->settings['locations-select'] : null;
			$this->street_select           = isset( $this->settings['street-select'] ) ? $this->settings['street-select'] : null;
			$this->return                  = isset( $this->settings['return-awb'] ) ? $this->settings['return-awb'] : null;
			$this->return_awb_print        = isset( $this->settings['return-awb-print'] ) ? $this->settings['return-awb-print'] : null;
			$this->return_awb_validity     = isset( $this->settings['awb-validity'] ) ? $this->settings['awb-validity'] : null;
			$this->repayment               = isset( $this->settings['repayment'] ) ? $this->settings['repayment'] : null;
			$this->payer                   = isset( $this->settings['payer'] ) ? $this->settings['payer'] : null;
			$this->type                    = isset( $this->settings['type'] ) ? $this->settings['type'] : null;
			$this->free                    = isset( $this->settings['free'] ) ? $this->settings['free'] : null;
			$this->fixed                   = isset( $this->settings['fixed'] ) ? $this->settings['fixed'] : null;
			$this->buc_fixed               = isset( $this->settings['buc_fixed'] ) ? $this->settings['buc_fixed'] : null;
			$this->shipping_cost_tax       = isset( $this->settings['shipping_cost_tax'] ) ? $this->settings['shipping_cost_tax'] : null;
			$this->height                  = isset( $this->settings['height'] ) ? $this->settings['height'] : null;
			$this->width                   = isset( $this->settings['width'] ) ? $this->settings['width'] : null;
			$this->length                  = isset( $this->settings['length'] ) ? $this->settings['length'] : null;
			$this->service_id              = isset( $this->settings['service_id'] ) ? $this->settings['service_id'] : null;
			$this->parcel_contents         = isset( $this->settings['parcel_contents'] ) ? $this->settings['parcel_contents'] : null;
			$this->print_format            = isset( $this->settings['print_format'] ) ? $this->settings['print_format'] : null;
			$this->order_status_create_awb = isset( $this->settings['order_status_create_awb'] ) ? $this->settings['order_status_create_awb'] : null;
			$this->order_status_remove_awb = isset( $this->settings['order_status_remove_awb'] ) ? $this->settings['order_status_remove_awb'] : null;

			if ( null !== $this->webservice && null !== $this->apikey ) {
				$this->api = new Cargus_Api( $this->webservice, $this->apikey );
			} else {
				$this->api = null;
			}

			if ( get_option( 'cargus_login_token' ) && ! is_object( get_option( 'cargus_login_token' ) ) && ! is_array( get_option( 'cargus_login_token' ) ) && '' !== get_option( 'cargus_login_token' )
				&& property_exists( $this, 'apikey' ) && '' !== $this->apikey
			) {
				$this->token = get_option( 'cargus_login_token' );
				$this->init_extra_fields();
			}
		}

		/**
		 * Include the cargus dependencies.
		 *
		 * @since    1.0.0
		 */
		public function load_dependencies() {

			/**
			 * The class responsible for making the php api call.
			 */
			require_once plugin_dir_path( __FILE__ ) . 'class-cargus-api.php';

			/**
			 * The class responsible for caching.
			 */
			require_once plugin_dir_path( __FILE__ ) . 'class-cargus-cache.php';
		}

		/**
		 * Initialize the shipping fields and settings.
		 *
		 * @since    1.0.0
		 */
		public function init() {
			$this->init_form_fields();
			$this->init_settings();

			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Process the admin options.
		 *
		 *  @since    1.0.0
		 */
		public function process_admin_options() {

			$post_data = $this->get_post_data();
			if ( ! empty( $post_data['woocommerce_cargus_webservice'] ) && ! empty( $post_data['woocommerce_cargus_apikey'] ) ) {

				$this->api = new Cargus_Api( $post_data['woocommerce_cargus_webservice'], $post_data['woocommerce_cargus_apikey'] );

				$fields = array(
					'UserName' => $post_data['woocommerce_cargus_username'],
					'Password' => $post_data['woocommerce_cargus_password'],
				);

				$this->token = $this->api->login_user( $fields, true );

				if ( $this->token && '' !== $this->token && ! is_object( $this->token ) && ! is_array( $this->token ) ) {
					update_option( 'cargus_login_token', $this->token, false );
					$this->init_extra_fields();
				} elseif ( is_array( $this->token ) && ( isset( $this->token['Error'] ) || ( isset( $this->token['statusCode'] ) && 500 === $this->token['statusCode'] ) ) ) {
					add_action( 'admin_notices', array( $this, 'cargus_admin_notice_username_password' ) );
					update_option( 'cargus_login_token', false, false );
				} elseif ( is_object( $this->token ) && ( property_exists( $this->token, 'Error' ) || ( property_exists( $this->token, 'statusCode' ) && 500 === $this->token->statusCode ) ) ) {
					add_action( 'admin_notices', array( $this, 'cargus_admin_notice_username_password' ) );
					update_option( 'cargus_login_token', false, false );
				} elseif ( is_array( $this->token ) && isset( $this->token['statusCode'] ) && 401 === $this->token['statusCode'] ) {
					add_action( 'admin_notices', array( $this, 'cargus_admin_notice_apikey' ) );
					update_option( 'cargus_login_token', false, false );
				} elseif ( is_object( $this->token ) && property_exists( $this->token, 'statusCode' ) && 401 === $this->token->statusCode ) {
					add_action( 'admin_notices', array( $this, 'cargus_admin_notice_apikey' ) );
					update_option( 'cargus_login_token', false, false );
				}

				if ( '' === $post_data['woocommerce_cargus_height'] || '' === $post_data['woocommerce_cargus_width'] || '' === $post_data['woocommerce_cargus_length'] ) {
					add_action( 'admin_notices', array( $this, 'cargus_admin_dimensions_notice' ) );
				}
			}

			parent::process_admin_options();

		}

		/**
		 * Initialize the admin form fields.
		 *
		 *  @since    1.0.0
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'title'      => array(
					'title'   => __( 'Titlu', 'cargus' ),
					'type'    => 'text',
					'default' => __( 'Cargus Livrare la domiciliu', 'cargus' ),
				),
				'webservice' => array(
					'title'   => __( 'URL Webservice', 'cargus' ),
					'type'    => 'text',
					'default' => __( 'https://urgentcargus.azure-api.net/api', 'cargus' ),
				),
				'apikey'     => array(
					'title' => __( 'API Key', 'cargus' ),
					'type'  => 'text',
				),
				'username'   => array(
					'title' => __( 'Nume utilizator', 'cargus' ),
					'type'  => 'text',
					'desc_tip'    => true,
					'description' => __( 'Username cont platforma WebExpress.', 'cargus' ),
				),
				'password'   => array(
					'title' => __( 'Parola', 'cargus' ),
					'type'  => 'password',
					'desc_tip'    => true,
					'description' => __( 'Parola cont platforma WebExpress.', 'cargus' ),
				),
			);
		}

		/**
		 * Initialize the admin extra form fields.
		 *
		 *  @since    1.0.0
		 */
		public function init_extra_fields() {
			// obtine lista punctelor de ridicare.
			$temp    = $this->api->get_pickup_locations();
			update_option( 'cargus_pickup_points', $temp );
			$pickups = array();
			if ( is_array( $temp ) ) {
				foreach ( $temp as $location ) {
					$pickups[ $location->LocationId ] = $location->Name; //phpcs:ignore
				}
			}

			if ( count( $pickups ) > 1 ) {
				unset( $pickups['0'] );
			}

			// obtine lista planurilor tarifare.
			$temp = $this->api->get_price_tables();

			$prices = array();
			if ( is_array( $temp ) ) {
				foreach ( $temp as $price ) {
					$prices[ $price->PriceTableId ] = empty( $price->Name ) ? $price->PriceTableId : $price->Name; //phpcs:ignore
				}
			}

			$extra_fields = array(
				'pickup'                  => array(
					'title'   => __( 'Punct de ridicare', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array( null => __( 'Alege punctul de ridicare', 'cargus' ) ) + $pickups,
				),
				'priceplan'               => array(
					'title'   => __( 'Plan tarifar', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array( null => __( 'Alege planul tarifar', 'cargus' ) ) + $prices,
				),
				'insurance'               => array(
					'title'   => __( 'Asigurare', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				'open'                    => array(
					'title'   => __( 'Deschidere colet', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				'repayment'               => array(
					'title'   => __( 'Mod Incasare ramburs', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'bank' => __( 'Cont colector', 'cargus' ),
						'cash' => __( 'Numerar', 'cargus' ),
					),
				),
				'payer'                   => array(
					'title'   => __( 'Platitor expeditie', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'sender'    => __( 'Expeditor', 'cargus' ),
						'recipient' => __( 'Destinatar', 'cargus' ),
					),
				),
				'type'                    => array(
					'title'   => __( 'Tip expeditie', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'parcel'   => __( 'Colet', 'cargus' ),
						'envelope' => __( 'Plic', 'cargus' ),
					),
				),
				'free'                    => array(
					'title' => __( 'Plafon transport gratuit', 'cargus' ),
					'type'  => 'text',
					'desc_tip'    => true,
					'description' => __( 'Suma dupa care costul transportului de catre cumparator devine 0.', 'cargus' ),
				),
				'fixed'                   => array(
					'title' => __( 'Cost fix transport', 'cargus' ),
					'type'  => 'text',
					'desc_tip'    => true,
					'description' => __( 'Setare pret fix platit de client pentru costul de transport.', 'cargus' ),
				),
				'shipping_cost_tax'       => array(
					'title'   => __( 'Tva inclus în costul de transport calculat', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'no',
				),
				'buc_fixed'               => array(
					'title' => __( 'Cost fix transport București', 'cargus' ),
					'type'  => 'text',
					'desc_tip'    => true,
					'description' => __( 'Setare pret fix platit de client pentru costul de transport pe aria Bucuresti. Lasat necompletat va preluat pretul fix setat pe toata tara.', 'cargus' ),
				),
				'height'                  => array(
					'title'   => __( 'Inaltime (cm)', 'cargus' ),
					'type'    => 'number',
					'default' => 10,
					'desc_tip'    => true,
					'description' => __( 'Inaltime standard colet, se calculeaza automat si poate fi modificata pe fiecare comanda in parte.', 'cargus' ),
				),
				'width'                   => array(
					'title'   => __( 'Latime (cm)', 'cargus' ),
					'type'    => 'number',
					'default' => 10,
					'desc_tip'    => true,
					'description' => __( 'Latime standard colet, se calculeaza automat si poate fi modificata pe fiecare comanda in parte.', 'cargus' ),
				),
				'length'                  => array(
					'title'   => __( 'Lungime (cm)', 'cargus' ),
					'type'    => 'number',
					'default' => 10,
					'desc_tip'    => true,
					'description' => __( 'Lungime standard colet, se calculeaza automat si poate fi modificata pe fiecare comanda in parte.', 'cargus' ),
				),
				'service_id'              => array(
					'title'   => __( 'Id Serviciu', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'1'  => __( '1 Standard', 'cargus' ),
						'34' => __( '34 Economic Standard', 'cargus' ),
						'39' => __( '39 Multipiece', 'cargus' ),
					),
				),
				'email-banner'            => array(
					'title'   => __( 'Email banner Cargus', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc_tip'    => true,
					'description' => __( 'Adaugare banner cargus in email-urile trimise clientilor despre comanda.', 'cargus' ),
				),
				'locations-select'        => array(
					'title'   => __( 'Camp Oras dropdown', 'cargus' ),
					'description' => __( 'Setare nomenclator orase cargus pe campul de oras.', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'yes',
					'desc_tip'    => true,
				),
				'street-select'        => array(
					'title'   => __( 'Camp Strada si camp Numar', 'cargus' ),
					'label'   => __( 'Activ', 'cargus' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'desc_tip'    => true,
					'description' => __( 'Adauga doua noi campuri, unul pentru strada, care va contine nomenclatorul pentru strazi cargus, si unul pentru numar, si ascunde campul pentru adresa.', 'cargus' ),
				),
				'return-awb'              => array(
					'title'   => __( 'Retur cumparator', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'0' => __( 'Nu', 'cargus' ),
						'1' => __( 'Cod retur', 'cargus' ),
						'2' => __( 'Pre-tiparit', 'cargus' ),
					),
				),
				'awb-validity'            => array(
					'title'             => __( 'Validitate AWB', 'cargus' ),
					'type'              => 'number',
					'default'           => 30,
					'custom_attributes' => array(
						'min' => 0,
						'max' => 180,
					),
				),
				'return-awb-print'        => array(
					'title'   => __( 'Printare AWB retur', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'0' => __( 'Nu se va printa AWB-ul de retur', 'cargus' ),
						'1' => __( 'Se vor printa AWB tur + AWB retur standard', 'cargus' ),
						'2' => __( 'Se vor printa AWB tur + AWB retur cu instructiuni', 'cargus' ),
						'3' => __( 'Se vor printa doar AWB retur standard', 'cargus' ),
						'4' => __( 'Se vor printa doar AWB retur cu instructiuni', 'cargus' ),
					),
				),
				'parcel_contents'         => array(
					'title'   => __( 'Descriere conținut pachet', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'null'              => __( 'Blank', 'cargus' ),
						'order-id'          => __( 'Numar Comanda', 'cargus' ),
						'product-tile'      => __( 'Numar Comanda + Titlu produse', 'cargus' ),
						'product-sku'       => __( 'Numar Comanda + Sku produse', 'cargus' ),
						'product-title-sku' => __( 'Numar Comanda + Titlu produse + Sku', 'cargus' ),
					),
					'desc_tip'    => true,
					'description' => __( 'Descrierea continului coletului trecuta pe awb.', 'cargus' ),
				),
				'print_format'            => array(
					'title'   => __( 'Format printare AWB', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						'0' => __( 'A4', 'cargus' ),
						'1' => __( 'Label 10x14', 'cargus' ),
					),
				),
				'order_status_create_awb' => array(
					'title'   => __( 'Status comanda dupa genereare AWB', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						''                  => __( 'Nu schimba statusul comnezii', 'cargus' ),
						'wc-processing'     => __( 'In procesare', 'cargus' ),
						'wc-completed'      => __( 'Finalizata ', 'cargus' ),
						'wc-pending'        => __( 'Plată în așteptare', 'cargus' ),
						'wc-on-hold'        => __( 'În așteptare', 'cargus' ),
						'wc-cancelled'      => __( 'Anulată', 'cargus' ),
						'wc-refunded'       => __( 'Rambursată', 'cargus' ),
						'wc-failed'         => __( 'Eșuată', 'cargus' ),
						'wc-checkout-draft' => __( 'Ciornă', 'cargus' ),
					),
				),
				'order_status_remove_awb' => array(
					'title'   => __( 'Status comanda dupa stergere AWB', 'cargus' ),
					'type'    => 'select',
					'class'   => 'select_height',
					'options' => array(
						''                  => __( 'Nu schimba statusul comnezii', 'cargus' ),
						'wc-processing'     => __( 'In procesare', 'cargus' ),
						'wc-completed'      => __( 'Finalizata ', 'cargus' ),
						'wc-pending'        => __( 'Plată în așteptare', 'cargus' ),
						'wc-on-hold'        => __( 'În așteptare', 'cargus' ),
						'wc-cancelled'      => __( 'Anulată', 'cargus' ),
						'wc-refunded'       => __( 'Rambursată', 'cargus' ),
						'wc-failed'         => __( 'Eșuată', 'cargus' ),
						'wc-checkout-draft' => __( 'Ciornă', 'cargus' ),
					),
				),
			);

			$this->form_fields += apply_filters( 'cargus_shipping_method_extra_fields', $extra_fields );
		}

		/**
		 * Calculate the shipping cost.
		 *
		 * @since    1.0.0
		 * @param Array $package The shipping package data array.
		 */
		public function calculate_shipping( $package = array() ) {
			$calculated_cost = $this->get_shipping_cost( $this, $package );

			if ( ! is_null( $calculated_cost ) ) {
				if ( 0 === $calculated_cost ) {
					$this->title .= apply_filters( 'cargus_shipping_method_title_free', ' - Gratuit');
				}

				$rate = array(
					'id'       => $this->id,
					'label'    => $this->title,
					'cost'     => $calculated_cost,
					'calc_tax' => 'per_order',
				);

				$this->add_rate( $rate );
			}
		}

		/**
		 * Get the calculated the shipping cost.
		 *
		 * @param $cargus_options
		 * @param $package
		 *
		 * @return float|int|string|void|null
		 * @since    1.0.0
		 */
		private function get_shipping_cost( $cargus_options, $package ) {
			try {

				$cargus = new Cargus_Api();
				if ( property_exists( $cargus, 'token' ) && ! is_object( $cargus->token ) && ! is_array( $cargus->token ) &&
				     property_exists( $cargus_options, 'apikey' ) && '' !== $cargus_options->apikey
				) {
					// Get Payemnt method.
					//phpcs:disable
					$available_payment_gateways = WC()->payment_gateways->get_available_payment_gateways();
					if ( isset( $_POST ) && isset( $_POST['payment_method'] ) && isset( $available_payment_gateways[ $_POST['payment_method'] ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ $_POST['payment_method'] ];
					} elseif ( isset( WC()->session->chosen_payment_method ) && isset( $available_payment_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ WC()->session->chosen_payment_method ];
					} elseif ( isset( $available_payment_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$current_payment_gateway = $available_payment_gateways[ get_option( 'woocommerce_default_gateway' ) ];
					} else {
						$current_payment_gateway = current( $available_payment_gateways );
					}
					// Check free shipping coupon.
					//phpcs:enable
					$coupons = WC()->cart->get_coupons();
					if ( $coupons ) {
						foreach ( $coupons as $code => $coupon ) {
							if ( $coupon->is_valid() && $coupon->get_free_shipping() ) {
								return 0;
							}
						}
					}

					// Get total.
					$total = WC()->cart->cart_contents_total + array_sum( WC()->cart->get_cart_contents_taxes() );

					// Get ramburs.
					$ramburs = $total;
					if ( ! in_array( $current_payment_gateway->id, array( 'cod', 'cargus_ship_and_go_payment' ), true ) ) {
						$ramburs = 0;
					}

					// UC check free.
					if ( ! empty( $cargus_options->free ) && $total >= $cargus_options->free ) {
						return 0;
					}

					// UC check fixed.
					$shipping_cost = 0;
					if ( '' !== $cargus_options->fixed && is_numeric( $cargus_options->fixed ) ) {
						$shipping_cost = $cargus_options->fixed;
					}

					// UC check buc fixed.
					if ( '' !== $cargus_options->buc_fixed && is_numeric( $cargus_options->buc_fixed ) && WC()->customer->get_shipping_state() === 'B' ) {
						$shipping_cost = $cargus_options->buc_fixed;
					}

					$discount_percent = apply_filters( 'cargus_add_shipping_discount', 0 );

					if ( 0 !== $shipping_cost && is_int( $discount_percent ) ) {
						$shipping_cost -= ( $discount_percent / 100 * $shipping_cost );
						return $shipping_cost;
					}

					// Get weight.
					$weight = 0.0;
					foreach ( $package['contents'] as $item_id => $values ) {
						$_product = $values['data'];

						if ( $_product->get_weight() === '0' || $_product->get_weight() === '' ) {
							$product_weight = 0.1;
						} else {
							$product_weight = floatval( $_product->get_weight() );
						}

						$weight = $weight + ( $product_weight * floatval( $values['quantity'] ) );
					}

					$weight = ceil( wc_get_weight( $weight, 'kg', get_option( 'woocommerce_weight_unit' ) ) );
					if ( $weight < 1 ) {
						$weight = 1;
					}

					// UC punctul de ridicare default.
					$location = array();
					$cargus_pickup_points = get_option( 'cargus_pickup_points' );
					if ( is_null( $cargus_pickup_points ) || 'error' === $cargus_pickup_points ) {
						return null;
					}

					foreach ( $cargus_pickup_points as $pickup ) {
						if ( $pickup->LocationId == $cargus_options->pickup ) { //phpcs:ignore
							$location['locality_id'] = $pickup->LocalityId; //phpcs:ignore
						}
					}

					if ( empty( $location ) ) {
						return null;
					}

					// UC shipping calculation.
					$fields = array(
						'FromLocalityId'         => $location['locality_id'],
						'ToLocalityId'           => 0,
						'FromCountyName'         => '',
						'FromLocalityName'       => '',
						'ToCountyName'           => trim( $package['destination']['state'] ),
						'ToLocalityName'         => trim( $package['destination']['state'] ) === 'B' ? 'Bucuresti' : trim( Cargus_Admin::cargus_normalize( $package['destination']['city'] ) ),
						'Parcels'                => 'envelope' === $cargus_options->type ? 0 : 1,
						'Envelopes'              => 'envelope' === $cargus_options->type ? 1 : 0,
						'TotalWeight'            => $weight,
						'DeclaredValue'          => 'yes' === $cargus_options->insurance ? $total : 0,
						'CashRepayment'          => ( null === $cargus_options->repayment ) ? 0 : ( 'bank' === $cargus_options->repayment ? 0 : $ramburs ),
						'BankRepayment'          => ( null === $cargus_options->repayment ) ? 0 : ( 'bank' === $cargus_options->repayment ? $ramburs : 0 ),
						'OtherRepayment'         => '',
						'PaymentInstrumentId'    => 0,
						'PaymentInstrumentValue' => 0,
						'OpenPackage'            => 'yes' === $cargus_options->open,
						'ShipmentPayer'          => 'recipient' === $cargus_options->payer ? 2 : 1,
						'SaturdayDelivery'       => false,
						'MorningDelivery'        => false,
					);

					$service = $cargus_options->settings['service_id'] ?? null;

					$fields = Cargus_Admin::get_service_id( $service, $fields, $weight );

					$result = $cargus->get_shipping_calulation( $fields );

					if ( is_null( $result ) || 'error' === $result || ( is_array( $result ) && 'Please send sender locality!' === $result[0] ) || ( is_object( $result ) && property_exists( $result, 'Error' ) ) ) {
						return null;
					}

					if ( 'yes' !== $cargus_options->shipping_cost_tax ) {
						$shipping_cost = $result->Subtotal; //phpcs:ignore
					} else {
						$shipping_cost = $result->GrandTotal; //phpcs:ignore
					}


					if ( 0 !== $shipping_cost && is_int( $discount_percent ) ) {
						$shipping_cost -= ( $discount_percent / 100 * $shipping_cost );
						return $shipping_cost;
					}
				}
			} catch ( Exception $ex ) {
				return null;
			}
		}

		/**
		 * Admin notice for invalid username or password.
		 *
		 *  @since    1.0.0
		 */
		public function cargus_admin_notice_username_password() {
			echo wp_kses_post( '<div class="notice notice-error"><p>' . __( 'Username sau parolă greșită. Reâncarcă pagina și încearcă iar.', 'cargus' ) . '</p></div>' );
		}

		/**
		 * Admin notice for invalid apikey.
		 *
		 *  @since    1.0.0
		 */
		public function cargus_admin_notice_apikey() {
			echo wp_kses_post( '<div class="notice notice-error"><p>' . __( 'Acces refuzat din cauza cheii de api invalidă. Asigurați-vă că furnizați o cheie validă pentru un abonament activ. Reâncarcă pagina și încearcă iar.', 'cargus' ) . '</p></div>' );
		}

		/**
		 * Admin notice for invalid package dimensions.
		 *
		 *  @since    1.0.0
		 */
		public function cargus_admin_dimensions_notice() {
			echo wp_kses_post( '<div class="notice notice-error"><p>' . __( 'Campurile pentru dimensiunile standard a coletului sunt obligatorii.', 'cargus' ) . '</p></div>' );
		}

		/**
		 * Return admin options as a html string.
		 *
		 * @return string
		 */
		public function get_admin_options_html() {
			if ( $this->instance_id ) {
				$settings_html = '<table class="form-table">' . $this->generate_settings_html( $this->get_instance_form_fields(), false ) . '</table>'; // WPCS: XSS ok.
			} else {
				$settings_html = '<table class="form-table">' . $this->generate_settings_html( $this->get_form_fields(), false ) . '</table>'; // WPCS: XSS ok.
			}

			return '<div class="wc-shipping-zone-method-fields">' . $settings_html . '</div>';
		}
	}
}
