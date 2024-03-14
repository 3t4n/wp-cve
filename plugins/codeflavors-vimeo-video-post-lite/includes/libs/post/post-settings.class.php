<?php

namespace Vimeotheque\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Post_Settings
 * @package Vimeotheque
 * @ignore
 */
class Post_Settings{
	/**
	 * Video ID meta name
	 *
	 * @var string
	 */
	private $meta_video_id = '__cvm_video_id';
	/**
	 * Video URL meta name
	 *
	 * @var string
	 */
	private $meta_video_url = '__cvm_video_url';
	/**
	 * Video data meta name
	 *
	 * @var string
	 */
	private $meta_video_data = '__cvm_video_data';
	/**
	 * Embed options meta name
	 *
	 * @var string
	 */
	private $meta_embed_settings = '__cvm_playback_settings';

	/**
	 * @var Post_Type
	 */
	private $post_type;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * Post_Settings constructor.
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ) {
		$this->post_type = $post_type;
		$this->options = $post_type->get_plugin()->get_options();
	}

	/**
	 * Returns if condition for importing images is on post display
	 * or post create.
	 *
	 * @return bool
	 */
	public function image_import(){

		return isset( $this->options['featured_image'] ) && $this->options['featured_image'];

	}

	/**
	 * Get import post status from plugin options
	 *
	 * @param bool $status
	 *
	 * @return bool|string
	 */
	public function post_status( $status = false ){
		if( !$status ){
			$status = $this->options['import_status'];
		}

		$status	= in_array( $status, [ 'publish', 'draft', 'pending' ] ) ? $status : 'draft';

		return $status;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_id(){
		return $this->meta_video_id;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_url(){
		return $this->meta_video_url;
	}

	/**
	 * @return string
	 */
	public function get_meta_video_data(){
		return $this->meta_video_data;
	}

	/**
	 * @return string
	 */
	public function get_meta_embed_settings(){
		return $this->meta_embed_settings;
	}
}