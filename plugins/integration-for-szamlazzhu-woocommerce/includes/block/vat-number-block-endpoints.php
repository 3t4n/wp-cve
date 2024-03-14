<?php
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema;
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

//Extend Store API
class WC_Szamlazz_VAT_Number_Block_Extend_Store_Endpoint {
	/**
	 * Stores Rest Extending instance.
	 *
	 * @var ExtendRestApi
	 */
	private static $extend;

	/**
	 * Plugin Identifier, unique to each plugin.
	 *
	 * @var string
	 */
	const IDENTIFIER = 'wc-szamlazz-vat-number';

	/**
	 * Bootstraps the class and hooks required data.
	 *
	 */
	public static function init() {
		self::extend_store();
	}

	/**
	 * Registers the actual data into each endpoint.
	 */
	public static function extend_store() {

		woocommerce_store_api_register_endpoint_data([
			'endpoint'        => CartSchema::IDENTIFIER,
			'namespace' 	  => 'wc-szamlazz-vat-number',
			'schema_callback' => [ 'WC_Szamlazz_VAT_Number_Block_Extend_Store_Endpoint', 'extend_cart_schema' ],
			'schema_type'     => ARRAY_A,
			'data_callback'   => function () {
	
				//Check for saved value in case of logged in users
				$saved_vat_number = false;
				if(is_user_logged_in()) {
					$customer_id = get_current_user_id();
					$saved_vat_number = get_user_meta( $customer_id, 'wc_szamlazz_adoszam', true );
				}

				//Setup vat number data
				$vat_number_data = WC()->session->get( 'vat-number-data' );
				if(!$vat_number_data) {
					$vat_number_data = array(
						'vat_number' => '',
						'customer_type' => 'individual'
					);

					if($saved_vat_number) {
						$vat_number_data['vat_number'] = $saved_vat_number;
						$vat_number_data['customer_type'] = 'company';
					}
				}

				return $vat_number_data;
			},
		]);
	
		woocommerce_store_api_register_endpoint_data([
			'endpoint'        => CheckoutSchema::IDENTIFIER,
			'namespace'       => 'wc-szamlazz-vat-number',
			'schema_callback' => [ 'WC_Szamlazz_VAT_Number_Block_Extend_Store_Endpoint', 'extend_checkout_schema' ],
			'schema_type'     => ARRAY_A,
		]);
	
		woocommerce_store_api_register_update_callback([
			'namespace' => 'wc-szamlazz-vat-number',
			'callback'  => function( $data ) {
				if ( isset( $data['vat_number'] ) ) {

					//Reset vat exemption
					WC()->customer->set_is_vat_exempt(false);

					//If starts with two letters, its an EU VAT number
					if(preg_match('/^[A-Z]{2}/', $data['vat_number'])) {

						//In that case, get EU vat number data
						$vat_number_data = WC_Szamlazz_Vat_Number_Field::get_eu_vat_number_data($data['vat_number']);

						//Set VAT exempt if the vat number is not from Hungary
						if(isset($vat_number_data['country_code']) && $vat_number_data['country_code'] != 'HU') {
							WC()->customer->set_is_vat_exempt(true);
						}

					} else {

						//Otherwise, get Hungarian VAT number data
						$vat_number_data = WC_Szamlazz_Vat_Number_Field::get_vat_number_data($data['vat_number']);
						
					}

					//Save in session
					$vat_number_data['vat_number'] = $data['vat_number'];
					$vat_number_data['customer_type'] = 'company';
					WC()->session->set( 'vat-number-data', $vat_number_data );
					
				} else {

					$vat_number_data = WC()->session->get( 'vat-number-data' );
					if(!$vat_number_data) {
						$vat_number_data = array(
							'vat_number' => '',
							'customer_type' => 'individual'
						);
					}

					WC()->session->set( 'vat-number-data', $vat_number_data );
					WC()->customer->set_is_vat_exempt(false);
				}
			}
		]);
		
	}

	//Register schema into the Checkout endpoint.
	public static function extend_cart_schema() {
        return [
            'customer_type'   => [
                'description' => 'Type of the customer, individual or company',
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
                'arg_options' => [
                    'validate_callback' => function( $value ) {
						return is_string($value) && in_array($value, ['individual', 'company']);
                    },
                ]
            ],
            'billing_vat_number'   => [
            	'description' => 'The entered VAT/TAX number',
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
				'optional'    => true,
                'arg_options' => [
                    'validate_callback' => function( $value ) {
						return is_string( $value );
                    },
                ]
            ],
            'billing_vat_number_info'   => [
            	'description' => 'The entered VAT/TAX number info',
                'type'        => 'object',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
				'optional'    => true,
            ]
        ];
    }

	//Register schema into the Checkout endpoint.
	public static function extend_checkout_schema() {
        return [
            'customer_type'   => [
                'description' => 'Type of the customer, individual or company',
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
                'arg_options' => [
                    'validate_callback' => function( $value ) {
						return is_string($value) && in_array($value, ['individual', 'company']);
                    },
                ]
            ],
            'billing_vat_number'   => [
            	'description' => 'The entered VAT/TAX number',
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
				'optional'    => true,
                'arg_options' => [
                    'validate_callback' => function( $value ) {
						return is_string( $value );
                    },
                ]
            ],
            'billing_vat_number_info'   => [
            	'description' => 'The entered VAT/TAX number info',
                'type'        => 'object',
                'context'     => [ 'view', 'edit' ],
                'readonly'    => true,
				'optional'    => true
            ]
        ];
    }

}