<?php
/**
 * Payment Gateways per Products for WooCommerce - Products Section Settings
 *
 * @version 1.7.9
 * @since   1.1.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_PGPP_Settings_Countries' ) ) :

class Alg_WC_PGPP_Settings_Countries extends Alg_WC_PGPP_Settings_Section {
	
	/**
	 * number_of_restrictions.
	 *
	 *  @version 1.7.9
	 * @since 1.7.9
	 */
	public $number_of_restrictions = 1;
	
	/**
	 * Constructor.
	 *
	 * @version 1.7.9
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   					= 'countries';
		$this->desc 					= __( 'Countries', 'payment-gateways-per-product-categories-for-woocommerce' );
		
		$this->number_of_restrictions   = (int) get_option( 'alg_wc_pgpp_countries_restriction_number', 1 );
		
		parent::__construct();
	}
	
	/**
	 * get_restriction_settings.
	 *
	 * @version 1.7.9
	 * @since   1.7.9
	 * @todo    get restriction setting based on saved numbers.
	 */
	function get_restriction_settings() {
		
		$numbers = (int) get_option( 'alg_wc_pgpp_countries_restriction_number', 1 );
		
		$return = array();
		
		if ( $numbers > 0 ) {
			for( $i = 1; $i <= $numbers; $i ++ ) {
				
				if($i == 1){
					$country_id = 'alg_wc_pgpp_countries_remove_countries';
					$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway';
				}else{
					$country_id = 'alg_wc_pgpp_countries_remove_countries_' . $i;
					$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway_' . $i;
				}
				
				$return[] = array(
					'title'    => __( 'Restrict gateway for selected countries - ' . $i, 'payment-gateways-per-product-categories-for-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_pgpp_countries_condition_' . $i,
				);
				
				$return[] = array(
					'title'    => __( 'Choose Countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip' => __( 'If countries chosen following payment gateways will be excluded', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'       => $country_id, 
					'default'  => '',
					'type'     => 'multiselect',
					'class'    => 'wc-enhanced-select',
					'options'  => $this->allCountries(),
					'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
				);
				
				$return[] = array(
					'title'    => __( 'Choose gateways to appear only on selected countries above', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip' => __( 'Gateways will appeared with above chosen countries.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'       => $gateway_id, 
					'default'  => '',
					'type'     => 'multiselect',
					'class'    => 'wc-enhanced-select',
					'options'  => $this->allGateways(),
					'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
				);
				
				
				$return[] = array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_pgpp_countries_condition_' . $i,
				);
			
			}
		}
		return $return;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.7.9
	 * @since   1.1.0
	 * @todo    [dev] "Add variations": maybe add option to use main product and variations *simultaneously*
	 */
	function get_settings() {
		
		$restrictions = $this->get_restriction_settings();
		$settings = array();
		
			$settings[] = array(
				'title'    => __( 'Remove from countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'     => __( 'By default, gateways will appear in all countries. To restrict a specific gateway to a specific country, enter them here, all others will remain untouched', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_pgpp_countries_remove',
			);
			
			$settings[] = array(
				'title'    => __( 'Enable/Disable', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'payment-gateways-per-product-categories-for-woocommerce' ) . '</strong>',
				'desc_tip' => apply_filters( 'alg_wc_pgpp', sprintf(
					'To enable this section you need <a href="%s" target="_blank">Payment Gateways per Products for WooCommerce Pro</a> plugin.',
					'https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/' ), 'settings' ),
				'id'       => 'alg_wc_pgpp_countries_remove_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
			);
			
			$settings[] = array(
				'title'    => __( 'Number of payment by country restrictions', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip' => __( 'It will behave the same as the default Country-Gateway combination mentioned above, with the added flexibility of being able to include multiple conditions if needed.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'       => 'alg_wc_pgpp_countries_restriction_number',
				'default'  => '1',
				'type'     => 'text',
				'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
			);
			
			$settings[] = array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_pgpp_products_options',
			);
			
			return array_merge($settings, $restrictions);
	}
	
	public function allCountries(){
		$wc_countries = new WC_Countries();
		$countries = $wc_countries->get_countries();
		return $countries;
	}
	
	public function allGateways(){
		$available_gateways = WC()->payment_gateways->payment_gateways();
		$gateways_settings  = array();
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			if(isset($gateway->method_title) && !empty($gateway->method_title)){
				$gateways_settings[$gateway_id] = $gateway->method_title . ' - ' . $gateway->title;
			}else{
				$gateways_settings[$gateway_id] = $gateway->title;
			}
		}
		return $gateways_settings;
	}

}

endif;

return new Alg_WC_PGPP_Settings_Countries();
