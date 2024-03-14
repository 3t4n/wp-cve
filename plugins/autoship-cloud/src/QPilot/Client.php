<?php

/**
 * The Main QPilot API Class
 */
class QPilotClient {

  /**
   * The Base URL for the API.
   * @var string
   */
	private $_api_url;

  /**
   * Stores the API Endpoint URLs.
   * @var string
   */
  private $_api_paths;

  /**
   * The Authentication Token for the API.
   * @var string
   */
	private $_token_auth;

  /**
   * The QPilot User ID
   * @var int
   */
	private $_user_id;

  /**
   * The QPilot Site ID
   * @var int
   */
	private $_site_id;

  /**
   * The timeout for the Calls.
   * @var int
   */
	private $_timeout = 20;

  /**
   * The Source of the Call
   * @var string
   */
	private $_source;

  /**
   * Customer Error Notice Code
   * Any Error with this HTTP Code should be Shown to Customers
   * @var int
   */
	public static $customer_http_code = 606;


  /**
   * The timeout for the Calls.
   */
	public function __construct( $token_auth = NULL, $user_id = NULL, $site_id = NULL, $api_url = NULL, $source = NULL ) {

    // Get site settings if Not supplied ( token, site id, user id, API Url )
    $this->_token_auth   = isset( $token_auth ) ? $token_auth       : $this->get_default( 'token_auth' );
    $this->_site_id      = isset( $site_id ) ? $site_id             : $this->get_default( 'site_id' );
    $this->_user_id      = isset( $user_id ) ? $user_id             : $this->get_default( 'user_id' );
    $this->_source       = isset( $source ) ? $source               : 'Merchant';

    // Trim any trailing forward or backward slashes before adding a back slash.
    $this->_api_url      = rtrim( isset( $api_url ) ? $api_url : $this->get_default( 'api_url' ), '/\\' ) . '/';

    // Now build any dynamic endpoint paths
    $this->init_endpoints();

  }

  /*
  * Endpoint Settings & Utilities
  //////////////////////////////////////*/

	/**
	 * Allows for Filtering of Values
   *
   * @param string $filter The filter name being applied.
   * @param MIXED $value The value to filter
   * @param array $args An optional array of additional args
   *
   * @return MIXED The filtered results
	 */
  private function apply_filters( $filter, $value, $args = array() ){

    if ( function_exists( 'apply_filters' ) )
    $value = apply_filters( $filter, $value, $args );

    return $value;

  }

	/**
	 * Allows for Action to be fired
   *
   * @param string $action The action name that should fire.
   * @param array $args An optional array of additional args to pass to action
   *
	 */
  private function do_action( $action, $args ){

    if ( function_exists( 'do_action' ) )
    do_action( $action, $args );

  }


	/**
	 * Retrieves the default override if set as a constant.
   * @return MIXED|NULL NULL if constant not found.
	 */
  private function get_default_override( $setting ){

    // Get the constant name to look for.
    $constant = 'QPILOT_' . strtoupper( $setting );

    // Return the constants value if it's defined.
    return defined( $constant ) ? constant( $constant ) : NULL;

  }

	/**
	 * Sets up the API endpoints.
	 */
  private function init_endpoints( $route = NULL, $subroute = NULL ){

    if ( isset( $route ) && is_array( $route ) ){
      $routes   = $route;
      $route    = $routes[0];
      $subroute = $routes[1];
    }

    $this->_api_paths = array(
      'oauth2'                => "oauth/token",
      'generate_token'        => "Sites/{$this->_site_id}/AccessTokens/Generate",
      'users'                 => "Users/{$route}",
      'sites'                 => "Sites/{$route}",
      'sites_metadata'        => "Sites/{$this->_site_id}/Metadata",
			'migrate_processing'    => "Sites/{$this->_site_id}/MigrateV3",
      'integration_check'     => "Sites/{$this->_site_id}/Integration/Check",
      'integration_status'    => "Sites/{$this->_site_id}/IntegrationStatus",
			'customer_metrics'    	=> "Sites/{$this->_site_id}/CustomerMetrics",
			'customer_summaries'	 	=> "Sites/{$this->_site_id}/Customers/Summaries",
      'customers'             => "Sites/{$this->_site_id}/Customers/{$route}",
      'upsert_customer'       => "Sites/{$this->_site_id}/Customers/Upsert/",
      'payment_methods'       => "Sites/{$this->_site_id}/Customers/{$route}/PaymentMethods",
      'orders'                => "Sites/{$this->_site_id}/ScheduledOrders/{$route}",
      'items'                 => "Sites/{$this->_site_id}/ScheduledOrderItems/{$route}",
      'orders_upsert'         => "Sites/{$this->_site_id}/ScheduledOrders/upsert",
      'customer_orders'       => "Sites/{$this->_site_id}/Customers/{$route}/ScheduledOrders/",
      'next_order'            => "Sites/{$this->_site_id}/ScheduledOrders/GetNextScheduledOrder",
      'addto_next_order'      => "Sites/{$this->_site_id}/ScheduledOrders/AddItemsToNextScheduledOrder",
      'generate_next_occur'   => "Sites/{$this->_site_id}/ScheduledOrders/NextOccurrenceUtc",
      'products'              => "Sites/{$this->_site_id}/Products/{$route}",
      'upsert_product'        => "Sites/{$this->_site_id}/Products/Upsert/",
      'product_summaries'     => "Sites/{$this->_site_id}/Products/Summaries",
      'coupons'               => "Sites/{$this->_site_id}/Coupons/{$route}",
      'get_coupon_by_code'    => "Sites/{$this->_site_id}/Coupons/ByCode",
      'validate_coupons'      => "Sites/{$this->_site_id}/Coupons/ValidateCoupons",
      'payment_method'        => "Sites/{$this->_site_id}/PaymentMethods/{$route}",
      'upsert_payment_method' => "Sites/{$this->_site_id}/PaymentMethods/Upsert",
      'payment_integrations'  => "Sites/{$this->_site_id}/PaymentIntegrations",
      'update_frequency'      => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Frequency",
      'update_occurrence'     => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/NextOccurrenceUtc",
      'update_active'         => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Active",
      'update_locked'         => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Locked",
      'update_processing'     => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Processing",
      'update_pending'        => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Pending",
      'update_failed'         => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Failed",
      'update_paused'         => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Paused",
      'update_deleted'        => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/Status/Deleted",
      'update_payment_method' => "Sites/{$this->_site_id}/ScheduledOrders/{$route}/PaymentMethod",
      'update_stock'          => "Sites/{$this->_site_id}/Products/{$route}/Availability",
      'update_stockquantity'  => "Sites/{$this->_site_id}/Products/{$route}/StockQuantity/{$subroute}",
      'update_availability'   => "Sites/{$this->_site_id}/Products/{$route}/ScheduledOrderAvailability",
      'batch_deactivate'      => "Sites/{$this->_site_id}/Products/BatchActivateDeactivate",
      'product_group'         => "Sites/{$this->_site_id}/ProductGroups/{$route}",
      'site_integrations'      => "SiteIntegrations",
    );

  }

	/**
	 * Return the requested Endpoint for the supplied param
	 * @return string $endpoint The endpoint being hit
	 * @return mixed $route An optional route on that endpoint
	 * @return array $params Additional Query Params to add to the endpoint
	 * @return string|false
	 */
  public function endpoint( $endpoint, $route = NULL, $params = array() ){

    // Dynamically update the endpoints with the route.
    if ( isset( $route ) ){
      $this->init_endpoints( $route );
    } else {
      $this->init_endpoints();
    }

    // grab the endpoint
    $endpoint = isset( $this->_api_paths[$endpoint] ) ? $this->_api_paths[$endpoint] : NULL;

    if ( empty( $endpoint ) )
    return false;

    // Finally attach any query args
    return !empty( $params ) ? $endpoint . '?' . http_build_query( $params ) : $endpoint;

  }

	/**
	 * Returns the value for the requested property
   * @return MIXED
   */
  public function get_default( $property ){

    // Check for an existing function to retrieve the value
    $function = 'autoship_get_' . $property;
    if ( function_exists( $function ) )
    return $function();

    // Allow for developers to set value as a constant for use outside of Autoship Cloud Plugin.
    $val = $this->get_default_override( $property );

    return $val;

  }

	/**
	 * Returns the value for the requested property
   * @param string $source The string representing the origin of the call
   */
  public function set_source( $source ){
    $this->_source = $source;
  }

  /*
  * Oauth API Calls
  //////////////////////////////////////*/

  /**
   * Generates the Oauth token
   * @param string $code The returned Oauth code.
   * @return stdClass Token Response.
   */
  public function oauth2( $code ){

    $data = array(
      'code'          => $code,
      'client_secret' => $this->get_default( 'client_secret' ),
      'client_id'     => $this->get_default( 'client_id' ),
      'grant_type'    => 'authorization_code',
      'redirect_uri'  => $this->get_default( 'redirect_uri' )
    );

		return $this->post( $this->endpoint( 'oauth2', NULL, $data ) );

  }

  /**
   * Refreshes the Oauth token
   * @return stdClass Token Response.
   */
  public function refresh_oauth2(){

    $data = array(
      'refresh_token' => $this->get_default( 'refresh_token' ),
      'client_secret' => $this->get_default( 'client_secret' ),
      'client_id'     => $this->get_default( 'client_id' ),
      'grant_type'    => 'refresh_token',
    );

		return $this->post( $this->endpoint( 'oauth2' ), NULL, $data );

  }

	/**
	 * Return the current Authentication Token
	 * @return string
	 */
	public function get_token_auth() {
		return $this->_token_auth;
	}

	/**
	 * Sets the current Authentication Token
	 * @param string
	 */
	public function set_token_auth( $token_auth ) {
		$this->_token_auth = $token_auth;
	}

  /*
  * User Get | Set | API Calls
  //////////////////////////////////////*/

	/**
	 * Return the current User ID
	 * @return int
	 */
	public function get_user_id() {
		return $this->_user_id;
	}

  /**
   * Sets the current User ID
   * @param int
   */
	public function set_user_id( $user_id ) {
		$this->_user_id = $user_id;
	}

	/**
	 * Return the current Default QPilot User object for the API Connection
	 * @return stdClass
	 */
	public function get_default_user() {
		return $this->get_user( $this->_user_id );
	}

  /**
   * Retrieves a user object from the API
   * @param int $user_id The id for the user to retrieve
   * @return stdClass
   */
	public function get_user( $user_id ) {
		return $this->get( $this->endpoint( 'users', $user_id ) );
	}

  /*
  * Site Get | Set | API Calls
  //////////////////////////////////////*/

	/**
	 * Return the current Site ID
	 * @return int
	 */
	public function get_site_id() {
		return $this->_site_id;
	}

  /**
   * Sets the current Site ID
   * @param int
   */
	public function set_site_id( $site_id ) {
		$this->_site_id = $site_id;
	}

  /**
   * Retrieves a site object from the API
 	 * Return the current Default QPilot Site object for the API Connection
 	 * @return stdClass
   */
	public function get_default_site() {
		return $this->get_site( $this->_site_id );
	}

  /**
   * Retrieves a site object from the API
   * @param int $site_id The id for the site to retrieve
   * @return stdClass
   */
	public function get_site( $site_id ) {
		return $this->get( $this->endpoint( 'sites', $site_id ) );
	}

  /**
   * Creates a new Site in QPilot
   * @param string $key The API Consumer Key
   * @param string $secret The API Consumer Secret
   */
  public function create_site( $key, $secret ){

    $data = array(
      'userId'  => $this->get_default( 'user_id' ),
      'apiKey1' => $key,
      'apiKey2' => $secret
    );

    // Retrieve the site info to generate the site.
    $site_info = $this->get_default( 'site_info' );

    if ( !empty( $site_info ) && is_array( $site_info ) )
    $data = array_merge( $site_info, $data );

		return $this->post( $this->endpoint( 'sites' ), $data );

  }

  /**
   * Updates a Site in QPilot
   * @param array $data The data to update
   */
  public function update_site_metadata( $data ){
  	return $this->put( $this->endpoint( 'sites_metadata' ), $data );
  }

  /**
   * Retrieves the current site object from the API
   * @return stdClass
   */
  public function get_settings(){
		return $this->get_site( $this->_site_id );
  }

  /*
  * Get Scheduled Order API Calls
  //////////////////////////////////////*/

  /**
   * Retrieves the scheduled orders for a site from the API for a Customer
   * @param int $customer_id The Customer id to retrieve orders for
   * @return array An array of one or more stdClass objects
   */
	public function get_customer_orders( $customer_id ) {
		return $this->get( $this->endpoint( 'customer_orders', $customer_id ) );
	}

  /**
   * Retrieves the scheduled orders for a site from the API
   * @param int $customer_id Optional. The Customer id to retrieve orders for
   * @param array $params {
   *     Optional. An array of search parameters.
   *
   *     @type int     $page                 The search results page to return. Default 1
   *     @type int     $pageSize             The default page size.  Default 100
   *     @type string  $orderBy              A product property to sort the results by
   *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
   *     @type array   $statusNames          Array of Status names to search for.
   *     @type array   $metadataKey	         Array of Order Metadata keys to search for.
   *     @type array   $metadataValue	       Array of Order Metadata Values to search for.
   *     @type string  $search               A query string to search for.
   * }
   * @return array An array of one or more stdClass objects
   */
	public function get_orders( $customer_id = NULL, $params = array() ) {

    $params = array_merge( array( 'page' => 1, 'pageSize' => 100 ), $params );

    if ( isset( $customer_id ) && $customer_id )
    $params['customerId'] = $customer_id;

		return $this->get( $this->endpoint( 'orders', NULL, $params ) );

	}

  /**
   * Retrieves a scheduled order from the API
   * @param int $order_id The id for the order to retrieve
   * @return stdClass The order object
   */
	public function get_order( $order_id ) {
		return $this->get( $this->endpoint( 'orders', $order_id ) );
	}

  /**
   * Gets the next scheduled order for a customer.
   * @param int       $customer_id     An Autoship customer id.
   * @param int       $frequency       Optional. The Frequency to Match
   * @param int       $frequency_type  Optional. The Frequency Type to Match
	 * @param string    $status          Optional. The Status to Match
   *
   * @return stdClass The next scheduledOrder object.
   */
  public function get_next_scheduled_order( $customer_id, $frequency = NULL, $frequency_type = NULL, $status = NULL ) {

    // Setup the Query params
    $filter = array( 'customerId' => $customer_id );

    // Check for a Freq filter
    if ( isset( $frequency ) )
    $filter['frequency'] = $frequency;

    // Check for a Freq type filter
    if ( isset( $frequency ) )
    $filter['frequencyType'] = $frequency_type;

    // Check for a Status filter
    if ( isset( $status ) )
    $filter['status'] = $status;

		return $this->get( $this->endpoint( 'next_order', NULL, $filter ) );

	}

  /*
  * Create Scheduled Order API Calls
  //////////////////////////////////////*/

  /**
   * Creates a Scheduled Order.
   * Defaults to Monthly frequency if not supplied.
   *
   * @param int       $customer_id    An Autoship customer id.
   * @param array     $data {
   *      Optional. The scheduled order data.
   *
   *      @type string $name
   *      @type string $nextOccurrenceUtc
   *      @type string $nextOccurrenceOffset
   *      @type int 	 $utcOffset
   *      @type string $status ( 'Processing', 'Success', 'Failed', 'Retry', 'Active', 'Paused', 'Deleted', 'Locked', 'Queued']
   *      @type string $frequencyType ( 'Days', 'Weeks', 'Months', 'DayOfTheWeek', 'DayOfTheMonth' )
   *      @type string $frequencyDisplayName
   *      @type int $frequency
   *      @type int $paymentMethodId
   *      @type bool $authorizeOnly
   *      @type string $currencyIso
   *      @type float $subtotal
   *      @type float $shippingTotal
   *      @type float $taxTotal
   *      @type float $total
   *      @type string $shippingRateName
   *      @type string $shippingFirstName
   *      @type string $shippingLastName
   *      @type string $shippingStreet1
   *      @type string $shippingStreet2
   *      @type string $shippingCity
   *      @type string $shippingState
   *      @type string $shippingPostcode
   *      @type string $shippingCountry
   *      @type string $phoneNumber
   *      @type string $company
   *      @type string $originalExternalId
   *      @type string $note
   *      @type array $scheduledOrderItems {
   *          Optional. An array of scheduled order item arrays. Each array is a order item.
   *          array {
   *            @type int     $productId            The Product ID
   *            @type float   $price                The item price. Default if not supplied pulled from Product
   *            @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
   *            @type float   $originalSalePrice    The original price stored for historical purposes
   *            @type int     $quantity             The quantity for the line item. Default 1
   *            @type int     $cycles               The current cycle count for the item. Default 0
   *            @type int     $minCycles            The min Cycle value for the item. Default 0
   *            @type int     $manCycles            The max Cycle value for the item. Default 0
   *            @type array   $metadata             An array of metadata key => value pairs to attach to the item
   *          }
   *      }
   *      @type string $estimatedDeliveryDate
   *      @type string $origin ( 'Merchant', 'CustomerCheckout', 'CustomerApi' ),
   *      @type array $coupons {
   *          Optional. An array of applied coupon codes
   *          @type string $coupon_code
   *      }
   *      @type array $metadata An array of Key Value pairs
   *
   * }
   *
   * @return stdClass The next scheduledOrder object.
   */
	public function create_order( $customer_id, $data = array() ) {
    $order = array_merge( array( 'customerId' => $customer_id, 'frequency' => 1, 'frequencyType' => "Months", 'utcOffset' => 0 ), $data );
    return $this->post( $this->endpoint( 'orders' ), $order );
	}

  /**
   * Creates / updates a Scheduled Order.
   * Defaults to Monthly frequency if not supplied.  If the order exists and a param is not supplied
   * the existing value will be cleared.
   *
   * @param int       $customer_id    An Autoship customer id.
   * @param array     $data {
   *      Optional. The scheduled order data.
   *
   *      @type string $name
   *      @type string $nextOccurrenceUtc
   *      @type string $nextOccurrenceOffset
   *      @type int 	 $utcOffset
   *      @type string $status ( 'Processing', 'Success', 'Failed', 'Retry', 'Active', 'Paused', 'Deleted', 'Locked', 'Queued']
   *      @type string $frequencyType ( 'Days', 'Weeks', 'Months', 'DayOfTheWeek', 'DayOfTheMonth' )
   *      @type string $frequencyDisplayName
   *      @type int $frequency
   *      @type int $paymentMethodId
   *      @type bool $authorizeOnly
   *      @type string $currencyIso
   *      @type float $subtotal
   *      @type float $shippingTotal
   *      @type float $taxTotal
   *      @type float $total
   *      @type string $shippingRateName
   *      @type string $shippingFirstName
   *      @type string $shippingLastName
   *      @type string $shippingStreet1
   *      @type string $shippingStreet2
   *      @type string $shippingCity
   *      @type string $shippingState
   *      @type string $shippingPostcode
   *      @type string $shippingCountry
   *      @type string $phoneNumber
   *      @type string $company
   *      @type string $originalExternalId
   *      @type string $note
   *      @type array $scheduledOrderItems {
   *          Optional. An array of scheduled order item arrays. Each array is a order item.
   *          array {
   *            @type int     $productId            The Product ID
   *            @type float   $price                The item price. Default if not supplied pulled from Product
   *            @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
   *            @type float   $originalSalePrice    The original price stored for historical purposes
   *            @type int     $quantity             The quantity for the line item. Default 1
   *            @type int     $cycles               The current cycle count for the item. Default 0
   *            @type int     $minCycles            The min Cycle value for the item. Default 0
   *            @type int     $manCycles            The max Cycle value for the item. Default 0
   *            @type array   $metadata             An array of metadata key => value pairs to attach to the item
   *          }
   *      }
   *      @type string $estimatedDeliveryDate
   *      @type string $origin ( 'Merchant', 'CustomerCheckout', 'CustomerApi' ),
   *      @type array $coupons {
   *          Optional. An array of applied coupon codes
   *          @type string $coupon_code
   *      }
   *      @type array $metadata An array of Key Value pairs
   *
   * }
   *
   * @return stdClass The upserted scheduledOrder object.
   */
	public function upsert_order( $customer_id, $data = array() ) {

		$order = array_merge( array( 'customerId' => $customer_id, 'frequency' => 1, 'frequencyType' => "Months", 'utcOffset' => 0 ), $data );
    return $this->post( $this->endpoint( 'orders_upsert' ), $order );
	}

  /**
   * Deletes a Scheduled Order.
   * @param int  $order_id  The order to delete.
   * @return bool True on success
   */
  public function delete_order( $order_id ) {
    return $this->delete( $this->endpoint( 'orders', $order_id ) );
  }

  /*
  * Coupon API Calls
  //////////////////////////////////////*/

  /**
   * Retrieves the coupons for a site
   * @param int    $page   Optional. The results page, by default the API returns groups of 100.
   * @param string $search Optional. A string to search for.
   * @return array An array of coupon stdClass objects.
   */
  public function get_coupons( $page = NULL, $search = NULL ){
		return $this->get( $this->endpoint( 'coupons', NULL, array( 'page' => $page, 'search' => $search ) ) );
  }

  /**
   * Retrieves the coupons for a site
   *
   * @param string $code The code to search for.
   * @return stdClass The Coupon object
   */
  public function get_coupon_by_code( $code ){
		return $this->get( $this->endpoint( 'get_coupon_by_code', NULL, array( 'code' => $code ) ) );
  }


  /**
   * Retrieves a coupon from the API
   * @param int $coupon_id The id for the coupon to retrieve
   * @return stdClass The coupon object
   */
  public function get_coupon( $coupon_id ) {
    return $this->get( $this->endpoint( 'coupons', $coupon_id ) );
  }

  /**
   * Creates a coupon in QPilot
   *
   * @param array $coupon {
   *      @type string $code                          The coupon code
   *      @type string $name                          The coupon name
   *      @type string $country                       Required Shipping Country for this coupon to be valid
   *      @type string $postcode                      Required Shipping Postcode for this coupon to be valid
   *      @type string $state                         Required Shipping State for this coupon to be valid
   *      @type string $city                          Required Shipping City for this coupon to be valid
   *      @type int $minUnits                         Min # units required for this coupon to be valid
   *      @type int $maxUnits                         Max # units required for this coupon to be valid
   *      @type int $minWeight                        Min weight required for this coupon to be valid
   *      @type int $maxWeight                        Max weight required for this coupon to be valid
   *      @type string $weightUnitType                The required WeightUnitType for this coupon to be valid
   *                                                  (i.e.'Pound', 'Ounce', 'Kilogram', 'Gram' )
   *      @type int $cycles                           Total Cycles this coupon will apply
   *      @type int $maxCyclesPerSite                 Max Scheduled Order cycles this coupon can be used across a site
   *      @type float $minSubtotal                    Min subtotal required for this coupon
   *      @type float $maxSubtotal                    Max subtotal required for this coupon
   *      @type int $maxCyclesPerCustomer             Max Scheduled Order cycles this coupon can be used by a customer
   *      @type int $maxAssignmentsPerCustomer        Max number of times this coupon can be used by a customer
   *      @type int $maxAssignmentsPerSite            Max number of times this coupon can be used across a site
   *      @type int $minScheduledOrderCycles          Required min cycles for a Scheduled Order for coupon to be valid
   *      @type int $maxScheduledOrderCycles          Required max cycles for a Scheduled Order for coupon to be valid
   *      @type int $maxCyclesPerScheduledOrder       Max Scheduled Order cycles a coupon will be valid
   *      @type float $maxDiscountPerCustomer         Max discount $ allowed for a single customer
   *      @type float $amount                         The discount $ amount
   *      @type string $discountType                  The Coupon Type ( i.e. 'None', 'ReduceSubtotalByPercentage',
   *                                                  'ReduceSubtotalByAmount', 'ReduceShippingByPercentage',
   *                                                  'ReduceShippingByAmount', 'SetShippingToAmount' )
   *      @type float $maxPercentageDiscount          Sets the maximum amount that can be discounted by the percentage discount
   *      @type string $expirationDate                The coupon expiration date ( i.e. 2019-12-10T13:06:10.929Z )
   *      @type bool $isStackable                     True if the coupon can be used with other discounts.
   *      @type bool $active                          True if the coupon is active
   * }
   * @return stdClass The created coupon object.
   */
  public function create_coupon( $coupon ){
    $coupon['siteId'] = $this->_site_id;
		return $this->post( $this->endpoint( 'coupons' ), $coupon );
  }

  /**
   * Deletes a Coupon from QPilot
   *
   * @param int $coupon_id   The QPilot Coupon ID.
   * @return bool            True on Success
   */
	public function delete_coupon( $coupon_id ) {
    return $this->delete( $this->endpoint( 'coupons', $coupon_id ) );
  }

  /**
   * Validates a set of coupons for an order
   * @param int    $order_id   The QPilot order id.
   * @param string $coupons A comma separated string of coupon codes
   * @return array An array of valid coupon objects.
   */
  public function validate_coupons( $order_id, $coupons ){
		return $this->post( $this->endpoint( 'validate_coupons' ), array( 'id' => $order_id, 'coupons' => $coupons ) );
  }

  /*
  * Update Order API Calls
  //////////////////////////////////////*/

  /**
   * Updates an existing Scheduled Order.
   *
   * @param int       $order_id A Qpilot order id.
   * @param array     $data     The updated scheduled order data.
   *
   * @return stdClass The updated scheduledOrder object.
   */
	public function update_scheduled_order( $order_id, $order ) {
		return $this->put( $this->endpoint( 'orders', $order_id ), $order );
	}

  /**
   * Updates an existing Scheduled Order's frequency & frequency type.
   *
   * @param int       $order_id A Qpilot order id.
   * @param string    $frequency_type The updated frequency type
   * @param int       $frequency The updated frequency
   *
   * @return stdClass The updated scheduledOrder object.
   */
  public function update_scheduled_order_frequency( $order_id, $frequency_type, $frequency ){
		return $this->put( $this->endpoint( 'update_frequency', $order_id ), array( 'frequencyType' => $frequency_type, 'frequency' => $frequency ) );
  }

  /**
   * Updates an existing Scheduled Order's Next Occurrence Date.
   *
   * @param int       $order_id A Qpilot order id.
   * @param string    $next_occurrence The New Next Occurrence Date in Y-m-d\TH:i:s format
   *
   * @return stdClass The updated scheduledOrder object.
   */
  public function update_scheduled_order_next_occurrence( $order_id, $next_occurrence ){
		return $this->put( $this->endpoint( 'update_occurrence', $order_id ), array( 'nextOccurrenceUtc' => $next_occurrence ) );
  }

  /**
   * Updates an existing Scheduled Order's Status.
   *
   * @param int       $order_id A Qpilot order id.
   * @param string    $status The New Status
   *
   * @return stdClass The updated scheduledOrder object.
   */
	public function update_scheduled_order_status( $order_id, $status ) {
    $status = strtolower($status);
		return $this->put( $this->endpoint( "update_{$status}", $order_id ) );
	}

  /**
   * Updates an existing Scheduled Order's Payment Method.
   *
   * @param int       $order_id A Qpilot order id.
   * @param int       $payment_method_id The QPilot Payment Method ID.
   *
   * @return stdClass The updated scheduledOrder object.
   */
  public function update_scheduled_order_payment_method( $order_id, $payment_method_id ){
		return $this->patch( $this->endpoint( "update_payment_method", $order_id ) , array( 'paymentMethodId'=> $payment_method_id ));
  }

  /*
  * Scheduled Order Item API Calls
  //////////////////////////////////////*/

  /**
   * Retrieve a specific scheduled order line item
   *
   * @param int $id The QPilot Scheduled Order Item ID
   * @return stdClass The item object
   */
	public function get_scheduled_order_item( $id ) {
		return $this->get( $this->endpoint( "items", $id ) );
	}

  /**
  * Adds a new Scheduled Order Item to a Scheduled Order
  * @param int $order_id    The QPilot Scheduled Order ID.
  * @param int $product_id  The WooCommerce Product ID.
  * @param array $item {
  *     Optional. An array of search parameters.
  *
  *     @type float   $price                The item price. Default if not supplied pulled from Product
  *     @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
  *     @type float   $originalSalePrice    The original price stored for historical purposes
  *     @type int     $quantity             The quantity for the line item. Default 1
  *     @type int     $cycles               The current cycle count for the item. Default 0
  *     @type int     $minCycles            The min Cycle value for the item. Default 0
  *     @type int     $manCycles            The max Cycle value for the item. Default 0
  *     @type array   $metadata             An array of metadata key => value pairs to attach to the item
  * }
  *
  * @return stdClass        The created item object
  */
	public function create_scheduled_order_item( $order_id, $product_id, $item = array() ) {
    $params['scheduledOrderId'] = $order_id;
    $params['productId'] = $product_id;
		$item = $this->post( $this->endpoint( "items" ), array(
      'scheduledOrderId'    => $order_id,
      'scheduledOrderItems' => array( $item ) ) );
    return $item[0];
	}

  /**
  * Adds new Scheduled Order Items to a Scheduled Order
  * @param int   $order_id    The QPilot Scheduled Order ID.
  * @param array $items {
  *    Optional. An array of item arrays .
  *    $item {
  *      @type int     $productId            The Product ID.
  *      @type float   $price                The item price. Default if not supplied pulled from Product
  *      @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
  *      @type float   $originalSalePrice    The original price stored for historical purposes
  *      @type int     $quantity             The quantity for the line item. Default 1
  *      @type int     $cycles               The current cycle count for the item. Default 0
  *      @type int     $minCycles            The min Cycle value for the item. Default 0
  *      @type int     $manCycles            The max Cycle value for the item. Default 0
  *      @type array   $metadata             An array of metadata key => value pairs to attach to the item
  *    }
  * }
  *
  * @return array An array of Scheduled Order Item objects
  */
	public function create_scheduled_order_items( $order_id, $items ) {
		return $this->post( $this->endpoint( "items" ), array(
      'scheduledOrderId'    => $order_id,
      'scheduledOrderItems' => $items )
    );
	}

  /**
   * Updates a Scheduled Order Item on a Scheduled Order
   *
   * @param int $item_id     The QPilot Scheduled Order Item ID.
   * @param array $item {
   *     An array of search parameters.
   *
   *     @type int     $productId            Required. The Product Id to Change the Product to for the line item.
   *     @type float   $price                Required. The item price. Default if not supplied pulled from Product
   *     @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
   *     @type float   $originalSalePrice    The original price stored for historical purposes
   *     @type int     $quantity             Required. The quantity for the line item. Default 1
   *     @type int     $cycles               The current cycle count for the item. Default 0
   *     @type int     $minCycles            The min Cycle value for the item. Default 0
   *     @type int     $manCycles            The max Cycle value for the item. Default 0
   *     @type array   $metadata             An array of metadata key => value pairs to attach to the item
   * }
   *
   * @return stdClass The updated item object
   */
	public function update_scheduled_order_item( $item_id, $item = array() ) {
		return $this->put( $this->endpoint( "items", $item_id ), $item );
	}

  /**
   * Deletes a Scheduled Order Item from a Scheduled Order
   *
   * @param int $item_id     The QPilot Scheduled Order Item ID.
   *
   * @return bool            True on Success
   */
	public function delete_scheduled_order_item( $item_id ) {
		return $this->delete( $this->endpoint( "items", $item_id ) );
	}

  /**
   * Adds the supplied line items to the next scheduled order.
   *
   * @param int $customer_id The Customer ID.
   * @param array $items {
   *     Array of Line Items to add
   *
   *     @type int     $productId            Required. The Product Id to Change the Product to for the line item.
   *     @type float   $price                Required. The item price. Default if not supplied pulled from Product
   *     @type float   $salePrice            The item sale price. Default if not supplied pulled from Product
   *     @type float   $originalSalePrice    The original price stored for historical purposes
   *     @type int     $quantity             Required. The quantity for the line item. Default 1
   *     @type int     $cycles               The current cycle count for the item. Default NULL
   *     @type int     $minCycles            The min Cycle value for the item. Default NULL
   *     @type int     $maxCycles            The max Cycle value for the item. Default NULL
   *     @type array   $metadata             An array of metadata key => value pairs to attach to the item
   * }
   *
   * @return stdClass The resulting scheduledOrder object.
   */
  public function add_items_to_next_scheduled_order( $customer_id, $items ){

    /**
    * Parse incoming $args into an array and merge it with defaults
    */
    foreach ( $items as $key => $args) {

      $items[$key] = array_merge( array(
      	'productId' => NULL,
        'quantity'  => 1,
        'price'     => NULL,
        'salePrice' => NULL,
        'minCycles' => NULL,
        'maxCycles' => NULL
      ), $args );

    }

    $data = array();
    $data["customerId"] = $customer_id;
    $data["items"] = $items;

		return $this->post(  $this->endpoint( "addto_next_order") , $data );

  }

  /*
  * Product API Calls
  //////////////////////////////////////*/

  /**
  * Searches the available products via api
  *
  * @param array $params {
  *     Optional. An array of search parameters.
  *
  *     @type int     $page                 The search results page to return. Default 1
  *     @type int     $pageSize             The default page size.  Default 100
  *     @type string  $orderBy              A product property to sort the results by
  *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
  *     @type string  $availability         The Stock Status ( Undefined, InStock, OutOfStock, PreOrder )
  *     @type float   $minPrice	            Minimum Price Range
  *     @type float   $maxPrice	            Maximum Price Range
  *     @type bool    $addToScheduledOrder	The Add to Scheduled Order Setting ( true for enabled )
  *     @type bool    $processScheduledOrderThe Process Scheduled Order Setting ( true for enabled )
  *     @type string  $search               A query string to search for.
  *     @type int     $groupId              A group id to search for.
  *     @type array   $productIds           An array of product ids to search for.
  *     @type array   $metadataKey          An array of metadata keys to search for.
  *     @type array   $metadataValue        An array of metadata values to search for.
  *     @type bool    $active               True for Active products
  *     @type bool    $valid                True for valid products
  * }
  *
  * @return stdClass object containing array of stdClass items, totalCount & totalPages
  */
  public function get_products( $params = array() ){
    return $this->get( $this->endpoint( "products" ) , $params );
  }

	/**
   * Retrieve a specific product
	 *
   * @param int $id The Product ID
	 * @return stdClass The product object
	 */
	public function get_product( $id ) {
    return $this->get( $this->endpoint( "products", $id ) );
	}

	/**
   * Updates the AddToScheduledOrder and ProcessScheduledOrder flags for a Product
	 *
   * @param int $product_id The Product ID
   * @param bool $addToScheduledOrder The value addToScheduledOrder should be set to. Default false
   * @param bool $processScheduledOrder The value processScheduledOrder should be set to. Default false
	 * @return bool True on Success
	 */
	public function update_product_availability( $product_id, $addToScheduledOrder = false, $processScheduledOrder = false ) {
    return $this->put( $this->endpoint( "update_availability", $product_id ), array(
      "AddToScheduledOrder"   => $addToScheduledOrder,
      "ProcessScheduledOrder" => $processScheduledOrder
    ) );
	}

  /**
  * Toggles the Active flag for all products for a site and optionally
  * excludes the supplied IDs
  *
  * @param string $active 'true' if all products except the supplied excluded should be active else false.
  * @param array $excluded_ids An array of ids to not include in those that are changed.
  * @return bool True on Success
  */
	public function batch_activate_deactivate_products( $active = 'true', $ids = array() ){
		return $this->patch( $this->endpoint( "batch_deactivate" ), array( 'active' => $active, 'ids' => $ids ) );
  }

	/**
   * Retrieves the product summaries from the QPilot API for the supplied ids.
   * NOTE Uses a POST method to prevent blow up on large quantities of ids.
   *
   * @param array $product_ids An array of Product IDs to Retrieve
   * @param array $params {
   *     Optional. An array of search parameters.
   *
   *     @type int     $page                 The search results page to return. Default 1
   *     @type int     $pageSize             The default page size.  Default 100
   *     @type string  $orderBy              A product property to sort the results by
   *     @type string  $startDate	           Filter for all products created on or after a date
   *                                         ISO Format, e.g.: 2019-12-10T13:17:25.878Z
   *     @type string  $endDate	             Filter for all products created before a date
   *                                         ISO Format, e.g.: 2019-12-10T13:17:25.878Z
   *     @type string  $search               A query string to search for.
   *     @type int     $externalId	         Query for a specific product
   *     @type int     $productGroupId       Query for products in a group.
   *     @type bool    $active               True for Active products
   *     @type bool    $valid                True for valid products
   * }
   * @return array of stdClass product objects.
	 */
	public function get_products_summary( $product_ids, $params = array() ) {
    $params = array_merge( array( 'pageSize' => 100 ), $params );
		return $this->post( $this->endpoint( "product_summaries", NULL, $params ), array( 'externalIds' => $product_ids ) );
	}

	/**
   * Creates a New Product in QPilot
	 *
   * @param int     $id          The Product ID
   * @param string  $title       The Product Title/Name
   * @param array   $product_data {
   *     Optional. An array of optional product properties.
   *
   *     @type int     $parentProductId       The parent id ( Parent ID for a Variation )
   *     @type string  $sku                   A product property to sort the results by
   *     @type string  $gtin                  The Global Trade Item Number for the product
   *     @type string  $mpn                   The Manufacturer Part Number for the product
   *     @type string  $description	          Maximum Price Range
   *     @type float   $price	                The Add to Scheduled Order Setting ( true for enabled )
   *     @type float   $salePrice             The Process Scheduled Order Setting ( true for enabled )
   *     @type string  $length                The Product Length
   *     @type string  $width                 The Product Width
   *     @type string  $height                The Product Height
   *     @type string  $weight                The Product Weight
   *     @type string  $weightUnitType        The unit of weight ( 'Pound', 'Ounce', 'Kilogram', 'Gram' )
   *     @type string  $lengthUnitType        The unit of weight ('Inch', 'Foot', 'Yard', 'Milimeter', 'Centimeter', 'Meter' )
   *     @type string  $shippingClass         The shipping class
   *     @type string  $taxClass              The tax class
   *     @type bool    $addToScheduledOrder   Set the Add To Scheduled Order Flag for the Product
   *     @type bool    $processScheduledOrder Set the Process Scheduled Order Flag for the Product
   *     @type string  $availability          Sets the Availability string ( 'Undefined', 'InStock', 'OutOfStock', 'Preorder' )
   *     @type int     $stock                 The stock amount
   *     @type float   $lifetimeValue         The lifetime value for the product,
   *     @type bool    $active                True if the product is active
   *     @type bool    $valid                 If the product is valid
   *     @type array   $productGroupIds       The group ids the product is assigned to
   *     @type array   $availableFrequencies  The available frequencies for the product
   *                                          frequencyType (string, optional) = ['Days', 'Weeks', 'Months', 'DayOfTheWeek', 'DayOfTheMonth']
   *                                          values (Array[integer], optional)
   *     @type array   $metadata              Array of Key Values pairs of metadata
   *     @type bool    $syncOnUpdate
   * }
	 * @return stdClass The created product object
	 */
	public function create_product( $id, $title, $product_data = array() ) {
    $product_data['id'] = $id;
    $product_data['title'] = $title;
		return $this->post( $this->endpoint( "products" ), $product_data );
	}

	/**
   * Deletes a Product from QPilot
   *
	 * @param int $id The Product ID
	 * @return bool True on success
	 */
	public function delete_product( $id ) {
		return $this->delete( $this->endpoint( "products", $id ) );
	}

	/**
	 * Creates or Updates a Product in QPilot
   * If the ID is found the existing product is updated else it's created.
	 *
   * @param int     $id          The Product ID
   * @param string  $title       The Product Title/Name
   * @param array   $product_data {
   *     Optional. An array of optional product properties.
   *
   *     @type int     $parentProductId       The parent id ( Parent ID for a Variation )
   *     @type string  $sku                   A product property to sort the results by
   *     @type string  $gtin                  The Global Trade Item Number for the product
   *     @type string  $mpn                   The Manufacturer Part Number for the product
   *     @type string  $description	          Maximum Price Range
   *     @type float   $price	                The Add to Scheduled Order Setting ( true for enabled )
   *     @type float   $salePrice             The Process Scheduled Order Setting ( true for enabled )
   *     @type string  $length                The Product Length
   *     @type string  $width                 The Product Width
   *     @type string  $height                The Product Height
   *     @type string  $weight                The Product Weight
   *     @type string  $weightUnitType        The unit of weight ( 'Pound', 'Ounce', 'Kilogram', 'Gram' )
   *     @type string  $lengthUnitType        The unit of weight ('Inch', 'Foot', 'Yard', 'Milimeter', 'Centimeter', 'Meter' )
   *     @type string  $shippingClass         The shipping class
   *     @type string  $taxClass              The tax class
   *     @type bool    $addToScheduledOrder   Set the Add To Scheduled Order Flag for the Product
   *     @type bool    $processScheduledOrder Set the Process Scheduled Order Flag for the Product
   *     @type string  $availability          Sets the Availability string ( 'Undefined', 'InStock', 'OutOfStock', 'Preorder' )
   *     @type int     $stock                 The stock amount
   *     @type float   $lifetimeValue         The lifetime value for the product,
   *     @type bool    $active                True if the product is active
   *     @type bool    $valid                 If the product is valid
   *     @type array   $productGroupIds       The group ids the product is assigned to
   *     @type array   $availableFrequencies  The available frequencies for the product
   *                                          frequencyType (string, optional) = ['Days', 'Weeks', 'Months', 'DayOfTheWeek', 'DayOfTheMonth']
   *                                          values (Array[integer], optional)
   *     @type array   $metadata              Array of Key Values pairs of metadata
   *     @type bool    $syncOnUpdate
   * }
	 * @return stdClass The created product object
	 */
	public function upsert_product( $id, $title, $product_data = array() ) {
    $product_data['id'] = $id;
    $product_data['title'] = $title;
		return $this->post( $this->endpoint( "upsert_product" ), $product_data );
	}

  /*
  * Customer API Calls
  //////////////////////////////////////*/

	/**
   * Retrieves a Customer Metrics from QPilot
	 *
   * @param array   $metrics_data {
   *     Optional. An array of optional metrics properties.
   *
   *     @type string  $startDate             The start date to filter customers by their creation date
   *     @type string  $endDate               The end date to filter customers by their creation date
   *     @type int     $page                  The search results page to return. Default 1
   *     @type int     $pageSize              The default page size.  Default 100
	 *		 @type array   $customerIds						An array of Customer IDs to pull
	 *     @type bool    excludeEventLogsData   True to Include event logs related data.
   * }
	 *
	 * @return array An array of stdClass objects
	 */
	public function get_customer_metrics( $params = array() ) {
    $params = array_merge( array( 'page' => 1, 'pageSize' => 100 ), $params );
		return $this->get( $this->endpoint( "customer_metrics" ), $params );
	}

	/**
   * Retrieves a Customer Summaries from QPilot
	 *
   * @param array   $metrics_data {
   *     Optional. An array of optional metrics properties.
   *
   *     @type int     $page                 The search results page to return. Default 1
   *     @type int     $pageSize             The default page size.  Default 100
   *     @type string  $orderBy              A product property to sort the results by
   *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
   *     @type string  $search               A query string to search for.
   *     @type string  $startDate            The start date to filter customers by their creation date
   *     @type string  $endDate              The end date to filter customers by their creation date
	 *     @type bool    isDeleted   					 True to Include soft deleted customers
   * }
	 *
	 * @return array An array of stdClass objects
	 */
	public function get_customer_summaries( $params = array() ) {
    $params = array_merge( array( 'page' => 1, 'pageSize' => 100 ), $params );
		return $this->get( $this->endpoint( "customer_summaries" ), $params );
	}

	/**
   * Retrieves a Customer from QPilot using the Customer ID
   *
	 * @param int $customer_id  The Customer ID
	 * @return stdClass The customer object
	 */
	public function get_customer( $customer_id ) {
		return $this->get( $this->endpoint( "customers", $customer_id ) );
	}

  /**
   * Searches the available customers via api
   *
   * @param array $params {
   *     Optional. An array of search parameters.
   *
   *     @type int     $page                 The search results page to return. Default 1
   *     @type int     $pageSize             The default page size.  Default 100
   *     @type string  $orderBy              A product property to sort the results by
   *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
   *     @type string  $search               A query string to search for.
   * }
   *
   * @return stdClass object containing array of stdClass items, totalCount & totalPages
   */
	public function get_customers( $params= array() ) {
		return $this->get( $this->endpoint( "customers") , $params );
	}

	/**
   * Creates a Customer in QPilot
   *
	 * @param int $customer_id     The Customer ID
	 * @param string $email        The Customers Email Address
   * @param array $customer_data  {
   *     Optional. An array of optional customer properties.
   *
   *     @type string  $firstName         The customers first name
   *     @type string  $lastName          The customers last name
   *     @type string  $shippingFirstName The customers Shipping first name
   *     @type string  $shippingLastName  The customers Shipping last name
   *     @type string  $shippingStreet1   The customers Shipping Address 1
   *     @type string  $shippingStreet2   The customers Shipping Address 2
   *     @type string  $shippingCity      The customers Shipping City
   *     @type string  $shippingState     The customers Shipping State
   *     @type string  $shippingPostcode  The customers Shipping Post Code
   *     @type string  $shippingCountry   The customers Shipping Country
   *     @type string  $billingFirstName  The customers Billing first name
   *     @type string  $billingLastName   The customers Billing last name
   *     @type string  $billingStreet1    The customers Billing Address 1
   *     @type string  $billingStreet2    The customers Billing Address 2
   *     @type string  $billingCity       The customers Billing City
   *     @type string  $billingState      The customers Billing State
   *     @type string  $billingPostcode   The customers Billing Post Code
   *     @type string  $billingCountry    The customers Billing Country
   *     @type string  $phoneNumber       The customers phone
   *     @type string  $company           The customers company
   *     @type array   $paymentMethods          {
   *      Optional. An array of payment methods for the customer
   *        @type int      $id                The QPilot Payment Method ID
   *        @type int      $customerId        The Customer ID
   *        @type array    $type              The Payment Type ( i.e. 'Stripe', 'AuthorizeNet', 'PayPal', etc )
   *        @type string   $gatewayCustomerId The Gateway Customer ID
   *        @type string   $gatewayPaymentId  The Gateway Payment ID
   *        @type int      $lastFourDigits    Payment Type Last Four
   *        @type string   $expiration        Payment Method Expiration MM/YY
   *        @type string   $description       Payment Method Description
   *        @type string   $billingFirstName  The customer billing first name
   *        @type string   $billingLastName   The customer billing first name
   *        @type string   $billingStreet1    The customer billing first name
   *        @type string   $billingStreet2    The customer billing first name
   *        @type string   $billingCity       The customer billing first name
   *        @type string   $billingState      The customer billing first name
   *        @type string   $billingPostcode   The customer billing first name
   *        @type string   $billingCountry    The customer billing first name
   *        @type bool     $isDefault         If the Payment Type is the Default
   *      }
   * }
   *
	 * @return stdClass The customer object
	 */
	public function create_customer( $customer_id, $email, $customer_data = array() ) {
    $customer_data['id']    = $customer_id;
    $customer_data['email'] = $email;
		return $this->post( $this->endpoint( "customers" ), $customer_data );
	}

  /**
   * Creates / Updates a Customer in QPilot
   *
	 * @param int      $customer_id     The Customer ID
	 * @param string   $email           The Customers Email Address
   * @param array    $customer_data  {
   *     Optional. An array of optional customer properties.
   *
   *     @type bool    $applyToScheduledOrders  If the changes should be applied to all orders
   *     @type string  $firstName               The customers first name
   *     @type string  $lastName                The customers last name
   *     @type string  $shippingFirstName       The customers Shipping first name
   *     @type string  $shippingLastName        The customers Shipping last name
   *     @type string  $shippingStreet1         The customers Shipping Address 1
   *     @type string  $shippingStreet2         The customers Shipping Address 2
   *     @type string  $shippingCity            The customers Shipping City
   *     @type string  $shippingState           The customers Shipping State
   *     @type string  $shippingPostcode        The customers Shipping Post Code
   *     @type string  $shippingCountry         The customers Shipping Country
   *     @type string  $billingFirstName        The customers Billing first name
   *     @type string  $billingLastName         The customers Billing last name
   *     @type string  $billingStreet1          The customers Billing Address 1
   *     @type string  $billingStreet2          The customers Billing Address 2
   *     @type string  $billingCity             The customers Billing City
   *     @type string  $billingState            The customers Billing State
   *     @type string  $billingPostcode         The customers Billing Post Code
   *     @type string  $billingCountry          The customers Billing Country
   *     @type string  $phoneNumber             The customers phone
   *     @type string  $company                 The customers company
   *     @type array   $paymentMethods          {
   *      Optional. An array of payment methods for the customer
   *        @type int      $id                The QPilot Payment Method ID
   *        @type int      $customerId        The Customer ID
   *        @type array    $type              The Payment Type ( i.e. 'Stripe', 'AuthorizeNet', 'PayPal', etc )
   *        @type string   $gatewayCustomerId The Gateway Customer ID
   *        @type string   $gatewayPaymentId  The Gateway Payment ID
   *        @type int      $lastFourDigits    Payment Type Last Four
   *        @type string   $expiration        Payment Method Expiration MM/YY
   *        @type string   $description       Payment Method Description
   *        @type string   $billingFirstName  The customer billing first name
   *        @type string   $billingLastName   The customer billing first name
   *        @type string   $billingStreet1    The customer billing first name
   *        @type string   $billingStreet2    The customer billing first name
   *        @type string   $billingCity       The customer billing first name
   *        @type string   $billingState      The customer billing first name
   *        @type string   $billingPostcode   The customer billing first name
   *        @type string   $billingCountry    The customer billing first name
   *        @type bool     $isDefault         If the Payment Type is the Default
   *      }
   * }
   *
	 * @return stdClass The created/updated customer object
	 */
	public function upsert_customer( $customer_id, $email, $customer_data = array() ) {
    $customer_data['id']    = $customer_id;

    // Only re-assign the email if the email param wasn't included.
    if ( !isset( $customer_data['email'] ) || empty( $customer_data['email'] ) )
    $customer_data['email'] = $email;

		return $this->post( $this->endpoint( "upsert_customer" ), $customer_data );
	}

	/**
   * Deletes a Customer from QPilot using the Customer ID
   *
	 * @param int $customer_id  The Customer ID
	 * @return bool True on Success
	 */
	public function delete_customer( $customer_id ) {
		return $this->delete( $this->endpoint( "customers", $customer_id ) );
	}

  /*
  * Payment Integrations API Calls
  //////////////////////////////////////*/

  /**
   * Retrieves the Payment Integrations from QPilot for a Site
   * @return array An array of stdClass Payment Integration Objects
   */
	public function get_payment_integrations() {
		return $this->get( $this->endpoint( "payment_integrations" ) );
	}

  /**
   * Creates a Payment Integration in QPilot for a Site
   *
   * @param array    $integration_data  {
   *
   *     @type string  $paymentMethodType The Integration Type ( i.e. Stripe, PayPal, etc. )
   *     @type string  $apiAccount        The API Account Number
   *     @type string  $apiKey1           The API Key
   *     @type string  $apiKey2           The Second API Key
   *     @type bool    $testMode          True for test mode
   *     @type bool    $authorizeOnly     True for Authorize only
   * }
   *
   * @return stdClass The Created Payment Integration Object
   */
	public function create_payment_integration( $integration_data ) {
		return $this->post( $this->endpoint( "payment_integrations" ), $integration_data );
	}

  /*
  * Payment Method API Calls
  //////////////////////////////////////*/

  /**
   * Retrieves the Payment Method from QPilot
   *
   * @param int $method_id The QPilot Payment Method ID
   * @return stdClass The Payment Method Object
   */
	public function get_payment_method( $method_id ) {
		return $this->get( $this->endpoint( "payment_method", $method_id ) );
	}

  /**
   * Retrieves the Payment Methods for a Customer from QPilot
   *
   * @param int $customer_id The Customer ID
   * @return array of stdClass Payment Method Objects
   */
	public function get_payment_methods( $customer_id ) {
		return $this->get( $this->endpoint( "payment_methods", $customer_id ) );
	}

  /**
   * Creates a Payment Method in QPilot
   *
   * @param array $payment_method_data {
   *     Array of Payment Method Information
   *
   *     @type int        $customerId.        The Customer id,
   *     @type string     $type               The payment gateway type name (stripe,NMI),
   *     @type string     $lastFourDigits     The last four digits of the card
   *     @type string     $expiration         The expiration date.
   *     @type mixed      $gatewayCustomerId  The Payment Gateway Customer id
   *     @type mixed      $gatewayPaymentId   The payment gateway token.
   *     @type string     $description        The payment description
   *     @type string     $billingFirstName   The Customer's Billing First Name
   *     @type string     $billingLastName    The Customer's Billing last Name
   *     @type string     $billingStreet1     The Customer's Billing Street Address Line 1
   *     @type string     $billingStreet2     The Customer's Billing Street Address Line 2
   *     @type string     $billingCity        The Customer's Billing City
   *     @type string     $billingState       The Customer's Billing State
   *     @type string     $billingPostcode    The Customer's Billing Postcode
   *     @type string     $billingCountry     The Customer's Billing Country
   *     @type bool       $isDefault          True if the payment method is the default.
   *
   * }
   *
   * @return stdClass The resulting payment method object.
   */
	public function create_payment_method( $payment_method_data ) {
		return $this->post( $this->endpoint( "payment_method" ), $payment_method_data );
	}

  /**
  * Creates/Updates a payment method in QPilot.
  *
  * @param array $payment_method_data {
  *     Array of Payment Method Information
  *
  *     @type int        $customerId.        The Customer id,
  *     @type string     $type               The payment gateway type name (stripe,NMI),
  *     @type string     $lastFourDigits     The last four digits of the card
  *     @type string     $expiration         The expiration date.
  *     @type mixed      $gatewayCustomerId  The Payment Gateway Customer id
  *     @type mixed      $gatewayPaymentId   The payment gateway token.
  *     @type string     $description        The payment description
  *     @type string     $billingFirstName   The Customer's Billing First Name
  *     @type string     $billingLastName    The Customer's Billing last Name
  *     @type string     $billingStreet1     The Customer's Billing Street Address Line 1
  *     @type string     $billingStreet2     The Customer's Billing Street Address Line 2
  *     @type string     $billingCity        The Customer's Billing City
  *     @type string     $billingState       The Customer's Billing State
  *     @type string     $billingPostcode    The Customer's Billing Postcode
  *     @type string     $billingCountry     The Customer's Billing Country
  *     @type bool       $applyToScheduledOrders True to apply this payment method to all
  *                                              Scheduled Orders
  *
  * }
  *
  * @return stdClass The resulting payment method object.
  */
	public function upsert_payment_method( $payment_method_data ) {
		return $this->post( $this->endpoint( "upsert_payment_method" ), $payment_method_data );
	}

  /**
   * Deletes a Payment Method for a Customer from QPilot
   *
   * @param int $method_id The QPilot Payment Method ID
   * @return bool True on Success
   */
	public function delete_payment_method( $method_id ) {
		return $this->delete( $this->endpoint( "payment_method", $method_id ) );
	}

  /*
  * Utility Method API Calls
  //////////////////////////////////////*/

  /**
   * Gets an Access Token for a Merchant from QPilot
   *
   * @param string $secret_key The Secret Key
   * @return stdClass The Access Token Object
   */
	public function generate_merchant_access_token( $secret_key ) {
		return $this->post( $this->endpoint( "generate_token" ), array( 'accessScope' => 'Merchant', 'secretKey' => $secret_key ) );
	}

  /**
   * Gets an Access Token for a Customer from QPilot
   *
   * @param int $customer_id The customers QPilot ID
   * @param string $secret_key The Secret Key
   * @return stdClass The Access Token Object
   */
	public function generate_customer_access_token( $customer_id, $secret_key ) {
		return $this->post( $this->endpoint( "generate_token" ), array( 'accessScope' => 'Customer', 'customerId'  => $customer_id, 'secretKey' => $secret_key ) );
	}

  /**
   * Generates the Next Occurrence Date Time in UTC using QPilot
   *
   * @param string $frequency_type The Frequency Type ( i.e. Months, Days, etc )
   * @param int $frequency The Frequency
   * @param string $from_utc The Date Time to calulate the next occurrence based off ( 'Y-m-d H:i:s' )
   * @return stdClass The Access Token Object
   */
	public function get_next_occurrence_utc( $frequency_type, $frequency, $from_utc = null ) {

		if ( $from_utc == null )
		$from_utc = autoship_get_utc_datetime( NULL, NULL, 'Y-m-d H:i:s' );

		$next_occurrence_query = array(
			'frequencyType' => $frequency_type,
			'frequency'     => $frequency,
			'fromUtc'       => $from_utc
		);

		return $this->get( $this->endpoint( "generate_next_occur" ), $next_occurrence_query );
	}

  /*
  * Processing API Calls
  //////////////////////////////////////*/

  /**
   * Upgrades a Sites Processing Version in QPilot
	 *
   * @since 2.2.5
   * @return stdClass The Status check results object
   */
	public function migrate_processing_version() {
		return $this->post( $this->endpoint( "migrate_processing" ) );
	}

  /*
  * Integration Health Method API Calls
  //////////////////////////////////////*/

  /**
   * Retrieves the Health Check status and initiates QPilot
   * api check with new API endpoint.
   * @since 1.2.29
   * @return stdClass The Status check results object
   */
	public function check_integration_status() {
		return $this->get( $this->endpoint( "integration_check" ) );
	}

  /*
  * HTTP Call Methods ( POST, PUT, GET, DELETE, PATCH )
  //////////////////////////////////////*/

  /**
   * Processes the HTTP Response and Pulls any Errors
   *
   * @param Requests_Response $response The HTTP Request Response
   * @param array $call_args The Original HTTP Request Arguments
   *
   * @return stdClass The Status check results object
   */
  private function process_response( $response, $call_args ){

    // parse the body if we can
		$response_data = ! empty( $response['body'] ) ? json_decode( $response['body'] ) : true;

    // If there are no errors just return the results.
    if ( $response['response']['code'] == 200 || $response['response']['code'] == 202 )
    return $response_data;

    // Set the default
    $message = $response['response']['message'];
    $code = (int) $response['response']['code'];

    // Use Current Message and Code if there is no Body
    if( empty( $response['body'] ) || !is_object( $response_data ) )
    throw new Exception( $message, $code );

    // First Check for Custom User Displayed Messages
    if ( isset( $response_data->messages ) ){

      // First Check for Technical System Errors
      if ( isset( $response_data->messages->errors ) ){

        // Allow functions to be hooked to errors
        $this->do_action( 'qpilot_remote_request_response_errors', array(
          'callArgs'       => $call_args,
          'code'           => $code,
          'response_error' => $response['response']['message'],
          'errors'         => $response_data->messages->errors
        ));

        $message = implode( ' ', $response_data->messages->errors );

      }

      // Now Check for User Messages Errors
      if ( isset( $response_data->messages->userMessage ) ){

        $user_messages = $this->apply_filters( 'qpilot_remote_request_response_messages', $response_data->messages->userMessage, $call_args );

        // Grab the messages and set the code to signal
        $message = implode( ' ', $user_messages );
        $code = self::$customer_http_code;

      }

    // Else Attempt to Parse the Body for the Non-Customer Displayed Messages
    } else if ( isset( $response_data->message ) ){
      $message = $response_data->message;
    }

    throw new Exception( $message, $code );

  }

  /**
   * Makes a GET Request to the API
   * @uses qpilot_remote_request
   *
   * @param string $entity The Endpoint URL
   * @param array $params Optional URL Parameters.
   * @throws Exception on Error.
   *
   * @return mixed The Endpoint Response.
   */
	private function get( $entity, $params = array() ) {

		$endpoint = $this->_api_url . $entity;
		if ( count( $params ) > 0 ) {
			$query    = '?' . http_build_query( $params );
			$endpoint .= $query;
		}

    $args = array(
      'endpoint'=> $endpoint,
      'method'  => 'GET',
			'body'    => '',
			'headers' => $this->get_headers(),
			'timeout' => $this->_timeout
		);

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request', $args );

    /**
     * qpilot_remote_request is a pluggable function
     * and is not defined in the class file by default
     * please define qpilot_remote_request before using the class.
     */
		$response = qpilot_remote_request( $args['endpoint'], array(
      'headers' => $args['headers'],
      'timeout' => $args['timeout']
		) );

    $args['response'] = $response;

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request_response', $args );

		return $this->process_response( $response, $args );

	}

  /**
   * Makes a POST Request to the API
   * @uses qpilot_remote_request
   *
   * @param string $entity The Endpoint URL
   * @param array $data The Key Value pairs to Post.
   * @param array $params Optional URL Parameters.
   * @throws Exception on Error.
   *
   * @return mixed The Endpoint Response.
   */
	private function post( $entity, $data = array(), $params = array() ) {
		$endpoint = $this->_api_url . $entity;

		if ( count( $params ) > 0 ) {
			$query    = '?' . http_build_query( $params );
			$endpoint .= $query;
		}

		$headers                 = $this->get_headers();
		$headers['Content-Type'] = 'application/json';
		$body                    = json_encode( $data );

    $args = array(
      'endpoint'=> $endpoint,
      'method'  => 'POST',
			'body'    => $body,
			'headers' => $headers,
			'timeout' => $this->_timeout
		);

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request', $args );

    /**
     * qpilot_remote_request is a pluggable function
     * and is not defined in the class file by default
     * please define qpilot_remote_request before using the class.
     */
    $response = qpilot_remote_request( $args['endpoint'], array(
 			'method'  => $args['method'],
 			'body'    => $args['body'],
 			'headers' => $args['headers'],
 			'timeout' => $args['timeout']
 		) );

    $args['response'] = $response;

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request_response', $args );

		return $this->process_response( $response, $args );

	}

  /**
   * Makes a PATCH Request to the API
   * @uses qpilot_remote_request
   *
   * @param string $entity The Endpoint URL
   * @param array $data The Key Value pairs to Post.
   * @param array $params Optional URL Parameters.
   * @throws Exception on Error.
   *
   * @return mixed The Endpoint Response.
   */
	private function patch( $entity, $data = array(), $params = array() ) {
		$endpoint = $this->_api_url . $entity;
		if ( count( $params ) > 0 ) {
			$query    = '?' . http_build_query( $params );
			$endpoint .= $query;
		}

		$headers                 = $this->get_headers();
		$headers['Content-Type'] = 'application/json';
		$body                    = json_encode( $data );

    $args = array(
      'endpoint'=> $endpoint,
      'method'  => 'PATCH',
			'body'    => $body,
			'headers' => $headers,
			'timeout' => $this->_timeout
		);

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request', $args );

    /**
     * qpilot_remote_request is a pluggable function
     * and is not defined in the class file by default
     * please define qpilot_remote_request before using the class.
     */
		$response = qpilot_remote_request( $args['endpoint'], array(
			'method'  => $args['method'],
			'body'    => $args['body'],
			'headers' => $args['headers'],
			'timeout' => $args['timeout']
		) );

    $args['response'] = $response;

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request_response', $args );

		return $this->process_response( $response, $args );

	}

  /**
   * Makes a PUT Request to the API
   * @uses wp_remote_post
   *
   * @param string $entity The Endpoint URL
   * @param array $data The Key Value pairs to Post.
   * @param array $params Optional URL Parameters.
   * @throws Exception on Error.
   *
   * @return mixed The Endpoint Response.
   */
	private function put( $entity, $data = array(), $params = array() ) {

		$endpoint = $this->_api_url . $entity;
		if ( count( $params ) > 0 ) {
			$query    = '?' . http_build_query( $params );
			$endpoint .= $query;
		}

		$headers                 = $this->get_headers();
		$headers['Content-Type'] = 'application/json';
		$body                    = json_encode( $data );

    $args = array(
      'endpoint'=> $endpoint,
      'method'  => 'PUT',
			'body'    => $body,
			'headers' => $headers,
			'timeout' => $this->_timeout
		);

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request', $args );

    /**
     * qpilot_remote_request is a pluggable function
     * and is not defined in the class file by default
     * please define qpilot_remote_request before using the class.
     */
		$response                = qpilot_remote_request( $args['endpoint'], array(
			'method'  => $args['method'],
			'body'    => $args['body'],
			'headers' => $args['headers'],
			'timeout' => $args['timeout']
		) );

    $args['response'] = $response;

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request_response', $args );

		return $this->process_response( $response, $args );

	}

  /**
   * Makes a DELETE Request to the API
   * @uses qpilot_remote_request
   *
   * @param string $entity The Endpoint URL
   * @param array $params Optional URL Parameters.
   * @throws Exception on Error.
   *
   * @return mixed The Endpoint Response.
   */
	private function delete( $entity, $params = array() ) {
		$endpoint = $this->_api_url . $entity;
		if ( count( $params ) > 0 ) {
			$query    = '?' . http_build_query( $params );
			$endpoint .= $query;
		}

    $args = array(
      'endpoint'=> $endpoint,
      'method'  => 'DELETE',
			'body'    => '',
			'headers' => $this->get_headers(),
			'timeout' => $this->_timeout
		);

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request', $args );

    /**
     * qpilot_remote_request is a pluggable function
     * and is not defined in the class file by default
     * please define qpilot_remote_request before using the class.
     */
		$response = qpilot_remote_request( $args['endpoint'], array(
			'method'  => $args['method'],
			'headers' => $args['headers'],
			'timeout' => $args['timeout']
		) );

    $args['response'] = $response;

    // Allow for hooking into action
    $this->do_action( 'qpilot_remote_request_response', $args );

		return $this->process_response( $response, $args );

	}

  /**
   * Returns the request header array
   *
   * @return array The headers
   */
	private function get_headers() {
		$headers = array();

		if ( null != $this->_token_auth ) {
			$headers['Authorization'] = sprintf( 'Bearer %s', $this->_token_auth );
		}

		if ( null != $this->_source ) {
			$headers['Source'] = $this->_source;
		}

		return $headers;
	}

  /*
  * Product Groups API Calls
  //////////////////////////////////////*/

  /**
   * Retrieve a specific product group
	 *
   * @param int $id The Product Group ID
	 * @return stdClass The product group object
	 */
	public function get_product_group( $id ) {
    return $this->get( $this->endpoint( 'product_group', $id ) );
	}

  /**
   * Retrieve site integrations
   *
   * @return stdClass The siteIntegrations object
   */
  public function get_site_integrations() {
    return $this->get( $this->endpoint( 'site_integrations' ) );
  }
}
