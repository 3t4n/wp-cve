<?php
/**
 * This file contains the class that defines REST API endpoints for
 * working with posts managed by Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Post_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Post_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Post_REST_Controller the single instance of this class.
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
			'/calendar/posts',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_calendar_posts' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'from' => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'to'   => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/search',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_posts' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'per_page' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'page'     => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'status'   => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => flow(
								'sanitize_text_field',
								'trim',
								fn( $v ) => empty( $v ) ? 'publish' : $v,
								fn( $v ) => explode( ',', $v )
							),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_post' ),
					'permission_callback' => array( $this, 'check_if_user_can_create_post' ),
					'args'                => array(
						'authorId'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'title'      => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'dateValue'  => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'timeValue'  => array(
							'required'          => false,
							'type'              => 'time',
							'validate_callback' => 'nc_is_time',
						),
						'type'       => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_valid_post_type',
						),
						'status'     => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'taxonomies' => array(
							'required'          => false,
							'type'              => 'record<taxonomy name, term with id[]>',
							'sanitize_callback' => array( $this, 'sanitize_taxonomies' ),
						),
						'references' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => nc_is_array( 'nc_is_url' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'id'  => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'aws' => array(
							'required'          => false,
							'type'              => 'flag (true iff present)',
							'sanitize_callback' => '__return_true',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_post' ),
					'permission_callback' => array( $this, 'check_if_user_can_edit_post' ),
					'args'                => array(
						'id'         => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'authorId'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'title'      => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'dateValue'  => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'timeValue'  => array(
							'required'          => false,
							'type'              => 'time',
							'validate_callback' => 'nc_is_time',
						),
						'status'     => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'taxonomies' => array(
							'required'          => false,
							'type'              => 'record<taxonomy name, term with id[]>',
							'sanitize_callback' => array( $this, 'sanitize_taxonomies' ),
						),
						'references' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => nc_is_array( 'nc_is_url' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/(?P<id>[\d]+)/references',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post_references' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'id' => array(
							'required'          => true,
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
			'/post/(?P<id>[\d]+)/reschedule',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'reschedule_post' ),
					'permission_callback' => array( $this, 'check_if_user_can_edit_post' ),
					'args'                => array(
						'id'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'day'  => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'hour' => array(
							'required'          => false,
							'type'              => 'time',
							'validate_callback' => 'nc_is_time',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/(?P<id>[\d]+)/unschedule',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'unschedule_post' ),
					'permission_callback' => array( $this, 'check_if_user_can_edit_post' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
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
			'/post/(?P<id>[\d]+)/trash',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'trash_post' ),
					'permission_callback' => array( $this, 'check_if_user_can_trash_post' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

	}//end register_routes()

	public function check_if_user_can_create_post( $request ) {

		if ( nc_can_current_user_manage_plugin() ) {
			return true;
		}//end if

		$post_type = get_post_type_object( $request['type'] );
		return current_user_can( $post_type->cap->create_posts );

	}//end check_if_user_can_create_post()

	public function check_if_user_can_edit_post( $request ) {

		$post_id   = $request['id'];
		$post_type = get_post_type( $post_id );
		if ( empty( $post_type ) ) {
			return false;
		}//end if

		if ( ! nc_is_valid_post_type( $post_type ) ) {
			return false;
		}//end if

		if ( nc_can_current_user_manage_plugin() ) {
			return true;
		}//end if

		$post_type  = get_post_type_object( $post_type );
		$capability = in_array( get_post_status( $post_id ), array( 'publish', 'future' ), true )
			? $post_type->cap->edit_published_posts
			: $post_type->cap->edit_posts;
		return current_user_can( $capability, $post_id );
	}//end check_if_user_can_edit_post()

	public function check_if_user_can_trash_post( $request ) {

		$editable = $this->check_if_user_can_edit_post( $request );
		if ( empty( $editable ) ) {
			return false;
		}//end if

		if ( nc_can_current_user_manage_plugin() ) {
			return true;
		}//end if

		$post_id    = $request['id'];
		$post_type  = get_post_type( $post_id );
		$post_type  = get_post_type_object( $post_type );
		$capability = in_array( get_post_status( $post_id ), array( 'publish', 'future' ), true )
			? $post_type->cap->delete_published_posts
			: $post_type->cap->delete_posts;
		return current_user_can( $capability, $post_id );
	}//end check_if_user_can_trash_post()

	public function sanitize_taxonomies( $taxonomies ) {
		return array_map(
			function( $values ) {
				$id = function( $term ) {
					return isset( $term['id'] ) ? absint( $term['id'] ) : 0;
				};
				return array_values( array_filter( array_map( $id, $values ) ) );
			},
			$taxonomies
		);
	}//end sanitize_taxonomies()

	/**
	 * Returns the requested post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_post( $request ) {

		$post = get_post( $request['id'] );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		if ( empty( $post ) ) {
			return new WP_Error(
				'post-not-found',
				_x( 'Post not found.', 'text', 'nelio-content' )
			);
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();

		return isset( $request['aws'] ) && $request['aws']
			? new WP_REST_Response( $post_helper->post_to_aws_json( $post->ID ), 200 )
			: new WP_REST_Response( $post_helper->post_to_json( $post->ID ), 200 );

	}//end get_post()

	/**
	 * Gets the post references.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_post_references( $request ) {
		$post_id     = $request['id'];
		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response(
			$post_helper->get_references( $post_id, 'suggested' ),
			200
		);
	}//end get_post_references()

	/**
	 * Gets all posts in given date period.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_calendar_posts( $request ) {

		global $post;
		$from = $request->get_param( 'from' );
		$to   = $request->get_param( 'to' );

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types', array() );

		$args = array(
			'date_query'     => array(
				'after'     => $from,
				'before'    => $to,
				'inclusive' => true,
			),
			'posts_per_page' => -1, // phpcs:ignore
			'orderby'        => 'date',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
			'post_status'    => 'any',
		);

		$query       = new WP_Query( $args );
		$post_helper = Nelio_Content_Post_Helper::instance();

		$result = array();
		while ( $query->have_posts() ) {
			$query->the_post();

			if ( '0000-00-00 00:00:00' === $post->post_date_gmt ) {
				continue;
			}//end if

			$aux = $post_helper->post_to_json( $post );
			if ( ! empty( $aux ) ) {
				array_push( $result, $aux );
			}//end if
		}//end while

		return new WP_REST_Response( $result, 200 );

	}//end get_calendar_posts()

	/**
	 * Creates a new post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function create_post( $request ) {

		$author_id  = $request->get_param( 'authorId' );
		$title      = $request->get_param( 'title' );
		$date       = $request->get_param( 'dateValue' );
		$date       = ! empty( $date ) ? $date : false;
		$time       = $request->get_param( 'timeValue' );
		$time       = ! empty( $time ) ? $time : false;
		$post_type  = $request->get_param( 'type' );
		$status     = $request->get_param( 'status' );
		$taxonomies = $request->get_param( 'taxonomies' );
		$taxonomies = ! empty( $taxonomies ) ? $taxonomies : array();
		$references = $request->get_param( 'references' );

		/**
		 * Modifies the title that will be used in the given post.
		 *
		 * This filter is called right before the post is saved in the database.
		 *
		 * @param string $title the new post title.
		 *
		 * @since 1.0.0
		 */
		$title = trim( apply_filters( 'nelio_content_calendar_create_post_title', $title ) );
		if ( empty( $title ) ) {
			$title = _x( 'No Title', 'text', 'nelio-content' );
		}//end if

		// Create new post.
		$post_data = array(
			'post_title'  => $title,
			'post_author' => $author_id,
			'post_type'   => $post_type,
			'post_status' => $status,
		);
		if ( $date && $time ) {
			$post_data['post_date']     = "$date $time:00";
			$post_data['post_date_gmt'] = get_gmt_from_date( gmdate( 'Y-m-d H:i:s', strtotime( "$date $time:00" ) ) );
		} else {
			$post_data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		$post_id = wp_insert_post( $post_data );
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post could not be created.', 'text', 'nelio-content' )
			);
		}//end if

		// NOTE. Make sure post_modified and post_modified_gmt are properly set by triggering an update.
		$post_data['ID'] = $post_id;
		wp_update_post( $post_data );

		$this->trigger_save_post_action( $post_id, true );

		foreach ( $taxonomies as $tax => $term_ids ) {
			wp_set_post_terms( $post_id, $term_ids, $tax );
		}//end foreach

		$post_helper = Nelio_Content_Post_Helper::instance();
		$post_helper->update_post_references( $post_id, $references, array() );

		$post = get_post( $post_id ); // phpcs:ignore
		if ( ! $post || is_wp_error( $post ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post was successfully created, but could not be retrieved.', 'text', 'nelio-content' )
			);
		}//end if

		$response = array(
			'post'       => $post_helper->post_to_json( $post ),
			'references' => $post_helper->get_references( $post_id, 'suggested' ),
		);
		return new WP_REST_Response( $response, 200 );

	}//end create_post()

	/**
	 * Updates a post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function update_post( $request ) {

		$post_id    = $request['id'];
		$author_id  = $request->get_param( 'authorId' );
		$title      = $request->get_param( 'title' );
		$date       = $request->get_param( 'dateValue' );
		$date       = ! empty( $date ) ? $date : false;
		$time       = $request->get_param( 'timeValue' );
		$time       = ! empty( $time ) ? $time : false;
		$status     = $request->get_param( 'status' );
		$taxonomies = $request->get_param( 'taxonomies' );
		$taxonomies = ! empty( $taxonomies ) ? $taxonomies : array();
		$references = $request->get_param( 'references' );

		/**
		 * Modifies the title that will be used in the given post.
		 *
		 * This filter is called right before the post is updated and saved in the database.
		 *
		 * @param string $title   the new post title.
		 * @param int    $post_id the ID of the post we're updating.
		 *
		 * @since 1.0.0
		 */
		$title = trim( apply_filters( 'nelio_content_calendar_update_post_title', $title, $post_id ) );
		if ( empty( $title ) ) {
			$title = _x( 'No Title', 'text', 'nelio-content' );
		}//end if

		$post = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$post_data = array(
			'ID'          => $post_id,
			'post_title'  => $title,
			'post_author' => $author_id,
			'post_status' => $status,
			'edit_date'   => true,
		);
		if ( $date && $time ) {
			$post_data['post_date']     = "$date $time:00";
			$post_data['post_date_gmt'] = get_gmt_from_date( gmdate( 'Y-m-d H:i:s', strtotime( "$date $time:00" ) ) );
		} else {
			$post_data['post_date']     = '0000-00-00 00:00:00';
			$post_data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		$aux = wp_update_post( $post_data );
		if ( is_wp_error( $aux ) ) {
			return new WP_Error(
				'post-not-updated',
				sprintf(
					/* translators: a post ID */
					_x( 'Post %s could not be updated.', 'text', 'nelio-content' ),
					$post_id
				)
			);
		}//end if

		foreach ( $taxonomies as $tax => $term_ids ) {
			wp_set_post_terms( $post_id, $term_ids, $tax );
		}//end foreach

		$post_helper = Nelio_Content_Post_Helper::instance();
		$post_helper->update_post_references( $post_id, $references, array() );

		$this->trigger_save_post_action( $post_id, false );

		$post = get_post( $post_id ); // phpcs:ignore
		if ( ! $post || is_wp_error( $post ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post was successfully updated, but could not be retrieved.', 'text', 'nelio-content' )
			);
		}//end if

		$response = array(
			'post'       => $post_helper->post_to_json( $post ),
			'references' => $post_helper->get_references( $post_id, 'suggested' ),
		);
		return new WP_REST_Response( $response, 200 );

	}//end update_post()

	/**
	 * Search posts.
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_REST_Response The response
	 */
	public function search_posts( $request ) {

		$per_page = $request->get_param( 'per_page' );
		$per_page = ! empty( $per_page ) ? $per_page : 10;
		$page     = $request->get_param( 'page' );
		$page     = ! empty( $page ) ? $page : 1;
		$status   = $request->get_param( 'status' );

		$query = $request->get_param( 'query' );
		$args  = array(
			'per_page' => $per_page,
			'page'     => $page,
			'status'   => $status,
		);

		$data = $this->search_wp_posts( $query, $args );
		return new WP_REST_Response( $data, 200 );

	}//end search_posts()

	/**
	 * Reschedules the post to the given date.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function reschedule_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$day  = $request->get_param( 'day' );
		$time = $request->get_param( 'hour' );

		if ( empty( $time ) ) {
			$time = '10:00';
			if ( '0000-00-00 00:00:00' !== $post->post_date_gmt ) {
				$time = gmdate( 'H:i:s', strtotime( $post->post_date ) );
			}//end if
		}//end if

		wp_update_post(
			array(
				'ID'            => $post_id,
				'post_date'     => $day . ' ' . $time,
				'post_date_gmt' => get_gmt_from_date( gmdate( 'Y-m-d H:i:s', strtotime( $day . ' ' . $time ) ) ),
				'edit_date'     => true,
			)
		);
		$this->trigger_save_post_action( $post_id, false );

		$post        = get_post( $post_id ); // phpcs:ignore
		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end reschedule_post()

	/**
	 * Unschedules the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function unschedule_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		wp_update_post(
			array(
				'ID'            => $post_id,
				'post_date'     => '0000-00-00 00:00:00',
				'post_date_gmt' => '0000-00-00 00:00:00',
				'edit_date'     => true,
			)
		);
		$this->trigger_save_post_action( $post_id, false );

		$post = get_post( $post_id ); // phpcs:ignore
		if ( ! $post || is_wp_error( $post ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post was successfully unscheduled, but could not be retrieved.', 'text', 'nelio-content' )
			);
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end unschedule_post()

	/**
	 * Trashes the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function trash_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$result = wp_trash_post( $post_id );
		if ( is_wp_error( $result ) ) {
			return $result;
		}//end if
		$this->trigger_save_post_action( $post_id, false );

		return new WP_REST_Response( true, 200 );

	}//end trash_post()

	private function trigger_save_post_action( $post_id, $creating ) {
		/**
		 * This filter is documented in includes/utils/class-nelio-content-post-saving.php
		 */
		do_action( 'nelio_content_save_post', $post_id, $creating );
	}//end trigger_save_post_action()

	private function search_wp_posts( $query, $args ) {

		global $post;
		global $wpdb;
		$wpdb->set_sql_mode( array( 'ALLOW_INVALID_DATES' ) );

		$page     = $args['page'];
		$per_page = $args['per_page'];
		$status   = $args['status'];

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );

		$posts = array();
		if ( 1 === $page ) {
			$posts = $this->search_wp_post_by_id_or_url( $query, $post_types );
		}//end if

		$args = array(
			'post_title__like' => $query,
			'post_type'        => $post_types,
			'order'            => 'desc',
			'orderby'          => 'date',
			'posts_per_page'   => $per_page,
			'paged'            => $page,
		);

		$args['post_status'] = $status;
		if ( count( $status ) === 1 && 'nc_unscheduled' === $status[0] ) {
			$args['post_status'] = 'any';
			$args['date_query']  = array(
				'column'    => 'post_date_gmt',
				'before'    => '0000-00-00',
				'inclusive' => true,
			);
		}//end if

		add_filter( 'posts_where', array( $this, 'add_title_filter_to_wp_query' ), 10, 2 );
		$wp_query = new WP_Query( $args );
		remove_filter( 'posts_where', array( $this, 'add_title_filter_to_wp_query' ), 10, 2 );

		$post_helper = Nelio_Content_Post_Helper::instance();
		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();
			if ( absint( $query ) !== $post->ID ) {
				array_push(
					$posts,
					$post_helper->post_to_json( $post )
				);
			}//end if
		}//end while

		wp_reset_postdata();

		$data = array(
			'results'    => $posts,
			'pagination' => array(
				'more'  => $page < $wp_query->max_num_pages,
				'pages' => $wp_query->max_num_pages,
			),
		);

		return $data;

	}//end search_wp_posts()

	private function search_wp_post_by_id_or_url( $id_or_url, $post_types ) {

		if ( ! absint( $id_or_url ) && ! filter_var( $id_or_url, FILTER_VALIDATE_URL ) ) {
			return array();
		}//end if

		$post_id = $id_or_url;
		if ( ! absint( $id_or_url ) ) {
			$post_id = nc_url_to_postid( $id_or_url );
		}//end if

		$post = get_post( $post_id );
		if ( ! $post || is_wp_error( $post ) ) {
			return array();
		}//end if

		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return array();
		}//end if

		if ( ! in_array( $post->post_status, array( 'publish', 'draft' ), true ) ) {
			return array();
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		return array( $post_helper->post_to_json( $post ) );

	}//end search_wp_post_by_id_or_url()

	/**
	 * A filter to search posts based on their title.
	 *
	 * This function modifies the posts query so that we can search posts based
	 * on a term that should appear in their titles.
	 *
	 * @param string   $where    The where clause, as it's originally defined.
	 * @param WP_Query $wp_query The $wp_query object that contains the params
	 *                           used to build the where clause.
	 *
	 * @return string the query.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_title_filter_to_wp_query( $where, $wp_query ) {

		global $wpdb;

		$search_term = $wp_query->get( 'post_title__like' );
		if ( $search_term ) {
			$search_term = $wpdb->esc_like( $search_term );
			$search_term = ' \'%' . $search_term . '%\'';
			$where       = $where . ' AND ' . $wpdb->posts . '.post_title LIKE ' . $search_term;
		}//end if

		return $where;

	}//end add_title_filter_to_wp_query()

	private function maybe_get_post( $post_id ) {

		if ( empty( $post_id ) ) {
			return new WP_Error(
				'missing-post-id',
				_x( 'Post ID is missing.', 'text', 'nelio-content' )
			);
		}//end if

		$post = get_post( $post_id ); // phpcs:ignore
		if ( is_wp_error( $post ) || empty( $post ) ) {
			return new WP_Error(
				'post-not-found',
				sprintf(
					/* translators: a post ID */
					_x( 'Post %s not found.', 'text', 'nelio-content' ),
					$post_id
				)
			);
		}//end if

		return $post;

	}//end maybe_get_post()

}//end class
