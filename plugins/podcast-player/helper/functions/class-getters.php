<?php
/**
 * Podcast player utility functions.
 *
 * @link       https://www.vedathemes.com
 * @since      3.3.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Functions;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Feed\Get_Feed_New;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Backend\Admin\Options;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;

/**
 * Podcast player utility functions.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Getters {

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function __construct() {}

	/**
	 * Add attributes strings to all HTML A elements in content.
	 *
	 * @since 3.3.0
	 *
	 * @param string $feed_url Podcast feed url.
	 * @param array  $mods     Feed episode filter args.
	 * @param array  $fields   Required episode field keys.
	 */
	public static function get_feed_data( $feed_url, $mods = array(), $fields = array() ) {
		$feed_url = self::get_valid_feed_url( $feed_url );
		if ( is_wp_error( $feed_url ) ) {
			return $feed_url;
		}

		$obj  = new Get_Feed_New( $feed_url, $mods, $fields );
		$data = $obj->init();
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		// Cron update only if auto import or cron update is enabled.
		list( $is_auto, $import ) = self::get_feed_import_settings( $feed_url );
		$cron_update = Get_Fn::get_Plugin_option('update_method');
		if ( $is_auto || $cron_update ) {
			Utility_Fn::schedule_next_auto_update( $feed_url );
		}
		return $data;
	}

	/**
	 * Check and Get valid podcast feed url.
	 *
	 * @since 1.0.0
	 *
	 * @param str $url Url to be checked or fetched.
	 * @return str
	 */
	public static function get_valid_feed_url( $url ) {

		// Check if a valid url has been provided.
		if ( Validation_Fn::is_valid_url( $url ) ) {
			return wp_strip_all_tags( $url );
		}

		// Check if url has been provided in as a custom field.
		$custom_keys = get_post_custom_keys();
		if ( $custom_keys && in_array( $url, $custom_keys, true ) ) {
			$murl = get_post_custom_values( $url );
			$murl = is_array( $murl ) ? $murl[0] : $murl;

			// Check if a valid url has been provided.
			if ( Validation_Fn::is_valid_url( $murl ) ) {
				return wp_strip_all_tags( $murl );
			}
		}

		$url = self::get_feed_url_from_index( $url );
		if ( $url ) {
			return wp_strip_all_tags( $url );
		}

		return new \WP_Error( 'invalid_url', esc_html__( 'Please provide a valid feed url.', 'podcast-player' ) );
	}

	/**
	 * Check and Get valid podcast episode media url.
	 *
	 * @since 4.0.0
	 *
	 * @param str $url Url to be checked or fetched.
	 * @return str
	 */
	public static function get_valid_media_url( $url ) {
		// Check if a valid url has been provided.
		if ( Validation_Fn::is_valid_url( $url ) ) {
			return wp_strip_all_tags( $url );
		}

		// Check if url has been provided in as a custom field.
		$custom_keys = get_post_custom_keys();
		if ( $custom_keys && in_array( $url, $custom_keys, true ) ) {
			$murl = get_post_custom_values( $url );
			$murl = is_array( $murl ) ? $murl[0] : $murl;

			// Check if a valid url has been provided.
			if ( Validation_Fn::is_valid_url( $murl ) ) {
				return wp_strip_all_tags( $murl );
			}
		}

		return false;
	}

	/**
	 * Get feed url from the feed index.
	 *
	 * @since 3.5.0
	 *
	 * @param string $key Feed unique key.
	 */
	public static function get_feed_url_from_index( $key ) {
		$feed_index = self::get_feed_index();
		if ( $feed_index && isset( $feed_index[ $key ] ) ) {
			$info = $feed_index[ $key ];
			if ( isset( $info['url'] ) && $info['url'] ) {
				return wp_strip_all_tags( $info['url'] );
			}
		}
		return false;
	}

	/**
	 * Check if url is video or audio media url.
	 *
	 * @since 3.3.0
	 *
	 * @param string $media Media url to be checked.
	 */
	public static function get_media_type( $media ) {
		$audio_ext  = wp_get_audio_extensions();
		$video_ext  = wp_get_video_extensions();
		$mime_types = wp_get_mime_types();
		$media_type = false;

		// Adding support for aac file extension.
		$audio_ext[] = 'aac';
		$media_url   = $media ? preg_replace( '/\?.*/', '', $media ) : false;
		if ( $media_url ) {
			$type = wp_check_filetype( $media_url, $mime_types );
			if ( in_array( strtolower( $type['ext'] ), $audio_ext, true ) ) {
				$media_type = 'audio';
			} elseif ( in_array( strtolower( $type['ext'] ), $video_ext, true ) ) {
				$media_type = 'video';
			}
		}
		return $media_type;
	}

	/**
	 * Get all available display styles.
	 *
	 * @return array
	 */
	public static function display_styles() {

		/**
		 * Get podcast player display styles.
		 *
		 * @since 3.3.0
		 *
		 * @param array $styles Array of styles available in podcast player.
		 */
		return apply_filters(
			'podcast_player_display_styles',
			array(
				array(
					'style_id' => 'modern',
					'label'    => esc_html__( 'Modern Player', 'podcast-player' ),
					'support'  => array( 'bgcolor' ),
				),
				array(
					'style_id' => '',
					'label'    => esc_html__( 'Default Player', 'podcast-player' ),
					'support'  => array( 'excerpt', 'bgcolor' ),
				),
				array(
					'style_id' => 'legacy',
					'label'    => esc_html__( 'Catalogue (Legacy) Player', 'podcast-player' ),
					'support'  => array( 'bgcolor' ),
				),
			)
		);
	}

	/**
	 * Get elements supported by selected style.
	 *
	 * @return array
	 */
	public static function get_styles() {
		return array_column( self::display_styles(), 'label', 'style_id' );
	}

	/**
	 * Get elements supported by selected style.
	 *
	 * @return array
	 */
	public static function get_style_supported() {
		return array_column( self::display_styles(), 'support', 'style_id' );
	}

	/**
	 * Get plugin options.
	 *
	 * @since 3.3.0
	 *
	 * @param string $key Get option value for an option key.
	 */
	public static function get_plugin_option( $key ) {
		$all_options = get_option( 'pp-common-options' );
		$params      = self::get_plugin_option_fields( $key );

		// Return false if plugin option do not exists.
		if ( ! $params ) {
			return false;
		}

		// Return default value if options are not yet saved.
		if ( false === $all_options ) {
			return $params['default'];
		}

		// Return saved or default plugin option.
		return isset( $all_options[ $key ] ) ? $all_options[ $key ] : $params['default'];
	}

	/**
	 * Get plugin's options fields array.
	 *
	 * @since 3.5.0
	 *
	 * @param string $key Plugin option key.
	 */
	public static function get_plugin_option_fields( $key ) {
		$options = Options::get_instance();
		$fields  = $options->get_setting_fields();
		if ( isset( $fields[ $key ] ) ) {
			return $fields[ $key ];
		}
		return false;
	}

	/**
	 * Get podcast feed index.
	 *
	 * @return array
	 */
	public static function get_feed_index() {
		$all_feeds = get_option( 'pp_feed_index' );

		// Check and update depricated feed index.
		if ( $all_feeds && is_array( $all_feeds ) ) {
			foreach ( $all_feeds as $key => $args ) {
				if ( ! ( is_array( $args ) && isset( $args['url'] ) && $args['url'] ) ) {
					$all_feeds = Utility_Fn::refresh_index_new();
					break;
				}
			}
		}

		return $all_feeds;
	}

	/**
	 * Get image src and srcset.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Image attachment ID.
	 * @param str $size Required Image size.
	 * @return array
	 */
	public static function get_image_src_set( $id, $size ) {
		$image  = wp_get_attachment_image_src( $id, $size );
		$src    = '';
		$srcset = '';
		$ratio  = 1;
		if ( $image ) {
			list( $url, $width, $height ) = $image;
			// Get image src.
			$src = $url;

			// Get Image ratio.
			if ( $width && $height ) {
				$ratio = $height / $width;
			}

			// Get image srcset.
			$image_meta = wp_get_attachment_metadata( $id );
			if ( is_array( $image_meta ) ) {
				$size_array = array( absint( $width ), absint( $height ) );
				$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $id );
				$srcset     = $srcset ? $srcset : '';
			}
		}
		return array(
			'src'    => $src,
			'srcset' => $srcset,
			'ratio'  => $ratio,
		);
	}

	/**
	 * Get unique key of the given url.
	 *
	 * @since 3.3.0
	 *
	 * @param string $url Url for which unique key to be generated.
	 */
	public static function get_url_key( $url ) {
		$url = wp_strip_all_tags( $url );
		if ( ! Validation_Fn::is_valid_url( $url ) ) {
			return '';
		}
		return md5( $url );
	}

	/**
	 * Get podcast service from the link.
	 *
	 * @param string $link Podcast Subcription Link.
	 *
	 * @since 5.6.0
	 */
	public static function get_podcast_service( $link ) {

		/**
		 * Filter subscription links markup.
		 *
		 * @since 5.4.0
		 *
		 * @param array $sub_links_markup Array of subscription links markup.
		 */
		$sub_icons = apply_filters(
			'pp_subscription_links_markup',
			array(
				'podcasts.apple.com'  => 'apple',
				'deezer.com'          => 'deezer',
				'breaker.audio'       => 'breaker',
				'castbox.fm'          => 'castbox',
				'castro.fm'           => 'castro',
				'podcasts.google.com' => 'google',
				'iheart.com'          => 'iheart',
				'overcast.fm'         => 'overcast',
				'pocketcasts.com'     => 'pocketcasts',
				'pca.st'              => 'pocketcasts',
				'podcastaddict.com'   => 'podcastaddict',
				'podchaser.com'       => 'podchaser',
				'radiopublic.com'     => 'radiopublic',
				'soundcloud.com'      => 'soundcloud',
				'spotify.com'         => 'spotify',
				'stitcher.com'        => 'stitcher',
				'tunein.com'          => 'tunein',
				'youtube.com'         => 'youtube',
				'bullhorn.fm'         => 'bullhorn',
				'podbean.com'         => 'podbean',
				'player.fm'           => 'playerfm',
				'music.amazon'        => 'amazon',
			)
		);

		$service = false;
		foreach ( $sub_icons as $attr => $value ) {
			if ( false !== strpos( $link, $attr ) ) {
				$service = $value;
				break;
			}
		}

		return $service;
	}

	/**
	 * Get podcast service list.
	 *
	 * @since 5.6.0
	 */
	public static function get_services_list() {

		/**
		 * Filter podcast subscription services.
		 *
		 * @since 5.4.0
		 *
		 * @param array $services Array of supported subscription services.
		 */
		return apply_filters(
			'pp_subscription_services',
			array(
				'apple'         => esc_html__( 'Apple', 'podcast-player' ),
				'google'        => esc_html__( 'Google', 'podcast-player' ),
				'spotify'       => esc_html__( 'Spotify', 'podcast-player' ),
				'amazon'        => esc_html__( 'Amazon Music', 'podcast-player' ),
				'breaker'       => esc_html__( 'Breaker', 'podcast-player' ),
				'castbox'       => esc_html__( 'Castbox', 'podcast-player' ),
				'castro'        => esc_html__( 'Castro', 'podcast-player' ),
				'iheart'        => esc_html__( 'iHeart Radio', 'podcast-player' ),
				'overcast'      => esc_html__( 'Overcast', 'podcast-player' ),
				'pocketcasts'   => esc_html__( 'Pocket Casts', 'podcast-player' ),
				'podcastaddict' => esc_html__( 'Podcast Addict', 'podcast-player' ),
				'podchaser'     => esc_html__( 'Podchaser', 'podcast-player' ),
				'radiopublic'   => esc_html__( 'Radio Public', 'podcast-player' ),
				'soundcloud'    => esc_html__( 'Soundcloud', 'podcast-player' ),
				'stitcher'      => esc_html__( 'Stitcher', 'podcast-player' ),
				'tunein'        => esc_html__( 'Tune In', 'podcast-player' ),
				'youtube'       => esc_html__( 'YouTube', 'podcast-player' ),
				'bullhorn'      => esc_html__( 'BullHorn', 'podcast-player' ),
				'podbean'       => esc_html__( 'Podbean', 'podcast-player' ),
				'playerfm'      => esc_html__( 'PlayerFM', 'podcast-player' ),
			)
		);
	}

	/**
	 * Get podcast import information (if any).
	 *
	 * @since 6.5.0
	 *
	 * @param string $feed_key Podcast Feed Key.
	 */
	public static function get_feed_import_settings( $feed_key ) {
		if ( Validation_Fn::is_valid_url( $feed_key ) ) {
			$feed_key = md5( $feed_key );
		}
		$podcasts = self::get_feed_index();
		$is_auto  = false;
		$import   = false;
		if ( $podcasts && is_array( $podcasts ) && isset( $podcasts[ $feed_key ] ) ) {
			$pod_data = $podcasts[ $feed_key ];
			if ( $pod_data && is_array( $pod_data ) && isset( $pod_data['import'] ) ) {
				$import  = $pod_data['import'];
				$is_auto = isset( $import['is_auto'] ) ? $import['is_auto'] : false;
			}
		}
		$is_auto = apply_filters( 'podcast_player_auto_import', $is_auto, $feed_key );
		return array( $is_auto, $import );
	}
}
