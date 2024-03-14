<?php
/**
 * UpStream_Admin_Metaboxes
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'UpStream_Admin_Metaboxes' ) ) :

	/**
	 * CMB2 Theme Options
	 *
	 * @version 0.1.0
	 */
	class UpStream_Admin_Metaboxes {


		/**
		 * Constructor
		 *
		 * @since 0.1.0
		 */
		public function __construct() {
			if ( upstream_should_run_cmb2() ) {
				add_action( 'cmb2_admin_init', array( $this, 'register_metaboxes' ) );
				add_filter( 'cmb2_override_meta_value', array( $this, 'get_project_meta' ), 10, 3 );
				add_filter( 'cmb2_override_meta_save', array( $this, 'set_project_meta' ), 10, 3 );
				add_filter( 'cmb2_save_field__upstream_project_start', array( $this, 'cmb2_save_field__upstream_project_start' ), 10, 3 );
				add_filter( 'cmb2_save_field__upstream_project_end', array( $this, 'cmb2_save_field__upstream_project_end' ), 10, 3 );
			}

			UpStream_Metaboxes_Clients::attach_hooks();
		}

		/**
		 * Add the options metabox to the array of metaboxes
		 *
		 * @since  0.1.0
		 */
		public function register_metaboxes() {
			/**
			 * Load the metaboxes for project post type
			 */
			$project_metaboxes = new UpStream_Metaboxes_Projects();
			$project_metaboxes->get_instance();

			// Load all Client metaboxes (post_type="client").
			UpStream_Metaboxes_Clients::instantiate();
		}

		/**
		 * Get Project Meta
		 *
		 * @param  mixed $data Data.
		 * @param  mixed $id Id.
		 * @param  mixed $field Field.
		 */
		public function get_project_meta( $data, $id, $field ) {
			// Override the milestone data for the metaboxes.
			if ( '_upstream_project_milestones' === $field['field_id'] ) {
				$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $id );

				$data = array();

				if ( ! empty( $milestones ) ) {
					foreach ( $milestones as $milestone ) {
						$milestone = \UpStream\Factory::get_milestone( $milestone );

						$milestone_data = $milestone->convertToLegacyRowset();

						$data[] = $milestone_data;
					}
				}
			} elseif ( '_upstream_project_tasks' === $field['field_id'] ) {
				$data = array();
				$data = get_metadata( $field['type'], $field['id'], $field['field_id'], ( $field['single'] || $field['repeat'] ) );

				// RSD: this is for backward compatibility with timezones
				// TODO: remove this.
				$offset     = get_option( 'gmt_offset' );
				$data_count = is_array( $data ) ? count( $data ) : 0;

				for ( $i = 0; $data && $i < $data_count; $i++ ) {

					if ( isset( $data[ $i ]['start_date'] ) && is_numeric( $data[ $i ]['start_date'] ) ) {
						$start_date_timestamp     = $data[ $i ]['start_date'];
						$start_date_timestamp     = $start_date_timestamp + ( $offset > 0 ? $offset * 60 * 60 : 0 );
						$data[ $i ]['start_date'] = $start_date_timestamp;

						if ( ! empty( $data[ $i ]['start_date.YMD'] ) ) {
							$data[ $i ]['start_date'] = strtotime( $data[ $i ]['start_date.YMD'] );
						}
					}

					if ( isset( $data[ $i ]['end_date'] ) && is_numeric( $data[ $i ]['end_date'] ) ) {
						$end_date_timestamp     = $data[ $i ]['end_date'];
						$end_date_timestamp     = $end_date_timestamp + ( $offset > 0 ? $offset * 60 * 60 : 0 );
						$data[ $i ]['end_date'] = $end_date_timestamp;

						if ( ! empty( $data[ $i ]['end_date.YMD'] ) ) {
							$data[ $i ]['end_date'] = strtotime( $data[ $i ]['end_date.YMD'] );
						}
					}
				}
			} elseif ( '_upstream_project_bugs' === $field['field_id'] ) {
				$data = array();
				$data = get_metadata( $field['type'], $field['id'], $field['field_id'], ( $field['single'] || $field['repeat'] ) );

				// RSD: this is for backward compatibility with timezones
				// TODO: remove this.
				$offset     = get_option( 'gmt_offset' );
				$data_count = is_array( $data ) ? count( $data ) : 0;

				for ( $i = 0; $data && $i < $data_count; $i++ ) {

					if ( isset( $data[ $i ]['due_date'] ) && is_numeric( $data[ $i ]['due_date'] ) ) {
						$due_date_timestamp     = $data[ $i ]['due_date'];
						$due_date_timestamp     = $due_date_timestamp + ( $offset > 0 ? $offset * 60 * 60 : 0 );
						$data[ $i ]['due_date'] = $due_date_timestamp;

						if ( ! empty( $data[ $i ]['due_date.YMD'] ) ) {
							$data[ $i ]['due_date'] = strtotime( $data[ $i ]['due_date.YMD'] );
						}
					}
				}
			}

			return $data;
		}

		/**
		 * Cmb2 Save Field Upstream Project Start
		 *
		 * @param  mixed $updated Updated.
		 * @param  mixed $action Action.
		 * @param  mixed $value Field value.
		 * @return void
		 */
		public function cmb2_save_field__upstream_project_start( $updated, $action, $value ) {
			$post_data  = isset( $_POST ) ? wp_unslash( $_POST ) : array();
			$nonce      = isset( $post_data['upstream_admin_project_form_nonce'] ) ? $post_data['upstream_admin_project_form_nonce'] : null;
			$value      = sanitize_text_field( $post_data['_upstream_project_start'] );
			$project_id = absint( $post_data['post_ID'] );

			/* if project ID isn't valid the access function will return false */
			if ( \UpStream_Model_Object::isValidDate( $value )
				&& upstream_user_can_access_project( get_current_user_id(), $project_id )
				&& wp_verify_nonce( $nonce, 'upstream_admin_project_form' )
			) {
				update_post_meta( $project_id, '_upstream_project_start.YMD', $value );
			}
		}

		/**
		 * Cmb2 Save Field Upstream Project End
		 *
		 * @param  mixed $updated Updated.
		 * @param  mixed $action Action.
		 * @param  mixed $value Field value.
		 * @return void
		 */
		public function cmb2_save_field__upstream_project_end( $updated, $action, $value ) {
			$post_data  = isset( $_POST ) ? wp_unslash( $_POST ) : array();
			$nonce      = isset( $post_data['upstream_admin_project_form_nonce'] ) ? $post_data['upstream_admin_project_form_nonce'] : null;
			$value      = sanitize_text_field( $post_data['_upstream_project_end'] );
			$project_id = absint( $post_data['post_ID'] );

			/* if project ID isn't valid the access function will return false */
			if ( \UpStream_Model_Object::isValidDate( $value )
				&& upstream_user_can_access_project( get_current_user_id(), $project_id )
				&& wp_verify_nonce( $nonce, 'upstream_admin_project_form' )
			) {
				update_post_meta( $project_id, '_upstream_project_end.YMD', $value );
			}
		}

		/**
		 * Set Project Meta
		 *
		 * @param mixed $check Check.
		 * @param mixed $object Object.
		 * @param mixed $form Form.
		 *
		 * @return bool
		 * @throws \UpStream\Exception Exception.
		 */
		public function set_project_meta( $check, $object, $form ) {
			$object_type = '';

			if ( '_upstream_project_status' === $object['field_id'] ) {

				do_action( 'upstream_item_pre_change', 'project', $object['id'], $object['id'], $object );

				do_action( 'upstream_save_metabox_field', $object );
				return $check;

			}

			if ( '_upstream_project_milestones' === $object['field_id'] ) {
				$object_type = 'milestone';
			} elseif ( '_upstream_project_tasks' === $object['field_id'] ) {
				$object_type = 'task';
			} elseif ( '_upstream_project_bugs' === $object['field_id'] ) {
				$object_type = 'bug';
			} elseif ( '_upstream_project_files' === $object['field_id'] ) {
				$object_type = 'file';
			}

			if ( $object_type ) {
				if ( isset( $object['value'] ) && is_array( $object['value'] ) ) {
					$count_value = is_array( $object['value'] ) ? count( $object['value'] ) : 0;
					for ( $i = 0; $i < $count_value; $i++ ) {
						$item = $object['value'][ $i ];
						if ( isset( $item['id'] ) ) {
							do_action( 'upstream_item_pre_change', $object_type, $item['id'], $object['id'], $item );
						}
					}
				}
			}
			if ( '_upstream_project_milestones' === $object['field_id'] ) {
				if ( isset( $object['value'] ) && is_array( $object['value'] ) ) {
					$current_milestone_ids = array();
					$count_value           = is_array( $object['value'] ) ? count( $object['value'] ) : 0;
					for ( $i = 0; $i < $count_value; $i++ ) {

						// $object is sanitized already by CMB2
						$milestone_data = $object['value'][ $i ];

						// If the milestone have a blank name, then we generate for them.
						if ( ! isset( $milestone_data['milestone'] ) ) {
							$milestone_data['milestone'] = 'Milestone ' . uniqid();
						}

						// If doesn't have an id, we create the milestone.
						if ( ! isset( $milestone_data['id'] ) || empty( $milestone_data['id'] ) ) {
							$milestone = \UpStream\Factory::create_milestone( $milestone_data['milestone'] );

							$milestone->setProjectId( $object['id'] );
							$object['value'][ $i ]['id'] = $milestone->getId();

						} else {
							// Update the milestone.
							$milestone = \UpStream\Factory::get_milestone( $milestone_data['id'] );
							$milestone->setName( $milestone_data['milestone'] );
						}

						if ( empty( $milestone ) ) {
							continue;
						}

						if ( ! upstream_disable_milestone_categories() ) {
							if ( isset( $milestone_data['categories'] ) ) {
								$milestone->setCategories( $milestone_data['categories'] );
							} else {
								$milestone->setCategories( array() );
							}
						}

						if ( isset( $milestone_data['assigned_to'] ) ) {
							$milestone->setAssignedTo( $milestone_data['assigned_to'] );
						} else {
							$milestone->setAssignedTo( 0 );
						}

						if ( isset( $milestone_data['start_date'] ) ) {
							$milestone->setStartDate( $milestone_data['start_date'] );
						} else {
							$milestone->setStartDate( '' );
						}

						if ( isset( $milestone_data['end_date'] ) ) {
							$milestone->setEndDate( $milestone_data['end_date'] );
						} else {
							$milestone->setEndDate( '' );
						}

						if ( isset( $milestone_data['start_date.YMD'] ) ) {
							$milestone->setStartDate__YMD( $milestone_data['start_date.YMD'] );
						} else {
							$milestone->setStartDate__YMD( '' );
						}

						if ( isset( $milestone_data['end_date.YMD'] ) ) {
							$milestone->setEndDate__YMD( $milestone_data['end_date.YMD'] );
						} else {
							$milestone->setEndDate__YMD( '' );
						}

						if ( isset( $milestone_data['notes'] ) ) {
							$milestone->setNotes( $milestone_data['notes'] );
						} else {
							$milestone->setNotes( '' );
						}

						// RSD: the colors get replaced because teh color widget isnt on this page.
						if ( empty( $milestone_data['color'] ) ) {
							$milestone->setColor( '' );
						} elseif ( isset( $milestone_data['color'] ) ) {
							$milestone->setColor( $milestone_data['color'] );
						}

						$current_milestone_ids[] = $milestone->getId();
					}

					// Check if we need to delete any Milestone. If it is not found on the post, it was removed.
					$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $object['id'] );

					foreach ( $milestones as $milestone ) {
						if ( ! in_array( $milestone->ID, $current_milestone_ids, false ) ) {
							$milestone = \UpStream\Factory::get_milestone( $milestone );
							$milestone->delete();
						}
					}

					$check = true;
				}
			}

			do_action( 'upstream_save_metabox_field', $object );

			return $check;
		}
	}

	new UpStream_Admin_Metaboxes();

endif;
