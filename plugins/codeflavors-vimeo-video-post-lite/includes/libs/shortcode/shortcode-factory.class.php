<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Shortcode_Factory
 * @package Vimeotheque
 * @ignore
 */
class Shortcode_Factory {
	/**
	 * @var Shortcode_Abstract[]
	 */
	private $shortcodes = [];

	/**
	 * Shortcode_Factory constructor.
	 *
	 */
	public function __construct() {
		$this->register_shortcode_objects();
	}

	private function register_shortcode_objects(){
		$this->shortcodes = [
			'vimeotheque_video_position' => new Video_Position( [ 'cvm_video_embed', 'vimeotheque_video_position' ] ),
			'vimeotheque_video' => new Video( [ 'cvm_video', 'vimeotheque_video' ] ),
			'vimeotheque_playlist' => new Playlist( [ 'cvm_playlist', 'vimeotheque_playlist' ] )
		];
	}

	/**
	 * @return Shortcode_Abstract[]
	 */
	public function get_shortcodes(){
		return $this->shortcodes;
	}

	/**
	 * @param $shortcode
	 *
	 * @return Shortcode_Abstract|\WP_Error
	 */
	public function get_shortcode( $shortcode ){
		if( isset( $this->shortcodes[ $shortcode ] ) ){
			return $this->shortcodes[ $shortcode ];
		}

		return new \WP_Error(
			'vimeotheque-shortcode-not-registered',
			sprintf(
				__( 'Shortcode %s is not registered.', 'codeflavors-vimeo-video-post-lite' ),
				$shortcode
			)
		);
	}
}