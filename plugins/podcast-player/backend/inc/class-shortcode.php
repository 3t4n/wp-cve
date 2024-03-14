<?php
/**
 * Shortcode API: Display Podcast from feed url class
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 */

namespace Podcast_Player\Backend\Inc;

use Podcast_Player\Frontend\Inc\Display;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Class used to display podcast episodes from a feed url.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/admin
 * @author     vedathemes <contact@vedathemes.com>
 */
class Shortcode extends Singleton {
	/**
	 * Podcast player shortcode function.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts User defined attributes in shortcode tag.
	 * @param str   $pp_content Shortcode text content.
	 */
	public function render( $atts, $pp_content = null ) {

		$defaults = $this->get_defaults();
		$atts     = shortcode_atts( $defaults, $atts, 'podcastplayer' );
		$img_url  = '';
		$image_id = '';
		if ( $atts['cover_image_url'] ) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $atts['cover_image_url'], $dir['baseurl'] . '/' ) ) {
				$image_id = attachment_url_to_postid( esc_url( $atts['cover_image_url'] ) );
			} else {
				$img_url = $atts['cover_image_url'];
			}
		}

		/**
		 * Podcast player display params from shortcode.
		 *
		 * @since 3.3.0
		 *
		 * @param array $script_data Podcast data to be sent to front-end script.
		 * @param array $args        Podcast display args.
		 */
		$display_args = apply_filters(
			'podcast_player_shcode_display',
			array(
				'url'               => $atts['feed_url'],
				'sortby'            => $atts['sortby'],
				'filterby'          => $atts['filterby'],
				'number'            => absint( $atts['number'] ),
				'menu'              => $atts['podcast_menu'],
				'main_menu_items'   => $atts['main_menu_items'],
				'description'       => $pp_content,
				'image'             => $image_id,
				'img_url'           => $img_url,
				'header-default'    => 'true' === $atts['header_default'] ? 1 : 0,
				'list-default'      => 'true' === $atts['list_default'] ? 1 : 0,
				'hide-header'       => 'true' === $atts['hide_header'] ? 1 : 0,
				'hide-title'        => 'true' === $atts['hide_title'] ? 1 : 0,
				'hide-cover-img'    => 'true' === $atts['hide_cover'] ? 1 : 0,
				'hide-description'  => 'true' === $atts['hide_description'] ? 1 : 0,
				'hide-subscribe'    => 'true' === $atts['hide_subscribe'] ? 1 : 0,
				'hide-search'       => 'true' === $atts['hide_search'] ? 1 : 0,
				'hide-author'       => 'true' === $atts['hide_author'] ? 1 : 0,
				'hide-content'      => 'true' === $atts['hide_content'] ? 1 : 0,
				'hide-loadmore'     => 'true' === $atts['hide_loadmore'] ? 1 : 0,
				'hide-download'     => 'true' === $atts['hide_download'] ? 1 : 0,
				'hide-social'       => 'true' === $atts['hide_social'] ? 1 : 0,
				'hide-featured'     => 'true' === $atts['hide_featured'] ? 1 : 0,
				'accent-color'      => $atts['accent_color'],
				'display-style'     => $atts['display_style'],
				'teaser-text'       => $atts['teaser_text'],
				'offset'            => absint( $atts['offset'] ),
				'excerpt-length'    => $atts['excerpt_length'],
				'excerpt-unit'      => $atts['excerpt_unit'],
				'from'              => 'shortcode',
				'apple-sub'         => $atts['apple_sub'],
				'google-sub'        => $atts['google_sub'],
				'spotify-sub'       => $atts['spotify_sub'],
				'breaker-sub'       => $atts['breaker_sub'],
				'castbox-sub'       => $atts['castbox_sub'],
				'castro-sub'        => $atts['castro_sub'],
				'iheart-sub'        => $atts['iheart_sub'],
				'amazon-sub'        => $atts['amazon_sub'],
				'overcast-sub'      => $atts['overcast_sub'],
				'pocketcasts-sub'   => $atts['pocketcasts_sub'],
				'podcastaddict-sub' => $atts['podcastaddict_sub'],
				'podchaser-sub'     => $atts['podchaser_sub'],
				'radiopublic-sub'   => $atts['radiopublic_sub'],
				'soundcloud-sub'    => $atts['soundcloud_sub'],
				'stitcher-sub'      => $atts['stitcher_sub'],
				'tunein-sub'        => $atts['tunein_sub'],
				'youtube-sub'       => $atts['youtube_sub'],
				'bullhorn-sub'      => $atts['bullhorn_sub'],
				'podbean-sub'       => $atts['podbean_sub'],
				'playerfm-sub'      => $atts['playerfm_sub'],
			),
			$atts
		);

		$display = Display::get_instance();
		return $display->init( $display_args, true );
	}

	/**
	 * Podcast player shortcode defaults.
	 *
	 * @since 3.3.0
	 */
	private function get_defaults() {
		return array(
			'feed_url'          => '',
			'sortby'            => 'sort_date_desc',
			'filterby'          => '',
			'number'            => 10,
			'offset'            => 0,
			'podcast_menu'      => '',
			'main_menu_items'   => 0,
			'cover_image_url'   => '',
			'teaser_text'       => '',
			'excerpt_length'    => 25,
			'excerpt_unit'      => '',
			'grid_columns'      => 3,
			'aspect_ratio'      => 'squr',
			'crop_method'       => 'centercrop',
			'header_default'    => '',
			'list_default'      => '',
			'hide_header'       => '',
			'hide_title'        => '',
			'hide_cover'        => '',
			'hide_description'  => '',
			'hide_subscribe'    => '',
			'hide_search'       => '',
			'hide_author'       => '',
			'hide_content'      => '',
			'hide_loadmore'     => '',
			'hide_download'     => '',
			'hide_social'       => '',
			'hide_featured'     => '',
			'accent_color'      => '',
			'display_style'     => '',
			'fetch_method'      => 'feed',
			'post_type'         => 'post',
			'taxonomy'          => '',
			'terms'             => '',
			'podtitle'          => '',
			'mediasrc'          => '',
			'episodetitle'      => '',
			'episodelink'       => '',
			'audio_msg'         => '',
			'play_freq'         => 0,
			'msg_start'         => 'start',
			'msg_time'          => '',
			'msg_text'          => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
			'font_family'       => '',
			'bgcolor'           => '',
			'txtcolor'          => '',
			'elist'             => '',
			'seasons'           => '',
			'episodes'          => '',
			'categories'        => '',
			'apple_sub'         => '',
			'google_sub'        => '',
			'spotify_sub'       => '',
			'breaker_sub'       => '',
			'castbox_sub'       => '',
			'castro_sub'        => '',
			'iheart_sub'        => '',
			'amazon_sub'        => '',
			'overcast_sub'      => '',
			'pocketcasts_sub'   => '',
			'podcastaddict_sub' => '',
			'podchaser_sub'     => '',
			'radiopublic_sub'   => '',
			'soundcloud_sub'    => '',
			'stitcher_sub'      => '',
			'tunein_sub'        => '',
			'youtube_sub'       => '',
			'bullhorn_sub'      => '',
			'podbean_sub'       => '',
			'playerfm_sub'      => '',
		);
	}
}
