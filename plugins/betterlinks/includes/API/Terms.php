<?php

namespace BetterLinks\API;

use BetterLinks\Traits\ArgumentSchema;
use BetterLinks\Helper;

class Terms extends Controller
{

	use \BetterLinks\Traits\Terms;
	use ArgumentSchema;

	/**
	 * Initialize hooks and option name
	 */
	public function __construct()
	{
		add_action('rest_api_init', array($this, 'register_routes'));
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes()
	{
		$endpoint = '/terms/';
		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array($this, 'get_items'),
					'permission_callback' => array($this, 'get_items_permissions_check'),
					'args'                => $this->get_terms_schema(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			$endpoint . 'tags',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array($this, 'get_tags'),
					'permission_callback' => array($this, 'get_items_permissions_check'),
					'args'                => $this->get_terms_schema(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array($this, 'create_item'),
					'permission_callback' => array($this, 'permissions_check'),
					'args'                => $this->get_terms_schema(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array($this, 'update_item'),
					'permission_callback' => array($this, 'permissions_check'),
					'args'                => $this->get_terms_schema(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$endpoint,
			array(
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array($this, 'delete_item'),
					'permission_callback' => array($this, 'permissions_check'),
					'args'                => $this->get_terms_schema(),
				),
			)
		);
	}

	public function get_tags()
	{
		$results = $this->get_all_tags();

		$analytic = $this->tags_analytic();

		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => array(
					'results' => $results,
					'analytic' => $analytic
				),
			),
			200
		);
	}
	/**
	 * Get betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function get_items($request)
	{
		$query_params = $request->get_query_params();
		$results      = $this->get_all_terms_data($query_params);

		if (count($results) <= 0) {
			$_term = array(
				'term_name' => 'Uncategorized',
				'term_slug' => 'uncategorized',
				'term_type' => 'category',
			);
			$id    = Helper::insert_term($_term);
			if ($id) {
				$_term['ID']         = $id;
				$_term['term_order'] = 0;
			}
			$results = array($_term);
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => $results,
			),
			200
		);
	}

	/**
	 * Create OR Update betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_item($request)
	{
		delete_transient(BETTERLINKS_CACHE_LINKS_NAME);
		$request = $request->get_params();

		$args    = array(
			'ID'        => (isset($request['params']['ID']) ? absint(sanitize_text_field($request['params']['ID'])) : 0),
			'term_name' => (isset($request['params']['term_name']) ? sanitize_text_field($request['params']['term_name']) : ''),
			'term_slug' => (isset($request['params']['term_slug']) ? sanitize_text_field($request['params']['term_slug']) : ''),
			'term_type' => (isset($request['params']['term_type']) ? sanitize_text_field($request['params']['term_type']) : ''),
		);
		$is_update = \BetterLinks\Helper::is_term_exists($args['ID'], 'tags');

		if (empty($args['term_slug'])) return;

		if ($is_update) {
			$results = $this->update_tag($args);
			return new \WP_REST_Response(
				array(
					'success' => true,
					'update' => true,
					'data'    => $results,
				),
				200
			);
		}
		$results = $this->create_term($args);
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => $results,
			),
			200
		);
	}

	/**
	 * Create OR Update betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_item($request)
	{
		delete_transient(BETTERLINKS_CACHE_LINKS_NAME);
		$request = $request->get_params();
		$args    = array(
			'cat_id'   => (isset($request['params']['ID']) ? absint(sanitize_text_field($request['params']['ID'])) : 0),
			'cat_name' => (isset($request['params']['term_name']) ? sanitize_text_field($request['params']['term_name']) : ''),
			'cat_slug' => (isset($request['params']['term_slug']) ? sanitize_text_field($request['params']['term_slug']) : ''),
		);
		$this->update_term($args);
		return new \WP_REST_Response(
			array(
				'success' => is_bool($request['params']['ID']),
				'data'    => $request['params'],
			),
			200
		);
	}

	/**
	 * Delete betterlinks
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function delete_item($request)
	{
		delete_transient(BETTERLINKS_CACHE_LINKS_NAME);
		$request = $request->get_params();

		$this->delete_term($request);
		return new \WP_REST_Response(
			array(
				'success' => true,
				'data'    => array(
					'cat_id' => isset($request['cat_id']) ? $request['cat_id'] : $request['tag_id'],
				),
			),
			200
		);
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check($request)
	{
		return apply_filters('betterlinks/api/terms_get_items_permissions_check', current_user_can('manage_options'));
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function permissions_check($request)
	{
		return current_user_can('manage_options');
	}
}
