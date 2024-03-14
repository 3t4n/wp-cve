<?php
/**
 * This file contains the class that defines REST API endpoints for
 * managing post references.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Reference_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Reference_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Reference_REST_Controller the single instance of this class.
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
			'/reference/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_reference' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'id'      => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'title'   => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'author'  => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'email'   => array(
							'required'          => false,
							'type'              => 'string',
							'validate_callback' => 'nc_is_email',
						),
						'twitter' => array(
							'required'          => false,
							'type'              => 'string',
							'validate_callback' => 'nc_is_twitter_handle',
						),
						'date'    => array(
							'required'          => false,
							'type'              => 'datetime',
							'validate_callback' => 'nc_is_datetime',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/reference/search',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_reference_by_url' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
			)
		);

	}//end register_routes()

	/**
	 * Gets the requested reference.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function search_reference_by_url( $request ) {

		$url       = $request->get_param( 'url' );
		$reference = nc_create_reference( $url );
		return new WP_REST_Response( $reference->json_encode(), 200 );

	}//end search_reference_by_url()

	/**
	 * Updates the reference.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_reference( $request ) {

		$reference_id = $request['id'];

		$reference = nc_get_reference( $reference_id );
		if ( ! $reference ) {
			return new WP_Error(
				'reference-not-found',
				_x( 'Reference not found', 'text', 'nelio-content' )
			);
		}//end if

		$title = $request->get_param( 'title' );
		$title = ! empty( $title ) ? $title : '';
		$reference->set_title( $title );

		$author = $request->get_param( 'author' );
		$author = ! empty( $author ) ? $author : '';
		$reference->set_author_name( $author );

		$email = $request->get_param( 'email' );
		$email = ! empty( $email ) ? $email : '';
		$reference->set_author_email( $email );

		$twitter = $request->get_param( 'twitter' );
		$twitter = ! empty( $twitter ) ? $twitter : '';
		$reference->set_author_twitter( $twitter );

		$date = $request->get_param( 'date' );
		$date = ! empty( $date ) ? $date : '';
		$reference->set_publication_date( $date );

		return new WP_REST_Response( $reference->json_encode(), 200 );

	}//end update_reference()

}//end class
