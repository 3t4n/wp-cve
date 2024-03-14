<?php
/**
 * Adds Open Graph and Twitter meta tags.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Adds Open Graph and Twitter meta tags.
 */
class Nelio_Content_Meta_Tags {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'wp_head', array( $this, 'maybe_add_meta_tags' ) );
	}//end init()

	public function maybe_add_meta_tags() {
		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'are_meta_tags_active' ) ) {
			return;
		}//end if

		// See https://developers.facebook.com/docs/sharing/webmasters#markup link.
		$image = $this->get_og_image();

		$open_graph = array(
			'og:locale'       => get_locale(),
			'og:type'         => $this->get_og_type(),
			'og:title'        => $this->get_og_title(),
			'og:description'  => $this->get_og_desc(),
			'og:url'          => $this->get_og_url(),
			'og:site_name'    => get_bloginfo( 'name' ),
			'og:image'        => $image ? $image['url'] : false,
			'og:image:width'  => $image ? $image['width'] : false,
			'og:image:height' => $image ? $image['height'] : false,
		);

		// See https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/markup link.
		$twitter = array(
			'twitter:card'    => 'summary_large_image',
			'twitter:creator' => false,
			'twitter:site'    => false,
		);

		$metas = array_merge( $open_graph, $twitter );
		foreach ( $metas as $key => $value ) {
			/**
			 * Filters the given meta tag. If `false`, the tag won't be printed.
			 *
			 * @param any    $value value of the meta tag.
			 * @param string $key   the tag we're filtering.
			 *
			 * @since 2.1.2
			 */
			$metas[ $key ] = apply_filters( 'nelio_content_meta_tag', $value, $key );

			/**
			 * Filters the given meta tag. If `false`, the tag won't be printed.
			 *
			 * @param any $value value of the meta tag.
			 *
			 * @since 2.1.2
			 */
			$metas[ $key ] = apply_filters( "nelio_content_{$key}_meta_tag", $value );

			$metas[ $key ] = wp_strip_all_tags( $metas[ $key ] );
		}//end foreach

		$metas = array_filter(
			$metas,
			function( $meta ) {
				return false !== $meta;
			}
		);

		echo "\n\n\t<!-- Nelio Content -->";
		foreach ( $metas as $key => $value ) {
			$attr = 0 === strpos( $key, 'twitter' ) ? 'name' : 'property';
			printf(
				"\n\t<meta %s=\"%s\" content=\"%s\" />",
				esc_attr( $attr ),
				esc_attr( $key ),
				esc_attr( $value )
			);
		}//end foreach
		echo "\n\t<!-- /Nelio Content -->\n\n";
	}//end maybe_add_meta_tags()

	private function get_og_image() {
		if ( ! is_singular() ) {
			return false;
		}//end if

		$thumb_id = get_post_thumbnail_id();
		$thumb    = wp_get_attachment_image_src( $thumb_id, 'full' );
		if ( empty( $thumb ) ) {
			return false;
		}//end if

		return array(
			'url'    => $thumb[0],
			'width'  => $thumb[1],
			'height' => $thumb[2],
		);
	}//end get_og_image()

	private function get_og_type() {
		if ( is_front_page() ) {
			return 'website';
		} elseif ( is_author() ) {
			return 'profile';
		} else {
			return 'article';
		}//end if
	}//end get_og_type()

	private function get_og_title() {
		return get_the_title();
	}//end get_og_title()

	private function get_og_desc() {
		$more = function() {
			return '…';
		};
		add_filter( 'excerpt_more', $more );
		$excerpt = is_singular() ? get_the_excerpt() : '';
		remove_filter( 'excerpt_more', $more );
		return $excerpt;
	}//end get_og_desc()

	private function get_og_url() {
		global $wp;
		return home_url( add_query_arg( array(), $wp->request ) );
	}//end get_og_url()

}//end class
