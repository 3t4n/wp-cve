<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Rest_Api\Endpoints\Wp;

use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Abstract;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 *
 * @ignore
 */
class Rest_Posts_Controller extends Rest_Controller_Abstract implements Rest_Controller_Interface {

	public function __construct() {
		parent::set_namespace( 'vimeotheque/v1' );
		parent::set_rest_base( 'get_posts' );
		$this->register_routes();
	}

	public function register_routes() {
		register_rest_route(
			parent::get_namespace(),
			parent::get_rest_base(),
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_response' ],
				'permission_callback' => function(){
					//return current_user_can( 'edit_posts' );
					return true;
				},
				'args' => [
					'post_type' => [
						'validate_callback' => function( $param ){
							return post_type_exists( $param );
						}
					],
					'vimeothequeMetaKey' => [
						'validate_callback' => function( $param ){
							return $param == 'true';
						}
					]
				]

			]
		);
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function get_response( \WP_REST_Request $request ) {
		$rest_controller = new \WP_REST_Posts_Controller( $request->get_param('post_type') );
		$request->set_param( 'vimeothequeMetaKey', 'true' );
		$_response = $rest_controller->get_items( $request );

		if( is_wp_error( $_response ) ){
			return $_response;
		}

		$posts = $_response->get_data();

		$result = [];
		foreach( $posts as $post ){
			$result[] = [
				'id' => $post['id'],
				'title' => $post['title'],
				'date' => $post['date'],
				'status' => $post['status'],
				'type' => $post['type'],
				'vimeo_video' => [
					'thumbnail' => $post['vimeo_video']['thumbnails'][2],
					'_duration' => $post['vimeo_video']['_duration']
				]
			];
		}

		$_response->set_data( $result );

		return $_response;
	}
}