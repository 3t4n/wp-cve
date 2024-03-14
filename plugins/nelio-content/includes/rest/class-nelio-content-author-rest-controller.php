<?php
/**
 * This file contains the class that defines REST API endpoints for
 * retrieving authors.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Author_REST_Controller extends WP_REST_Controller {

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
	 * @return Nelio_Content_Author_REST_Controller the single instance of this class.
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
			'/author/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_author' ),
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
			'/author/search',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_authors' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'query'    => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'page'     => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'per_page' => array(
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

	/**
	 * Retrieves the specified author.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_author( $request ) {

		$author_id = $request['id'];

		$author = get_userdata( $author_id );
		if ( ! $author ) {
			return new WP_Error(
				'author-not-found',
				sprintf(
					/* translators: author id */
					_x( 'Author %d not found.', 'text', 'nelio-content' ),
					$author_id
				)
			);
		}//end if

		return new WP_REST_Response( $this->json( $author ), 200 );

	}//end get_author()

	/**
	 * Search authors.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function search_authors( $request ) {

		$query    = $request->get_param( 'query' );
		$per_page = $request->get_param( 'per_page' );
		$page     = $request->get_param( 'page' );

		// Search query.
		$args = array(
			'capability' => array( 'edit_posts' ),
			'number'     => $per_page,
			'order'      => 'ASC',
			'orderby'    => 'display_name',
			'paged'      => $page,
			'search'     => '*' . esc_attr( $query ) . '*',
			'meta_query' => array( // phpcs:ignore
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $query,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'last_name',
					'value'   => $query,
					'compare' => 'LIKE',
				),
			),
		);

		if ( empty( $query ) ) {
			$args['exclude'] = $this->get_priority_authors(); // phpcs:ignore
		}//end if

		$wp_query = new WP_User_Query( $args );
		$authors  = array_map(
			function( $user ) {
				return $this->json( $user->data );
			},
			$wp_query->get_results()
		);

		if ( empty( $query ) ) {
			if ( 1 === $page ) {
				$authors = $this->add_priority_authors( $authors );
			}//end if
		}//end if

		// Build result object, ready for pagination.
		$result = array(
			'results'    => $authors,
			'pagination' => array(
				'more'  => $page < $wp_query->max_num_pages,
				'pages' => $wp_query->max_num_pages,
			),
		);

		return new WP_REST_Response( $result, 200 );

	}//end search_authors()

	private function add_priority_authors( $authors ) {

		$priority_authors = array_map(
			function( $user_id ) {
				return $this->json( get_userdata( $user_id ) );
			},
			$this->get_priority_authors()
		);

		return array_merge(
			array_filter( $priority_authors ),
			$authors
		);

	}//end add_priority_authors()

	private function get_priority_authors() {
		/**
		 * Returns a list of author IDs.
		 *
		 * These authors will be the first results returned by an empty search.
		 *
		 * @param array $author_ids list of author IDs.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'nelio_content_get_priority_authors', array() );
	}//end get_priority_authors()

	private function json( $author ) {

		$author_id = absint( $author->ID );

		return array(
			'id'      => $author_id,
			'isAdmin' => user_can( $author_id, 'manage_options' ),
			'email'   => $this->mask_email( $author->user_email ),
			'name'    => $author->display_name,
			'photo'   => get_avatar_url(
				$author->user_email,
				array(
					'size'    => 60,
					'default' => 'blank',
				)
			),
		);

	}//end json()

	private function mask_email( $email ) {

		$domain    = strrchr( $email, '@' );
		$extension = strrchr( $domain, '.' );
		$mailname  = str_replace( $domain, '', $email );

		$domain = str_replace( $extension, '', $domain );

		$domain    = substr( $domain, 1 );
		$extension = substr( $extension, 1 );

		if ( strlen( $mailname ) < 5 ) {
			$mailname = '***';
		} else {
			$mailname = substr( $mailname, 0, 3 ) . '***';
		}//end if

		if ( strlen( $domain ) < 5 ) {
			$domain = '***';
		} else {
			$domain = substr( $domain, 0, 3 ) . '***';
		}//end if

		return "$mailname@$domain$extension";

	}//end mask_email()

}//end class
