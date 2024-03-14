<?php
/**
 * Add security and SEO related attributes to external links.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Add security and SEO related attributes to external links.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Add_External_Link_Attr extends Singleton {

	/**
	 * Init method.
	 *
	 * @since  3.3.0
	 *
	 * @param string $content Content in which attr to be added.
	 */
	public function init( $content ) {

		// Replace all links in content with properly formatted links.
		$content = preg_replace_callback(
			'|<a (.+?)>|i',
			function( $matches ) {
				return call_user_func( array( $this, 'add_link_attr' ), $matches );
			},
			$content
		);

		return $content;
	}

	/**
	 * Add attributes to any given link tag.
	 *
	 * @since  3.3.0
	 *
	 * @param string $atag Link tag to be formatted.
	 */
	private function add_link_attr( $atag ) {
		// Builds an attribute list from A tag string.
		$text = $atag[1];
		$atts = wp_kses_hair( $atag[1], wp_allowed_protocols() );

		if ( ! empty( $atts['href'] ) ) {

			// Return AS IS if it is an internal link.
			$is_internal_link = Validation_Fn::is_internal_link( $atts['href']['value'] );
			if ( $is_internal_link ) {
				return "<a $text>";
			}
		}

		list( $attr, $rel ) = $this->remove_existing_tags( $atts );
		$text               = $this->get_attr_markup( $atts );
		return "<a $text rel=\"" . esc_attr( $rel ) . '" target="_blank">';
	}

	/**
	 * Remove rel and target from link attributes (if any).
	 *
	 * @since  3.3.0
	 *
	 * @param string $atts Link tag attributes.
	 */
	private function remove_existing_tags( $atts ) {
		$rel = 'noopener noreferrer nofollow';
		if ( ! empty( $atts['rel'] ) || ! empty( $atts['target'] ) ) {
			if ( ! empty( $atts['rel'] ) ) {
				$parts     = array_map( 'trim', explode( ' ', $atts['rel']['value'] ) );
				$rel_array = array_map( 'trim', explode( ' ', $rel ) );
				$parts     = array_unique( array_merge( $parts, $rel_array ) );
				$rel       = implode( ' ', $parts );
				unset( $atts['rel'] );
			}

			if ( ! empty( $atts['target'] ) ) {
				unset( $atts['target'] );
			}
		}
		return array( $atts, $rel );
	}

	/**
	 * Get link tag attributes markup.
	 *
	 * @since  3.3.0
	 *
	 * @param string $atts Link tag attributes.
	 */
	private function get_attr_markup( $atts ) {
		$html = '';
		foreach ( $atts as $name => $value ) {
			if ( isset( $value['vless'] ) && 'y' === $value['vless'] ) {
				$html .= $name . ' ';
			} else {
				$html .= "{$name}=\"" . esc_attr( $value['value'] ) . '" ';
			}
		}
		return trim( $html );
	}
}
