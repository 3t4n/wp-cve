<?php

namespace Vimeotheque\Amp;

// No direct include
use Vimeotheque\Helper;

if( ! defined( 'ABSPATH' ) ){
	die();
}

/**
 * Class Amp
 *
 * @package Vimeotheque\Amp
 * @ignore
 */
class Amp{
	/**
	 * Amp constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect',  [ $this, 'check_request' ] );
	}

	/**
	 * Check if request is AMP
	 */
	public function check_request(){
		if( !function_exists( 'amp_is_request' ) ){
			return;
		}

		if( amp_is_request() && is_singular() ){
			$video = Helper::get_video_post();
			if( !$video->is_video() ){
				return;
			}

			add_filter( 'vimeotheque\post_content_embed', '__return_false' );

			add_filter(
				'the_content',
				function( $content ){
					$video = Helper::get_video_post();
					$url = sprintf( 'https://vimeo.com/%s', $video->video_id );
					return $url . "\n\n" . $content;
				},
				-999999
			);
		}
	}
}