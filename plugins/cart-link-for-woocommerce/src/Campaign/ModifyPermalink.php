<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use WP_Post;

class ModifyPermalink {
	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 10, 2 );
		add_filter( 'get_sample_permalink', [ $this, 'get_sample_permalink' ], 10, 2 );
	}

	/**
	 * @param array $permalink .
	 * @param int   $post_id   .
	 *
	 * @return array
	 */
	public function get_sample_permalink( $permalink, int $post_id ): array {
		if ( get_post_type( $post_id ) === RegisterPostType::POST_TYPE ) {
			$permalink[0] = home_url( '%pagename%/' );
		}

		return $permalink;
	}

	/**
	 * @param string  $post_link .
	 * @param WP_Post $post      .
	 *
	 * @return string
	 */
	public function post_type_link( $post_link, WP_Post $post ): string {
		if ( RegisterPostType::POST_TYPE === get_post_type( $post ) ) {
			return trailingslashit( home_url( $post->post_name ) );
		}

		return $post_link;
	}
}
