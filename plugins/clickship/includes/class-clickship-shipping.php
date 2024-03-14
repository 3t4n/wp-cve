<?php
/**
 * Register all actions and filters for the plugin
 *
 * @link       https://clickship.com
 * @since      1.0.0
 *
 * @package    Clickship
 * @subpackage Clickship/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Clickship
 * @subpackage Clickship/includes
 * @author     ClickShip <info@clickship.com>
 */
if ( ! class_exists( 'WC_Clickship_Shipping_Rates_Method' ) ) {
	class WC_Clickship_Shipping_Rates_Method extends WC_Shipping_Method {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	/**
	 * Constructor for your shipping class
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id 		= 'clickship_shipping_rates';
		$this->method_title = __( 'ClickShip', 'clickship' );		

		$this->method_description = __( 'Provide customers with <b>real-time accurate shipping rates</b> at your store checkout. <br/><br/> <b>To get started, integrate your WooCommerce store on ClickShip. Don\'t have a ClickShip account? <a href="https://app.clickship.com/clickship/signup" target="_blank">Create a free account</a> </b> <br/><br/> Need help setting up? <a href="https://www.youtube.com/watch?v=aBEUo5YTsXs" target="_blank">Watch this tutorial</a>' );
	

        $this->init();		
	}
	/**
	 * Init your settings 
	 *
	 * @access public
	 * @return void
	 */
	function init() {
        // Load the settings API
        $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
        $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
        
		$this->clickship_url = CLICKSHIP_URL;
		$this->marketplace_id = $this->get_option('clickship_marketplace_id');
		$this->clickship_enable = $this->get_option('clickship_enable');		
		
        // Save settings in admin if you have any defined
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }
	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields(){
        $this->form_fields = array(
          'clickship_marketplace_id' => array(
              'type'			=> 'text',
              'title'			=> __( 'Marketplace ID', 'clickship' ),
              'description' 	=> __( 'Enter the Marketplace ID. On ClickShip go to Marketplaces > Edit Store Settings.', 'clickship' )
          ),         
		  
		'clickship_enable' => array(
			'type'    		=> 'checkbox',
			'default'		=> 'yes',
			'title' 		=> __( 'ClickShip Shipping Rates', 'clickship' ),
			'label' 		=> __( 'Activate Real-Time Rates at Checkout', 'clickship' ),

		)    
      );	
    }
	//Calculate Shipping
	/**
	 *  @return Formatted URL 
	 */
	function get_formatted_url( $url ) {
		$url = urldecode( $url );
		if ( ! strstr( $url, '://' ) ) {
			$url = 'https://' . $url;
		}
		
		return $url;

	}
	/**
	 * Get Woocommerce Store Address
	 *
	 * @access public
	 * @return array
	 */
	function getOriginAddress(){
		$origin = array();
		$originCountry = explode(':', get_option('woocommerce_default_country'));

		$origin['name'] = sanitize_text_field(get_option('woocommerce_email_from_name'));
		$origin['company_name'] = sanitize_text_field(get_option('woocommerce_email_from_name'));		
		$origin['address1'] = sanitize_text_field(get_option('woocommerce_store_address'));
		$origin['address2'] = sanitize_text_field(get_option( 'woocommerce_store_address_2'));
		$origin['city'] = sanitize_text_field(get_option('woocommerce_store_city'));
		$origin['province'] = sanitize_text_field($originCountry['1']);
		$origin['country'] = sanitize_text_field($originCountry['0']);
		$origin['postal_code'] = sanitize_text_field(get_option('woocommerce_store_postcode'));		
		$origin['phone'] = "";
		$origin['email'] = sanitize_text_field(get_option('woocommerce_email_from_address'));
		return $origin;
	}

	/**
	 * Get Woocommerce Checkout Page Adderess
	 *
	 * @access public
	 * @return array
	 */
	function getDestinationAddress($package){
		$desination = array();
		$current_user = wp_get_current_user();
  	 	$customer_meta = get_user_meta( $current_user->ID );  	 	

		if ( 1 == $current_user->ID ) {
			$desination['address1'] = sanitize_text_field($customer_meta['shipping_address_1'][0]);
			$desination['address2'] = sanitize_text_field($customer_meta['shipping_address_2'][0]);
			$desination['email'] = sanitize_text_field($customer_meta['billing_email'][0]);
			$desination['phone'] = sanitize_text_field($customer_meta['billing_phone'][0]);
			$shipping_first_name_data = sanitize_text_field($customer_meta['shipping_first_name'][0]);
			if(!empty($shipping_first_name_data)){
				$shipping_first_name_result = $shipping_first_name_data.' ';
			}
			$desination['name'] = trim($shipping_first_name_result . sanitize_text_field($customer_meta['shipping_last_name'][0]));
			$desination['company_name'] = sanitize_text_field($customer_meta['shipping_company'][0]);
		} else {			
			$desination['email'] = "";			
			$desination['phone'] = "";
		}

		$desination['city'] = sanitize_text_field($package['destination']['city']);
		$desination['province'] = sanitize_text_field($package['destination']['state']);
		$desination['country'] = sanitize_text_field($package['destination']['country']);
		$desination['postal_code'] = sanitize_text_field($package['destination']['postcode']);		

		return $desination;
	}

	/**
	 * Get Proudct Items 
	 *
	 * @access public
	 * @return array
	 */
	function getItems($package = Array()){
		$items = array();

		foreach ( $package['contents'] as $key => $value ) {
			$item = array(
				'quantity' => $value ['quantity'],
				'product_id' => $value ['product_id'],
				'variant_id' => $value ['variation_id']
			);			
			array_push($items, $item);
		}
		return $items;
	}
	
	/**
	 * Get Proudct Items 
	 *
	 * @access public
	 * @return array
	 * The URL is validated to avoid redirection and request forgery attacks.
	 */
	function post_consumer_data( $consumer_data, $url ) {
		$params = array(
			'body'    => wp_json_encode( $consumer_data ),
			'timeout' => 60,
			'headers' => array(
				'Content-Type' => 'application/json;',
			),
		);
		return wp_safe_remote_post( esc_url_raw( $url ), $params );
	}

	/**
	 * calculate_shipping function.
	 *
	 * @access public
	 * @param mixed $package
	 * @return items,origign and destination address
	 */
	public function calculate_shipping($package = Array()){
		try {
			if ( $this->clickship_enable == 'yes' &&  !empty($this->marketplace_id) ) {
				$url = $this->get_formatted_url($this->clickship_url.$this->marketplace_id);
			
				$input_data = array();
				
				$input_data['items'] = $this->getItems($package);
				$input_data['origin'] = $this->getOriginAddress();
				$input_data['destination'] = $this->getDestinationAddress($package);
			
				$response = $this->post_consumer_data( $input_data, $url );
				$this->process_clickship_service_response($response);
			}
		}catch (\Exception $ex) {
			echo $ex->getMessage();
		}
	}
	
	/**
	 * process Clickship API response function.
	 *
	 * @access public
	 * @param mixed $package
	 * @return Clickship API response
	 */
	function process_clickship_service_response( $json_response ) {		

		if ( 200 == intval( $json_response['response']['code'] ) ) {
			$response = json_decode( $json_response['body'], true );
			//echo '<pre>'; print_r($response); echo '</pre>';
			foreach ($response['data'] as $key => $value) {
				$metadata = array(
					'cs_service_code' => sanitize_text_field($value['service_code']),
					'cs_service_name' => sanitize_text_field($value['service_name'])
				);
				
				$this->serviveName	= sanitize_text_field($value['service_name']);
								
				if($value ['transitDays'] > 0) {
					$this->transitDays	= sanitize_text_field($value['transitDays']) + 1;
					$this->serviveName	= sprintf( __('%s (%s business days)','clickship'), sanitize_text_field($value['service_name']), sanitize_text_field($this->transitDays) );
				}

				$rate = array(
					'id' 	=> sanitize_text_field($value['service_code']),
					'label' => __($this->serviveName,'clickship'),
					'cost' 	=> sanitize_text_field($value['total_price']).' '.sanitize_text_field($value['currency']),
					'meta_data' => $metadata
				);
				
				$this->add_rate($rate);
			}
		} else {
			$messageType = "error";
			$message = sprintf( __( 'Unknown error while fetching shipping rates, Please contact support team', 'clickship' ));
			wc_add_notice( $message, $messageType );
		}
	}
	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}
	
}
}