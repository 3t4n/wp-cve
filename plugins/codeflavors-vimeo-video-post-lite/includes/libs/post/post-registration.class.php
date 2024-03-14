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
class Post_Registration {
	/**
	 * @var Register_Post[]
	 */
	private $types;

	/**
	 * Post_Registration constructor.
	 *
	 * @since 2.0.14    Added new argument for tag taxonomy associated with the post type passed as first argument
	 *
	 * @param \WP_Post_Type      $post_type
	 * @param \WP_Taxonomy|false $taxonomy
	 * @param \WP_Taxonomy|false $tag_taxonomy
	 */
	public function __construct( \WP_Post_Type $post_type, $taxonomy, $tag_taxonomy = false ) {
		$this->register( $post_type, $taxonomy, $tag_taxonomy );
	}

	/**
	 * @since 2.0.14    Added new parameter for tag taxonomy
	 *
	 * @param \WP_Post_Type      $post_type
	 * @param \WP_Taxonomy|false $taxonomy
	 * @param \WP_Taxonomy|false $tag_taxonomy
	 */
	public function register( \WP_Post_Type $post_type, $taxonomy, $tag_taxonomy = false ){
		if( !did_action( 'init' ) ){
			_doing_it_wrong( __FUNCTION__, 'Post types must be registered only after "init" hook is fired.' );
		}

		$this->types[ $post_type->name ] = new Register_Post(
			$post_type,
			$taxonomy,
			$tag_taxonomy
		);
	}

	/**
	 * @return Register_Post[]
	 */
	public function get_post_types(){
		return $this->types;
	}

	/**
	 * @param $post_type
	 *
	 * @return null|Register_Post
	 */
	public function get_post_type( $post_type ){
		if( $this->is_registered_post_type( $post_type ) ){
			return $this->types[ $post_type ];
		}

		return null;
	}

	/**
	 * @param $post_type
	 *
	 * @return bool
	 */
	public function is_registered_post_type( $post_type ){
		return isset( $this->types[ $post_type ] );
	}
}