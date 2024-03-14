<?php

/**
 * class AvatarHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.7.0
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class AvatarHook {
	public function __construct() {
		add_filter( 'app_builder_prepare_avatar_data', array( $this, 'app_builder_prepare_avatar_data' ), 10, 2 );
	}

	/**
	 * Filter get avatar urls
	 *
	 * @param mixed $id_or_email The Gravatar to retrieve. Accepts a user ID, Gravatar MD5 hash,
	 *                           user email, WP_User object, WP_Post object, or WP_Comment object.
	 * @param $urls
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public function app_builder_prepare_avatar_data( $id_or_email, $urls ): array {
		if ( class_exists( 'Simple_Local_Avatars' ) ) {
			$local_avatar = new \Simple_Local_Avatars();
			$avatar_sizes = rest_get_avatar_sizes();
			$new_urls     = array();
			foreach ( $avatar_sizes as $size ) {
				$avatar = $local_avatar->get_simple_local_avatar_url( $id_or_email, $size );
				if ( $avatar ) {
					$new_urls[ $size ] = $avatar;
				} else {
					$new_urls[ $size ] = $local_avatar->get_default_avatar_url( $size );
				}
			}
			$avatar_urls = $new_urls;
		} else {
			$avatar_urls = rest_get_avatar_urls( $id_or_email );
		}

		return wp_parse_args( $avatar_urls, $urls );
	}
}
