<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and methods that impact
 * the admin side functionality of the plugin.
 *
 * @package    Cargus
 * @subpackage Cargus/admin
 * @author     Cargus <contact@cargus.ro>
 */
class Cargus_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The admin folder path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $admin_path The admin folder path of this plugin.
	 */
	private $admin_path;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
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
		/** Load all necessary dependencies */
		add_action( 'wp_loaded', array( $this, 'load_dependencies' ) );
	}

	/**
	 * Include the cargus shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function load_dependencies(): void {
		/**
		 * The class responsible for creating the Cargus api methods.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-api.php';
		/**
		 * The class responsible for creating the Cargus Ship and Go payment gateway.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-ship-and-go-payment.php';
		/**
		 * The class responsible for creating custom meta boxes.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-metaboxes.php';
		/**
		 * The class responsible for creating cargus local cache.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-cache.php';
	}

	/**
	 * Register the JavaScript for the admin-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function cargus_enqueue_scripts(): void {
		global $pagenow;

		if ( ( 'post.php' === $pagenow && ( ( isset( $_GET['post'] ) && 'shop_order' === get_post_type( $_GET['post'] ) ) || ( isset( $_POST['post_ID'] ) && 'shop_order' === get_post_type( $_POST['post_ID'] ) ) ) ) || //phpcs:ignore
		     'admin.php' === $pagenow && ( isset( $_GET['tab'] ) && 'shipping' === $_GET['tab'] ) && ( isset( $_GET['section'] ) && 'cargus' === $_GET['section'] ) ) { //phpcs:ignore
			wp_enqueue_script(
				'cargus-admin',
				plugin_dir_url( __FILE__ ) . 'js/cargus-admin.js',
				array( 'jquery' ),
				$this->version,
				false
			);

			wp_localize_script(
				'cargus-admin',
				'ajax_var',
				array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'ajax_cargus_nonce' ),
				)
			);
		}
	}

	/**
	 * Include the cargus shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_shipping_method(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-shipping-method.php';
	}

	/**
	 * Add the cargus shipping method to the woocommerce shipping methods.
	 *
	 * @param Array $methods The woocommerce shipping methods array.
	 *
	 * @since    1.0.0
	 */
	public function add_cargus_shipping_method( array $methods ): array {
		$methods['cargus'] = 'Cargus_Shipping_Method';

		return $methods;
	}

	/**
	 * Include the cargus Ship&Go payment gateway class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_ship_and_go_payment(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-ship-and-go-payment.php';
	}

	/**
	 * Add the cargus ship and go payment gateway.
	 *
	 * @param Array $methods The woocommerce shipping methods array.
	 *
	 * @since    1.0.0
	 */
	public function cargus_add_ship_and_go_gateway_class( array $methods ): array {
		$methods['Cargus_Ship_And_Go'] = 'Cargus_Ship_And_Go_Payment';

		return $methods;
	}

	/**
	 * Include the cargus saturday shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_saturday_shipping_method(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-saturday.php';
	}

	/**
	 * Add the cargus saturday shipping method to the woocommerce shipping methods.
	 *
	 * @param Array $methods The woocommerce shipping methods array.
	 *
	 * @since    1.0.0
	 */
	public function add_cargus_saturday_shipping_method( array $methods ): array {
		$methods['cargus_saturday'] = 'Cargus_Saturday';

		return $methods;
	}

	/**
	 * Include the cargus pre10 shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_pre10_shipping_method(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-pre10.php';
	}

	/**
	 * Add the cargus pre10 shipping method to the woocommerce shipping methods.
	 *
	 * @param Array $methods The woocommerce shipping methods array.
	 *
	 * @since    1.0.0
	 */
	public function add_cargus_pre10_shipping_method( array $methods ): array {
		$methods['cargus_pre10'] = 'Cargus_Pre10';

		return $methods;
	}

	/**
	 * Include the cargus pre12 shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_pre12_shipping_method(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-pre12.php';
	}

	/**
	 * Add the cargus pre12 shipping method to the woocommerce shipping methods.
	 *
	 * @param Array $methods The woocommerce shipping methods array.
	 *
	 * @since    1.0.0
	 */
	public function add_cargus_pre12_shipping_method( array $methods ): array {
		$methods['cargus_pre12'] = 'Cargus_Pre12';

		return $methods;
	}

	/**
	 * Process the cargus ship and go payment gateway.
	 *
	 * @since    1.0.0
	 */
	public function cargus_process_ship_and_go_gateway_class(): void {
		if ( isset( $_POST['payment_method'] ) && 'cargus_ship_and_go_payment' !== $_POST['payment_method'] ) { //phpcs:ignore
			return;
		}
	}

	/**
	 * Update the order meta with field value.
	 *
	 * @param int $order_id The woocommerce order id.
	 *
	 * @since    1.0.0
	 */
	public function cargus_ship_and_go_payment_update_order_meta( int $order_id ): void {
		if ( isset( $_POST['payment_method'] ) && 'cargus_ship_and_go_payment' !== $_POST['payment_method'] ) { //phpcs:ignore
			return;
		}
	}

	/**
	 * Display field value on the order edit page.
	 *
	 * @param object $order The woocommerce order object.
	 *
	 * @since    1.0.0
	 */
	public function cargus_ship_and_go_checkout_field_display_admin_order_meta( $order ): void {
		$method = get_post_meta( $order->get_id(), '_payment_method', true );
		if ( 'cargus_ship_and_go_payment' !== $method ) {
			return;
		}
	}

	/**
	 * Include the cargus Ship&Go shipping method class.
	 *
	 * @since    1.0.0
	 */
	public function cargus_ship_and_go_shipping(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-cargus-ship-and-go.php';
	}

	/**
	 * Register the Ship and Go shipping method.
	 *
	 * @param array $methods The woocommerce shipping methods array.
	 *
	 * @return array
	 * @since    1.0.0
	 */
	public function cargus_add_ship_and_go_sipping( array $methods ): array {
		$methods['cargus_ship_and_go'] = 'Cargus_Ship_and_go';

		return $methods;
	}

	/**
	 * Add cart error is ship and go is selected as a shipping method but no shipping point are selected.
	 *
	 * @param array $data The cart data array.
	 * @param WP_Error $errors The cart validation error object.
	 *
	 * @since    1.0.0
	 */
	public function cargus_validate_cart( array $data, WP_Error $errors ): void {
		if ( WC()->cart->needs_shipping() ) {
			if ( ! WC()->session->get( 'location_id' ) && in_array( 'cargus_ship_and_go', WC()->session->get( 'chosen_shipping_methods' ), true ) ) {
				$errors->add( 'shipping', __( 'Pentru a continua cu plasarea comenzii prin cargus Ship & Go te rugăm să alegi un punct de livrare.', 'cargus' ) );
			}

			if ( isset( $data['billing_city'] ) && ( '-' === $data['billing_city'] || '' === $data['billing_city'] ) ) {
				$errors->add( 'shipping', __( 'Pentru a continua cu plasarea comenzii te rugam sa alegi o localitate pentru facturare.', 'cargus' ) );
			}

			if ( isset( $data['billing_street_number'] ) && 16 < strlen( $data['billing_street_number'] ) ) {
				$errors->add( 'shipping', __( 'Numarul maxim de caractere pentru numarul strazii este 16.', 'cargus' ) );
			}

			if ( true === $data['ship_to_different_address'] && isset( $data['shipping_city'] ) && ( '-' === $data['shipping_city'] || '' === $data['shipping_city'] ) ) {
				$errors->add( 'shipping', __( 'Pentru a continua cu plasarea comenzii te rugam sa alegi o localitate pentru livrare.', 'cargus' ) );
			}

			if ( true === $data['ship_to_different_address'] && isset( $data['shipping_street_number'] ) && 16 < strlen( $data['shipping_street_number'] ) ) {
				$errors->add( 'shipping', __( 'Numarul maxim de caractere pentru numarul strazii este 16.', 'cargus' ) );
			}
		}
	}

	/**
	 * Normalize diacritics.
	 *
	 * @param String $string The string that will have its diacritics removed.
	 */
	public static function cargus_normalize( string $string ): string {
		$table = array(
			'Š' => 'S',
			'š' => 's',
			'Đ' => 'Dj',
			'đ' => 'dj',
			'Ž' => 'Z',
			'ž' => 'z',
			'Č' => 'C',
			'č' => 'c',
			'Ć' => 'C',
			'ć' => 'c',
			'À' => 'A',
			'Á' => 'A',
			'Â' => 'A',
			'Ã' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'Ă' => 'A',
			'Æ' => 'A',
			'Ç' => 'C',
			'È' => 'E',
			'É' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Ì' => 'I',
			'Í' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ș' => 'S',
			'Ț' => 'T',
			'Ñ' => 'N',
			'Ò' => 'O',
			'Ó' => 'O',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ö' => 'O',
			'Ø' => 'O',
			'Ù' => 'U',
			'Ú' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ý' => 'Y',
			'Þ' => 'B',
			'ß' => 'Ss',
			'à' => 'a',
			'á' => 'a',
			'â' => 'a',
			'ã' => 'a',
			'ä' => 'a',
			'å' => 'a',
			'ă' => 'a',
			'æ' => 'a',
			'ç' => 'c',
			'è' => 'e',
			'é' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'ì' => 'i',
			'í' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'ð' => 'o',
			'ș' => 's',
			'ț' => 't',
			'ñ' => 'n',
			'ò' => 'o',
			'ó' => 'o',
			'ô' => 'o',
			'õ' => 'o',
			'ö' => 'o',
			'ø' => 'o',
			'ù' => 'u',
			'ú' => 'u',
			'û' => 'u',
			'ý' => 'y',
			'ý' => 'y',
			'þ' => 'b',
			'ÿ' => 'y',
			'Ŕ' => 'R',
			'ŕ' => 'r',
		);

		return strtr( $string, $table );
	}

	/**
	 * @param $service
	 * @param array $fields
	 * @param $weight
	 *
	 * @return array
	 */
	public static function get_service_id( $service, array $fields, $weight ): array {
		if ( '1' === $service ) {
			$fields['ServiceId'] = 1;
		} elseif ( '34' === $service ) {
			if ( $weight <= 31 ) {
				$fields['ServiceId'] = 34;
			} elseif ( $weight <= 50 ) {
				$fields['ServiceId'] = 35;
			} else {
				$fields['ServiceId'] = 36;
			}
		} elseif ( '39' === $service ) {
			if ( $weight <= 31 ) {
				$fields['ServiceId'] = 39;
			} elseif ( $weight <= 50 ) {
				$fields['ServiceId'] = 40;
			} else {
				$fields['ServiceId'] = 36;
			}
		}

		return $fields;
	}

	/**
	 * Save the selected location ajax.
	 *
	 * @since    1.0.0
	 */
	public function cargus_ajax_store_selected_box() {
		if ( ! check_ajax_referer( 'ajax_cargus_nonce', 'security' ) ) {
			wp_die( 'Nonce verification failed' );
		}

		if ( isset( $_POST['location_id'] ) && isset( $_POST['location_name'] ) ) {

			WC()->session->set( 'location_id', sanitize_text_field( wp_unslash( $_POST['location_id'] ) ) );
			WC()->session->set( 'location_name', sanitize_text_field( wp_unslash( $_POST['location_name'] ) ) );

			if ( isset( $_POST['location_service_cod'] ) ) {
				WC()->session->set( 'location_service_cod', sanitize_text_field( wp_unslash( $_POST['location_service_cod'] ) ) );
			}

			if ( isset( $_POST['location_accepted_payment_type'] ) ) {
				WC()->session->set( 'location_accepted_payment_type', wc_clean( wp_unslash( $_POST['location_accepted_payment_type'] ) ) ); //phpcs:ignore
			}

			echo 'OK!';
		} else {
			echo 'ERROR: There was a problem saving the Ship & Go Location';
		}
		wp_die();
	}

	/**
	 * Save location id in order meta.
	 *
	 * @param WC_Order $order The woocommerce order object.
	 * @param array $data The cart data array.
	 *
	 * @since    1.0.0
	 */
	public function cargus_before_checkout_create_order( WC_Order $order, array $data ): void {
		if (
			$order->has_shipping_method( 'cargus' ) ||
			$order->has_shipping_method( 'cargus_ship_and_go' ) ||
			$order->has_shipping_method( 'cargus_saturday' ) ||
			$order->has_shipping_method( 'cargus_pre10' ) ||
			$order->has_shipping_method( 'cargus_pre12' ) ) {
			if ( isset( $data["billing_street"] ) && '' !== $data["billing_street"] ) {
				$order->update_meta_data( '_billing_street', $data["billing_street"] );
				$order->update_meta_data( '_shipping_street', $data["billing_street"] );
			}

			if ( isset( $_POST["billing_street_id"] ) && '' !== $_POST["billing_street_id"] ) {
				$order->update_meta_data( '_billing_street_id', $_POST["billing_street_id"] );
				$order->update_meta_data( '_shipping_street_id', $_POST["billing_street_id"] );
			}

			if ( isset( $data["billing_street_number"] ) && '' !== $data["billing_street_number"] ) {
				$order->update_meta_data( '_billing_street_number', $data["billing_street_number"] );
				$order->update_meta_data( '_shipping_street_number', $data["billing_street_number"] );
			}

			if ( isset( $data["shipping_street"] ) && '' !== $data["shipping_street"] ) {
				$order->update_meta_data( '_shipping_street', $data["shipping_street"] );
			}

			if ( isset( $_POST["shipping_street_id"] ) && '' !== $_POST["shipping_street_id"] ) {
				$order->update_meta_data( '_shipping_street_id', $_POST["shipping_street_id"] );
			}

			if ( isset( $data["shipping_street_number"] ) && '' !== $data["shipping_street_number"] ) {
				$order->update_meta_data( '_shipping_street_number', $data["shipping_street_number"] );
			}
		}
		if ( $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
			// set the metadata for the woocommerce ship and go shipping method.
			$order->update_meta_data( '_selected_cargus_location', WC()->session->get( 'location_id' ) );
			$order->update_meta_data( '_selected_cargus_location_name', WC()->session->get( 'location_name' ) );
			$order->update_meta_data( '_selected_cargus_location_service_cod', WC()->session->get( 'location_service_cod' ) );

			// set the metadata for the shipping manager platform.
			$order->update_meta_data( 'shippingmanger_target_pid', WC()->session->get( 'location_id' ) );
			$order->update_meta_data( 'shippingmanger_target_pname', WC()->session->get( 'location_name' ) );
			$order->update_meta_data( 'shippingmanger_target_cid', '63' );
			$order->update_meta_data( 'shippingmanger_target_cname', get_option( 'woocommerce_cargus_ship_and_go_settings' )['method_title'] );
		}

		do_action( 'cargus_update_order_meta_before_checkout', $order, $data );
	}

	/**
	 * Print the cargus awb.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function cargus_print_awbs() {

		/**
		 * Verify the nonce.
		 */
		if ( ! isset( $_GET['cargus_print_awb_nonce'] ) || ! check_ajax_referer( 'cargus_print_awb', 'cargus_print_awb_nonce' ) ) {
			return;
		}

		if ( is_admin() && ( ( isset( $_GET['cargus_print_awb'] ) && '1' === $_GET['cargus_print_awb'] ) || ( isset( $_GET['cargus_print_retur_awb'] ) && '1' === $_GET['cargus_print_retur_awb'] ) ) && isset( $_GET['order_ids'] ) ) {

			header( 'Content-type:application/pdf' );

			$awbs_array = array();
			foreach ( explode( ',', urldecode( wc_clean( wp_unslash( $_GET['order_ids'] ) ) ) ) as $order_id ) { //phpcs:ignore
				$awb          = get_post_meta( $order_id, '_cargus_awb', true );
				$awbs_array[] = $awb;
			}

			$awbs_to_print = wp_json_encode( $awbs_array );

			$cargus         = new Cargus_Api();
			$cargus_options = get_option( 'woocommerce_cargus_settings' );

			if ( property_exists( $cargus, 'token' ) && ! is_object( $cargus->token ) ) {

				do_action( 'cargus_before_print_awb', $awbs_to_print, $cargus );

				$result = '';

				if ( ( isset( $_GET['cargus_print_awb'] ) && '1' === $_GET['cargus_print_awb'] ) || ! isset( $_GET['cargus_print_awb'] ) ) {
					// I have only one print awb button.
					if ( isset( $cargus_options['return-awb'] ) && '2' === $cargus_options['return-awb'] &&
					     isset( $cargus_options['return-awb-print'] ) && in_array( $cargus_options['return-awb-print'], array(
							'1',
							'2'
						), true )
					) {
						// Selected Option for "Retur cumparator" is "Pre-tiparit" and for "Printare AWB retur" is 1 or 2.
						$result = $cargus->call_method(
							'AwbDocuments?barCodes=' . $awbs_to_print . '&type=PDF&format=' . $cargus_options['print_format'] . '&printMainOnce=1&printReturn=' . $cargus_options['return-awb-print'],
							'GET',
							'',
							$cargus->token
						);
					} elseif ( ( isset( $cargus_options['return-awb'] ) && '2' !== $cargus_options['return-awb'] ) || ! isset( $_GET['cargus_print_awb'] ) ) {
						// Selected Option for "Retur cumparator" is not "Pre-tiparit".
						$result = $cargus->call_method(
							'AwbDocuments?barCodes=' . $awbs_to_print . '&type=PDF&format=' . $cargus_options['print_format'] . '&printMainOnce=1',
							'GET',
							'',
							$cargus->token
						);
					}
				} elseif ( isset( $_GET['cargus_print_retur_awb'] ) && '1' === $_GET['cargus_print_retur_awb'] ) {
					// I have also print return awb button.
					if ( isset( $cargus_options['return'] ) && '2' === $cargus_options['return'] &&
					     isset( $cargus_options['return-awb-print'] ) && in_array( $cargus_options['return-awb-print'], array(
							'3',
							'4'
						), true )
					) {
						// Selected Option for "Retur cumparator" is "Pre-tiparit" and for "Printare AWB retur" is 3 or 4.
						$result = $cargus->call_method(
							'AwbDocuments?barCodes=' . $awbs_to_print . '&type=PDF&format=' . $cargus_options['print_format'] . '&printMainOnce=1&printReturn=' . $cargus_options['return-awb-print'],
							'GET',
							'',
							$cargus->token
						);
					}
				} else {
					$result = $cargus->call_method(
						'AwbDocuments?barCodes=' . $awbs_to_print . '&type=PDF&format=' . $cargus_options['print_format'] . '&printMainOnce=1',
						'GET',
						'',
						$cargus->token
					);
				}

				if ( 'error' !== $result ) {
					echo( "<b class='bad'>Awb Documents: </b>" . base64_decode( $result ) ); //phpcs:ignore

					// run hook after awb print.
					do_action( 'cargus_after_print_awb', $awbs_to_print, $result, $cargus );
				} else {
					echo base64_decode( $result ); //phpcs:ignore
				}
			}
			die();
		}

	}

	/**
	 * Adding to admin order list bulk dropdown cargus options.
	 *
	 * @param array $actions The actions available at the bulk actions dropdown list.
	 *
	 * @since 1.0.0
	 */
	public function cargus_bulk_actions_add_awb_option( array $actions ): array {
		$actions['cargus_generate_awb'] = __( 'Cargus Generează Awb', 'cargus' );
		$actions['cargus_delete_awb']   = __( 'Cargus Șterge Awb', 'cargus' );
		$actions['cargus_print_awb']    = __( 'Cargus Printează Awb', 'cargus' );

		$cargus         = new Cargus_Api();
		$cargus_options = get_option( 'woocommerce_cargus_settings' );

		if ( isset( $cargus_options['return'] ) && '2' === $cargus_options['return'] &&
		     isset( $cargus_options['return-awb-print'] ) && in_array( $cargus_options['return-awb-print'], array(
				'3',
				'4'
			), true )
		) {
			$actions['cargus_print_retur_awb'] = __( 'Cargus Printează Awb Retur', 'cargus' );
		}

		do_action( 'cargus_add_bulk_order_actions', $actions );

		return $actions;
	}

	/**
	 * Create the awb for the selected orders.
	 *
	 * @param string $redirect_to The url to redirect the page after to submit.
	 * @param string $action The action selected from bulk actions' dropdown.
	 * @param array $order_ids The order ids selected.
	 *
	 * @since 1.0.0
	 *
	 */
	public function cargus_generate_awb_bulk( string $redirect_to, string $action, array $order_ids ): string {
		if ( 'cargus_generate_awb' !== $action ) {
			return $redirect_to; // Exit.
		}

		$successful_ids = array();
		$failed_ids     = array();

		foreach ( $order_ids as $order_id ) {
			$create_awb = self::cargus_create_awb( $order_id );
			if ( true === $create_awb['success'] ) {
				$successful_ids[] = $order_id;
			} else {
				$failed_ids[ $order_id ] = $create_awb['message'];
			}
		}

		$query_args = array(
			'cargus_generate_awb'  => '1',
			'successful_ids_count' => count( $successful_ids ),
			'successful_ids'       => implode( ',', $successful_ids ),
		);

		if ( ! empty( $failed_ids ) ) {
			$query_args['failed_ids'] = implode(
				',',
				array_map(
					function ( $v, $k ) {
						return sprintf( '%d=>"%s"', $k, $v );
					},
					$failed_ids,
					array_keys( $failed_ids )
				)
			);
		}

		return add_query_arg(
			$query_args,
			admin_url( 'edit.php?post_type=shop_order' )
		);
	}

	/**
	 * Create the order cargus awb.
	 *
	 * @param int $order_id The woocomerce order id.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public static function cargus_create_awb( $order_id ) {
		if ( is_admin() && ! get_post_meta( $order_id, '_cargus_awb', true ) ) {
			// daca nu exista niciun awb atunci adaug unul.
			$cargus         = new Cargus_Api();
			$cargus_options = get_option( 'woocommerce_cargus_settings' );

			// obtin comanda, greutatea si comentariile.
			$order = wc_get_order( $order_id );

			// check if the order has the cargus shipping method.
			if ( ! ( $order->has_shipping_method( 'cargus' ) || $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus_saturday' ) || $order->has_shipping_method( 'cargus_pre10' ) || $order->has_shipping_method( 'cargus_pre12' ) ) ) {
				return array(
					'order_id' => $order_id,
					'success'  => false,
					'message'  => esc_html__( 'Nu este livrata folosing Cargus.' ),
				);
			}

			$products         = $order->get_items();
			$parcel_contents  = array();
			$weight           = 0.0;
			$max_volume       = $max_width = $max_length = $max_height = $rate = 0;

			// Get the dimension unit set in Woocommerce.
			$dimension_unit = get_option( 'woocommerce_dimension_unit' );

			// Calculate the rate to be applied for cm.
			if ( $dimension_unit == 'mm' ) {
				$rate = 0.1;
			} elseif ( $dimension_unit == 'cm' ) {
				$rate = 1;
			} elseif ( $dimension_unit == 'm' ) {
				$rate = 100;
			}

			$package_contents = '';
			if ( 'null' !== $cargus_options['parcel_contents'] ) {
				/* translators: %s is replaced with the order id */
				$package_contents = sprintf( __( 'Comanda numărul: %s. ', 'cargus' ), $order_id );
			}

			foreach ( $products as $p ) {

				$quantity = '';
				if ( $p['quantity'] > 1 ) {
					$quantity = $p['quantity'] . ' x ';
				}
				$_product = wc_get_product( $p->get_product_id() );

				if ( $_product->is_type( 'variable' ) ) {
					$_product = wc_get_product( $p->get_variation_id() );
				}

				if ( 'product-title' === $cargus_options['parcel_contents'] ) {
					$parcel_contents[] = $quantity . ' ' . $_product->get_name();
				} elseif ( ( 'product-sku' === $cargus_options['parcel_contents'] || 'sku' === $cargus_options['parcel_contents'] ) && $_product->get_sku() ) {
					$parcel_contents[] = $quantity . ' ' . $_product->get_sku();
				} elseif ( 'product-title-sku' === $cargus_options['parcel_contents'] && $_product->get_sku() ) {
					$parcel_contents[] = $quantity . ' ' . $_product->get_name() . ' - ' . $_product->get_sku();
				} elseif ( 'order-id' !== $cargus_options['parcel_contents'] && 'null' !== $cargus_options['parcel_contents'] ) {
					$parcel_contents[] = $quantity . ' ' . $_product->get_name();
				}

				if ( $_product->get_weight() === '0' || $_product->get_weight() === '' ) {
					$product_weight = 0.1;
				} else {
					$product_weight = floatval( $_product->get_weight() );
				}

				$weight = $weight + ( $product_weight * floatval( $p['quantity'] ) );

				// Get product dimensions and volume.
				$length = $_product->get_length();
				$width  = $_product->get_width();
				$height = $_product->get_height();
				if ( '' !== $length && '' !== $width && '' !== $height ) {
					$volume = $length * $width * $height;
					if ( $max_volume < $volume ) {
						$max_volume = $volume;
						$max_width  = ceil( $width * $rate );
						$max_length = ceil( $length * $rate );
						$max_height = ceil( $height * $rate );
					}
				}
			}

			$weight = ceil( wc_get_weight( $weight, 'kg', get_option( 'woocommerce_weight_unit' ) ) );
			if ( $weight < 1 ) {
				$weight = 1;
			}

			// determin ramburs-ul.
			$ramburs = $order->get_total();
			if ( ! in_array( $order->get_payment_method(), array( 'cod', 'cargus_ship_and_go_payment' ), true ) ) {
				$ramburs = 0;
			}

			if ( ! $cargus_options['length'] || ! $cargus_options['width'] || ! $cargus_options['height'] ) {
				add_action(
					'admin_notices',
					function () {
						/* translators: %s is replaced with the order id */
						printf( '<div class="notice notice-error"><p>' . esc_html__( 'Vă rugam sa introduceți dimensiunile coletului pentru comanda #%s.', 'cargus' ) . '</p></div>', esc_html( $order_id ) );
					},
					20
				);

				$order->add_order_note( 'Vă rugam sa introduceți dimensiunile coletului standard sau dimensiunile produselor din comanda.', 0 );
				return array(
					'order_id' => $order_id,
					'success'  => false,
					'message'  => esc_html__( 'Nu sunt introduse dimensiunile coletului.' ),
				);
			}

			if ( property_exists( $cargus, 'token' ) && ! is_object( $cargus->token ) ) {

				$fields = array(
					'Sender'           => array(
						'LocationId' => $cargus_options['pickup'],
					),
					'Recipient'        => array(
						'LocationId'     => null,
						'Name'           => trim( $order->get_shipping_company() ) != '' ? trim( $order->get_shipping_company() ) : trim( $order->get_formatted_shipping_full_name() ),
						'CountyId'       => null,
						'CountyName'     => trim( $order->get_shipping_state() ),
						'LocalityId'     => null,
						'LocalityName'   => trim( self::cargus_normalize( $order->get_shipping_city() ) ),
						'StreetName'     => trim( $order->get_shipping_address_1() ),
						'AddressText'    => trim( $order->get_shipping_address_2() ),
						'ContactPerson'  => trim( $order->get_formatted_shipping_full_name() ),
						'PhoneNumber'    => trim( $order->get_billing_phone() ),
						'Email'          => trim( $order->get_billing_email() ),
					),
					'Parcels'          => 'envelope' === $cargus_options['type'] ? 0 : 1,
					'Envelopes'        => 'envelope' === $cargus_options['type'] ? 1 : 0,
					'TotalWeight'      => $weight,
					'DeclaredValue'    => 'yes' === $cargus_options['insurance'] ? ( $order->get_total() - $order->get_shipping_total() ) : 0,
					'CashRepayment'    => ( null === $cargus_options['repayment'] ) ? 0 : ( 'bank' === $cargus_options['repayment'] ? 0 : (float) $ramburs ),
					'BankRepayment'    => ( null === $cargus_options['repayment'] ) ? 0 : ( 'bank' === $cargus_options['repayment'] ? (float) $ramburs : 0 ),
					'OtherRepayment'   => '',
					'OpenPackage'      => 'yes' === $cargus_options['open'],
					'ShipmentPayer'    => 'recipient' === $cargus_options['payer'] ? 2 : 1,
					'Observations'     => '',
					'PackageContent'   => $package_contents,
					'CustomString'     => $order_id,
					'PriceTableId'     => $cargus_options['priceplan'],
					'SaturdayDelivery' => false,
					'MorningDelivery'  => false,
				);

				if ( $order->has_shipping_method( 'cargus_saturday' ) ) {
					$fields['SaturdayDelivery'] = true;
				}

				if ( $order->has_shipping_method( 'cargus_pre10' ) ) {
					$fields['DeliveryTime'] = 10;
				}

				if ( $order->has_shipping_method( 'cargus_pre12' ) ) {
					$fields['DeliveryTime'] = 12;
				}

				if ( 'yes' === $cargus_options['locations-select'] && ( ! isset( $cargus_options['street-select'] ) || 'yes' === $cargus_options['street-select'] ) ) {
					$fields['Recipient']['StreetId']       = substr( trim( get_post_meta( $order_id, '_shipping_street_id', true ) ?? null ), 0, 16 );
					$fields['Recipient']['BuildingNumber'] = substr( trim( get_post_meta( $order_id, '_shipping_street_number', true ) ?? null ), 0, 16 );
				}

				if ( isset( $cargus_options['return-awb'] ) ) {
					$fields['ConsumerReturnType'] = $cargus_options['return-awb'];

					if ( in_array( $cargus_options['return-awb'], array(
							'1',
							'2'
						), true ) && isset( $cargus_options['awb-validity'] ) ) {
						$fields['ReturnCodeExpirationDays'] = $cargus_options['awb-validity'];
					}
				}

				$service = $cargus_options['service_id'] ?? null;

				// set the service id.
				$fields = self::get_service_id( $service, $fields, $weight );

				// check if the order will be shipped to ship and go point.
				if ( $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
					$fields['CashRepayment']     = 0;
					$fields['DeliveryPudoPoint'] = get_post_meta( $order_id, '_selected_cargus_location', true );
					$fields['ServiceId']         = 38;
					$fields['ShipmentPayer']     = 1;

					unset( $fields['OpenPackage'] );
				}

				// add postcode is the order has it.
				if ( trim( $order->get_shipping_postcode() ) ) {
					$fields['Recipient']['CodPostal'] = trim( $order->get_shipping_postcode() );
				}

				// check for order override options.
				if ( '34' === $cargus_options['service_id'] || $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
					if ( get_post_meta( $order_id, 'cargus_greutate_comanda', true ) && '' !== get_post_meta( $order_id, 'cargus_greutate_comanda', true ) ) {
						$fields['TotalWeight'] = get_post_meta( $order_id, 'cargus_greutate_comanda', true );
					}

					$fields['ParcelCodes'] = array(
						array(
							'Code'          => 0,
							'Weight'        => ( get_post_meta( $order_id, 'cargus_greutate_comanda', true ) && '' !== get_post_meta( $order_id, 'cargus_greutate_comanda', true ) ) ? get_post_meta( $order_id, 'cargus_greutate_comanda', true ) : $weight,
							'Length'        => ( get_post_meta( $order_id, 'cargus_lungime_comanda', true ) && '' !== get_post_meta( $order_id, 'cargus_lungime_comanda', true ) ) ? get_post_meta( $order_id, 'cargus_lungime_comanda', true ) : ( ( 0 !== $max_length ) ? $max_length : ( ( isset( $cargus_options['length'] ) ) ? $cargus_options['length'] : null ) ),
							'Width'         => ( get_post_meta( $order_id, 'cargus_latime_comanda', true ) && '' !== get_post_meta( $order_id, 'cargus_latime_comanda', true ) ) ? get_post_meta( $order_id, 'cargus_latime_comanda', true ) : ( ( 0 !== $max_width ) ? $max_width : ( ( isset( $cargus_options['width'] ) ) ? $cargus_options['width'] : null ) ),
							'Height'        => ( get_post_meta( $order_id, 'cargus_inaltime_comanda', true ) && '' !== get_post_meta( $order_id, 'cargus_inaltime_comanda', true ) ) ? get_post_meta( $order_id, 'cargus_inaltime_comanda', true ) : ( ( 0 !== $max_height ) ? $max_height : ( ( isset( $cargus_options['height'] ) ) ? $cargus_options['height'] : null ) ),
							'ParcelContent' => ( 'order-id' !== $cargus_options['parcel_contents'] && 'null' !== $cargus_options['parcel_contents'] ) ? 'Produse: ' . implode( ' | ', $parcel_contents ) : '',
						),
					);

					if ( get_post_meta( $order_id, 'cargus_tip_colet', true ) && '' !== get_post_meta( $order_id, 'cargus_tip_colet', true ) ) {
						$fields['ParcelCodes'][0]['Type'] = 'envelope' === get_post_meta( $order_id, 'cargus_tip_colet', true ) ? 0 : 1;
						$fields['Parcels']                = 'envelope' === get_post_meta( $order_id, 'cargus_tip_colet', true ) ? 0 : 1;
						$fields['Envelopes']              = 'envelope' === get_post_meta( $order_id, 'cargus_tip_colet', true ) ? 1 : 0;
					} else {
						$fields['ParcelCodes'][0]['Type'] = 'envelope' === $cargus_options['type'] ? 0 : 1;
					}

					if ( (int) $fields['TotalWeight'] > 1 && 1 === $fields['Envelopes'] ) {
						$fields['TotalWeight'] = 1;
						update_post_meta( $order_id, 'cargus_greutate_comanda', '1' );
					}
				} elseif ( get_post_meta( $order_id, 'cargus_colete_comanda', true ) ) {

					$parcel_codes = array();
					$colete_no    = get_post_meta( $order_id, 'cargus_colete_comanda', true );
					$total_weight = 0;

					if ( get_post_meta( $order_id, 'cargus_tip_colet', true ) && '' !== get_post_meta( $order_id, 'cargus_tip_colet', true ) ) {
						$fields['Parcels']   = 'envelope' === get_post_meta( $order_id, 'cargus_tip_colet', true ) ? 0 : (int) $colete_no;
						$fields['Envelopes'] = 'envelope' === get_post_meta( $order_id, 'cargus_tip_colet', true ) ? (int) $colete_no : 0;
					} else {
						$fields['Parcels']   = 'envelope' === $cargus_options['type'] ? 0 : (int) $colete_no;
						$fields['Envelopes'] = 'envelope' === $cargus_options['type'] ? (int) $colete_no : 0;
					}

					for ( $i = 0; $i < (int) $colete_no; $i ++ ) {
						$total_weight += unserialize( get_post_meta( $order_id, 'cargus_greutate_colet', true ) )[ $i ];

						$fields['ParcelCodes'][ $i ] = array(
							'Code' => 0,
							'Type' => get_post_meta( $order_id, 'cargus_tip_colet', true ) === 'envelope' ? 0 : 1,
						);

						if ( get_post_meta( $order_id, 'cargus_greutate_colet', true ) ) {
							$fields['ParcelCodes'][ $i ]['Weight'] = unserialize( get_post_meta( $order_id, 'cargus_greutate_colet', true ) )[ $i ];
						}

						if ( get_post_meta( $order_id, 'cargus_lungime_colet', true ) ) {
							$fields['ParcelCodes'][ $i ]['Length'] = unserialize( get_post_meta( $order_id, 'cargus_lungime_colet', true ) )[ $i ];
						}
						if ( get_post_meta( $order_id, 'cargus_latime_colet', true ) ) {
							$fields['ParcelCodes'][ $i ]['Width'] = unserialize( get_post_meta( $order_id, 'cargus_latime_colet', true ) )[ $i ];
						}
						if ( get_post_meta( $order_id, 'cargus_inaltime_colet', true ) ) {
							$fields['ParcelCodes'][ $i ]['Height'] = unserialize( get_post_meta( $order_id, 'cargus_inaltime_colet', true ) )[ $i ];
						}

						if ( get_post_meta( $order_id, 'cargus_continut_colet', true ) ) {
							foreach ( unserialize( get_post_meta( $order_id, 'cargus_continut_colet', true ) )[ $i ] as $product_id ) {
								$product        = wc_get_product( $product_id );
								$parcel_content = array();
								if ( 'product-title' === $cargus_options['parcel_contents'] ) {
									$parcel_content[ $i ] = $product->get_name();
								} elseif ( ( 'product-sku' === $cargus_options['parcel_contents'] || 'sku' === $cargus_options['parcel_contents'] ) && $product->get_sku() ) {
									$parcel_content[ $i ] = $product->get_sku();
								} elseif ( 'product-title-sku' === $cargus_options['parcel_contents'] && $product->get_sku() ) {
									$parcel_content[ $i ] = $product->get_name() . ' - ' . $product->get_sku();
								} elseif ( 'order-id' !== $cargus_options['parcel_contents'] && 'null' !== $cargus_options['parcel_contents'] ) {
									$parcel_content[ $i ] = $product->get_name();
								}
							}
							$fields['ParcelCodes'][ $i ]['ParcelContent'] = ( 'order-id' !== $cargus_options['parcel_contents'] && 'null' !== $cargus_options['parcel_contents'] ) ? 'Produse: ' . implode( ' | ', $parcel_contents ) : '';
						}
					}
					if ( (int) $total_weight > 0 ) {
						$fields['TotalWeight'] = $total_weight;
					}

					if ( (int) $fields['TotalWeight'] > 1 && 0 !== $fields['Envelopes'] ) {
						$fields['TotalWeight'] = 1;
					}
				} else {
					$fields['ParcelCodes'] = array(
						array(
							'Code'          => 0,
							'Type'          => 'envelope' === $cargus_options['type'] ? 0 : 1,
							'Weight'        => $weight,
							'Length'        => ( 0 !== $max_length ) ? $max_length : ( ( isset( $cargus_options['length'] ) ) ? $cargus_options['length'] : null ),
							'Width'         => ( 0 !== $max_width ) ? $max_width : ( ( isset( $cargus_options['width'] ) ) ? $cargus_options['width'] : null ),
							'Height'        => ( 0 !== $max_height ) ? $max_height : ( ( isset( $cargus_options['height'] ) ) ? $cargus_options['height'] : null ),
							'ParcelContent' => ( 'order-id' !== $cargus_options['parcel_contents'] && 'null' !== $cargus_options['parcel_contents'] ) ? 'Produse: ' . implode( ' | ', $parcel_contents ) : '',
						),
					);
				}

				if ( get_post_meta( $order_id, 'cargus_deschidere_colete', true ) === 'on' ) {
					$fields['OpenPackage'] = true;
				}

				if ( get_post_meta( $order_id, 'cargus_valoare_ramburs', true ) ) {
					$fields['CashRepayment'] = ( null === $cargus_options['repayment'] ) ? 0 : ( 'bank' === $cargus_options['repayment'] ? 0 : (float) get_post_meta( $order_id, 'cargus_valoare_ramburs', true ) );
					$fields['BankRepayment'] = ( null === $cargus_options['repayment'] ) ? 0 : ( 'bank' === $cargus_options['repayment'] ? (float) get_post_meta( $order_id, 'cargus_valoare_ramburs', true ) : 0 );
				}

				$request_body = apply_filters( 'cargus_before_create_awb_fields', $fields, $order_id, $cargus );
				do_action( 'cargus_before_create_awb', $order_id, $cargus, $request_body );
				$awbs = $cargus->call_method( 'Awbs/WithgetAwb', 'POST', $request_body, $cargus->token );

				foreach ( $awbs as $awb ) {
					if ( is_object( $awb ) && ! property_exists( $awb, 'Error' ) ) {
						if ( property_exists( $awb, 'BarCode' ) ) {
							$order->add_order_note( 'Expeditia Cargus cu numarul #' . $awb->BarCode . ' a fost creata!', 0 ); //phpcs:ignore
							update_post_meta( $order_id, '_cargus_awb', $awb->BarCode );//phpcs:ignore
						}

						if ( isset( $cargus_options['return-awb'] ) && '1' === $cargus_options['return-awb'] && property_exists( $awb, 'ReturnCode' ) && '' !== $awb->ReturnCode ) {
							update_post_meta( $order_id, '_cargus_return_code', $awb->ReturnCode );//phpcs:ignore
						}

						$order_status = $order->get_status();

						if ( isset( $cargus_options['order_status_create_awb'] ) && '' !== $cargus_options['order_status_create_awb'] && 'wc-completed' !== $order_status ) {
							$order->update_status( $cargus_options['order_status_create_awb'] );
						}

						return array(
							'order_id' => $order_id,
							'awb'      => $awb->BarCode,//phpcs:ignore
							'success'  => true,
						);

					} elseif ( is_object( $awb ) && property_exists( $awb, 'Error' ) ) {
						$order->add_order_note( 'Eroare la crearea expeditiei: ' . $awb->Error, 0 );//phpcs:ignore

						return array(
							'order_id' => $order_id,
							'success'  => false,
							/* translators: %s is replaced with error reason */
							'message'  => sprintf( esc_html__( 'Eroare la crearea expeditiei: %s', 'cargus' ), $awb->Error ),
							//phpcs:ignore
						);

					} elseif ( is_array( $awb ) ) {
						$error_string = '';
						foreach ( $awb as $item ) {
							$error_string .= $item . ' ';
							if ( count( $awb ) > 1 && end( $awb ) != $item ) {
								$error_string .= ',';
							}
						}
						$order->add_order_note( 'Eroare la creerea expeditiei: ' . $error_string, 0 );

						return array(
							'order_id' => $order_id,
							'success'  => false,
							/* translators: %s is replaced with error reason */
							'message'  => sprintf( esc_html__( 'Eroare la crearea expeditiei: %s', 'cargus' ), $error_string ),
						);

					} elseif ( is_string( $awb ) ) {
						$order->add_order_note( 'Eroare la crearea expeditiei: ' . $awb, 0 );//phpcs:ignore

						return array(
							'order_id' => $order_id,
							'success'  => false,
							/* translators: %s is replaced with error reason */
							'message'  => sprintf( esc_html__( 'Eroare la crearea expeditiei: %s', 'cargus' ), $awb ),
							//phpcs:ignore
						);

					} else {
						$order->add_order_note( 'Eroare la creerea expeditiei', 0 );

						return array(
							'order_id' => $order_id,
							'success'  => false,
							'message'  => esc_html__( 'Eroare la crearea expeditiei', 'cargus' ),
						);
					}
				}
			} else {
				return array(
					'order_id' => $order_id,
					'success'  => false,
					'message'  => esc_html__( 'Token invalid.' ),
				);
			}
		} else {
			return array(
				'order_id' => $order_id,
				'success'  => false,
				'message'  => esc_html__( 'AWB-ul a fost deja generat.' ),
			);
		}
	}

	/**
	 * Create the admin notice for generating cargus awb in bulk.
	 *
	 * @since 1.0.0
	 */
	public function cargus_generate_awb_bulk_notice() {
		//phpcs:disable
		if ( empty( $_REQUEST['cargus_generate_awb'] ) ) {
			return; // Exit.
		}

		$count     = intval( $_REQUEST['successful_ids_count'] );
		$order_ids = wc_clean( wp_unslash( $_REQUEST['successful_ids'] ) );

		if ( ( int ) $count > 0 ) {
			printf(
				wp_kses_post(
					'<div class="notice notice-success is-dismissible"><p>' .
					_n(
						'A fost generat awb pentru %s comanda: ',
						'Au fost generate awb-uri pentru %s comments: ',
						$count,
						'cargus'
					) . esc_html( $order_ids ) . '.</p></div>'
				),
				esc_html( $count )
			);
		}

		if ( isset( $_REQUEST['failed_ids'] ) ) {
			$failed_ids_array = array();
			$failed_ids       = wc_clean( wp_unslash( $_REQUEST['failed_ids'] ) );
			foreach ( explode( ',', $failed_ids ) as $failed_id ) {
				$failed_ids_array[ explode( '=', $failed_id )[0] ] = explode( '=', $failed_id )[1];
			}

			if ( is_array( $failed_ids_array ) && ! empty( $failed_ids_array ) ) {
				foreach ( $failed_ids_array as $failed_id => $message ) {
					printf(
						wp_kses_post(
							'<div class="notice notice-error is-dismissible"><p>' .
							__(
								'Crearea AWB-ului pentru comanda cu numarul: %1$s a esuat. %2$s',
								'cargus'
							) . '</p></div>'
						),
						esc_html( $failed_id ), esc_html( $message )
					);
				}
			}
		}
		//phpcs:enable
	}

	/**
	 * Delete the awb for the selected orders.
	 *
	 * @param string $redirect_to The url to redirect the page after submit.
	 * @param string $action The action selected from bulk actions' dropdown.
	 * @param array $order_ids The order ids selected.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 *
	 */
	public function cargus_delete_awb_bulk( string $redirect_to, string $action, array $order_ids ): string {
		if ( 'cargus_delete_awb' !== $action ) {
			return $redirect_to; // Exit.
		}

		$successful_ids = array();
		$failed_ids     = array();

		foreach ( $order_ids as $order_id ) {
			$delete_awb = $this->cargus_delete_awb( $order_id, true );
			if ( true === $delete_awb['success'] ) {
				$successful_ids[] = $order_id;
			} else {
				$failed_ids[ $order_id ] = $delete_awb['message'];
			}
		}

		$query_args = array(
			'cargus_delete_awb'    => '1',
			'successful_ids_count' => count( $successful_ids ),
			'successful_ids'       => implode( ',', $successful_ids ),
		);

		if ( ! empty( $failed_ids ) ) {
			$query_args['failed_ids'] = implode(
				',',
				array_map(
					function ( $v, $k ) {
						return sprintf( '%d=>"%s"', $k, $v );
					},
					$failed_ids,
					array_keys( $failed_ids )
				)
			);
		}

		return add_query_arg(
			$query_args,
			admin_url( 'edit.php?post_type=shop_order' )
		);
	}

	/**
	 * Delete the order cargus awb.
	 *
	 * @param int $order_id The woocommerce order id.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	private function cargus_delete_awb( int $order_id ) {
		$order = wc_get_order( $order_id );
		// obtin comanda.

		// check if the order has the cargus shipping method.
		if ( ! ( $order->has_shipping_method( 'cargus' ) || $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus_saturday' ) || $order->has_shipping_method( 'cargus_pre10' ) || $order->has_shipping_method( 'cargus_pre12' ) ) ) {
			return array(
				'order_id' => $order_id,
				'success'  => false,
				'message'  => esc_html__( 'Nu este livrata folosing Cargus.' ),
			);
		}

		$awb = get_post_meta( $order_id, '_cargus_awb', true );
		if ( is_admin() && $awb ) {

			$cargus         = new Cargus_Api();
			$cargus_options = get_option( 'woocommerce_cargus_settings' );

			if ( property_exists( $cargus, 'token' ) && ! is_object( $cargus->token ) ) {

				do_action( 'cargus_before_delete_awb', $order_id, $order, $cargus );

				// sterg awb-ul din api cargus.
				$result = $cargus->call_method( 'Awbs?barCode=' . $awb, 'DELETE', array(), $cargus->token );

				if ( $result && 'error' !== $result ) {
					/* translators: %s is replaced with awb number */
					$order->add_order_note( sprintf( __( 'Expeditia Cargus cu numarul #%s a fost ștearsă!', 'cargus' ), $awb ), 0 );
					delete_post_meta( $order_id, '_cargus_awb', $awb );

					if ( isset( $cargus_options['order_status_remove_awb'] ) && '' !== $cargus_options['order_status_remove_awb'] ) {
						$order->update_status( $cargus_options['order_status_remove_awb'] );
					}

					return array(
						'order_id' => $order_id,
						'success'  => true,
					);
				}
			} else {
				return array(
					'order_id' => $order_id,
					'success'  => false,
					'message'  => esc_html__( 'Token invalid.' ),
				);
			}
		}
	}

	/**
	 * Create the admin notice for deleting cargus awb in bulk.
	 *
	 * @since 1.0.0
	 */
	public function cargus_delete_awb_bulk_notice(): void {
		//phpcs:disable
		if ( empty( $_REQUEST['cargus_delete_awb'] ) ) {
			return; // Exit
		}

		$count     = intval( $_REQUEST['processed_count'] );
		$order_ids = wc_clean( wp_unslash( $_REQUEST['processed_ids'] ) );

		if ( ( int ) $count > 0 ) {
			printf(
				wp_kses_post(
					'<div class="notice notice-success is-dismissible"><p>' .
					_n(
						'A fost sters awb-ul pentru %s comanda: ',
						'Au fost șterse awb-urile pentru %s comenzi: ',
						$count,
						'cargus'
					) . esc_html( $order_ids ) . '.</p></div>'
				),
				esc_html( $count )
			);
		}

		if ( isset( $_REQUEST['failed_ids'] ) ) {
			$failed_ids_array = array();
			$failed_ids       = wc_clean( wp_unslash( $_REQUEST['failed_ids'] ) );
			foreach ( explode( ',', $failed_ids ) as $failed_id ) {
				$failed_ids_array[ explode( '=', $failed_id )[0] ] = explode( '=', $failed_id )[1];
			}

			if ( is_array( $failed_ids_array ) && ! empty( $failed_ids_array ) ) {
				foreach ( $failed_ids_array as $failed_id => $message ) {
					printf(
						wp_kses_post(
							'<div class="notice notice-error is-dismissible"><p>' .
							__(
								'Stergerea AWB-ului pentru comanda cu numarul: %1$s a esuat. %2$s',
								'cargus'
							) . '</p></div>'
						),
						esc_html( $failed_id ), esc_html( $message )
					);
				}
			}
		}
		//phpcs:enable
	}

	/**
	 * Print the awb for the selected orders.
	 *
	 * @param string $redirect_to The url to redirect the page after submit.
	 * @param string $action The action selected from bulk actions' dropdown.
	 * @param array $order_ids The order ids selected.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 *
	 */
	public function cargus_print_awb_bulk( string $redirect_to, string $action, array $order_ids ): string {
		if ( ! in_array( $action, array( 'cargus_print_awb', 'cargus_print_retur_awb' ), true ) ) {
			return $redirect_to; // Exit.
		}

		$processed_ids = array();
		$awb           = array();
		foreach ( $order_ids as $order_id ) {
			$awb = get_post_meta( $order_id, '_cargus_awb', true );
			if ( ! $awb ) {
				$create_awb = self::cargus_create_awb( $order_id );
				if ( $create_awb['order_id'] ) {
					$processed_ids[] = $order_id;
				}
			} elseif ( $awb ) {
				$processed_ids[] = $order_id;
			}
		}

		$query_args = array(
			'cargus_print_awb' => '1',
			'processed_count'  => count( $processed_ids ),
			'processed_ids'    => implode( ',', $processed_ids ),
		);

		if ( 'cargus_print_retur_awb' === $action ) {
			unset( $query_args['cargus_print_awb'] );
			$query_args['cargus_print_retur_awb'] = '1';
		}

		return add_query_arg(
			$query_args,
			admin_url( 'edit.php?post_type=shop_order' )
		);
	}

	/**
	 * Create the admin notice for printing awb in bulk.
	 *
	 * @since 1.0.0
	 */
	public function cargus_print_awb_bulk_notice(): void {
		//phpcs:disable
		if ( empty( $_REQUEST['cargus_print_awb'] ) && empty( $_REQUEST['cargus_print_retur_awb'] ) ) {
			return; // Exit
		}

		$count     = intval( $_REQUEST['processed_count'] );
		$order_ids = wc_clean( wp_unslash( $_REQUEST['processed_ids'] ) );

		$url = wp_nonce_url(
			admin_url( 'edit.php?post_type=shop_order&cargus_print_awb=1&order_ids=' . $order_ids . '' ),
			'cargus_print_awb',
			'cargus_print_awb_nonce'
		);

		$singular_string = 'Se poate printa awb pentru %1$s comanda:';
		$plural_string   = 'Se pot printa awb-uri pentru %1$s comenzi: ';

		if ( ! empty( $_REQUEST['cargus_print_retur_awb'] ) ) {
			$url = wp_nonce_url(
				admin_url( 'edit.php?post_type=shop_order&cargus_print_retur_awb=1&order_ids=' . $order_ids . '' ),
				'cargus_print_awb',
				'cargus_print_awb_nonce'
			);

			$singular_string = 'Se poate printa awb de retur pentru %1$s comanda:';
			$plural_string   = 'Se pot printa awb-uri de retur pentru %1$s comenzi: ';
		}

		printf(
			wp_kses(
				'<div class="notice notice-success is-dismissible"><p>' .
				_n(
					$singular_string,
					$plural_string,
					$count,
					'cargus'
				) .
				$order_ids . '. ' .
				'<b>Apasă <a href="%2$s" target="_blank" onclick="window.open(\'%2$s\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');">aici</a> pentru a printa</b>!' .
				'</p></div>',
				array(
					'div' => array(
						'class' => array(),
					),
					'p'   => array(),
					'b'   => array(),
					'a'   => array(
						'href'    => array(),
						'target'  => array(),
						'onclick' => array(),
					),
				)
			),
			esc_html( $count ),
			esc_url( $url )
		);
		//phpcs:enable
	}

	/**
	 * Add a custom meta box for woocommerce order settings page.
	 *
	 * @since 1.0.0
	 */
	public function cargus_order_admin_add_metabox(): void {
		//phpcs:disable
		global $pagenow;

		if ( 'post.php' === $pagenow ) {
			$order_products         = array();
			$default_order_products = array();
			$ramburs                = 0;

			$cargus         = new Cargus_Api();
			$cargus_options = get_option( 'woocommerce_cargus_settings' );

			$default_weight = 0;
			$max_volume     = $max_width = $max_length = $max_height = $rate = 0;

			$order_id = '';
			if ( isset( $_GET['post'] ) && 'shop_order' === get_post_type( $_GET['post'] ) ) {
				$order_id = wc_clean( wp_unslash( $_GET['post'] ) );
			} elseif ( isset( $_POST['post_ID'] ) && 'shop_order' === get_post_type( $_POST['post_ID'] ) ) {
				$order_id = wc_clean( wp_unslash( $_POST['post_ID'] ) );
			}

			$order = wc_get_order( $order_id );
			if ( $order && ( $order->has_shipping_method( 'cargus' ) || $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus_saturday' ) || $order->has_shipping_method( 'cargus_pre10' ) || $order->has_shipping_method( 'cargus_pre12' ) ) ) {
				if ( in_array( $order->get_payment_method(), array( 'cod', 'cargus_ship_and_go_payment' ) ) ) {
					$ramburs = $order->get_total();
				}

				// Get the dimension unit set in Woocommerce.
				$dimension_unit = get_option( 'woocommerce_dimension_unit' );

				// Calculate the rate to be applied for cm.
				if ( $dimension_unit == 'mm' ) {
					$rate = 0.1;
				} elseif ( $dimension_unit == 'cm' ) {
					$rate = 1;
				} elseif ( $dimension_unit == 'm' ) {
					$rate = 100;
				}

				foreach ( $order->get_items() as $item_id => $item ) {
					$order_products[ $item->get_product_id() ] = $item->get_name();
					$default_order_products[]                  = $item->get_product_id();
					$_product                                  = wc_get_product( $item->get_product_id() );

					if ( $_product->is_type( 'variable' ) ) {
						$_product = wc_get_product( $item->get_variation_id() );
					}
					$item_quantity                             = $item->get_quantity();

					if ( $item->get_product_id() ) {
						if ( $_product->get_weight() === '0' && $_product->get_weight() === '' ) {
							$product_weight = 0.1;
						} else {
							$product_weight = floatval( $_product->get_weight() ) * $item_quantity;
						}
						$default_weight += $product_weight;

						// Get product dimensions and volume.
						$length = $_product->get_length();
						$width  = $_product->get_width();
						$height = $_product->get_height();
						if ( '' !== $length && '' !== $width && '' !== $height ) {
							$volume = $length * $width * $height;
							if ( $max_volume < $volume ) {
								$max_volume = $volume;
								$max_width  = $width * $rate;
								$max_length = $length * $rate;
								$max_height = $height * $rate;
							}
						}
					}
				}

				$default_weight = ceil( wc_get_weight( $default_weight, 'kg', get_option( 'woocommerce_weight_unit' ) ) );

				if ( $default_weight < 1 ) {
					$default_weight = 1;
				}

				if ( class_exists( 'FB_Meta_Box' ) ) {

					$args_form = array(
						'meta_box_id'   => 'cargus_woocommerce_detalii_awb',
						'label'         => __( 'Detalii AWB', 'cargus' ),
						'post_type'     => 'shop_order',
						'context'       => 'normal',
						'priority'      => 'default',
						'hook_priority' => 20,
						'fields'        => array(
							array(
								'name'     => 'cargus_deschidere_colete',
								'label'    => __( 'Deschidere colete', 'cargus' ),
								'type'     => 'checkbox',
								'desc'     => __( 'Activează deschiderea coletului la livrare.', 'cargus' ),
								'class'    => 'fb-meta-field',
								'disabled' => 'yes' !== $cargus_options['open'],
							),
							array(
								'name'    => 'cargus_valoare_ramburs',
								'label'   => __( 'Valoare ramburs', 'cargus' ),
								'type'    => 'number',
								'desc'    => __( 'Setează valoarea ramburs a coletului.', 'cargus' ),
								'class'   => 'fb-meta-field',
								'step'    => '.01',
								'default' => ( $ramburs ) ? $ramburs : null,
							),
							array(
								'name'    => 'cargus_tip_colet',
								'label'   => __( 'Tip Expeditie', 'cargus' ),
								'type'    => 'select',
								'desc'    => __( 'Selectează tipul expeditie. <b>Atenție! Pentru plicuri, greutatea maximă acceptată este de 1Kg.</b>', 'cargus' ),
								'class'   => 'fb-meta-field fb-meta-sub-field',
								'options' => array(
									'parcel'   => __( 'Colet', 'cargus' ),
									'envelope' => __( 'Plic', 'cargus' ),
								),
								'default' => ( isset( $cargus_options['type'] ) ) ? $cargus_options['type'] : 'parcel',
							),
						),
					);

					if ( $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
						$args_form['fields'][0]['disabled'] = true;
						$args_form['fields'][1]['disabled'] = true;

						$selected_cargus_pudo_point_id = get_post_meta( $order_id, '_selected_cargus_location', true );
						// obtin lista de locatii pudo din fisier json.
						$pudo_locations = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/locations/pudo_locations.json' );
						// Convert to array.
						$pudo_locations_array   = json_decode( $pudo_locations, true );
						$cargus_locations_array = array();
						foreach ( $pudo_locations_array as $pudo_location ) {
							$cargus_locations_array[ $pudo_location['Id'] ] = $pudo_location['Name'];
						}

						// add ship and go change point field.
						$args_form['fields'][] = array(
							'name'    => '_selected_cargus_location',
							'label'   => __( 'Punct ship & go', 'cargus' ),
							'type'    => 'select',
							'desc'    => __( 'Puteti selecta un alt punct ship and go daca doriti sa il schimbati.', 'cargus' ),
							'class'   => 'cargus-select2 fb-meta-field fb-meta-sub-field pudo-point-select',
							'options' => $cargus_locations_array,
							'default' => $selected_cargus_pudo_point_id,
						);
					}

					if ( in_array( $cargus_options['service_id'], array(
							'1',
							'39'
						) ) && ! $order->has_shipping_method( 'cargus_ship_and_go' ) ) {
						$repeater = array(
							'name'               => 'cargus_colete_comanda',
							'label'              => __( 'Colete comandă', 'cargus' ),
							'type'               => 'repeater',
							'default'            => 1,
							'class'              => 'fb-meta-field',
							'add_button_text'    => __( 'Adaugă colet', 'cargus' ),
							'remove_button_text' => __( 'Elimină colet', 'cargus' ),
							'fields'             => array(
								array(
									'name'    => 'cargus_greutate_colet',
									'label'   => __( 'Greutate colet (kg)', 'cargus' ),
									'type'    => 'number',
									'desc'    => __( 'Setează greutatea coletului.', 'cargus' ),
									'class'   => 'fb-meta-field fb-meta-sub-field',
									'step'    => '1',
									'default' => ( isset( $default_weight ) ) ? $default_weight : null,
								),
								array(
									'name'    => 'cargus_latime_colet',
									'label'   => __( 'Lățime colet (cm)', 'cargus' ),
									'type'    => 'number',
									'desc'    => __( 'Setează lățimea coletului.', 'cargus' ),
									'class'   => 'fb-meta-field fb-meta-sub-field',
									'step'    => '1',
									'default' => ( 0 !== $max_width ) ? $max_width : ( ( isset( $cargus_options['width'] ) ) ? $cargus_options['width'] : null ),
								),
								array(
									'name'    => 'cargus_lungime_colet',
									'label'   => __( 'Lungime colet (cm)', 'cargus' ),
									'type'    => 'number',
									'desc'    => __( 'Setează lungimea coletului.', 'cargus' ),
									'class'   => 'fb-meta-field fb-meta-sub-field',
									'step'    => '1',
									'default' => ( 0 !== $max_length ) ? $max_length : ( ( isset( $cargus_options['length'] ) ) ? $cargus_options['length'] : null ),
								),
								array(
									'name'    => 'cargus_inaltime_colet',
									'label'   => __( 'Înălțime colet (cm)', 'cargus' ),
									'type'    => 'number',
									'desc'    => __( 'Setează înălțimea coletului.', 'cargus' ),
									'class'   => 'fb-meta-field fb-meta-sub-field',
									'step'    => '.01',
									'default' => ( 0 !== $max_height ) ? $max_height : ( ( isset( $cargus_options['height'] ) ) ? $cargus_options['height'] : null ),
								),
								array(
									'name'     => 'cargus_continut_colet',
									'label'    => __( 'Continut colet', 'cargus' ),
									'type'     => 'select',
									'desc'     => __( 'Selectează conținutul coletului.', 'cargus' ),
									'class'    => 'cargus-select2 fb-meta-field fb-meta-sub-field',
									'multiple' => 'multiple',
									'options'  => $order_products,
									'default'  => $default_order_products,
								),
							),
						);

						if ( '39' === $cargus_options['service_id'] ) {
							$repeater['max'] = '15';
						}

						$args_form['fields'][] = $repeater;

					} else {
						$args_form['fields'][] = array(
							'name'    => 'cargus_greutate_comanda',
							'label'   => __( 'Greutate colet (kg)', 'cargus' ),
							'type'    => 'number',
							'desc'    => __( 'Setează greutatea coletului.', 'cargus' ),
							'class'   => 'fb-meta-field',
							'step'    => '1',
							'default' => $default_weight,
						);

						$args_form['fields'][] = array(
							'name'    => 'cargus_latime_comanda',
							'label'   => __( 'Lățime colet (cm)', 'cargus' ),
							'type'    => 'number',
							'desc'    => __( 'Setează lățimea coletului.', 'cargus' ),
							'class'   => 'fb-meta-field fb-meta-sub-field',
							'step'    => '1',
							'default' => ( isset( $cargus_options['width'] ) ) ? $cargus_options['width'] : null,
						);
						$args_form['fields'][] = array(
							'name'    => 'cargus_lungime_comanda',
							'label'   => __( 'Lungime colet (cm)', 'cargus' ),
							'type'    => 'number',
							'desc'    => __( 'Setează lungimea coletului.', 'cargus' ),
							'class'   => 'fb-meta-field fb-meta-sub-field',
							'step'    => '1',
							'default' => ( isset( $cargus_options['length'] ) ) ? $cargus_options['length'] : null,
						);
						$args_form['fields'][] = array(
							'name'    => 'cargus_inaltime_comanda',
							'label'   => __( 'Înălțime colet (cm)', 'cargus' ),
							'type'    => 'number',
							'desc'    => __( 'Setează înălțimea coletului.', 'cargus' ),
							'class'   => 'fb-meta-field fb-meta-sub-field',
							'step'    => '1',
							'default' => ( isset( $cargus_options['height'] ) ) ? $cargus_options['height'] : null,
						);
					}

					$args_form['fields'][] = array(
						'name'        => 'save',
						'value'       => 'cargus_save_changes',
						'text'        => __( 'Actualizeaza', 'cargus' ),
						'type'        => 'button',
						'button_type' => 'submit',
						'class'       => 'button button-primary',
						'desc'        => __( 'Salveaza modificarile aduse comenzii inainte de a genera AWB.', 'cargus' ),
					);

					$fields = apply_filters( 'cargus_before_add_metabox', $args_form, $cargus_options );

					$template_meta = new FB_Meta_Box( $fields );
				}
			}
		}
		//phpcs:enable
	}

	// resubmit renew order handler

	/**
	 * Add a custom meta box for woocommerce order settings page.
	 *
	 * @since 1.0.0
	 */
	public function cargus_order_admin_add_side_metabox(): void {
		//phpcs:disable
		global $pagenow;

		if ( 'post.php' === $pagenow || ( 'admin.php' === $pagenow && 'wc-orders' === $_GET['page'] && 'edit' === $_GET['action'] ) ) {

			$cargus         = new Cargus_Api();
			$cargus_options = get_option( 'woocommerce_cargus_settings' );

			$order_id = '';
			if ( isset( $_GET['post'] ) && 'shop_order' === get_post_type( $_GET['post'] ) ) {
				$order_id = wc_clean( wp_unslash( $_GET['post'] ) );
			} elseif ( isset( $_POST['post_ID'] ) && 'shop_order' === get_post_type( $_POST['post_ID'] ) ) {
				$order_id = wc_clean( wp_unslash( $_POST['post_ID'] ) );
			} elseif ( isset( $_GET['id'] ) && in_array( get_post_type( $_GET['id'] ), array( 'shop_order_placehold', 'shop_order' ) ) && 'wc-orders' === $_GET['page'] ) {
				$order_id = wc_clean( wp_unslash( $_GET['id'] ) );
			}

			$order = wc_get_order( $order_id );
			if ( $order && class_exists( 'FB_Meta_Box' ) && ( $order->has_shipping_method( 'cargus' ) || $order->has_shipping_method( 'cargus_ship_and_go' ) || $order->has_shipping_method( 'cargus_saturday' ) || $order->has_shipping_method( 'cargus_pre10' ) || $order->has_shipping_method( 'cargus_pre12' ) ) ) {
				$args_form = array(
					'meta_box_id'   => 'cargus_woocommerce_actiuni_awb',
					'label'         => __( 'Cargus Shipping', 'cargus' ),
					'post_type'     => 'shop_order',
					'context'       => 'side',
					'priority'      => 'high',
					'hook_priority' => 20,
					'fields'        => array()
				);

				if ( ! get_post_meta( $order_id, '_cargus_awb', true ) ) {
					$args_form['fields'][] = array(
						'name'        => 'cargus_create_awb',
						'value'       => 'cargus_create_awb',
						'text'        => __( 'Genereaza AWB', 'cargus' ),
						'type'        => 'button',
						'button_type' => 'submit',
						'class'       => 'button button-primary',
						'desc'        => __( 'Genereaza AWB-ul cargus.', 'cargus' ),
					);
				}

				if ( get_post_meta( $order_id, '_cargus_awb', true ) ) {
					$url = wp_nonce_url(
						admin_url( 'post.php?post=' . esc_attr( $order_id ) . '&action=edit&cargus_print_awb=1&order_ids=' . esc_attr( $order_id ) . '' ),
						'cargus_print_awb',
						'cargus_print_awb_nonce'
					);

					$args_form['fields'][] = array(
						'type'        => 'button',
						'name'        => 'cargus_print_awb',
						'text'        => __( 'Printeaza AWB', 'cargus' ),
						'url'         => $url,
						'button_type' => 'link',
						'class'       => 'button button-primary print_awb',
						'target'      => '_blank',
						'desc'        => __( 'Printeaza AWB-ul cargus.', 'cargus' ),
					);

					if ( isset( $cargus_options['return-awb-print'] ) && in_array( $cargus_options['return-awb-print'], array(
							'3',
							'4'
						), true ) && isset( $cargus_options['return-awb'] ) && '2' === $cargus_options['return-awb'] ) {
						$url_retur = wp_nonce_url(
							admin_url( 'post.php?post=' . esc_attr( $order_id ) . '&action=edit&cargus_print_retur_awb=1&order_ids=' . esc_attr( $order_id ) . '' ),
							'cargus_print_awb',
							'cargus_print_awb_nonce'
						);

						$args_form['fields'][] = array(
							'type'        => 'button',
							'name'        => 'cargus_print_retur_awb',
							'text'        => __( 'Printeaza AWB retur', 'cargus' ),
							'url'         => $url_retur,
							'button_type' => 'link',
							'class'       => 'button button-primary print_awb',
							'target'      => '_blank',
							'desc'        => __( 'Printeaza AWB-ul de retur cargus.', 'cargus' ),
						);
					}

					$args_form['fields'][] = array(
						'type'        => 'button',
						'name'        => 'cargus_delete_awb',
						'value'       => 'cargus_delete_awb',
						'text'        => __( 'Sterge AWB', 'cargus' ),
						'button_type' => 'submit',
						'class'       => 'button button-primary',
						'desc'        => __( 'Sterge AWB-ul cargus.', 'cargus' ),
					);
				}

				$fields = apply_filters( 'cargus_before_add_buttons_metabox', $args_form, $cargus_options );

				$template_meta = new FB_Meta_Box( $fields );
			}
		}
	}

	/**
	 * @throws Exception
	 */
	public function cargus_order_admin_actions( $post_id, $post, $update ): void {
		$slug = 'shop_order';
		if ( is_admin() ) {
			// If this isn't a 'woocommerce order' post, don't update it.
			if ( $slug !== $post->post_type ) {
				return;
			}

			// create awb.
			if ( isset( $_POST['cargus_create_awb'] ) && $_POST['cargus_create_awb'] ) {
				self::cargus_create_awb( $post_id );
			}

			// delete awb.
			if ( isset( $_POST['cargus_delete_awb'] ) && $_POST['cargus_delete_awb'] ) {
				$this->cargus_delete_awb( $post_id );
			}
		}
	}

	/**
	 * Check if an option exists and is numeric.
	 *
	 * @param array $option The option that needs to be compared.
	 * @param string $key The hey that needs to be valid and numeric.
	 *
	 * @since 1.0.0
	 */
	private function cargus_numeric_option( $option, $key ) {
		if ( array_key_exists( $key, $option ) &&
		     '' !== $option[ $key ] &&
		     is_numeric( $option[ $key ] )
		) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Unset the unnecessary shipping methods.
	 *
	 * @param array $rates The woocommerce shipping rates.
	 * @param array $package The woocommerce shipping package.
	 *
	 * @since 1.0.0
	 */
	public function cargus_hide_shipping_rates( $rates, $package ) {
		// get the day number in the week and the hour.
		$day  = wp_date( 'w' );

		if ( ! in_array( $day, array( '4', '5' ) ) && isset( $rates['cargus_saturday'] ) ) {
			// make sure it's friday for the saturday delivery.
			unset( $rates['cargus_saturday'] );
		}

		if ( 5 <= intval( $day ) ) {
			// make sure it's between monday and thursday for pre10 and pre12 delivery.
			unset( $rates['cargus_pre10'] );
			unset( $rates['cargus_pre12'] );
		}

		if ( ! is_checkout() ) {
			// don't show the shipping rates if it's not checkout.
			unset( $rates['cargus_saturday'] );
			unset( $rates['cargus_pre10'] );
			unset( $rates['cargus_pre12'] );
		}

		return $rates;
	}

	/**
	 * Unset the cargus_saturday and cargus_pre_10 and cargus_pre_12 when no needed.
	 *
	 * @param string $posted_data The woocommerce checkout posted data.
	 * @since 1.0.0
	 */
	function cargus_condition_additional_shipping_methods( $posted_data ) {
		// convert string to array;
		parse_str( $posted_data, $posted_data );
		$day  = wp_date( 'w' );
		if ( in_array( $day, array( '4', '5' ) ) ) {
			// check if it's friday or thursday for the saturday delivery to be listed.
			if (
				( isset( $posted_data[ 'billing_city_saturday_delivery' ] ) && "false" === $posted_data[ 'billing_city_saturday_delivery' ] ) || ! isset( $posted_data[ 'billing_city_saturday_delivery' ] ) ||
				( isset( $posted_data[ 'shipping_city_saturday_delivery' ] ) && "false" === $posted_data[ 'shipping_city_saturday_delivery' ] )
			) {
				add_filter( 'woocommerce_package_rates' , function( $rates, $package ){
					if ( isset( $rates['cargus_saturday'] ) ) {
						unset( $rates['cargus_saturday'] );
					}

					return $rates;
				}, 20, 2);
				// to do: for future set the saturday delivery on and off earlier.
				WC()->session->set( 'saturday_delivery', false );
			} else {
				WC()->session->set( 'saturday_delivery', true );
			}
		}

		if ( 4 >= intval( $day ) ) {
			//check if it's from monday to thursday for pre10 pre pre12 delivery.
			if (
				( isset( $posted_data[ 'billing_city_pre10_delivery' ] ) && 'false' === $posted_data[ 'billing_city_pre10_delivery' ] ) || ! isset( $posted_data[ 'billing_city_pre10_delivery' ] ) ||
				( isset( $posted_data[ 'shipping_city_pre10_delivery' ] ) && 'false' === $posted_data[ 'shipping_city_pre10_delivery' ] )
			) {

				add_filter( 'woocommerce_package_rates' , function( $rates, $package ){
					if ( isset( $rates['cargus_pre10'] ) ) {
						unset( $rates['cargus_pre10'] );
					}

					return $rates;
				}, 20, 2);
				// to do: for future set the saturday delivery on and off earlier.
//				WC()->session->set( 'cargus_pre10_delivery', false );
			} else {
//				WC()->session->set( 'cargus_pre10_delivery', true );
			}

			if (
				( isset( $posted_data[ 'billing_city_pre12_delivery' ] ) && 'false' === $posted_data[ 'billing_city_pre12_delivery' ] ) || ! isset( $posted_data[ 'billing_city_pre12_delivery' ] ) ||
				( isset( $posted_data[ 'shipping_city_pre12_delivery' ] ) && 'false' === $posted_data[ 'shipping_city_pre12_delivery' ] )
			) {

				add_filter( 'woocommerce_package_rates' , function( $rates, $package ){
					if ( isset( $rates['cargus_pre12'] ) ) {
						unset( $rates['cargus_pre12'] );
					}

					return $rates;
				}, 20, 2);
				// to do: for future set the saturday delivery on and off earlier.
//				WC()->session->set( 'cargus_pre12_delivery', false );
			} else {
//				WC()->session->set( 'cargus_pre12_delivery', true );
			}
		}
	}

	/**
	 * Create a callback function for exporting the orders to the shippingmanager platform.
	 *
	 * @param WP_REST_Request $request The rest request.
	 * @since 1.0.0
	 */
	public function cargus_shippingmanager_export_orders( WP_REST_Request $request ) {

		$parameters = $request->get_url_params();

		if ( isset($parameters['from']) && (int)$parameters['from'] > 0  ) {
			$where = ' AND p.ID > ' . $parameters['from'];
			$and = true;
		}
		if (isset($parameters['to']) && (int)$parameters['to'] > 0 ) {
			if ($and) {
				$where .= ' AND p.ID < ' . $parameters['to'];
			} else {
				$where = ' AND p.ID < ' . $parameters['to'];
			}
		}

		global $wpdb;
		$result = $wpdb->get_results("SELECT 
    p.ID,
    p.post_status,
    (SELECT meta_value FROM  $wpdb->postmeta WHERE post_id = p.ID and meta_key='shippingmanger_target_cid' ) as 'carrier_id',
    (SELECT meta_value FROM  $wpdb->postmeta WHERE post_id = p.ID and meta_key='shippingmanger_target_pid' ) as 'point_id'
    FROM  $wpdb->posts p  
    WHERE post_type  = 'shop_order' and p.post_status not in ('trash') " . $where);
		if ($wpdb->last_error) {
			return 'wpdb error: ' . $wpdb->last_error;
		} else {
			return $result;
		}
	}

	/**
	 * Create a callback function for exporting the orders to the shippingmanager platform.
	 *
	 * @param WP_REST_Request $request The rest request.
	 * @since 1.0.0
	 */
	public function cargus_shippingmanager_register_rest_api_route( ) {
		// REST API DATA - https://domain.com/?rest_route=/shippingmanager/orders
		add_action('rest_api_init', function () {
			register_rest_route('shippingmanager', '/orders/(?P<from>\d+)/(?P<to>\d+)', array(
				'methods' => 'GET',
				'callback' => array( $this, 'cargus_shippingmanager_export_orders' ),
				'permission_callback' => '__return_true',
			));
		});
	}
}
