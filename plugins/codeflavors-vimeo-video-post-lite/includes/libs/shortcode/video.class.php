<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Player\Player;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Shortcode
 * @ignore
 */
class Video extends Shortcode_Abstract implements Shortcode_Interface {
	/**
	 * @var Video_Post|null
	 */
	private $post = null;

	/**
	 * @param $atts
	 * @param $content
	 *
	 * @return bool|string|void
	 */
	public function get_output( $atts, $content ) {
		parent::set_atts( $atts );
		parent::set_content( $content );

		if( parent::get_attr( 'id' ) ){
			$this->post = Helper::get_video_post( parent::get_attr('id') );
		}

		if( !$this->post || !$this->post->is_video() ){
			return;
		}

		$vars = shortcode_atts( $this->post->get_embed_options(), parent::get_atts() );
		$player = new Player( $this->post, $vars );

		Helper::enqueue_player();

		return $player->get_output( false );
	}
}