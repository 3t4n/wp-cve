<?php
namespace SG_Email_Marketing\Post_Types;

/**
 * Forms CPT class.
 */
class Forms {

	/**
	 * The CPT type.
	 *
	 * @var string
	 */
	public static $post_type = 'sg_form';

	/**
	 * Register the Forms Custom Post type.
	 *
	 * @since 1.0.0
	 */
	public function register_forms_post_type() {
		register_post_type(
			self::$post_type,
			array(
				'labels' => array(
					'name' => __( 'SiteGround Forms', 'siteground-email-marketing' ),
				),
				'public'       => true,
				'show_ui'      => false,
				'show_in_menu' => false,
				'map_meta_cap' => true,
				'show_in_rest' => true,
				'supports'     => array(
					'title',
					'editor',
					'author',
				),
			)
		);
	}

	/**
	 * Retrieve all CPT entries.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool $decode_titles  True, if it should decode the titles of the posts before returning.
	 *
	 * @return array             List of posts.
	 */
	public static function get_all_forms( $decode_titles = false ) {
		$posts = get_posts( array( 'post_type' => 'sg_form', 'posts_per_page' => -1 ) );

		if ( empty( $posts ) ) {
			return array();
		}

		if ( ! $decode_titles ) {

			return $posts;
		}

		foreach ( $posts as $post ) {
			$post->post_title = htmlspecialchars_decode( $post->post_title, ENT_QUOTES );
		}

		return $posts;
	}
}
