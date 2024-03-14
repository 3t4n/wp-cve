<?php

namespace Vimeotheque\Rest_Api;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Rest_Api\Endpoints\Rest_Endpoint_Factory;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * REST API implementation
 * Add all meta fields to video post
 * @author CodeFlavors
 *
 */
class Rest_Api{
	/**
	 * @var Rest_Endpoint_Factory
	 */
	private $routes;

	/**
	 * Custom post type class reference
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Constructor
	 *
	 * @param Post_Type $cpt
	 */
	public function __construct( Post_Type $cpt ){
		// store custom post type reference
		$this->cpt = $cpt;
		// add init action
		add_action( 'rest_api_init', [ $this, 'api_init' ] );

		$this->routes = new Rest_Endpoint_Factory();
	}

	/**
	 * REST API init callback
	 */
	public function api_init(){
		$this->register_rest_field();
	}

	/**
	 * Register new Rest API fields
	 */
	private function register_rest_field(){

		$objects = array();
		$post_types = Plugin::instance()->get_registered_post_types()->get_post_types();
		foreach( $post_types as $post_type ){
			$objects[] = $post_type->get_post_type()->name;
		}

		register_rest_field(
			$objects,
			'vimeo_video',
			[
				'get_callback' => [ $this, 'register_field' ],
				//'update_callback' => NULL,
				//'schema' => array()
			]
		);
	}

	/**
	 * Post array returned by REST API
	 *
	 * @param array $object
	 *
	 * @return array|null
	 */
	public function register_field( $object ){
		$video = Helper::get_video_post( $object['id'] );
		$response = NULL;

		if( $video->is_video() ){
			$response = [
				'video_id'		=> $video->video_id,
				'uploader'		=> $video->uploader,
				'uploader_uri'	=> $video->uploader_uri,
				'published' 	=> $video->published,
				'_published'	=> $video->_published,
				'updated'		=> $video->updated,
				'title'			=> $video->title,
				'description' 	=> $video->description,
				'tags'			=> $video->tags,
				'duration'		=> $video->duration,
				'_duration'		=> $video->_duration,
				'thumbnails'	=> $video->thumbnails,
				'stats'			=> $video->stats,
				'privacy'		=> $video->privacy, // set by the plugin
				'view_privacy'	=> $video->view_privacy, // the original Vimeo privacy setting
				'embed_privacy' => $video->embed_privacy, // the original Vimeo privacy embed setting
				'size'			=> $video->size,
				// Vimeo on Demand
				'type' 	=> $video->type,
				'uri'	=> $video->uri,
				'link'	=> $video->link
			];
		}

		/**
		 * Run filter on returned result to allow third party to add additional fields.
		 *
		 * @param null|array $response  The returned fields that wil be added to Rest API.
		 * @param array $object         The queried object.
		 * @param Video_Post $video     The video post object.
		 */
		return apply_filters( 'vimeotheque\rest_api\fields', $response, $object, $video );
	}

	/**
	 * @return Rest_Endpoint_Factory
	 */
	public function get_routes() {
		return $this->routes;
	}
}