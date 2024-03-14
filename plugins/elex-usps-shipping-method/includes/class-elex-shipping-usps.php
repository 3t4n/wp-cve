<?php

/**
 * ELEX_Shipping_USPS class.
 *
 * @extends WC_Shipping_Method
 */
class ELEX_Shipping_USPS extends WC_Shipping_Method {

	private $endpoint = 'https://production.shippingapis.com/shippingapi.dll';
	//private $endpoint        = 'https://stg-production.shippingapis.com/ShippingApi.dll';
	private $default_user_id = '570CYDTE1766';
	private $default_password = '#cwCWRaJ2HH@';
	private $domestic        = array( 'US', 'PR', 'MP', 'VI', 'GU', 'AS' );
	private $found_rates;
	private $package_info;
	public $method_description;
	public $selected_flat_rate_boxes;
	public $disable_commercial_rates;
	public $box_max_weight;
	public $optional;
	public $elex_usps_insurance_contents;
	public $elex_usps_insurance;
	public $weight_unit;
	public $dimension_unit;
	public $test_mode;
	public $weight_packing_process;
	public $method_title;
	
	 public $flat_rate_boxes;
	 public $unpacked_item_costs;
	 public $id;
	public $availability;
	public $origin;
	public $disbleShipmentTracking;
	public $fillShipmentTracking;
	public $disblePrintLabel;
	public $defaultPrintService;
	public $defaultPrintInternationalService;
	public $printLabelSize;
	public $printLabelType;
	public $senderName;
	public $senderCompanyName;
	
	public $flat_rate_fee;
	public $mediamail_restriction;
	public $unpacked_item_handling;
	public $enable_standard_services;
	public $senderCountry;
	public $senderEmail;

	public $senderAddressLine1;
	public $senderAddressLine2;
	public $senderCity;
	public $senderState;
	public $enable_flat_rate_boxes;
	public $debug;
	public $senderPhone;
	public $user_id;
	public $password;
	public $packing_method;
	public $boxes;
	public $custom_services = array();
	public $offer_rates;
	public $fallback;
	public $ordered_services;
	public $services = array();
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = ELEX_USPS_ID;
		$this->method_title       = __( 'USPS', 'wf-usps-woocommerce-shipping' );
		$this->method_description = __( 'The <strong>USPS</strong> extension obtains rates dynamically from the USPS API during cart/checkout.', 'wf-usps-woocommerce-shipping' );
		$this->services           = include  'data-wf-services.php' ;
		$this->init();
	}

	/**
	 * Is_available function.
	 *
	 * @param array $package
	 * @return bool
	 */
	public function is_available( $package ) {
		if ( 'no' === $this->enabled ) {
			return false;
		}
		if ( 'specific' === $this->availability ) {
			if ( is_array( $this->countries ) && ! in_array( $package['destination']['country'], $this->countries ) ) {
				return false;
			}
		} elseif ( 'excluding' === $this->availability ) {
			if ( is_array( $this->countries ) && ( in_array( $package['destination']['country'], $this->countries ) || ! $package['destination']['country'] ) ) {
				return false;
			}
		}
			/** 
		 * Fire a filter hook for check availability
		 *
		 * @since 2002
		 * @param $package
		 */  
		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
	}

	/**
	 * Init function.
	 *
	 * @accesss public
	 * @return void
	 */
	private function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->enabled      = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : $this->enabled;
		$this->title        = isset( $this->settings['title'] ) ? $this->settings['title'] : $this->method_title;
		$this->availability = isset( $this->settings['availability'] ) ? $this->settings['availability'] : 'all';
		$this->countries    = isset( $this->settings['countries'] ) ? $this->settings['countries'] : array();
		$this->origin       = isset( $this->settings['origin'] ) ? $this->settings['origin'] : '';

		$this->user_id                  = ! empty( $this->settings['user_id'] ) ? $this->settings['user_id'] : $this->default_user_id;
		$this->password                  = ! empty( $this->settings['password'] ) ? $this->settings['password'] : $this->default_password;

		$this->packing_method           = 'per_item';
		$this->custom_services          = isset( $this->settings['services'] ) ? $this->settings['services'] : array();
		$this->offer_rates              = isset( $this->settings['offer_rates'] ) ? $this->settings['offer_rates'] : 'all';
		$this->fallback                 = ! empty( $this->settings['fallback'] ) ? $this->settings['fallback'] : '';
		$this->mediamail_restriction    = isset( $this->settings['mediamail_restriction'] ) ? $this->settings['mediamail_restriction'] : array();
		$this->mediamail_restriction    = array_filter( (array) $this->mediamail_restriction );
		$this->enable_standard_services = true;
		$this->debug                    = isset( $this->settings['debug_mode'] ) && 'yes' == $this->settings['debug_mode'] ? true : false;
		$this->disable_commercial_rates = false;
		$this->weight_unit              = strtolower( get_option( 'woocommerce_weight_unit' ) );


		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'test_user_id' ), -10 );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );
	}

	/**
	 * Environment_check function.
	 *
	 * @accesss public
	 * @return void
	 */
	private function environment_check() {

		$admin_page = version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ? admin_url( 'admin.php?page=wc-settings&tab=general' ) : admin_url( 'admin.php?page=woocommerce_settings&tab=general' );

		if ( get_woocommerce_currency() != 'USD' ) {
			echo '<div class="error">
				<p>' . sprintf( wp_kses_post( 'USPS requires that the <a href="%s">currency</a> is set to US Dollars.', 'wf-usps-woocommerce-shipping' ), esc_url( $admin_page ) ) . '</p>
			</div>';
		} elseif ( ! in_array( WC()->countries->get_base_country(), $this->domestic ) ) {
			echo '<div class="error">
				<p>' . sprintf( wp_kses_post( 'USPS requires that the <a href="%s">base country/region</a> is the United States.', 'wf-usps-woocommerce-shipping' ), esc_url( $admin_page ) ) . '</p>
			</div>';
		} elseif ( ! $this->origin && 'yes' == $this->enabled ) {
			echo '<div class="error">
				<p>' . esc_html_e( 'USPS is enabled, but the origin postcode has not been set.', 'wf-usps-woocommerce-shipping' ) . '</p>
			</div>';
		}
	}

	/**
	 * Admin_options function.
	 *
	 * @accesss public
	 * @return void
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();
		
		// Show settings
		parent::admin_options();
	}

	/**
	 * Generate_services_html function.
	 */
	public function generate_services_html() {
		ob_start();
		include  'html-wf-services.php' ;
		return ob_get_clean();
	}

	/**
	 * Validate_services_field function.
	 *
	 * @accesss public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_services_field( $key ) {
		$services = array();
		if ( ! ( isset( $_POST ) && isset( $_POST['_elex_ajax_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['_elex_ajax_nonce'] ), '_elex_usps_shipping_ajax_nonce' ) ) && isset( $_POST['usps_service'] ) ) {
			return;
		}
		foreach ( $this->services as $code => $service ) {
			if ( isset( $_POST['usps_service'][ $code ]['order'] ) ) {
				$services[ $code ] = array(
					// 'name'               => wc_clean( $settings['name'] ),
					'order' => wc_clean( sanitize_text_field( $_POST['usps_service'][ $code ]['order'] ) ),
				);
			}
			foreach ( $service['services'] as $key => $name ) {
				$services[ $code ][ $key ]['enabled'] = isset( $_POST['usps_service'][ $code ][ $key ]['enabled'] ) ? true : false;
			}
		}
		return $services;
	}

	/**
	 * Clear_transients function.
	 *
	 * @accesss public
	 * @return void
	 */
	public function clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_usps_quote_%') OR `option_name` LIKE ('_transient_timeout_usps_quote_%')" );
	}
	
	public function generate_marketing_content_html() {
		ob_start();
		$plugin_name = 'usps';
		include  'html-wf-market-content.php' ;
		return ob_get_clean();
	}


	/**
	* Tabs function
	*
	* @accesss public
	* @return void
	*/
	public function generate_usps_tabs_html() {
		$current_tab = ( ! empty( $_GET['subtab'] ) ) ? sanitize_text_field( $_GET['subtab'] ) : 'general';
		echo '
                <div class="wrap">
                    <script>
                    jQuery(function($){
                    show_selected_tab($(".tab_general"),"general");
                    $(".tab_general").on("click",function(){
                        return show_selected_tab($(this),"general");
                    });
                    $(".tab_rates").on("click",function(){
                        return show_selected_tab($(this),"rates");
                    });
                    $(".tab_labels").on("click",function(){
                    });
                    $(".tab_packing").on("click",function(){
                    });
                    $(".tab_gopremium").on("click",function(){                                                
                        return show_selected_tab($(this),"gopremium");
                    });
                    $(".tab_tracking").on("click",function(){                                                
                    });
                    function show_selected_tab($element,$tab)
                    {

                        $(".nav-tab").removeClass("nav-tab-active");
                        $element.addClass("nav-tab-active");             
                        $(".rates_tab_field").closest("tr,h3").hide();
                        $(".rates_tab_field").next("p").hide();

                        $(".general_tab_field").closest("tr,h3").hide();
                        $(".general_tab_field").next("p").hide();

                        $(".package_tab_field").closest("tr,h3").hide();
                        $(".package_tab_field").next("p").hide();

                        $(".label_tab_field").closest("tr,h3").hide();
                        $(".label_tab_field").next("p").hide();

                        $(".gopremium_tab_field").closest("tr,h3").hide();
                        $(".gopremium_tab_field").next("p").hide();

                        $(".tracking_tab_field").closest("tr,h3").hide();
                        $(".tracking_tab_field").next("p").hide();

                        $(".select2-search__field").closest("tr,h3").hide();
                        $(".select2-search__field").next("p").hide();                                     
                        if($tab=="gopremium")
                        {   
                            $(".marketing_content").show();
                        }else{
                            $(".marketing_content").hide();
                        }

                        $("."+$tab+"_tab_field").closest("tr,h3").show();
                        $("."+$tab+"_tab_field").next("p").show();

                        if($tab=="rates")
                            $("#woocommerce_wf_shipping_usps_availability").trigger("change");
                        
                        
                        
                        if($tab=="gopremium" || $tab=="package" || $tab=="tracking" || $tab=="label")
                        {
                            $(".woocommerce-save-button").hide();
                        }else
                        {
                            $(".woocommerce-save-button").show();
                        }
						
                        return false;
                    }   

                    });
                    </script>
                    <style>
                    .wrap {
                                min-height: 800px;
                            }
                    a.nav-tab{
                                cursor: default;
                    }
                    </style>
                    <hr class="wp-header-end">';
		$tabs = array(
			'general' => __( "General<span class='wf-super'></span>", 'wf-usps-stamps-woocommerce' ),
			'rates' => __( "Rates & Services<span class='wf-super'></span>", 'wf-usps-stamps-woocommerce' ),
			'packing' => __( "Packaging <span style='vertical-align: super;color:green;font-size:12px' >[Premium]</span>", 'wf-usps-stamps-woocommerce' ),
			'labels' => __( "Label Generation <span style='vertical-align: super;color:green;font-size:12px' >[Premium]</span>", 'wf-usps-stamps-woocommerce' ),
			'tracking' => __( "Tracking <span style='vertical-align: super;color:green;font-size:12px' >[Premium]</span>", 'wf-usps-stamps-woocommerce' ),
			'gopremium' => __( "Go Premium! <span class='wf-super'></span>", 'wf-usps-stamps-woocommerce' ),
		);
		$html = '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $stab => $name ) {
			$class = ( $stab == $current_tab ) ? 'nav-tab-active' : '';
			$style = ( $stab == $current_tab ) ? 'border-bottom: 1px solid transparent !important;' : '';
			$style = ( 'gopremium' == $stab ) ? $style . 'color:red; !important;' : '';
			$html .= '<a style="text-decoration:none !important;' . $style . '" class="nav-tab ' . $class . ' tab_' . $stab . '" >' . $name . '</a>';
		}
		$html .= '</h2>';
		echo wp_kses_post( $html );
	}
	/**
	 * Init_form_fields function.
	 *
	 * @accesss public
	 * @return void
	 */
	public function init_form_fields() {


		global $woocommerce;

		$shipping_classes = array();
		$classes          = get_terms( 'product_shipping_class', array( 'hide_empty' => '0' ) );
		$classes          = ( $classes ) ? $classes : array();

		foreach ( $classes as $class ) {
			if ( ! empty( $class->name ) ) {
				$shipping_classes[ $class->term_id ] = $class->name;
			}
		}

		$this->form_fields = array(
			'usps_wrapper' => array(
				'type' => 'usps_tabs',
			),
			'enabled' => array(
				'title' => __( 'Realtime Rates', 'wf-usps-woocommerce-shipping' ),
				'type' => 'checkbox',
				'label' => __( 'Enable', 'wf-usps-woocommerce-shipping' ),
				'default' => 'no',
				'description' => __( 'Enable realtime rates on the Cart/Checkout page.', 'wf-usps-woocommerce-shipping' ),
				'desc_tip' => true,
				'class' => 'general_tab_field',
			),
			'title' => array(
				'title' => __( 'Method Title', 'wf-usps-woocommerce-shipping' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wf-usps-woocommerce-shipping' ),
				'default' => __( 'USPS', 'wf-usps-woocommerce-shipping' ),
				'desc_tip' => true,
				'class' => 'rates_tab_field',
			),
			'availability' => array(
				'title' => __( 'Method Available to', 'wf-usps-woocommerce-shipping' ),
				'type' => 'select',
				'css' => 'padding: 0px;',
				'default' => 'all',
				'class' => 'rates_tab_field',
				'options' => array(
					'all' => __( 'All Countries', 'wf-usps-woocommerce-shipping' ),
					'specific' => __( 'Specific Countries', 'wf-usps-woocommerce-shipping' ),
				),
				
			),
			'countries' => array(
				'title' => __( 'Specific Countries', 'wf-usps-woocommerce-shipping' ),
				'type' => 'multiselect',
				'class' => 'chosen_select rates_tab_field',
				'css' => 'width: 450px;',
				'default' => '',
				'options' => $woocommerce->countries->get_allowed_countries(),
				'description' => __( 'Select the countries for which you want to avail the method.', 'wf-usps-woocommerce-shipping' ),
			),
			'origin' => array(
				'title' => __( 'Origin Postcode', 'wf-usps-woocommerce-shipping' ),
				'type' => 'text',
				'description' => __( 'Enter the postcode for the <strong>Shipper</strong>.', 'wf-usps-woocommerce-shipping' ),
				'default' => '',
				'desc_tip' => true,
				'class' => 'general_tab_field',
			),
			'api' => array(
				'title' => __( 'Common API Settings:', 'wf-usps-woocommerce-shipping' ),
				'type' => 'title',
				'description' => sprintf( wp_kses_post( 'You can obtain a USPS user ID by %s signing up on the USPS website %s, or just use ours by leaving the field blank.', 'wf-usps-woocommerce-shipping' ), '<a href="https://www.usps.com/">', '</a>' ),
				'class' => 'general_tab_field',
			),
			'user_id' => array(
				'title' => __( 'User ID', 'wf-usps-woocommerce-shipping' ),
				'type' => 'text',
				'description' => __( 'Obtained from USPS after getting an account.', 'wf-usps-woocommerce-shipping' ),
				'default' => '',
				'placeholder' => $this->default_user_id,
				'desc_tip' => true,
				'class' => 'general_tab_field',
			),
			'password'                                   => array(
				'title'       => __( 'Password', 'wf-usps-woocommerce-shipping' ),
				'type'        => 'password',
				'description' => __( 'Obtained from USPS after getting an account.', 'wf-usps-woocommerce-shipping' ),
				'class'       => 'general_tab_field',
				'default'     => '',
				'desc_tip'    => true,
			),
			'debug_mode' => array(
				'title' => __( 'Debug', 'wf-usps-woocommerce-shipping' ),
				'label' => __( 'Enable debug mode', 'wf-usps-woocommerce-shipping' ),
				'type' => 'checkbox',
				'default' => 'no',
				'description' => __( 'Enable debug mode to show debugging information on your cart/checkout.', 'wf-usps-woocommerce-shipping' ),
				'class' => 'general_tab_field',
			),
			'rates' => array(
				'title' => __( 'Rates API Settings:', 'wf-usps-woocommerce-shipping' ),
				'type' => 'title',
				'description' => __( 'The following settings determine the rates you offer your customers.', 'wf-usps-woocommerce-shipping' ),
				'class' => 'rates_tab_field',
			),
			'shippingrates' => array(
				'title' => __( 'Shipping Rates', 'wf-usps-woocommerce-shipping' ),
				'type' => 'select',
				'css' => 'padding: 0px;',
				'default' => 'ONLINE',
				'options' => array(
					'ONLINE' => __( 'Use Click-N-Ship Rates', 'wf-usps-woocommerce-shipping' ),
					'ALL' => __( 'Use OFFLINE rates', 'wf-usps-woocommerce-shipping' ),
				),
				'description' => __( 'Choose which rates to show your customers, Click-N-Ship (ONLINE) rates are normally cheaper than OFFLINE', 'wf-usps-woocommerce-shipping' ),
				'desc_tip' => true,
				'class' => 'rates_tab_field',
			),
			'fallback' => array(
				'title' => __( 'Fallback', 'wf-usps-woocommerce-shipping' ),
				'type' => 'text',
				'description' => __( 'If USPS returns no matching rates, offer this amount for shipping so that the user can still checkout. Leave blank to disable.', 'wf-usps-woocommerce-shipping' ),
				'default' => '',
				'class' => 'rates_tab_field',
				'desc_tip' => true,
			),
			'flat_rates'          => array(
				'title'           => __( 'Flat Rate: <span class="wf-super">[Premium]</span>', 'wf-usps-woocommerce-shipping' ),
				'type'            => 'title',
				'class'           => 'rates_tab_field',
				'description' => __( 'Flat Rates consists of several fields which are premium feature.', 'wf-usps-woocommerce-shipping' ),
			),
			'standard_rates' => array(
				'title' => __( 'Services, Rates and Packing:', 'wf-usps-woocommerce-shipping' ),
				'type' => 'title',
				'class' => 'rates_tab_field',
			),

			'offer_rates' => array(
				'title' => __( 'Offer Rates', 'wf-usps-woocommerce-shipping' ),
				'type' => 'select',
				'css' => 'padding: 0px;',
				'description' => '',
				'default' => 'all',
				'options' => array(
					'all' => __( 'Offer the customer all returned rates', 'wf-usps-woocommerce-shipping' ),
					'cheapest' => __( 'Offer the customer the cheapest rate only', 'wf-usps-woocommerce-shipping' ),
				),
				'class' => 'rates_tab_field',
			),
			'services'  => array(
				'type'            => 'services',
				'class'           => 'rates_tab_field',
			),
			'mediamail_restriction' => array(
				'title' => __( 'Allow media mail', 'wf-usps-woocommerce-shipping' ),
				'type' => 'multiselect',
				'class' => 'chosen_select general_tab_field',
				'css' => 'width: 450px;',
				'default' => '',
				'description' => __( 'Select the shipping classes for which you want to include Media Mail Rate.', 'wf-usps-woocommerce-shipping' ),
				'options' => $shipping_classes,
				'custom_attributes' => array(
					'data-placeholder' => __( 'No restrictions', 'wf-usps-woocommerce-shipping' ),
				),
				'desc_tip' => true,
			),
			'gopremium'  => array(
				'type'            => 'marketing_content',
			),  
				
		);
	}

	public function test_user_id() {

		if ( ( ! ( isset( $_POST ) && isset( $_POST['_elex_ajax_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['_elex_ajax_nonce'] ), '_elex_usps_shipping_ajax_nonce' ) && isset( $_POST['woocommerce_elex_shipping_usps_user_id'] ) && isset( $_POST['woocommerce_elex_shipping_usps_password'] ) ) ) ) {
			
			return;
			
		}
		$example_xml  = '<RateV4Request USERID="' . sanitize_text_field( $_POST['woocommerce_elex_shipping_usps_user_id'] ) . '" PASSWORD="' . sanitize_text_field( $_POST['woocommerce_elex_shipping_usps_password'] ) . '">';
		$example_xml .= '<Revision>2</Revision>';
		$example_xml .= '<Package ID="1">';
		$example_xml .= '<Service>PRIORITY</Service>';
		$example_xml .= '<ZipOrigination>97201</ZipOrigination>';
		$example_xml .= '<ZipDestination>44101</ZipDestination>';
		$example_xml .= '<Pounds>1</Pounds>';
		$example_xml .= '<Ounces>0</Ounces>';
		$example_xml .= '<Container />';
		$example_xml .= '<Size>REGULAR</Size>';
		$example_xml .= '</Package>';
		$example_xml .= '</RateV4Request>';

		$response = wp_remote_post(
			$this->endpoint,
			array(
				'body' => 'API=RateV4&XML=' . $example_xml,
			)
		);

		if ( is_wp_error( $response ) ) {
			return;
		}
		$xml = $this->get_parsed_xml( $response['body'] );
		if ( ! ( $xml ) ) {
			return;
		}
		if ( ! is_object( $xml ) && ! is_a( $xml, 'SimpleXMLElement' ) ) {
			return;
		}

		// 80040B1A is an Authorization failure
		if ( '80040B1A' !== $xml->Number->__toString() ) {
			return;
		}

		echo wp_kses_post(
			'<div class="error">
			<p>' . __( 'The USPS User ID you entered is invalid. Please make sure you entered a valid ID (<a href="https://www.usps.com/business/web-tools-apis/welcome.htm">which can be obtained here</a>). Our User ID will be used instead.', 'wf-usps-woocommerce-shipping' ) . '</p>
		</div>'
		);


	}

	/**
	 * Get Parsed XML response
	 *
	 * @param  string $xml
	 * @return string|bool
	 */
	private function get_parsed_xml( $xml ) {
		if ( ! class_exists( 'ELEX_Safe_DOMDocument' ) ) {
			include_once  'class-elex-safe-domdocument.php' ;
		}

		libxml_use_internal_errors( true );

		$dom     = new ELEX_Safe_DOMDocument();
		$success = $dom->loadXML( $xml );

		if ( ! $success ) {
			if ( $this->debug ) {
				trigger_error( 'wpcom_safe_simplexml_load_string(): Error loading XML string', E_USER_WARNING );
			}
			return false;
		}

		if ( isset( $dom->doctype ) ) {
			if ( $this->debug ) {
				trigger_error( 'wpcom_safe_simplexml_import_dom(): Unsafe DOCTYPE Detected', E_USER_WARNING );
			}
			return false;
		}

		return simplexml_import_dom( $dom, 'SimpleXMLElement' );
	}

	/**
	 * Calculate_shipping function.
	 *
	 * @accesss public
	 * @param mixed $package
	 * @return void
	 */
	public function calculate_shipping( $package = array() ) {
		global $woocommerce;
		$valid_response = true;
		
		$this->rates               = array();
		$this->unpacked_item_costs = 0;
		$domestic                  = in_array( $package['destination']['country'], $this->domestic ) ? true : false;

		$this->debug( __( 'USPS debug mode is on - to hide these messages, turn debug mode off in the settings.', 'wf-usps-woocommerce-shipping' ) );

		if ( $this->enable_standard_services ) {

			$package_requests = $this->get_package_requests( $package );
			$api              = $domestic ? 'RateV4' : 'IntlRateV2';
			libxml_use_internal_errors( true );

			if ( $package_requests ) {

				$request  = '<' . $api . 'Request USERID="' . $this->user_id . '" PASSWORD="' . $this->password . '">' . "\n";
				$request .= '<Revision>2</Revision>' . "\n";

				foreach ( $package_requests as $key => $package_request ) {
					$request .= $package_request['request_data'];
				}

				$request .= '</' . $api . 'Request>' . "\n";
				$request  = 'API=' . $api . '&XML=' . str_replace( array( "\n", "\r" ), '', $request );

				$transient       = 'usps_quote_' . md5( $request );
				$cached_response = get_transient( $transient );

				$this->debug( 'USPS REQUEST: <pre>' . print_r( htmlspecialchars( $request ), true ) . '</pre>' );

				if ( false !== $cached_response ) {
					$response = $cached_response;

					$this->debug( 'USPS CACHED RESPONSE: <pre style="height: 200px; overflow:auto;">' . print_r( htmlspecialchars( $response ), true ) . '</pre>' );
				} else {
					$response = wp_remote_post(
						$this->endpoint,
						array(
							'timeout' => 70,
							'sslverify' => 0,
							'body' => $request,
						)
					);

					if ( is_wp_error( $response ) ) {
						$error_string = $response->get_error_message();
						$this->debug( 'USPS REQUEST FAILED' . $error_string );
						if ( $this->fallback && ! empty( $this->custom_services ) ) {
							$this->add_rate(
								array(
									'id' => $this->id . '_fallback',
									'label' => $this->title,
									'cost' => $this->fallback,
									'sort' => 0,
								)
							);
						}
						$response = false;
					} else {
						$response = $response['body'];

						$this->debug( 'USPS RESPONSE: <pre style="height: 200px; overflow:auto;">' . print_r( htmlspecialchars( $response ), true ) . '</pre>' );

						set_transient( $transient, $response, DAY_IN_SECONDS * 30 );
					}
				}
			
				if ( $response ) {
					$xml = $this->get_parsed_xml( $response );
					if ( ! ( $xml ) ) {
						$this->debug( 'Failed loading XML', 'error' );
					}

					if ( ! is_object( $xml ) && ! is_a( $xml, 'SimpleXMLElement' ) ) {
						$this->debug( 'Invalid XML response format', 'error' );
					}

					// Our XML response is as we like it. Begin parsing.
					$usps_packages = $xml;

					if ( ! empty( $usps_packages ) ) {
						foreach ( $usps_packages as $usps_package ) {
							if ( ! $usps_package || ! is_object( $usps_package ) ) {
								continue;
							}
							// Get package data
							$data_parts = explode( ':', $usps_package->attributes()->ID );
							if ( count( $data_parts ) < 6 ) {
								$valid_response = false; // when the request has invalid ID or no valid address was found.
								continue;
							}

							list( $package_item_id, $cart_item_qty, $package_length, $package_width, $package_height, $package_weight ) = $data_parts;

							$quotes = $usps_package->children();

							if ( $this->debug ) {
								$found_quotes = array();

								foreach ( $quotes as $quote ) {
									if ( $domestic ) {
										$code = strval( $quote->attributes()->CLASSID );
										$name = strip_tags( htmlspecialchars_decode( (string) $quote->{'MailService'} ) );
									} else {
										$code = strval( $quote->attributes()->ID );
										$name = strip_tags( htmlspecialchars_decode( (string) $quote->{'SvcDescription'} ) );
									}

									if ( $name && $code ) {
										$found_quotes[ $code ] = $name;
									} elseif ( $name ) {
										$found_quotes[ $code . '-' . sanitize_title( $name ) ] = $name;
									}
								}

								if ( $found_quotes ) {
									ksort( $found_quotes );
									$found_quotes_html = '';
									foreach ( $found_quotes as $code => $name ) {
										if ( ! strstr( $name, 'Flat Rate' ) ) {
											$found_quotes_html .= '<li>' . $code . ' - ' . $name . '</li>';
										}
									}
									$this->debug( 'The following quotes were returned by USPS: <ul>' . $found_quotes_html . '</ul> If any of these do not display, they may not be enabled in USPS settings.', 'success' );
								}
							}

							// Loop our known services
							foreach ( $this->services as $service => $values ) {

								if ( $domestic && strpos( $service, 'D_' ) !== 0 ) {
									continue;
								}

								if ( ! $domestic && strpos( $service, 'I_' ) !== 0 ) {
									continue;
								}

								$rate_code      = (string) $service;
								$rate_id        = $this->id . ':' . $rate_code;
								$rate_name      = (string) $values['name'] . ' (' . $this->title . ')';
								$rate_cost      = null;
								$svc_commitment = null;

								foreach ( $quotes as $quote ) {

									if ( $domestic ) {
										$code = strval( $quote->attributes()->CLASSID );
									} else {
										$code = strval( $quote->attributes()->ID );
									}

									if ( '' !== $code && in_array( $code, array_keys( $values['services'] ) ) ) {

										if ( $domestic ) {
											if ( $this->disable_commercial_rates ) {
												if ( ( (float) $quote->{'Rate'} ) > 0.0 ) {
													$cost = (float) $quote->{'Rate'} * $cart_item_qty;
												} else {
													continue;
												}
											} else {
												if ( ! empty( $quote->{'CommercialRate'} ) ) {
													$cost = (float) $quote->{'CommercialRate'} * $cart_item_qty;
												} else {
													$cost = (float) $quote->{'Rate'} * $cart_item_qty;
												}
											}
										} else {

											if ( ! empty( $quote->{'CommercialPostage'} ) ) {
												$cost = (float) $quote->{'CommercialPostage'} * $cart_item_qty;
											} else {
												$cost = (float) $quote->{'Postage'} * $cart_item_qty;
											}
										}

										// Cost adjustment %
										if ( ! empty( $this->custom_services[ $rate_code ][ $code ]['adjustment_percent'] ) ) {
											$cost = round( $cost + ( $cost * ( floatval( $this->custom_services[ $rate_code ][ $code ]['adjustment_percent'] ) / 100 ) ), wc_get_price_decimals() );
										}

										// Cost adjustment
										if ( ! empty( $this->custom_services[ $rate_code ][ $code ]['adjustment'] ) ) {
											$cost = round( $cost + floatval( $this->custom_services[ $rate_code ][ $code ]['adjustment'] ), wc_get_price_decimals() );
										}

										// Enabled check
										if ( isset( $this->custom_services[ $rate_code ][ $code ] ) && empty( $this->custom_services[ $rate_code ][ $code ]['enabled'] ) ) {
											continue;
										}

										if ( $domestic ) {
											switch ( $code ) {
												// Handle first class - there are multiple d0 rates and we need to handle size retrictions because the USPS API doesn't do this.
												case '0':
													$service_name = strip_tags( htmlspecialchars_decode( (string) $quote->{'MailService'} ) );
														/** 
														 * Fire a filter hook for first class service
														 *
														 * @since 2002
														 * @param $package
														 */  
													if ( apply_filters( 'usps_disable_first_class_rate_' . sanitize_title( $service_name ), false ) ) {
														continue 2;
													}

													if ( strstr( $service_name, 'Package Service - Retail' ) ) {
														if ( 'ONLINE' == $this->settings['shippingrates'] ) {
															continue 2;
														}
													}
													break;
												// Media mail has restrictions - check here
												case '6':
													if ( count( $this->mediamail_restriction ) > 0 ) {
														$invalid = false;

														foreach ( $package['contents'] as $package_item ) {
															if ( ! in_array( $package_item['data']->get_shipping_class_id(), $this->mediamail_restriction ) ) {
																// Checking if product is virutal. If it is,
																// then don't skip media mail.
																if ( ! $package_item['data']->is_virtual() ) {
																	$invalid = true;
																}
															}
														}

														if ( $invalid ) {
															$this->debug( 'Skipping media mail' );
														}

														if ( $invalid ) {
															continue 2;
														}
													}
													break;
											}
										}

										if ( $domestic && $package_length && $package_width && $package_height ) {
											switch ( $code ) {
												// Regional rate boxes need additonal checks to deal with USPS's API
												case '47':
													if ( ( $package_length > 10 || $package_width > 7 || $package_height > 4.75 ) && ( $package_length > 12.875 || $package_width > 10.9375 || $package_height > 2.365 ) ) {
														continue 2;
													} else {
														// Valid
														break;
													}
													break;
												case '49':
													if ( ( $package_length > 12 || $package_width > 10.25 || $package_height > 5 ) && ( $package_length > 15.875 || $package_width > 14.375 || $package_height > 2.875 ) ) {
														continue 2;
													} else {
														// Valid
														break;
													}
													break;
												case '58':
													if ( $package_length > 14.75 || $package_width > 11.75 || $package_height > 11.5 ) {
														continue 2;
													} else {
														// Valid
														break;
													}
													break;
												// Handle first class - there are multiple d0 rates and we need to handle size retrictions because the API doesn't do this for us!
												case '0':
													$service_name = strip_tags( htmlspecialchars_decode( (string) $quote->{'MailService'} ) );

													if ( strstr( $service_name, 'Postcards' ) ) {

														if ( $package_length > 6 || $package_length < 5 ) {
															continue 2;
														}
														if ( $package_width > 4.25 || $package_width < 3.5 ) {
															continue 2;
														}
														if ( $package_height > 0.016 || $package_height < 0.007 ) {
															continue 2;
														}
													} elseif ( strstr( $service_name, 'Large Envelope' ) ) {

														if ( $package_length > 15 || $package_length < 11.5 ) {
															continue 2;
														}
														if ( $package_width > 12 || $package_width < 6 ) {
															continue 2;
														}
														if ( $package_height > 0.75 || $package_height < 0.25 ) {
															continue 2;
														}
													} elseif ( strstr( $service_name, 'Letter' ) ) {

														if ( $package_length > 11.5 || $package_length < 5 ) {
															continue 2;
														}
														if ( $package_width > 6.125 || $package_width < 3.5 ) {
															continue 2;
														}
														if ( $package_height > 0.25 || $package_height < 0.007 ) {
															continue 2;
														}
													} elseif ( strstr( $service_name, 'Package Service - Retail' ) ) {
														if ( 'ONLINE' != $this->settings['shippingrates'] ) {
															if ( 'oz' == $this->weight_unit ) {
																if ( $package_weight > 13 ) {
																	continue 2;
																}
															} else {
																$package_weight_in_oz = wc_get_weight( $package_weight, 'oz', $this->weight_unit );
																if ( $package_weight_in_oz > 13 ) {
																	continue 2;
																}
															}
														}                                                   
													} else {
														continue 2;
													}
													break;
											}
										}

										if ( is_null( $rate_cost ) ) {
											$rate_cost      = $cost;
											$svc_commitment = $quote->SvcCommitments;
										} elseif ( $cost < $rate_cost ) {
											$rate_cost      = $cost;
											$svc_commitment = $quote->SvcCommitments;
										}
									}
								}

								if ( $rate_cost ) {
									if ( ! empty( $svc_commitment ) && strstr( $svc_commitment, 'days' ) ) {
										$rate_name .= ' (' . current( explode( 'days', $svc_commitment ) ) . ' days)';
									}
									$this->prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost );
								}
							}
						}
					} else {
						// No rates
						$this->debug( 'Invalid request; no rates returned', 'error' );
					}
				}
			}
		   
			// Ensure rates were found for all packages
			if ( $this->found_rates ) {
				foreach ( $this->found_rates as $key => $value ) {
					if ( $value['packages'] < count( $package_requests ) ) {
						$this->debug( "Unsetting {$key} - too few packages.", 'error' );
						unset( $this->found_rates[ $key ] );
					}

					if ( $this->unpacked_item_costs && ! empty( $this->found_rates[ $key ] ) ) {
						$this->debug( sprintf( wp_kses_post( 'Adding unpacked item costs to rate %s', 'wf-usps-woocommerce-shipping' ), $key ) );
						$this->found_rates[ $key ]['cost'] += $this->unpacked_item_costs;
					}
				}
			}
		}
		// Add rates
		if ( $this->found_rates ) {

			// Only offer one priority rate
			if ( isset( $this->found_rates['usps:D_PRIORITY_MAIL'] ) && isset( $this->found_rates['usps:flat_rate_box_priority'] ) ) {
				if ( $this->found_rates['usps:flat_rate_box_priority']['cost'] < $this->found_rates['usps:D_PRIORITY_MAIL']['cost'] ) {
					$this->debug( 'Unsetting PRIORITY MAIL api rate - flat rate box is cheaper.', 'error' );
					unset( $this->found_rates['usps:D_PRIORITY_MAIL'] );
				} else {
					$this->debug( 'Unsetting PRIORITY MAIL flat rate - api rate is cheaper.', 'error' );
					unset( $this->found_rates['usps:flat_rate_box_priority'] );
				}
			}

			if ( isset( $this->found_rates['usps:D_EXPRESS_MAIL'] ) && isset( $this->found_rates['usps:flat_rate_box_express'] ) ) {
				if ( $this->found_rates['usps:flat_rate_box_express']['cost'] < $this->found_rates['usps:D_EXPRESS_MAIL']['cost'] ) {
					$this->debug( 'Unsetting PRIORITY MAIL EXPRESS api rate - flat rate box is cheaper.', 'error' );
					unset( $this->found_rates['usps:D_EXPRESS_MAIL'] );
				} else {
					$this->debug( 'Unsetting PRIORITY MAIL EXPRESS flat rate - api rate is cheaper.', 'error' );
					unset( $this->found_rates['usps:flat_rate_box_express'] );
				}
			}

			if ( isset( $this->found_rates['usps:I_PRIORITY_MAIL'] ) && isset( $this->found_rates['usps:flat_rate_box_priority'] ) ) {
				if ( $this->found_rates['usps:flat_rate_box_priority']['cost'] < $this->found_rates['usps:I_PRIORITY_MAIL']['cost'] ) {
					$this->debug( 'Unsetting PRIORITY MAIL api rate - flat rate box is cheaper.', 'error' );
					unset( $this->found_rates['usps:I_PRIORITY_MAIL'] );
				} else {
					$this->debug( 'Unsetting PRIORITY MAIL flat rate - api rate is cheaper.', 'error' );
					unset( $this->found_rates['usps:flat_rate_box_priority'] );
				}
			}

			if ( isset( $this->found_rates['usps:I_EXPRESS_MAIL'] ) && isset( $this->found_rates['usps:flat_rate_box_express'] ) ) {
				if ( $this->found_rates['usps:flat_rate_box_express']['cost'] < $this->found_rates['usps:I_EXPRESS_MAIL']['cost'] ) {
					$this->debug( 'Unsetting PRIORITY MAIL EXPRESS api rate - flat rate box is cheaper.', 'error' );
					unset( $this->found_rates['usps:I_EXPRESS_MAIL'] );
				} else {
					$this->debug( 'Unsetting PRIORITY MAIL EXPRESS flat rate - api rate is cheaper.', 'error' );
					unset( $this->found_rates['usps:flat_rate_box_express'] );
				}
			}
			
			if ( 'all' == $this->offer_rates ) {

				uasort( $this->found_rates, array( $this, 'sort_rates' ) );

				foreach ( $this->found_rates as $key => $rate ) {
					$this->add_rate( $rate );
				}
			} else {

				$cheapest_rate = '';

				foreach ( $this->found_rates as $key => $rate ) {
					if ( ! $cheapest_rate || $cheapest_rate['cost'] > $rate['cost'] ) {
						$cheapest_rate = $rate;
					}
				}

				$cheapest_rate['label'] = $this->title;

				$this->add_rate( $cheapest_rate );
			}       
		}
	}

	/**
	 * Prepare_rate function.
	 *
	 * @accesss private
	 * @param mixed $rate_code
	 * @param mixed $rate_id
	 * @param mixed $rate_name
	 * @param mixed $rate_cost
	 * @return void
	 */
	private function prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost ) {

		// Name adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['name'] ) ) {
			$rate_name = $this->custom_services[ $rate_code ]['name'];
		}

		// Merging
		if ( isset( $this->found_rates[ $rate_id ] ) ) {
			$rate_cost = $rate_cost + $this->found_rates[ $rate_id ]['cost'];
			$packages  = 1 + $this->found_rates[ $rate_id ]['packages'];
		} else {
			$packages = 1;
		}

		// Sort
		if ( isset( $this->custom_services[ $rate_code ]['order'] ) ) {
			$sort = $this->custom_services[ $rate_code ]['order'];
		} else {
			$sort = 999;
		}

		$this->found_rates[ $rate_id ] = array(
			'id' => $rate_id,
			'label' => $rate_name,
			'cost' => $rate_cost,
			'sort' => $sort,
			'packages' => $packages,
		);
	}

	/**
	 * Sort_rates function.
	 *
	 * @accesss public
	 * @param mixed $a
	 * @param mixed $b
	 * @return void
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) {
			return 0;
		}
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}

	/**
	 * Get_request function.
	 *
	 * @accesss private
	 * @return void
	 */
	private function get_package_requests( $package ) {

		// Choose selected packing
		switch ( $this->packing_method ) {
			case 'box_packing':
				$requests = $this->box_shipping( $package );
				break;
			case 'weight_based':
				$requests = $this->weight_based_shipping( $package );
				break;
			case 'per_item':
			default:
				$requests = $this->per_item_shipping( $package );
				break;
		}

		return $requests;
	}

	/**
	 * Per_item_shipping function.
	 *
	 * @accesss private
	 * @param mixed $package
	 * @return void
	 */
	private function per_item_shipping( $package ) {
		global $woocommerce;

		$requests = array();
		$domestic = in_array( $package['destination']['country'], $this->domestic ) ? true : false;

		// Get weight of order
		foreach ( $package['contents'] as $item_id => $values ) {
			$packed_items   = array();
			$values['data'] = $this->wf_load_product( $values['data'] );
			if ( ! $values['data']->needs_shipping() ) {
				$this->debug( sprintf( wp_kses_post( 'Product # is virtual. Skipping.', 'wf-usps-woocommerce-shipping' ), $item_id ) );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( wp_kses_post( 'Product # is missing weight. Using 1lb.', 'wf-usps-woocommerce-shipping' ), $item_id ) );

				$weight = 1;
			} else {
				$weight = wc_get_weight( $values['data']->get_weight(), 'lbs' );
			}

			$size = 'REGULAR';

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( wc_get_dimension( $values['data']->length, 'in' ), wc_get_dimension( $values['data']->height, 'in' ), wc_get_dimension( $values['data']->width, 'in' ) );

				sort( $dimensions );

				if ( max( $dimensions ) > 12 ) {
					$size = 'LARGE';
				}           
			} else {
				$dimensions = array( 0, 0, 0 );
			}

			if ( $domestic ) {

				$request  = '<Package ID="' . $this->generate_package_id( $item_id, $values['quantity'], $dimensions[2], $dimensions[1], $dimensions[0], $weight ) . '">' . "\n";
				$request .= '	<Service>' . ( ! $this->settings['shippingrates'] ? 'ONLINE' : $this->settings['shippingrates'] ) . '</Service>' . "\n";
				$request .= '	<ZipOrigination>' . str_replace( ' ', '', strtoupper( $this->origin ) ) . '</ZipOrigination>' . "\n";
				$request .= '	<ZipDestination>' . strtoupper( substr( $package['destination']['postcode'], 0, 5 ) ) . '</ZipDestination>' . "\n";
				$request .= '	<Pounds>' . floor( $weight ) . '</Pounds>' . "\n";
				$request .= '	<Ounces>' . number_format( ( $weight - floor( $weight ) ) * 16, 2 ) . '</Ounces>' . "\n";

				if ( 'LARGE' === $size ) {
					$request .= '	<Container>RECTANGULAR</Container>' . "\n";
				} else {
					$request .= '	<Container />' . "\n";
				}

				$request .= '	<Size>' . $size . '</Size>' . "\n";
				$request .= '	<Width>' . $dimensions[1] . '</Width>' . "\n";
				$request .= '	<Length>' . $dimensions[2] . '</Length>' . "\n";
				$request .= '	<Height>' . $dimensions[0] . '</Height>' . "\n";
				$request .= '	<Girth></Girth>' . "\n";
				$request .= '	<Machinable>true</Machinable> ' . "\n";
				$request .= '	<ShipDate>' . gmdate( 'd-M-Y', ( current_time( 'timestamp' ) + ( 60 * 60 * 24 ) ) ) . '</ShipDate>' . "\n";
				$request .= '</Package>' . "\n";
			} else {

				$request  = '<Package ID="' . $this->generate_package_id( $item_id, $values['quantity'], $dimensions[2], $dimensions[1], $dimensions[0], $weight ) . '">' . "\n";
				$request .= '	<Pounds>' . floor( $weight ) . '</Pounds>' . "\n";
				$request .= '	<Ounces>' . number_format( ( $weight - floor( $weight ) ) * 16, 2 ) . '</Ounces>' . "\n";
				$request .= '	<Machinable>true</Machinable> ' . "\n";
				$request .= '	<MailType>Package</MailType>' . "\n";
				$request .= '	<ValueOfContents>' . $values['data']->get_price() . '</ValueOfContents>' . "\n";
				$request .= '	<Country>' . $this->get_country_name( $package['destination']['country'] ) . '</Country>' . "\n";

				$request .= '	<Container>RECTANGULAR</Container>' . "\n";

				$request .= '	<Size>' . $size . '</Size>' . "\n";
				$request .= '	<Width>' . $dimensions[1] . '</Width>' . "\n";
				$request .= '	<Length>' . $dimensions[2] . '</Length>' . "\n";
				$request .= '	<Height>' . $dimensions[0] . '</Height>' . "\n";
				$request .= '	<Girth></Girth>' . "\n";
				$request .= '	<OriginZip>' . str_replace( ' ', '', strtoupper( $this->origin ) ) . '</OriginZip>' . "\n";
				$request .= '	<CommercialFlag>' . ( 'ONLINE' == $this->settings['shippingrates'] ? 'Y' : 'N' ) . '</CommercialFlag>' . "\n";
				$request .= '</Package>' . "\n";
			}
			$item_data = wc_get_product( $item_id );

			if ( $item_data ) {// Front-end price call doesn't need this data
				$item_id                = $item_data->variation_id ? $item_data->variation_id : $item_data->id;
				$packed_items[ $item_id ] = array(
					'product_name' => $item_data->get_title(),
					'qty' => 1,
				);
				if ( $item_data->variation_id ) {
					$packed_items[ $item_id ]['variation_text'] = $this->wf_get_variation_data_from_variation_id( $item_data->variation_id );
				}
			}
			$package_info = array(
				'items' => $packed_items,
				'dimension' => array(
					'length' => $dimensions[2],
					'width' => $dimensions[1],
					'height' => $dimensions[0],
					'weight' => $weight,
				),
				'units' => array(
					'dimension' => 'in',
					'weight' => 'lbs',
				),
			);
			$requests[]   = array(
				'request_data' => $request,
				'package_info' => $package_info,
			);
		}
		return $requests;
	}

	/**
	 * Generate a package ID for the request
	 *
	 * Contains qty and dimension info so we can look at it again later when it comes back from USPS if needed
	 *
	 * @return string
	 */
	public function generate_package_id( $id, $qty, $length, $width, $height, $weight ) {
		return implode( ':', array( $id, $qty, $length, $width, $height, $weight ) );
	}

	/**
	 * Get_country_name function.
	 *
	 * @accesss private
	 * @return void
	 */
	public function get_country_name( $code ) {
			/** 
		 * Fire a filter hook for usps_country
		 *
		 * @since 2002
		 * @param $package
		 */  
		$countries = apply_filters(
			'usps_countries',
			array(
				'AF' => __( 'Afghanistan', 'wf-usps-woocommerce-shipping' ),
				'AX' => __( '&#197;land Islands', 'wf-usps-woocommerce-shipping' ),
				'AL' => __( 'Albania', 'wf-usps-woocommerce-shipping' ),
				'DZ' => __( 'Algeria', 'wf-usps-woocommerce-shipping' ),
				'AD' => __( 'Andorra', 'wf-usps-woocommerce-shipping' ),
				'AO' => __( 'Angola', 'wf-usps-woocommerce-shipping' ),
				'AI' => __( 'Anguilla', 'wf-usps-woocommerce-shipping' ),
				'AQ' => __( 'Antarctica', 'wf-usps-woocommerce-shipping' ),
				'AG' => __( 'Antigua and Barbuda', 'wf-usps-woocommerce-shipping' ),
				'AR' => __( 'Argentina', 'wf-usps-woocommerce-shipping' ),
				'AM' => __( 'Armenia', 'wf-usps-woocommerce-shipping' ),
				'AW' => __( 'Aruba', 'wf-usps-woocommerce-shipping' ),
				'AU' => __( 'Australia', 'wf-usps-woocommerce-shipping' ),
				'AT' => __( 'Austria', 'wf-usps-woocommerce-shipping' ),
				'AZ' => __( 'Azerbaijan', 'wf-usps-woocommerce-shipping' ),
				'BS' => __( 'Bahamas', 'wf-usps-woocommerce-shipping' ),
				'BH' => __( 'Bahrain', 'wf-usps-woocommerce-shipping' ),
				'BD' => __( 'Bangladesh', 'wf-usps-woocommerce-shipping' ),
				'BB' => __( 'Barbados', 'wf-usps-woocommerce-shipping' ),
				'BY' => __( 'Belarus', 'wf-usps-woocommerce-shipping' ),
				'BE' => __( 'Belgium', 'wf-usps-woocommerce-shipping' ),
				'PW' => __( 'Belau', 'wf-usps-woocommerce-shipping' ),
				'BZ' => __( 'Belize', 'wf-usps-woocommerce-shipping' ),
				'BJ' => __( 'Benin', 'wf-usps-woocommerce-shipping' ),
				'BM' => __( 'Bermuda', 'wf-usps-woocommerce-shipping' ),
				'BT' => __( 'Bhutan', 'wf-usps-woocommerce-shipping' ),
				'BO' => __( 'Bolivia', 'wf-usps-woocommerce-shipping' ),
				'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wf-usps-woocommerce-shipping' ),
				'BA' => __( 'Bosnia and Herzegovina', 'wf-usps-woocommerce-shipping' ),
				'BW' => __( 'Botswana', 'wf-usps-woocommerce-shipping' ),
				'BV' => __( 'Bouvet Island', 'wf-usps-woocommerce-shipping' ),
				'BR' => __( 'Brazil', 'wf-usps-woocommerce-shipping' ),
				'IO' => __( 'British Indian Ocean Territory', 'wf-usps-woocommerce-shipping' ),
				'VG' => __( 'British Virgin Islands', 'wf-usps-woocommerce-shipping' ),
				'BN' => __( 'Brunei', 'wf-usps-woocommerce-shipping' ),
				'BG' => __( 'Bulgaria', 'wf-usps-woocommerce-shipping' ),
				'BF' => __( 'Burkina Faso', 'wf-usps-woocommerce-shipping' ),
				'BI' => __( 'Burundi', 'wf-usps-woocommerce-shipping' ),
				'KH' => __( 'Cambodia', 'wf-usps-woocommerce-shipping' ),
				'CM' => __( 'Cameroon', 'wf-usps-woocommerce-shipping' ),
				'CA' => __( 'Canada', 'wf-usps-woocommerce-shipping' ),
				'CV' => __( 'Cape Verde', 'wf-usps-woocommerce-shipping' ),
				'KY' => __( 'Cayman Islands', 'wf-usps-woocommerce-shipping' ),
				'CF' => __( 'Central African Republic', 'wf-usps-woocommerce-shipping' ),
				'TD' => __( 'Chad', 'wf-usps-woocommerce-shipping' ),
				'CL' => __( 'Chile', 'wf-usps-woocommerce-shipping' ),
				'CN' => __( 'China', 'wf-usps-woocommerce-shipping' ),
				'CX' => __( 'Christmas Island', 'wf-usps-woocommerce-shipping' ),
				'CC' => __( 'Cocos (Keeling) Islands', 'wf-usps-woocommerce-shipping' ),
				'CO' => __( 'Colombia', 'wf-usps-woocommerce-shipping' ),
				'KM' => __( 'Comoros', 'wf-usps-woocommerce-shipping' ),
				'CG' => __( 'Congo (Brazzaville)', 'wf-usps-woocommerce-shipping' ),
				'CD' => __( 'Congo (Kinshasa)', 'wf-usps-woocommerce-shipping' ),
				'CK' => __( 'Cook Islands', 'wf-usps-woocommerce-shipping' ),
				'CR' => __( 'Costa Rica', 'wf-usps-woocommerce-shipping' ),
				'HR' => __( 'Croatia', 'wf-usps-woocommerce-shipping' ),
				'CU' => __( 'Cuba', 'wf-usps-woocommerce-shipping' ),
				'CW' => __( 'Cura&Ccedil;ao', 'wf-usps-woocommerce-shipping' ),
				'CY' => __( 'Cyprus', 'wf-usps-woocommerce-shipping' ),
				'CZ' => __( 'Czech Republic', 'wf-usps-woocommerce-shipping' ),
				'DK' => __( 'Denmark', 'wf-usps-woocommerce-shipping' ),
				'DJ' => __( 'Djibouti', 'wf-usps-woocommerce-shipping' ),
				'DM' => __( 'Dominica', 'wf-usps-woocommerce-shipping' ),
				'DO' => __( 'Dominican Republic', 'wf-usps-woocommerce-shipping' ),
				'EC' => __( 'Ecuador', 'wf-usps-woocommerce-shipping' ),
				'EG' => __( 'Egypt', 'wf-usps-woocommerce-shipping' ),
				'SV' => __( 'El Salvador', 'wf-usps-woocommerce-shipping' ),
				'GQ' => __( 'Equatorial Guinea', 'wf-usps-woocommerce-shipping' ),
				'ER' => __( 'Eritrea', 'wf-usps-woocommerce-shipping' ),
				'EE' => __( 'Estonia', 'wf-usps-woocommerce-shipping' ),
				'ET' => __( 'Ethiopia', 'wf-usps-woocommerce-shipping' ),
				'FK' => __( 'Falkland Islands', 'wf-usps-woocommerce-shipping' ),
				'FO' => __( 'Faroe Islands', 'wf-usps-woocommerce-shipping' ),
				'FJ' => __( 'Fiji', 'wf-usps-woocommerce-shipping' ),
				'FI' => __( 'Finland', 'wf-usps-woocommerce-shipping' ),
				'FR' => __( 'France', 'wf-usps-woocommerce-shipping' ),
				'GF' => __( 'French Guiana', 'wf-usps-woocommerce-shipping' ),
				'PF' => __( 'French Polynesia', 'wf-usps-woocommerce-shipping' ),
				'TF' => __( 'French Southern Territories', 'wf-usps-woocommerce-shipping' ),
				'GA' => __( 'Gabon', 'wf-usps-woocommerce-shipping' ),
				'GM' => __( 'Gambia', 'wf-usps-woocommerce-shipping' ),
				'GE' => __( 'Georgia', 'wf-usps-woocommerce-shipping' ),
				'DE' => __( 'Germany', 'wf-usps-woocommerce-shipping' ),
				'GH' => __( 'Ghana', 'wf-usps-woocommerce-shipping' ),
				'GI' => __( 'Gibraltar', 'wf-usps-woocommerce-shipping' ),
				'GR' => __( 'Greece', 'wf-usps-woocommerce-shipping' ),
				'GL' => __( 'Greenland', 'wf-usps-woocommerce-shipping' ),
				'GD' => __( 'Grenada', 'wf-usps-woocommerce-shipping' ),
				'GP' => __( 'Guadeloupe', 'wf-usps-woocommerce-shipping' ),
				'GT' => __( 'Guatemala', 'wf-usps-woocommerce-shipping' ),
				'GG' => __( 'Guernsey', 'wf-usps-woocommerce-shipping' ),
				'GN' => __( 'Guinea', 'wf-usps-woocommerce-shipping' ),
				'GW' => __( 'Guinea-Bissau', 'wf-usps-woocommerce-shipping' ),
				'GY' => __( 'Guyana', 'wf-usps-woocommerce-shipping' ),
				'HT' => __( 'Haiti', 'wf-usps-woocommerce-shipping' ),
				'HM' => __( 'Heard Island and McDonald Islands', 'wf-usps-woocommerce-shipping' ),
				'HN' => __( 'Honduras', 'wf-usps-woocommerce-shipping' ),
				'HK' => __( 'Hong Kong', 'wf-usps-woocommerce-shipping' ),
				'HU' => __( 'Hungary', 'wf-usps-woocommerce-shipping' ),
				'IS' => __( 'Iceland', 'wf-usps-woocommerce-shipping' ),
				'IN' => __( 'India', 'wf-usps-woocommerce-shipping' ),
				'ID' => __( 'Indonesia', 'wf-usps-woocommerce-shipping' ),
				'IR' => __( 'Iran', 'wf-usps-woocommerce-shipping' ),
				'IQ' => __( 'Iraq', 'wf-usps-woocommerce-shipping' ),
				'IE' => __( 'Ireland', 'wf-usps-woocommerce-shipping' ),
				'IM' => __( 'Isle of Man', 'wf-usps-woocommerce-shipping' ),
				'IL' => __( 'Israel', 'wf-usps-woocommerce-shipping' ),
				'IT' => __( 'Italy', 'wf-usps-woocommerce-shipping' ),
				'CI' => __( 'Ivory Coast', 'wf-usps-woocommerce-shipping' ),
				'JM' => __( 'Jamaica', 'wf-usps-woocommerce-shipping' ),
				'JP' => __( 'Japan', 'wf-usps-woocommerce-shipping' ),
				'JE' => __( 'Jersey', 'wf-usps-woocommerce-shipping' ),
				'JO' => __( 'Jordan', 'wf-usps-woocommerce-shipping' ),
				'KZ' => __( 'Kazakhstan', 'wf-usps-woocommerce-shipping' ),
				'KE' => __( 'Kenya', 'wf-usps-woocommerce-shipping' ),
				'KI' => __( 'Kiribati', 'wf-usps-woocommerce-shipping' ),
				'KW' => __( 'Kuwait', 'wf-usps-woocommerce-shipping' ),
				'KG' => __( 'Kyrgyzstan', 'wf-usps-woocommerce-shipping' ),
				'LA' => __( 'Laos', 'wf-usps-woocommerce-shipping' ),
				'LV' => __( 'Latvia', 'wf-usps-woocommerce-shipping' ),
				'LB' => __( 'Lebanon', 'wf-usps-woocommerce-shipping' ),
				'LS' => __( 'Lesotho', 'wf-usps-woocommerce-shipping' ),
				'LR' => __( 'Liberia', 'wf-usps-woocommerce-shipping' ),
				'LY' => __( 'Libya', 'wf-usps-woocommerce-shipping' ),
				'LI' => __( 'Liechtenstein', 'wf-usps-woocommerce-shipping' ),
				'LT' => __( 'Lithuania', 'wf-usps-woocommerce-shipping' ),
				'LU' => __( 'Luxembourg', 'wf-usps-woocommerce-shipping' ),
				'MO' => __( 'Macao S.A.R., China', 'wf-usps-woocommerce-shipping' ),
				'MK' => __( 'Macedonia', 'wf-usps-woocommerce-shipping' ),
				'MG' => __( 'Madagascar', 'wf-usps-woocommerce-shipping' ),
				'MW' => __( 'Malawi', 'wf-usps-woocommerce-shipping' ),
				'MY' => __( 'Malaysia', 'wf-usps-woocommerce-shipping' ),
				'MV' => __( 'Maldives', 'wf-usps-woocommerce-shipping' ),
				'ML' => __( 'Mali', 'wf-usps-woocommerce-shipping' ),
				'MT' => __( 'Malta', 'wf-usps-woocommerce-shipping' ),
				'MH' => __( 'Marshall Islands', 'wf-usps-woocommerce-shipping' ),
				'MQ' => __( 'Martinique', 'wf-usps-woocommerce-shipping' ),
				'MR' => __( 'Mauritania', 'wf-usps-woocommerce-shipping' ),
				'MU' => __( 'Mauritius', 'wf-usps-woocommerce-shipping' ),
				'YT' => __( 'Mayotte', 'wf-usps-woocommerce-shipping' ),
				'MX' => __( 'Mexico', 'wf-usps-woocommerce-shipping' ),
				'FM' => __( 'Micronesia', 'wf-usps-woocommerce-shipping' ),
				'MD' => __( 'Moldova', 'wf-usps-woocommerce-shipping' ),
				'MC' => __( 'Monaco', 'wf-usps-woocommerce-shipping' ),
				'MN' => __( 'Mongolia', 'wf-usps-woocommerce-shipping' ),
				'ME' => __( 'Montenegro', 'wf-usps-woocommerce-shipping' ),
				'MS' => __( 'Montserrat', 'wf-usps-woocommerce-shipping' ),
				'MA' => __( 'Morocco', 'wf-usps-woocommerce-shipping' ),
				'MZ' => __( 'Mozambique', 'wf-usps-woocommerce-shipping' ),
				'MM' => __( 'Myanmar', 'wf-usps-woocommerce-shipping' ),
				'NA' => __( 'Namibia', 'wf-usps-woocommerce-shipping' ),
				'NR' => __( 'Nauru', 'wf-usps-woocommerce-shipping' ),
				'NP' => __( 'Nepal', 'wf-usps-woocommerce-shipping' ),
				'NL' => __( 'Netherlands', 'wf-usps-woocommerce-shipping' ),
				'AN' => __( 'Netherlands Antilles', 'wf-usps-woocommerce-shipping' ),
				'NC' => __( 'New Caledonia', 'wf-usps-woocommerce-shipping' ),
				'NZ' => __( 'New Zealand', 'wf-usps-woocommerce-shipping' ),
				'NI' => __( 'Nicaragua', 'wf-usps-woocommerce-shipping' ),
				'NE' => __( 'Niger', 'wf-usps-woocommerce-shipping' ),
				'NG' => __( 'Nigeria', 'wf-usps-woocommerce-shipping' ),
				'NU' => __( 'Niue', 'wf-usps-woocommerce-shipping' ),
				'NF' => __( 'Norfolk Island', 'wf-usps-woocommerce-shipping' ),
				'KP' => __( 'North Korea', 'wf-usps-woocommerce-shipping' ),
				'NO' => __( 'Norway', 'wf-usps-woocommerce-shipping' ),
				'OM' => __( 'Oman', 'wf-usps-woocommerce-shipping' ),
				'PK' => __( 'Pakistan', 'wf-usps-woocommerce-shipping' ),
				'PS' => __( 'Palestinian Territory', 'wf-usps-woocommerce-shipping' ),
				'PA' => __( 'Panama', 'wf-usps-woocommerce-shipping' ),
				'PG' => __( 'Papua New Guinea', 'wf-usps-woocommerce-shipping' ),
				'PY' => __( 'Paraguay', 'wf-usps-woocommerce-shipping' ),
				'PE' => __( 'Peru', 'wf-usps-woocommerce-shipping' ),
				'PH' => __( 'Philippines', 'wf-usps-woocommerce-shipping' ),
				'PN' => __( 'Pitcairn', 'wf-usps-woocommerce-shipping' ),
				'PL' => __( 'Poland', 'wf-usps-woocommerce-shipping' ),
				'PT' => __( 'Portugal', 'wf-usps-woocommerce-shipping' ),
				'QA' => __( 'Qatar', 'wf-usps-woocommerce-shipping' ),
				'RE' => __( 'Reunion', 'wf-usps-woocommerce-shipping' ),
				'RO' => __( 'Romania', 'wf-usps-woocommerce-shipping' ),
				'RU' => __( 'Russia', 'wf-usps-woocommerce-shipping' ),
				'RW' => __( 'Rwanda', 'wf-usps-woocommerce-shipping' ),
				'BL' => __( 'Saint Barth&eacute;lemy', 'wf-usps-woocommerce-shipping' ),
				'SH' => __( 'Saint Helena', 'wf-usps-woocommerce-shipping' ),
				'KN' => __( 'Saint Kitts and Nevis', 'wf-usps-woocommerce-shipping' ),
				'LC' => __( 'Saint Lucia', 'wf-usps-woocommerce-shipping' ),
				'MF' => __( 'Saint Martin (French part)', 'wf-usps-woocommerce-shipping' ),
				'SX' => __( 'Saint Martin (Dutch part)', 'wf-usps-woocommerce-shipping' ),
				'PM' => __( 'Saint Pierre and Miquelon', 'wf-usps-woocommerce-shipping' ),
				'VC' => __( 'Saint Vincent and the Grenadines', 'wf-usps-woocommerce-shipping' ),
				'SM' => __( 'San Marino', 'wf-usps-woocommerce-shipping' ),
				'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wf-usps-woocommerce-shipping' ),
				'SA' => __( 'Saudi Arabia', 'wf-usps-woocommerce-shipping' ),
				'SN' => __( 'Senegal', 'wf-usps-woocommerce-shipping' ),
				'RS' => __( 'Serbia', 'wf-usps-woocommerce-shipping' ),
				'SC' => __( 'Seychelles', 'wf-usps-woocommerce-shipping' ),
				'SL' => __( 'Sierra Leone', 'wf-usps-woocommerce-shipping' ),
				'SG' => __( 'Singapore', 'wf-usps-woocommerce-shipping' ),
				'SK' => __( 'Slovakia', 'wf-usps-woocommerce-shipping' ),
				'SI' => __( 'Slovenia', 'wf-usps-woocommerce-shipping' ),
				'SB' => __( 'Solomon Islands', 'wf-usps-woocommerce-shipping' ),
				'SO' => __( 'Somalia', 'wf-usps-woocommerce-shipping' ),
				'ZA' => __( 'South Africa', 'wf-usps-woocommerce-shipping' ),
				'GS' => __( 'South Georgia/Sandwich Islands', 'wf-usps-woocommerce-shipping' ),
				'KR' => __( 'South Korea', 'wf-usps-woocommerce-shipping' ),
				'SS' => __( 'South Sudan', 'wf-usps-woocommerce-shipping' ),
				'ES' => __( 'Spain', 'wf-usps-woocommerce-shipping' ),
				'LK' => __( 'Sri Lanka', 'wf-usps-woocommerce-shipping' ),
				'SD' => __( 'Sudan', 'wf-usps-woocommerce-shipping' ),
				'SR' => __( 'Suriname', 'wf-usps-woocommerce-shipping' ),
				'SJ' => __( 'Svalbard and Jan Mayen', 'wf-usps-woocommerce-shipping' ),
				'SZ' => __( 'Swaziland', 'wf-usps-woocommerce-shipping' ),
				'SE' => __( 'Sweden', 'wf-usps-woocommerce-shipping' ),
				'CH' => __( 'Switzerland', 'wf-usps-woocommerce-shipping' ),
				'SY' => __( 'Syria', 'wf-usps-woocommerce-shipping' ),
				'TW' => __( 'Taiwan', 'wf-usps-woocommerce-shipping' ),
				'TJ' => __( 'Tajikistan', 'wf-usps-woocommerce-shipping' ),
				'TZ' => __( 'Tanzania', 'wf-usps-woocommerce-shipping' ),
				'TH' => __( 'Thailand', 'wf-usps-woocommerce-shipping' ),
				'TL' => __( 'Timor-Leste', 'wf-usps-woocommerce-shipping' ),
				'TG' => __( 'Togo', 'wf-usps-woocommerce-shipping' ),
				'TK' => __( 'Tokelau', 'wf-usps-woocommerce-shipping' ),
				'TO' => __( 'Tonga', 'wf-usps-woocommerce-shipping' ),
				'TT' => __( 'Trinidad and Tobago', 'wf-usps-woocommerce-shipping' ),
				'TN' => __( 'Tunisia', 'wf-usps-woocommerce-shipping' ),
				'TR' => __( 'Turkey', 'wf-usps-woocommerce-shipping' ),
				'TM' => __( 'Turkmenistan', 'wf-usps-woocommerce-shipping' ),
				'TC' => __( 'Turks and Caicos Islands', 'wf-usps-woocommerce-shipping' ),
				'TV' => __( 'Tuvalu', 'wf-usps-woocommerce-shipping' ),
				'UG' => __( 'Uganda', 'wf-usps-woocommerce-shipping' ),
				'UA' => __( 'Ukraine', 'wf-usps-woocommerce-shipping' ),
				'AE' => __( 'United Arab Emirates', 'wf-usps-woocommerce-shipping' ),
				'GB' => __( 'United Kingdom', 'wf-usps-woocommerce-shipping' ),
				'US' => __( 'United States', 'wf-usps-woocommerce-shipping' ),
				'UY' => __( 'Uruguay', 'wf-usps-woocommerce-shipping' ),
				'UZ' => __( 'Uzbekistan', 'wf-usps-woocommerce-shipping' ),
				'VU' => __( 'Vanuatu', 'wf-usps-woocommerce-shipping' ),
				'VA' => __( 'Vatican', 'wf-usps-woocommerce-shipping' ),
				'VE' => __( 'Venezuela', 'wf-usps-woocommerce-shipping' ),
				'VN' => __( 'Vietnam', 'wf-usps-woocommerce-shipping' ),
				'WF' => __( 'Wallis and Futuna', 'wf-usps-woocommerce-shipping' ),
				'EH' => __( 'Western Sahara', 'wf-usps-woocommerce-shipping' ),
				'WS' => __( 'Western Samoa', 'wf-usps-woocommerce-shipping' ),
				'YE' => __( 'Yemen', 'wf-usps-woocommerce-shipping' ),
				'ZM' => __( 'Zambia', 'wf-usps-woocommerce-shipping' ),
				'ZW' => __( 'Zimbabwe', 'woocommerce' ),
			)
		);

		if ( isset( $countries[ $code ] ) ) {
			return strtoupper( $countries[ $code ] );
		} else {
			return false;
		}
	}

	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug && ! is_admin() ) { //WF: is_admin check added.
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
				wc_add_notice( $message, $type );
			} else {
				global $woocommerce;
				$woocommerce->add_message( $message );
			}
		}
	}

	public function wf_get_variation_data_from_variation_id( $item_id ) {
		$_product         = new WC_Product_Variation( $item_id );
		$variation_data   = $_product->get_variation_attributes();
		$variation_detail = woocommerce_get_formatted_variation( $variation_data, true );  // this will give all variation detail in one line
		// $variation_detail = woocommerce_get_formatted_variation( $variation_data, false);  // this will give all variation detail one by one
		return $variation_detail; // $variation_detail will return string containing variation detail which can be used to print on website
		// return $variation_data; // $variation_data will return only the data which can be used to store variation data
	}

	private function wf_load_product( $product ) {
		if ( ! $product ) {
			return false;
		}
		return ( WC()->version < '2.7.0' ) ? $product : new ELEX_Product( $product );
	}

}
