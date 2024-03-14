<?php
/**
 * This file contains REST endpoints to work with analytics.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Analytics_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Author_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Analytics_REST_Controller the single instance of this class.
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
			'/analytics/top-posts',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_top_posts' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'author'   => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'from'     => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'to'       => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'postType' => array(
							'required' => false,
							'type'     => 'string',
						),
						'sortBy'   => array(
							'required' => false,
							'type'     => 'string',
						),
						'page'     => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'perPage'  => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/connect',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'connect_google_analytics' ),
					'permission_callback' => array( $this, 'validate_analytics_connection' ),
					'args'                => array(
						'token'         => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'refresh-token' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'expiration'    => array(
							'required'          => true,
							'type'              => 'numeric',
							'sanitize_callback' => 'absint',
						),
						'nonce'         => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/refresh-access-token',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'refresh_google_analytics_token' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/check',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'has_google_analytics_token' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/post',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post_ids_to_update' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'page'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'period' => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/post/(?P<id>[\d]+)/update',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_post_analytics' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

	}//end register_routes()

	public function validate_analytics_connection( $request ) {
		$token         = $request['token'];
		$refresh_token = $request['refresh-token'];
		$nonce         = $request['nonce'];
		$secret        = get_option( 'nc_api_secret', false );
		return md5( "{$token}{$refresh_token}{$secret}" ) === $nonce;
	}//end validate_analytics_connection()

	/**
	 * Returns the list of top posts that match the search criteria.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_top_posts( $request ) {

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types', array() );

		// Get the author id.
		$author_id = isset( $request['author'] ) ? $request['author'] : 0;

		// Get the time interval.
		$first_day = $this->get_top_posts_arg( $request, 'from' );
		$first_day = ! empty( $first_day ) ? $first_day : false;
		$last_day  = $this->get_top_posts_arg( $request, 'to' );
		$last_day  = ! empty( $last_day ) ? $last_day : false;
		$last_day  = $last_day ? "{$last_day} 23:59:59" : $last_day;

		// Post type.
		$post_types = $this->get_top_posts_arg( $request, 'postType' );
		$post_types = ! empty( $post_types ) ? $post_types : false;
		$post_types = empty( $post_types ) ? $enabled_post_types : array( $post_types );

		// Sort by criterion.
		$ranking_field = $this->get_top_posts_arg( $request, 'sortBy' );
		$ranking_field = ! empty( $ranking_field ) ? $ranking_field : false;
		$ranking_field = 'pageviews' === $ranking_field ? '_nc_pageviews_total' : '_nc_engagement_total';

		// Pagination.
		$posts_per_page = isset( $request['perPage'] ) ? $request['perPage'] : 10;
		$page           = isset( $request['page'] ) ? $request['page'] : 1;

		$args = array(
			'paged'          => $page,
			'posts_per_page' => $posts_per_page,
			'author'         => $author_id,
			'meta_key'       => $ranking_field, // phpcs:ignore
			'orderby'        => 'meta_value_num post_date',
			'order'          => 'desc',
			'post_type'      => $post_types,
			'date_query'     => array(
				'after'     => $first_day,
				'before'    => $last_day,
				'inclusive' => true,
			),
		);

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$result    = $analytics->get_paginated_posts( $args );
		return new WP_REST_Response( $result, 200 );

	}//end get_top_posts()

	private function get_top_posts_arg( $request, $name ) {
		if ( ! isset( $request[ $name ] ) ) {
			return false;
		}//end if
		return $request[ $name ];
	}//end get_top_posts_arg()

	/**
	 * Connects Google Analytics by saving its access and refresh tokens.
	 *
	 * It returns a simple HTML page that closes the screen.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 */
	public function connect_google_analytics( $request ) {
		$refresh_token = $request['refresh-token'];
		update_option( 'nc_ga_refresh_token', $refresh_token );

		$token      = $request['token'];
		$expiration = ( $request['expiration'] / MINUTE_IN_SECONDS ) - 10;
		$expiration = $expiration < 10 ? 10 : $expiration;
		set_transient( 'nc_ga_token', $token, $expiration * MINUTE_IN_SECONDS );

		delete_option( 'nc_ga_token_error' );

		header( 'Content-Type: text/html; charset=UTF-8' );
		echo '<!DOCTYPE html>';
		echo '<html><head><script>window.close();</script></head></html>';
		die();
	}//end connect_google_analytics()

	/**
	 * Refreshes Google Analytics’ access token.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function refresh_google_analytics_token() {

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$token     = $analytics->refresh_access_token();
		if ( empty( $token ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Unable to retrieve Google Analytics token.', 'text', 'nelio-content' )
			);
		}//end if

		return new WP_REST_Response( true, 200 );

	}//end refresh_google_analytics_token()

	/**
	 * Returns whether GA is connected or not.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function has_google_analytics_token() {
		$code = get_option( 'nc_ga_refresh_token', false );
		return new WP_REST_Response( ! empty( $code ), 200 );
	}//end has_google_analytics_token()

	/**
	 * Returns a list of IDs of posts that require updating.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_post_ids_to_update( $request ) {

		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types', array() );

		$page   = $request['page'];
		$period = $request['period'];
		$ppp    = 10;

		$args = array(
			'paged'          => $page,
			'post_status'    => 'publish',
			'posts_per_page' => $ppp,
			'orderby'        => 'date',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
		);

		if ( 'month' === $period || 'year' === $period ) {
			$args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 ' . $period . ' ago',
				),
			);
		}//end if

		$query  = new WP_Query( $args );
		$result = array(
			'ids'   => wp_list_pluck( $query->posts, 'ID' ),
			'more'  => $page < $query->max_num_pages,
			'total' => absint( $query->found_posts ),
			'ppp'   => $ppp,
		);
		wp_reset_postdata();

		return new WP_REST_Response( $result, 200 );

	}//end get_post_ids_to_update()

	/**
	 * Updates the analytics of all posts included in the current period.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_post_analytics( $request ) {
		$analytics = Nelio_Content_Analytics_Helper::instance();
		$analytics->update_statistics( $request['id'] );
		return new WP_REST_Response( true, 200 );
	}//end update_post_analytics()

}//end class
