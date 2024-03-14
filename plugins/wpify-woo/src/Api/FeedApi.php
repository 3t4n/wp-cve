<?php

namespace WpifyWoo\Api;

use WP_REST_Response;
use WP_REST_Server;
use WpifyWoo\Modules\XmlFeedHeureka\XmlFeedHeurekaModule;
use WpifyWoo\Plugin;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class FeedApi extends AbstractRest {

	/**
	 * ExampleApi constructor.
	 */
	public function __construct() {
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
			'feed/generate/(?P<id>[\w].+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'generate_feed' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'feed/chunk-generate/(?P<id>[\w].+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'chunk_generate_feed' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	public function get_module( $id ) {
		if ( 'heureka' === $id ) {
			/** @var XmlFeedHeurekaModule $module */
			$module = $this->plugin->get_module( XmlFeedHeurekaModule::class );
		} else {
			$module = apply_filters( 'wpify_woo_feeds_api_module', null, $id );
		}
		return $module;
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 * @throws \ComposePress\Core\Exception\Plugin
	 */
	public function generate_feed( $request ) {
		$id    = $request->get_param( 'id' );
		$module = $this->get_module($id);
		if ( ! $module ) {
			return new \WP_Error( 'module-not-found', __( 'Module not found', 'wpify-woo' ) );
		}

		$module->get_feed()->delete_tmp_file();
		$module->get_feed()->generate_feed();

		return new WP_REST_Response( array( 'result' => 'done' ), 200 );
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 * @throws \ComposePress\Core\Exception\Plugin
	 */
	public function chunk_generate_feed( $request ) {
		$id    = $request->get_param( 'id' );
		$module = $this->get_module($id);
		if ( ! $module ) {
			return new \WP_Error( 'module-not-found', __( 'Module not found', 'wpify-woo' ) );
		}

		$feed   = $module->get_feed();
		$page   = $request->get_param( 'page' ) ?: 1;
		if ( (int) $page === 1 ) {
			$feed->delete_tmp_file();
		}
		$new_data = $feed->get_data_for_page( $page );
		if ( ! $new_data ) {
			// We are done, save the feed.
			$feed->save_feed( $feed->get_xml_from_array( $feed->get_tmp_data(), $feed->get_root_name() ) );

			return new WP_REST_Response( array( 'status' => 'done' ), 201 );
		}

		$feed->add_tmp_data( $new_data['data'] );
		$total = wp_count_posts( 'product' );

		return new WP_REST_Response( array(
			'total_count'     => (int) $total->publish,
			'processed_count' => $new_data['count'],
			'next_page'       => $page + 1,
			'status'          => 'pending',
		), 201 );
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
	 * @param mixed            $item WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}
}
