<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Post;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 *
 * @ignore
 */
class Register_Post {

	/**
	 * @var \WP_Taxonomy
	 */
	private $taxonomy;
	/**
	 * @var \WP_Post_Type
	 */
	private $post_type;
	/**
	 * @var false|\WP_Taxonomy
	 */
	private $tag_taxonomy;

	/**
	 * Register_Post constructor.
	 *
	 * @param \WP_Post_Type      $post_type
	 * @param \WP_Taxonomy|false $taxonomy
	 * @param \WP_Taxonomy|false $tag_taxonomy
	 */
	public function __construct( \WP_Post_Type $post_type, $taxonomy, $tag_taxonomy = false ) {
		$this->post_type = $post_type;
		$this->taxonomy  = $taxonomy;
		$this->tag_taxonomy = $tag_taxonomy;
	}

	/**
	 * @return \WP_Taxonomy
	 */
	public function get_taxonomy() {
		if( !$this->taxonomy instanceof \WP_Taxonomy ){
			return false;
		}

		return $this->taxonomy;
	}

	/**
	 * Returns the tag taxonomy associated with the registered post type
	 *
	 * @since 2.0.14
	 *
	 * @return false|\WP_Taxonomy
	 */
	public function get_tag_taxonomy() {
		if( !$this->tag_taxonomy instanceof \WP_Taxonomy ){
			return false;
		}

		return $this->tag_taxonomy;
	}

	/**
	 * @return \WP_Post_Type
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	public function get_post_type_rest_endpoint(){
		return '/vimeotheque/v1/get_posts';
	}

	public function get_taxonomy_rest_endpoint(){
		if( !$this->taxonomy instanceof \WP_Taxonomy ){
			return false;
		}

		$rest_base = ! empty( $this->taxonomy->rest_base ) ? $this->taxonomy->rest_base : $this->taxonomy->name;
		return '/wp/v2/' . $rest_base;
	}


}