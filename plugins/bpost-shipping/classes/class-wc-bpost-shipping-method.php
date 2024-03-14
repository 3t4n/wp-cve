<?php

use Bpost\BpostApiClient\BpostException;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\Exception\WC_BPost_Shipping_Api_Exception_Country_Not_Allowed;
use WC_BPost_Shipping\Api\Exception\WC_BPost_Shipping_Api_Exception_No_Price_Found;
use WC_BPost_Shipping\Api\Exception\WC_BPost_Shipping_Api_Exception_Weight_Not_Allowed;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Strings;
use WC_BPost_Shipping\Factory\WC_BPost_Shipping_Factory_Form;
use WC_BPost_Shipping\JsonArray\WC_BPost_Shipping_JsonArray_Validator;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;

/**
 * Class WC_BPost_Shipping_Method creates a shipping method usable into woocommerce
 */
class WC_BPost_Shipping_Method extends WC_Shipping_Method {
	private $allowed_countries;

	/**Info provided by bpost system and must be handled by wp config*/

	/** @var WC_BPost_Shipping_Logger */
	private $logger;
	/** @var WC_BPost_Shipping_Configuration_Checker */
	private $config_checker;
	/** @var WC_BPost_Shipping_Api_Factory */
	private $product_configuration_factory;
	/** @var \WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce */
	private $woocommerce_adapter;

	/**
	 * WC_BPost_Shipping_Method constructor.
	 *
	 * @param int $instance_id
	 */
	public function __construct( $instance_id = 0 ) {
		$this->woocommerce_adapter = new \WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce();
		$this->allowed_countries   = $this->woocommerce_adapter->get_shipping_countries();

		if ( $this->woocommerce_adapter->is_wc_version_equal_or_greater_than( '2.6' ) ) {
			parent::__construct( $instance_id );
		}

		$strings = new WC_BPost_Shipping_Assets_Strings();

		// Id for your shipping method. Should be unique.
		$this->id = BPOST_PLUGIN_ID;
		// Title shown in admin
		$this->method_title = $strings->get_title();
		// Description shown in admin
		$this->method_description = $strings->get_description();

		//$this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
		$this->title = 'bpost shipping'; // This can be added as an setting but for this example its forced.

		$this->init_logger();

		$this->product_configuration_factory = new WC_BPost_Shipping_Api_Factory(
			new WC_BPost_Shipping_Options_Base(),
			$this->logger
		);

		$this->supports[] = 'shipping-zones';
		$this->supports[] = 'instance-settings';

		$this->init();
	}

	/**
	 * Init your settings
	 */
	public function init() {
		// Load the settings API
		$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
		$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
		$this->config_checker = new WC_BPost_Shipping_Configuration_Checker(
			$this->product_configuration_factory->get_product_configuration(),
			$this->product_configuration_factory->get_api_connector()
		);

		$this->enabled = $this->get_option( 'enabled' );

		// Save settings in admin if you have any defined
		add_action(
			'woocommerce_update_options_shipping_' . $this->id,
			array(
				$this,
				'process_admin_options',
			)
		);
	}

	/**
	 * Initialize integration settings form fields.
	 */
	public function init_form_fields() {
		$factory_form      = new WC_BPost_Shipping_Factory_Form( new WC_BPost_Shipping_Adapter_Woocommerce() );
		$this->form_fields = $factory_form->get_settings_form( $this->title );
	}


	/**
	 * Validate Text Field.
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param mixed $key
	 *
	 * @return string
	 */
	public function validate_jsonarray_field( $key ) {
		$json_array_validator = new WC_BPost_Shipping_JsonArray_Validator(
			$this->logger,
			$this->woocommerce_adapter,
			$this->get_field_key( $key ),
			array_intersect_key(
				$this->allowed_countries,
				$this->product_configuration_factory->get_product_configuration()->get_bpost_countries()
			),
			$_POST
		);

		return $json_array_validator->get_json();
	}

	/**
	 * @param $key
	 * @param $data
	 *
	 * @return string
	 */
	public function generate_jsonarray_html( $key, $data ) {
		$field = $this->get_field_key( $key );

		$defaults = array(
			'title'       => '',
			'disabled'    => false,
			'type'        => 'text',
			'desc_tip'    => false,
			'description' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<?php

				$shm_loader = new WC_BPost_Shipping_Json_Array_Controller(
					$this->woocommerce_adapter,
					$field,
					$this->get_option( $key ),
					array_intersect_key(
						$this->allowed_countries,
						$this->product_configuration_factory->get_product_configuration()->get_bpost_countries()
					)
				);
				$shm_loader->load_template();
				?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $key
	 *
	 * @return string
	 * @warning Add compatibility with WooCommerce <2.4.0
	 */
	public function get_field_key( $key ) {
		return $this->plugin_id . $this->id . '_' . $key;
	}

	/**
	 * calculate_shipping function.
	 *
	 * @param array $package
	 *
	 * @see \WC_Shipping::calculate_shipping_for_package
	 */
	public function calculate_shipping( $package = array() ) {
		if ( array_key_exists( 'post_data', $_POST ) ) {
			$post_data = wp_parse_args( $_POST['post_data'] );

			if ( $post_data['bpost_shm_already_called'] === 'yes' ) {
				$this->add_rate_from_cost( $post_data['bpost_delivery_price'] ); // Euros

				return;
			}
		}

		$shipping_package = new WC_BPost_Shipping_Package(
			$this->product_configuration_factory->get_product_configuration(),
			new WC_BPost_Shipping_Options_Base(),
			$package,
			WC()->cart
		);

		$bpost_delivery_price = filter_input( INPUT_POST, 'bpost_delivery_price', FILTER_VALIDATE_FLOAT );

		if ( ! is_null( $bpost_delivery_price ) && false !== $bpost_delivery_price ) {
			// We will load the delivery price given by the SHM, and stored in session
			$this->add_rate_from_cost( $bpost_delivery_price ); // Euros

			return;
		}

		// We will calculate the minimal delivery price, by using the bpost API
		try {
			$cost = $shipping_package->calculate_shipping() / 100; // Euro-cents to Euros
			$this->add_rate_from_cost( $cost );

		} catch ( WC_BPost_Shipping_Api_Exception_Country_Not_Allowed $ex ) {
			// Bad country ? We don't show the bpost shipping
			$this->logger->warning( __METHOD__ . ': ' . $ex->get_short_name(), array( $shipping_package ) );
			$this->add_rate_when_error();

		} catch ( WC_BPost_Shipping_Api_Exception_Weight_Not_Allowed $ex ) {
			// Bad weight ? We show the bpost shipping but without the price
			$this->logger->warning( __METHOD__ . ': ' . $ex->get_short_name(), array( $shipping_package ) );
			$this->add_rate_when_error();

		} catch ( WC_BPost_Shipping_Api_Exception_No_Price_Found $ex ) {
			// No price found ? We show the bpost shipping but without the price
			$this->logger->warning( __METHOD__ . ': ' . $ex->get_short_name(), array( $shipping_package ) );
			$this->add_rate_when_error();

		} catch ( BpostException $exception ) {
			$this->logger->log_exception( $exception );
			$this->add_rate_when_error();
		}
	}

	/**
	 * @param float $taxed_cost in Euros
	 */
	private function add_rate_from_cost( $taxed_cost ) {
		$ratio = WC_Tax::calc_shipping_tax( 1, WC_Tax::get_shipping_tax_rates() );
		if ( $ratio ) {
			$tax_id       = current( array_keys( $ratio ) );
			$untaxed_cost = $taxed_cost / ( 1 + $ratio[ $tax_id ] );
			$tax          = $taxed_cost - $untaxed_cost;

			$rate = array(
				'cost'  => $untaxed_cost, // Euros
				'taxes' => array( $tax_id => $tax ), // bpost shipping includes already VAT
			);
		} else {
			$rate = array(
				'cost'  => $taxed_cost, // Euros
				'taxes' => 0,
			);

		}

		$rate['id']    = $this->id;
		$rate['label'] = bpost__( 'bpost' );

		// Register the rate
		$this->add_rate( $rate );
	}

	private function add_rate_when_error() {
		$rate = array(
			'id'    => $this->id . '_error',
			'label' => bpost__( 'bpost' ),
			'cost'  => 0,
		);

		// Register the rate
		$this->add_rate( $rate );
	}

	/**
	 * admin_options function.
	 * @access public
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->config_checker->environment_check();

		$this->clean_logs();

		// Add fields following bpost API
		if ( $this->config_checker->is_valid_product_configuration() ) {
			$this->add_countries_fields();
		} else {
			$this->disable_plugin();
		}

		// Show errors, or messages (if there is not error), or nothing (if there is nothing to show)
		\WC_Admin_Settings::show_messages();

		// Show settings
		parent::admin_options();
	}

	/**
	 * build countries accepted as free countries
	 */
	private function add_countries_fields() {
		$bpost_countries = $this->product_configuration_factory->get_product_configuration()->get_bpost_countries();

		$countries     = new WC_Countries();
		$country_names = $countries->get_countries();

		foreach ( $bpost_countries as $bpost_country_iso2 ) {
			if ( ! isset( $this->allowed_countries[ $bpost_country_iso2 ] ) ) {

				$bpost_country_name = isset( $country_names[ $bpost_country_iso2 ] )
					? $country_names[ $bpost_country_iso2 ]
					: $bpost_country_iso2;

				$this->woocommerce_adapter->settings_add_error(
					sprintf(
						bpost__( '%s is set in your bpost Shipping Manager but you did not allow it in your shop' ),
						$bpost_country_name
					)
				);
			}
		}

		foreach ( $this->allowed_countries as $country_iso_2 => $country_name ) {
			if ( ! isset( $bpost_countries[ $country_iso_2 ] ) ) {
				$this->woocommerce_adapter->settings_add_error(
					sprintf(
						bpost__( '%s is not available in your bpost Shipping Manager' ),
						$country_name
					)
				);
			}
		}
	}

	/**
	 * Clean logs for bpost
	 */
	private function clean_logs() {
		if ( $this->get_option( 'logs_clean' ) === 'yes' ) {
			$logger = new WC_Log_Handler_File();
			$logger->clear( BPOST_PLUGIN_ID );

			$this->settings['logs_clean'] = 'no';

			$this->woocommerce_adapter->settings_add_message( bpost__( 'bpost logs had been clean' ) );
		}

	}

	/**
	 * Disable the plugin, with a error message
	 */
	private function disable_plugin() {
		$this->woocommerce_adapter->settings_add_error( bpost__( 'The plugin has been disabled because the connection to the API failed. Do not forget to re-enable it when you set the good credentials' ) );
		$this->settings['enabled'] = 'no';
		$this->save_settings();
	}

	private function init_logger() {
		$this->logger = \WC_BPost_Shipping\WC_Bpost_Shipping_Container::get_logger();
	}

	/**
	 * @param mixed $key
	 * @param mixed $data
	 *
	 * @return string
	 */
	public function generate_title_html( $key, $data ) {
		$title_text = parent::generate_title_html( $key, $data );

		return str_replace( 'form-table', 'form-table bpost', $title_text );
	}

	/**
	 * persist settings of this plugin into database
	 */
	private function save_settings() {
		update_option( $this->plugin_id . $this->id . '_settings', $this->settings );
	}
}
