<?php
/**
 * Copy blocks, variations, and patterns
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2023, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( CopyPost::class ) ) :
	/**
	 * Copy blocks, variations, or patterns
	 */
	class CopyPost extends CoreComponent {
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Starting point.
			add_action( 'admin_init', [ $this, 'copy_post' ] );

			// Handle copy action.
			add_action( 'admin_action_cbb_copy_item', [ $this, 'copy_item' ] );

			// Add a notice for item copied successfully.
			add_action( 'admin_notices', [ $this, 'copy_item_admin_notice' ] );
		}

		/**
		 * Copy data
		 *
		 * @return void
		 */
		public function copy_post() {
			if ( 'edit.php' === $GLOBALS['pagenow'] ) {
				add_filter( 'post_row_actions', [ $this, 'add_row_action' ], 10, 2 );
				return;
			}
		}

		/**
		 * Add a "Copy" row action to blocks, variations, and patterns on list views.
		 *
		 * @param array   $actions Existing actions.
		 * @param WP_Post $post    Post object of current post in list.
		 * @return array           Array of updated row actions.
		 */
		public function add_row_action( $actions, $post ) {
			// Bail if it's not a place to add the copy link.
			if ( ! $this->user_can_access_post( $post->ID ) || ! $post instanceof \WP_Post || ! $this->validate_post_type( $post->post_type, $post ) ) {
				return $actions;
			}

			$edit_url    = wp_nonce_url(
				add_query_arg(
					array(
						'post_type'   => $post->post_type,
						'action'      => 'cbb_copy_item',
						'cbb-copy-id' => $post->ID,
					),
					admin_url( 'post-new.php' )
				),
				BOLDBLOCKS_CBB_ROOT_FILE,
				'cbb-copy-nonce'
			);
			$edit_action = array(
				'cbb-copy' => sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					esc_url( $edit_url ),
					esc_attr__( 'Copy this item.', 'content-blocks-builder' ),
					esc_html__( 'Copy', 'content-blocks-builder' )
				),
			);

			// Insert the Copy action before the Trash action.
			$edit_offset     = max( 2, array_search( 'trash', array_keys( $actions ), true ) );
			$updated_actions = array_merge(
				array_slice( $actions, 0, $edit_offset ),
				$edit_action,
				array_slice( $actions, $edit_offset )
			);

			/**
			 * Fires after the new Copy action has been added to the row actions.
			 * Allows changes to the action presentation, or other final checks.
			 *
			 * @param array   $updated_actions Updated row actions with the Copy Post action.
			 * @param array   $actions Original row actions passed to this filter.
			 * @param WP_Post $post Post object of current post in listing.
			 */
			return apply_filters( 'cbb_copy_item_row_actions', $updated_actions, $actions, $post );
		}

		/**
		 * Function creates post duplicate as a draft and redirects then to the edit post screen
		 */
		public function copy_item() {
			// Bail if there is no post to copy.
			if ( empty( $_GET['cbb-copy-id'] ) ) {
				return;
			}

			// Nonce verification.
			if ( ! isset( $_GET['cbb-copy-nonce'] ) || ! wp_verify_nonce( $_GET['cbb-copy-nonce'], BOLDBLOCKS_CBB_ROOT_FILE ) ) {
				return;
			}

			// Get the original post id.
			$post_id = absint( $_GET['cbb-copy-id'] );

			// Get the post data.
			$post = get_post( $post_id );
			if ( ! $post || ! $post instanceof \WP_Post ) {
				wp_die( 'Copy post failed, could not find the original post.', 'content-blocks-builder' );
			}

			// Not has permission and invalid post type.
			if ( ! $this->user_can_access_post( $post->ID ) || ! $this->validate_post_type( $post->post_type, $post ) ) {
				return;
			}

			// New author id.
			$current_user  = wp_get_current_user();
			$new_author_id = $current_user->ID;

			// New post data.
			$args = array(
				'post_title'   => $post->post_title . __( ' new copy', 'content-blocks-builder' ),
				'post_content' => wp_slash( $post->post_content ),
				'post_author'  => $new_author_id,
				'post_status'  => 'draft',
				'post_type'    => $post->post_type,
				'menu_order'   => $post->menu_order,
			);

			// Insert the post.
			$new_post_id = wp_insert_post( $args );

			// Copy taxonomies.
			$taxonomies = get_object_taxonomies( $post->post_type );
			if ( $taxonomies ) {
				foreach ( $taxonomies as $taxonomy ) {
					$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
					wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
				}
			}

			// Copy post meta.
			$post_meta = get_post_custom( $post_id );
			foreach ( $post_meta as $key => $values ) {
				foreach ( $values as $value ) {
					if ( 'boldblocks_variation_name' === $key ) {
						$strlen = strlen( $value );
						if ( $strlen > 10 ) {
							$id = uniqid();
							if ( strlen( $id ) > 10 ) {
								$id = substr( $id, -10 );
							}
							$prefix = substr( $value, 0, $strlen - 10 );
							$value  = $prefix . $id;
						}
					}
					update_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
				}
			}

			// Redirect to the post list screen.
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type'     => $post->post_type,
						'cbb_copied_id' => $new_post_id,
					),
					admin_url( 'edit.php' )
				)
			);

			exit;
		}

		/**
		 * Add the admin notice
		 *
		 * @return void
		 */
		public function copy_item_admin_notice() {
			// Get the current screen.
			$screen = get_current_screen();

			if ( 'edit' !== $screen->base ) {
				return;
			}

			if ( ! $this->validate_post_type( $screen->post_type ) ) {
				return;
			}

			// Checks if settings updated.
			if ( isset( $_GET['cbb_copied_id'] ) ) {
				$new_post_id = absint( $_GET['cbb_copied_id'] );
				switch ( $screen->post_type ) {
					case 'boldblocks_variation':
						$post_type_label = __( 'variation' );
						break;

					case 'boldblocks_pattern':
						$post_type_label = __( 'pattern' );
						break;

					default:
						$post_type_label = __( 'block' );

						break;
				}

				$edit_url = add_query_arg(
					array(
						'action' => 'edit',
						'post'   => $new_post_id,
					),
					admin_url( 'post.php' )
				);

				$edit_link = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					esc_url( $edit_url ),
					esc_attr__( 'Edit' ),
					esc_html__( 'Edit' )
				);

				echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( 'New %s copied.', 'content-blocks-builder' ), $post_type_label ) . ' ' . $edit_link . '.</p></div>';
			}
		}

		/**
		 * Determine if the current user has edit access to the source post.
		 *
		 * @param int $post_id Source post ID (the post being copied).
		 * @return bool True if user has the meta cap of `edit_post` for the given post ID, false otherwise.
		 */
		protected function user_can_access_post( $post_id ) {
			return current_user_can( 'edit_post', $post_id );
		}

		/**
		 * Validate the post type to be used for the target post.
		 *
		 * @param WP_Post $post Post object of current post in listing.
		 * @return bool True if the post type is in a list of supported psot types; false otherwise.
		 */
		protected function validate_post_type( $post_type, $post = null ) {
			/**
			 * Fires when determining if the "Copy" row action should be made available.
			 * Allows overriding supported post types.
			 *
			 * @param array   Post types supported by default.
			 * @param WP_Post $post Post object of current post in listing.
			 */
			$valid_post_types = apply_filters(
				'cbb_copy_item_post_types',
				[
					'boldblocks_block',
					'boldblocks_variation',
					'boldblocks_pattern',
				],
				$post
			);

			return in_array( $post_type, $valid_post_types, true );
		}
	}
endif;
