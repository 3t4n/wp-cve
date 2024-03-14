<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe_Maker
 * @subpackage Blossom_Recipe_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe_Maker
 * @subpackage Blossom_Recipe_Maker/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Meta_Box {

	/**
	 * Register this class with the WordPress API
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_recipe_options_meta_box' ) );
		add_action( 'save_post', array( $this, 'brm_save_recipe_post' ) );

	}

	/**
	 * The function responsible for creating the actual meta box.
	 *
	 * @since    1.0.0
	 */
	public function add_recipe_options_meta_box() {

		$post_type = array( 'blossom-recipe' );

		add_meta_box(
			'blossom-recipe',  // Unique ID
			__( 'Recipe Details', 'blossom-recipe-maker' ),         // Box title
			array( $this, 'display_recipe_options_meta_box' ),   // Content callback, must be of type callable
			$post_type,             // Post type
			'normal',
			'high'
		);

	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @since    1.0.0
	 */
	public function display_recipe_options_meta_box() {

		include BLOSSOM_RECIPE_MAKER_BASE_PATH . '/admin/meta-data/blossom-recipe-tabs.php';

	}

	/**
	 * Sanitizes and serializes the information associated with this post.
	 *
	 * @since    1.0.0
	 *
	 * @param    int $post_id    The ID of the post that's currently being edited.
	 */
	public function brm_save_recipe_post( $post_id ) {

		$submitted_post_data = blossom_recipe_maker_get_submitted_data( 'post' );

		if ( ! isset( $submitted_post_data['blossom_recipe_maker_nonce'] ) || ! wp_verify_nonce( $submitted_post_data['blossom_recipe_maker_nonce'], 'blossom_recipe_maker_save' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! empty( $submitted_post_data['br_recipe'] ) ) {

			// We'll remove all white space, HTML tags, and encode the information to be saved
			$recipedetails = $submitted_post_data['br_recipe'];
			update_post_meta( $post_id, 'br_recipe', $recipedetails );

		} else {

			if ( '' !== get_post_meta( $post_id, 'br_recipe', true ) ) {
				delete_post_meta( $post_id, 'br_recipe' );
			}
		}

		if ( isset( $submitted_post_data['br_recipe_gallery'] ) ) {
			update_post_meta( $post_id, 'br_recipe_gallery', $submitted_post_data['br_recipe_gallery'] );
		} else {
			delete_post_meta( $post_id, 'br_recipe_gallery' );
		}

	}



}
