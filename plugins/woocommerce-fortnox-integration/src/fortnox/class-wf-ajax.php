<?php

namespace src\fortnox;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\admin_views\WF_Admin_Listing_Actions;
use src\fortnox\api\WF_Auth;
use src\fortnox\api\WF_Delivery_Terms;
use src\wetail\WF_Credentials;
use src\wetail\WF_NG_Fortnox_auth;

class WF_Ajax {

    const WF_ACTION_AJAX_SYNC_ORDER = "sync_order";
    const WF_ACTION_AJAX_SYNC_PRODUCT = "sync_product";
    const WF_ACTION_AJAX_BULK_SYNC_PRODUCTS = "fortnox_sync_products";
    const WF_ACTION_AJAX_BULK_SYNC_ORDERS = "fortnox_sync_orders_date_range";
    const WF_ACTION_AJAX_SEND_INVOICE = "send_invoice";
    const WF_ACTION_AJAX_FLUSH_ACCESS_TOKEN = "fortnox_flush_access_token";
    const WF_ACTION_AJAX_GET_SETTINGS = "fortnox_get_settings";

    /**
     * INIT
     */
    public static function init(){

        add_action( 'wp_ajax_fortnox_update_setting', __CLASS__ . '::update_setting' );
        add_action( 'wp_ajax_fortnox_bulk_action', __CLASS__ . '::bulk_action' );
        add_action( 'wp_ajax_check_fortnox_license_key', __CLASS__ . '::check_license_key' );#
        add_action( 'wp_ajax_check_fortnox_organization_number', __CLASS__ . '::fortnox_organization_number' );#
        add_action( 'wp_ajax_check_pull_for_result_auth_by_organisation_number', __CLASS__ . '::pull_for_result_auth_by_organisation_number' );#
	    add_action( 'wp_ajax_check_fortnox_auth_code', __CLASS__. '::check_auth_code' );
        add_action( 'wp_ajax_fetch_delivery_terms',  __CLASS__. 'check_auth_code' );
        add_action( 'wp_ajax_fortnox_action',  __CLASS__. '::process' );
        add_action( 'wp_ajax_fetch_delivery_terms',  __CLASS__. '::fetch_delivery_terms' );
        add_action( 'wp_ajax_fetch_payment_terms',  __CLASS__. '::fetch_payment_terms' );
	    if ( is_admin() ) {
		    WF_NG_Fortnox_auth::init();
	    }
    }

	public static function pull_for_result_auth_by_organisation_number() {
		if ( $response = get_option( 'fortnox_organization_number_auth_result', false ) ) {
			if ( isset( $response['error'] ) && $response['error'] ) {
				self::respond( [ 'finished' => true, 'error' => true, 'message' => $response['message'] ] );
			}
			self::respond( [ 'finished' => true, 'message' => __( "Successful", WF_Plugin::TEXTDOMAIN ) ] );
		}
		if ( ! get_transient( 'fortnox_organisation_auth_secret' ) ) {
			self::respond( [
				'finished' => true,
				'error'    => true,
				'message'  => __( "Timed out", WF_Plugin::TEXTDOMAIN ),
			] );
		}
		self::respond( [ 'message' => __( "No response yet...", WF_Plugin::TEXTDOMAIN ) ] );
	}

	public static function fortnox_organization_number() {
		if ( empty( $_REQUEST['key'] ) ) {
			self::error( __( "Organization number is empty", WF_Plugin::TEXTDOMAIN ) );
		}

		if ( ! preg_match( '/^(\d{10})|(\d{6}-\d{4})$/', $_REQUEST['key'] ) ) {
			self::error( __( "Organization number is invalid, accepted formats: XXXXXX-XXXX or XXXXXXXXXX",
				WF_Plugin::TEXTDOMAIN ) );
		}

		update_option( 'fortnox_organization_number', $_REQUEST['key'] );


		if ( $auth_response = WF_NG_Fortnox_auth::request_for_auth($_REQUEST['key'], $_SERVER['HTTP_REFERER']) ) {
			self::respond( [
				'message' => __( "Organization number registered, next step is to register app in Fortnox. See " , WF_Plugin::TEXTDOMAIN ) . '<a href="https://docs.wetail.io/woocommerce/fortnox-integration/fortnox-installationsguide/#aktivera-app">' . __( "installation guide" , WF_Plugin::TEXTDOMAIN ) . "</a>",
				'extra'   => 'pull_for_result_auth_by_organisation_number',
			] );
		} else {
			self::error( __( "Error placing request.", WF_Plugin::TEXTDOMAIN ) );
		}
    }

	/**
	 * Send AJAX response
	 *
	 * @param array $data
	 */
	public static function respond( $data = [] )
	{
		$defaults = [
			'error' => false
		];
		$data = array_merge( $defaults, $data );
		die( json_encode( $data ) );
	}

	/**
	 * Send AJAX error
	 *
	 * @param string $message
	 */
	public static function error( $message ){
		self::respond(
		    [
		        'message' => $message,
                'error' => true
            ]
        );
	}

	/**
	 * Update settings through AJAX
	 */
	public static function update_setting()
	{
		if( ! empty( $_REQUEST['settings'] ) ){
            foreach( $_REQUEST['settings'] as $option => $value ){
                if( 0 === strpos( $option, 'fortnox_'  ) ){
                    update_option( $option, $value );
                }
            }
        }

		self::respond();
	}

    /**
     * Process AJAX request
     * @throws \Exception
     */
    public static function fetch_delivery_terms(){

        $response = WF_Delivery_Terms::get_delivery_terms();
        self::respond( $response );
    }

	/**
	 * Process AJAX request
	 */
	public static function process(){

		$response = [];

		switch( $_REQUEST[ 'fortnox_action' ] ) {
            case self::WF_ACTION_AJAX_SYNC_ORDER:
                $response = WF_Admin_Listing_Actions::ajax_sync_order();
				break;
			case self::WF_ACTION_AJAX_SYNC_PRODUCT:
                $response = WF_Admin_Listing_Actions::ajax_sync_product();
                break;
            case self::WF_ACTION_AJAX_SEND_INVOICE:
                $response = WF_Admin_Listing_Actions::ajax_send_invoice();
                break;
		}

		self::respond( $response );
	}

	/**
	 * Do bulk action through AJAX
	 */
	public static function bulk_action(){

		$response = [ 'error' => false ];

		if( empty( $_REQUEST['bulk'] ) ){
            self::error( "Bulk action is missing." );
        }

		switch( $_REQUEST['bulk'] ) {
            case self::WF_ACTION_AJAX_BULK_SYNC_PRODUCTS:
                $response = WF_Admin_Listing_Actions::bulk_sync_products();
                break;
            case self::WF_ACTION_AJAX_FLUSH_ACCESS_TOKEN:
                $response = WF_Admin_Listing_Actions::ajax_flush_access_token();
				break;
            case self::WF_ACTION_AJAX_BULK_SYNC_ORDERS:
                $response = WF_Admin_Listing_Actions::bulk_sync_orders();
                break;
            case self::WF_ACTION_AJAX_GET_SETTINGS:
                $response = WF_Admin_Listing_Actions::fetch_settings();
                break;

		}
		
		self::respond( $response );
	}

    /** Check License key
     *
     */
	public static function check_license_key(){

        if( empty( $_REQUEST[ 'key' ] ) ){
            self::error( __( "License key is empty", WF_Plugin::TEXTDOMAIN ) );
        }

        update_option( 'fortnox_license_key' , $_REQUEST[ 'key' ] );

		if ( WF_Credentials::check() ) {
			self::respond( [ 'message' => __( "License key is valid.", WF_Plugin::TEXTDOMAIN ) ] );
		}else{
			self::error( __( "License key is invalid.", WF_Plugin::TEXTDOMAIN ) );
		}
	}

	/**
	 * Check Auth code and authorize Fortnox
	 */
	public static function check_auth_code(){
        if( empty( $_REQUEST[ 'key' ] ) ){
            self::error( __( "Authorization code is empty.", WF_Plugin::TEXTDOMAIN ) );
        }

        update_option( 'fortnox_auth_code' , $_REQUEST[ 'key' ] );
		$fortnox_access_token = get_option( 'fortnox_access_token'  );

		if( ! empty( $fortnox_access_token ) ){
            self::respond( [ 'message' => __( "Auth code is valid. Access token has been already generated. If you need to regenerate the access token please flush it using 'Flush access token' button under the Advanced tab.", WF_Plugin::TEXTDOMAIN ) ] );
        }

		try {
			WF_Auth::get_access_token();
            WF_Admin_Listing_Actions::fetch_settings();
		}
		catch( \Exception $error ) {
			self::error( $error->getMessage() );
		}

		self::respond( [ 'message' => __( "Authorisation code is OK. Access token has been generated.", WF_Plugin::TEXTDOMAIN ) ] );
	}
}
