<?php
/**
 * This file contains REST endpoints to work with feeds.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

use function Nelio_Content\Helpers\flow;

class Nelio_Content_Feed_REST_Controller extends WP_REST_Controller {

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
	 * @return Nelio_Content_Feed_REST_Controller the single instance of this class.
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
			'/feeds',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_feeds' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_feed' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'url' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_feed' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'id'      => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
						'name'    => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => flow( 'trim', 'nc_is_not_empty' ),
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
						'twitter' => array(
							'required'          => false,
							'type'              => 'string',
							'validate_callback' => 'nc_is_twitter_handle',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'remove_feed' ),
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/feeds/items',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_feed_items' ),
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => flow( 'trim', 'nc_is_not_empty' ),
							'sanitize_callback' => flow( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

	}//end register_routes()

	/**
	 * Returns the feed list.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_feeds() {
		return new WP_REST_Response( get_option( 'nc_feeds', array() ), 200 );
	}//end get_feeds()

	/**
	 * Creates a new feed.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function create_feed( $request ) {

		include_once ABSPATH . WPINC . '/feed.php';

		$feed_url = $request['url'];
		$rss      = fetch_feed( $feed_url );

		if ( is_wp_error( $rss ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Error while processing feeds.', 'text', 'nelio-content' )
			);
		}//end if

		$feed = array(
			'id'   => $rss->subscribe_url(),
			'name' => ! empty( $rss->get_title() ) ? $rss->get_title() : $rss->subscribe_url(),
			'url'  => ! empty( $rss->get_permalink() ) ? $rss->get_permalink() : $rss->subscribe_url(),
			'feed' => $rss->subscribe_url(),
			'icon' => $rss->get_image_url(),
		);

		$this->save_new_feed( $feed );
		return new WP_REST_Response( $feed, 200 );
	}//end create_feed()

	/**
	 * Renames the given feed.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_feed( $request ) {

		$feed_id = $request['id'];
		$name    = trim( $request['name'] );
		$twitter = isset( $request['twitter'] ) ? $request['twitter'] : '';

		$feed = $this->get_feed( $feed_id );
		if ( empty( $feed ) ) {
			return new WP_Error(
				'feed-not-found',
				_x( 'Feed not found.', 'text', 'nelio-content' )
			);
		}//end if

		$feeds = get_option( 'nc_feeds', array() );
		foreach ( $feeds as &$feed ) {
			if ( $feed['id'] === $feed_id ) {
				$feed['name']    = $name;
				$feed['twitter'] = $twitter;
			}//end if
		}//end foreach
		update_option( 'nc_feeds', $feeds );

		return new WP_REST_Response( $this->get_feed( $feed_id ), 200 );
	}//end update_feed()

	/**
	 * Removes the given feed.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function remove_feed( $request ) {

		$feed_id = $request['id'];
		$feed    = $this->get_feed( $feed_id );
		if ( empty( $feed ) ) {
			return new WP_REST_Response( true, 200 );
		}//end if

		$feeds = get_option( 'nc_feeds', array() );
		$feeds = array_filter(
			$feeds,
			function ( $feed ) use ( $feed_id ) {
				return $feed['id'] !== $feed_id;
			}
		);
		update_option( 'nc_feeds', array_values( $feeds ) );

		return new WP_REST_Response( true, 200 );

	}//end remove_feed()

	/**
	 * Returns all the items in the given feed.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_feed_items( $request ) {

		$feed_id = $request['id'];
		$feed    = $this->get_feed( $feed_id );
		if ( empty( $feed ) ) {
			return new WP_Error(
				'feed-not-found',
				_x( 'Feed not found.', 'text', 'nelio-content' )
			);
		}//end if

		include_once ABSPATH . WPINC . '/feed.php';

		$rss = fetch_feed( $feed['feed'] );
		if ( is_wp_error( $rss ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Error while processing feeds.', 'text', 'nelio-content' )
			);
		}//end if

		$rss_items = $rss->get_items( 0, 10 );
		$result    = array_map(
			function ( $item ) use ( $feed_id ) {
				$feed = $item->get_feed();
				return array(
					'id'        => $item->get_permalink(),
					'authors'   => $this->prepare_authors( ! empty( $item->get_authors() ) ? $item->get_authors() : array() ),
					'excerpt'   => wp_strip_all_tags( $item->get_description() ),
					'date'      => $item->get_date( 'c' ),
					'feedId'    => $feed_id,
					'permalink' => $item->get_permalink(),
					'title'     => $item->get_title(),
				);
			},
			$rss_items
		);

		return new WP_REST_Response( $result, 200 );

	}//end get_feed_items()

	private function prepare_authors( $authors ) {
		return array_map(
			function ( $author ) {
				return $author->get_name();
			},
			$authors
		);
	}//end prepare_authors()

	private function save_new_feed( $feed ) {
		$old_feed = $this->get_feed( $feed['id'] );
		if ( ! empty( $old_feed ) ) {
			return;
		}//end if

		$feeds = get_option( 'nc_feeds', array() );
		array_push( $feeds, $feed );
		update_option( 'nc_feeds', $feeds );
	}//end save_new_feed()

	private function get_feed( $id ) {
		$feeds = get_option( 'nc_feeds', array() );
		foreach ( $feeds as $feed ) {
			if ( $feed['id'] === $id ) {
				return $feed;
			}//end if
		}//end foreach
		return false;
	}//end get_feed()

}//end class
