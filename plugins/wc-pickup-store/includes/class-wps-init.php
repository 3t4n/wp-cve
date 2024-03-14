<?php
/**
 * Add shipping method to WC
 */
function wps_store_shipping_method( $methods ) {
	$methods['wc_pickup_store'] = 'WC_PICKUP_STORE';

	return $methods;
}
add_filter('woocommerce_shipping_methods', 'wps_store_shipping_method');

/**
 * Declare Shipping Method
 */
function wps_store_shipping_method_init() {
	if ( class_exists( 'WC_Shipping_Method' ) ) {
		class WC_PICKUP_STORE extends WC_Shipping_Method {
			public $enabled;
			public $enable_store_select;
			public $title;
			public $select_first_option;
			public $costs_type;
			public $costs;
			public $costs_per_store;
			public $stores_order_by;
			public $stores_order;
			public $store_default;
			public $checkout_notification;
			public $hide_store_details;
			public $country_filtering;
			public $external_bootstrap;
			public $external_font_awesome;
			public $local_css;
			public $disable_select2;
			public $tax_configuration_details;
			public $wps_tax_status;

			public $bootstrap_version;
			public $font_awesome_version;

			/**
			 * Constructor for your shipping class
			 *
			 * @access public
			 * @return void
			 */
			public function __construct()
			{
				$this->id = 'wc_pickup_store';
				$this->method_title = __('WC Pickup Store');
				$this->method_description = __('Lets users to choose a store to pick up their products', 'wc-pickup-store');
	
				$this->init();
			}
	
			/**
			 * Init your settings
			 *
			 * @access public
			 * @return void
			 */
			function init()
			{
				// Load the settings API
				$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
				$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
	
				// Turn these settings into variables we can use
				foreach ( $this->settings as $setting_key => $value ) {
					$this->$setting_key = apply_filters('wps_settings_data', $value, $setting_key, $this->settings);
				}

				// Set a default value if tax options are disabled on this site - 1.8.3
				if ( empty( $this->wps_tax_status  ) ) {
					$this->wps_tax_status = 'none';
				}

				// Check for external libraries
				$this->bootstrap_version = isset( $this->external_bootstrap ) ? $this->external_bootstrap : $this->wps_get_library_version_or_cdn('bootstrap');
				$this->font_awesome_version = isset( $this->external_font_awesome ) ? $this->external_font_awesome : $this->wps_get_library_version_or_cdn('font_awesome');
	
				// Save settings in admin if you have any defined
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				add_filter( 'woocommerce_get_order_item_totals', array( $this, 'wc_reordering_order_item_totals' ), 10, 3 );

				add_filter( 'wps_store_select_first_option', array( $this, 'wps_store_select_first_option_text' ), 60 );
			}
	
			public function init_form_fields() {
				$this->form_fields = require WPS_PLUGIN_PATH . '/includes/admin/wps-settings.php';
			}
	
			public function is_available( $package )
			{
				$is_available = ($this->enabled == 'yes') ? true : false;
	
				return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package, $this );
			}
	
			/**
			 * calculate_shipping function.
			 *
			 * @access public
			 * @param mixed $package
			 * @return void
			 */
			public function calculate_shipping( $package = array() )
			{
				$calculated_costs = $this->wps_get_calculated_costs( $this->costs, true );
				$formatted_title = $this->title;
				$is_taxable = ( bool ) ( wps_get_tax_status() == 'taxable' );
				
				if ( !empty( $this->costs ) && $this->costs_per_store != 'yes' ) {
					$formatted_title = ( $calculated_costs > 0 ) ? sprintf( '%1$s: %2$s', $this->title, wc_price( $calculated_costs ) ) : $this->title; // Only when shipping cost > 0
				}

				$rate = array(
					'id' => $this->id,
					'label' => apply_filters( 'wps_formatted_shipping_title', $formatted_title, $this->title, $calculated_costs ),
					'cost' => (float) apply_filters( 'wps_shipping_costs', $calculated_costs ),
					'package' => $package,
					'taxes' => apply_filters( 'wps_tax_calculatation', ( $is_taxable ) ? '' : false ),
					'calc_tax' => 'per_order' // 'per_item'
				);
	
				// Register the rate
				$this->add_rate( $rate );
			}
	
			public function generate_store_default_html()
			{
				ob_start();
				?>
				<tr valign="top">
					<th scope="row" class="titledesc"><?php _e('Default store', 'wc-pickup-store'); ?>:</th>
					<td class="forminp">
						<p><?php
							echo sprintf(__('Find this option in <a href="%s" target="_blank">the Customizer</a>', 'wc-pickup-store'), admin_url('/customize.php?autofocus[section]=wps_store_customize_section'));
						?></p>
					</td>
				</tr>
				<?php
				return ob_get_clean();
			}
	
			public function generate_plugin_version_html()
			{
				ob_start();
				?>
				<tr valign="top">
					<td colspan="2" align="right">
						<p><em><?php echo sprintf(__('Version %s', $this->id), WPS_PLUGIN_VERSION); ?></em></p>
					</td>
				</tr>
				<?php
				return ob_get_clean();
			}
	
			/**
			 * @version 1.8.6
			 * @since 1.x
			 * 
			 * @param array $total_rows
			 * @param WC_Order $order
			 * @param string $tax_display Tax to display.
			 */
			public function wc_reordering_order_item_totals( $total_rows, $order, $tax_display )
			{
				$store = $order->get_meta( '_shipping_pickup_stores' );
				$formatted_title = ( !empty( $this->costs ) && $this->costs_per_store != 'yes' ) ? $this->title . ': ' . wc_price( $this->wps_get_calculated_costs( $this->costs, true, $order ) ) : $this->title;
				$item_label[] = __('Pickup Store', 'wc-pickup-store');
				if ( !empty( $this->checkout_notification ) ) {
					$item_label[] = $this->checkout_notification;
				}
	
				if ( $order->has_shipping_method( $this->id ) && !empty( $store ) ) {
					foreach ( $total_rows as $key => $row ) {
						$new_rows[$key] = $row;
						if ( $key == 'shipping' ) {
							$new_rows['shipping']['value'] = $formatted_title; // Shipping title
							$new_rows[$this->id] = array(
								'label' => apply_filters( 'wps_order_shipping_item_label', implode( ': ', $item_label ), $this->checkout_notification ),
								'value' => $store
							);
						}
					}
					$total_rows = $new_rows;
				}
	
				return $total_rows;
			}
	
			/**
			 * Get calculated costs based on flat/percentage cost type
			 */
			public function wps_get_calculated_costs($shipping_costs, $costs_on_method = false, $order = null)
			{
				$store_shipping_cost = (double) (!empty($shipping_costs) && $this->costs_per_store == 'yes') ? $shipping_costs : $this->costs;
				if ( isset( $this->costs_type ) ) {
					switch ($this->costs_type) {
						case 'flat':
							$costs = (($this->costs_per_store == 'yes' && !$costs_on_method) || ($this->costs_per_store == 'no' && $costs_on_method)) ? $store_shipping_cost : 0;
						break;
						case 'percentage':
							$subtotal = !is_null($order) ? $order->get_subtotal() : WC()->cart->get_subtotal();
							$subtotal = (double) apply_filters('wps_subtotal_for_store_cost', $subtotal);
							$costs = (($this->costs_per_store == 'yes' && !$costs_on_method) || ($this->costs_per_store == 'no' && $costs_on_method)) ? ($subtotal * $store_shipping_cost) / 100 : 0;
						break;
						default:
							$costs = 0;
						break;
					}
				} else {
					$costs = 0;
				}
	
				return apply_filters('wps_store_calculated_costs', $costs, $this->costs_type);
			}

			/**
			 * The available CDN for libraries
			 * @version 1.6.1
			 * @param string $library
			 * @param string $version
			 * @return string Current version library CDN
			 * @return boolean In case $library or $version are not set
			 */
			public function wps_get_library_version_or_cdn( $library = '', $version = '' )
			{
				if ( $version == 'disable' )
					return false;

				if ( !empty( $library ) ) {
					if ( empty( $version ) ) {
						$defaults = array(
							'bootstrap' => 'version_3',
							'font_awesome' => 'version_4',
						);
	
						$version = version_compare(WPS_PLUGIN_VERSION, '1.6.0', '>') ? $defaults[$library] : false;
						return $version;
					}
				
					$libraries = array(
						'bootstrap' => array(
							'version_3' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
							'version_4' => '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'
						),
						'font_awesome' => array(
							'version_4' => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
							'version_5' => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'
						),
					);

					if ( isset( $libraries[$library] ) && isset( $libraries[$library][$version] ) ) {
						return $libraries[$library][$version];
					}
				}

				return false;
			}

			/**
			 * Stores dropdown first option
			 * 
			 * @version 1.8.5
			 * 
			 * @param string $first_option_text		Current first option
			 * @return string $first_option			New first option from settings
			 */
			public function wps_store_select_first_option_text( $first_option_text )
			{
				$first_option = !empty( $this->select_first_option ) ? $this->select_first_option : $first_option_text;

				return $first_option;
			}
		}
		new WC_PICKUP_STORE();
	}
}
add_action('init', 'wps_store_shipping_method_init');

/**
 * Returns the main instance for WC_PICKUP_STORE class
 */
function wps() {
	return new WC_PICKUP_STORE();
}