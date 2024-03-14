<?php
/**
 * Meta field revisioning
 * Adapted from https://github.com/adamsilverstein/wp-post-meta-revisions
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( MetaRevisioning::class ) ) :
	/**
	 * The controller class for meta revisioning.
	 */
	class MetaRevisioning extends CoreComponent {
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// When restoring a revision, also restore that revisions's revisioned meta.
			add_action( 'wp_restore_post_revision', [ $this, 'wp_restore_post_revision_meta' ], 10, 2 );

			// When creating or updating an autosave, save any revisioned meta fields.
			add_action( 'wp_creating_autosave', [ $this, 'wp_autosave_post_revisioned_meta_fields' ] );
			add_action( 'wp_before_creating_autosave', [ $this, 'wp_autosave_post_revisioned_meta_fields' ] );

			// When creating a revision, also save any revisioned meta.
			add_action( '_wp_put_post_revision', [ $this, 'wp_save_revisioned_meta_fields' ] );

			// When revisioned post meta has changed, trigger a revision save.
			add_filter( 'wp_save_post_revision_post_has_changed', [ $this, 'wp_check_revisioned_meta_fields_have_changed' ], 10, 3 );

			// Add the revisioned meta to the JS data for the revisions interface.
			add_filter( 'wp_prepare_revision_for_js', [ $this, 'wp_add_meta_to_prepare_revision_for_js' ], 10, 3 );

			// Filter the diff ui returned for the revisions screen.
			add_filter( 'wp_get_revision_ui_diff', [ $this, 'wp_filter_revision_ui_diff' ], 10, 3 );

		}

		/**
		 * Autosave the revisioned meta fields.
		 *
		 * Iterates thru the revisioned meta fields and checks each to see if they are set,
		 * and have a changed value. If so, the meta value is saved and attached to the autosave.
		 *
		 * @since 1.0.0
		 *
		 * @param Post object $new_autosave The new post being autosaved.
		 */
		public function wp_autosave_post_revisioned_meta_fields( $new_autosave ) {
			/*
			 * The post data arrives as either $_POST['data']['wp_autosave'] or the $_POST
			 * itself. This sets $posted_data to the correct variable.
			 *
			 * Ignoring sanitization to avoid altering meta. Ignoring the nonce check because
			 * this is hooked on inner core hooks where a valid nonce was already checked.
			 *
			 * @phpcs:disable WordPress.Security
			 */
			$posted_data = isset( $_POST['data']['wp_autosave'] ) ? $_POST['data']['wp_autosave'] : $_POST;
			// phpcs:enable

			/*
			 * Go thru the revisioned meta keys and save them as part of the autosave, if
			 * the meta key is part of the posted data, the meta value is not blank and
			 * the the meta value has changes from the last autosaved value.
			 */
			foreach ( $this->wp_post_revision_meta_keys() as $meta_key ) {

				if (
				isset( $posted_data[ $meta_key ] ) &&
				! $this->compare_meta_value( get_post_meta( $new_autosave['ID'], $meta_key, true ), wp_unslash( $posted_data[ $meta_key ] ) )
				) {
					/*
					 * Use the underlying delete_metadata() and add_metadata() functions
					 * vs delete_post_meta() and add_post_meta() to make sure we're working
					 * with the actual revision meta.
					 */
					delete_metadata( 'post', $new_autosave['ID'], $meta_key );

					/*
					 * One last check to ensure meta value not empty().
					 */
					if ( ! empty( $posted_data[ $meta_key ] ) ) {
						/*
						 * Add the revisions meta data to the autosave.
						 */
						add_metadata( 'post', $new_autosave['ID'], $meta_key, $posted_data[ $meta_key ] );
					}
				}
			}
		}

		/**
		 * Determine which post meta fields should be revisioned.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * @return array An array of meta keys to be revisioned.
		 */
		public function wp_post_revision_meta_keys() {
			/**
			 * Filter the list of post meta keys to be revisioned.
			 *
			 * @since 1.0.0
			 *
			 * @param array $keys An array of default meta fields to be revisioned.
			 */
			return apply_filters( 'boldblocks_post_revision_meta_keys', [] ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
		}

		/**
		 * Check whether revisioned post meta fields have changed.
		 *
		 * @param bool     $post_has_changed Whether the post has changed.
		 * @param \WP_Post $last_revision    The last revision post object.
		 * @param \WP_Post $post             The post object.
		 *
		 * @since 1.0.0
		 */
		public function wp_check_revisioned_meta_fields_have_changed( $post_has_changed, \WP_Post $last_revision, \WP_Post $post ) {
			foreach ( $this->wp_post_revision_meta_keys() as $meta_key ) {
				$meta               = get_post_meta( $post->ID, $meta_key, true );
				$last_revision_meta = get_post_meta( $last_revision->ID, $meta_key, true );
				if ( ! $this->compare_meta_value( $meta, $last_revision_meta ) ) {
					$post_has_changed = true;
					break;
				}
			}
			return $post_has_changed;
		}

		/**
		 * Compare two meta value.
		 *
		 * @param mixed $value1
		 * @param mixed $value2
		 * @return void
		 */
		private function compare_meta_value( $value1, $value2 ) {
			if ( \is_array( $value1 ) && \is_array( $value2 ) ) {
				if ( count( $value1 ) === count( $value2 ) ) {
					$value1_serialize = array_map( 'serialize', $value1 );
					$value2_serialize = array_map( 'serialize', $value2 );
					return array_diff( $value1_serialize, $value2_serialize ) === array_diff( $value2_serialize, $value1_serialize );
				} else {
					return false;
				}
			} else {
				return $value1 === $value2;
			}
		}

		/**
		 * Save the revisioned meta fields.
		 *
		 * @param int $revision_id The ID of the revision to save the meta to.
		 *
		 * @since 1.0.0
		 */
		public function wp_save_revisioned_meta_fields( $revision_id ) {
			$revision = get_post( $revision_id );
			$post_id  = $revision->post_parent;

			// Save revisioned meta fields.
			foreach ( $this->wp_post_revision_meta_keys() as $meta_key ) {
				$this->copy_post_meta( $post_id, $revision_id, $meta_key );
			}
		}

		/**
		 * Restore the revisioned meta values for a post.
		 *
		 * @param int $post_id     The ID of the post to restore the meta to.
		 * @param int $revision_id The ID of the revision to restore the meta from.
		 *
		 * @since 1.0.0
		 */
		public function wp_restore_post_revision_meta( $post_id, $revision_id ) {

			// Restore revisioned meta fields.
			foreach ( (array) $this->wp_post_revision_meta_keys() as $meta_key ) {

				// Clear any existing meta.
				delete_post_meta( $post_id, $meta_key );

				$this->copy_post_meta( $revision_id, $post_id, $meta_key );
			}
		}

		/**
		 * Copy post meta for the given key from one post to another.
		 *
		 * @param int    $source_post_id Post ID to copy meta value(s) from.
		 * @param int    $target_post_id Post ID to copy meta value(s) to.
		 * @param string $meta_key       Meta key to copy.
		 *
		 * @since 2.0.0
		 */
		protected function copy_post_meta( $source_post_id, $target_post_id, $meta_key ) {
			foreach ( get_post_meta( $source_post_id, $meta_key ) as $meta_value ) {
				/**
				 * We use add_metadata() function vs add_post_meta() here
				 * to allow for a revision post target OR regular post.
				 */
				add_metadata( 'post', $target_post_id, $meta_key, wp_slash( $meta_value ) );
			}
		}

		/**
		 * Get the diff between two meta value
		 *
		 * @param mixed $meta_from
		 * @param mixed $meta_to
		 * @param array $args additional params for wp_text_diff
		 * @return string
		 */
		private function get_meta_diff( $meta_from, $meta_to, $args = [] ) {
			$meta_from = \is_array( $meta_from ) ? json_encode( $meta_from ) : $meta_from;
			$meta_to   = \is_array( $meta_to ) ? json_encode( $meta_to ) : $meta_to;

			$diff = wp_text_diff( (string) $meta_from, (string) $meta_to, $args );

			return $diff;
		}

		/**
		 * Filter the revisions ui diff, adding revisioned meta fields.
		 *
		 * @param array   fields        Revision UI fields. Each item is an array of id, name and diff.
		 * @param WP_Post                                  $compare_from The revision post to compare from.
		 * @param WP_Post                                  $compare_to   The revision post to compare to.
		 */
		public function wp_filter_revision_ui_diff( $fields, $compare_from, $compare_to ) {
			if ( ! $compare_from instanceof \WP_Post || ! $compare_to instanceof \WP_Post ) {
				return $fields;
			}

			// Do we have revisioned meta fields?
			$revisioned_meta_keys = $this->wp_post_revision_meta_keys();
			if ( ! empty( $revisioned_meta_keys ) ) {

				// Only add the header once, if we have a non-empty meta field.
				$meta_header_added = false;

				// Check each meta comparison for non empty diffs.
				foreach ( $revisioned_meta_keys as $meta_key ) {
					$meta_from = get_post_meta( $compare_from->ID, $meta_key, true );
					$meta_to   = get_post_meta( $compare_to->ID, $meta_key, true );

					$args = array( 'show_split_view' => true );
					$args = apply_filters( 'revision_text_diff_options', $args, end( $fields ), $compare_from, $compare_to );

					$diff = $this->get_meta_diff( $meta_from, $meta_to, $args );

					// Add this meta field if it has a diff.
					if ( ! empty( $diff ) ) {

						$new_field = array(
							'id'   => $meta_key,
							'name' => $meta_key,
							'diff' => $diff,
						);

						/**
						 * Filter revisioned meta fields used for the revisions UI.
						 *
						 * The dynamic portion of the hook name, `$meta_key`, refers to
						 * the revisioned meta key.
						 *
						 * @since 4.6.0
						 *
						 * @param object $new_field     Object with id, name and diff for the UI.
						 * @param WP_Post $compare_from The revision post to compare from.
						 * @param WP_Post $compare_to   The revision post to compare to.
						 */
						$new_field = apply_filters( 'revisioned_meta_ui_field_{$meta_key}', $new_field, $compare_from, $compare_to );

						$fields[ sizeof( $fields ) ] = $new_field;
					}
				}
			}

			return $fields;
		}

		/**
		 * Add the revisioned meta fields to the revisions interface.
		 *
		 * Include filters to enable customization of the meta display.
		 */
		public function wp_add_meta_to_prepare_revision_for_js( $revisions_data, $revision, $post ) {

			$revisions_data['revisionedMeta'] = array();

			// Go thru revisioned meta fields, adding them to the display data.
			foreach ( $this->wp_post_revision_meta_keys() as $meta_key ) {
				$revisions_data['revisionedMeta'][] = array(
					$meta_key => get_post_meta( $revisions_data['id'], $meta_key, true ),
				);
			}
			return $revisions_data;
		}
	}
endif;

