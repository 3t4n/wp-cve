<?php

namespace Vimeotheque\Rest_Api\Endpoints\Vimeo_Api;

use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Abstract;
use Vimeotheque\Rest_Api\Endpoints\Rest_Controller_Interface;
use Vimeotheque\Video_Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @ignore
 */
class Rest_Video_Controller extends Rest_Controller_Abstract implements Rest_Controller_Interface {

	/**
	 * Rest_Pictures_Controller constructor.
	 */
	public function __construct() {
		parent::set_namespace( 'vimeotheque/v1' );
		parent::set_rest_base( '/api-query/video/' );
		$this->register_routes();
	}

	/**
	 * @inheritDoc
	 */
	public function register_routes() {
		register_rest_route(
			parent::get_namespace(),
			parent::get_rest_base(),
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_response' ],
				'permission_callback' => function(){
					return current_user_can( 'edit_posts' );
				},
				'args' => [
					'id' => [
						'validate_callback' => function( $param ){
							return true;
						}
					]
				]
			]
		);
	}

	/**
	 * Returns the response for Rest API
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return array
	 */
	public function get_response( \WP_REST_Request $request ) {
		if( empty( $request->get_param( 'id' ) ) ){
			return new \WP_Error(
				'vimeotheque_rest_api_no_item_id',
				__( 'Video ID not found.', 'codeflavors-vimeo-video-post-lite' )
			);
		}

		$id = $this->get_video_id( $request->get_param( 'id' ) );

		if( is_wp_error( $id ) ){
			return $id;
		}

		$query = new Video_Import( 'video', $id );

		$error = $query->get_errors();
		if( $error ){
			// set response status code to 404 (Not found)
			$error->error_data[ $error->get_error_code() ]['status'] = '404';
			return $error;
		}

		return $query->get_feed();
	}

	private function get_video_id( $string ){
		if( is_numeric( $string ) ){
			return $string;
		}

		$id = $this->search_video_id( $string );

		if( !$id ){
			return new \WP_Error(
				'vimeotheque-unknown-link',
				__( 'Something is wrong with the URL you entered. Please try again.', 'codeflavors-vimeo-video-post-lite' ),
				['status' => '404']
			);
		}

		return $id;
	}

	/**
	 * Determine video ID and provider based on given URL.
	 *
	 * @param $url - video URL
	 * @return false/array - false if video URL couldn't be understood, array in case video was detected
	 **/
	private function search_video_id( $url ) {
		// providers
		$patterns = array(
			'#https?://(.+\.)?vimeo\.com/groups/.*/videos/([0-9]+)#i',
			'#https?://(.+\.)?vimeo\.com/channels/.*/([0-9]+)#i',
			'#https?://(.+\.)?vimeo\.com/([0-9]+)#i',
		);

		foreach ( $patterns as $matchmask ) {
			if ( preg_match( $matchmask, $url, $matches ) ) {
				return end( $matches );
			}
		}
	}
}