<?php
/**
 * This file contains the class that defines REST API endpoints for
 * managing a Nelio Content account.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\get;

class Nelio_Content_Account_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Account_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Account_REST_Controller the single instance of this class.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * Hooks into WordPress.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}//end init()

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_site_data' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/free',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_free_site' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/use-license',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'use_license_in_site' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
					'args'                => array(
						'license' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_valid_license',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/remove-license',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'remove_license_from_site' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
					'args'                => array(
						'siteId' => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/upgrade',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'upgrade_subscription' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
					'args'                => array(
						'product' => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription',
			array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'cancel_subscription' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/uncancel',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'uncancel_subscription' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/sites',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_sites_using_subscription' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/invoices',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_invoices_from_subscription' ),
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/authentication-token',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_authentication_token' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/products',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_products' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

	}//end register_routes()

	/**
	 * Retrieves information about the site.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_site_data() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update subscription information with response.
		$site_info = json_decode( $response['body'], true );
		$account   = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );

		return new WP_REST_Response( $account, 200 );

	}//end get_site_data()

	/**
	 * Creates a new free site in AWS and updates the info in WordPress.
	 *
	 * @return WP_REST_Response The response
	 */
	public function create_free_site() {

		if ( nc_get_site_id() ) {
			return new WP_Error(
				'site-already-exists',
				_x( 'Site already exists.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'url'      => home_url(),
					'timezone' => nc_get_timezone(),
					'language' => nc_get_language(),
				)
			),
		);

		$url      = nc_get_api_url( '/site', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		update_option( 'nc_api_secret', $site_info['secret'] );

		// Update subscription information with response.
		$account = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );

		$this->notify_site_created();

		return new WP_REST_Response( $account, 200 );

	}//end create_free_site()

	/**
	 * Connects a site with a subscription.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function use_license_in_site( $request ) {

		$parameters = $request->get_json_params();
		$license    = $parameters['license'];

		if ( nc_get_site_id() ) {

			$data = array(
				'method'    => 'POST',
				'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
				'sslverify' => ! nc_does_api_use_proxy(),
				'headers'   => array(
					'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				),
				'body'      => wp_json_encode(
					array(
						'license' => $license,
					)
				),
			);

			$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );

		} else {

			$data = array(
				'method'    => 'POST',
				'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
				'sslverify' => ! nc_does_api_use_proxy(),
				'headers'   => array(
					'accept'       => 'application/json',
					'content-type' => 'application/json',
				),
				'body'      => wp_json_encode(
					array(
						'url'      => home_url(),
						'timezone' => nc_get_timezone(),
						'language' => nc_get_language(),
						'license'  => $license,
					)
				),
			);

			$url = nc_get_api_url( '/site/subscription', 'wp' );

		}//end if

		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );
		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		$account = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );

		// If this is a new site, let's also save the ID and the secret.
		if ( ! nc_get_site_id() ) {
			update_option( 'nc_site_id', $site_info['id'] );
			update_option( 'nc_api_secret', $site_info['secret'] );
			$this->notify_site_created();
		}//end if

		return new WP_REST_Response( $account, 200 );

	}//end use_license_in_site()

	/**
	 * Removes the license from this site (if any).
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function remove_license_from_site( $request ) {

		$data = array(
			'method'    => 'POST',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . $request['siteId'] . '/subscription/free', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		return new WP_REST_Response( true, 200 );

	}//end remove_license_from_site()

	/**
	 * Upgrades the subscription to a yearly subscription.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function upgrade_subscription( $request ) {

		$data = array(
			'method'    => 'PUT',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'product' => $request['product'],
				)
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		$account = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );

		return new WP_REST_Response( $account, 200 );

	}//end upgrade_subscription()

	/**
	 * Cancels a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function cancel_subscription() {

		if ( ! nc_get_site_id() ) {
			return new WP_Error(
				'no-site-id',
				_x( 'Subscription cannot be canceled, because there’s no account available.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'DELETE',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		$account = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );
		update_option( 'nc_site_id', $site_info['id'] );

		return new WP_REST_Response( $account, 200 );

	}//end cancel_subscription()

	/**
	 * Un-cancels a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function uncancel_subscription() {

		if ( ! nc_get_site_id() ) {
			return new WP_Error(
				'no-site-id',
				_x( 'Subscription cannot be reactivated, because there’s no account available.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/uncancel', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		$account = $this->create_account_object( $site_info );
		nc_update_subscription( $account['plan'], $account['limits'] );
		update_option( 'nc_site_id', $site_info['id'] );

		return new WP_REST_Response( $account, 200 );

	}//end uncancel_subscription()

	/**
	 * Obtains all sites connected to a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_sites_using_subscription() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/sites', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Extract the current site.
		$sites     = json_decode( $response['body'], true );
		$site_id   = nc_get_site_id();
		$key       = array_search( $site_id, array_column( $sites, 'id' ), true );
		$this_site = ! empty( $sites[ $key ] ) ? $sites[ $key ] : array();
		array_splice( $sites, $key, 1 );

		// Map other sites to the appropriate object form.
		$sites = array_map(
			function( $site ) {
				return array(
					'id'            => $site['id'],
					'url'           => $site['url'],
					'isCurrentSite' => false,
				);
			},
			$sites
		);

		// Fix this site.
		$this_site = array(
			'id'            => nc_get_site_id(),
			'url'           => isset( $this_site['url'] ) ? $this_site['url'] : home_url(),
			'actualUrl'     => home_url(),
			'isCurrentSite' => true,
		);

		// Merge them all and return.
		array_unshift( $sites, $this_site );
		return new WP_REST_Response( $sites, 200 );

	}//end get_sites_using_subscription()


	/**
	 * Obtains the invoices of a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_invoices_from_subscription() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/invoices', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Regenerate the invoices result and send it to the JS.
		$invoices = json_decode( $response['body'], true );
		$invoices = array_map(
			function( $invoice ) {
				$invoice['chargeDate'] = gmdate( get_option( 'date_format' ), strtotime( $invoice['chargeDate'] ) );
				return $invoice;
			},
			$invoices
		);

		return new WP_REST_Response( $invoices, 200 );

	}//end get_invoices_from_subscription()

	/**
	 * Obtains the subscription products of Nelio Content.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_REST_Response The response
	 */
	public function get_products( $request ) {

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/fastspring/products', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Regenerate the products result and send it to the JS.
		$products = json_decode( $response['body'], true );
		$products = array_map(
			function( $product ) {
				$from = get( $product, 'upgradeableFrom' );
				if ( ! is_array( $from ) ) {
					$from = empty( $from ) ? array() : array( $from );
				}//end if
				return array(
					'id'              => get( $product, 'product' ),
					'plan'            => nc_get_plan( get( $product, 'product' ) ),
					'period'          => get( $product, 'pricing.interval' ),
					'displayName'     => get( $product, 'display' ),
					'price'           => get( $product, 'pricing.price' ),
					'description'     => get( $product, 'description.full' ),
					'attributes'      => get( $product, 'attributes' ),
					'isSubscription'  => get( $product, 'isSubscription' ),
					'upgradeableFrom' => $from,
				);
			},
			$products
		);

		return new WP_REST_Response( $products, 200 );

	}//end get_products()

	/**
	 * Gets an authentication token for the current user.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_authentication_token() {
		return new WP_REST_Response( nc_generate_api_auth_token(), 200 );
	}//end get_authentication_token()

	/**
	 * This helper function creates an account object.
	 *
	 * @param object $site The data about the site.
	 *
	 * @return array an account object.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function create_account_object( $site ) {

		$limits = array(
			'maxAutomationGroups'   => 1,
			'maxProfiles'           => get( $site, 'maxProfiles', -1 ),
			'maxProfilesPerNetwork' => get( $site, 'maxProfilesPerNetwork', 1 ),
		);

		$subscription_id = get( $site, 'subscription.id' );
		if ( empty( $subscription_id ) ) {
			return array(
				'siteId' => nc_get_site_id(),
				'plan'   => 'free',
				'limits' => $limits,
			);
		}//end if

		$sites_allowed = absint( get( $site, 'subscription.sites' ) );

		$groups_allowed                = absint( get( $site, 'subscription.maxAutomationGroups' ) );
		$groups_allowed                = empty( $groups_allowed ) ? 1 : $groups_allowed;
		$limits['maxAutomationGroups'] = $groups_allowed;

		return array(
			'creationDate'        => get( $site, 'creation' ),
			'currency'            => get( $site, 'subscription.currency', 'USD' ),
			'deactivationDate'    => get( $site, 'subscription.deactivationDate', '' ),
			'email'               => get( $site, 'subscription.account.email' ),
			'endDate'             => get( $site, 'subscription.endDate', '' ),
			'firstname'           => get( $site, 'subscription.account.firstname' ),
			'isAgency'            => get( $site, 'subscription.isAgency', false ),
			'lastname'            => get( $site, 'subscription.account.lastname' ),
			'license'             => get( $site, 'subscription.license' ),
			'limits'              => $limits,
			'mode'                => get( $site, 'subscription.mode' ),
			'nextChargeDate'      => get( $site, 'subscription.nextChargeDate', '' ),
			'nextChargeTotal'     => get( $site, 'subscription.nextChargeTotal', get( $site, 'subscription.nextChargeTotalDisplay', '' ) ),
			'period'              => get( $site, 'subscription.intervalUnit' ),
			'photo'               => get_avatar_url( get( $site, 'subscription.account.email' ), array( 'default' => 'mysteryman' ) ),
			'plan'                => nc_get_plan( get( $site, 'subscription.product' ) ),
			'productId'           => get( $site, 'subscription.product' ),
			'state'               => get( $site, 'subscription.state' ),
			'sitesAllowed'        => ! empty( $sites_allowed ) ? $sites_allowed : 1,
			'siteId'              => nc_get_site_id(),
			'subscription'        => $subscription_id,
			'urlToManagePayments' => nc_get_api_url( '/fastspring/' . $subscription_id . '/url', 'browser' ),
		);

	}//end create_account_object()

	private function notify_site_created() {

		/**
		 * Fires once the site has been registered in Nelio’s cloud.
		 *
		 * When fired, the site has a valid site ID and an API secret.
		 *
		 * @since 2.0.0
		 */
		do_action( 'nelio_content_site_created' );

	}//end notify_site_created()

}//end class
