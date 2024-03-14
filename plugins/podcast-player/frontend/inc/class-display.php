<?php
/**
 * Display a podcast instance.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player\Frontend\Inc;

use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Display a podcast instance.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Display extends Singleton {
	/**
	 * Is pp pro version available.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var int
	 */
	private $is_pro;

	/**
	 * Display a podcast instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args    Podcast display args.
	 * @param bool  $return  Display or return.
	 */
	public function init( $args, $return = true ) {
		/**
		 * Is pp pro version available.
		 *
		 * @since 3.3.0
		 *
		 * @param bool $is_pro Pro version status.
		 */
		$this->is_pro = apply_filters( 'podcast_player_is_premium', false );
		$defaults = $this->get_defaults();
		$args     = wp_parse_args( $args, $defaults );
		$args     = $this->fetch_custom_fields( $args );
		$args     = apply_filters( 'podcast_player_display_args', $args );
		$podcast  = $this->get_fetch_instance( $args['fetch-method'] );

		if ( is_wp_error( $podcast ) ) {
			if ( $return ) {
				return $podcast->get_error_message();
			}
			echo $podcast->get_error_message(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		// Get podcast player markup for current instance.
		ob_start();
		$podcast->display_podcast( $args );
		$markup = ob_get_clean();

		if ( $return ) {
			return $markup;
		}
		echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get podcast player fetch instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $method Podcast fetch method.
	 */
	private function get_fetch_instance( $method ) {
		$class = $this->get_fetch_class( $method );

		// Return if fetch class is not defined.
		if ( false === $class ) {
			return new \WP_Error(
				'fetch-not-defined',
				esc_html__( 'Podcast fetch class not defined.', 'podcast-player' )
			);
		}

		// Return if defined class does not exist.
		if ( ! class_exists( $class, true ) ) {
			return new \WP_Error(
				'fetch-not-exist',
				esc_html__( 'Podcast fetch class does not exist.', 'podcast-player' )
			);
		}

		// Return instance of the fetch class.
		return $class::get_instance();
	}

	/**
	 * Get podcast player fetch class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $method Podcast fetch method.
	 */
	private function get_fetch_class( $method ) {

		/**
		 * Podcast player fetch class.
		 *
		 * @since 3.3.0
		 *
		 * @param array List of fetch method classes.
		 */
		$classes = apply_filters(
			'podcast_player_fetch_method_class',
			array(
				'feed' => 'Podcast_Player\Frontend\Inc\Feed',
			)
		);

		if ( $method && isset( $classes[ $method ] ) ) {
			return $classes[ $method ];
		}

		return false;
	}

	/**
	 * Podcast player shortcode defaults.
	 *
	 * @since 3.3.0
	 */
	private function get_defaults() {
		return array(
			'url'               => '',
			'sortby'            => 'sort_date_desc',
			'filterby'          => '',
			'number'            => 10,
			'menu'              => '',
			'main_menu_items'   => 0,
			'image'             => '',
			'description'       => '',
			'img_url'           => '',
			'teaser-text'       => '',
			'offset'            => 0,
			'excerpt-length'    => 18,
			'excerpt-unit'      => '',
			'aspect-ratio'      => 'squr',
			'crop-method'       => 'centercrop',
			'header-default'    => '',
			'list-default'      => '',
			'hide-header'       => '',
			'hide-title'        => '',
			'hide-cover-img'    => '',
			'hide-description'  => '',
			'hide-subscribe'    => '',
			'hide-search'       => '',
			'hide-author'       => '',
			'hide-content'      => '',
			'hide-loadmore'     => '',
			'hide-download'     => '',
			'hide-social'       => '',
			'hide-featured'     => '',
			'accent-color'      => '',
			'display-style'     => '',
			'grid-columns'      => 3,
			'fetch-method'      => 'feed',
			'post-type'         => 'post',
			'taxonomy'          => '',
			'terms'             => '',
			'podtitle'          => '',
			'audiosrc'          => '',
			'audiotitle'        => '',
			'audiolink'         => '',
			'ahide-download'    => '',
			'ahide-social'      => '',
			'audio-msg'         => '',
			'play-freq'         => 0,
			'msg-start'         => 'start',
			'msg-time'          => array( 0, 0 ),
			'msg-text'          => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
			'font-family'       => '',
			'bgcolor'           => '',
			'txtcolor'          => '',
			'seasons'           => '',
			'episodes'          => '',
			'apple-sub'         => '',
			'google-sub'        => '',
			'spotify-sub'       => '',
			'breaker-sub'       => '',
			'castbox-sub'       => '',
			'castro-sub'        => '',
			'iheart-sub'        => '',
			'amazon-sub'        => '',
			'overcast-sub'      => '',
			'pocketcasts-sub'   => '',
			'podcastaddict-sub' => '',
			'podchaser-sub'     => '',
			'radiopublic-sub'   => '',
			'soundcloud-sub'    => '',
			'stitcher-sub'      => '',
			'tunein-sub'        => '',
			'youtube-sub'       => '',
			'bullhorn-sub'      => '',
			'podbean-sub'       => '',
			'playerfm-sub'      => '',
			'elist'             => array( '' ),
			'slist'             => array(),
			'catlist'           => array(),
			'edisplay'          => '',
			'from'              => false,
			'classes'           => '',
			'fixed'             => '',
		);
	}

	/**
	 * Get specific values of podcast player display args from custom fields.
	 *
	 * @since 6.0.0
	 *
	 * @param string $args Podcast player display args.
	 */
	private function fetch_custom_fields( $args ) {

		$s_args      = array( 'url', 'filterby', 'number', 'audiosrc', 'seasons', 'episodes' );
		$custom_keys = get_post_custom_keys();

		// Return if custom keys are not available.
		if ( ! $custom_keys ) {
			return $args;
		}

		// Check and fetch custom field values (if any).
		foreach ( $s_args as $key ) {
			$val = $args[ $key ];
			if ( in_array( $val, $custom_keys, true ) ) {
				$mval         = get_post_custom_values( $val );
				$mval         = is_array( $mval ) ? $mval[0] : $mval;
				$args[ $key ] = $mval;
			}
		}

		return $args;
	}
}
