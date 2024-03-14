<?php
/**
 * Block API: Display Podcast from feed url class
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
class Block extends Singleton {
	/**
	 * Register editor block for featured content.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		// Check if the register function exists.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'podcast-player/podcast-player',
			array(
				'render_callback' => array( $this, 'render_block' ),
				'attributes'      => apply_filters(
					'podcast_player_block_attr',
					array(
						'feedURL'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'sortBy'           => array(
							'type'    => 'string',
							'default' => 'sort_date_desc',
						),
						'filterBy'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'number'           => array(
							'type'    => 'number',
							'default' => 10,
						),
						'offset'           => array(
							'type'    => 'number',
							'default' => 0,
						),
						'teaserText'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'excerptLength'    => array(
							'type'    => 'number',
							'default' => 18,
						),
						'excerptUnit'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'gridColumns'      => array(
							'type'    => 'number',
							'default' => 3,
						),
						'podcastMenu'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'mainMenuItems'    => array(
							'type'    => 'number',
							'default' => 0,
						),
						'aspectRatio'      => array(
							'type'    => 'string',
							'default' => 'squr',
						),
						'cropMethod'       => array(
							'type'    => 'string',
							'default' => 'centercrop',
						),
						'coverImage'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'description'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'accentColor'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'displayStyle'     => array(
							'type'    => 'string',
							'default' => '',
						),
						'fetchMethod'      => array(
							'type'    => 'string',
							'default' => 'feed',
						),
						'postType'         => array(
							'type'    => 'string',
							'default' => 'post',
						),
						'taxonomy'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'terms'            => array(
							'type'    => 'array',
							'items'   => array(
								'type' => 'string',
							),
							'default' => array(),
						),
						'podtitle'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'audioSrc'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'audioTitle'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'audioLink'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'headerDefault'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'listDefault'      => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideHeader'       => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideTitle'        => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideCover'        => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideDesc'         => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideSubscribe'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideSearch'       => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideAuthor'       => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideContent'      => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideLoadmore'     => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideDownload'     => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'ahideDownload'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideSocial'       => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'hideFeatured'     => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'ahideSocial'      => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'audioMsg'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'playFreq'         => array(
							'type'    => 'number',
							'default' => 0,
						),
						'msgStart'         => array(
							'type'    => 'string',
							'default' => 'start',
						),
						'msgTime'          => array(
							'type'    => 'array',
							'items'   => array(
								'type' => 'number',
							),
							'default' => array( 0, 0, 0 ),
						),
						'msgText'          => array(
							'type'    => 'string',
							'default' => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
						),
						'fontFamily'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'bgColor'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'txtColor'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'seasons'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'episodes'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'appleSub'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'googleSub'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'spotifySub'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'breakerSub'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'castboxSub'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'castroSub'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'iheartSub'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'amazonSub'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'overcastSub'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'pocketcastsSub'   => array(
							'type'    => 'string',
							'default' => '',
						),
						'podcastaddictSub' => array(
							'type'    => 'string',
							'default' => '',
						),
						'podchaserSub'     => array(
							'type'    => 'string',
							'default' => '',
						),
						'radiopublicSub'   => array(
							'type'    => 'string',
							'default' => '',
						),
						'soundcloudSub'    => array(
							'type'    => 'string',
							'default' => '',
						),
						'stitcherSub'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'tuneinSub'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'youtubeSub'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'bullhornSub'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'podbeanSub'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'playerfmSub'      => array(
							'type'    => 'string',
							'default' => '',
						),
						'elist'            => array(
							'type'    => 'array',
							'items'   => array(
								'type' => 'string',
							),
							'default' => array( '' ),
						),
						'edisplay'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'slist'            => array(
							'type'    => 'array',
							'items'   => array(
								'type' => 'string',
							),
							'default' => array( '' ),
						),
						'catlist'          => array(
							'type'    => 'array',
							'items'   => array(
								'type' => 'string',
							),
							'default' => array( '' ),
						),
						'className'        => array(
							'type' => 'string',
						),
					)
				),
			)
		);
	}

	/**
	 * Render editor block for podcast player.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Display attributes.
	 */
	public function render_block( $atts ) {
		$img_url  = '';
		$image_id = '';
		if ( $atts['coverImage'] ) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $atts['coverImage'], $dir['baseurl'] . '/' ) ) {
				$image_id = attachment_url_to_postid( esc_url( $atts['coverImage'] ) );
			} else {
				$img_url = $atts['coverImage'];
			}
		}

		$display_args = apply_filters(
			'podcast_player_block_display',
			array(
				'url'               => $atts['feedURL'],
				'sortby'            => $atts['sortBy'],
				'filterby'          => $atts['filterBy'],
				'number'            => absint( $atts['number'] ),
				'menu'              => $atts['podcastMenu'],
				'main_menu_items'   => $atts['mainMenuItems'],
				'image'             => $image_id,
				'description'       => $atts['description'],
				'img_url'           => $img_url,
				'header-default'    => true === $atts['headerDefault'] ? 1 : 0,
				'list-default'      => true === $atts['listDefault'] ? 1 : 0,
				'hide-header'       => true === $atts['hideHeader'] ? 1 : 0,
				'hide-title'        => true === $atts['hideTitle'] ? 1 : 0,
				'hide-cover-img'    => true === $atts['hideCover'] ? 1 : 0,
				'hide-description'  => true === $atts['hideDesc'] ? 1 : 0,
				'hide-subscribe'    => true === $atts['hideSubscribe'] ? 1 : 0,
				'hide-search'       => true === $atts['hideSearch'] ? 1 : 0,
				'hide-author'       => true === $atts['hideAuthor'] ? 1 : 0,
				'hide-content'      => true === $atts['hideContent'] ? 1 : 0,
				'hide-loadmore'     => true === $atts['hideLoadmore'] ? 1 : 0,
				'hide-download'     => true === $atts['hideDownload'] ? 1 : 0,
				'hide-social'       => true === $atts['hideSocial'] ? 1 : 0,
				'hide-featured'     => true === $atts['hideFeatured'] ? 1 : 0,
				'accent-color'      => $atts['accentColor'],
				'display-style'     => $atts['displayStyle'],
				'apple-sub'         => $atts['appleSub'],
				'google-sub'        => $atts['googleSub'],
				'spotify-sub'       => $atts['spotifySub'],
				'breaker-sub'       => $atts['breakerSub'],
				'castbox-sub'       => $atts['castboxSub'],
				'castro-sub'        => $atts['castroSub'],
				'iheart-sub'        => $atts['iheartSub'],
				'amazon-sub'        => $atts['amazonSub'],
				'overcast-sub'      => $atts['overcastSub'],
				'pocketcasts-sub'   => $atts['pocketcastsSub'],
				'podcastaddict-sub' => $atts['podcastaddictSub'],
				'podchaser-sub'     => $atts['podchaserSub'],
				'radiopublic-sub'   => $atts['radiopublicSub'],
				'soundcloud-sub'    => $atts['soundcloudSub'],
				'stitcher-sub'      => $atts['stitcherSub'],
				'tunein-sub'        => $atts['tuneinSub'],
				'youtube-sub'       => $atts['youtubeSub'],
				'bullhorn-sub'      => $atts['bullhornSub'],
				'podbean-sub'       => $atts['podbeanSub'],
				'playerfm-sub'      => $atts['playerfmSub'],
				'teaser-text'       => $atts['teaserText'],
				'offset'            => $atts['offset'],
				'excerpt-length'    => $atts['excerptLength'],
				'excerpt-unit'      => $atts['excerptUnit'],
				'classes'           => isset( $atts['className'] ) ? $atts['className'] : '',
				'random'            => true,
				'from'              => 'block',
			),
			$atts
		);

		$display = Display::get_instance();
		return $display->init( $display_args, true );
	}
}
