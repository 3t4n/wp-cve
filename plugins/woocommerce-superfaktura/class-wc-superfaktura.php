<?php
/**
 * SuperFaktúra WooCommerce.
 *
 * @package   SuperFaktúra WooCommerce
 * @author    2day.sk <superfaktura@2day.sk>
 * @copyright 2022 2day.sk s.r.o., Webikon s.r.o.
 * @license   GPL-2.0+
 * @link      https://www.superfaktura.sk/integracia/
 */

/**
 * WC_SuperFaktura.
 *
 * @package SuperFaktúra WooCommerce
 * @author  2day.sk <superfaktura@2day.sk>
 */
class WC_SuperFaktura {

	/**
	 * Fake payment gateway ID used to target zero value orders without payment method set.
	 *
	 * @var string
	 */
	public static $zero_value_order_fake_payment_method_id = 'wc_sf_zero_value_fake_gateway';

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $version = '1.40.6';

	/**
	 * Database version.
	 *
	 * @var string
	 */
	protected $db_version = '1.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should match the Text Domain file header in the main plugin file.
	 *
	 * @var string
	 */
	protected $plugin_slug = 'woocommerce-superfaktura';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @var string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Default product description template.
	 *
	 * @var string
	 */
	protected $product_description_template_default;

	/**
	 * Stored result of detection wc-nastavenia-skcz plugin.
	 *
	 * @var bool
	 */
	protected $wc_nastavenia_skcz_activated;

	/**
	 * List of EU countries.
	 *
	 * @var array
	 */
	protected $eu_countries;

	/**
	 * List of EU countries + Northern Ireland for VAT Number validation
	 *
	 * @var array
	 */
	protected $eu_vat_countries;

	/**
	 * Allowed tags in HTML output.
	 *
	 * @var array
	 */
	protected $allowed_tags;



	/**
	 * Initialize the class and set its properties.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'admin_notices', array( __CLASS__, 'order_number_notice_all' ) );
		add_action( 'woocommerce_settings_wc_superfaktura', array( __CLASS__, 'order_number_notice' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'wp_ajax_wc_sf_api_test', array( $this, 'wc_sf_api_test' ) );
		add_action( 'wp_ajax_wc_sf_url_check', array( $this, 'wc_sf_url_check' ) );

		// Backward compatibility with previous removed option.
		$this->product_description_template_default = '[ATTRIBUTES]' . ( 'yes' === get_option( 'woocommerce_sf_product_description_visibility', 'yes' ) ? "\n[SHORT_DESCR]" : '' );

		$this->eu_countries = array(
			'AT', // Austria
			'BE', // Belgium
			'BG', // Bulgaria
			'CY', // Cyprus
			'CZ', // Czechia
			'DE', // Germany
			'DK', // Denmark
			'EE', // Estonia
			'ES', // Spain
			'FI', // Finland
			'FR', // France
			'GR', // Greece
			'HR', // Croatia
			'HU', // Hungary
			'IE', // Ireland
			'IT', // Italy
			'LT', // Lithuania
			'LU', // Luxembourg
			'LV', // Latvia
			'MT', // Malta
			'NL', // The Netherlands
			'PL', // Poland
			'PT', // Portugal
			'RO', // Romania
			'SE', // Sweden
			'SI', // Slovenia
			'SK', // Slovakia
		);

		$this->eu_vat_countries = array(
			'AT', // Austria
			'BE', // Belgium
			'BG', // Bulgaria
			'CY', // Cyprus
			'CZ', // Czechia
			'DE', // Germany
			'DK', // Denmark
			'EE', // Estonia
			'EL', // Greece
			'ES', // Spain
			'FI', // Finland
			'FR', // France
			'HR', // Croatia
			'HU', // Hungary
			'IE', // Ireland
			'IT', // Italy
			'LT', // Lithuania
			'LU', // Luxembourg
			'LV', // Latvia
			'MT', // Malta
			'NL', // The Netherlands
			'PL', // Poland
			'PT', // Portugal
			'RO', // Romania
			'SE', // Sweden
			'SI', // Slovenia
			'SK', // Slovakia
			'XI', // Northern Ireland
		);
	}



	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}



	/**
	 * Fired when the plugin is activated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// :TODO: Define activation functionality here.
	}



	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// :TODO: Define deactivation functionality here.
	}



	/**
	 * Handles migration of legacy plugin settings.
	 */
	public function migrate_plugin_settings() {

		// Settings for delivery date.
		$delivery_date_value = get_option( 'woocommerce_sf_delivery_date_value' );
		if ( false === $delivery_date_value ) {
			// Load previous options.
			$dd_visibility = wc_string_to_bool( get_option( 'woocommerce_sf_delivery_date_visibility', 'yes' ) );
			$dd_paid       = wc_string_to_bool( get_option( 'woocommerce_sf_delivery_date_order_paid', 'no' ) );

			// Save them as new option, previous default was sending -1 to SF API and this was causing the delivery date NOT to show.
			$new_delivery_date_value = 'none';
			if ( $dd_visibility ) {
				$new_delivery_date_value = $dd_paid ? 'order_paid' : 'invoice_created';
			}
			add_option( 'woocommerce_sf_delivery_date_value', $new_delivery_date_value, '', false );

			// Delete legacy options.
			delete_option( 'woocommerce_sf_delivery_date_visibility' );
			delete_option( 'woocommerce_sf_delivery_date_order_paid' );
		}

		// Settings for company billing fields.
		$billing_fields_id = get_option( 'woocommerce_sf_add_company_billing_fields_id' );
		if ( false === $billing_fields_id ) {
			// Load previous options.
			$checkout_id              = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_checkout_id' ) );
			$checkout_vat             = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_checkout_vat' ) );
			$checkout_tax             = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_checkout_tax' ) );
			$checkout_required        = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_checkout_required' ) );
			$checkout_vat_id_required = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_checkout_vat_id_required' ) );

			// Set values for new options.
			add_option( 'woocommerce_sf_add_company_billing_fields_name', $checkout_required ? 'required' : 'optional' );
			add_option( 'woocommerce_sf_add_company_billing_fields_id', $checkout_id ? ( $checkout_required ? 'required' : 'optional' ) : 'no' );
			add_option( 'woocommerce_sf_add_company_billing_fields_vat', $checkout_vat ? ( $checkout_vat_id_required ? 'required' : 'optional' ) : 'no' );
			add_option( 'woocommerce_sf_add_company_billing_fields_tax', $checkout_tax ? ( $checkout_required ? 'required' : 'optional' ) : 'no' );

			// Delete legacy options.
			delete_option( 'woocommerce_sf_invoice_checkout_id' );
			delete_option( 'woocommerce_sf_invoice_checkout_vat' );
			delete_option( 'woocommerce_sf_invoice_checkout_tax' );
			delete_option( 'woocommerce_sf_invoice_checkout_required' );
			delete_option( 'woocommerce_sf_invoice_checkout_vat_id_required' );
		}
	}



	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}



	/**
	 * Register scripts for public pages.
	 */
	public function enqueue_scripts() {
		if ( is_checkout() || is_account_page() ) {
			if ( 'yes' === get_option( 'woocommerce_sf_add_company_billing_fields', 'yes' ) ) {
				wp_enqueue_script( 'wc-sf-checkout-js', plugins_url( 'js/checkout.js', __FILE__ ), array( 'jquery' ), $this->version, true );
			}
		}
	}



	/**
	 * Register scripts for admin pages.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'wc-sf-admin-js', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version, true );
		wp_localize_script( 'wc-sf-admin-js', 'wc_sf', array( 'ajaxnonce'   => wp_create_nonce( 'ajax_validation' ) ) );
	}



	/**
	 * Process input for admin pages.
	 */
	public function admin_init() {
		if ( isset( $_GET['sf_regen'] ) || isset( $_GET['sf_invoice_proforma_create'] ) || isset( $_GET['sf_invoice_regular_create'] ) || isset( $_GET['sf_invoice_cancel_create'] ) ) {
			if ( ! current_user_can( 'manage_woocommerce' ) || ! isset( $_GET['sf_order'] ) ) {
				wp_die( 'Unauthorized' );
			}

			$order_id = (int) $_GET['sf_order'];

			$result     = true;
			$result_msg = '';
			if ( isset( $_GET['sf_regen'] ) ) {
				$result      = $this->sf_regen_invoice( $order_id );
				$result_msg .= 'regen';
			} elseif ( isset( $_GET['sf_invoice_proforma_create'] ) ) {
				$order       = wc_get_order( $order_id );
				$result      = $this->sf_generate_invoice( $order, 'proforma', isset( $_GET['force_create'] ) );
				$result_msg .= 'proforma';
			} elseif ( isset( $_GET['sf_invoice_regular_create'] ) ) {
				$order       = wc_get_order( $order_id );
				$result      = $this->sf_generate_invoice( $order, 'regular', isset( $_GET['force_create'] ) );
				$result_msg .= 'regular';
			} elseif ( isset( $_GET['sf_invoice_cancel_create'] ) ) {
				$order       = wc_get_order( $order_id );
				$result      = $this->sf_generate_invoice( $order, 'cancel', isset( $_GET['force_create'] ) );
				$result_msg .= 'cancel';
			}

			if ( is_wp_error( $result ) ) {
				$result_msg = array_key_first( $result->errors );
			} elseif ( $result_msg ) {
				$result_msg .= ( false === $result ) ? '_failed' : '_ok';
			}

			wp_safe_redirect( admin_url( 'post.php?post=' . $order_id . '&action=edit&sf_msg=' . $result_msg ) );
			die();
		}

		if ( isset( $_GET['sf_hide_order_number_notice'] ) ) {
			update_option( 'wc_sf_order_number_notice_hidden', 1 );
			wp_safe_redirect( remove_query_arg( 'sf_hide_order_number_notice' ) );
		}
	}



	/**
	 * Process notices for admin pages.
	 */
	public function admin_notices() {

		// Show notices saved in database.
		$admin_notices = get_option( 'woocommerce_sf_admin_notices', array() );
		if ( ! empty( $admin_notices ) ) {
			foreach ( $admin_notices as $admin_notice ) {
				echo sprintf( wp_kses( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $this->allowed_tags ), $admin_notice['type'], $admin_notice['text'] );
			}
			delete_option( 'woocommerce_sf_admin_notices' );
		}

		// Show notices based on GET parameter.
		if ( ! isset( $_GET['sf_msg'] ) || empty( $_GET['sf_msg'] ) ) {
			return;
		}

		// Translators: %s API log URL.
		$see_api_log = sprintf( __( 'See <a href="%s">API log</a> for more information.', 'woocommerce-superfaktura' ), admin_url( 'admin.php?page=wc-settings&tab=superfaktura&section=api_log' ) );

		switch ( $_GET['sf_msg'] ) {

			case 'proforma_ok':
				echo wp_kses( '<div class="notice notice-success is-dismissible"><p>' . __( 'Proforma invoice was created.', 'woocommerce-superfaktura' ) . '</p></div>', $this->allowed_tags );
				break;

			case 'regular_ok':
				echo wp_kses( '<div class="notice notice-success is-dismissible"><p>' . __( 'Invoice was created.', 'woocommerce-superfaktura' ) . '</p></div>', $this->allowed_tags );
				break;

			case 'cancel_ok':
				echo wp_kses( '<div class="notice notice-success is-dismissible"><p>' . __( 'Credit note was created.', 'woocommerce-superfaktura' ) . '</p></div>', $this->allowed_tags );
				break;

			case 'regen_ok':
				echo wp_kses( '<div class="notice notice-success is-dismissible"><p>' . __( 'Documents were regenerated.', 'woocommerce-superfaktura' ) . '</p></div>', $this->allowed_tags );
				break;

			case 'proforma_failed':
				echo wp_kses( '<div class="notice notice-error is-dismissible"><p>' . __( 'Proforma invoice was not created.', 'woocommerce-superfaktura' ) . ' ' . $see_api_log . '</p></div>', $this->allowed_tags );
				break;

			case 'regular_failed':
				echo wp_kses( '<div class="notice notice-error is-dismissible"><p>' . __( 'Invoice was not created.', 'woocommerce-superfaktura' ) . ' ' . $see_api_log . '</p></div>', $this->allowed_tags );
				break;

			case 'cancel_failed':
				echo wp_kses( '<div class="notice notice-error is-dismissible"><p>' . __( 'Credit note was not created.', 'woocommerce-superfaktura' ) . ' ' . $see_api_log . '</p></div>', $this->allowed_tags );
				break;

			case 'regen_failed':
				echo wp_kses( '<div class="notice notice-error is-dismissible"><p>' . __( 'Documents were not regenerated.', 'woocommerce-superfaktura' ) . ' ' . $see_api_log . '</p></div>', $this->allowed_tags );
				break;

			case 'duplicate_document':
				echo wp_kses( '<div class="notice notice-error is-dismissible"><p>' . __( 'Document was not created, because it already exists.', 'woocommerce-superfaktura' ) . '</p></div>', $this->allowed_tags );
				break;

			default:
				echo wp_kses( '<div class="notice notice-warning is-dismissible"><p>' . wp_unslash( $_GET['sf_msg'] ) . '</p></div>', $this->allowed_tags );
				break;
		}
	}



	/**
	 * Fires once activated plugins have loaded.
	 */
	public function plugins_loaded() {
		if ( get_site_option( 'wc_sf_db_version' ) !== $this->db_version ) {
			$this->wc_sf_db_install();
		}

		$this->migrate_plugin_settings();
	}



	/**
	 * Install database tables.
	 */
	public function wc_sf_db_install() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'wc_sf_log';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            order_id bigint(20) unsigned NULL,
            document_type varchar(16) NULL,
            request_type varchar(16) NULL,
            response_status int(11) NULL,
            response_message varchar(1024) NULL,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'wc_sf_db_version', $this->db_version );
	}



	/**
	 * Avoid double notice on superfaktura settings tab.
	 */
	public static function order_number_notice_all() {
		if ( isset( $_GET['page'], $_GET['tab'] ) && 'wc-settings' === $_GET['page'] && 'wc_superfaktura' === $_GET['tab'] ) {
			return;
		}

		self::order_number_notice();
	}


	/**
	 * Display warning if we use custom numbering + [ORDER_NUMBER] variable and do not have active plugin Woocommerce Sequential Order Numbers.
	 */
	public static function order_number_notice() {
		if ( ! is_admin() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( is_plugin_active( 'woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php' )
			|| is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php' )
			|| is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php' )
		) {
			return;
		}

		if ( 'no' === get_option( 'woocommerce_sf_invoice_custom_num' ) ) {
			return;
		}

		if ( get_option( 'wc_sf_order_number_notice_hidden' ) ) {
			return;
		}

		$tmpl1 = get_option( 'woocommerce_sf_invoice_proforma_id' ) ?? '';
		$tmpl2 = get_option( 'woocommerce_sf_invoice_regular_id' ) ?? '';
		$tmpl3 = get_option( 'woocommerce_sf_invoice_cancel_id' ) ?? '';
		if ( false !== strpos( $tmpl1 . $tmpl2 . $tmpl3, '[ORDER_NUMBER]' ) ) {
			// Translators: %1$s Order number, %2$s Plugin name.
			echo '<div class="notice notice-error is-dismissible">';
			echo '<p><strong>SuperFaktúra Woocommerce</strong>: ' . sprintf( __( 'You use variable %1$s in your invoice nr. or proforma invoice nr., but the plugin "%2$s" is not activated. This may cause that your invoice numbers will not be sequential.', 'woocommerce-superfaktura' ), '[ORDER_NUMBER]', 'WooCommerce Sequential Order Numbers' ) . '</p>';
			echo '<p><a href="' . esc_url( add_query_arg( 'sf_hide_order_number_notice', 1 ) ) . '">' . __( 'Hide notification forever', 'woocommerce-superfaktura' ) . '</a></p>';
			echo '</div>';
		}
	}



	/**
	 * Initialize the plugin.
	 */
	public function init() {
		$this->load_plugin_textdomain();

		$this->allowed_tags = wp_kses_allowed_html( 'post' );
		$this->allowed_tags['style'] = array( 'type' );

		$this->wc_nastavenia_skcz_activated = class_exists( 'Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ\Plugin', false );

		add_action( 'woocommerce_get_settings_pages', array( $this, 'woocommerce_settings' ) );

		if ( 'yes' === get_option( 'woocommerce_sf_add_company_billing_fields', 'yes' ) && ! $this->wc_nastavenia_skcz_activated ) {
			add_filter( 'woocommerce_billing_fields', array( $this, 'billing_fields' ) );
			add_filter( 'woocommerce_form_field', array( $this, 'billing_fields_labels' ), 10, 4 );
			add_filter( 'woocommerce_checkout_process', array( $this, 'checkout_process' ) );

			add_filter( 'woocommerce_admin_billing_fields', array( $this, 'woocommerce_admin_billing_fields' ), 10, 1 );
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'woocommerce_process_shop_order_meta' ), 10, 2 );

			// Add editable fields to user profile in admin.
			add_filter( 'woocommerce_customer_meta_fields' , array( $this, 'woocommerce_customer_meta_fields' ) );

			// Add custom fields values to customer details.
			add_filter( 'woocommerce_ajax_get_customer_details', array( $this, 'woocommerce_ajax_get_customer_details' ), 10, 3 );
		}

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'checkout_order_meta' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'my_orders_actions' ), 10, 2 );

		// Custom order filter by wc_sf_internal_regular_id (see https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query#adding-custom-parameter-support).
		add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', array( $this, 'filter_order_by_internal_regular_id' ), 10, 2 );

		$wc_get_order_statuses = $this->get_order_statuses();
		foreach ( $wc_get_order_statuses as $key => $status ) {
			add_action( 'woocommerce_order_status_' . $key, array( $this, 'sf_new_invoice' ), 5 );
		}

		add_action( 'woocommerce_checkout_order_processed', array( $this, 'sf_new_invoice' ), 5 );

		add_action( 'woocommerce_email_customer_details', array( $this, 'sf_invoice_business_data_email' ), 30, 3 );
		add_action( 'woocommerce_email_order_meta', array( $this, 'sf_payment_link_email' ), 10, 2 );
		add_action( 'woocommerce_email_order_meta', array( $this, 'sf_invoice_link_email' ), 10, 2 );
		add_filter( 'woocommerce_email_attachments', array( $this, 'sf_invoice_attachment_email' ), 10, 3 );

		add_action( 'woocommerce_thankyou', array( $this, 'sf_invoice_link_page' ) );
		add_action( 'wp_loaded', array( $this, 'set_order_as_paid' ) );
		add_action( 'sf_fetch_related_invoice', array( $this, 'fetch_related_invoice'), 10, 1 );
		add_action( 'sf_retry_generate_invoice', array( $this, 'retry_generate_invoice'), 10, 3 );
		add_action( 'wp_ajax_wc_sf_generate_secret_key', array( $this, 'generate_secret_key' ) );
		add_filter( 'woocommerce_admin_order_actions', array( $this, 'add_custom_order_status_actions_button' ), 100, 2 );
		add_action( 'admin_head', array( $this, 'add_admin_css' ) );
	}



	/**
	 * Generate secret key.
	 */
	public function generate_secret_key() {
		check_ajax_referer( 'wc_sf' );
		echo esc_attr( WC_Secret_Key_Helper::generate_secret_key() );
		wp_die();
	}



	/**
	 * Check if HPOS is enabled.
	 */
	public function hpos_enabled() {
		return wc_get_container()->get( \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();
	}



	/**
	 * Get orders by metadata.
	 */
	public function get_orders_by_meta( $meta_key, $meta_value ) {
		if ( $this->hpos_enabled() ) {
			return wc_get_orders(
				array(
					'meta_query' => array(
						array(
							'key'   => $meta_key,
							'value' => $meta_value
						)
					),
				)
			);
		}

		// Support for custom metadata in query is added via woocommerce_order_data_store_cpt_get_orders_query filter.
		return wc_get_orders( array( $meta_key => $meta_value ) );
	}



	/**
	 * Set order as paid.
	 */
	public function set_order_as_paid() {
		if ( ! isset( $_GET['callback'] ) || 'wc_sf_order_paid' !== $_GET['callback'] ) {
			return;
		}

		if ( ! isset( $_GET['invoice_id'] ) || ! isset( $_GET['secret_key'] ) ) {
			exit();
		}

		$invoice_id      = sanitize_text_field( wp_unslash( $_GET['invoice_id'] ) );
		$secret_key      = sanitize_text_field( wp_unslash( $_GET['secret_key'] ) );
		$user_secret_key = get_option( 'woocommerce_sf_sync_secret_key', false );

		if ( is_numeric( $invoice_id ) && ( false === $user_secret_key || $secret_key === $user_secret_key ) ) {

			// Query order by custom field.
			$orders = $this->get_orders_by_meta( 'wc_sf_internal_proforma_id', $invoice_id );
			if ( count( $orders ) === 0 ) {
				$orders = $this->get_orders_by_meta( 'wc_sf_internal_regular_id', $invoice_id );
			}

			// Check invoice status.
			$api      = $this->sf_api();
			$response = $api->getInvoiceDetails( $invoice_id );

			// Invoice not found.
			if ( ! $response || ! isset( $response->{$invoice_id} ) ) {
				$this->wc_sf_log(
					array(
						'request_type'     => 'callback_paid',
						'response_status'  => 902,
						'response_message' => sprintf( 'Invoice ID %d not found', $invoice_id ),
					)
				);
				exit();
			}

			// 1 = not paid, 2 = paid partially, 3 = paid
			if ( 3 != $response->{$invoice_id}->Invoice->status ) {
				$this->wc_sf_log(
					array(
						'request_type'     => 'callback_paid',
						'response_status'  => 903,
						'response_message' => sprintf( 'Invoice ID %d not paid', $invoice_id ),
					)
				);
				exit();
			}

			if ( count( $orders ) === 1 ) {
				$order = $orders[0];

				// Get order status (see https://docs.woocommerce.com/document/managing-orders/).
				$order_status = $order->get_status();
				if ( 'on-hold' === $order_status ) {
					// Mark order as paid (see https://woocommerce.wp-a2z.org/oik_api/wc_orderpayment_complete/).
					$order->payment_complete();

					$this->wc_sf_log(
						array(
							'order_id'     => $order->get_id(),
							'request_type' => 'callback_paid',
						)
					);

					// Check if related regular invoice was created automatically in SuperFaktura.
					if ( 'proforma' == $response->{$invoice_id}->Invoice->type ) {

						// Only if regular invoice does not already exist in WooCommerce.
						$regular_id = $order->get_meta( 'wc_sf_internal_regular_id', true );
						if ( empty( $regular_id ) ) {

							// Schedule an action (because SuperFaktura calls the callback BEFORE it creates the regular invoice automatically).
							as_schedule_single_action( time() + 300, 'sf_fetch_related_invoice', array( 'proforma_id' => $invoice_id ), 'woocommerce-superfaktura' );
						}
					}
				}
				else {
					$this->wc_sf_log(
						array(
							'order_id'         => $order->get_id(),
							'request_type'     => 'callback_paid',
							'response_status'  => 905,
							'response_message' => 'Order is not on hold',
						)
					);
				}
			}
			else {
				$this->wc_sf_log(
					array(
						'request_type'     => 'callback_paid',
						'response_status'  => 904,
						'response_message' => sprintf( 'Order with invoice ID %d not found', $invoice_id ),
					)
				);
			}
		}
		else {
			$this->wc_sf_log(
				array(
					'request_type'     => 'callback_paid',
					'response_status'  => 901,
					'response_message' => 'Incorrect parameters',
				)
			);
		}

		exit();
	}



	/**
	 * Check if there is a related invoice created automatically in SuperFaktura and update order meta if there is.
	 */
	public function fetch_related_invoice( $proforma_id ) {

		// Find order by proforma invoice id.
		$orders = $this->get_orders_by_meta( 'wc_sf_internal_proforma_id', $proforma_id );
		if ( empty( $orders ) ) {
			return false;
		}

		$order = $orders[0];

		// Only if regular invoice does not already exist in WooCommerce.
		$regular_id = $order->get_meta( 'wc_sf_internal_regular_id', true );
		if ( ! empty( $regular_id ) ) {
			return false;
		}

		// Get proforma invoice data from SF API.
		$api      = $this->sf_api();
		$proforma = $api->getInvoiceDetails( $proforma_id );
		if ( ! $proforma || ! isset( $proforma->{$proforma_id} ) || 'proforma' !== $proforma->{$proforma_id}->Invoice->type ) {
			return false;
		}

		$regular_id = null;

		// Find if there is a regular invoice in RelatedItems.
		foreach ( $proforma->{$proforma_id}->RelatedItems as $related_item ) {
			if ( 'regular' != $related_item->Invoice->type || $related_item->Invoice->tax_document ) {
				continue;
			}

			$regular_id = $related_item->Invoice->id;
		}

		// Find if there is a regular invoice in parent_id.
		if ( $proforma->{$proforma_id}->Invoice->parent_id && 'regular' ===  $proforma->{$proforma_id}->Parent->Invoice->type && ! $proforma->{$proforma_id}->Parent->Invoice->tax_document ) {
			$regular_id = $proforma->{$proforma_id}->Parent->Invoice->id;
		}

		if ( empty( $regular_id ) ) {
			return false;
		}

		$regular = $api->getInvoiceDetails( $regular_id );
		if ( ! $regular || ! isset( $regular->{$regular_id} ) ) {
			return false;
		}

		// Delete payment link.
		if ( 3 == $regular->{$regular_id}->Invoice->status ) {
			$order->delete_meta_data( 'wc_sf_payment_link' );
		}

		// Save document ID.
		$order->update_meta_data( 'wc_sf_internal_regular_id', $regular_id );

		// Save formatted invoice number.
		$order->update_meta_data( 'wc_sf_regular_invoice_number', $regular->{$regular_id}->Invoice->invoice_no_formatted );

		// Save pdf url.
		$language = $this->get_language( $order->get_id(), get_option( 'woocommerce_sf_invoice_language' ), true );
		$pdf      = ( ( 'yes' === get_option( 'woocommerce_sf_sandbox', 'no' ) ) ? $api::SANDBOX_URL : $api::SFAPI_URL ) . '/' . $language . '/invoices/pdf/' . $regular_id . '/token:' . $regular->{$regular_id}->Invoice->token;
		$order->update_meta_data( 'wc_sf_invoice_regular', $pdf );

		$order->save();

		return true;
	}



	/**
	 * Schedule next attempt to create the document.
	 */
	public function retry_generate_invoice_schedule( $order, $type ) {

		$attempt = $order->get_meta( 'wc_sf_' . $type . '_create_retry_attempts', true );
		if ( ! $attempt ) {
			$attempt = 0;
		}
		$attempt++;

		if ( $attempt < 4 ) {
			switch ( $attempt ) {
				case 1:
				default:
					$mins_to_next_attempt = 5;
					break;

				case 2:
					$mins_to_next_attempt = 30;
					break;

				case 3:
					$mins_to_next_attempt = 60;
					break;
			}

			switch ( $type ) {
				case 'proforma':
					$order->add_order_note( sprintf( __( 'API call to create proforma invoice failed. We will try again in %d minutes.', 'woocommerce-superfaktura' ), $mins_to_next_attempt ) );
					break;

				case 'regular':
					$order->add_order_note( sprintf( __( 'API call to create invoice failed. We will try again in %d minutes.', 'woocommerce-superfaktura' ), $mins_to_next_attempt ) );
					break;

				case 'cancel':
					$order->add_order_note( sprintf( __( 'API call to create credit note failed. We will try again in %d minutes.', 'woocommerce-superfaktura' ), $mins_to_next_attempt ) );
					break;

				default:
					$order->add_order_note( sprintf( __( 'API call to create document failed. We will try again in %d minutes.', 'woocommerce-superfaktura' ), $mins_to_next_attempt ) );
					break;
			}

			as_schedule_single_action( time() + ( 60 * $mins_to_next_attempt ), 'sf_retry_generate_invoice', array( 'order_id' => $order->get_id(), 'type' => $type, 'attempt' => $attempt), 'woocommerce-superfaktura' );
		}
		else {
			switch ( $type ) {
				case 'proforma':
					$this->save_admin_notice( sprintf( __( 'API call to create proforma invoice for <a href="%s">order #%d</a> failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $order->get_edit_order_url(), $order->get_id(), $attempt ), 'error' );
					$order->add_order_note( sprintf( __( 'API call to create proforma invoice failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $attempt ) );
					break;

				case 'regular':
					$this->save_admin_notice( sprintf( __( 'API call to create invoice for <a href="%s">order #%d</a> failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $order->get_edit_order_url(), $order->get_id(), $attempt ), 'error' );
					$order->add_order_note( sprintf( __( 'API call to create invoice failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $attempt ) );
					break;

				case 'cancel':
					$this->save_admin_notice( sprintf( __( 'API call to create credit note for <a href="%s">order #%d</a> failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $order->get_edit_order_url(), $order->get_id(), $attempt ), 'error' );
					$order->add_order_note( sprintf( __( 'API call to create credit note failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $attempt ) );
					break;

				default:
					$this->save_admin_notice( sprintf( __( 'API call to create document for <a href="%s">order #%d</a> failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $order->get_edit_order_url(), $order->get_id(), $attempt ), 'error' );
					$order->add_order_note( sprintf( __( 'API call to create document failed %d times. Try creating it manually.', 'woocommerce-superfaktura' ), $attempt ) );
					break;
			}
		}
	}



	/**
	 * Try creating the document again, if the previous attempt failed.
	 */
	public function retry_generate_invoice( $order_id, $type, $attempt ) {

		$order = wc_get_order( $order_id );
		$order->update_meta_data( 'wc_sf_' . $type . '_create_retry_attempts', $attempt );

		// Check if the document has not been created in the meantime.
		$sf_id = $order->get_meta( 'wc_sf_internal_' . $type . '_id', true );
		if ( ! empty( $sf_id ) ) {
			return false;
		}

		// Check if the document was created in SF but we didn't get a response due to timeout and therefore the order metadata is empty.
		$invoices = $this->sf_api()->invoices( array( 'order_no' => $order_id, 'type' => $type ) );
		if ( ! empty( $invoices ) && $invoices->itemCount > 0 ) {

			// Save document ID.
			$internal_id = $invoices->items[0]->Invoice->id;
			$order->update_meta_data( 'wc_sf_internal_' . $type . '_id', $internal_id );

			// Save formatted invoice number.
			$invoice_number = $invoices->items[0]->Invoice->invoice_no_formatted;
			$order->update_meta_data( 'wc_sf_' . $type . '_invoice_number', $invoice_number );

			// Save pdf url.
			$language = $this->get_language( $order->get_id(), get_option( 'woocommerce_sf_invoice_language' ), true );
			$token    = $invoices->items[0]->Invoice->token;
			$pdf      = ( ( 'yes' === get_option( 'woocommerce_sf_sandbox', 'no' ) ) ? $this->sf_api()::SANDBOX_URL : $this->sf_api()::SFAPI_URL ) . '/' . $language . '/invoices/pdf/' . $internal_id . '/token:' . $token;
			$order->update_meta_data( 'wc_sf_invoice_' . $type, $pdf );

			$order->save();

			return true;
		}

		// Try to generate the document again.
		$this->sf_generate_invoice( $order, $type );

		return true;
	}



	/**
	 * Save the admin notification to appear the next time admin page loads
	 */
	public function save_admin_notice( $notice_text, $notice_type = 'info' ) {
		$admin_notices = get_option( 'woocommerce_sf_admin_notices', array() );
		$admin_notices[] = array( 'text' => $notice_text, 'type' => $notice_type );
		update_option( 'woocommerce_sf_admin_notices', $admin_notices );
	}



	/**
	 * Handle a custom 'wc_sf_internal_proforma_id' and 'wc_sf_internal_regular_id' query var to get orders with the 'wc_sf_internal_proforma_id' or 'wc_sf_internal_regular_id' meta respectively.
	 *
	 * @param array $query Args for WP_Query.
	 * @param array $query_vars Query vars from WC_Order_Query.
	 */
	public function filter_order_by_internal_regular_id( $query, $query_vars ) {

		if ( ! empty( $query_vars['wc_sf_internal_proforma_id'] ) ) {
			$query['meta_query'][] = array(
				'key'   => 'wc_sf_internal_proforma_id',
				'value' => esc_attr( $query_vars['wc_sf_internal_proforma_id'] ),
			);
		}

		if ( ! empty( $query_vars['wc_sf_internal_regular_id'] ) ) {
			$query['meta_query'][] = array(
				'key'   => 'wc_sf_internal_regular_id',
				'value' => esc_attr( $query_vars['wc_sf_internal_regular_id'] ),
			);
		}

		return $query;
	}



	/**
	 * Initialize SuperFaktura API.
	 *
	 * @param array $credentials SuperFaktura API credentials.
	 */
	public function sf_api( $credentials = array() ) {

		$sf_lang       = ( isset( $credentials['woocommerce_sf_lang'] ) ) ? $credentials['woocommerce_sf_lang'] : get_option( 'woocommerce_sf_lang', 'sk' );
		$sf_email      = ( isset( $credentials['woocommerce_sf_email'] ) ) ? $credentials['woocommerce_sf_email'] : get_option( 'woocommerce_sf_email' );
		$sf_key        = ( isset( $credentials['woocommerce_sf_apikey'] ) ) ? $credentials['woocommerce_sf_apikey'] : get_option( 'woocommerce_sf_apikey' );
		$sf_company_id = ( isset( $credentials['woocommerce_sf_company_id'] ) ) ? $credentials['woocommerce_sf_company_id'] : get_option( 'woocommerce_sf_company_id' );
		$sf_sandbox    = ( isset( $credentials['woocommerce_sf_sandbox'] ) ) ? $credentials['woocommerce_sf_sandbox'] : get_option( 'woocommerce_sf_sandbox', 'no' );

		$module_id = sprintf( 'WordPress %s (WC %s, WC SF %s)', get_bloginfo( 'version' ), WC()->version, $this->version );

		switch ( $sf_lang ) {
			case 'at':
				$api = new SFAPIclientAT( $sf_email, $sf_key, sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ?? '' ) ), $module_id, $sf_company_id );
				break;

			case 'cz':
				$api = new SFAPIclientCZ( $sf_email, $sf_key, sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ?? '' ) ), $module_id, $sf_company_id );
				break;

			default:
				$api = new SFAPIclient( $sf_email, $sf_key, sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ?? '' ) ), $module_id, $sf_company_id );
				break;
		}

		if ( 'yes' === $sf_sandbox ) {
			$api->useSandBox();
		}

		return $api;
	}



	/**
	 * Delete invoice items.
	 *
	 * @param int                                     $invoice_id Invoice ID.
	 * @param SFAPIclient|SFAPIclientAT|SFAPIclientCZ $api SuperFaktura API client.
	 */
	public function sf_clean_invoice_items( $invoice_id, $api ) {
		$response = $api->invoice( $invoice_id );
		if ( ! isset( $response->error ) || 0 == $response->error ) {
			if ( isset( $response->InvoiceItem ) && is_array( $response->InvoiceItem ) ) {
				$delete_item_ids = array();
				foreach ( $response->InvoiceItem as $item ) {
					$delete_item_ids[] = $item->id;
				}

				// Delete items in chunks of 50.
				$delete_item_ids_chunks = array_chunk( $delete_item_ids, 50 );
				foreach ( $delete_item_ids_chunks as $delete_item_ids_chunk ) {
					$delete_response = $api->deleteInvoiceItem( $invoice_id, $delete_item_ids_chunk );
				}
			}
		}
	}



	/**
	 * Check if invoice has to be created.
	 *
	 * @param int $order_id Order ID.
	 */
	public function sf_new_invoice( $order_id ) {
		$order          = wc_get_order( $order_id );
		$order_status   = $order->get_status();
		$payment_method = $order->get_payment_method();

		// Payment method is missing for zero value orders, we check the "Zero value invoices" status from settings using fake payment method name.
		if ( empty( $payment_method ) && 0.0 === abs( floatval( $order->get_total() ) ) ) {
			$payment_method = self::$zero_value_order_fake_payment_method_id;
		}

		// Payment method for WooCommerce Subscriptions manual renewal.
		if ( empty( $payment_method ) && class_exists( 'WC_Subscriptions' ) ) {
			$subscription_order_id = $order->get_meta( '_subscription_renewal', true );
			if ( $subscription_order_id ) {
				$subscription_order = wc_get_order( $subscription_order_id );
				$payment_method     = $subscription_order->get_payment_method();
			}
		}

		foreach ( array( 'regular', 'proforma', 'cancel' ) as $type ) {
			$generate_invoice_status = ( 'cancel' === $type ) ? null : get_option( 'woocommerce_sf_invoice_' . $type . '_' . $payment_method );

			if ( $order_status === $generate_invoice_status ) {
				$generate_invoice = true;
			} else {
				$generate_invoice = false;

				if ( 'regular' === $type ) {
					/*
					 * Workaround for orders that don't need processing. Invoice won't be generated in some cases because
					 * the "Processing" order state is skipped. We need to allow the generation of invoice in "Completed"
					 * order state instead.
					 */
					$workaround_enabled = wc_string_to_bool( get_option( 'woocommerce_sf_invoice_regular_processing_skipped_fix' ) );
					if ( $workaround_enabled && 'processing' === $generate_invoice_status && 'completed' === $order_status && ! $order->needs_processing() ) {
						$generate_invoice = true;
					}
				}
			}

			/**
			 * Filter to allow forcing or skipping invoice creation.
			 *
			 * Example:
			 * function custom_generate_invoice( $generate_invoice, $order, $type, $payment_method ) {
			 *     if ( 'regular' === $type && 'ready_to_pickup' === $order->get_status() ) {
			 *         return true;
			 *     }
			 *     return $generate_invoice;
			 * }
			 * add_filter( 'sf_generate_invoice', 'custom_generate_invoice', 10, 4 );
			 */
			$generate_invoice = apply_filters( 'sf_generate_invoice', $generate_invoice, $order, $type, $payment_method );

			if ( $generate_invoice ) {
				$this->sf_generate_invoice( $order, $type );
			}
		}
	}



	/**
	 * Regenerate existing invoices.
	 *
	 * @param int $order_id Order ID.
	 */
	public function sf_regen_invoice( $order_id ) {
		$order = wc_get_order( $order_id );

		$result = true;

		foreach ( array( 'proforma', 'regular', 'cancel' ) as $type ) {
			$sf_id = $order->get_meta( 'wc_sf_internal_' . $type . '_id', true );
			if ( ! empty( $sf_id ) ) {
				$result = $result && $this->sf_generate_invoice( $order, $type );
			}
		}

		return $result;
	}



	/**
	 * Create or update an invoice.
	 *
	 * @param WC_Order $order Order.
	 * @param string   $type Invoice type.
	 */
	public function sf_generate_invoice( $order, $type, $force_create = false ) {

		// Filter to allow skipping invoice creation.
		$skip_invoice = apply_filters( 'sf_skip_invoice', false, $order );
		if ( $skip_invoice ) {
			return false;
		}

		try {

			$credentials = apply_filters( 'sf_order_api_credentials', array(), $order );
			$api         = $this->sf_api( $credentials );

			if ( $force_create ) {
				$sf_id = null;
				$edit = false;
			} else {
				$sf_id = $order->get_meta( 'wc_sf_internal_' . $type . '_id', true );

				// Try to get $sf_id from deprecated meta data.
				if ( ! $sf_id ) {
					$old_sf_id = $order->get_meta( 'wc_sf_internal_id', true );
					if ( $old_sf_id ) {

						if ( $order->get_meta( 'wc_sf_invoice_regular', true ) ) {
							// If regular invoice link exists, it's a regular ID.
							$order->update_meta_data( 'wc_sf_internal_regular_id', $old_sf_id );

							// Use only if we are generating regular invoice.
							if ( 'regular' === $type ) {
								$sf_id = $old_sf_id;
							}
						} elseif ( $order->get_meta( 'wc_sf_invoice_proforma', true ) ) {
							// If proforma invoice link exists, it's a proforma ID.
							$order->update_meta_data( 'wc_sf_internal_proforma_id', $old_sf_id );

							// Use only if we are generating proforma invoice.
							if ( 'proforma' === $type ) {
								$sf_id = $old_sf_id;
							}
						}

						$order->save();
					}
				}

				$edit = false;
				if ( ! empty( $sf_id ) ) {

					$old_invoice_exists = true;
					$old_invoice        = $api->invoice( $sf_id );
					if ( empty( $old_invoice ) ) {
						$error = $api->getLastError();
						if ( isset( $error['status'] ) && '404' === $error['status'] ) {
							$old_invoice_exists = false;
						}
					}

					if ( $old_invoice_exists ) {
						if ( ! $this->sf_can_regenerate( $order ) ) {
							return new WP_Error( 'duplicate_document', __( 'Document was not created, because it already exists.', 'woocommerce-superfaktura' ) );
						}

						$this->sf_clean_invoice_items( $sf_id, $api );
						$edit = true;
					}
				}
			}

			if ( 'yes' === get_option( 'woocommerce_sf_prevent_concurrency', 'no' ) ) {
				$lock_file = get_temp_dir() . sprintf( 'lock_%s_%s_%s', $order->get_id(), $type, date( 'YmdHi' ) );
				$fp = fopen( $lock_file, 'x' );
				if ( ! $fp ) {
					throw new Exception( __( 'Request failed because of concurrency check.', 'woocommerce-superfaktura' ) );
				}
			}

			/* CLIENT DATA */

			if ( $this->wc_nastavenia_skcz_activated ) {
				$plugin  = Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ\Plugin::get_instance();
				$details = $plugin->get_customer_details( $order->get_id() );
				$ico     = $details->get_company_id();
				$ic_dph  = $details->get_company_vat_id();
				$dic     = $details->get_company_tax_id();
			} else {
				$ico    = $order->get_meta( 'billing_company_wi_id', true );
				$ic_dph = $order->get_meta( 'billing_company_wi_vat', true );
				$dic    = $order->get_meta( 'billing_company_wi_tax', true );
			}

			if ( empty( $ic_dph ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';

				// Compatibility with WooCommerce EU VAT Number plugin.
				if ( is_plugin_active( 'woocommerce-eu-vat-number/woocommerce-eu-vat-number.php' ) && 'true' === $order->get_meta( '_vat_number_is_valid', true ) ) {
					$ic_dph = $order->get_meta( '_vat_number', true );
					if ( empty( $ic_dph ) ) {
						$ic_dph = $order->get_meta( '_billing_vat_number', true );
					}
				}

				// Compatibility with WooCommerce EU VAT Assistant plugin.
				if ( is_plugin_active( 'woocommerce-eu-vat-assistant/woocommerce-eu-vat-assistant.php' ) && 'valid' === $order->get_meta( '_vat_number_validated', true ) ) {
					$ic_dph = $order->get_meta( 'vat_number', true );
				}

				// Compatibility with WooCommerce EU/UK VAT Compliance (Premium).
				if ( is_plugin_active( 'woocommerce-eu-vat-compliance-premium/eu-vat-compliance-premium.php' ) && 'true' === $order->get_meta( 'VAT number validated', true ) ) {
					$ic_dph = $order->get_meta( 'VAT Number', true );
				}

				// Compatibility with EU/UK VAT Manager for WooCommerce.
				if ( is_plugin_active( 'eu-vat-for-woocommerce/eu-vat-for-woocommerce.php' ) || is_plugin_active( 'eu-vat-for-woocommerce-pro/eu-vat-for-woocommerce-pro.php' ) ) {
					$ic_dph = $order->get_meta( '_billing_eu_vat_number', true );
				}
			}

			$client_data = array(
				'name'               => ( $order->get_billing_company() ) ? $order->get_billing_company() : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
				'ico'                => $ico,
				'dic'                => $dic,
				'ic_dph'             => $ic_dph,
				'email'              => $order->get_billing_email(),
				'address'            => $order->get_billing_address_1() . ( ( $order->get_billing_address_2() ) ? ' ' . $order->get_billing_address_2() : '' ),
				'country_iso_id'     => $order->get_billing_country(),
				'city'               => $order->get_billing_city(),
				'zip'                => $order->get_billing_postcode(),
				'phone'              => $order->get_billing_phone(),
				'update_addressbook' => ( 'yes' === get_option( 'woocommerce_sf_invoice_update_addressbook', 'no' ) ),
			);

			if ( $order->get_formatted_billing_address() !== $order->get_formatted_shipping_address() ) {
				if ( $order->get_shipping_company() ) {
					if ( 'yes' === get_option( 'woocommerce_sf_invoice_delivery_name' ) ) {
						$shipping_name = sprintf( '%s - %s %s', $order->get_shipping_company(), $order->get_shipping_first_name(), $order->get_shipping_last_name() );
					} else {
						$shipping_name = $order->get_shipping_company();
					}
				} else {
					$shipping_name = $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
				}

				$client_data['delivery_address']        = $order->get_shipping_address_1() . ( ( $order->get_shipping_address_2() ) ? ' ' . $order->get_shipping_address_2() : '' );
				$client_data['delivery_city']           = $order->get_shipping_city();
				$client_data['delivery_country_iso_id'] = $order->get_shipping_country();
				$client_data['delivery_name']           = $shipping_name;
				$client_data['delivery_zip']            = $order->get_shipping_postcode();
			}

			$shipping_phone = $order->get_shipping_phone();
			if ( $shipping_phone ) {
				$client_data['delivery_phone'] = $shipping_phone;
			}

			$client_data = apply_filters( 'sf_client_data', $client_data, $order );

			$api->setClient( $client_data );

			/* INVOICE DATA */

			$delivery_type = null;
			$shipping_methods = $order->get_shipping_methods();
			if ( !empty( $shipping_methods ) ) {
				$shipping_method  = reset( $shipping_methods );
				if ( class_exists( 'WC_Shipping_Zones' ) ) {
					$delivery_type = get_option( 'woocommerce_sf_shipping_' . $shipping_method['method_id'] . ':' . $shipping_method['instance_id'] );
				} else {
					$delivery_type = get_option( 'woocommerce_sf_shipping_' . $shipping_method['method_id'] );
				}
			}

			$set_invoice_data = array(
				'invoice_currency' => $order->get_currency(),
				'payment_type'     => get_option( 'woocommerce_sf_gateway_' . $order->get_payment_method() ),
				'delivery_type'    => $delivery_type,
				'rounding'         => get_option( 'woocommerce_sf_rounding', ( wc_prices_include_tax() ) ? 'item_ext' : 'document' ),
				'issued_by'        => get_option( 'woocommerce_sf_issued_by' ),
				'issued_by_phone'  => get_option( 'woocommerce_sf_issued_phone' ),
				'issued_by_web'    => get_option( 'woocommerce_sf_issued_web' ),
				'issued_by_email'  => get_option( 'woocommerce_sf_issued_email' ),
				'internal_comment' => $order->get_customer_note(),
				'order_no'         => $order->get_order_number(),
			);

			if ( 'cod' === $set_invoice_data['payment_type'] && 'yes' === get_option( 'woocommerce_sf_cod_add_rounding_item', 'no' ) ) {
				$set_invoice_data['add_rounding_item'] = true;
			}

			/* document relations */
			switch ( $type ) {
				case 'regular':
					$sf_proforma_id = $order->get_meta( 'wc_sf_internal_proforma_id', true );
					if ( $sf_proforma_id ) {
						$set_invoice_data['proforma_id'] = $sf_proforma_id;

						$proforma = $api->invoice( $set_invoice_data['proforma_id'] );
					}
					break;

				case 'cancel':
					$sf_invoice_id = $order->get_meta( 'wc_sf_internal_regular_id', true );
					if ( $sf_invoice_id ) {
						$set_invoice_data['parent_id'] = $sf_invoice_id;
					}
					break;
			}

			/* sequence */
			switch ( $type ) {
				case 'proforma':
					$set_invoice_data['sequence_id'] = get_option( 'woocommerce_sf_proforma_invoice_sequence_id' );
					break;

				case 'regular':
					$set_invoice_data['sequence_id'] = get_option( 'woocommerce_sf_invoice_sequence_id' );
					break;

				case 'cancel':
					$set_invoice_data['sequence_id'] = get_option( 'woocommerce_sf_cancel_sequence_id' );
					break;
			}

			/* logo */
			$set_invoice_data['logo_id'] = get_option( 'woocommerce_sf_logo_id' );

			/* bank account */
			$bank_account_id = get_option( 'woocommerce_sf_bank_account_id', null );
			if ( $bank_account_id ) {
				$set_invoice_data['bank_accounts'] = array(
					array( 'id' => $bank_account_id ),
				);
			}

			/* create date */
			if ( 'yes' === get_option( 'woocommerce_sf_created_date_as_order' ) ) {
				$set_invoice_data['created'] = (string) $order->get_date_created();
			}

			/* variable symbol */
			switch ( get_option( 'woocommerce_sf_variable_symbol' ) ) {

				case 'invoice_nr':
					if ( isset( $old_invoice ) && isset( $old_invoice->Invoice ) ) {
						$set_invoice_data['variable'] = $old_invoice->Invoice->invoice_no_formatted_raw;
					}
					break;

				case 'invoice_nr_match':
					if ( 'proforma' == $type && isset( $old_invoice ) && isset( $old_invoice->Invoice ) ) {
						$set_invoice_data['variable'] = $old_invoice->Invoice->invoice_no_formatted_raw;
					}

					if ( 'regular' === $type && isset( $proforma ) && isset( $proforma->Invoice ) ) {
						$set_invoice_data['variable'] = $proforma->Invoice->variable;
					}
					break;

				case 'order_nr':
					$set_invoice_data['variable'] = $order->get_order_number();
					break;
			}

			/* delivery date */
			switch ( get_option( 'woocommerce_sf_delivery_date_value', 'invoice_created' ) ) {

				case 'invoice_created':
					// Do nothing, SuperFaktura API will use invoice creation date by default.
					break;

				case 'order_paid':
					$delivery_date = $order->get_date_paid();
					if ( $delivery_date ) {
						$set_invoice_data['delivery'] = $delivery_date->date( 'Y-m-d' );
					}
					break;

				case 'order_created':
					$delivery_date = $order->get_date_created();
					if ( $delivery_date ) {
						$set_invoice_data['delivery'] = $delivery_date->date( 'Y-m-d' );
					}
					break;

				case 'none':
					$set_invoice_data['delivery'] = -1;
					break;

			}

			/* comment */
			if ( 'yes' === get_option( 'woocommerce_sf_comments' ) ) {
				$comment_parts = array();

				if ( 'yes' === get_option( 'woocommerce_sf_comment_add_proforma_payment', 'no' ) && isset( $proforma ) && isset( $proforma->Invoice ) && 1 != $proforma->Invoice->status ) {
					$comment_parts[] = sprintf(
						// Translators: %1$s Invoice number, %2$s Payment date.
						__( 'Paid with proforma invoice %1$s on %2$s.', 'woocommerce-superfaktura' ),
						$proforma->Invoice->invoice_no_formatted,
						date( 'j.n.Y', strtotime( $proforma->Invoice->paydate ) )
					);
				}

				if ( ( $client_data['ic_dph'] && WC()->countries->get_base_country() !== $order->get_billing_country() ) || ( in_array( WC()->countries->get_base_country(), array( 'SK', 'CZ' ), true ) && ! in_array( $order->get_billing_country(), $this->eu_countries, true ) ) ) {
					$set_invoice_data['vat_transfer'] = 1;

					$sf_tax_liability = get_option( 'woocommerce_sf_tax_liability' );
					if ( $sf_tax_liability ) {
						$comment_parts[] = $sf_tax_liability;
					}
				}
				elseif ( $edit ) {
					$set_invoice_data['vat_transfer'] = 0;
				}

				$sf_comment = get_option( 'woocommerce_sf_comment' );
				if ( $sf_comment ) {
					$comment_parts[] = $sf_comment;
				}

				if ( 'yes' === get_option( 'woocommerce_sf_comment_add_order_note' ) ) {
					$customer_note = $order->get_customer_note();
					if ( $customer_note ) {
						$comment_parts[] = $customer_note;
					}
				}

				$set_invoice_data['comment'] = implode( "\r\n\r\n", $comment_parts );
			}

			// Override invoice settings for specific countries based on customer billing address.
			$country_settings = json_decode( get_option( 'woocommerce_sf_country_settings', false ), true );
			if ( $country_settings ) {
				$country_settings = array_column( $country_settings, null, 'country' );

				$billing_country = $order->get_billing_country();

				$override_settings = $country_settings[ $billing_country ] ?? null;
				if ( empty( $override_settings ) ) {
					$override_settings = $country_settings[ '*' ] ?? null;
				}

				if ( ! empty( $override_settings ) ) {

					$client_country_data = array();

					/* override VAT */
					if ( $override_settings['vat_id'] ) {
						if ( empty( $override_settings['vat_id_only_final_consumer'] ) || ( ! empty( $override_settings['vat_id_only_final_consumer'] ) && empty( $client_data['ic_dph'] ) ) ) {
							$client_country_data['ic_dph'] = $override_settings['vat_id'];
						}
					}

					/* override TAX ID */
					if ( $override_settings['tax_id'] ) {
						if ( empty( $override_settings['vat_id_only_final_consumer'] ) || ( ! empty( $override_settings['vat_id_only_final_consumer'] ) && empty( $client_data['ic_dph'] ) ) ) {
							$client_country_data['dic'] = $override_settings['tax_id'];
						}
					}

					// Webikon, 20201001: Added a filter that allows to modify client data based on country.
					$client_country_data = apply_filters( 'sf_client_country_data', $client_country_data, $order );

					if ( ! empty( $client_country_data ) ) {
						$api->setMyData( $client_country_data );
					}

					/* override bank account */
					if ( $override_settings['bank_account_id'] ) {
						$set_invoice_data['bank_accounts'] = array(
							array(
								'id' => $override_settings['bank_account_id'],
							),
						);
					}

					/* override sequences */
					switch ( $type ) {
						case 'proforma':
							if ( $override_settings['proforma_sequence_id'] ) {
								$set_invoice_data['sequence_id'] = $override_settings['proforma_sequence_id'];
							}
							break;

						case 'regular':
							if ( $override_settings['invoice_sequence_id'] ) {
								$set_invoice_data['sequence_id'] = $override_settings['invoice_sequence_id'];
							}
							break;

						case 'cancel':
							if ( $override_settings['cancel_sequence_id'] ) {
								$set_invoice_data['sequence_id'] = $override_settings['cancel_sequence_id'];
							}
							break;
					}
				}
			}

			// Webikon, 20200521: added extra attribute $type to be able to identify credit note in the hook.
			$set_invoice_data = apply_filters( 'sf_invoice_data', $set_invoice_data, $order, $type );

			$api->setInvoice( $set_invoice_data );

			/* INVOICE SETTINGS */

			$settings = array(
				'language'     => $this->get_language( $order->get_id(), get_option( 'woocommerce_sf_invoice_language' ), true ),
				'signature'    => true,
				'payment_info' => true,
				'bysquare'     => 'yes' === get_option( 'woocommerce_sf_bysquare', 'yes' ),
			);

			if ( 'multi' === get_option( 'woocommerce_sf_sync_type', 'single' ) ) {
				$settings['callback_payment'] = site_url( '/' ) . '?callback=wc_sf_order_paid&secret_key=' . get_option( 'woocommerce_sf_sync_secret_key', false );
			}

			$api->setInvoiceSettings( $settings );

			$extras = array();

			if ( 'yes' === get_option( 'woocommerce_sf_oss', 'no' ) ) {

				// Pri vystavení faktúry s odberateľom z inej krajiny EÚ, ktorý je súkromná osoba (nepodnikateľ) alebo firma bez IČ DPH.
				if ( empty( $client_data['ic_dph'] ) && WC()->countries->get_base_country() !== $order->get_billing_country() && in_array( $order->get_billing_country(), $this->eu_countries, true ) ) {
					$extras['oss'] = true;
				}
			}

			/* packeta */
			$pickup_point_id = $order->get_meta( 'zasilkovna_id_pobocky', true );
			if ( $pickup_point_id ) {
				$extras['pickup_point_id'] = $pickup_point_id;
			}

			$weight = $order->get_meta( 'zasilkovna_custom_weight', true );
			if ( empty( $weight ) ) {
				$weight = $order->get_meta( '_cart_weight', true );
			}
			if ( $weight ) {
				$extras['weight'] = $weight;
			}

			$api->setInvoiceExtras( $extras );

			/* PAYMENT STATUS */

			if ( $this->order_is_paid( $order ) || 'yes' === get_option( 'woocommerce_sf_invoice_' . $type . '_' . $order->get_payment_method() . '_set_as_paid', 'no' ) ) {

				// Check if proforma was already paid.
				$proforma_already_paid = false;
				if ( isset( $set_invoice_data['proforma_id'] ) ) {
					if ( isset( $proforma ) && isset( $proforma->Invoice ) && 1 != $proforma->Invoice->status ) {
						$proforma_already_paid = true;
					}
				}

				if ( ! $proforma_already_paid ) {
					$api->setInvoice(
						array(
							'already_paid'     => true,
							'cash_register_id' => get_option( 'woocommerce_sf_cash_register_' . $order->get_payment_method() ),
						)
					);
				}
			}

			$tax_rates                   = array();
			$possible_discount_tax_rates = array();
			foreach ( $order->get_items( 'tax' ) as $tax_item ) {
				if ( 'WC_Order_Item_Tax' === get_class( $tax_item ) ) {
					$tax_rates[ $tax_item->get_rate_id() ] = $tax_item->get_rate_percent();

					if ( 0 < $tax_item->get_tax_total() ) {
						$possible_discount_tax_rates[ $tax_item->get_rate_id() ] = $tax_item->get_rate_percent();
					}
				}
			}

			$refunds = $order->get_refunds();
			if ( 'cancel' === $type && $refunds ) {

				/* REFUNDS */

				foreach ( $refunds as $refund ) {

					// Get refunded amount for items by quantity.
					$refund_items_price             = 0;
					$refund_items_price_without_tax = 0;

					// Subtract refunded amount for items by quantity, because quantity was already subtracted by get_qty_refunded_for_item() above.
					// :TODO: verify why this is here, probebly not necessary at all
					if ( 'yes' === get_option( 'woocommerce_sf_product_subtract_refunded_qty', 'no' ) ) {
						$refunded_items = $refund->get_items();
						if ( $refunded_items ) {
							foreach ( $refunded_items as $item ) {
								$refund_items_price             += abs( $item['qty'] ) * $refund->get_item_subtotal( $item, true );
								$refund_items_price_without_tax += abs( $item['qty'] ) * $refund->get_item_subtotal( $item, false );
							}
						}
					}

					$refund_price = abs( $refund->get_total() ) - $refund_items_price;

					// Skip refund if whole amount was refunded with items by quantity.
					if ( $refund_price <= 0 ) {
						continue;
					}

					$refund_price_without_tax = abs( $refund->get_total() ) - abs( $refund->get_total_tax() ) - $refund_items_price_without_tax;
					$refund_tax               = round( ( $refund_price - $refund_price_without_tax ) / $refund_price_without_tax * 100 );
					$refund_description       = $refund->get_reason();

					$refund_data = array(
						'name'        => __( 'Refunded', 'woocommerce-superfaktura' ),
						'description' => $refund_description ? $refund_description : '',
						'quantity'    => '',
						'unit'        => '',
						'unit_price'  => $refund_price_without_tax * -1,
						'tax'         => $refund_tax,
					);
					$refund_data = apply_filters( 'sf_refund_data', $refund_data, $order );

					$api->addItem( $refund_data );
				}

			} else {

				/* ITEMS */

				// Array of WC_Order_Item_Product.
				$items = $order->get_items();

				foreach ( $items as $item_id => $item ) {
					$product = $item->get_product();

					// Skip invalid product.
					if ( empty( $product ) ) {
						continue;
					}

					$item_tax = 0;
					$taxes    = $item->get_taxes();
					foreach ( $taxes['subtotal'] as $rate_id => $tax ) {
						if ( empty( $tax ) ) {
							continue;
						}
						$item_tax = $tax_rates[ $rate_id ];
					}

					$quantity = $item['qty'];

					// Subtract refunded items quantity.
					if ( 'yes' === get_option( 'woocommerce_sf_product_subtract_refunded_qty', 'no' ) ) {
						$quantity -= abs( $order->get_qty_refunded_for_item( $item_id ) );

						// Skip item if whole quantity was refunded.
						if ( $quantity <= 0 ) {
							continue;
						}
					}

					$item_data = array(
						'name'       => html_entity_decode( $item['name'] ),
						'quantity'   => $quantity,
						'sku'        => $product->get_sku(),
						'unit'       => 'ks',
						'unit_price' => $order->get_item_subtotal( $item, false, false ),
						'tax'        => $item_tax,
					);

					if ( 'cancel' === $type ) {
						$item_data['unit_price'] *= -1;
					}

					if ( 'per_item' === get_option( 'woocommerce_sf_coupon_invoice_items', 'total' ) ) {
						$item_discount = $order->get_item_subtotal( $item, true, false ) - $order->get_item_total( $item, true, false );
						if ( $item_discount ) {
							$item_discount_percent             = $item_discount / $order->get_item_subtotal( $item, true, false ) * 100;
							$item_data['discount']             = $item_discount_percent;
							$item_data['discount_description'] = __( get_option( 'woocommerce_sf_discount_name', 'Zľava' ), 'woocommerce-superfaktura' );

							$discount_description = $this->get_discount_description( $order );
							if ( $discount_description ) {
								$item_data['discount_description'] .= ', ' . $discount_description;
							}
						}
					}

					$product_id = ( isset( $item['variation_id'] ) && $item['variation_id'] > 0 ) ? $item['variation_id'] : $item['product_id'];
					$product    = wc_get_product( $product_id );

					if ($product) {
						$attributes                = $this->format_item_meta( $item, $product );
						$non_variations_attributes = $this->get_non_variations_attributes( $item['product_id'] );
						if ( $product->is_type( 'variation' ) ) {
							$variation = $this->convert_to_plaintext( $product->get_description() );

							$parent_product = wc_get_product( $item['product_id'] );
							$short_descr    = $this->convert_to_plaintext( $parent_product->get_short_description() );
						} else {
							$variation   = '';
							$short_descr = $this->convert_to_plaintext( $product->get_short_description() );
						}

						$template = get_option( 'woocommerce_sf_product_description', $this->product_description_template_default );

						$item_data['description'] = strtr(
							$template,
							array(
								'[ATTRIBUTES]'                => $attributes,
								'[NON_VARIATIONS_ATTRIBUTES]' => $non_variations_attributes,
								'[VARIATION]'                 => $variation,
								'[SHORT_DESCR]'               => $short_descr,
								'[SKU]'                       => $product->get_sku(),
								'[WEIGHT]'                    => $product->get_weight(),
							)
						);
						$item_data['description'] = $this->replace_single_attribute_tags( $item['product_id'], $item_data['description'] );

						// Compatibility with WooCommerce Wholesale Pricing plugin.
						$wprice = get_post_meta( $product->get_id(), 'wholesale_price', true );

						if ( ! $wprice && $product->is_on_sale() ) {
							$tax      = 1 + ( ( wc_get_price_excluding_tax( $product ) == 0 ) ? 0 : round( ( ( wc_get_price_including_tax( $product ) - wc_get_price_excluding_tax( $product ) ) / wc_get_price_excluding_tax( $product ) ), 2 ) );
							$discount = floatval( $product->get_regular_price() ) - floatval( $product->get_sale_price() );

							if ( 'yes' === get_option( 'woocommerce_sf_product_description_show_discount', 'yes' ) && $discount ) {
								$item_data['description'] = trim( $item_data['description'] . PHP_EOL . __( get_option( 'woocommerce_sf_discount_name', 'Zľava' ), 'woocommerce-superfaktura' ) . ' -' . $discount . ' ' . html_entity_decode( get_woocommerce_currency_symbol() ) );
							}
						}

						/* accounting */
						$item_type_product = get_option( 'woocommerce_sf_item_type_product' );
						if ( $item_type_product ) {
							$item_data['AccountingDetail']['type'] = $item_type_product;
						}

						$analytics_account_product = get_option( 'woocommerce_sf_analytics_account_product' );
						if ( $analytics_account_product ) {
							$item_data['AccountingDetail']['analytics_account'] = $analytics_account_product;
						}

						$synthetic_account_product = get_option( 'woocommerce_sf_synthetic_account_product' );
						if ( $synthetic_account_product ) {
							$item_data['AccountingDetail']['synthetic_account'] = $synthetic_account_product;
						}

						$preconfidence_product = get_option( 'woocommerce_sf_preconfidence_product' );
						if ( $preconfidence_product ) {
							$item_data['AccountingDetail']['preconfidence'] = $preconfidence_product;
						}

						$item_data = apply_filters( 'sf_item_data', $item_data, $order, $product );

						// skip free products
						if ( empty( $item_data['unit_price'] ) && 'yes' === get_option( 'woocommerce_sf_skip_free_products', 'no' ) ) {
							continue;
						}

						if ( $item_data ) {
							$api->addItem( $item_data );
						}
					}
				}

				// Compatibility with WooCommerce Gift Cards (https://woocommerce.com/products/gift-cards/) plugin.
				if ( is_plugin_active( 'woocommerce-gift-cards/woocommerce-gift-cards.php' ) ) {

					$giftcards = $order->get_items( 'gift_card' );
					if ( $giftcards ) {
						foreach ( $giftcards as $giftcard ) {
							$item_data = array(
								'name'        => __( 'Gift Card', 'woocommerce-superfaktura' ),
								'quantity'    => '',
								'unit'        => '',
								'unit_price'  => $giftcard->get_amount() * -1,
								'tax'         => 0,
								'description' => $giftcard->get_code(),
							);

							$api->addItem( $item_data );
						}
					}
				}

				/* FEES */

				if ( $order->get_fees() ) {
					foreach ( $order->get_fees() as $fee ) {
						$fee_total     = $fee->get_total();
						$fee_taxes     = $fee->get_taxes();
						$fee_tax_total = array_sum( $fee_taxes['total'] );

						$item_data = array(
							'name'       => $fee['name'],
							'quantity'   => '',
							'unit'       => '',
							'unit_price' => $fee_total,
							'tax'        => ( 0 == $fee_total ) ? 0 : round( ( $fee_tax_total / $fee_total ) * 100 ),
						);

						if ( 'cancel' === $type ) {
							$item_data['unit_price'] *= -1;
						}

						/* accounting */
						$item_type_fees = get_option( 'woocommerce_sf_item_type_fees' );
						if ( $item_type_fees ) {
							$item_data['AccountingDetail']['type'] = $item_type_fees;
						}

						$analytics_account_fees = get_option( 'woocommerce_sf_analytics_account_fees' );
						if ( $analytics_account_fees ) {
							$item_data['AccountingDetail']['analytics_account'] = $analytics_account_fees;
						}

						$synthetic_account_fees = get_option( 'woocommerce_sf_synthetic_account_fees' );
						if ( $synthetic_account_fees ) {
							$item_data['AccountingDetail']['synthetic_account'] = $synthetic_account_fees;
						}

						$preconfidence_fees = get_option( 'woocommerce_sf_preconfidence_fees' );
						if ( $preconfidence_fees ) {
							$item_data['AccountingDetail']['preconfidence'] = $preconfidence_fees;
						}

						$api->addItem( $item_data );
					}
				}

				/* SHIPPING */

				$shipping_price = $order->get_shipping_total() + $order->get_shipping_tax();

				$shipping_tax = 0;
				foreach ( $order->get_items( 'tax' ) as $tax_item ) {
					$tax_rate = WC_Tax::_get_tax_rate($tax_item->get_rate_id());

					if ( ! empty( $tax_item->get_shipping_tax_total() ) || '1' === $tax_rate['tax_rate_shipping'] ) {
						$shipping_tax = $tax_item->get_rate_percent();
					}
				}

				$shipping_item_name = ( $shipping_price > 0 ) ? __( get_option( 'woocommerce_sf_shipping_item_name', 'Poštovné' ), 'woocommerce-superfaktura' ) : __( get_option( 'woocommerce_sf_free_shipping_name' ), 'woocommerce-superfaktura' );

				if ( $shipping_item_name ) {
					$item_data = array(
						'name'       => $shipping_item_name,
						'quantity'   => '',
						'unit'       => '',
						'unit_price' => $shipping_price / ( 1 + $shipping_tax / 100 ),
						'tax'        => $shipping_tax,
					);

					if ( 'cancel' === $type ) {
						$item_data['unit_price'] *= -1;
					}

					/* accounting */
					$item_type_shipping = get_option( 'woocommerce_sf_item_type_shipping' );
					if ( $item_type_shipping ) {
						$item_data['AccountingDetail']['type'] = $item_type_shipping;
					}

					$analytics_account_shipping = get_option( 'woocommerce_sf_analytics_account_shipping' );
					if ( $analytics_account_shipping ) {
						$item_data['AccountingDetail']['analytics_account'] = $analytics_account_shipping;
					}

					$synthetic_account_shipping = get_option( 'woocommerce_sf_synthetic_account_shipping' );
					if ( $synthetic_account_shipping ) {
						$item_data['AccountingDetail']['synthetic_account'] = $synthetic_account_shipping;
					}

					$preconfidence_shipping = get_option( 'woocommerce_sf_preconfidence_shipping' );
					if ( $preconfidence_shipping ) {
						$item_data['AccountingDetail']['preconfidence'] = $preconfidence_shipping;
					}

					$item_data = apply_filters( 'sf_shipping_data', $item_data, $order );

					$api->addItem( $item_data );
				}

				/* DISCOUNT */

				if ( 'total' === get_option( 'woocommerce_sf_coupon_invoice_items', 'total' ) ) {
					if ( $order->get_total_discount() ) {

						// We use highest tax rate (in case there are several different tax rates in the order).
						$discount_tax = ( $possible_discount_tax_rates ) ? max( $possible_discount_tax_rates ) : 0;

						$discount_description = $this->get_discount_description( $order );

						$discount_data = array(
							'name'        => __( get_option( 'woocommerce_sf_discount_name', 'Zľava' ), 'woocommerce-superfaktura' ),
							'description' => $discount_description ? $discount_description : '',
							'quantity'    => '',
							'unit'        => '',
							'unit_price'  => ( 0 == $discount_tax ) ? $order->get_total_discount() * -1 : $order->get_total_discount( false ) / ( 1 + $discount_tax / 100 ) * -1,
							'tax'         => $discount_tax,
						);

						/* accounting */
						$item_type_discount = get_option( 'woocommerce_sf_item_type_discount' );
						if ( $item_type_discount ) {
							$discount_data['AccountingDetail']['type'] = $item_type_discount;
						}

						$analytics_account_discount = get_option( 'woocommerce_sf_analytics_account_discount' );
						if ( $analytics_account_discount ) {
							$discount_data['AccountingDetail']['analytics_account'] = $analytics_account_discount;
						}

						$synthetic_account_discount = get_option( 'woocommerce_sf_synthetic_account_discount' );
						if ( $synthetic_account_discount ) {
							$discount_data['AccountingDetail']['synthetic_account'] = $synthetic_account_discount;
						}

						$preconfidence_discount = get_option( 'woocommerce_sf_preconfidence_discount' );
						if ( $preconfidence_discount ) {
							$discount_data['AccountingDetail']['preconfidence'] = $preconfidence_discount;
						}

						$discount_data = apply_filters( 'sf_discount_data', $discount_data, $order );

						$api->addItem( $discount_data );
					}
				}
			}

			/* TAG */

			$tag = get_option( 'woocommerce_sf_invoice_tag' );
			if ( $tag ) {
				$tags   = (array) $api->getTags();
				$tag_id = array_search( strtolower( $tag ), array_map( 'strtolower', $tags ), true );

				if ( ! $tag_id ) {
					$add_tag_result = $api->addTag( array( 'name' => $tag ) );
					if ( $add_tag_result && ! $add_tag_result->error ) {
						$tag_id = $add_tag_result->tag_id;
					}
				}

				if ( $tag_id ) {
					$api->addTags( array( $tag_id ) );
				}
			}

			// 2019/05/31 webikon: added invoice items as an extra argument
			// 2020/05/07 webikon: added document type as an extra argument
			foreach ( apply_filters( 'woocommerce_sf_invoice_extra_items', array(), $order, $api->data['InvoiceItem'], $type ) as $extra_item ) {
				$api->addItem( $extra_item );
			}
		} catch ( Throwable $e ) {

			// Add log entry.
			$this->wc_sf_log(
				array(
					'order_id'         => $order->get_id(),
					'document_type'    => $type,
					'request_type'     => ( $edit ) ? 'edit' : 'create',
					'response_status'  => 990,
					'response_message' => sprintf( '%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine() ),
				)
			);

			return false;
		}

		if ( $edit ) {
			$api->setInvoice(
				array(
					'type' => apply_filters( 'woocommerce_sf_invoice_type', $type, 'edit' ),
					'id'   => $sf_id,
				)
			);

			$response = $api->edit();
		} else {
			$args = array(
				'type' => apply_filters( 'woocommerce_sf_invoice_type', $type, 'create' ),
			);

			$sequence_id = apply_filters( 'wc_sf_sequence_id', false, $type, $order );

			if ( ! $sequence_id ) {
				$sequence_id = '';
			}

			if ( $sequence_id ) {
				$args['sequence_id'] = $sequence_id;
			} else {
				$invoice_id = apply_filters( 'wc_sf_invoice_id', false, $type, $order );

				if ( ! $invoice_id ) {
					$invoice_id = 'yes' === get_option( 'woocommerce_sf_invoice_custom_num' ) ? $this->generate_invoice_id( $order, $type ) : '';
				}

				$args['invoice_no_formatted'] = $invoice_id;
			}

			$api->setInvoice( $args );

			$response = $api->save();
		}

		if ( 'yes' === get_option( 'woocommerce_sf_prevent_concurrency', 'no' ) ) {
			if ( isset( $_GET['callback'] ) && 'wc_sf_order_paid' === $_GET['callback'] ) {
				// Wait for all duplicate callbacks to be blocked.
				sleep( 1 );
			}

			fclose( $fp );
			unlink( $lock_file );
		}

		// Add log entry.
		$log_data = array(
			'order_id'      => $order->get_id(),
			'document_type' => $type,
			'request_type'  => ( $edit ) ? 'edit' : 'create',
		);

		if ( empty( $response ) ) {
			$error                        = $api->getLastError();
			$log_data['response_status']  = ( isset( $error['status'] ) ) ? $error['status'] : 999;
			$log_data['response_message'] = ( isset( $error['message'] ) ) ? $error['message'] : 'Request failed without further information.';

			// If request to create the document failed because SF API did not respond, we'll try again later
			if ( ! $edit &&                                                              // only for creating a new document
				! isset( $_GET['sf_invoice_' . $type . '_create'] ) &&                   // not for manual document creation
				'yes' === get_option( 'woocommerce_sf_retry_failed_api_calls', 'no' ) && // only if "Retry failed API calls" is enabled in plugin settings
				isset( $error['code'] ) && 'http_request_failed' == $error['code']       // only if the API call failed with "http_request_failed"
			) {
				$this->retry_generate_invoice_schedule( $order, $type );
			}

		} elseif ( isset( $response->error ) && $response->error ) {
			$log_data['response_status']  = $response->error;

			if ( isset( $response->message ) ) {
				$log_data['response_message'] = $response->message;
			} elseif ( isset( $response->error_message ) ) {
				if ( is_object( $response->error_message ) ) {
					$log_data['response_message'] = implode(
						' ',
						array_map(
							function( $a ) {
								return ( is_array( $a ) ) ? implode( ' ', $a ) : $a;
							},
							get_object_vars( $response->error_message )
						)
					);
				}
				else {
					$log_data['response_message'] = $response->error_message;
				}
			}
		}

		$this->wc_sf_log( $log_data );

		if ( empty( $response ) || ( isset( $response->error ) && 0 !== $response->error ) ) {
			return false;
		}

		if ( isset( $response->data->PaymentLink ) && 3 != $response->data->Invoice->status ) {
			// Save payment link if there is one and the invoice is not paid yet.
			$order->update_meta_data( 'wc_sf_payment_link', $response->data->PaymentLink );
		} else {
			// Delete payment link otherwise.
			$order->delete_meta_data( 'wc_sf_payment_link' );
		}

		// Save document ID.
		$internal_id = $response->data->Invoice->id;
		$order->update_meta_data( 'wc_sf_internal_' . $type . '_id', $internal_id );

		// Save formatted invoice number.
		$invoice_number = $response->data->Invoice->invoice_no_formatted;
		$order->update_meta_data( 'wc_sf_' . $type . '_invoice_number', $invoice_number );

		// Save pdf url.
		$language = $this->get_language( $order->get_id(), get_option( 'woocommerce_sf_invoice_language' ), true );
		$pdf      = ( ( 'yes' === get_option( 'woocommerce_sf_sandbox', 'no' ) ) ? $api::SANDBOX_URL : $api::SFAPI_URL ) . '/' . $language . '/invoices/pdf/' . $internal_id . '/token:' . $response->data->Invoice->token;
		$order->update_meta_data( 'wc_sf_invoice_' . $type, $pdf );

		switch ( $type ) {
			case 'proforma':
				$order->add_order_note( __( 'Proforma invoice created.', 'woocommerce-superfaktura' ) );
				break;

			case 'regular':
				$order->add_order_note( __( 'Invoice created.', 'woocommerce-superfaktura' ) );
				break;

			case 'cancel':
				$order->add_order_note( __( 'Credit note created.', 'woocommerce-superfaktura' ) );
				break;

			default:
				$order->add_order_note( __( 'Document created.', 'woocommerce-superfaktura' ) );
				break;
		}

		$order->save();

		return true;
	}



	/**
	 * Write log entry to database.
	 *
	 * @param array $log_data Data to write to log table.
	 */
	private function wc_sf_log( $log_data ) {
		global $wpdb;

		$log_data['time'] = current_time( 'mysql' );
		$wpdb->insert( $wpdb->prefix . 'wc_sf_log', $log_data );
	}



	/**
	 * Format product attributes.
	 *
	 * @param array      $item Item data.
	 * @param WC_Product $product Product.
	 */
	private function format_item_meta( $item, $product ) {

		$item_meta = $item['item_meta'];

		if ( empty( $item_meta ) ) {
			return false;
		}

		$processed_item_meta = $item_meta;

		// Remove meta from WooCommerce Product Add-Ons plugin.
		unset( $processed_item_meta['product_extras'] );

		// Compatibility with N-Media WooCommerce PPOM plugin.
		if ( function_exists( 'ppom_woocommerce_order_key' ) ) {
			$processed_item_meta = array();
			foreach ( $item_meta as $meta_key => $meta_value ) {
				$meta_key                         = ppom_woocommerce_order_key( $meta_key, null, $item );
				$processed_item_meta[ $meta_key ] = html_entity_decode( wp_strip_all_tags( $meta_value ) );
			}
		}

		// Compatibility with YITH WooCommerce Product Add-ons & Extra Options plugin.
		if ( defined( 'YITH_WAPO' ) ) {
			$processed_item_meta = array();
			foreach ( $item_meta as $meta_key => $meta_value ) {

				if ( 0 === strpos( $meta_key, 'ywapo-addon-' ) ) {
					list( $addon_id, $option_id ) = explode( '-', str_replace( 'ywapo-addon-', '', $meta_key) );
					$info = yith_wapo_get_option_info( $addon_id, $option_id );
					$meta_key = $info['addon_label'] ?? $meta_key;
				}

				$processed_item_meta[ $meta_key ] = $meta_value;
			}
		}

		if ( empty( $processed_item_meta ) || ! is_array( $processed_item_meta ) ) {
			return false;
		}

		$result = array();
		foreach ( $processed_item_meta as $attribute => $slug ) {

			// Skip meta attributes.
			if ( '_' === $attribute[0] ) {
				continue;
			}

			$value = '';
			if ( taxonomy_exists( esc_attr( str_replace( 'attribute_', '', $attribute ) ) ) ) {
				$term = get_term_by( 'slug', $slug, esc_attr( str_replace( 'attribute_', '', $attribute ) ) );
				if ( ! is_wp_error( $term ) && $term->name ) {
					$value = $term->name;
				}
			} else {
				$value = apply_filters( 'woocommerce_variation_option_name', $slug, null, $attribute, $product );
			}

			$result[] = sprintf( '%s: %s', wc_attribute_label( $attribute, $product ), $value );
		}

		$separator = apply_filters( 'sf_attr_separator', ', ' );
		return implode( $separator, $result );
	}



	/**
	 * Format discount description.
	 *
	 * @param WC_Order $order Order.
	 */
	private function get_discount_description( $order ) {
		$coupons_codes = $order->get_used_coupons();
		if ( ! $coupons_codes ) {
			return false;
		}

		$coupons = array();
		foreach ( $coupons_codes as $coupon_code ) {
			$coupon = new WC_Coupon( $coupon_code );

			$sign = '';
			if ( $coupon->is_type( 'fixed_cart' ) ) {
				$sign = ' ' . $order->get_currency();
			} elseif ( $coupon->is_type( 'percent' ) ) {
				$sign = '%';
			}

			if ( 'yes' === get_option( 'woocommerce_sf_product_description_show_coupon_code', 'yes' ) ) {
				$coupons[] = $coupon_code . ' (' . $coupon->get_amount() . $sign . ')';
			} else {
				$coupons[] = $coupon->get_amount() . $sign;
			}
		}

		$result = __( 'Coupons', 'woocommerce-superfaktura' ) . ': ' . implode( ', ', $coupons );
		return $result;
	}



	/**
	 * Get invoice language.
	 *
	 * @param int    $order_id Order ID.
	 * @param string $woocommerce_sf_invoice_language Option value from plugin settings.
	 * @param bool   $strict If true allows only languages supported by SuperFaktura.
	 */
	private function get_language( $order_id, $woocommerce_sf_invoice_language, $strict = false ) {
		$locale_map = array(
			'sk' => 'slo',
			'cs' => 'cze',
			'en' => 'eng',
			'de' => 'deu',
			'nl' => 'nld',
			'hr' => 'hrv',
			'hu' => 'hun',
			'pl' => 'pol',
			'ro' => 'rom',
			'ru' => 'rus',
			'sl' => 'slv',
			'es' => 'spa',
			'it' => 'ita',
			'uk' => 'ukr',
		);

		$language = $woocommerce_sf_invoice_language;
		switch ( $language ) {
			case 'locale':
				$locale = substr( get_locale(), 0, 2 );
				if ( isset( $locale_map[ $locale ] ) ) {
					$language = $locale_map[ $locale ];
				}
				break;

			case 'wpml':
				$order = wc_get_order( $order_id );
				$wpml_language = $order->get_meta( 'wpml_language', true );
				if ( isset( $locale_map[ $wpml_language ] ) ) {
					$language = $locale_map[ $wpml_language ];
				}

				if ( class_exists( 'sitepress' ) ) {
					global $sitepress;
					$sitepress->switch_lang( $wpml_language, false );
				}
				break;

			case 'endpoint':
			default:
				// Nothing to do.
				break;
		}

		if ( $strict ) {
			if ( ! in_array( $language, $locale_map, true ) ) {
				$language = ( 'cz' === get_option( 'woocommerce_sf_lang' ) ) ? 'cze' : 'slo';
			}
		}

		$language = apply_filters( 'sf_invoice_language', $language, $order_id, $woocommerce_sf_invoice_language );

		return $language;
	}


	/**
	 * Get non-variation product attributes.
	 *
	 * @param int $product_id Product ID.
	 */
	private function get_non_variations_attributes( $product_id ) {
		$attributes = get_post_meta( $product_id, '_product_attributes' );
		if ( ! $attributes ) {
			return false;
		}
		$result = array();
		foreach ( $attributes[0] as $attribute ) {
			if ( $attribute['is_variation'] ) {
				continue;
			}

			if ( $attribute['is_taxonomy'] ) {
				$taxonomy = get_taxonomy( $attribute['name'] );
				$result[] = ( ( $taxonomy ) ? $taxonomy->labels->singular_name : $attribute['name'] ) . ': ' . implode( ', ', wc_get_product_terms( $product_id, $attribute['name'], array( 'fields' => 'names' ) ) );
			} else {
				$result[] = $attribute['name'] . ': ' . $attribute['value'];
			}
		}

		$separator = apply_filters( 'sf_attr_separator', ', ' );
		return implode( $separator, $result );
	}



	/**
	 * Replace [ATTRIBUTE:name] tags in product description.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $description Product description.
	 */
	private function replace_single_attribute_tags( $product_id, $description ) {

		preg_match_all( '/\[ATTRIBUTE:([^\]]*)\]/', $description, $matches, PREG_SET_ORDER );

		if ( $matches ) {
			$attributes = get_post_meta( $product_id, '_product_attributes' );

			foreach ( $matches as $match ) {
				$att_name  = $match[1];
				$att_slug  = sanitize_title( $match[1] );
				$att_value = null;

				if ( isset( $attributes[0][ $att_slug ] ) ) {
					$att_value = $attributes[0][ $att_slug ]['value'];
				} elseif ( isset( $attributes[0][ 'pa_' . $att_slug ] ) ) {
					$att_value = implode( ', ', wc_get_product_terms( $product_id, 'pa_' . $att_slug, array( 'fields' => 'names' ) ) );
				}

				$description = str_replace( $match[0], ( $att_value ) ? sprintf( '%s: %s', $att_name, $att_value ) : '', $description );
			}
		}

		return $description;
	}



	/**
	 * Add additional billing fields to checkout page.
	 *
	 * @param array $fields Billing fields.
	 */
	public function billing_fields( $fields ) {
		$new_fields = array();
		foreach ( $fields as $key => $value ) {

			if ( 'billing_company' === $key ) {

				// Add "Buy as Business client" checkbox.
				$new_fields['wi_as_company'] = array(
					'type'  => 'checkbox',
					'label' => __( 'Buy as Business client', 'woocommerce-superfaktura' ),
					'class' => array( 'form-row-wide' ),
				);

				// Keep "Company name" field.
				$new_fields[ $key ]             = $value;
				$new_fields[ $key ]['required'] = false;

				// Add "ID #" field (ICO).
				if ( 'no' !== get_option( 'woocommerce_sf_add_company_billing_fields_id', 'optional' ) ) {
					$new_fields['billing_company_wi_id'] = array(
						'type'     => 'text',
						'label'    => __( 'ID #', 'woocommerce-superfaktura' ),
						'required' => false,
						'class'    => array( 'form-row-wide' ),
					);
				}

				// Add "VAT #" field (IC DPH).
				if ( 'no' !== get_option( 'woocommerce_sf_add_company_billing_fields_vat', false ) ) {
					$new_fields['billing_company_wi_vat'] = array(
						'type'     => 'text',
						'label'    => __( 'VAT #', 'woocommerce-superfaktura' ),
						'required' => false,
						'class'    => array( 'form-row-wide' ),
					);
				}

				// Add "TAX ID #" field (DIC).
				if ( 'no' !== get_option( 'woocommerce_sf_add_company_billing_fields_tax', false ) ) {
					$new_fields['billing_company_wi_tax'] = array(
						'type'     => 'text',
						'label'    => __( 'TAX ID #', 'woocommerce-superfaktura' ),
						'required' => false,
						'class'    => array( 'form-row-wide' ),
					);
				}

				continue;
			}

			// Keep all other fields without change.
			$new_fields[ $key ] = $value;
		}

		return $new_fields;
	}



	/**
	 * Customize billing fields labels.
	 *
	 * @param string $field Billing field HTML code.
	 * @param string $key Billing field name.
	 * @param array  $args Billing field parameters.
	 * @param string $value Billing field value.
	 */
	public function billing_fields_labels( $field, $key, $args, $value ) {
		$replace = false;
		switch ( $key ) {
			case 'billing_company':
				$replace = ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_name', 'optional' ) );
				break;

			case 'billing_company_wi_id':
				$replace = ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_id', 'optional' ) );
				break;

			case 'billing_company_wi_vat':
				$replace = ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_vat', 'optional' ) );
				break;

			case 'billing_company_wi_tax':
				$replace = ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_tax', 'optional' ) );
				break;
		}

		if ($replace) {
			$field = str_replace( '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>', '&nbsp;<abbr class="required" title="' . esc_html__( 'required', 'woocommerce' ) . '">*</abbr>', $field );
		}

		return $field;
	}



	/*
	 * Validate EU VAT Number
	 */
	public function validate_eu_vat_number( $vat_number ) {
		$sanitized_vat_number = preg_replace( '/[^A-Z0-9]/i', '', sanitize_text_field( $vat_number ) );
		$country = substr( $sanitized_vat_number, 0, 2 );
		$vatno = substr( $sanitized_vat_number, 2 );

		if ( ! in_array( strtoupper( $country ), $this->eu_vat_countries ) ) {
			return null;
		}

		try {
			$response = wp_remote_get(
				"https://ec.europa.eu/taxation_customs/vies/rest-api/ms/{$country}/vat/{$vatno}",
				array(
					'timeout' => 10
				)
			);

			if (is_wp_error($response)) {
				$this->wc_sf_log(
					array(
						'request_type'     => 'eu_vat_number',
						'response_status'  => ( (int)$response->get_error_code() ) ? $response->get_error_code() : 912,
						'response_message' => $response->get_error_message(),
					)
				);

				return null;
			}

			if (substr($response['response']['code'], 0, 1) != 2) {
				$this->wc_sf_log(
					array(
						'request_type'     => 'eu_vat_number',
						'response_status'  => $response['response']['code'],
						'response_message' => $response['response']['message'],
					)
				);

				return null;
			}

			$result = json_decode($response['body']);

		} catch (Exception $e) {
			$this->wc_sf_log(
				array(
					'request_type'     => 'eu_vat_number',
					'response_status'  => 911,
					'response_message' => $this->exceptionHandling($e),
				)
			);

			return null;
		}



		if ( 'VALID' !== $result->userError ) {
			return false;
		}

		return true;
	}



	/**
	 * Validate additional billing fields in checkout.
	 */
	public function checkout_process() {
		if ( isset( $_POST['wi_as_company'] ) ) {
			if ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_name', 'optional' ) && empty( $_POST['billing_company'] )) {
				// Translators: %s Field name.
				wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( __( 'Company name', 'woocommerce' ) ) . '</strong>' ), 'error' );
			}

			if ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_id', 'optional' ) && empty( $_POST['billing_company_wi_id'] ) ) {
				// Translators: %s Field name.
				wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( __( 'ID #', 'woocommerce-superfaktura' ) ) . '</strong>' ), 'error' );
			}

			if ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_vat', 'optional' ) && empty( $_POST['billing_company_wi_vat'] ) ) {
				// Translators: %s Field name.
				wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( __( 'VAT #', 'woocommerce-superfaktura' ) ) . '</strong>' ), 'error' );
			}
			elseif ( 'yes' === get_option( 'woocommerce_sf_validate_eu_vat_number', 'no' ) && ! empty( $_POST['billing_company_wi_vat'] ) ) {
				$valid_eu_vat_number = $this->validate_eu_vat_number( $_POST['billing_company_wi_vat'] );
				if ( false === $valid_eu_vat_number ) {
					// Translators: %s Field name.
					wc_add_notice( sprintf( __( '%s is not valid.', 'woocommerce-superfaktura' ), '<strong>' . esc_html( __( 'VAT #', 'woocommerce-superfaktura' ) ) . '</strong>' ), 'error' );
				}
				elseif ( null === $valid_eu_vat_number ) {
					wc_add_notice( sprintf( __( '%s could not be validated.', 'woocommerce-superfaktura' ), '<strong>' . esc_html( __( 'VAT #', 'woocommerce-superfaktura' ) ) . '</strong>' ), 'error' );
				}
			}

			if ( 'required' === get_option( 'woocommerce_sf_add_company_billing_fields_tax', 'optional' ) && empty( $_POST['billing_company_wi_tax'] ) ) {
				// Translators: %s Field name.
				wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( __( 'TAX ID #', 'woocommerce-superfaktura' ) ) . '</strong>' ), 'error' );
			}
		}
	}



	/**
	 * Process additional billing fields in checkout.
	 *
	 * @param int $order_id Order ID.
	 */
	public function checkout_order_meta( $order_id ) {
		$order = wc_get_order( $order_id );

		$order->update_meta_data( 'has_shipping', ( isset( $_POST['shiptobilling'] ) && '1' == $_POST['shiptobilling'] ) ? '0' : '1' );

		if ( isset( $_POST['wi_as_company'] ) && '1' == $_POST['wi_as_company'] ) {
			foreach ( array( 'billing_company_wi_id', 'billing_company_wi_vat', 'billing_company_wi_tax' ) as $key ) {
				if ( isset( $_POST[ $key ] ) ) {
					$order->update_meta_data( $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
				}
			}
		} else {
			// Delete the private custom fields prefixed with "_" which are automatically saved even if "Buy as Business client" checkbox is not checked.
			foreach ( array( '_billing_company_wi_id', '_billing_company_wi_vat', '_billing_company_wi_tax' ) as $key ) {
				$order->delete_meta_data( $key );
			}
		}

		$order->save();
	}



	/**
	 * Add additional billing fields to admin order page.
	 *
	 * @param array $fields Billing fields.
	 */
	public function woocommerce_admin_billing_fields( $fields ) {

		$fields['company_wi_id'] = array(
			'label'         => __( 'ID #', 'woocommerce-superfaktura' ),
			'show'          => true,
			'wrapper_class' => 'form-field-wide',
		);

		$fields['company_wi_vat'] = array(
			'label'         => __( 'VAT #', 'woocommerce-superfaktura' ),
			'show'          => true,
			'wrapper_class' => 'form-field-wide',
		);

		$fields['company_wi_tax'] = array(
			'label'         => __( 'TAX ID #', 'woocommerce-superfaktura' ),
			'show'          => true,
			'wrapper_class' => 'form-field-wide',
		);

		return $fields;
	}



	/**
	 * Process additional billing fields in admin order page.
	 *
	 * @param int      $order_id Order ID.
	 * @param WP_Order $order Order.
	 */
	public function woocommerce_process_shop_order_meta( $order_id, $order ) {
		if ( ! $order instanceof WC_Order ) {
			$order = wc_get_order( $order_id );
		}

		$should_save = false;

		// Because the filter "woocommerce_admin_billing_fields" above saves only private custom fields prefixed with "_" and the plugin for some reason uses duplicates of these fields without the prefix, we need to update those values here as well.
		foreach ( array( 'billing_company_wi_id', 'billing_company_wi_vat', 'billing_company_wi_tax' ) as $key ) {
			if ( isset( $_POST[ '_' . $key ] ) ) {
				$order->update_meta_data( $key, sanitize_text_field( wp_unslash( $_POST[ '_' . $key ] ) ) );
				$should_save = true;
			}
		}

		if ( $should_save ) {
			$order->save();
		}
	}



	/**
	 * Add additional billing fields to admin user profile page.
	 *
	 * @param array $fields User profile fields.
	 */
	function woocommerce_customer_meta_fields( $fields ) {
		if ( isset( $fields['billing']['fields'] ) ) {
			$fields['billing']['fields']['billing_company_wi_id'] = array(
				'label'       => __( 'ID #', 'woocommerce-superfaktura' ),
				'description' => '',
			);

			$fields['billing']['fields']['billing_company_wi_vat'] = array(
				'label'       => __( 'VAT #', 'woocommerce-superfaktura' ),
				'description' => '',
			);

			$fields['billing']['fields']['billing_company_wi_tax'] = array(
				'label'       => __( 'TAX ID #', 'woocommerce-superfaktura' ),
				'description' => '',
			);
		}

		return $fields;
	}



	/**
	 * Add additional billing fields values to customer details.
	 *
	 * @param  array $data Customer data.
	 * @param  WC_Customer $customer Customer.
	 * @param  int $user_id User ID.
	 *
	 * @return
	 */
	function woocommerce_ajax_get_customer_details( $data, $customer, $user_id ) {
		if ( isset ( $data['billing'] ) ) {
			$data['billing']['company_wi_id'] = get_user_meta( $user_id, 'billing_company_wi_id', true );
			$data['billing']['company_wi_vat'] = get_user_meta( $user_id, 'billing_company_wi_vat', true );
			$data['billing']['company_wi_tax'] = get_user_meta( $user_id, 'billing_company_wi_tax', true );
		}

		return $data;
	}



	/**
	 * Create tab in WooCommerce settings.
	 *
	 * @param array $settings WooCommerce settings.
	 */
	public function woocommerce_settings( $settings ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-settings-superfaktura.php';
		$settings[] = new WC_Settings_SuperFaktura();
		return $settings;
	}



	/**
	 * Create invoices meta box in order screen.
	 */
	public function add_meta_boxes() {
		try {
			$screen = $this->hpos_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
		} catch ( Exception $e ) {
			$screen = 'shop_order';
		}

		add_meta_box( 'wc_sf_invoice_box', __( 'Invoices', 'woocommerce-superfaktura' ), array( $this, 'add_box' ), $screen, 'side' );
	}



	/**
	 * Create invoices meta box actions for downloading PDFs.
	 *
	 * @param array    $actions Actions.
	 * @param WC_Order $order Order.
	 */
	public function my_orders_actions( $actions, $order ) {
		$pdf = $order->get_meta( 'wc_sf_invoice_proforma', true );
		if ( $pdf ) {
			$actions['wc_sf_invoice_proforma'] = array(
				'url'  => $pdf,
				'name' => __( 'Proforma', 'woocommerce-superfaktura' ),
			);
		}
		$pdf = $order->get_meta( 'wc_sf_invoice_regular', true );
		if ( $pdf ) {
			$actions['wc_sf_invoice_regular'] = array(
				'url'  => $pdf,
				'name' => __( 'Invoice', 'woocommerce-superfaktura' ),
			);
		}

		return $actions;
	}



	/**
	 * Check if invoices can be regenerated.
	 *
	 * @param WC_Order $order Order.
	 */
	private function sf_can_regenerate( $order ) {
		$can_regenerate = true;

		if ( 'completed' === $order->get_status() ) {
			$can_regenerate = false;
		}

		if ( 'processing' === $order->get_status() && 'cod' !== $order->get_payment_method() ) {
			$can_regenerate = false;
		}

		return apply_filters( 'sf_can_regenerate', $can_regenerate, $order );
	}



	/**
	 * Create invoices meta box content.
	 *
	 * @param WP_Post $post Post.
	 */
	public function add_box( $post ) {
		if ( $post instanceof WC_Order ) {
			$order = $post;
		}
		else {
			$order = wc_get_order( $post->ID );
		}

		$proforma = $order->get_meta( 'wc_sf_invoice_proforma', true );
		$invoice  = $order->get_meta( 'wc_sf_invoice_regular', true );
		$cancel   = $order->get_meta( 'wc_sf_invoice_cancel', true );

		echo wp_kses( '<p><strong>' . __( 'View Generated Invoices', 'woocommerce-superfaktura' ) . '</strong>:', $this->allowed_tags );
		if ( empty( $proforma ) && empty( $invoice ) ) {
			echo wp_kses( '<br>' . __( 'No invoice was generated', 'woocommerce-superfaktura' ), $this->allowed_tags );
		}
		echo wp_kses( '</p>', $this->allowed_tags );

		if ( ! empty( $proforma ) ) {
			$error_html = sprintf( '%s<br><a href="%s">%s</a>', __( 'Proforma could not be found in SuperFaktura.', 'woocommerce-superfaktura' ), admin_url( 'admin.php?sf_invoice_proforma_create=1&force_create=1&sf_order=' . $order->get_id() ), __( 'Create new proforma invoice', 'woocommerce-superfaktura' ) );
			echo wp_kses( '<p><a href="' . $proforma . '" class="button sf-url-check" data-error="' . htmlentities( $error_html ) . '" target="_blank">' . __( 'Proforma', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		} elseif ( 'yes' === get_option( 'woocommerce_sf_invoice_proforma_manual', 'no' ) ) {
			echo wp_kses( '<p><a href="' . admin_url( 'admin.php?sf_invoice_proforma_create=1&sf_order=' . $order->get_id() ) . '">' . __( 'Create proforma invoice', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		}

		if ( ! empty( $invoice ) ) {
			$error_html = sprintf( '%s<br><a href="%s">%s</a>', __( 'Invoice could not be found in SuperFaktura.', 'woocommerce-superfaktura' ), admin_url( 'admin.php?sf_invoice_regular_create=1&force_create=1&sf_order=' . $order->get_id() ), __( 'Create new invoice', 'woocommerce-superfaktura' ) );
			echo wp_kses( '<p><a href="' . $invoice . '" class="button sf-url-check" data-error="' . htmlentities( $error_html ) . '" target="_blank">' . __( 'Invoice', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		} elseif ( 'yes' === get_option( 'woocommerce_sf_invoice_regular_manual', 'no' ) ) {
			echo wp_kses( '<p><a href="' . admin_url( 'admin.php?sf_invoice_regular_create=1&sf_order=' . $order->get_id() ) . '">' . __( 'Create invoice', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		}

		if ( ! empty( $cancel ) ) {
			$error_html = sprintf( '%s<br><a href="%s">%s</a>', __( 'Credit note could not be found in SuperFaktura.', 'woocommerce-superfaktura' ), admin_url( 'admin.php?sf_invoice_cancel_create=1&force_create=1&sf_order=' . $order->get_id() ), __( 'Create new credit note', 'woocommerce-superfaktura' ) );
			echo wp_kses( '<p><a href="' . $cancel . '" class="button sf-url-check" data-error="' . htmlentities( $error_html ) . '" target="_blank">' . __( 'Credit note', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		} elseif ( ! empty( $invoice ) && ( $order->get_refunds() || in_array( $order->get_status(), array( 'cancelled', 'refunded', 'failed' ), true ) ) ) {
			echo wp_kses( '<p><a href="' . admin_url( 'admin.php?sf_invoice_cancel_create=1&sf_order=' . $order->get_id() ) . '">' . __( 'Create credit note', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		}

		if ( ( ! empty( $proforma ) || ! empty( $invoice ) ) && $this->sf_can_regenerate( $order ) ) {
			echo wp_kses( '<p><a href="' . esc_url( admin_url( 'admin.php?sf_regen=1&sf_order=' . $order->get_id() ) ) . '">' . __( 'Regenerate existing invoices', 'woocommerce-superfaktura' ) . '</a></p>', $this->allowed_tags );
		}

		// 2020/07/01 webikon: Added an action that allows to add content after the invoice button
		do_action( 'sf_metabox_after_invoice_generate_button', $order, $invoice );
	}



	/**
	 * Generate invoice ID.
	 *
	 * @param WC_Order $order Order.
	 * @param string   $key Invoice type.
	 */
	private function generate_invoice_id( $order, $key = 'regular' ) {
		$invoice_id = $order->get_meta( 'wc_sf_invoice_' . $key . '_id', true );
		if ( ! empty( $invoice_id ) ) {
			return $invoice_id;
		}

		$invoice_id_template = get_option( 'woocommerce_sf_invoice_' . $key . '_id', true );
		if ( empty( $invoice_id_template ) ) {
			$invoice_id_template = '[YEAR][MONTH][COUNT]';
		}

		$num_decimals = get_option( 'woocommerce_sf_invoice_count_decimals', true );
		if ( empty( $num_decimals ) ) {
			$num_decimals = 4;
		}

		$count = get_option( 'woocommerce_sf_invoice_' . $key . '_count', true );
		update_option( 'woocommerce_sf_invoice_' . $key . '_count', intval( $count ) + 1 );
		$count = str_pad( $count, intval( $num_decimals ), '0', STR_PAD_LEFT );

		$date = current_time( 'timestamp' );

		$template_tags = array(
			'[YEAR]'         => date( 'Y', $date ),
			'[YEAR_SHORT]'   => date( 'y', $date ),
			'[MONTH]'        => date( 'm', $date ),
			'[DAY]'          => date( 'd', $date ),
			'[COUNT]'        => $count,
			'[ORDER_NUMBER]' => $order->get_order_number(),
		);
		$invoice_id    = strtr( $invoice_id_template, $template_tags );

		$invoice_id = apply_filters( 'superfaktura_invoice_id', $invoice_id, $template_tags, $key );

		$order->update_meta_data( 'wc_sf_invoice_' . $key . '_id', $invoice_id );
		$order->save();

		return $invoice_id;
	}



	/**
	 * Create invoice link for "Thank You" page.
	 *
	 * @param int $order_id Order ID.
	 */
	public function sf_invoice_link_page( $order_id ) {
		if ( 'yes' === get_option( 'woocommerce_sf_order_received_invoice_link', 'yes' ) ) {
			$order = wc_get_order( $order_id );

			$pdf = $order->get_meta( 'wc_sf_invoice_regular', true );
			if ( $pdf ) {
				echo wp_kses( '<section class="woocommerce-superfaktura"><h2 class="woocommerce-superfaktura__title">' . __( 'Invoice', 'woocommerce-superfaktura' ) . "</h2>\n\n" . '<a href="' . esc_attr( $pdf ) . '" target="_blank">' . __( 'Download invoice', 'woocommerce-superfaktura' ) . "</a></section>\n\n", $this->allowed_tags );
				return;
			}

			$pdf = $order->get_meta( 'wc_sf_invoice_proforma', true );
			if ( $pdf ) {
				echo wp_kses( '<section class="woocommerce-superfaktura"><h2 class="woocommerce-superfaktura__title">' . __( 'Proforma invoice', 'woocommerce-superfaktura' ) . "</h2>\n\n" . '<a href="' . esc_attr( $pdf ) . '" target="_blank">' . __( 'Download proforma invoice', 'woocommerce-superfaktura' ) . "</a></section>\n\n", $this->allowed_tags );
			}
		}
	}


	/**
	 * Get invoice data.
	 *
	 * @param int $order_id Order ID.
	 */
	private function get_invoice_data( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}

		$pdf = $order->get_meta( 'wc_sf_invoice_regular', true );
		if ( $pdf ) {
			return array(
				'type'       => 'regular',
				'pdf'        => $pdf,
				'invoice_id' => (int) $order->get_meta( 'wc_sf_internal_regular_id', true ),
			);
		}

		$pdf = $order->get_meta( 'wc_sf_invoice_proforma', true );
		if ( $pdf ) {
			return array(
				'type'       => 'proforma',
				'pdf'        => $pdf,
				'invoice_id' => (int) $order->get_meta( 'wc_sf_internal_proforma_id', true ),
			);
		}

		return false;
	}



	/**
	 * Add company information to customer data in emails.
	 *
	 * @param WC_Order $order Order.
	 * @param boolean  $sent_to_admin True if sent to admin.
	 * @param boolean  $plain_text True if email is in plain text format.
	 */
	public function sf_invoice_business_data_email( $order, $sent_to_admin, $plain_text ) {

		if ( 'no' === get_option( 'woocommerce_sf_email_billing_details', 'no' ) ) {
			return;
		}

		if ( $this->wc_nastavenia_skcz_activated ) {
			$plugin  = Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ\Plugin::get_instance();
			$details = $plugin->get_customer_details( $order->get_id() );
			$ico     = $details->get_company_id();
			$ic_dph  = $details->get_company_vat_id();
			$dic     = $details->get_company_tax_id();
		} else {
			$ico    = $order->get_meta( 'billing_company_wi_id', true );
			$ic_dph = $order->get_meta( 'billing_company_wi_vat', true );
			$dic    = $order->get_meta( 'billing_company_wi_tax', true );
		}

		$result = '';

		if ( $ico ) {
			$result .= sprintf( '%s: %s<br>', __( 'ID #', 'woocommerce-superfaktura' ), $ico );
		}

		if ( $ic_dph ) {
			$result .= sprintf( '%s: %s<br>', __( 'VAT #', 'woocommerce-superfaktura' ), $ic_dph );
		}

		if ( $dic ) {
			$result .= sprintf( '%s: %s<br>', __( 'TAX ID #', 'woocommerce-superfaktura' ), $dic );
		}

		if ( $result ) {
			echo wp_kses( '<p>' . $result . '</p>', $this->allowed_tags );
		}
	}



	/**
	 * Add payment link to emails.
	 *
	 * @param WC_Order $order Order.
	 * @param boolean  $sent_to_admin True if sent to admin.
	 */
	public function sf_payment_link_email( $order, $sent_to_admin = false ) {

		if ( in_array( $order->get_status(), array( 'cancelled', 'refunded', 'failed' ), true ) ) {
			return;
		}

		$payment_link = $order->get_meta( 'wc_sf_payment_link', true );
		if ( ! $payment_link ) {
			return;
		}

		if ( 'yes' === get_option( 'woocommerce_sf_email_payment_link', 'yes' ) ) {
			echo wp_kses( '<h2>' . __( 'Online payment link', 'woocommerce-superfaktura' ) . '</h2>', $this->allowed_tags );
			echo wp_kses( '<p><a href="' . esc_url( $payment_link ) . '">' . esc_url( $payment_link ) . '</a></p>', $this->allowed_tags );
		}
	}



	/**
	 * Add invoice link to emails.
	 *
	 * @param WC_Order $order Order.
	 * @param boolean  $sent_to_admin True if sent to admin.
	 */
	public function sf_invoice_link_email( $order, $sent_to_admin = false ) {

		// Filter allows to cancel invoice link.
		$skip_link = apply_filters( 'sf_skip_email_link', false, $order );
		if ( $skip_link ) {
			return;
		}

		if ( in_array( $order->get_status(), array( 'cancelled', 'refunded', 'failed' ), true ) ) {
			return;
		}

		if ( 'completed' === $order->get_status() && 'yes' === get_option( 'woocommerce_sf_completed_email_skip_invoice', 'no' ) ) {
			return;
		}

		if ( 'cod' === $order->get_payment_method() && 'yes' === get_option( 'woocommerce_sf_cod_email_skip_invoice', 'no' ) ) {
			return;
		}

		$invoice_data = $this->get_invoice_data( $order->get_id() );
		if ( ! $invoice_data ) {
			return;
		}

		// Check if proforma was already paid.
		if ( 'proforma' === $invoice_data['type'] ) {
			$proforma = $this->sf_api()->invoice( $invoice_data['invoice_id'] );
			if ( isset( $proforma->Invoice ) && 1 != $proforma->Invoice->status ) {
				return;
			}
		}

		if ( 'yes' === get_option( 'woocommerce_sf_email_invoice_link', 'yes' ) ) {
			echo wp_kses( '<h2>' . ( ( 'regular' === $invoice_data['type'] ) ? __( 'Download invoice', 'woocommerce-superfaktura' ) : __( 'Download proforma invoice', 'woocommerce-superfaktura' ) ) . "</h2>\n\n", $this->allowed_tags );
			echo wp_kses( '<p><a href="' . esc_url( $invoice_data['pdf'] ) . '">' . $invoice_data['pdf'] . "</a></p>\n\n", $this->allowed_tags );

			// Mark invoice as sent only if email is sent to the customer.
			if ( ! empty( $invoice_data['invoice_id'] ) && ! $sent_to_admin ) {
				try {
					$this->sf_api()->markAsSent( $invoice_data['invoice_id'], $order->get_billing_email() );
				} catch ( Exception $e ) {
					// Do not report anything.
					return;
				}
			}
		}
	}



	/**
	 * Add invoice attachment to emails.
	 *
	 * @param array    $attachments Attachments.
	 * @param int      $email_id Email ID.
	 * @param WC_Order $order Order.
	 */
	public function sf_invoice_attachment_email( $attachments, $email_id, $order ) {

		// Filter allows to cancel pdf attachment.
		$skip_attachment = apply_filters( 'sf_skip_email_attachment', false, $order );
		if ( $skip_attachment ) {
			return;
		}

		if ( ! ( $order instanceof WC_Order ) ) {
			return $attachments;
		}

		if ( in_array( $order->get_status(), array( 'cancelled', 'refunded', 'failed' ), true ) ) {
			return $attachments;
		}

		if ( 'completed' === $order->get_status() && 'yes' === get_option( 'woocommerce_sf_completed_email_skip_invoice', 'no' ) ) {
			return $attachments;
		}

		if ( 'cod' === $order->get_payment_method() && 'yes' === get_option( 'woocommerce_sf_cod_email_skip_invoice', 'no' ) ) {
			return $attachments;
		}

		$invoice_data = $this->get_invoice_data( $order->get_id() );
		if ( ! $invoice_data ) {
			return $attachments;
		}

		// Check if proforma was already paid.
		if ( 'proforma' === $invoice_data['type'] ) {
			$proforma = $this->sf_api()->invoice( $invoice_data['invoice_id'] );
			if ( isset( $proforma->Invoice ) && 1 != $proforma->Invoice->status ) {
				return $attachments;
			}
		}

		if ( 'yes' === get_option( 'woocommerce_sf_invoice_pdf_attachment', 'no' ) ) {
			$pdf_resource = fopen( $invoice_data['pdf'], 'r' );
			if ( false === $pdf_resource ) {
				return $attachments;
			}

			$pdf_path = get_temp_dir() . $invoice_data['invoice_id'] . '.pdf';
			$pdf_path = str_replace( "\0", "", $pdf_path ); // Remove null bytes (error reported by users).
			file_put_contents( $pdf_path, $pdf_resource );
			$attachments[] = $pdf_path;

			// Mark invoice as sent only if email is sent to the customer and invoice wasn't marked as sent in "sf_invoice_link_email()" already.
			if ( ! empty( $invoice_data['invoice_id'] ) && 0 === strpos( $email_id, 'customer' ) && 'no' === get_option( 'woocommerce_sf_email_invoice_link', 'yes' ) ) {
				try {
					$this->sf_api()->markAsSent( $invoice_data['invoice_id'], $order->get_billing_email() );
				} catch ( Exception $e ) {
					// Do not report anything.
					return $attachments;
				}
			}
		}

		return $attachments;
	}



	/**
	 * Get available order statuses.
	 */
	private function get_order_statuses() {
		if ( function_exists( 'wc_order_status_manager_get_order_status_posts' ) ) {
			$wc_order_statuses = array_reduce(
				wc_order_status_manager_get_order_status_posts(),
				function( $result, $item ) {
					$result[ $item->post_name ] = $item->post_title;
					return $result;
				},
				array()
			);

			return $wc_order_statuses;
		}

		if ( function_exists( 'wc_get_order_statuses' ) ) {
			$wc_get_order_statuses = wc_get_order_statuses();

			return $this->alter_wc_statuses( $wc_get_order_statuses );
		}

		$order_status_terms = get_terms( 'shop_order_status', 'hide_empty=0' );

		$shop_order_statuses = array();
		if ( ! is_wp_error( $order_status_terms ) ) {
			foreach ( $order_status_terms as $term ) {
				$shop_order_statuses[ $term->slug ] = $term->name;
			}
		}

		return $shop_order_statuses;
	}



	/**
	 * Modify order statuses.
	 *
	 * @param array $array Order statuses.
	 */
	private function alter_wc_statuses( $array ) {
		$new_array = array();
		foreach ( $array as $key => $value ) {
			$new_array[ substr( $key, 3 ) ] = $value;
		}

		return $new_array;
	}



	/**
	 * Check if order is paid.
	 *
	 * @param WC_Order $order Order.
	 */
	private function order_is_paid( $order ) {

		$is_paid              = false;
		$set_as_paid_statuses = get_option( 'woocommerce_sf_invoice_set_as_paid_statuses', false );

		if ( class_exists( 'WC_Order_Status_Manager_Order_Status' ) ) {

			// Compatibility with WooCommerce Order Status Manager plugin.
			$order_status = new WC_Order_Status_Manager_Order_Status( $order->get_status() );

			if ( false !== $set_as_paid_statuses ) {
				if ( in_array( $order_status->get_slug(), $set_as_paid_statuses, true ) || $order_status->is_paid() ) {
					$is_paid = true;
				}
			} else {

				// Backward compatibility with previous options woocommerce_sf_invoice_regular_processing_set_as_paid and woocommerce_sf_invoice_regular_dont_set_as_paid.
				switch ( $order_status->get_slug() ) {

					case 'processing':
						if ( 'yes' === get_option( 'woocommerce_sf_invoice_regular_processing_set_as_paid', 'no' ) ) {
							$is_paid = true;
						}
						break;

					case 'completed':
						if ( 'no' === get_option( 'woocommerce_sf_invoice_regular_dont_set_as_paid', 'no' ) ) {
							$is_paid = true;
						}
						break;

					default:
						$is_paid = $order_status->is_paid();
						break;
				}
			}
		} else {

			// Default WooCommerce order statuses.
			if ( false !== $set_as_paid_statuses ) {
				if ( in_array( $order->get_status(), $set_as_paid_statuses, true ) ) {
					$is_paid = true;
				}
			} else {

				// Backward compatibility with previous options woocommerce_sf_invoice_regular_processing_set_as_paid and woocommerce_sf_invoice_regular_dont_set_as_paid.
				switch ( $order->get_status() ) {

					case 'processing':
						if ( 'yes' === get_option( 'woocommerce_sf_invoice_regular_processing_set_as_paid', 'no' ) ) {
							$is_paid = true;
						}
						break;

					case 'completed':
						if ( 'no' === get_option( 'woocommerce_sf_invoice_regular_dont_set_as_paid', 'no' ) ) {
							$is_paid = true;
						}
						break;
				}
			}
		}

		return apply_filters( 'woocommerce_sf_order_is_paid', $is_paid, $order );
	}



	/**
	 * Convert string to plain text.
	 *
	 * @param string $string String.
	 */
	private function convert_to_plaintext( $string ) {
		return html_entity_decode( wp_strip_all_tags( $string ), ENT_QUOTES, get_option( 'blog_charset' ) );
	}



	/**
	 * Add custom order status actions.
	 *
	 * @param array    $actions Actions.
	 * @param WC_Order $order Order.
	 */
	public function add_custom_order_status_actions_button( $actions, $order ) {
		if ( 'yes' === get_option( 'woocommerce_sf_invoice_download_button_actions', false ) ) {
			$action_slug = 'invoice';

			$pdf = $order->get_meta( 'wc_sf_invoice_proforma', true );
			if ( $pdf ) {
				$actions[ $action_slug ] = array(
					'url'    => $pdf,
					'name'   => __( 'Proforma', 'woocommerce-superfaktura' ),
					'action' => $action_slug,
				);
			}
			$pdf = $order->get_meta( 'wc_sf_invoice_regular', true );
			if ( $pdf ) {
				$actions[ $action_slug ] = array(
					'url'    => $pdf,
					'name'   => __( 'Invoice', 'woocommerce-superfaktura' ),
					'action' => $action_slug,
				);
			}
		}

		return $actions;
	}



	/**
	 * Add custom css for admin.
	 */
	public function add_admin_css() {
		echo wp_kses(
			'
				<style>
				.wc-action-button-invoice::after {
					font-family: woocommerce !important;
					content: "\e00a" !important;
				}
				p.description .button {
					font-style: normal;
				}
				.wc-sf-api-test-loading,
				.wc-sf-api-test-ok,
				.wc-sf-api-test-fail {
					display: none;
					vertical-align: middle;
				}
				table.wc-sf-api-log {
					border-spacing: 0;
				}
				table.wc-sf-api-log th,
				table.wc-sf-api-log td {
					margin: 0;
					padding: 6px 12px;
					text-align: left;
				}
				table.wc-sf-api-log tr.odd td {
					background: #fff;
				}
				table.wc-sf-api-log tr.error td {
					color: #f00;
				}

				#woocommerce_wi_invoice_creation1-description + .form-table tr:nth-child(odd) th,
				#woocommerce_wi_invoice_creation1-description + .form-table tr:nth-child(odd) td {
					padding-bottom: 0;
				}
				#woocommerce_wi_invoice_creation1-description + .form-table tr:nth-child(even) th,
				#woocommerce_wi_invoice_creation1-description + .form-table tr:nth-child(even) td {
					padding-top: 0;
				}

				.sf-sandbox-notice {
					margin: 24px 0;
					padding: 12px 24px;
					color: #fff;
					background: #f00;
				}

				.wc-sf-url-error,
				.wc-sf-url-error a {
					color: #f00;
				}
				</style>
			',
			$this->allowed_tags
		);
	}



	/**
	 * Test SuperFaktura API connection.
	 */
	public function wc_sf_api_test() {

		$api = $this->sf_api(
			array(
				'woocommerce_sf_lang'       => sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_lang'] ?? '' ) ),
				'woocommerce_sf_email'      => sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_email'] ?? '' ) ),
				'woocommerce_sf_apikey'     => sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_apikey'] ?? '' ) ),
				'woocommerce_sf_company_id' => sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_company_id'] ?? '' ) ),
				'woocommerce_sf_sandbox'    => sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_sandbox'] ?? '' ) ),
			)
		);

		$result = $api->getSequences();

		if ( empty( $result ) ) {
			$error = $api->getLastError();
			echo wp_kses( $error['message'], $this->allowed_tags );
		} else {
			echo 'OK';
		}

		wp_die();
	}



	/**
	 * Get URL status.
	 */
	public function wc_sf_url_check() {
		if ( ! check_ajax_referer( 'ajax_validation', 'security' ) ) {
			wp_die();
		}

		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_die();
		}

		$result = wp_safe_remote_get( $_POST['url'] );
		if ( is_wp_error( $result ) ) {
			echo 'ERROR';
		}
		else {
			echo $result['response']['code'] ?? 'ERROR';
		}

		wp_die();
	}
}
