<?php
/**
 * Ogp Plus
 *
 * @package    Ogp Plus
 * @subpackage OgpPlus Main Functions
/*
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$ogpplus = new OgpPlus();

/** ==================================================
 * Main Functions
 */
class OgpPlus {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'wp_head', array( $this, 'meta_ogp' ) );
	}

	/** ==================================================
	 * Output OGP
	 *
	 * @since 1.00
	 */
	public function meta_ogp() {

		if ( is_front_page() || is_home() || is_singular() || is_archive() ) {

			$ogpplus_settings = get_option(
				'ogpplus_settings',
				array(
					'excerpt' => 100,
					'df_img_id' => null,
					'tw_user_name' => null,
					'fb_app_id' => null,
				)
			);

			if ( ! empty( $ogpplus_settings['df_img_id'] ) ) {

				global $post;
				$ogp_title = null;
				$ogp_descr = null;
				$ogp_url = null;
				$ogp_img_url = null;
				if ( is_front_page() || is_home() ) {
					$ogp_title = get_bloginfo( 'name' );
					$ogp_descr = get_bloginfo( 'description' );
					$ogp_url = home_url();
				} else if ( is_archive() ) {
					$ogp_title = wp_strip_all_tags( get_the_archive_title() );
					$ogp_descr = wp_strip_all_tags( get_the_archive_description() );
					if ( empty( $ogp_descr ) ) {
						$ogp_descr = get_bloginfo( 'description' );
					}
					if ( is_ssl() ) {
						$http = 'https://';
					} else {
						$http = 'http://';
					}
					$host = null;
					if ( isset( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['HTTP_HOST'] ) ) {
						$host = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
					}
					$uri = null;
					if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
						$uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
					}
					$ogp_url = esc_url_raw( $http . $host . $uri );
				} else if ( is_singular() ) {
					setup_postdata( $post );
					$ogp_title = $post->post_title;
					if ( function_exists( 'mb_substr' ) ) {
						$ogp_descr = mb_substr( strip_shortcodes( wp_strip_all_tags( $post->post_content, true ) ), 0, $ogpplus_settings['excerpt'] ) . '...';
					} else {
						$ogp_descr = substr( strip_shortcodes( wp_strip_all_tags( $post->post_content, true ) ), 0, $ogpplus_settings['excerpt'] ) . '...';
					}
					if ( '...' === $ogp_descr ) {
						$ogp_descr = get_bloginfo( 'description' );
					}
					$ogp_url = get_permalink( $post->ID );
					wp_reset_postdata();
				}

				if ( is_front_page() || is_home() || is_archive() ) {
					$ogp_type = 'website';
				} else {
					$ogp_type = 'article';
				}

				if ( is_singular() && has_post_thumbnail() ) {
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
					$ogp_img_url = $thumb[0];
					$ogp_img_width = $thumb[1];
					$ogp_img_height = $thumb[2];
					$ogp_img_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
				} else {
					$thumb = wp_get_attachment_image_src( $ogpplus_settings['df_img_id'], 'medium' );
					$ogp_img_url = $thumb[0];
					$ogp_img_width = $thumb[1];
					$ogp_img_height = $thumb[2];
					$ogp_img_alt = get_post_meta( $ogpplus_settings['df_img_id'], '_wp_attachment_image_alt', true );
				}

				$insert = '<meta property="og:title" content="' . esc_attr( $ogp_title ) . '" />' . "\n";
				$insert .= '<meta property="og:description" content="' . esc_attr( $ogp_descr ) . '" />' . "\n";
				$insert .= '<meta property="og:type" content="' . $ogp_type . '" />' . "\n";
				$insert .= '<meta property="og:url" content="' . esc_url( $ogp_url ) . '" />' . "\n";
				if ( ! empty( $ogp_img_url ) ) {
					$insert .= '<meta property="og:image" content="' . esc_url( $ogp_img_url ) . '" />' . "\n";
					$insert .= '<meta property="og:image:width" content="' . esc_attr( $ogp_img_width ) . '" />' . "\n";
					$insert .= '<meta property="og:image:height" content="' . esc_attr( $ogp_img_height ) . '" />' . "\n";
					$insert .= '<meta property="og:image:alt" content="' . esc_attr( $ogp_img_alt ) . '" />' . "\n";
				}
				$insert .= '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
				if ( ! empty( $ogpplus_settings['tw_user_name'] ) ) {
					$insert .= '<meta name="twitter:card" content="summary" />' . "\n";
					$insert .= '<meta name="twitter:site" content="' . esc_attr( $ogpplus_settings['tw_user_name'] ) . '" />' . "\n";
				}
				if ( ! empty( $ogpplus_settings['fb_app_id'] ) ) {
					$insert .= '<meta property="fb:app_id" content="' . esc_attr( $ogpplus_settings['fb_app_id'] ) . '" />' . "\n";
				}

				$allowed_insert_html = array(
					'meta'  => array(
						'property'  => array(),
						'name'  => array(),
						'content' => array(),
					),
				);
				echo wp_kses( $insert, $allowed_insert_html );

			}
		}
	}
}
