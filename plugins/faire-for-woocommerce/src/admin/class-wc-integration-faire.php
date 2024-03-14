<?php
/**
 * Integration Demo Integration.
 *
 * @package  Wc_Integration_Faire
 * @category Integration
 * @author   Faire
 */

namespace Faire\Wc\Admin;

use Exception;
use Faire\Wc\Api\Faire_Api;
use Faire\Wc\Api\Order_Api;
use Faire\Wc\Api\Product_Api;
use Faire\Wc\Sync\Sync_Order;
use Faire\Wc\Sync\Sync_Taxonomy;
use Faire\Wc\Sync\Sync_Brand;
use Faire\Wc\Sync\Sync_Product_Linking;
use Faire\Wc\Sync\Sync_Product_Unlinking;
use Faire\Wc\Sync\Sync_Order_Status;
use Faire\Wc\Admin\Settings;

class Wc_Integration_Faire extends \WC_Integration {

	/**
	 *  Instance of Faire\Wc\Admin\Settings class.
	 *
	 * @var Settings
	 */
	protected $plugin_settings;

	/**
	 *  Instance of Faire\Wc\Admin\Faire_Api class.
	 *
	 * @var Faire_Api
	 */
	protected $api;

	/**
	 * Minimum width for products images.
	 *
	 * @var int
	 */
	const IMAGE_MIN_WIDTH = 1050;

	/**
	 * Minimum height for products images.
	 *
	 * @var int
	 */
	const IMAGE_MIN_HEIGHT = 1050;

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id           = 'faire_wc_integration';
		$this->method_title = __( 'Faire', 'faire-for-woocommerce' );


		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Load plugin Settings getter setter functions.
		$this->plugin_settings = new Settings();

		$this->api = new Faire_Api();

		$this->method_description = '<div class="options-description"><p>' . ( ! $this->is_sync_enabled() ? sprintf(
			// translators: link markup.
			esc_html__( 'To set up your integration, first add your API key and then set up your product sync settings. If you have existing products on Faire, %1$slink your Faire products%2$s before syncing.', 'faire-for-woocommerce' ),
			'<a class="faire-admin-section-link-js" href="#product_linking" data-section="product_linking">',
			'</a>',
		) : '' ) . '</p></div>';

		// Actions.
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'faire_process_admin_options' ) );

		// Filters.
		add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings' ) );

		// Ensures inventory sync is disabled if WooCommerce stock management is.
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'manage_inventory_sync_settings' ), 10, 2 );

		// Handles the Ajax call to test the API connection.
		add_action( 'wp_ajax_faire_test_api_connection', array( $this, 'ajax_test_api_connection' ) );

		// Handles the Ajax call to sync orders.
		add_action(
			'wp_ajax_faire_orders_manual_sync',
			array(
				new Sync_Order( new Order_Api(), $this->plugin_settings ),
				'ajax_orders_manual_sync',
			)
		);

		// Handles the Ajax call to cancel sync orders.
		add_action(
			'wp_ajax_faire_cancel_orders_manual_sync',
			array(
				new Sync_Order( new Order_Api(), $this->plugin_settings ),
				'ajax_cancel_orders_manual_sync',
			)
		);

		add_filter( 'heartbeat_received', array( $this, 'manual_sync_status' ), 10, 2 );

		// Handles the Ajax call to sync taxonomy.
		add_action(
			'wp_ajax_faire_product_taxonomy_manual_sync',
			array(
				new Sync_Taxonomy( new Product_Api(), $this->plugin_settings ),
				'ajax_taxonomy_manual_sync',
			)
		);

		// Handles the Ajax call to sync the brand.
		add_action(
			'wp_ajax_faire_brand_manual_sync',
			array(
				new Sync_Brand( $this->api, $this->plugin_settings ),
				'ajax_brand_manual_sync',
			)
		);

		// Handles the Ajax call to link products.
		add_action(
			'wp_ajax_faire_product_linking_manual_sync',
			array(
				new Sync_Product_Linking( new Product_Api(), $this->plugin_settings ),
				'ajax_product_linking_manual_sync',
			)
		);

		// Handles the Ajax call to unlink products.
		add_action(
			'wp_ajax_faire_product_unlinking_manual_sync',
			array(
				new Sync_Product_Unlinking( $this->plugin_settings ),
				'ajax_product_unlinking_sync',
			)
		);

		// Handles download of csv from settings page.
		if ( is_admin() && ( isset( $_GET['wc_faire_link_products_csv'] ) || isset( $_GET['wc_faire_link_variations_csv'] ) ) ) {
			$product_linking = new Sync_Product_Linking( new Product_Api(), $this->plugin_settings );
			if ( isset( $_GET['wc_faire_link_products_csv'] ) ) {
				$product_linking->download_faire_create_csv( 'products' );
			}
			if ( isset( $_GET['wc_faire_link_variations_csv'] ) ) {
				$product_linking->download_faire_create_csv( 'variations' );
			}
		}

		if ( isset( $_GET['forcecheck'] ) ) {
			$this->maybe_run_initial_setup_sync();
		}
	}

	/**
	 * Output the admin options table.
	 */
	public function admin_options() {
		self::maybe_disable_fields();
		echo '<h2>' . esc_html__( 'Faire integration settings', 'faire-for-woocommerce' ) . '</h2>';
		if ( empty( $_POST ) ) { // On settings submit, display errors during process_admin_options().
			$this->display_errors();
		}
		echo wp_kses_post( $this->get_method_description() );

		if ( ! wp_script_is( 'heartbeat' ) ) {
			?>
			<div class="faire-admin-notice notice-warning notice is-dismissible">
				<p>
					<?php
					echo sprintf(
						// translators: <a> tag open, </a> tag close.
						esc_html__( '%1$sHeartbeat%2$s is disabled, Product and Order sync progress notifications won\'t be automatically updated.', 'faire-for-woocommerce' ),
						'<a href="https://developer.wordpress.org/plugins/javascript/heartbeat-api/" target="_blank">',
						'</a>'
					);
					?>
				</p>
			</div>
			<?php
		}

		echo '<div><input type="hidden" name="section" value="' . esc_attr( $this->id ) . '" /></div>';
		echo '<div id="faire-options">';
		echo '<table class="form-table">' . $this->generate_settings_html( $this->get_form_fields(), false ) . '</table>';
		echo '</div><div class="submit-placeholder"></div></div></div>';
	}

	/**
	 * Checks if the shop country is in the EU.
	 *
	 * @return bool True if the shop country is in the EU.
	 */
	public function shop_country_in_eu(): bool {
		return in_array(
			WC()->countries->get_base_country(),
			WC()->countries->get_european_union_countries(),
			true
		);
	}

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$site_title                     = get_bloginfo( 'name' );
		$woocommerce_logs_url           = admin_url( 'admin.php?page=wc-status&tab=logs' );
		$faire_pricing_policy_url       = defined( 'FAIRE_PRICING_POLICY_URL' )
			? FAIRE_PRICING_POLICY_URL
			: 'https://www.faire-stage.com/support/articles/360019040531';
		$faire_pricing_policy_statement = $this->shop_country_in_eu()
			? sprintf(
				// translators: %1$, %2$ link to pricing policy.
				__( 'To comply with %1$sFaire\'s pricing policy%2$s, your wholesale prices must be the same across all sales channels.', 'faire-for-woocommerce' ),
				'<a href="' . $faire_pricing_policy_url . '" target = "_blank">',
				'</a>'
			)
			: sprintf(
				// translators: %1$, %2$ link to pricing policy.
				__( 'To comply with %1$sFaire\'s pricing policy%2$s, your wholesale and retail prices must be the same across all sales channels.', 'faire-for-woocommerce' ),
				'<a href="' . $faire_pricing_policy_url . '" target = "_blank">',
				'</a>'
			);

		$this->form_fields = array(
			// Faire API related settings.
			'menu'                               => array(
				'type' => 'menu',
			),
			// Brand related settings.
			'api'                                => array(
				'title' => __( 'Faire API and account', 'faire-for-woocommerce' ),
				'type'  => 'title',
				'flag'  => 'first',
			),
			'api_key'                            => array(
				'title'       => __( 'API Key', 'faire-for-woocommerce' ),
				'type'        => 'api_key',
				'placeholder' => 'Paste API key here',
				'description' => sprintf(
					// translators: %1$, %2$ link.
					__( 'Enter the API key you received from Faire. %1$sFind the API key on Faire%2$s by visiting your brand portal.', 'faire-for-woocommerce' ),
					'<a href="#" target="_blank">',
					'</a>'
				),
				'desc_tip'    => false,
				'default'     => '',
			),
			'api_mode'                           => array(
				'title'       => __( 'API Mode', 'faire-for-woocommerce' ),
				'type'        => 'select',
				'options'     => array(
					'production' => __( 'Production', 'faire-for-woocommerce' ),
					'staging'    => __( 'Staging', 'faire-for-woocommerce' ),
				),
				'description' => __( 'Switch to Staging if you\'d like to use a testing environment.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
				'default'     => 'production',
			),
			'initial_setup_products_exist'       => array(
				'label'       => __( 'Existing products found.', 'faire-for-woocommerce' ),
				'type'        => 'hidden',
				'description' => '',
			),
			'initial_setup'                      => array(
				'label'       => __( 'Save & Finish Setup', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'description' => 'Save the API key and perform an initial sync of the Brand and Taxonomy types.',
				'class'       => 'button-primary',
			),
			'debug'                              => array(
				'title'       => __( 'Event Log', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'faire-for-woocommerce' ),
				'default'     => 'yes',
				'description' => str_replace(
					'\n',
					'<br />',
					sprintf(
						// translators: %1$s link start %2$s link end.
						__( 'Check this to log events such as API requests and responses. These events can be used in case of technical issues and can be found in %1$sWooCommerce > Status > Logs%2$s.', 'faire-for-woocommerce' ),
						'<a href="' . $woocommerce_logs_url . '" target = "_blank">',
						'</a>'
					)
				),
			),
			'test_api_connection'                => array(
				'title'    => __( 'Test connection to the Faire API', 'faire-for-woocommerce' ),
				'label'    => __( 'Test connection', 'faire-for-woocommerce' ),
				'type'     => 'button',
				'desc_tip' => false,
				'class'    => 'button-secondary button-inline',
			),

			// Brand related settings.
			'brand_lang_currency'                => array(
				'title'       => 'Shop language and currency',
				'type'        => 'table',
				'desc_tip'    => false,
				'description' => 'Pasting your API key above will automatically sync your language and currency from Faire. Please note that the language and currency need to match for the integration to work.',
			),

			// Brand related settings.
			'brand_locale'                       => array(
				'title'             => 'Locale',
				'type'              => 'hidden',
				'description'       => '',
				'default'           => '',
				'class'             => 'disabled',
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
			),
			'brand_currency'                     => array(
				'title'             => 'Currency',
				'type'              => 'hidden',
				'description'       => '',
				'class'             => 'disabled',
				'default'           => '',
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
			),
			// 'brand_sync_manual'                     => array(
			// 'label'       => __( 'Brand Sync', 'faire-for-woocommerce' ),
			// 'type'        => 'button',
			// 'desc_tip'    => false,
			// 'description' => 'Get the brand profile from Faire.',
			// 'class'       => 'button-secondary',
			// ),

			'product_sync'                       => array(
				'title'       => __( 'Product sync', 'faire-for-woocommerce' ),
				'type'        => 'title',
				'description' => sprintf(
					// translators: link.
					__( 'If you already have products on Faire, skip this section and use %1$slink existing Faire products%2$s to merge your catalog.', 'faire-for-woocommerce' ),
					'<a class="faire-admin-section-link-js" href="#product_linking" data-section="product_linking">',
					'</a>',
				),
			),
			'product_sync_mode'                  => array(
				'title'       => __( 'Product sync mode', 'faire-for-woocommerce' ),
				'type'        => 'select',
				'desc_tip'    => false,
				'options'     => array(
					'do_not_sync'    => __( 'Sync products manually', 'faire-for-woocommerce' ),
					'sync_scheduled' => __( 'Sync products automatically', 'faire-for-woocommerce' ),
				),
				'class'       => 'wc-enhanced-select',
				'default'     => 'do_not_sync',
				'description' => __( 'You can set your products to automatically sync on a set schedule or do it manually.', 'faire-for-woocommerce' ),
			),
			'product_sync_schedule'              => array(
				'title'   => __( 'Automatic sync schedule:', 'faire-for-woocommerce' ),
				'type'    => 'sync_schedule',
				'default' => 'daily',
			),
			'product_sync_schedule_num'          => array(
				'type' => 'none',
			),
			'product_sync_schedule_time'         => array(
				'type' => 'none',
			),
			'inventory_sync_on_change'           => array(
				'title'       => __( 'Update stock with product sync', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Yes, sync every stock update', 'faire-for-woocommerce' ),
				'default'     => 'yes',
				'description' => __( 'Check this option to sync product details on every stock update.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'product_pricing_policy'             => array(
				'title'       => __( 'Pricing', 'faire-for-woocommerce' ),
				'type'        => 'radio',
				'description' => __( 'Do you use retail or wholesale pricing on WooCommerce?', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
				'options'     => array(
					'wholesale_percentage' => __( 'Retail', 'faire-for-woocommerce' ),
					'wholesale_multiplier' => __( 'Wholesale', 'faire-for-woocommerce' ),
				),
				'default'     => 'wholesale_percentage',
			),
			'product_wholesale_multiplier'       => array(
				'title'             => '',
				'type'              => 'number',
				'default'           => '1.25',
				'description'       => $faire_pricing_policy_statement,
				'desc_tip'          => false,
				'custom_attributes' => array(
					'min'  => '1.25',
					'max'  => '10',
					'step' => '0.05',
				),
				'class'             => 'auto-width',
				'section_class'     => 'mt-none',
			),
			'product_wholesale_percentage'       => array(
				'title'             => '',
				'type'              => 'number',
				'default'           => '80',
				'description'       => $faire_pricing_policy_statement,
				'desc_tip'          => false,
				'custom_attributes' => array(
					'min' => '10',
					'max' => '80',
				),
				'class'             => 'auto-width',
				'section_class'     => 'mt-none',
			),
			'product_sync_exclude_fields'        => array(
				'title'       => __( 'Exclude these Faire-specific fields from syncing', 'faire-for-woocommerce' ),
				'type'        => 'multiselect',
				'description' => __( 'Fields listed here won\'t be overwritten on Faire, and changes made on Faire won\'t carry over. To complete these fields on WooCommerce, delete them here, add the info on WooCommerce, and then sync.', 'faire-for-woocommerce' ),
				'options'     => array(
					'product.name'                   => __( 'Product Name', 'faire-for-woocommerce' ),
					// 'product.short_description' => __( 'Product Short Description', 'faire-for-woocommerce' ),
					'product.description'            => __( 'Product Description', 'faire-for-woocommerce' ),
					'product.lifecycle_state'        => __( 'Product & Variant Lifecycle State', 'faire-for-woocommerce' ),
					'product.images'                 => __( 'Product Images', 'faire-for-woocommerce' ),
					'product.taxonomy_type'          => __( 'Product Taxonomy Type', 'faire-for-woocommerce' ),
					'product.allow_sales_when_out_of_stock' => __( 'Allow Sales When Out Of Stock', 'faire-for-woocommerce' ),
					'product.preorder_fields'        => __( 'Product Preorder fields', 'faire-for-woocommerce' ),
					'product.unit_multiplier'        => __( 'Case quantity', 'faire-for-woocommerce' ),
					'product.minimum_order_quantity' => __( 'Minimum order quantity', 'faire-for-woocommerce' ),
					'product.per_style_minimum_order_quantity' => __( 'Per style minimum order quantity', 'faire-for-woocommerce' ),
					'variant.available_quantity'     => __( 'Stock quantity', 'faire-for-woocommerce' ),
					'variant.sku'                    => __( 'Variant SKU', 'faire-for-woocommerce' ),
					'variant.tariff_code'            => __( 'Variant Tariff Code', 'faire-for-woocommerce' ),
					'variant.prices'                 => __( 'Retail & Wholesale Prices', 'faire-for-woocommerce' ),
					'variant.measurements'           => __( 'Measurements', 'faire-for-woocommerce' ),
				),
				'class'       => 'wc-enhanced-select wc-enhanced-select-wide',
				'default'     => array(
					'product.allow_sales_when_out_of_stock',
					'product.preorder_fields',
					'variant.sku',
					'variant.tariff_code',
				),
			),
			'product_taxonomy_sync_manual'       => array(
				'title'       => '',
				'label'       => '',
				'type'        => 'simple',
				'description' => sprintf(
					// translators: link markup open and close.
					__( '%1$sSync new taxonomy types%2$s or changes that have happened on Faire. This will update your product types on WooCommerce to match product types chosen on Faire. ', 'faire-for-woocommerce' ),
					'<a id="' . $this->plugin_id . $this->id . '_product_taxonomy_sync_manual" href="#">',
					'</a>'
				),
				'desc_tip'    => false,
			),
			'product_sync_manual'                => array(
				'title'       => __( 'Manual product sync', 'faire-for-woocommerce' ),
				'label'       => __( 'Sync products now', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'class'       => 'button-primary button-inline',
				'description' => __( 'Sync your products from WooCommerce to Faire once you\'ve adjusted the settings above.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'product_sync_results'               => array(
				'title'             => __( 'Last sync details', 'faire-for-woocommerce' ),
				'type'              => 'textarea',
				'description'       => __( 'Each time products are synced, the results are displayed here. Go to Faire to review your products.', 'faire-for-woocommerce' ),
				'class'             => 'disabled',
				'css'               => 'min-height:200px;min-width:min(400px,80%);width:min(60ch,100%);resize:both;overflow-y:auto;',
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
			),
			// Orders and Inventory related settings.
			'order_sync'                         => array(
				'title' => __( 'Orders and inventory sync', 'faire-for-woocommerce' ),
				'type'  => 'title',
				// 'description' => __( 'Set order and inventory related settings when syncing from Faire.', 'faire-for-woocommerce' ),
			),
			'order_sync_mode'                    => array(
				'title'       => __( 'Order sync mode', 'faire-for-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'You can set your orders to automatically sync on a set schedule or do it manually.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
				'options'     => array(
					'do_not_sync'    => __( 'Sync orders manually', 'faire-for-woocommerce' ),
					'sync_scheduled' => __( 'Sync orders automatically', 'faire-for-woocommerce' ),
				),
				'class'       => 'wc-enhanced-select',
				'default'     => 'do_not_sync',
			),
			'order_sync_schedule'                => array(
				'title'   => __( 'Automatic sync schedule:', 'faire-for-woocommerce' ),
				'type'    => 'sync_schedule',
				'default' => 'hours',
			),
			'order_sync_schedule_num'            => array(
				'type' => 'none',
			),
			'order_sync_schedule_time'           => array(
				'type' => 'none',
			),
			'order_sync_skip_orders_create'      => array(
				'title'       => __( 'Create orders automatically', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Yes, create orders automatically', 'faire-for-woocommerce' ),
				'default'     => 'no',
				'desc_tip'    => false,
				'description' => __( 'Check to automatically sync Faire orders to WooCommerce. Your inventory will be updated either way.', 'faire-for-woocommerce' ),
			),
			'inventory_sync_on_add_to_cart'      => array(
				'title'       => __( 'Sync inventory on Add to Cart', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Yes, sync inventory on Add to Cart', 'faire-for-woocommerce' ),
				'default'     => 'no',
				'description' => __( 'Check to sync your inventory when a retailer adds an item to their cart.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'order_sync_manual'                  => array(
				'title'       => __( 'Manual order sync', 'faire-for-woocommerce' ),
				'label'       => __( 'Sync orders now', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'class'       => 'button-primary button-inline',
				'description' => __( 'Sync your orders from Faire to WooCommerce.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'cancel_order_sync_manual'           => array(
				'label'       => __( 'Cancel orders sync process', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'class'       => 'button button-cancel button-inline',
				'description' => __( 'Cancels orders and inventory sync.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'order_sync_results'                 => array(
				'title'             => __( 'Last sync details', 'faire-for-woocommerce' ),
				'type'              => 'textarea',
				'description'       => sprintf(
					// translators: link markup open and close.
					__( 'Each time orders are synced, the results are displayed here. %1$sView your orders%2$s.', 'faire-for-woocommerce' ),
					'<a href="' . admin_url( 'edit.php?post_type=shop_order' ) . '">',
					'</a>'
				),
				'class'             => 'disabled',
				'css'               => 'min-height:200px;min-width:min(400px,80%);width:min(60ch,100%);resize:both;overflow-y:auto;',
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
			),
			// Faire product taxonomy.
			// 'product_taxonomy'                => array(
			// 'title'       => __( 'Product type', 'faire-for-woocommerce' ),
			// 'type'        => 'title',
			// 'description' => __( 'Faire Product type related settings and actions.', 'faire-for-woocommerce' ),
			// ),

			// Faire product linking sync.
			'product_linking'                    => array(
				'title'       => __( 'Link Faire products', 'faire-for-woocommerce' ),
				'type'        => 'title',
				'description' => __( 'Link to connect existing Faire products to WooCommerce products without importing them again. Product linking is important as it allows order, inventory, and product syncing to happen on a scheduled basis.', 'faire-for-woocommerce' ),
			),
			'product_linking_sync_manual'        => array(
				'title'       => __( 'Link products', 'faire-for-woocommerce' ),
				'label'       => __( 'Link products', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'class'       => 'button-primary',
				'description' => sprintf(
					// translators: bold markup.
					__( 'Link your Faire products and WooCommerce products. All products in Faire and WooCommerce %1$smust have a SKU associated with the product variants.%2$s', 'faire-for-woocommerce' ),
					'<b>',
					'</b>'
				),
				'desc_tip'    => false,
			),
			'product_linking_sync_results'       => array(
				'title'             => __( 'Last link details', 'faire-for-woocommerce' ),
				'type'              => 'textarea',
				'description'       => __( 'Each time products are linked, the results are displayed here.', 'faire-for-woocommerce' ),
				'class'             => 'disabled',
				'css'               => 'min-height:200px;min-width:min(400px,80%);width:min(60ch,100%);resize:both;overflow-y:auto;',
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
			),
			'create_new_variations_when_linking' => array(
				'title'       => __( 'Create new variations from Faire', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Yes, add product variations to WooCommerce', 'faire-for-woocommerce' ),
				'default'     => 'no',
				'description' => __( 'Check this option to create new wordpress product variations when linking products.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'create_new_products_when_linking'   => array(
				'title'       => __( 'Create new products from Faire', 'faire-for-woocommerce' ),
				'type'        => 'checkbox',
				'label'       => __( 'Yes, add products to WooCommerce', 'faire-for-woocommerce' ),
				'default'     => 'no',
				'description' => __( 'Check this option to create new wordpress products when linking products.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			// 'product_linking_create_variations_csv' => array(
			// 'title'       => __( 'Variation CSV', 'faire-for-woocommerce' ),
			// 'label'       => __( 'Download variation CSV', 'faire-for-woocommerce' ),
			// 'type'        => 'button',
			// 'description' => __( 'Download new variations CSV for importing into WordPress.', 'faire-for-woocommerce' ),
			// 'desc_tip'    => false,
			// ),
			// 'product_linking_create_products_csv'   => array(
			// 'title'       => __( 'Product CSV', 'faire-for-woocommerce' ),
			// 'label'       => __( 'Download product CSV', 'faire-for-woocommerce' ),
			// 'type'        => 'button',
			// 'description' => __( 'Download new products CSV for importing into WordPress.', 'faire-for-woocommerce' ),
			// 'desc_tip'    => false,
			// ),
			'product_linking_csv'                => array(
				'title'       => __( 'Download CSV', 'faire-for-woocommerce' ),
				'type'        => 'download',
				'description' => __( 'Download a CSV of your newly created products or variations.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
			'product_unlinking_manual_sync'      => array(
				'title'       => __( 'Unlink products', 'faire-for-woocommerce' ),
				'label'       => __( 'Unlink all products', 'faire-for-woocommerce' ),
				'type'        => 'button',
				'class'       => 'button-cancel',
				'description' => __( 'Remove all links between WooCommerce products and Faire products.', 'faire-for-woocommerce' ),
				'desc_tip'    => false,
			),
		);
	}

	/**
	 * Get the form fields after they are initialized
	 */
	public function maybe_disable_fields() {

		// Conditionally hide initial setup if already run.
		if ( '' !== $this->get_option( 'api_key' ) && $this->get_option( 'initial_setup_date' ) && $this->is_sync_enabled() ) {
			unset( $this->form_fields['initial_setup'] );
		}

		// Conditionally disable sync buttons.
		$disable_sync_fields = array(
			'product_sync_manual',
			'order_sync_manual',
			'product_sync_mode',
			'order_sync_mode',
			'product_linking_sync_manual',
		);
		if ( $this->is_sync_enabled() === false ) {
			foreach ( $disable_sync_fields as $field_key ) {
				if ( ! isset( $this->form_fields[ $field_key ] ) ) {
					continue;
				}
				$this->form_fields[ $field_key ]['class']             = isset( $this->form_fields[ $field_key ]['class'] )
					? $this->form_fields[ $field_key ]['class']
					: ''
					. ' disabled';
				$this->form_fields[ $field_key ]['custom_attributes'] = array( 'disabled' => 'disabled' );
			}
		}
		$sync_status = new Sync_Order_Status( $this->plugin_settings );
		if ( isset( $this->form_fields['order_sync_manual'] ) && $sync_status->check_sync_running() ) {
			$this->form_fields['order_sync_manual']['custom_attributes'] = array( 'disabled' => 'disabled' );
		}

		if ( isset( $this->form_fields['cancel_order_sync_manual'] ) && ! $sync_status->check_sync_running() ) {
			$this->form_fields['cancel_order_sync_manual']['hidden'] = true;
		}

		if ( ! $this->get_option( 'api_key' ) ) {
			$this->form_fields['debug']['hidden']               = true;
			$this->form_fields['test_api_connection']['hidden'] = true;
		}

		$inventory_sync_settings = array(
			'inventory_sync_on_add_to_cart',
			'inventory_sync_on_change',
		);
		if ( 'yes' !== get_option( 'woocommerce_manage_stock' ) ) {
			foreach ( $inventory_sync_settings as $field_key ) {
				if ( ! isset( $this->form_fields[ $field_key ] ) ) {
					continue;
				}
				$this->form_fields[ $field_key ]['class']             = isset( $this->form_fields[ $field_key ]['class'] )
					? $this->form_fields[ $field_key ]['class']
					: ''
					. ' disabled';
				$this->form_fields[ $field_key ]['custom_attributes'] = array( 'disabled' => 'disabled' );
			}
		}
		// Conditionally disable download csv buttons.
		$disable_download_fields = array(
			'product_linking_create_variations_csv',
			'product_linking_create_products_csv',
		);
		foreach ( $disable_download_fields as $field_key ) {
			if ( ! isset( $this->form_fields[ $field_key ] ) ) {
				continue;
			}
			$option_key = 'faire_' . $field_key; // Lookup global shop setting, not faire plugin setting.
			if ( ! get_option( $option_key ) ) {
				$this->form_fields[ $field_key ]['class']             = isset( $this->form_fields[ $field_key ]['class'] )
					? $this->form_fields[ $field_key ]['class']
					: ''
					. ' disabled';
				$this->form_fields[ $field_key ]['custom_attributes'] = array( 'disabled' => 'disabled' );
			}
		}
		// Conditionally add product linking before sync warning flag.
		$warning_sync_fields = array(
			'product_sync_manual',
			'product_sync_mode',
		);
		if ( $this->get_option( 'initial_setup_products_exist', false ) ) {
			foreach ( $warning_sync_fields as $field_key ) {
				if ( ! isset( $this->form_fields[ $field_key ] ) ) {
					continue;
				}
				$custom_attributes                                    = isset( $this->form_fields[ $field_key ]['custom_attributes'] ) ? $this->form_fields[ $field_key ]['custom_attributes'] : array();
				$this->form_fields[ $field_key ]['custom_attributes'] = array_merge( $custom_attributes, array( 'data-faire-linking-warning' => 'true' ) );
			}
		}
		// Conditionally disable "Skip Order Creation".
		if ( true === $this->plugin_settings->get_suppress_currency_matching() ) {
			$this->form_fields['order_sync_skip_orders_create']['class']                 = isset( $this->form_fields['order_sync_skip_orders_create']['class'] )
					? $this->form_fields['order_sync_skip_orders_create']['class']
					: ''
					. ' disabled';
				$this->form_fields['order_sync_skip_orders_create']['custom_attributes'] = array(
					'disabled' => 'disabled',
					'checked'  => 'checked',
				);
		}
	}

		/**
		 * Generate Text Input HTML.
		 *
		 * @param string $key Field key.
		 * @param array  $data Field data.
		 * @since  1.0.0
		 * @return string
		 */
	public function generate_text_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'section_class'     => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top"<?php echo ! empty( $data['section_class'] ) ? ' class="' . esc_attr( $data['section_class'] ) . '"' : ''; ?>>
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?> />
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Radio Button HTML.
	 *
	 * @param string $key  Field key.
	 * @param array  $data Field setup data.
	 */
	public function generate_radio_html( string $key, array $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => esc_attr( $field ),
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
			'options'           => '',
		);
		$data     = wp_parse_args( $data, $defaults );
		ob_start();
		?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
					<?php echo wp_kses_post( $this->get_tooltip_html( $data ) ); ?>
				</th>
				<td class="forminp forminp-radio">
					<fieldset>
						<ul>
							<?php
							foreach ( $data['options'] as $value => $label ) :
								$checked = checked(
									$this->plugin_settings->get_product_pricing_policy(),
									$value,
									false
								);
								?>
							<li>
								<label>
									<input
										type="radio"
										name="<?php echo esc_attr( $field ); ?>"
										value="<?php echo esc_attr( $value ); ?>"
										style="<?php echo esc_attr( $data['css'] ); ?>"
										class="<?php echo esc_attr( $data['class'] ); ?>"
										<?php echo esc_attr( $this->get_custom_attribute_html( $data ) ); ?>
										<?php echo esc_attr( $checked ); ?>
									>
									<?php echo wp_kses_post( $label ); ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
					</fieldset>
				</td>
			</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Hidden input HTML.
	 *
	 * @param string $key  Field key.
	 * @param array  $data Field setup data.
	 */
	public function generate_hidden_html( string $key, array $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => esc_attr( $field ),
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
			'value'             => '',
		);
		$data     = wp_parse_args( $data, $defaults );

		ob_start();
		?>
			<tr valign="top">
				<td colspan="2" class="forminp forminp-hidden">
					<input
						type="hidden"
						id="<?php echo esc_attr( $field ); ?>"
						name="<?php echo esc_attr( $field ); ?>"
						value="<?php echo esc_attr( $this->get_option( $key ) ); ?>"
						style="<?php echo esc_attr( $data['css'] ); ?>"
						class="<?php echo esc_attr( $data['class'] ); ?>"
					>
				</td>
			</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Title HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_table_html( $key, $data ) {
		$field = $this->plugin_id . $this->id . '_' . $key;
		ob_start();
		?>
		<tr valign="top" class="hr-top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp forminp-radio">
			<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
				<table class="sync-status-table">
					<tr>
						<th></th>
						<th>WooCommerce</th>
						<th>Faire</th>
						<th>Match</th>
					</tr>
					<tr>
						<th>Language</th>
						<td><?php echo esc_html( $this->format_code_lang( get_locale() ) ); ?></td>
						<td class="sync-status-table-lang-faire">
							<?php
								echo $this->get_option( 'api_key' ) ?
								esc_html( $this->format_code_lang( $this->get_option( 'brand_locale' ) ) ) :
								'<i>' . esc_html__( 'Add API above to fill', 'faire-for-woocommerce' ) . '</i>';
							?>
						</td>
						<td>
						<?php
						if ( $this->get_option( 'api_key' ) ) {
							echo '<span class="lang-currency-status">';
							echo $this->language_match() ?
								'<i class="ok ok-green"></i>' . esc_html__( 'Match', 'faire-for-woocommerce' ) :
								'<i class="error"></i>' . esc_html__( 'Mismatch', 'faire-for-woocommerce' );
							echo '</span>';
						}
						?>
						</td>
					</tr>
					<tr>
						<th>Currency</th>
						<td><?php echo esc_html( get_woocommerce_currency() ); ?></td>
						<td class="sync-status-table-lang-currency">
							<?php
								echo $this->get_option( 'api_key' ) ?
								esc_html( $this->get_option( 'brand_currency' ) ) :
								'<i>' . esc_html__( 'Add API above to fill', 'faire-for-woocommerce' ) . '</i>';
							?>
						</td>
						<td>
						<?php
						if ( $this->get_option( 'api_key' ) ) {
							echo '<span class="lang-currency-status">';
							echo $this->currency_match() ?
								'<i class="ok ok-green"></i>' . esc_html__( 'Match', 'faire-for-woocommerce' ) :
								'<i class="error"></i>' . esc_html__( 'Mismatch', 'faire-for-woocommerce' );
							echo '</span>';
						}
						?>
						</td>
					</tr>
				</table>
				<?php if ( ! $this->language_match() || ! $this->currency_match() ) : ?>
					<p class="notification-error">
					<b><?php esc_html_e( 'Your language and currency need to match.', 'faire-for-woocommerce' ); ?></b> <?php esc_html_e( 'Update your language and currency on WooCommerce to match your language and currency on Faire.', 'faire-for-woocommerce' ); ?> <a href="#" target="_blank"><?php esc_html_e( 'Get help', 'faire-for-woocommerce' ); ?></a>
					</p>
				<?php endif; ?>
				<?php if ( $this->get_option( 'api_key' ) ) : ?>
					<p class="description">
					<?php
					echo sprintf(
						// translators: sync button markup.
						esc_html__( 'If you’ve changed your language or currency settings on Faire since adding the API key, you can %1$ssync again%2$s to update the settings here.', 'faire-for-woocommerce' ),
						'<a id="woocommerce_faire_wc_integration_brand_sync_manual" href="#">',
						'</a>'
					);
					?>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Title HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_menu_html( $key, $data ) {
		$first = true;
		ob_start();
		echo '<ul class="menu">';
		$tag = $this->is_sync_enabled() ? 'a' : 'span';
		foreach ( $this->form_fields as $key => $field ) {
			if ( 'title' === $field['type'] ) {
				echo '<li><' . esc_html( $tag ) . ' class="faire-admin-section-link faire-admin-section-link-js' . ( $first ? ' is-selected' : '' ) . ( 'a' === $tag ? ( '" href="#' . esc_attr( $key ) . '" data-section="' . esc_attr( $key ) ) : '' ) . '">' . esc_html( $field['title'] ) . '</' . esc_html( $tag ) . '></li>';
				$first = false;
			}
		}
		echo '</ul>';
		return ob_get_clean();
	}

	/**
	 * Generate Title HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_title_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title' => '',
			'class' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		</table>
		<?php echo isset( $data['flag'] ) && 'first' === $data['flag'] ? '<div class="sections">' : '</div>'; ?>
		<div class="options-section" data-section="<?php echo $key; ?>">
		<h3 class="wc-settings-sub-title <?php echo esc_attr( $data['class'] ); ?>" id="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></h3>
		<?php if ( ! empty( $data['description'] ) ) : ?>
			<p><?php echo wp_kses_post( $data['description'] ); ?></p>
		<?php endif; ?>
		<table class="form-table">
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Title HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_section_close_html( $key, $data ) {
		return '</div>';
	}


	/**
	 * Generate Hidden input HTML.
	 *
	 * @param string $key  Field key.
	 * @param array  $data Field setup data.
	 */
	public function generate_simple_html( string $key, array $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => esc_attr( $field ),
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
			'value'             => '',
		);
		$data     = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top" class="mt-small">
			<td class="forminp">
				<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Button HTML.
	 */
	public function generate_button_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => 'button-secondary',
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
			'label'             => '',
			'hidden'            => false,
		);

		$data = wp_parse_args( $data, $defaults );
		if ( true === $data['hidden'] ) {
			return;
		}

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo wp_kses_post( $this->get_tooltip_html( $data ) ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<button
						class="<?php echo esc_attr( $data['class'] ); ?>"
						type="button" name="<?php echo esc_attr( $field ); ?>"
						id="<?php echo esc_attr( $field ); ?>"
						style="<?php echo esc_attr( $data['css'] ); ?>"
						<?php echo esc_attr( $this->get_custom_attribute_html( $data ) ); ?>
					>
						<?php echo wp_kses_post( $data['label'] ); ?>
					</button>
					<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}


	/**
	 * Generate Button HTML.
	 */
	public function generate_download_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'class'             => 'button-secondary',
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => false,
			'description'       => '',
			'title'             => '',
			'label'             => '',
			'hidden'            => false,
		);

		$data = wp_parse_args( $data, $defaults );
		if ( true === $data['hidden'] ) {
			return;
		}

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<button class="button-secondary button-inline" type="button" name="<?php echo esc_attr( $this->plugin_id . $this->id ) . '_product_linking_create_products_csv'; ?>" id="<?php echo esc_attr( $this->plugin_id . $this->id ) . '_product_linking_create_products_csv'; ?>">
						Download product CSV</button>
					<button class="button-secondary button-inline" type="button" name="<?php echo esc_attr( $this->plugin_id . $this->id ) . '_product_linking_create_variations_csv'; ?>" id="<?php echo esc_attr( $this->plugin_id . $this->id ) . '_product_linking_create_variations_csv'; ?>">
						Download variation CSV</button>
					<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Button HTML.
	 */
	public function generate_sync_schedule_html( $key, $data ) {
		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'    => '',
			'default'  => 'daily',
			'hidden'   => false,
			'desc_tip' => false,
		);

		$data = wp_parse_args( $data, $defaults );
		if ( true === $data['hidden'] ) {
			return;
		}

		$schedule_num_field      = $field . '_num';
		$schedule_interval_field = $field . '_time';
		$schedule_num_value      = $this->get_option( $key . '_num', 1 );
		$schedule_interval_value = $this->get_option( $key . '_time', $data['default'] );

		ob_start();
		?>
		<tr valign="top" class="mt-small">
			<th scope="row" class="titledesc titledesc-small">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp">
				<fieldset class="fieldset-flex-row" id="<?php echo esc_attr( $field ); ?>">
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<span class="label"><?php esc_html_e( 'Sync every', 'faire-for-woocommerce' ); ?></span>
					<input class="input-text regular-input " type="number" name="<?php echo esc_attr( $schedule_num_field ); ?>" id="<?php echo esc_attr( $schedule_num_field ); ?>" value="<?php echo esc_attr( $schedule_num_value ); ?>" placeholder="" min="0.5" max="5000" step="0.5">
					<select class="select wc-enhanced-select" name="<?php echo esc_attr( $schedule_interval_field ); ?>" id="<?php echo esc_attr( $schedule_interval_field ); ?>">
						<option value="hours" <?php selected( 'hours', esc_attr( $schedule_interval_value ) ); ?>><?php esc_html_e( 'hour(s)', 'faire-for-woocommerce' ); ?></option>
						<option value="daily" <?php selected( 'daily', esc_attr( $schedule_interval_value ) ); ?>><?php esc_html_e( 'day(s)', 'faire-for-woocommerce' ); ?></option>
					</select>
					<?php echo wp_kses_post( $this->get_description_html( $data ) ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Button HTML.
	 */
	public function generate_none_html( $key, $data ) {
		return '';
	}

		/**
		 * Generate Textarea HTML.
		 *
		 * @param string $key Field key.
		 * @param array  $data Field data.
		 * @since  1.0.0
		 * @return string
		 */
	public function generate_api_key_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<textarea rows="2" cols="20" class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Checkbox HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_checkbox_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'label'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'hidden'            => false,
		);

		$data = wp_parse_args( $data, $defaults );

		if ( ! $data['label'] ) {
			$data['label'] = $data['title'];
		}

		ob_start();
		?>
		<tr valign="top"<?php echo $data['hidden'] ? ' class="hidden"' : ''; ?>>
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<label for="<?php echo esc_attr( $field_key ); ?>">
					<input <?php disabled( $data['disabled'], true ); ?> class="<?php echo esc_attr( $data['class'] ); ?>" type="checkbox" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="1" <?php checked( $this->get_option( $key ), 'yes' ); ?> <?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?> /> <?php echo wp_kses_post( $data['label'] ); ?></label><br/>
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}


	/**
	 * Generate Select HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_select_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'options'           => array(),
			'hidden'            => false,
		);

		$data  = wp_parse_args( $data, $defaults );
		$value = $this->get_option( $key );

		ob_start();
		?>
		<tr valign="top"<?php echo $data['hidden'] ? ' class="hidden"' : ''; ?>>
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
							<?php if ( is_array( $option_value ) ) : ?>
								<optgroup label="<?php echo esc_attr( $option_key ); ?>">
									<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
										<option value="<?php echo esc_attr( $option_key_inner ); ?>" <?php selected( (string) $option_key_inner, esc_attr( $value ) ); ?>><?php echo esc_html( $option_value_inner ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php else : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( (string) $option_key, esc_attr( $value ) ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Is API Key Entered.
	 *
	 * @return bool
	 */
	public function is_api_key_set() {
		return $this->get_option( 'api_key' );
	}

	/**
	 * Is API Key Entered.
	 *
	 * @return bool
	 */
	public function currency_match() {
		return get_woocommerce_currency() === $this->get_option( 'brand_currency' );
	}

	/**
	 * Is API Key Entered.
	 *
	 * @return bool
	 */
	public function language_match() {
		return $this->format_code_lang( get_locale() ) === $this->format_code_lang( $this->get_option( 'brand_locale' ) );
	}

	/**
	 * Sanitize our settings
	 *
	 * @see process_admin_options()
	 */
	public function sanitize_settings( $settings ) {

		// We're just going to make the api key all upper case characters since that's how our imaginary API works.
		if ( isset( $settings['api_key'] ) ) {
			$settings['api_key'] = strtolower( $settings['api_key'] );
		}

		$stock_management_enabled = get_option( 'woocommerce_manage_stock' ) === 'yes';
		if ( isset( $settings['inventory_sync_on_add_to_cart'] ) ) {
			$settings['inventory_sync_on_add_to_cart'] = $stock_management_enabled
				? $settings['inventory_sync_on_add_to_cart']
				: 'no';
		}

		return $settings;
	}

	/**
	 * Ensures inventory sync is disabled if WooCommerce stock management is.
	 *
	 * If WooCommerce general setting to enable stock management is disabled,
	 * inventory sync related settings should be off.
	 *
	 * @param mixed $value
	 *   The WooCommerce settings field value.
	 *
	 * @param array $option
	 *   The WooCommerce settings field data.
	 *
	 * @return mixed
	 *   The WooCommerce settings field value, unchanged.
	 */
	public function manage_inventory_sync_settings( $value, array $option ) {
		if ( 'woocommerce_manage_stock' === $option['id'] && 'yes' !== $value ) {
			$this->update_option( 'inventory_sync_on_add_to_cart', 'no' );
			$this->update_option( 'inventory_sync_on_change', 'no' );
		}
		return $value;
	}

	/**
	 * Process our settings with exclusions
	 *
	 * @see parent::process_admin_options()
	 */
	public function faire_process_admin_options() {

		parent::process_admin_options();

		$this->maybe_run_initial_setup_sync();

		add_action( 'admin_notices', array( $this, 'display_errors' ) );
	}


	/**
	 * Validate the API key
	 *
	 * @see validate_settings_fields()
	 */
	 /*
	public function validate_api_key_field( $key ) {
		// get the posted value
		$value = $_POST[ $this->plugin_id . $this->id . '_' . $key ];

		// check if the API key is longer than 20 characters. Our imaginary API doesn't create keys that large so something must be wrong. Throw an error which will prevent the user from saving.
		if ( isset( $value ) &&
			20 < strlen( $value ) ) {
			$this->errors[] = $key;
		}
		return $value;
	}
	*/


	/**
	 * Display errors by overriding the display_errors() method
	 *
	 * @see display_errors()
	 */
	/*
	public function display_errors() {

		// loop through each error and display it
		foreach ( $this->errors as $key => $value ) {
			?>
		<div class="error">
			<p><?php _e( 'Looks like you made a mistake with the ' . $value . ' field. Make sure it isn&apos;t longer than 20 characters', 'faire-for-woocommerce' ); ?></p>
		</div>
			<?php
		}
	}
	*/

	public function maybe_run_initial_setup_sync() {

		if ( empty( $this->get_option( 'api_key' ) ) ) {
			return;
		}

		// Send a test request to the API.
		try {
			$this->api->test_connection();
		} catch ( Exception $e ) {
			$message = sprintf(
				'%s %s',
				__( 'API Connection failed. Please check your API key and API mode.', 'faire-for-woocommerce' ),
				$e->getMessage(),
			);

			if ( ! in_array( $message, $this->errors ) ) {
				$this->add_error( $message );
			}
			return;
		}

		if ( ! isset( $_POST[ 'woocommerce_' . $this->id . '_initial_setup_trigger' ] ) ) {
			return;
		}

		// If API test passed, then perform initial sync.
		$sync_brand   = new Sync_Brand( $this->api, $this->plugin_settings );
		$brand_result = $sync_brand->import_brand();

		$sync_taxonomy = new Sync_Taxonomy( new Product_Api(), $this->plugin_settings );
		$tax_result    = $sync_taxonomy->import_taxonomy_types();

		if ( 'error' === $brand_result['status'] ) {
			$this->add_error( $brand_result['info'] );
		} else {
			// Set for this instance.
			if ( isset( $brand_result['brand']['locale'] ) ) {
				$this->settings['brand_locale'] = $brand_result['brand']['locale'];
			}
			if ( isset( $brand_result['brand']['currency'] ) ) {
				$this->settings['brand_currency'] = $brand_result['brand']['currency'];
			}
		}
		if ( 'error' === $tax_result['status'] ) {
			$this->add_error( $tax_result['info'] );
		}
		if ( 'success' === $brand_result['status'] && 'success' === $tax_result['status'] ) {
			$update_date = gmdate( 'c' );
			$this->update_option( 'initial_setup_date', $update_date );
			$this->settings['initial_setup_date'] = $update_date;
		}

		if ( ! empty( get_option( 'faire_products_linking_last_sync_date' ) ) ) {
			return;
		}

		// Check for existing products.
		$product_linking = new Sync_Product_Linking( new Product_Api(), $this->plugin_settings );
		$product_check   = $product_linking->check_if_faire_products_exist();

		if ( 'error' === $product_check['status'] ) {
			$this->add_error( $product_check['info'] );
		} else {
			if ( isset( $product_check['products_exist'] ) ) {
				$exist = (bool) $product_check['products_exist'];
				$this->update_option( 'initial_setup_products_exist', $exist );
				$this->settings['initial_setup_products_exist'] = $exist;

				$this->add_error(
					sprintf(
						esc_html__( 'Existing products were found at Faire. Run %1$sproduct linking%2$s to link faire products with WooCommerce before running product sync.', 'faire-for-woocommerce' ),
						'<a class="faire-admin-section-link-js" href="#product_linking" data-section="product_linking">',
						'</a>',
					)
				);
			}
		}

		// Re-initialize fields to reflect our updated brand locale and currency.
		$this->init_form_fields();
		$this->init_settings();
	}

	/**
	 * Determine if sync should be enabled
	 *
	 * @return boolean
	 */
	public function is_sync_enabled(): bool {

		// Add error if locale and currency do not match.
		if ( ! $this->get_option( 'api_key' ) || true !== $this->plugin_settings->is_sync_enabled() ) {
			return false;
		}
					// Send a test request to the API.
		try {
			$this->api->test_connection();
			return true;
		} catch ( Exception $e ) {
			$message = sprintf(
				'%s %s',
				__( 'API Connection failed. Please check your API key and API mode.', 'faire-for-woocommerce' ),
				$e->getMessage(),
			);

			if ( ! in_array( $message, $this->errors ) ) {
				$this->add_error( $message );
			}
		}

		return false;
	}

	/**
	 * Handles AJAX call to test API connection.
	 */
	public function ajax_test_api_connection() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_test_api_connection' )
		) {
			wp_send_json_error(
				__( 'Testing failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		// Send a test request to the API.
		try {
			$this->api->test_connection();
			// Connection works.
			wp_send_json_success(
				__( '<i class="ok"></i> API connection is working OK.', 'faire-for-woocommerce' ),
				200
			);
		} catch ( Exception $e ) {
			wp_send_json_error(
				sprintf(
					'%s %s',
					__( '<i class="error"></i> Connection test failed.', 'faire-for-woocommerce' ),
					$e->getMessage(),
				),
				401
			);
		}
	}

	/**
	 * Handles product sync progress via heartbeat.
	 *
	 * @param  array $response heartbeat response.
	 * @param  array $data heartbeat data.
	 *
	 * @return array
	 */
	public function manual_sync_status( array $response, array $data ) {

		if ( empty( $data['faire_product_manual_sync'] ) && empty( $data['faire_order_manual_sync'] ) ) {
			return $response;
		}

		$remaining_products = as_get_scheduled_actions(
			array(
				'hook'   => 'faire_scheduler_hook_faire_product_scheduled_event',
				'group'  => 'faire_scheduled_actions_single_time',
				'status' => \ActionScheduler_Store::STATUS_PENDING,
			),
			'ids'
		);

		$remaining_orders = as_get_scheduled_actions(
			array(
				'hook'   => 'faire_scheduler_hook_faire_order_scheduled_event',
				'group'  => 'faire_scheduled_actions_single_time',
				'status' => \ActionScheduler_Store::STATUS_PENDING,
			),
			'ids'
		);
		if ( ! empty( $data['faire_product_manual_sync'] ) ) {
			$response['faire_product_manual_sync_status']['message'] = 0 < count( $remaining_products ) ? __( 'Remaining products to sync', 'faire-for-woocommerce' ) . ' ' . count( $remaining_products ) : '<i class="ok"></i> ' . __( 'Products synced', 'faire-for-woocommerce' );
			$response['faire_product_manual_sync_status']['details'] = $this->get_option( 'product_sync_results' );
		}

		if ( ! empty( $data['faire_order_manual_sync'] ) ) {
			$response['faire_order_manual_sync_status']['message'] = 0 < count( $remaining_orders ) ? __( 'Remaining orders to sync', 'faire-for-woocommerce' ) . ' ' . count( $remaining_orders ) : '<i class="ok"></i> ' . __( 'Orders synced', 'faire-for-woocommerce' );
			$response['faire_order_manual_sync_status']['details'] = $this->get_option( 'order_sync_results' );
		}

		return $response;
	}

	/**
	 * Retrieves all defined images sizes with their dimensions.
	 *
	 * @param int $min_width  Minimum width for the collected image sizes.
	 * @param int $min_height Minimum height for the collected image sizes.
	 *
	 * @return array List of image sizes.
	 */
	private function get_images_sizes_info( int $min_width = 0, int $min_height = 0 ): array {
		$sizes                     = array();
		$registered_image_subsizes = wp_get_registered_image_subsizes();
		$image_is_big_enough       =
			function( $width, $height ) use ( $min_width, $min_height ) {
				return $width >= $min_width && $height >= $min_height;
			};

		foreach ( $registered_image_subsizes as $key => $image_size ) {
			if (
				$image_is_big_enough( $image_size['width'], $image_size['height'] )
			) {
				$sizes[ $key ] = $image_size;
			}
		}

		return $sizes;
	}

	/**
	 * Builds options for the image size dropdown setting.
	 *
	 * @param array $images_sizes Available image sizes.
	 *
	 * @return array List of options.
	 */
	private function get_images_sizes_options( array $images_sizes ): array {
		$options = array();
		foreach ( $images_sizes as $name => $dimensions ) {
			$options[ $name ] = $name;
		}
		$options['original'] = __( 'Original', 'faire-for-woocommerce' );
		return $options;
	}

	private function format_code_lang( $code = '' ) {
		$code       = strtolower( substr( $code, 0, 2 ) );
		$lang_codes = array(
			'aa' => 'Afar',
			'ab' => 'Abkhazian',
			'af' => 'Afrikaans',
			'ak' => 'Akan',
			'sq' => 'Albanian',
			'am' => 'Amharic',
			'ar' => 'Arabic',
			'an' => 'Aragonese',
			'hy' => 'Armenian',
			'as' => 'Assamese',
			'av' => 'Avaric',
			'ae' => 'Avestan',
			'ay' => 'Aymara',
			'az' => 'Azerbaijani',
			'ba' => 'Bashkir',
			'bm' => 'Bambara',
			'eu' => 'Basque',
			'be' => 'Belarusian',
			'bn' => 'Bengali',
			'bh' => 'Bihari',
			'bi' => 'Bislama',
			'bs' => 'Bosnian',
			'br' => 'Breton',
			'bg' => 'Bulgarian',
			'my' => 'Burmese',
			'ca' => 'Catalan; Valencian',
			'ch' => 'Chamorro',
			'ce' => 'Chechen',
			'zh' => 'Chinese',
			'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
			'cv' => 'Chuvash',
			'kw' => 'Cornish',
			'co' => 'Corsican',
			'cr' => 'Cree',
			'cs' => 'Czech',
			'da' => 'Danish',
			'dv' => 'Divehi; Dhivehi; Maldivian',
			'nl' => 'Dutch; Flemish',
			'dz' => 'Dzongkha',
			'en' => 'English',
			'eo' => 'Esperanto',
			'et' => 'Estonian',
			'ee' => 'Ewe',
			'fo' => 'Faroese',
			'fj' => 'Fijjian',
			'fi' => 'Finnish',
			'fr' => 'French',
			'fy' => 'Western Frisian',
			'ff' => 'Fulah',
			'ka' => 'Georgian',
			'de' => 'German',
			'gd' => 'Gaelic; Scottish Gaelic',
			'ga' => 'Irish',
			'gl' => 'Galician',
			'gv' => 'Manx',
			'el' => 'Greek, Modern',
			'gn' => 'Guarani',
			'gu' => 'Gujarati',
			'ht' => 'Haitian; Haitian Creole',
			'ha' => 'Hausa',
			'he' => 'Hebrew',
			'hz' => 'Herero',
			'hi' => 'Hindi',
			'ho' => 'Hiri Motu',
			'hu' => 'Hungarian',
			'ig' => 'Igbo',
			'is' => 'Icelandic',
			'io' => 'Ido',
			'ii' => 'Sichuan Yi',
			'iu' => 'Inuktitut',
			'ie' => 'Interlingue',
			'ia' => 'Interlingua (International Auxiliary Language Association)',
			'id' => 'Indonesian',
			'ik' => 'Inupiaq',
			'it' => 'Italian',
			'jv' => 'Javanese',
			'ja' => 'Japanese',
			'kl' => 'Kalaallisut; Greenlandic',
			'kn' => 'Kannada',
			'ks' => 'Kashmiri',
			'kr' => 'Kanuri',
			'kk' => 'Kazakh',
			'km' => 'Central Khmer',
			'ki' => 'Kikuyu; Gikuyu',
			'rw' => 'Kinyarwanda',
			'ky' => 'Kirghiz; Kyrgyz',
			'kv' => 'Komi',
			'kg' => 'Kongo',
			'ko' => 'Korean',
			'kj' => 'Kuanyama; Kwanyama',
			'ku' => 'Kurdish',
			'lo' => 'Lao',
			'la' => 'Latin',
			'lv' => 'Latvian',
			'li' => 'Limburgan; Limburger; Limburgish',
			'ln' => 'Lingala',
			'lt' => 'Lithuanian',
			'lb' => 'Luxembourgish; Letzeburgesch',
			'lu' => 'Luba-Katanga',
			'lg' => 'Ganda',
			'mk' => 'Macedonian',
			'mh' => 'Marshallese',
			'ml' => 'Malayalam',
			'mi' => 'Maori',
			'mr' => 'Marathi',
			'ms' => 'Malay',
			'mg' => 'Malagasy',
			'mt' => 'Maltese',
			'mo' => 'Moldavian',
			'mn' => 'Mongolian',
			'na' => 'Nauru',
			'nv' => 'Navajo; Navaho',
			'nr' => 'Ndebele, South; South Ndebele',
			'nd' => 'Ndebele, North; North Ndebele',
			'ng' => 'Ndonga',
			'ne' => 'Nepali',
			'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian',
			'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
			'no' => 'Norwegian',
			'ny' => 'Chichewa; Chewa; Nyanja',
			'oc' => 'Occitan, Provençal',
			'oj' => 'Ojibwa',
			'or' => 'Oriya',
			'om' => 'Oromo',
			'os' => 'Ossetian; Ossetic',
			'pa' => 'Panjabi; Punjabi',
			'fa' => 'Persian',
			'pi' => 'Pali',
			'pl' => 'Polish',
			'pt' => 'Portuguese',
			'ps' => 'Pushto',
			'qu' => 'Quechua',
			'rm' => 'Romansh',
			'ro' => 'Romanian',
			'rn' => 'Rundi',
			'ru' => 'Russian',
			'sg' => 'Sango',
			'sa' => 'Sanskrit',
			'sr' => 'Serbian',
			'hr' => 'Croatian',
			'si' => 'Sinhala; Sinhalese',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'se' => 'Northern Sami',
			'sm' => 'Samoan',
			'sn' => 'Shona',
			'sd' => 'Sindhi',
			'so' => 'Somali',
			'st' => 'Sotho, Southern',
			'es' => 'Spanish; Castilian',
			'sc' => 'Sardinian',
			'ss' => 'Swati',
			'su' => 'Sundanese',
			'sw' => 'Swahili',
			'sv' => 'Swedish',
			'ty' => 'Tahitian',
			'ta' => 'Tamil',
			'tt' => 'Tatar',
			'te' => 'Telugu',
			'tg' => 'Tajik',
			'tl' => 'Tagalog',
			'th' => 'Thai',
			'bo' => 'Tibetan',
			'ti' => 'Tigrinya',
			'to' => 'Tonga (Tonga Islands)',
			'tn' => 'Tswana',
			'ts' => 'Tsonga',
			'tk' => 'Turkmen',
			'tr' => 'Turkish',
			'tw' => 'Twi',
			'ug' => 'Uighur; Uyghur',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			've' => 'Venda',
			'vi' => 'Vietnamese',
			'vo' => 'Volapük',
			'cy' => 'Welsh',
			'wa' => 'Walloon',
			'wo' => 'Wolof',
			'xh' => 'Xhosa',
			'yi' => 'Yiddish',
			'yo' => 'Yoruba',
			'za' => 'Zhuang; Chuang',
			'zu' => 'Zulu',
		);

		/**
		 * Filters the language codes.
		 *
		 * @since MU (3.0.0)
		 *
		 * @param string[] $lang_codes Array of key/value pairs of language codes where key is the short version.
		 * @param string   $code       A two-letter designation of the language.
		 */
		$lang_codes = apply_filters( 'lang_codes', $lang_codes, $code );
		return strtr( $code, $lang_codes );
	}

}
