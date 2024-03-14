<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Player\Player;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video_Position
 * @package Vimeotheque\Shortcode
 * @ignore
 */
class Video_Position extends Shortcode_Abstract implements Shortcode_Interface {

	public function __construct( $name ) {
		parent::__construct( $name );

		add_filter( 'the_content', [$this, 'search_shortcode'], -99999999 );

	}

	public function search_shortcode( $content ){
		$names =  !is_array( parent::get_shortcode_name() ) ? [ parent::get_shortcode_name() ] : parent::get_shortcode_name();
		foreach( $names as $tag ){
			if( has_shortcode( $content, $tag ) ){
				Plugin::instance()->get_front_end()->prevent_post_autoembed();
				break;
			}
		}
		return $content;
	}

	/**
	 * @param $atts
	 * @param $content
	 *
	 * @return string|void
	 */
	public function get_output( $atts, $content ){
		if( !is_singular() ){
			return;
		}

		parent::set_atts( $atts );
		parent::set_content( $content );

		global $post;
		$_post = Helper::get_video_post( $post );

		if( !$_post->is_video() ){
			return;
		}

		$player = new Player( $_post );
		return $player->get_output( false );
	}

}