<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Player;

use Vimeotheque\Helper;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Player {
	/**
	 * @var Video_Post
	 */
	private $post;

	/**
	 * Initial options that will override any options retrieved from post
	 * options
	 *
	 * @var array
	 */
	private $manual_options;

	/**
	 * @var mixed|void
	 */
	private $options;

	/**
	 * Player constructor.
	 *
	 * @param Video_Post $post
	 * @param array $options
	 */
	public function __construct( Video_Post $post, $options = [] ) {
		$this->post           = $post;
		$this->manual_options = $options;
		$this->set_post_embed_options();
	}

	/**
	 * Embed output
	 *
	 * @param bool $echo
	 *
	 * @param bool $width
	 *
	 * @return string|void
	 */
	public function get_output( $echo = true, $width = false ){
		if( !$this->post->is_video() ){
			return;
		}

		$_width = $width ? absint( $width ) : $this->get_embed_width();
		$height = !$width && $this->get_max_height() ? $this->get_max_height() : $this->get_embed_height( $_width );

		$css_class = $this->get_css_classes();

		$embed_content = sprintf(
			'<iframe src="%s" width="100%%" height="100%%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			$this->get_embed_url()
		);

		if( $this->options['lazy_load'] ){
			$attachment_id = get_post_thumbnail_id( $this->post->get_post()->ID );
			$img = wp_get_attachment_image_src( $attachment_id, 'full' ) ?: end( $this->post->thumbnails );
			if( $img ){
				$embed_content = sprintf(
					'<a href="#" class="vimeotheque-load-video" title="%s" data-url="%s" data-post_id="%d"><img src="%s" class="video-thumbnail" />%s</a>',
					esc_attr( $this->post->get_post()->post_title ),
					$this->get_embed_url(),
                    $this->post->get_post()->ID,
					is_array( $img ) ? $img[0] : $img,
					sprintf(
						'<div class="icon" style="background-color:%s"></div>',
						esc_attr( $this->options['play_icon_color'] )
					)
				);
			}
		}

		$video_container = sprintf(
			'<div class="vimeotheque-player %s" %s style="width:%spx; height:%spx; max-width:100%%;">%s</div>',
			$css_class,
			$this->get_data_attributes(),
			$_width,
			$height,
			$embed_content
		);

		if( $echo ){
			echo $video_container;
		}

		return $video_container;
	}

	/**
	 * Get video embedding options
	 *
	 * @return void
	 */
	private function set_post_embed_options(){
		/**
		 * Allow embed settings filtering that can change the embedding options when the post is displayed.
		 *
		 * @param array $embed_settings     The post video embed settings.
		 * @param object $post              The current post being displayed.
		 * @param array $video              The video details as retrieved from Vimeo.
		 */
		$this->options = apply_filters(
			'vimeotheque\player\embed_options',
			wp_parse_args(
				$this->manual_options,
				$this->post->get_embed_options( true )
			),
			$this->post->get_post(),
			$this->post->get_video_data()
		);
	}

	/**
	 * @return mixed|void
	 */
	private function get_embed_width(){
		/**
		 * Filter that can be used to modify the width of the embed.
		 *
		 * @param int       $width  Width in pixels.
		 * @param array     $video  Array of video details.
		 * @param \WP_Post  $post   The WP_Post object that the video is attached to.
		 */
		$w = apply_filters(
			'vimeotheque\player\embed_width',
			$this->options['width'],
			$this->post->get_video_data(),
			$this->post->get_post()
		);

		$max_height = $this->get_max_height();

		if( $max_height ){
			$h = $this->get_embed_height( $w );
			if( $h > $max_height ){
				$w = Helper::calculate_player_width( $this->options['aspect_ratio'], $max_height, $this->options['size_ratio']);
			}
		}

		return $w;
	}

	/**
	 * Get the maximum height, if anu
	 *
	 * @return false|int
	 */
	private function get_max_height(){
		/**
		 * Filter that allows a maximum height to be set for the embeds.
		 *
		 * @param int $height   The maximum height that players must have.
		 */
		$max_height = absint( apply_filters( 'vimeotheque\player\max_height', $this->options['max_height'] ) );

		if( $max_height < 50 ){
			$max_height = false;
		}

		return $max_height;
	}

	/**
	 * Calculate height based on width
	 *
	 * @param $width
	 *
	 * @return false|float
	 */
	private function get_embed_height( $width ){
		return Helper::calculate_player_height(
			$this->options['aspect_ratio'],
			$width,
			$this->options['size_ratio']
		);
	}

	/**
	 * Returns additional CSS classes
	 *
	 * @return string
	 */
	private function get_css_classes(){

		$default = [
			$this->options['video_align']
		];

		if( $this->options['lazy_load'] ){
			$default[] = 'lazy-load';
		}

		$ratio = isset( $this->post->size['ratio'] ) ? (float) $this->post->size['ratio'] : 1;
		if( $ratio > 1 ){
			$default[] = 'landscape';
		}else{
			$default[] = 'portrait';
		}

		if( isset( $this->manual_options['class'] ) ){
			$default[] = esc_attr( $this->manual_options['class'] );
		}

		/**
		 * Generate additional CSS classes on Vimeotheque embed player container.
		 *
		 * @param array $classes    CSS classes to be added.
		 * @param \WP_Post $post    The post object reference.
		 */
		$classes = apply_filters(
			'vimeotheque\player\css_class',
			$default,
			$this->post->get_post()
		);

		return implode( ' ', (array) $classes );
	}

	/**
	 * Generates options
	 *
	 * @since 2.0.14    Modified method visibility from private to public
	 *
	 * @return string
	 */
	public function get_embed_url(){
		$options = [
			'title' => $this->options['title'],
			'byline' => $this->options['byline'],
			'portrait' => $this->options['portrait'],
			'color' => str_replace( '#', '', $this->options['color'] ),
			'dnt' => $this->options['dnt'],
			'background' => $this->options['background'],
			'transparent' => $this->options['transparent']
		];

		/**
		 * Background mode loads video on autoplay, muted and with loop; no need to set the options as they
		 * might interfere with the player's default functionality.
		 */
		if( !$this->options['background'] ){
			$options = array_merge(
				[
					'autoplay' => $this->options['autoplay'],
					'muted' => $this->options['muted'],
					'loop' => $this->options['loop']
				],
				$options
			);
		}

		/**
		 * Filter to allow extra parameters to be put on the embed URL in iframe
		 *
		 * @param array $options            The extra options
		 * @param \WP_Post|false|null $post The WordPress post object
		 * @param array $video_details      The video details array attached to the post
		 * @param array $manual_options     An array of manual options that might have been apssed to the player.
		 */
		$extra = apply_filters(
			'vimeotheque\player\embed-parameters',
			[],
			$this->post->get_post(),
			$this->post->get_video_data(),
			$this->manual_options
		);

		if( $extra && is_array( $extra ) ){
			$options = array_merge( $extra, $options );
		}

		$start_time = $this->get_start_time( $this->options['start_time'] );
		
		/**
		 * If player embed URL is available and third party plugins set the h parameter
		 * for the embed, remove it because it is already incorporated into the player_embed_url parameter.
		 */
		if( !empty( $this->post->player_embed_url ) && isset( $options['h'] ) ){
			unset( $options['h'] );
		}
		
		return
			add_query_arg(
				$options,
				$this->post->get_embed_url()
			) . ( $start_time ? '#t=' . $start_time : '' );
	}

	/**
	 * Given a number of seconds, returns a formatted MMmSSs string
	 *
	 * @param $seconds
	 *
	 * @return string
	 */
	private function get_start_time( $seconds ){
		if( $seconds < 1 ){
			return false;
		}

		$h = floor( $seconds / HOUR_IN_SECONDS );
		$m = floor( $seconds / MINUTE_IN_SECONDS );
		$s = $seconds % MINUTE_IN_SECONDS;

		$hh = $h > 0 ? $h . 'h' : '';
		$mm = $m > 0 ? $m . 'm' : '';
		$ss = $s > 0 ? $s . 's' : '';

		return sprintf( '%s%s%s', $hh, $mm, $ss );
	}

	/**
	 * @return string[]
	 */
	private function get_data_attributes(){
		$result = [];
		// loop attributes
		foreach( $this->options as $key=>$value ){
			$result[] = sprintf(
				'data-%s="%s"',
				$key,
				$value
			);
		}

		$result[] = sprintf( 'data-video_id="%s"', $this->post->video_id );

		return implode(' ', $result);
	}
}