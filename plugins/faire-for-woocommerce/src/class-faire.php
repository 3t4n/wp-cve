<?php
/**
 * Installation related functions and actions.
 *
 * @package  Faire for WooCommerce
 */

namespace Faire\Wc;

use Faire\Wc\Admin\Main;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Order_Api;
use Faire\Wc\Sync\Sync_Order;
use Faire\Wc\Sync\Sync_Product;

/**
 * Main Plugin Class. This will be the singleton instance
 */
final class Faire {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = VERSION;

	/**
	 * The single instance of the class.
	 *
	 * @var Faire|null
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * If the class was initialized already.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static bool $initialized = false;

	/**
	 * Main Plugin Instance.
	 *
	 * Ensures only one instance of Plugin is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Faire - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->initialize_plugin();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'faire-for-woocommerce' ), '1.0.0' );
	}

	/**
	 * Deserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheating&#8217; huh?', 'faire-for-woocommerce' ), '1.0.0' );
	}

	/**
	 * Initializer.
	 *
	 * @return void
	 */
	public function initialize_plugin() {
		// Ensure plugin can only be activated if WooCommerce is active.
		if ( ! $this->is_woocommerce_activated() ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			deactivate_plugins( plugin_basename( FAIRE_WC_PLUGIN_FILE ) );
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
			add_action( 'admin_notices', array( $this, 'notice_wc_required' ) );
			return;
		}

		if ( self::$initialized ) {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Only a single instance of this class is allowed. Use singleton.', 'faire-for-woocommerce' ), '1.0.0' );
			return;
		}

		self::$initialized = true;

		$this->includes();
		$this->init_hooks();

		do_action( 'faire_for_woocommerce_loaded' );
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( string $type ): bool {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
		return false;
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @return void
	 */
	private function includes() {
		register_activation_hook(
			FAIRE_WC_PLUGIN_FILE,
			array( Install::class, 'on_plugin_activation' )
		);
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @return void
	 *
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Init Plugin when WordPress Initialises.
	 *
	 * @return void
	 */
	public function init() {
		// Before init action.
		do_action( 'before_faire_for_woocommerce_init' );

		// Actions to run after WooCommerce CPTs are setup.
		add_action(
			'woocommerce_after_register_post_type',
			array( $this, 'woocommerce_after_register_post_type' )
		);

		$this->register_custom_order_statuses();
		// Adds custom order statuses to allow mapping to Faire orders statuses.
		add_filter( 'wc_order_statuses', array( $this, 'add_custom_order_statuses' ) );

		if ( $this->is_request( 'admin' ) ) {
			new Main();
		}

		new Sync_Product();

		// Initializes the orders syncing.
		new Sync_Order( new Order_Api(), new Settings() );

		// Set up localization.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'faire_for_woocommerce_init' );
	}

	/**
	 * Actions to run after WooCommerce CPTs are setup.
	 */
	public function woocommerce_after_register_post_type() {
		// Extends WC_Order_Query to allow retrieving orders by Faire order ID.
		add_filter(
			'woocommerce_order_data_store_cpt_get_orders_query',
			array( $this, 'handle_orders_custom_query_var' ),
			10,
			2
		);

		// Extends WC_Product_Query to allow retrieving orders by Faire product ID
		// and Faire variant ID.
		add_filter(
			'woocommerce_product_data_store_cpt_get_products_query',
			array( $this, 'handle_products_custom_query_var' ),
			10,
			2
		);

		// Extends WC_Product_Query to allow retrieving products that have Faire product IDs.
		add_filter(
			'woocommerce_product_data_store_cpt_get_products_query',
			array( $this, 'handle_products_faire_meta_exists_custom_query_var' ),
			11,
			2
		);
	}

	/**
	 * Registers custom order statuses.
	 */
	public function register_custom_order_statuses() {
		// phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment
		$custom_statuses = array(
			'new'         => array(
				'label'       => __( 'Faire New', 'faire-for-woocommerce' ),
				'label_count' => _n_noop(
					'Faire new <span class="count">(%s)</span>',
					'Faire new <span class="count">(%s)</span>',
					'faire-for-woocommerce'
				),
			),
			'backordered' => array(
				'label'       => __( 'Faire Backordered', 'woocommerce' ),
				'label_count' => _n_noop(
					'Faire backordered <span class="count">(%s)</span>',
					'Faire backordered <span class="count">(%s)</span>',
					'faire-for-woocommerce'
				),
			),
		);

		foreach ( $custom_statuses as $key => $value ) {
			register_post_status(
				'wc-faire-' . $key,
				array(
					'label'                     => $value['label'],
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => $value['label_count'],
				)
			);
			// phpcs:enable WordPress.WP.I18n.MissingTranslatorsComment
		}
	}

	/**
	 * Adds custom order statuses to allow mapping to Faire orders statuses.
	 *
	 * @param array $order_statuses Valid order statuses.
	 *
	 * @return array Updated list of order statuses.
	 */
	public function add_custom_order_statuses( array $order_statuses ): array {
		$order_statuses['wc-faire-new']         = _x( 'Faire new', 'Order status', 'faire-for-woocommerce' );
		$order_statuses['wc-faire-backordered'] = _x( 'Faire backordered', 'Order status', 'faire-for-woocommerce' );

		return $order_statuses;
	}

	/**
	 * Handles a custom 'faire_order_id' query var to get orders.
	 *
	 * See https://bit.ly/3M3XuEw
	 *
	 * @param array $query Args for WP_Query.
	 * @param array $query_vars Query vars from WC_Order_Query.
	 *
	 * @return array modified $query
	 */
	public function handle_orders_custom_query_var(
		array $query,
		array $query_vars
	): array {
		$custom_var = '_faire_order_id';
		return $this->add_custom_query_vars( $custom_var, $query, $query_vars );
	}

	/**
	 * Handles custom query vars to get products.
	 *
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Product_Query.
	 *
	 * @return array modified $query
	 */
	public function handle_products_custom_query_var(
		array $query,
		array $query_vars
	): array {
		$settings    = new Settings();
		$custom_vars = array(
			$settings->get_meta_faire_product_id(),
			$settings->get_meta_faire_variant_id(),
		);
		foreach ( $custom_vars as $custom_var ) {
			$query = $this->add_custom_query_vars( $custom_var, $query, $query_vars );
		}

		return $query;
	}

	/**
	 * Adds custom vars to the current query.
	 *
	 * @param string $custom_var Custom var to add.
	 * @param array  $query      Args for WP_Query.
	 * @param array  $query_vars Query vars from WC_Product_Query.
	 *
	 * @return array modified $query
	 */
	private function add_custom_query_vars(
		string $custom_var,
		array $query,
		array $query_vars
	): array {
		if ( ! empty( $query_vars[ $custom_var ] ) ) {
			$query['meta_query'][] = array(
				'key'   => $custom_var,
				'value' => esc_attr( $query_vars[ $custom_var ] ),
			);
		}

		return $query;
	}

	/**
	 * Handles custom query vars to get products.
	 *
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Product_Query.
	 *
	 * @return array modified $query
	 */
	public function handle_products_faire_meta_exists_custom_query_var(
		array $query,
		array $query_vars
	): array {
		$settings = new Settings();

		if ( isset( $query_vars['_faire_id_exists'] ) && '' !== $query_vars['_faire_id_exists'] ) {
			$query['meta_query'][] = array(
				'key'     => $settings->get_meta_faire_product_id(),
				'value'   => '',
				'compare' => '!=',
			);
		}

		return $query;
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/faire-for-woocommerce/faire-for-woocommerce-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/faire-for-woocommerce-LOCALE.mo
	 *
	 * @return void
	 */
	private function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'faire-for-woocommerce' );

		load_textdomain( 'faire-for-woocommerce', WP_LANG_DIR . '/faire-for-woocommerce/faire-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'faire-for-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Check if WooCommerce is activated
	 *
	 * @return True if WooCommerce is activated.
	 */
	private function is_woocommerce_activated(): bool {
		return in_array(
			'woocommerce/woocommerce.php',
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
			true
		);
	}

	/**
	 * Admin error notifying user that WC is required
	 */
	public function notice_wc_required() {
		?>
		<div class="error">
			<p><?php echo esc_html__( 'Faire for WooCommerce requires WooCommerce to be installed and activated!', 'faire-for-woocommerce' ); ?></p>
		</div>
		<?php
	}
}
