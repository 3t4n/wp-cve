<?php

namespace WpifyWoo\Modules\IcDic\Api;

use Exception;
use WP_REST_Response;
use WP_REST_Server;
use WpifyWoo\Modules\IcDic\IcDicModule;
use WpifyWoo\Plugin;
use WpifyWooDeps\DragonBe\Vies\Vies;
use WpifyWooDeps\h4kuna\Ares;
use WpifyWooDeps\h4kuna\Ares\Exceptions\IdentificationNumberNotFoundException;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class IcDicApi extends AbstractRest {

	/** @var IcDicModule $module */
	private $module;

	/**
	 * ExampleApi constructor.
	 */
	public function __construct( IcDicModule $module ) {
		$this->module = $module;
	}

	public function setup() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'icdic',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_company_details' ),
					'permission_callback' => '__return_true',
					'args'                => array(
						'in' => array(
							'required' => true,
						),
					),
				),
			)
		);

		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'icdic-vies',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_valid_vies' ),
					'permission_callback' => '__return_true',
					'args'                => array(
						'in' => array(
							'required' => true,
						),
					),
				),
			)
		);
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function get_company_details( $request ) {
		$ic = $request->get_param( 'in' );

		if ( ! is_numeric( $ic ) ) {
			return new \WP_Error( 'not-found', __( 'The entered Identification Number has not been found in ARES, please enter valid Identification number.', 'wpify-woo' ) );
		}

		try {
			$ares    = ( new Ares\AresFactory() )->create();
			$record  = $ares->loadBasic( $ic );
			$details = array(
				'billing_company'   => $record->company,
				'billing_ic'        => $record->in,
				'billing_address_1' => sprintf( '%s %s', $record->street, $record->house_number ),
				'billing_city'      => $record->city,
				'billing_postcode'  => $record->zip,
				'billing_dic'       => $record->tin,
			);

			$details = apply_filters( 'wpify_woo_icdic_ares_details', $details, $record );

			return new WP_REST_Response( array( 'details' => $details ), 200 );
		} catch ( IdentificationNumberNotFoundException $e ) {
			return new \WP_Error( 'not-found', __( 'The entered Identification Number has not been found in ARES, please enter valid Identification number.', 'wpify-woo' ) );
		} catch ( Ares\Exceptions\ServerResponseException $e ) {
			return new \WP_Error( 'ares-server-error', __( 'Invalid response from ARES.', 'wpify-woo' ) );
		}
	}


	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function get_valid_vies( $request ) {
		$dic                   = $request->get_param( 'in' );
		$country               = substr( $dic, 0, 2 );
		$vat_extempt_countries = $this->module->get_setting( 'zero_tax_for_vat_countries' );
		$error_text            = __( 'The entered number did not pass the VIES validation. Check if it is correct.', 'wpify-woo' );

		if ( ! empty( $vat_extempt_countries ) && in_array( $country, $vat_extempt_countries ) ) {
			$error_text .= ' ' . __( 'Zero VAT could not be applied.', 'wpify-woo' );
		}

		if ( ! $this->module->is_valid_dic( $dic ) ) {
			return new \WP_Error( 'not-found', $error_text );;
		}

		return new WP_REST_Response( array( 'validation' => 'passed' ), 200 );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed            $item    WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}
}
