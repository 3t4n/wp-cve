<?php
/**
 * @author  CodeFlavors
 */

namespace Vimeotheque\Templates;

use Vimeotheque\Plugin;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Helper
 */
class Helper {
	/**
	 * Check if video taxonomy.
	 *
	 * Returns true is viewing a video taxonomy page.
	 *
	 * @return bool
	 */
	public static function is_video_taxonomy(){
		return is_tax( get_object_taxonomies( Plugin::instance()->get_cpt()->get_post_type() ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return mixed|void
	 */
	public static function template_path(){
		/**
		 * Rename theme template folder.
		 *
		 * By default, tempaltes must be stored within the WordPress theme inside a folder named 'vimeotheque'.
		 * This filter allows renaming of the folder.
		 *
		 * @param string $folder_name   The folder name.
		 */
		return apply_filters( 'vimeotheque_template_path', 'vimeotheque/' );
	}

	/**
	 * Retrieves the adjacent post.
	 *
	 * Can either be next or previous post.
	 *
	 *
	 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
	 * @param int[]|string $excluded_terms Optional. Array or comma-separated list of excluded term IDs. Default empty string.
	 * @param bool         $previous       Optional. Whether to retrieve previous post. Default true
	 * @param string       $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default 'category'.
	 * @return WP_Post|null|string Post object if successful. Null if global $post is not set. Empty string if no
	 *                             corresponding post exists.
	 */
	public static function get_adjacent_post(  $in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'vimeo-videos' ){
		$post = get_adjacent_post( $in_same_term, $excluded_terms, $previous, $taxonomy );
		return $post;
	}

	/**
	 * Get video duration.
	 *
	 * Get the current post video duration.
	 *
	 * @return string
	 */
	public static function get_the_video_duration(){

		$post = \Vimeotheque\Helper::get_video_post();

		return $post->_duration;
	}
}