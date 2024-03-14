<?php
/**
 * Custom Post Template.
 *
 * PHP version 7
 *
 * @package  Custom_Post_Template
 */

/**
 * Custom Post Template.
 *
 * Template Class
 *
 * @package  Custom_Post_Template
 */
class Custom_Post_Template {
	/** Constructor or curretn class */
	public function __construct() {
		add_filter( 'single_template', array( 'Custom_Post_Template', 'mws_single_story_template' ) );

		add_filter( 'archive_template', array($this, 'get_custom_post_type_template') ) ;
	}
	/**
	 * Override Post Template For Custom Post Type.
	 *
	 * @param string $template This will return the post template.
	 */
	public static function mws_single_story_template( $template ) {
		global $post;
		if ( 'webstories' === $post->post_type ) {
			return plugin_dir_path( __FILE__ ) . 'class-custom-post-content.php';
		}
		return $template;
	}

	public function get_custom_post_type_template( $archive_template ) {
		global $post;
		if (is_archive() && get_post_type($post) == 'webstories') {
		 	return plugin_dir_path( __FILE__ ) . 'class-custom-post-archieve.php';
		}
		return $archive_template;
	}

}
new Custom_Post_Template();
