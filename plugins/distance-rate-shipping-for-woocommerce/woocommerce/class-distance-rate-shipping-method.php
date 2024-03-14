<?php
/**
 * Distance_Rate_Shipping_Method
 * This class defines all the required code for plugin configuration.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/woocommerce
 * @author tusharknovator
 * @since 1.0.0
 */
if (class_exists('Distance_Rate_Shipping_Method')) {
    return;
}
class Distance_Rate_Shipping_Method extends WC_Shipping_Method{

    /**
     * Store the name of the plugin
     * @access private
     * @var string $plugin_name
     */
    private $plugin_name;

    /**
     * Store the version of the plugin
     * @access private
     * @var string $plugin_version
     */
    private $plugin_version;

    /**
     * Store the config instance of the plugin
     * @access private
     * @var string $plugin_config
     */
    private $plugin_config;

    /**
     * Store the reference of the plugin's shipping method id
     * @access public
     * @var string $method_id
     */
    public $method_id;

    /**
     * Store the reference of the plugin's shipping method title
     * @access public
     * @var string $method_title
     */
    public $method_title;

    /**
     * Store the reference of the plugin's shipping method description
     * @access public
     * @var string $method_description
     */
    public $method_description;

    /**
     * __constructor function
     * To initiate class variables.
     * It run on object creation of class.
     * @access public
     * @return void
     * @param int $instance_id
     * @since 1.0.0
     */
    public function __construct( $instance_id = 0){
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-distance-rate-shipping-config.php';
        $plugin_config = new Distance_Rate_Shipping_Config();
        $this->plugin_name = $plugin_config->get_plugin_name();
        $this->plugin_version = $plugin_config->get_plugin_version();
        $this->plugin_config = $plugin_config;
        $this->prefix = str_replace('-', '_', $this->plugin_name);

        // set shipping method properties
        $this->id = $this->plugin_config->get_shipping_method_id();
        $this->title = __($this->plugin_config->get_shipping_method_title(), $this->plugin_config->get_text_domain() );
        $this->instance_id = absint($instance_id);
        $this->method_title = __( $this->plugin_config->get_shipping_method_title(), $this->plugin_config->get_text_domain()  );
        $this->method_description = __( $this->plugin_config->get_shipping_method_description(), $this->plugin_config->get_text_domain()  );
        $this->supports = $this->plugin_config->get_shipping_method_supports();

        // initialize form fields
        $this->init();

        // call to action hook to process and save shipping method settings
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }
    /**
     * init function
     * To initiate method in WordPress.
     * It is called on object creation of class.
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function init(){

        $this->instance_form_fields = include __DIR__ . '/includes/settings-distance-rate-shipping.php';   
        
        $this->title                    = $this->get_option('title');
        $this->min_shipping_distance    = $this->get_option('min_shipping_distance');
        $this->max_shipping_distance    = $this->get_option('max_shipping_distance');
        $this->min_order_amount         = $this->get_option('min_order_amount');
        $this->max_order_amount         = $this->get_option('max_order_amount');
        $this->min_order_qty            = $this->get_option('min_order_qty');
        $this->max_order_qty            = $this->get_option('max_order_qty');
        $this->price_per_distance       = $this->get_option('price_per_distance');
		$this->tax_status               = $this->get_option('tax_status');
        $this->type                     = $this->get_option( 'type', 'class' );
    }

    /**
     * init_method function
     * To initiate method form fields in WordPress.
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function calculate_shipping($package = array()){
        global $woocommerce;

        $rate  = array(
            'id' => $this->get_rate_id(),
            'label' => $this->title,
            'cost' => 0,
            'package' => $package,
            'calc_tax' => 'per_order',
        );
        $has_costs = false;

        $shipping_zone = array();
        $shipping_methods = array();
        if(class_exists('WC_Shipping_Zones')){
            $shipping_zone = WC_Shipping_Zones::get_zone_matching_package($package);
            $shipping_methods = $shipping_zone->get_shipping_methods();
        }

        $distance = $this->get_distance($package);

        $cost = $this->calculate_cost($distance, $shipping_methods, $package);
        
        if ( '' !== $cost ) {
			$has_costs    = true;
			$rate['cost'] = $this->evaluate_cost(
				$cost,
				array(
					'qty'  => $this->get_package_item_qty( $package ),
					'cost' => $package['contents_cost'],
				)
			);
		}

        // Add shipping class costs.
		$shipping_classes = $woocommerce->shipping()->get_shipping_classes();
        
        if ( ! empty( $shipping_classes ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$highest_class_cost     = 0;

			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids.
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

				if ( '' === $class_cost_string ) {
					continue;
				}

				$has_costs  = true;
				$class_cost = $this->evaluate_cost(
					$class_cost_string,
					array(
						'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
						'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
					)
				);

				if ( 'class' === $this->type ) {
					$rate['cost'] += $class_cost;
				} else {
					$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
			}

			if ( 'order' === $this->type && $highest_class_cost ) {
				$rate['cost'] += $highest_class_cost;
			}
		}
        $this->add_rate( $rate );
    }
    
    /**
     * sanitize_string_field function
     * To sanitize and validate form field for string value.
     * @access public
     * @param string $value
     * @return string
     * @since 1.0.0
     */
    public function sanitize_string_field($value){
        $value = (is_null( $value ) || !is_string( $value ) ) ? '' : $value;
        return $value;
    }

    /**
     * sanitize_number_field function
     * To sanitize and validate form field for number value
     * @access public
     * @param string $value
     * @return string
     * @since 1.0.0
     */
    public function sanitize_number_field($value){
        $value = (is_null( $value ) || !is_numeric( $value ) ) ? '' : $value;
        return $value;
    }
    
    /**
     * sanitize_cost function
     * To sanitize and validate form field for cost/price value
     * @access public
     * @param string $value Unsanitized value.
	 * @throws Exception Last error triggered.
	 * @return string
     */
	public function sanitize_cost( $value ) {
		$value = is_null( $value ) ? '' : $value;
		$value = wp_kses_post( trim( wp_unslash( $value ) ) );
		$value = str_replace( array( get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ) ), '', $value );
		// Thrown an error on the front end if the evaluate_cost will fail.
		$dummy_cost = $this->evaluate_cost(
			$value,
			array(
				'cost' => 1,
				'qty'  => 1,
			)
		);
		if ( false === $dummy_cost ) {
			throw new Exception( WC_Eval_Math::$last_error );
		}
		return $value;
	}
    /**
     * get_destination_address function
     * To retrieve customer's shipping address from WooCommerce.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_destination_address($package){
        global $woocommerce;
        $address = array();

        if(!is_user_logged_in() && !empty($package['destination'])){
            $address = $package['destination'];
        }
        else{
            $user = wp_get_current_user();
            $customer = new WC_Customer($user->ID);
            $address['address_1'] = $customer->get_shipping_address_1();
            $address['address_2'] = $customer->get_shipping_address_2();
            $address['city'] = $customer->get_shipping_city();
            $address['state'] = $customer->get_shipping_state();
            $address['country'] = $customer->get_shipping_country();
            $address['postcode'] = $customer->get_shipping_postcode();
        }

        $address = $this->format_address($address);
        return $address;
    }

    /**
     * get_origin_address function
     * To retrieve shop physical address from WooCommerce.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_origin_address(){
        global $woocommerce;
        
        $address = array();
        $address['address_1'] = $woocommerce->countries->get_base_address();
        $address['address_2'] = $woocommerce->countries->get_base_address_2();
        $address['city'] = $woocommerce->countries->get_base_city();
        $address['state'] = $woocommerce->countries->get_base_state();
        $address['country'] = $woocommerce->countries->get_base_country();
        $address['postcode'] = $woocommerce->countries->get_base_postcode();

        $address = $this->format_address($address);

        return $address;
    }
    
    /**
     * format_address function
     * to format address into string format.
     * @access public
     * @param array $address
     * @return string
     * @since 1.0.0
     */
    public function format_address($address){
        $formatted_address = "";
        // $formatted_address .= $this->is_empty_address_string($address['address']);
        $formatted_address .= $this->is_empty_address_string($address['address_1']);
        $formatted_address .= $this->is_empty_address_string($address['address_2']);
        $formatted_address .= $this->is_empty_address_string($address['city']);
        $formatted_address .= $this->is_empty_address_string($address['state']);
        $formatted_address .= $this->is_empty_address_string($address['country']);
        $formatted_address .= $this->is_empty_address_string($address['postcode']);
        $formatted_address = substr($formatted_address, 0, strlen($formatted_address)-1);
        return $formatted_address;
    }
    
    /**
     * is_empty_address_string function
     * check if address string is not empty and append "," to it.
     * @access public
     * @param array $str
     * @return string
     * @since 1.0.0
     */
    public function is_empty_address_string($str=null){
        if(!empty($str)){
            return $str . ',';
        }
        return "";
    }

    /**
     * get_distance function
     * Using google's distance matrix API calculate distance between destination address and origin address.
     * @access public
     * @param string $format
     * @param string $destination
     * @param string $origin
     * @param string $apikey
     * @return float
     * @since 1.0.0
     */
    public function get_distance($package){
        $format = "json";
        $destination = $this->get_destination_address($package);
        $origin = $this->get_origin_address();
        $apikey = $this->plugin_config->get_gdm_apikey();
        $endpoint = sprintf(
            'https://maps.googleapis.com/maps/api/distancematrix/%1$s?destinations=%2$s&origins=%3$s&key=%4$s',
            $format,
            $destination,
            $origin,
            $apikey
        );
        
        $response = wp_remote_get($endpoint);
        $response_body = wp_remote_retrieve_body( $response );
        $result = json_decode($response_body);
        
        if(!empty($result)){   
            if($result->status === "OK"){
                $distance = $result->rows[0]->elements[0]->distance->value;
                $distance = $this->format_distance($distance);
                return $distance;
            }
        }
        return -1;
    }

    /**
     * format_distance function
     * fortmat distance based on selected measurement standards.
     * @access public
     * @param float $value
     * @return float
     * @since 1.0.0 
     */
    public function format_distance($value){
        $measurement_standard = $this->plugin_config->get_measurement_standard();
        
        if($measurement_standard === 'mi'){
            $value = round(($value*0.000621), 2);
        }
        else{
            $value = round(($value/1000), 2);
        }
        return $value;
    }
    
    /**
     * calculate_cost function
     * based on distance and shipping method rules calculate shipping cost.
     * @access public
     * @param float $value
     * @return float
     * @since 1.0.0
     */
    public function calculate_cost($distance, $shipping_methods, $package){
        global $woocommerce;
        $cart = $woocommerce->cart;
        $cart_contents_total = $cart->get_cart_contents_total();
        $shipping_rate = 0;
        $chosen_method = "";
        $chosen_method_instance = "";
        
        foreach($shipping_methods as $method){
            if($method->id === $this->plugin_config->get_shipping_method_id()
                && round($distance) >= $method->get_option('min_shipping_distance')
                && round($distance) <= $method->get_option('max_shipping_distance')
                && round($cart_contents_total) >= $method->get_option('min_order_amount')
                && round($cart_contents_total) <= $method->get_option('max_order_amount')
                && $cart->get_cart_contents_count() >= $method->get_option('min_order_qty')
                && $cart->get_cart_contents_count() <= $method->get_option('max_order_qty')
            ){
                $shipping_rate = $method->get_option('price_per_distance');
                $chosen_method = $method->id;
                $chosen_method_instance = $method->instance_id;
            }
        }
        $cost = floatval($shipping_rate) * floatval($distance);
        $cost = round($cost, 2);
        $_SESSION['chosen_method'] = $chosen_method;
        $_SESSION['chosen_method_instance'] = $chosen_method_instance;
        return $cost;
    }

    /**
     * find_shipping_classes function
     * find shipping classes applied to order based package.
     * @access public
     * @param mixed $package
     * @return array
     * @since 1.0.0
     */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}
    /**
     * find_shipping_classes function
     * Evaluate a cost from a sum/string.
     * @access protected
     * @param string $sum
     * @param array $args
     * @return string
     * @since 1.0.0
     */
	protected function evaluate_cost( $sum, $args = array() ) {
		// Add warning for subclasses.
		if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
			wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
		}

		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
		$this->fee_cost = $args['cost'];

		// Expand shortcodes.
		add_shortcode( 'fee', array( $this, 'fee' ) );

		$sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			)
		);

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		// Remove whitespace from string.
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string.
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters.
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Do the math.
		return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
	}

    /**
     * get_package_item_qty function
     * Get count of items in package.
     * @access public
     * @param array $package
     * @return int
     * @since 1.0.0
     */
	public function get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}
}