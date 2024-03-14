<?php
/**
 * UpStream_Project_Activity Class
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UpStream_Project_Activity Class
 *
 * @since 1.0.0
 */
class UpStream_Project_Activity {

	use \UpStream\Traits\Singleton;

	/**
	 * The project ID
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * The posted data
	 *
	 * @var array|null
	 */
	public $posted = null;

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Run WordPress hooks
	 */
	public function hooks() {
		// Posted in frontend.
		$this->init_update( null, null );

		// Posted in backend.
		add_action( 'wp_insert_post_data', array( $this, 'init_update' ), 99, 2 );
	}

	/**
	 * Update post data
	 *
	 * @param array $data Post data.
	 * @param array $postarr Posted data to insert.
	 */
	public function init_update( $data, $postarr ) {
		if ( ! $postarr && ! isset( $_POST ) ) {
			return;
		}

		// Get the post data.
		$this->posted = $postarr ? $postarr : wp_unslash( $_POST );

		/**
		 * Nonce verification.
		 * We have a different nonce verification between frontend and backend.
		 */
		if ( isset( $this->posted['upstream-nonce'] ) && isset( $this->posted['post_id'] ) ) {
			// Posted in frontend.
			$nonce = isset( $this->posted['upstream-nonce'] ) ? $this->posted['upstream-nonce'] : null;

			if ( ! wp_verify_nonce( $nonce, 'upstream_security' ) ) {
				return $data;
			}
		} elseif (
			isset( $this->posted['action'] ) &&
			isset( $this->posted['post_type'] ) &&
			sanitize_text_field( $this->posted['post_type'] ) === 'project'
		) {
			// Posted in backend.
			$nonce = isset( $this->posted['upstream_admin_project_form_nonce'] ) ? $this->posted['upstream_admin_project_form_nonce'] : null;

			if ( ! wp_verify_nonce( $nonce, 'upstream_admin_project_form' ) ) {
				return $data;
			}
		} else {
			return $data;
		}

		// ID may be alphanumeric.
		$this->ID = isset( $this->posted['post_id'] ) ? absint( $this->posted['post_id'] ) : absint( $this->posted['post_ID'] );

		// Posted in frontend.
		if ( isset( $this->posted['upstream-nonce'] ) ) {
			$posted = $this->posted;
			$group  = '_upstream_project_' . sanitize_text_field( $posted['type'] );

			if ( isset( $posted['editing'] ) ) {
				$posted['id'] = sanitize_text_field( $posted['editing'] );
			}

			// reset our posted variable.
			$this->posted              = array();
			$this->posted[ $group ][0] = $posted;

			if ( isset( $posted['action'] ) && sanitize_text_field( $posted['action'] ) === 'upstream_frontend_delete_item' ) {
				$this->posted['frontend'] = 'delete';
			}

			// remove keys not required.
			$remove = array(
				'upstream-nonce',
				'upstream_security',
				'action',
				'_wp_http_referer',
				'post_id',
				'type',
				'editing',
				'row',
				'upstream-files-nonce',
			);

			foreach ( $remove as $key ) {
				unset( $this->posted[ $group ][0][ $key ] );
			}
		}

		if ( isset( $postarr ) ) {
			$this->posted['admin'] = true;
		}

		// If this is an auto draft.
		if ( isset( $this->posted['post_status'] ) && sanitize_text_field( $this->posted['post_status'] ) === 'auto-draft' ) {
			return $data;
		}

		// ignore quick edit.
		if ( isset( $this->posted['action'] ) && sanitize_text_field( $this->posted['action'] ) === 'inline-save' ) {
			return $data;
		}

		$this->update_project();

		return $data;
	}


	/**
	 * Update a project
	 */
	public function update_project() {
		$activity = array();
		$time     = current_time( 'timestamp' );
		$user_id  = upstream_current_user_id();

		// start to loop through each POSTED item.
		foreach ( $this->posted as $key => $new_value ) {

			$key = sanitize_text_field( $key );

			// skip some of WordPress standard fields that we don't need.
			if ( $this->match( $key, array( 'nonce', 'action', 'refer', 'hidden' ) ) ) {
				continue;
			}

			// first check for UpStream fields.
			if ( $this->match( $key, '_upstream_project' ) ) {

				// get the old value so we can compare.
				$old_value = $this->get_meta( $key );

				// check the simple string fields first.
				if ( ! is_array( $new_value ) || '_upstream_project_client_users' === $key ) {

					if ( '_upstream_project_client_users' === $key ) {
						$nv = array();
						if ( $new_value && is_array( $new_value ) && isset( $new_value[0] ) ) {
							$nv = \array_map( 'sanitize_text_field', $new_value );
						}
						$new_value = $nv;
					} else {
						$new_value = sanitize_text_field( $new_value );
					}

					// handle date formatting differences first.
					if ( $this->match( $key, array( 'project_start', 'project_end', 'date' ) ) ) {
						// $old_value = upstream_format_date( $old_value );
						$new_value = upstream_timestamp_from_date( $new_value );
					}

					// if we are adding a new item.
					if ( ! $old_value && ! empty( $new_value ) ) {
						$activity['single'][ $key ]['add'] = $new_value;
						continue;
					}

					// add the activity to our array.
					if ( $old_value != $new_value ) {
						$activity['single'][ $key ]['from'] = $old_value;
						$activity['single'][ $key ]['to']   = $new_value;
					}
				}

				/*
				 * check the array fields
				 */
				if ( is_array( $new_value ) && '_upstream_project_client_users' !== $key ) {

					// deleted from frontend.
					if ( isset( $this->posted['frontend'] ) && 'delete' === sanitize_text_field( $this->posted['frontend'] ) ) {

						if ( $new_value && is_array( $new_value ) && isset( $new_value[0] ) ) {
							// sanitize new value.
							$nv        = array();
							$nv[]      = \array_map( 'sanitize_text_field', $new_value[0] );
							$new_value = $nv;

							foreach ( $old_value as $old_old => $old_item ) {
								// if the old id is not in the new items, we have deleted it.
								if ( isset( $new_value[0]['id'] ) && $new_value[0]['id'] == $old_item['id'] ) {
									$activity['group'][ $key ]['remove'][] = $old_item;
								}
							}
						}
					}

					// deleted from admin.
					if ( isset( $this->posted['admin'] ) && sanitize_text_field( $this->posted['admin'] ) === true ) {
						if ( $old_value ) {
							foreach ( $old_value as $old_index => $old_item ) {
								// if the old id is not in the new items, we have deleted it.
								if ( isset( $old_item['id'] ) && ! $this->in_array_r( $old_item['id'], $new_value ) ) {
									$activity['group'][ $key ]['remove'][] = $old_item;
								}
							}
						}
					}

					// loop through each new item.
					foreach ( $new_value as $new_index => $new_item ) {

						// see if our new item matches any existing.
						$item_id       = isset( $new_item['id'] ) ? $new_item['id'] : null;
						$existing_item = upstream_project_item_by_id( $this->ID, $item_id );

						// if we are adding a new item.
						if ( ! $existing_item ) {
							// ignore if all fields are empty.
							if ( array_filter( $new_item ) ) {
								$activity['group'][ $key ]['add'][] = $new_item;
							}
						}

						if ( $existing_item ) {

							// loop through each new item field.
							foreach ( $new_item as $new_item_field_key => $new_item_field_val ) {

								// check for date fields.
								if ( $this->match( $new_item_field_key, array( 'date' ) ) ) {
									// convert date to timestamp.
									$new_item_field_val = upstream_timestamp_from_date( $new_item_field_val );
								}

								// we've added a new field.
								// existing item is NOT set.
								if (
									! isset( $existing_item[ $new_item_field_key ] )
									&& ! empty( $new_item_field_val ) ) {
									$activity['group'][ $key ][ $existing_item['id'] ][ $new_item_field_key ]['add'] = $new_item_field_val;
									continue;
								}

								// we've removed a field.
								// existing item is set.
								// new item is NOT set.
								if (
									( isset( $existing_item[ $new_item_field_key ] ) && ! isset( $existing_item[ $new_item_field_key ] ) )
									&& ! empty( $existing_item[ $new_item_field_key ] ) ) {
									$activity['group'][ $key ][ $existing_item['id'] ][ $new_item_field_key ]['remove'] = $existing_item[ $new_item_field_key ];
									continue;
								}

								// we've edited a field.
								if ( isset( $existing_item[ $new_item_field_key ] ) && $existing_item[ $new_item_field_key ] != $new_item_field_val ) {
									$activity['group'][ $key ][ $existing_item['id'] ][ $new_item_field_key ]['from'] = $existing_item[ $new_item_field_key ];
									$activity['group'][ $key ][ $existing_item['id'] ][ $new_item_field_key ]['to']   = $new_item_field_val;
								}
							}
						}
					}
				} // end is_array check.
			}
		}

		if ( empty( $activity ) ) {
			return;
		}

		$data[ $time ]['fields']  = $activity;
		$data[ $time ]['user_id'] = $user_id;

		$existing = get_post_meta( $this->ID, '_upstream_project_activity', true );
		if ( $existing ) {
			$update = ( $existing + $data );
		} else {
			$update = $data;
		}

		$updated = update_post_meta( $this->ID, '_upstream_project_activity', $update );
	}

	/**
	 * Helper function to find matching strings.
	 * $needle can be a string or an array with multiple strings.
	 *
	 * @param array  $haystack The array.
	 * @param string $needle The searched value.
	 */
	public function match( $haystack, $needle ) {
		if ( ! $needle ) {
			return;
		}

		// push single string into array for simplicity.
		if ( ! is_array( $needle ) ) {
			$needle = array( $needle );
		}

		foreach ( $needle as $string ) {
			if ( strpos( $haystack, $string ) !== false ) {
				return true;
			}
		}
	}

	/**
	 * Get a meta value
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta the meta field (without prefix).
	 *
	 * @return mixed
	 */
	public function get_meta( $meta ) {
		// to allow frontend use.
		if ( strpos( $meta, '_upstream_project_' ) !== false ) {
			$meta = str_replace( '_upstream_project_', '', $meta );
		}

		$result = get_post_meta( $this->ID, '_upstream_project_' . $meta, true );
		if ( ! $result ) {
			$result = null;
		}

		return $result;
	}

	/**
	 * In_array_r
	 *
	 * @param string $needle The searched value.
	 * @param array  $haystack The array.
	 * @param bool   $strict Set to true then the in_array() function will also check the types of the needle in the haystack.
	 *
	 * @return mixed
	 */
	public function in_array_r( $needle, $haystack, $strict = false ) {
		foreach ( $haystack as $item ) {
			if ( ( $strict ? $item === $needle : $item === $needle ) || ( is_array( $item ) && $this->in_array_r(
				$needle,
				$item,
				$strict
			) ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get activity
	 *
	 * @param int $post_id Post id.
	 */
	public function get_activity( $post_id ) {
		// set the post id.
		$this->ID = $post_id;

		// make the field names readable.
		$find    = array( '_', 'upstream' );
		$replace = array( ' ', '' );

		// get the activity data.
		$activity = $this->get_meta( '_upstream_project_activity' );

		if ( ! $activity ) {
			return;
		}

		$activity = array_reverse( $activity, true );

		$activity = array_slice( $activity, 0, $this->number_of_items(), true );

		// loop through each timestamp.
		foreach ( $activity as $time => $update ) {

			// get the day and time there was some activity.
			echo '<div class="activity-item">';
			echo '<h4>' . esc_html( upstream_format_date( $time ) . ' ' . upstream_format_time( $time ) ) . '</h4>';
			echo '<span class="user">' . esc_html( upstream_users_name( $update['user_id'] ) ) . ' (' . esc_html(
				upstream_user_item(
					$update['user_id'],
					'role'
				)
			) . ')</span>';

			/*
			 * single field
			 */
			if ( isset( $update['fields']['single'] ) ) {
				foreach ( $update['fields']['single'] as $field_id => $data ) {
					$single_name = ucwords( str_replace( $find, $replace, $field_id ) );

					if ( isset( $data['add'] ) ) {
						$the_val       = $this->format_fields( $field_id, $data['add'] );
						$single_output = sprintf(
							// translators: %s: field name.
							__( 'New: %s', 'upstream' ),
							$the_val
						);
					}

					if ( isset( $data['from'] ) ) {
						$from          = $this->format_fields( $field_id, $data['from'] );
						$to            = $this->format_fields( $field_id, $data['to'] );
						$single_output = sprintf(
							// translators: %1$s: date from.
							// translators: %2$s: date to.
							__( 'Edit: %1$s to %2$s', 'upstream' ),
							$from,
							$to
						);
					}
				}

				echo '<span class="item-name">' . esc_html( $single_name ) . '</span>';
				echo wp_kses_post( $single_output );
			}

			// group item.
			if ( isset( $update['fields']['group'] ) ) {
				foreach ( $update['fields']['group'] as $group_id => $data ) {
					$group_name = ucwords( str_replace( $find, $replace, $group_id ) );
					echo '<span class="item-name">' . esc_html( $group_name ) . '</span>';
					foreach ( $data as $item_id => $fields ) {

						// deleted an item.
						if ( 'remove' === $item_id ) {
							$item_removed = '';
							foreach ( $fields as $key => $item ) {
								// skip empty files.
								if ( ( isset( $item['file_id'] ) && '0' === $item['file_id'] ) && ( isset( $item['title'] ) && empty( $item['title'] ) ) ) {
									$group_name = '';
									continue;
								}

								if ( '_upstream_project_milestones' === $group_id ) {
									$title = $item['milestone'];
								} else {
									$title = $item['title'];
								}

								$item_removed .= '<span class="item">' . sprintf(
									// translators: %s: Deleted item name.
									__( 'Deleted: %s', 'upstream' ),
									'<span class="highlight">' . $title . '</span>'
								) . '</span>';
							}

							$group_output = $item_removed;
							echo wp_kses_post( $group_output );
						}

						/*
						 * add an item
						 */
						if ( 'add' === $item_id ) {
							$item_added = '';
							foreach ( $fields as $key => $item ) {
								// skip empty files.
								if ( ( isset( $item['file_id'] ) && '0' === $item['file_id'] ) && ( isset( $item['title'] ) && empty( $item['title'] ) ) ) {
									$group_name = '';
									continue;
								}

								if ( '_upstream_project_milestones' === $group_id ) {
									if ( isset( $item['milestone'] ) ) {
										$title = $item['milestone'];
									} elseif ( isset( $item['data']['milestone'] ) ) {
										$title = $item['data']['milestone'];
									}
								} else {
									if ( isset( $item['title'] ) ) {
										$title = $item['title'];
									} elseif ( isset( $item['data']['title'] ) ) {
										$title = $item['data']['title'];
									}
								}

								$item_added .= '<span class="item">' . sprintf(
									// translators: %s: New item name.
									__( 'New Item: %s', 'upstream' ),
									'<span class="highlight">' . $title . '</span>'
								) . '</span>';
							}

							$group_output = $item_added;
							echo wp_kses_post( $group_output );
						}

						/*
						 * edit an item
						 */
						if ( strlen( $item_id ) > 5 ) {
							foreach ( $fields as $field_id => $field_data ) {
								$field_name   = ucwords( str_replace( $find, $replace, $field_id ) );
								$field_output = '';
								if ( isset( $field_data['add'] ) ) {
									$item = upstream_project_item_by_id( $this->ID, $item_id );

									$the_val       = $this->format_fields( $field_id, $field_data['add'] );
									$field_output .= '<span class="item">' . sprintf(
										// translators: %1$s: Field name.
										// translators: %2$s: Field value.
										// translators: %3$s: Item title.
										__(
											'New: %1$s - %2$s on %3$s',
											'upstream'
										),
										$field_name,
										( is_array( $the_val ) ? json_encode( $the_val ) : $the_val ),
										'<span class="highlight">' . ( isset( $item['title'] ) ? $item['title'] : '' ) . '</span>'
									) . '</span>';
								}

								if ( isset( $field_data['from'] ) ) {
									$item = upstream_project_item_by_id( $this->ID, $item_id );
									$from = $this->format_fields( $field_id, $field_data['from'] );
									$to   = $this->format_fields( $field_id, $field_data['to'] );

									$field_output .= '<span class="item">' . sprintf(
										// translators: %1$s: Field name.
										// translators: %2$s: Data 'from' couter.
										// translators: %3$s: Data 'to' couter.
										// translators: %4$s: Item title.
										__(
											'Edit: %1$s from %2$s to %3$s on %4$s',
											'upstream'
										),
										$field_name,
										is_array( $from ) ? count( $from ) : $from,
										is_array( $to ) ? count( $to ) : $to,
										'<span class="highlight">' . ( isset( $item['title'] ) ? $item['title'] : '' ) . '</span>'
									) . '</span>';
								}

								$group_output = $field_output;
								echo wp_kses_post( $group_output );
							}
						}
					}
				}
			}

			echo '</div>';
		} // end items
	}

	/**
	 * Add activity
	 *
	 * @param int    $project_id Project id.
	 * @param string $meta_name  meta_name.
	 * @param array  $action     action.
	 * @param mixed  $item       item.
	 */
	public function add_activity( $project_id, $meta_name, $action, $item ) {
		// Update Project activity.
		$activity = (array) get_post_meta( $project_id, '_upstream_project_activity', true );

		$log = array(
			'fields'  => array(
				'group' => array(
					$meta_name => array(
						$action => array( $item ),
					),
				),
			),
			'user_id' => get_current_user_id(),
		);

		$now              = time();
		$activity[ $now ] = $log;

		update_post_meta( $project_id, '_upstream_project_activity', $activity );
	}

	/**
	 * Get activity
	 */
	public function number_of_items() {
		$number = isset( $_GET['activity_items'] ) ? intval( $_GET['activity_items'] ) : 5;
		$number = 'all' === $number ? 99999999 : $number;

		return intval( $number );
	}

	/**
	 * Format data fields.
	 *
	 * @param string $field_id Field name / id.
	 * @param string $val Field value.
	 */
	public function format_fields( $field_id, $val ) {
		$field   = str_replace( '_upstream_project_', '', $field_id );
		$the_val = '';

		if ( strpos( $field, 'date' ) !== false ) {
			$field = 'date';
		}

		switch ( $field ) {
			case 'client_users':
				$prefix = '';
				$users  = '';
				foreach ( $val as $index => $user_id ) {
					$users .= $prefix . '' . upstream_users_name( $user_id ) . '';
					$prefix = '& ';
				}
				$the_val = $users;
				break;

			case 'client':
				$the_val = get_the_title( $val );
				break;

			case 'start':
			case 'end':
			case 'date':
				$the_val = upstream_format_date( $val );
				break;

			case 'assigned_to':
			case 'owner':
				if ( ! is_array( $val ) ) {
					$val = (array) $val;
				}

				$val     = array_unique( array_filter( $val ) );
				$the_val = array();
				foreach ( $val as $user_id ) {
					$user_id = (int) $user_id;
					if ( $user_id > 0 ) {
						$the_val[] = upstream_users_name( $user_id );
					}
				}

				$the_val = implode( ', ', $the_val );

				break;

			case 'milestone':
				$item    = upstream_project_item_by_id( $this->ID, $val );
				$the_val = isset( $item['title'] ) ? $item['title'] : $item['milestone'];
				break;

			default:
				$the_val = $val;
				break;
		}

		$the_val = empty( $the_val ) ? '(' . __( 'none', 'upstream' ) . ')' : $the_val;

		return $the_val;
	}
}
