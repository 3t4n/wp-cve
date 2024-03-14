<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\api\WF_Orders;
use src\fortnox\api\WF_Predefined_Accounts;
use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;

class WF_Accounting_Settings_View{


    /**
     * Adds all required setting fields for Accounting View
     */
    public static function add_settings()
    {
        $page = "fortnox";

        // Automation settings tab
        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "accounting",
            'title' => __( "Accounting", WF_Plugin::TEXTDOMAIN )
        ] );

	    // Accounting section
	    WF_Admin_Settings::add_section( [
		    'page' => $page,
		    'tab' => "accounting",
		    'name' => "accounting",
		    'title' => __( "Accounting", WF_Plugin::TEXTDOMAIN ),
		    'description' => __( "Accounts for different VAT percentages, if below is not filled, defaults are applied.", WF_Plugin::TEXTDOMAIN )
	    ] );

	    // 25 Account
	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "accounting",
		    'section' => "accounting",
		    'name' => "fortnox_products_25_account",
		    'type' => 'number',
		    'min' => '1',
		    'max' => '9999',
		    'title' => __( "Account 25% VAT", WF_Plugin::TEXTDOMAIN ),
		    'tooltip' => __( "The account for products with 25% VAT.", WF_Plugin::TEXTDOMAIN ),
		    'placeholder' =>  WF_Predefined_Accounts::get_predefined_account_by_name( 'OUTVAT_MP1' )
	    ] );

	    // 12 Account
	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "accounting",
		    'section' => "accounting",
		    'name' => "fortnox_products_12_account",
		    'type' => 'number',
		    'min' => '1',
		    'max' => '9999',
		    'title' => __( "Account 12% VAT", WF_Plugin::TEXTDOMAIN ),
		    'tooltip' => __( "The account for products with 12% VAT.", WF_Plugin::TEXTDOMAIN ),
		    'placeholder' =>  WF_Predefined_Accounts::get_predefined_account_by_name( 'OUTVAT_MP2' )
	    ] );

	    // 6 Account
	    WF_Admin_Settings::add_field( [
		    'page' => $page,
		    'tab' => "accounting",
		    'section' => "accounting",
		    'name' => "fortnox_products_6_account",
		    'type' => 'number',
		    'min' => '1',
		    'max' => '9999',
		    'title' => __( "Account 6% VAT", WF_Plugin::TEXTDOMAIN ),
		    'tooltip' => __( "The account for products with 6% VAT.", WF_Plugin::TEXTDOMAIN ),
		    'placeholder' =>  WF_Predefined_Accounts::get_predefined_account_by_name( 'OUTVAT_MP3' )
	    ] );

	    WF_Admin_Settings::add_section( [
		    'page' => $page,
		    'tab' => "accounting",
		    'name' => "invoice_payment_account",
		    'title' => __( "Invoice Payment accounts", WF_Plugin::TEXTDOMAIN ),
		    'description' => sprintf(__( "When an invoice is booked in Fortnox it will per default be booked by debiting the Fortnox default receivables account %d. When a payment is booked, the Fortnox default payment account %d will be debited. In this section you can change the default payment account for each payment type in order to get a ledger for each.", WF_Plugin::TEXTDOMAIN), WF_Predefined_Accounts::get_predefined_account_by_name( 'CUSTCLAIM' ), WF_Predefined_Accounts::get_predefined_account_by_name( 'BG' ) )
	    ] );

	    if( WC()->payment_gateways->get_available_payment_gateways() ) {
		    foreach( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {

			    if( $gateway->enabled == 'yes' ) {
				    WF_Admin_Settings::add_field( [
					    'page' => $page,
					    'tab' => "accounting",
					    'section' => "invoice_payment_account",
					    'name' => "fortnox_invoice_payment_account_" . $gateway->id,
					    'type' => 'number',
					    'min' => '1',
					    'max' => '9999',
					    'title' => __( "Invoice payment account for:" , WF_Plugin::TEXTDOMAIN ) . '<br>' . $gateway->get_title(),
					    'placeholder' => WF_Predefined_Accounts::get_predefined_account_by_name( 'BG' )
				    ] );

			    }
		    }
	    }

	    WF_Admin_Settings::add_section( [
		    'page' => $page,
		    'tab' => "accounting",
		    'name' => "invoice_payment_terms",
		    'title' => __( "Payment terms", WF_Plugin::TEXTDOMAIN ),
		    'description' => __( "Each payment method can have it's own payment terms, usually given in days. Mostly 0 is the most adequate choice as long as it is not a payment method where the customer does not make a direct purchase.", WF_Plugin::TEXTDOMAIN )

	    ] );

	    if( WC()->payment_gateways->get_available_payment_gateways() ) {
		    foreach( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {

			    if( $gateway->enabled == 'yes' ) {
				    WF_Admin_Settings::add_field( [
					    'page' => $page,
					    'tab' => "accounting",
					    'section' => "invoice_payment_terms",
					    'name' => "fortnox_invoice_payment_terms_" . $gateway->id,
					    'title' => __( "Payment terms for:", WF_Plugin::TEXTDOMAIN ) . '<br>' . $gateway->get_title(),
					    'type' => "dropdown",
					    'short' => true,
					    'options' => self::get_payment_terms()
				    ] );

			    }
		    }
	    }

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "accounting",
            'name' => "eu_sales_accounts",
            'title' => __( "EU Sales Accounts", WF_Plugin::TEXTDOMAIN ),
            'description' => sprintf(__( "Here you can define sales accounts per country. If an account is defined for a country, the orders and its invoice will be booked on that account. If nothing is specified Fortnox accounting settings will be used for booking the invoice.", WF_Plugin::TEXTDOMAIN), WF_Predefined_Accounts::get_predefined_account_by_name( 'CUSTCLAIM' ), WF_Predefined_Accounts::get_predefined_account_by_name( 'BG' ) )
        ] );

        foreach( WF_Orders::EU_COUNTRIES as $country_code ) {

            WF_Admin_Settings::add_field( [
                'page' => $page,
                'tab' => "accounting",
                'section' => "eu_sales_accounts",
                'name' => "wf_eu_sales_account_" . strtolower($country_code),
                'title' => __( "Sales Account: ", WF_Plugin::TEXTDOMAIN ) .  $country_code,
                'type' => 'number',
                'min' => '3000',
                'max' => '3999',
            ] );
        }

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "accounting",
            'section' => "eu_sales_accounts",
            'name' => "wf_eu_sales_account_gb",
            'title' => __( "Sales Account: ", WF_Plugin::TEXTDOMAIN ) .  "GB",
            'type' => 'number',
            'min' => '3000',
            'max' => '3999',
        ] );
    }

	/** Returns Fortnox payment terms from database
	 * @return array
	 */
	private static function get_payment_terms(){
		$fortnox_payment_terms = get_option( 'fortnox_payment_terms' );

		if ( $fortnox_payment_terms ) {
			$payment_terms = array_map( function( $fortnox_payment_term ){
				return [
					"value" => $fortnox_payment_term,
					"label" => $fortnox_payment_term
				];
			}, $fortnox_payment_terms );

			array_unshift( $payment_terms, [
				"value" => "",
				"label" => __( "Please select...", WF_Plugin::TEXTDOMAIN )
			]);
		} else {
			$payment_terms = [
                [
                    "value" => "",
                    "label" => __( "No payment terms available in Fortnox", WF_Plugin::TEXTDOMAIN )
                ]
            ];
		}

		return $payment_terms;
	}
}
