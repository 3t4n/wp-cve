<?php
/**
 * Category Featured Image
 *
 * @package    CategoryFeaturedImage
 * @subpackage Category Featured Image Main function
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

$categoryfeaturedimage = new CategoryFeaturedImage();

/** ==================================================
 * Class Main function
 *
 * @since 1.00
 */
class CategoryFeaturedImage {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'category_add_form_fields', array( $this, 'add_term_fields' ), 10, 1 );
		add_action( 'create_term', array( $this, 'save_terms' ), 10, 2 );
		add_action( 'edit_terms', array( $this, 'save_terms' ), 10, 2 );
		add_action( 'category_edit_form_fields', array( $this, 'edit_term_fields' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'manage_edit-category_columns', array( $this, 'add_term_columns' ) );
		add_filter( 'manage_category_custom_column', array( $this, 'add_term_custom_column' ), 10, 3 );
		add_action( 'wp_insert_post', array( $this, 'update_thumbnail' ), 10, 1 );
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 1.00
	 */
	public function load_custom_wp_admin_style() {

		if ( $this->is_my_plugin_screen() ) {
			$this->media_selector_print_scripts();
			$media_title_text = array(
				'title' => __( 'Choose Image', 'category-featured-image' ),
				'text' => __( 'Set featured image', 'category-featured-image' ),
			);
			wp_enqueue_script( 'jquery_cfi_text', plugin_dir_url( __DIR__ ) . 'js/jquery.categoryfeaturedimage.js', array( 'jquery' ), '1.00', false );
			wp_localize_script( 'jquery_cfi_text', 'cfi_text', $media_title_text );
		}
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 1.00
	 */
	private function is_my_plugin_screen() {

		$screen = get_current_screen();
		if ( is_object( $screen ) && 'edit-category' == $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Add Term
	 *
	 * @param string $taxonomy  taxonomy.
	 * @since 1.00
	 */
	public function add_term_fields( $taxonomy ) {

		if ( $this->is_my_plugin_screen() ) {
			/* Featured Image Id */
			if ( isset( $_POST['featured_image_id'] ) && ! empty( $_POST['featured_image_id'] ) ) {
				if ( check_admin_referer( 'fi_id_set', 'featured_image_id_set' ) ) {
					update_option( 'media_selector_attachment_id', absint( $_POST['featured_image_id'] ) );
				}
			}
			wp_enqueue_media();
			$this->edit_category_html( 0, false );
		}
	}

	/** ==================================================
	 * Edit Term
	 *
	 * @param object $tag  tag.
	 * @param string $taxonomy  taxonomy.
	 * @since 1.00
	 */
	public function edit_term_fields( $tag, $taxonomy ) {

		if ( $this->is_my_plugin_screen() ) {
			/* Featured Image Id */
			if ( isset( $_POST['featured_image_id'] ) && ! empty( $_POST['featured_image_id'] ) ) {
				if ( check_admin_referer( 'fi_id_set', 'featured_image_id_set' ) ) {
					update_option( 'media_selector_attachment_id', absint( $_POST['featured_image_id'] ) );
				}
			}
			wp_enqueue_media();
			$value = intval( get_term_meta( $tag->term_id, 'featured_image_id', true ) );
			$this->edit_category_html( $value, true );
		}
	}

	/** ==================================================
	 * Save Term Meta
	 *
	 * @param int    $term_id  term_id.
	 * @param string $taxonomy  taxonomy.
	 * @since 1.00
	 */
	public function save_terms( $term_id, $taxonomy ) {

		if ( array_key_exists( 'featured_image_id', $_POST ) ) {
			if ( isset( $_POST['featured_image_id'] ) && ! empty( $_POST['featured_image_id'] ) ) {
				if ( check_admin_referer( 'fi_id_set', 'featured_image_id_set' ) ) {
					$featured_image_id = intval( $_POST['featured_image_id'] );
					if ( 0 < $featured_image_id ) {
						update_term_meta( $term_id, 'featured_image_id', $featured_image_id );
					} else {
						delete_term_meta( $term_id, 'featured_image_id' );
					}
					$this->db_save( $term_id, $featured_image_id );
				}
			}
		}
	}

	/** ==================================================
	 * Custom column
	 *
	 * @param array $columns  columns.
	 * @return array $columns
	 * @since 1.00
	 */
	public function add_term_columns( $columns ) {

		return array_merge(
			array_slice( $columns, 0, 2 ),
			array( 'image' => __( 'Featured image' ) ),
			array_slice( $columns, 2 )
		);
	}

	/** ==================================================
	 * Custom column cotent
	 *
	 * @param string $content  content.
	 * @param string $column_name  column_name.
	 * @param int    $term_id  term_id.
	 * @return string $content
	 * @since 1.00
	 */
	public function add_term_custom_column( $content, $column_name, $term_id ) {

		if ( 'image' === $column_name ) {
			$featured_image_id = intval( get_term_meta( $term_id, 'featured_image_id', true ) );
			$content = wp_get_attachment_image( $featured_image_id, array( 64, 64 ) );
		}

		return $content;
	}

	/** ==================================================
	 * Update thumbnail meta data
	 *
	 * @param int $post_id  post_id.
	 * @since 2.00
	 */
	public function update_thumbnail( $post_id ) {

		/* Skip if current theme doesn't support post thumbnails */
		if ( ! current_theme_supports( 'post-thumbnails' ) ) {
			return;
		}

		$term_id = $this->choose_term( $post_id );
		if ( 0 < $term_id ) {
			$featured_image_id = intval( get_term_meta( $term_id, 'featured_image_id', true ) );
			update_post_meta( $post_id, '_thumbnail_id', $featured_image_id );
		} else {
			delete_post_meta( $post_id, '_thumbnail_id' );
		}
	}

	/** ==================================================
	 * Choose a category from multiple categories
	 *
	 * @param int $post_id  post_id.
	 * @return int $term_id.
	 * @since 2.00
	 */
	private function choose_term( $post_id ) {

		$term_id = 0;

		$terms = get_the_terms( $post_id, 'category' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$child_ids = array();
			foreach ( $terms as $term ) {
				$child_ids[] = $term->term_id;
				$term_id = $term->term_id;
			}
			foreach ( $child_ids as $child_id ) {
				$term2 = get_term( $child_id, 'category' );
				$parent_id = $term2->parent;
				if ( in_array( $parent_id, $child_ids ) ) {
					$term_id = $parent_id;
				}
			}
		}

		return $term_id;
	}

	/** ==================================================
	 * Database
	 *
	 * @param int $term_id  term_id.
	 * @param int $featured_image_id  featured_image_id.
	 * @since 1.00
	 */
	private function db_save( $term_id, $featured_image_id ) {

		$arg = array(
			'posts_per_page' => -1,
			'post_type'      => $this->post_custom_types(),
			'category'       => $term_id,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish,private,draft',
		);
		$posts = get_posts( $arg );

		foreach ( $posts as $post ) {
			if ( $featured_image_id > 0 ) {
				$term_id_parent = $this->choose_term( $post->ID );
				if ( 0 < $term_id_parent ) {
					$featured_image_id_parent = intval( get_term_meta( $term_id_parent, 'featured_image_id', true ) );
					if ( $featured_image_id_parent == $featured_image_id ) {
						update_post_meta( $post->ID, '_thumbnail_id', $featured_image_id );
					}
				} else {
					delete_post_meta( $post->ID, '_thumbnail_id' );
				}
			} else {
				$featured_image_id_org = intval( get_post_meta( $post->ID, '_thumbnail_id', true ) );
				if ( abs( $featured_image_id ) == $featured_image_id_org ) {
					delete_post_meta( $post->ID, '_thumbnail_id' );
				}
			}
		}
	}

	/** ==================================================
	 * Edit Category Html View
	 *
	 * @param int  $image_id  image_id.
	 * @param bool $edit  edit.
	 * @since 1.00
	 */
	private function edit_category_html( $image_id, $edit ) {

		if ( $image_id ) {
			$button_text = __( 'Replace Image', 'category-featured-image' );
		} else {
			$button_text = __( 'Add new image', 'category-featured-image' );
		}
		if ( $edit ) {
			?><tr class="form-field">
			<?php
		} else {
			?>
			<div class="form-field">
			<?php
		}
		if ( $edit ) {
			?>
			<th>
			<?php
		}
		?>
		<label for="featured_image_id"></label><?php esc_html_e( 'Featured image', 'category-featured-image' ); ?>
		<?php
		if ( $edit ) {
			?>
			</th><td>
			<?php
		}
		if ( $image_id ) {
			$image = $image_id;
		} else {
			$image = get_option( 'media_selector_attachment_id' );
		}
		?>
		<div class='image-preview-wrapper'>
		<img id='image-preview' src='<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>' height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php echo esc_attr( $button_text ); ?>" />
		<input id="delete_image_button" type="button" class="button" value="<?php esc_attr_e( 'Delete' ); ?>" />
		<?php wp_nonce_field( 'fi_id_set', 'featured_image_id_set' ); ?>
		<input type='hidden' name='featured_image_id' id='featured_image_id' value="<?php echo esc_attr( $image ); ?>">
		<?php
		if ( $edit ) {
			?>
			<h3 style="color: red;"><?php esc_html_e( 'Warning' ); ?></h3>
			<p class="description" style="color: red;"><?php esc_html_e( 'When "Update", this image will be the featured image for all posts in that category.', 'category-featured-image' ); ?></p>
			<p class="description" style="color: red;"><?php esc_html_e( 'Even if you do not set an image, pressing the "Update" button will remove the featured image for all posts in that category.', 'category-featured-image' ); ?></p>
			</td><tr>
			<?php
		} else {
			?>
			</div><div style="margin: 5px 0px; padding: 5px 0px;"></div>
			<?php
		}
	}

	/** ==================================================
	 * Media Selector
	 *
	 * @since 1.00
	 */
	private function media_selector_print_scripts() {

		$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

		?>
		<script type='text/javascript'>
			jQuery( document ).ready( function( $ ) {
				/* Uploading files */
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; /* Old id */
				var set_to_post_id = <?php echo intval( $my_saved_attachment_post_id ); ?>; /* Set this */
				jQuery('#upload_image_button').on('click', function( event ){
					event.preventDefault();
					/* If the media frame already exists, reopen it. */
					if ( file_frame ) {
						/* Set the post ID to what we want */
						file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						/* Open frame */
						file_frame.open();
						return;
					} else {
						/* Set the wp.media post id so the uploader grabs the ID we want when initialised */
						wp.media.model.settings.post.id = set_to_post_id;
					}
					/* Create the media frame. */
					file_frame = wp.media.frames.file_frame = wp.media({
						title: cfi_text.title,
						button: {
							text: cfi_text.text,
						},
						multiple: false	/* Set to true to allow multiple files to be selected */
					});
					/* When an image is selected, run a callback. */
					file_frame.on( 'select', function() {
						/* We set multiple to false so only get one image from the uploader */
						attachment = file_frame.state().get('selection').first().toJSON();
						/* Do something with attachment.id and/or attachment.url here */
						$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						$( '#featured_image_id' ).val( attachment.id );
						/* Restore the main post ID */
						wp.media.model.settings.post.id = wp_media_post_id;
					});
					/* Finally, open the modal */
					file_frame.open();
				});
				/* Restore the main ID when the add media button is pressed */
				jQuery( 'a.add_media' ).on( 'click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
				/* Remove media */
				jQuery('#delete_image_button').on('click', function( event ){
					event.preventDefault();
					/* Clear out the preview image */
					$( '#image-preview' ).removeAttr('src');
					/* Delete the image id from the hidden input */
					image_id = $( '#featured_image_id' ).val();
					$( '#featured_image_id' ).val( '-' + image_id );
					wp.media.model.settings.post.id = '';
				});
			});
		</script>
		<?php
	}

	/** ==================================================
	 * Post & Custom Post Type Name
	 *
	 * @since 1.00
	 */
	private function post_custom_types() {

		$post_custom_types = array( 'post' );
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$custom_post_types = get_post_types( $args, 'objects', 'and' );
		foreach ( $custom_post_types as $post_type ) {
			$post_custom_types[] = $post_type->name;
		}

		return $post_custom_types;
	}
}


