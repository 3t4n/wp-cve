<?php
/*
Plugin Name: Title Colors
Plugin URI: http://trepmal.com/plugins/post-title-colors/
Description: Choose the Title color for posts
Author: Kailey Lampert
Version: 1.4
Author URI: http://kaileylampert.com/
*/
/*
	Copyright (C) 2010-16  Kailey Lampert

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


$ptc_post_title_color = new Post_Title_Color();

class Post_Title_Color {

	/**
	 * Meta key
	 */
	private $meta_key = 'title_color';

	/**
	 * Supported post types
	 */
	private $valid_post_types;

	/**
	 * Get hooked in
	 */
	function __construct() {
		add_action( 'add_meta_boxes',        array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'save_post',             array( $this, 'update' ), 10, 2 );
		add_action( 'the_post',              array( $this, 'the_post' ) );
		add_filter( 'is_protected_meta',     array( $this, 'protect_meta' ), 10, 2 );

		/**
		 * Previous versions of this plugin suggested editing this file to support pages as well as posts
		 * This is bad practice, but you may add support below if you're in a pinch.
		 *
		 * However, this would be recommended:
		 * Create a directory (if it doesn't exist) in wp-content/ called "mu-plugins"
		 * In mu-plugins/ create a file called 'ptc-on-pages.php' (or any name will do)
		 * In that file add this code (be sure to exclude the asterisks):
		 *    <?php
		 *    add_filter( 'post_title_colors_post_types', 'ptc_on_pages' );
		 *    function ptc_on_pages( $post_types ) {
		 *    	$post_types[] = 'page';
		 *    	return $post_types;
		 *    }
		 */
		$this->valid_post_types = apply_filters( 'post_title_colors_post_types', array( 'post' ) );
	}

	/**
	 * Set up meta boxes
	 */
	function add_meta_boxes() {

		foreach ( $this->valid_post_types as $post_type ) {
			add_meta_box( 'title-color', __( 'Title Color', 'post-title-color' ), array( $this, 'box' ), $post_type, 'side' );
		}

	}

	/**
	 * Fill in meta box
	 */
	function box( $post ) {
		wp_nonce_field( 'a_save', 'n_color' );
		?>
			<input type="text" class="colorpick" name="title-color" value="<?php echo esc_attr( get_post_meta( $post->ID, $this->meta_key, true ) ); ?>" />
		<?php
	}

	/**
	 * Enqueue required scripts
	 */
	function admin_enqueue_scripts( $hook ) {
		// only load these scripts on the proper admin pages
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}
		ob_start();
	?>
jQuery(document).ready(function($){
	$('.colorpick').wpColorPicker({
		change: function( event, ui ) {
			$(this).val( ui.color.toString() );
		}
	});
});
		<?php
		$script = ob_get_clean();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script( 'wp-color-picker', $script );
	}

	/**
	 * Save
	 */
	function update( $post_id, $post ) {
		// no nonce set? bail
		if ( ! isset( $_POST['n_color'] ) ) {
			return;
		}

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['n_color'], 'a_save' ) ) {
			return;
		}

		// Check permissions
		if ( 'post' == $post->post_type && ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// though the option isn't on pages by default, support perms out of box
		if ( 'page' == $post->post_type && ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, $this->meta_key, strip_tags( $_POST['title-color'] ) );

	}

	/**
	 * As late as possible, filter the title
	 */
	function the_post( $query ) {
		add_filter( 'the_title', array( $this, 'add_to_title' ), 11, 2 );
	}

	/**
	 * Add color to title
	 */
	function add_to_title( $title, $post_id ) {
		remove_filter( 'the_title', array( $this, 'add_to_title' ), 11, 2 );
		if ( ! in_array( get_post_type(), $this->valid_post_types ) ) {
			return $title;
		}
		global $wp_query;
		if ( $wp_query->in_the_loop ) {
			if ( $color = get_post_meta( $post_id, $this->meta_key, true ) ) {
				$color = esc_attr( $color );
				return "<span style='color:{$color};'>{$title}</span>";
			}
		}
		return $title;
	}

	/**
	 * Hide from Custom Fields meta box
	 */
	function protect_meta( $protected, $meta_key ) {
		if ( $this->meta_key == $meta_key ) {
			return true;
		}
		return $protected;
	}

}
