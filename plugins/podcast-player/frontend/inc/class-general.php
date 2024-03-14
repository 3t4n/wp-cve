<?php
/**
 * Podcast player premium.
 *
 * @link       https://www.vedathemes.com
 * @since      4.5.0
 *
 * @package    Podcast_Player
 */

namespace Podcast_Player\Frontend\Inc;

use Podcast_Player\Helper\Functions\Markup as Markup_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Podcast player premium.
 *
 * @package    Podcast_Player
 * @author     vedathemes <contact@vedathemes.com>
 */
class General extends Singleton {
	/**
	 * Prevent exposing podcast feed data.
	 *
	 * @param array $data Podcast episodes data.
	 *
	 * @since 4.5.0
	 */
	public function data_protect( $data ) {
		return array( array(), 0 );
	}

	/**
	 * Prevent exposing episode audio URL.
	 *
	 * @param string $url Podcast episode audio URL.
	 *
	 * @since 4.5.0
	 */
	public function mask_audio_url( $url ) {
		return md5( esc_url( $url ) );
	}

	/**
	 * Create properly formatted subscribe menu.
	 *
	 * @param  string  $item_output The menu item output.
	 * @param  WP_Post $item        Menu item object.
	 * @param  int     $depth       Depth of the menu.
	 * @param  array   $args        wp_nav_menu() arguments.
	 * @return string  $item_output The menu item output with social icon.
	 *
	 * @since 4.5.0
	 */
	public function subscribe_menu( $item_output, $item, $depth, $args ) {

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

		// Change SVG icon inside social links menu if there is supported URL.
		if ( 'pod-menu' === $args->menu_class ) {
			$has_sub = false;
			foreach ( $sub_icons as $attr => $value ) {
				if ( false !== strpos( $item_output, $attr ) ) {
					$has_sub     = true;
					$item_output = str_replace( $args->link_before, '<span class="ppjs__offscreen">', $item_output );
					$item_output = str_replace( $args->link_after, '</span>' . $this->get_podcast_template( 'subscribe', $value ), $item_output );
					break;
				}
			}
		}
		return $item_output;
	}

	/**
	 * Get podcast player template parts.
	 *
	 * @since  5.4.0
	 *
	 * @param string $path Template relative path.
	 * @param string $name Template file name without .php suffix.
	 */
	public function get_podcast_template( $path, $name ) {
		$markup   = '';
		$template = Markup_Fn::locate_template( $path, $name );
		if ( $template ) {
			ob_start();
			require $template;
			$markup .= ob_get_clean();
		}

		$markup = Markup_Fn::remove_breaks( $markup );

		if ( $markup ) {
			$markup = sprintf( '<span class="subscribe-item %1$s-sub">%2$s</span>', $name, $markup );
		}

		return $markup;
	}
}
