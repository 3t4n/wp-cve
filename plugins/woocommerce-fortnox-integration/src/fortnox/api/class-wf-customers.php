<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Utils;

class WF_Customers {

    /**
     * Get customer number by e-mail
     *
     * @param string $email
     * @return object|bool
     * @throws \Exception
     */
	static public function get( $order ) {
		// If no email is sent, fake one that can't possibly be used already

        $email = $order->get_billing_email();
		if ( empty( $email ) || ! isset( $email ) )
			$email = 'none@none.none';

		try {
			$response = WF_Request::get( apply_filters( 'wf_customer_url', "/customers?email=" . urlencode( $email ), $order ) );

			if( ! empty( $response->Customers ) ){
                return $response->Customers[ 0 ];
            }

		}
		catch( \Exception $error ) {
			throw new \Exception( $error->getMessage() );
		}
		return false;
	}

    static public function get_by_number( $number ) {
        // If no email is sent, fake one that can't possibly be used already

        try {
            $response = WF_Request::get( "/customers/" . $number );

            if( ! empty( $response->Customer ) ){
                return $response->Customer;
            }

        }
        catch( \Exception $error ) {
            throw new \Exception( $error->getMessage() );
        }
        return false;
    }


	/**
	 * Validate company number
	 *
	 * @wrike https://www.wrike.com/open.htm?id=807478538
	 *
	 * @param string $number
	 *
	 * @return string
	 */
    static function validate_company_number( $number = '' ){

    	if( empty( $number ) ) return $number;

    	$number = preg_replace( '/[ -]/', '', $number );

		$a_num  = substr( $number, 0, 6 );
		if( 6 !== strlen( $a_num ) ) return '';

		$b_num  = substr( $number, 6, 4 );
		if( 4 !== strlen( $b_num ) ) return '';

		if( preg_match( '/[^0-9]+/', $a_num ) || preg_match( '/[^0-9]+/', $b_num ) ) return '';

		return $a_num . '-' . $b_num;
    }

    /**
     * Format Customer.
     *
     * @param \WC_Order $order
     * @return mixed
     */
    public static function format_customer_payload( $order ){

        $address = $order->get_address();
        $shipping_address2 = $address['country'] == 'US' ? $order->get_shipping_state() : $order->get_shipping_address_2();

        $fortnox_customer = apply_filters( 'wf_customer_before_post', [
            'Email'                 => $address['email'],
            'EmailInvoice'          => $address['email'],
            'Name'                  => ! empty( $address['company'] ) ? $address['company'] : $address['first_name'] . ' ' . $address['last_name'],
            'Type'                  => ( ! empty( $address['company'] ) ? "COMPANY" : "PRIVATE" ),
            'OrganisationNumber'    => self::validate_company_number( WF_Utils::get_order_meta_compat( $order->get_id(), '_billing_company_number' ) ),
            'Address1'              => $order->get_billing_address_1() ? $order->get_billing_address_1() : $order->get_shipping_address_1(),
            'Address2'              => $order->get_billing_address_2() ? $order->get_billing_address_2() : $order->get_shipping_address_2(),
            'ZipCode'               => $order->get_billing_postcode() ? $order->get_billing_postcode() : $order->get_shipping_postcode(),
            'City'                  => $order->get_billing_city() ? $order->get_billing_city() : $order->get_shipping_city(),
            'CountryCode'           => $order->get_billing_country() ? $order->get_billing_country() : $order->get_shipping_country(),
            'DeliveryAddress1'      => $order->get_shipping_address_1(),
            'DeliveryAddress2'      => $shipping_address2,
            'DeliveryCity'          => $order->get_shipping_city(),
            'Currency'              => $order->get_currency(),
            'DeliveryCountryCode'   => $order->get_shipping_country() ? $order->get_shipping_country() : $order->get_billing_country() ,
            'DeliveryName'          => $order->get_shipping_first_name()." ".$order->get_shipping_last_name(),
            'DeliveryZipCode'       => $order->get_shipping_postcode(),
            'Phone1'                => $address['phone'],
            'ShowPriceVATIncluded'  => false,
            'Active'                => true,
        ], $order->get_user(), $order );//TODO

        if ( has_filter( 'wetail_fortnox_sync_modify_customer'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_sync_modify_customer filter', '', 'wf_customer_before_post'  );
            $fortnox_customer = apply_filters( 'wetail_fortnox_sync_modify_customer', [
                'Email'                 => $address['email'],
                'EmailInvoice'          => $address['email'],
                'Name'                  => ! empty( $address['company'] ) ? $address['company'] : $address['first_name'] . ' ' . $address['last_name'],
                'Type'                  => ( ! empty( $address['company'] ) ? "COMPANY" : "PRIVATE" ),
                'OrganisationNumber'    => self::validate_company_number( WF_Utils::get_order_meta_compat( $order->get_id(), '_billing_company_number' ) ),
                'Address1'              => $order->get_billing_address_1() ? $order->get_billing_address_1() : $order->get_shipping_address_1(),
                'Address2'              => $order->get_billing_address_2() ? $order->get_billing_address_2() : $order->get_shipping_address_2(),
                'ZipCode'               => $order->get_billing_postcode() ? $order->get_billing_postcode() : $order->get_shipping_postcode(),
                'City'                  => $order->get_billing_city() ? $order->get_billing_city() : $order->get_shipping_city(),
                'CountryCode'           => $order->get_billing_country() ? $order->get_billing_country() : $order->get_shipping_country(),
                'DeliveryAddress1'      => $order->get_shipping_address_1(),
                'DeliveryAddress2'      => $shipping_address2,
                'DeliveryCity'          => $order->get_shipping_city(),
                'Currency'              => $order->get_currency(),
                'DeliveryCountryCode'   => $order->get_shipping_country() ? $order->get_shipping_country() : $order->get_billing_country() ,
                'DeliveryName'          => $order->get_shipping_first_name()." ".$order->get_shipping_last_name(),
                'DeliveryZipCode'       => $order->get_shipping_postcode(),
                'Phone1'                => $address['phone'],
                'ShowPriceVATIncluded'  => false,
            ], $order->get_user() );
        }

        return $fortnox_customer + self::get_vat_info( $order );
    }

    /**
     * @param \WC_Order $order
     * @return array
     */
    public static function get_vat_info( $order ){
        $address = $order->get_address();
        $vat_info = [];
        $vat_number = WF_Utils::get_vat_number( $order->get_id() );

        // Set customer VAT type based on country
        if ( ! empty( $vat_number ) ) {
            $vat_info['VATNumber'] = $vat_number;
        }

        // If customer is in EU tax zone and has no VAT number
        if ( empty( $vat_number ) && $address['country'] !== "SE" && in_array( $address['country'], WF_Orders::EU_COUNTRIES ) ) {
            $vat_info['VATType'] = "EUVAT";
        }
        else {
            // If customer is outside of the EU tax zone
            $vat_info['VATType'] = "EXPORT";
        }

        // If customer is in EU tax zone and has a VAT number
        if ( in_array( $address['country'], WF_Orders::EU_COUNTRIES ) && ! empty( $vat_number ) ) {
            $vat_info['VATType'] = "EUREVERSEDVAT";
        }

        // If customer is Swedish
        if ( "SE" == $address['country'] ) {
            $vat_info['VATType'] = "SEVAT";
        }

        return apply_filters( 'wf_customer_vat_info', $vat_info, $order );


    }

    /**
     * @param $email
     * @return bool
     * @throws Exception
     */
    public static function get_customer_number( $order ){
        try {
            $existing_customer = self::get( $order );

            // Create new customer
            if( empty( $existing_customer ) ) {
                return false;
            }
            elseif( ! empty( $existing_customer ) ) {
                return $existing_customer->CustomerNumber;
            }
        }
        catch( \Exception $error ) {
            throw new \Exception( $error->getMessage() );
        }
    }

    /**
     * Sync customer 
     * @param array $customer
     * @return integer
     * @throws \Exception
     */
	 public static function sync( $customer, $order ) {
		try {
			$existing_customer = self::get( $order );

			// Create new customer
			if( empty( $existing_customer ) ) {
				$response = WF_Request::post( "/customers", [ 'Customer' => $customer ] );
                return $response->Customer->CustomerNumber;
			}
			elseif( ! empty( $existing_customer ) ) {
                $customer_number = $existing_customer->CustomerNumber;
                if ( get_option( 'wf_do_not_sync_customer_on_update' ) ){
                    return $customer_number;
                }
                unset($customer['CustomerNumber']);
				WF_Request::put("/customers/" . $customer_number, [
					'Customer' => $customer
				] );
                return $customer_number;
			}
		}
		catch( \Exception $error ) {
			throw new \Exception( $error->getMessage() );
		}
	}
}
