<?php
/**
 * UpStream Admin
 *
 * @class    UpStream_Admin
 * @author   UpStream
 * @category Admin
 * @package  UpStream/Admin
 * @version  1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UpStream_Admin class.
 */
class UpStream_Admin {

	/**
	 * Framework
	 *
	 * @var \Allex\Core
	 */
	protected $framework;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'init', array( $this, 'init' ), 13 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_filter( 'ajax_query_attachments_args', array( $this, 'filter_user_attachments' ), 10, 1 );
		add_action( 'admin_menu', array( $this, 'limit_up_stream_user_access' ) );

		add_action( 'show_user_profile', array( $this, 'render_additional_user_fields' ), 10, 1 );
		add_action( 'edit_user_profile', array( $this, 'render_additional_user_fields' ), 10, 1 );
		add_action( 'personal_options_update', array( $this, 'save_additional_user_fields' ), 10, 1 );
		add_action( 'edit_user_profile_update', array( $this, 'save_additional_user_fields' ), 10, 1 );

		global $pagenow;
		if ( 'edit-comments.php' === $pagenow ) {
			add_filter( 'comment_status_links', array( $this, 'comment_status_links' ), 10, 1 );
			add_action( 'pre_get_comments', array( $this, 'pre_get_comments' ), 10, 1 );
		}

		add_action(
			'wp_ajax_upstream:project.get_all_items_comments',
			array( 'UpStream_Metaboxes_Projects', 'fetch_all_items_comments' )
		);

		add_action( 'cmb2_render_up_timestamp', array( $this, 'render_cmb2_timestamp_field' ), 10, 5 );
		add_action( 'cmb2_sanitize_up_timestamp', array( $this, 'sanitize_cmb2_timestamp_field' ), 10, 5 );

		add_action( 'cmb2_render_up_button', array( $this, 'render_cmb2_button_field' ), 10, 5 );
		add_action( 'cmb2_sanitize_up_button', array( $this, 'sanitize_cmb2_button_field' ), 10, 5 );

		add_action( 'cmb2_render_up_buttonsgroup', array( $this, 'render_cmb2_buttons_group_field' ), 10, 5 );
		add_action( 'cmb2_sanitize_up_buttonsgroup', array( $this, 'sanitize_cmb2_buttons_group_field' ), 10, 5 );

		add_filter(
			'cmb2_override_option_get_upstream_general',
			array( $this, 'filter_override_option_get_upstream_general' ),
			10,
			3
		);

		$this->framework = UpStream::instance()->get_container()['framework'];

		add_action( 'wp_ajax_upstream.milestone-edit.editmenuorder', array( $this, 'edit_menu_order' ) );
		add_action( 'wp_ajax_upstream.task-edit.gettaskpercent', array( $this, 'get_task_percent' ) );
		add_action( 'wp_ajax_upstream.task-edit.gettaskstatus', array( $this, 'get_task_status' ) );
	}

	/**
	 * Edit Menu Order
	 *
	 * @since   1.24.5
	 * @static
	 */
	public function edit_menu_order() {
		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();
		$cur_post     = array(
			'ID'         => intval( $request_data['post_id'] ),
			'menu_order' => intval( $request_data['item_val'] ),
		);
		wp_update_post( $cur_post );

		return 'success';
	}

	/**
	 * Get Task Percent
	 *
	 * @since   1.24.5
	 * @static
	 */
	public function get_task_percent() {
		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();
		$task_id      = sanitize_text_field( $request_data['task_id'] );
		$cur_per      = intval( $request_data['cur_per'] );
		$option       = get_option( 'upstream_tasks' );
		$statuses     = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( $statuses ) {
			foreach ( $statuses as $status ) {
				if ( $status['id'] == $task_id ) {
					if ( ! empty( $status['percent'] ) && (int) $status['percent'] > $cur_per ) {
						echo (int) $status['percent'];
						exit;
					} else {
						echo (int) $cur_per;
						exit;
					}
				}
			}
		}
		return 0;
	}

	/**
	 * Get Task Status
	 *
	 * @since   1.24.5
	 * @static
	 */
	public function get_task_status() {
		$request_data = isset( $_REQUEST ) ? wp_unslash( $_REQUEST ) : array();
		$task_percent = (int) sanitize_text_field( $request_data['task_percent'] );

		$option     = get_option( 'upstream_tasks' );
		$statuses   = isset( $option['statuses'] ) ? $option['statuses'] : '';
		$sort_arr   = array();
		$sel_status = '';

		if ( $statuses ) {
			foreach ( $statuses as $status ) {
				$sort_arr[ $status['id'] ] = $status['percent'];
				if ( '100' == $task_percent && '100' === $status['percent'] ) {
					echo esc_attr( $status['id'] );
					exit;
				}
			}
		}
		asort( $sort_arr );
		if ( $sort_arr ) {
			foreach ( $sort_arr as $id => $percent ) {
				if ( $percent > $task_percent ) {
					echo esc_attr( $sel_status );
					exit;
				}
				$sel_status = $id;
			}
		}
		return 0;
	}

	/**
	 * Filter comments for Comments.php page.
	 *
	 * @param array $query Query args array.
	 *
	 * @since   1.13.0
	 * @static
	 */
	public static function pre_get_comments( $query ) {
		if ( ! upstream_is_user_either_manager_or_admin() ) {
			$user = wp_get_current_user();

			if ( in_array( 'upstream_user', $user->roles ) || in_array( 'upstream_client_user', $user->roles ) ) {
				// Limit comments visibility to projects user is participating in.
				$allowed_projects              = upstream_get_users_projects( $user );
				$query->query_vars['post__in'] = array_keys( $allowed_projects );

				$user_can_moderate_comments = user_can( $user, 'moderate_comments' );
				$user_can_delete_comments   = user_can( $user, 'delete_project_discussion' );

				$query->query_vars['status'] = array( 'approve' );

				if ( $user_can_moderate_comments ) {
					$query->query_vars['status'][] = 'hold';
				} elseif ( empty( $allowed_projects ) ) {
					$query->query_vars['post__in'] = -1;
				}
			} else {
				// Hide Projects comments from other user types.
				$projects = get_posts(
					array(
						'post_type'      => 'project',
						'post_status'    => 'any',
						'posts_per_page' => -1,
					)
				);

				$ids = array();
				foreach ( $projects as $project ) {
					$ids[] = $project->ID;
				}

				$query->query_vars['post__not_in'] = $ids;
			}
		}
	}

	/**
	 * Set up WP-Table filters links.
	 *
	 * @param array $links Associative array of table filters.
	 *
	 * @return  array   $links
	 * @since   1.13.0
	 * @static
	 */
	public static function comment_status_links( $links ) {
		if ( ! upstream_is_user_either_manager_or_admin() ) {
			$user = wp_get_current_user();

			if ( in_array( 'upstream_user', $user->roles ) || in_array( 'upstream_client_user', $user->roles ) ) {
				$user_can_moderate_comments = user_can( $user, 'moderate_comments' );
				$user_can_delete_comments   = user_can( $user, 'delete_project_discussion' );

				if ( ! $user_can_moderate_comments ) {
					unset( $links['moderated'], $links['approved'], $links['spam'] );

					if ( ! $user_can_delete_comments ) {
						unset( $links['trash'] );
					}
				}

				$projects = upstream_get_users_projects( $user );

				$comments_query_args = array(
					'post_type' => 'project',
					'post__in'  => array_keys( $projects ),
					'count'     => true,
				);

				$comments_count      = new stdClass();
				$comments_count->all = get_comments( $comments_query_args );

				$links['all'] = preg_replace(
					'/<span class="all-count">\d+<\/span>/',
					'<span class="all-count">' . $comments_count->all . '</span>',
					$links['all']
				);

				if ( isset( $links['moderated'] ) ) {
					$comments_count->approved = get_comments(
						array_merge(
							$comments_query_args,
							array( 'status' => 'approve' )
						)
					);

					$links['approved'] = preg_replace(
						'/<span class="approved-count">\d+<\/span>/',
						'<span class="approved-count">' . $comments_count->approved . '</span>',
						$links['approved']
					);

					$comments_count->pending = get_comments( array_merge( $comments_query_args, array( 'status' => 'hold' ) ) );

					$links['moderated'] = preg_replace(
						'/<span class="pending-count">\d+<\/span>/',
						'<span class="pending-count">' . $comments_count->pending . '</span>',
						$links['moderated']
					);
				}

				if ( isset( $links['trash'] ) ) {
					$comments_count->trash = get_comments( array_merge( $comments_query_args, array( 'status' => 'trash' ) ) );

					$links['trash'] = preg_replace(
						'/<span class="trash-count">\d+<\/span>/',
						'<span class="trash-count">' . $comments_count->trash . '</span>',
						$links['trash']
					);
				}
			} else {
				$projects = get_posts(
					array(
						'post_type'      => 'project',
						'posts_per_page' => -1,
					)
				);

				$projects_ids = array();
				foreach ( $projects as $project ) {
					$projects_ids[] = $project->ID;
				}

				$comments_query_args = array(
					'post__not_in' => $projects_ids,
					'count'        => true,
				);

				if ( isset( $links['all'] ) ) {
					$count        = get_comments( $comments_query_args );
					$links['all'] = preg_replace(
						'/<span class="all-count">\d+<\/span>/',
						'<span class="all-count">' . $count . '</span>',
						$links['all']
					);
				}

				if ( isset( $links['moderated'] ) ) {
					$count              = get_comments( array_merge( $comments_query_args, array( 'status' => 'hold' ) ) );
					$links['moderated'] = preg_replace(
						'/<span class="pending-count">\d+<\/span>/',
						'<span class="pending-count">' . $count . '</span>',
						$links['moderated']
					);
				}

				if ( isset( $links['approved'] ) ) {
					$count             = get_comments( array_merge( $comments_query_args, array( 'status' => 'approve' ) ) );
					$links['approved'] = preg_replace(
						'/<span class="approved-count">\d+<\/span>/',
						'<span class="approved-count">' . $count . '</span>',
						$links['approved']
					);
				}

				if ( isset( $links['spam'] ) ) {
					$count         = get_comments( array_merge( $comments_query_args, array( 'status' => 'spam' ) ) );
					$links['spam'] = preg_replace(
						'/<span class="spam-count">\d+<\/span>/',
						'<span class="spam-count">' . $count . '</span>',
						$links['spam']
					);
				}

				if ( isset( $links['trash'] ) ) {
					$count          = get_comments( array_merge( $comments_query_args, array( 'status' => 'trash' ) ) );
					$links['trash'] = preg_replace(
						'/<span class="trash-count">\d+<\/span>/',
						'<span class="trash-count">' . $count . '</span>',
						$links['trash']
					);
				}
			}
		}

		return $links;
	}

	/**
	 * Render a button
	 *
	 * @param \CMB2_Field $field       The current CMB2_Field object.
	 * @param string      $value       The field value passed through the escaping filter.
	 * @param mixed       $object_id   The object id.
	 * @param string      $object_type The type of object being handled.
	 * @param \CMB2_Types $field_type  Instance of the correspondent CMB2_Types object.
	 *
	 * @since   1.15.1
	 * @static
	 */
	public static function render_cmb2_buttons_group_field( $field, $value, $object_id, $object_type, $field_type ) {
		$count     = (int) $field->args['count'];
		$selectors = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$id          = $field->args['id'] . '_' . $i;
			$selectors[] = '#' . $id;

			printf(
				'<button class="%s" id="%s" data-nonce="%s" data-slug="%s">%s</button>',
				isset( $field->args['class'] ) ? esc_attr( $field->args['class'] ) : 'button-secondary',
				esc_attr( $id ),
				esc_attr( $field->args['nonce'] ),
				esc_attr( $field->args['slugs'][ $i ] ),
				esc_html( $field->args['labels'][ $i ] )
			);
		}

		$selector = implode( ', ', $selectors );

		?>

		<script type="text/javascript">
			jQuery("<?php echo esc_html( $selector ); ?>").on( "click", function( event ) {
				event.preventDefault(); 
				<?php echo esc_js( $field->args['onclick'] ); ?> 
			} );
		</script>';

		<?php

		if ( isset( $field->args['desc'] ) ) {
			?>
			<p class="cmb2-metabox-description"><?php echo esc_html( $field->args['desc'] ); ?></p>
			<?php
		}
	}

	/**
	 * Ensure 'up_button' fills in missing button on newer CMB2.
	 *
	 * @param null            $override_value Sanitization override value to return.
	 * @param mixed           $value         The actual field value.
	 * @param mixed           $object_id      The object id.
	 * @param string          $object_type    The type of object being handled.
	 * @param \CMB2_Sanitizer $sanitizer     Sanitizer's instance.
	 *
	 * @return  mixed
	 * @since   1.15.1
	 * @static
	 */
	public static function sanitize_cmb2_buttons_group_field( $override_value, $value, $object_id, $object_type, $sanitizer ) {     }

	/**
	 * Render a button
	 *
	 * @param \CMB2_Field $field      The current CMB2_Field object.
	 * @param string      $value      The field value passed through the escaping filter.
	 * @param mixed       $object_id   The object id.
	 * @param string      $object_type The type of object being handled.
	 * @param \CMB2_Types $field_type  Instance of the correspondent CMB2_Types object.
	 *
	 * @since   1.15.1
	 * @static
	 */
	public static function render_cmb2_button_field( $field, $value, $object_id, $object_type, $field_type ) {
		printf(
			'<button class="%s" id="%s" data-nonce="%s">%s</button>',
			isset( $field->args['class'] ) ? esc_attr( $field->args['class'] ) : 'button-secondary',
			esc_attr( $field->args['id'] ),
			esc_attr( $field->args['nonce'] ),
			esc_html( $field->args['label'] )
		);

		if ( isset( $field->args['desc'] ) ) {
			?>
			<p class="cmb2-metabox-description"><?php echo esc_html( $field->args['desc'] ); ?></p>
			<?php
		}

		$selector = '#' . $field->_id();

		?>

		<script>
			jQuery("<?php echo esc_html( $selector ); ?>").on( "click", function( event ) {
				event.preventDefault();
				<?php echo esc_js( $field->args['onclick'] ); ?>
			} );
		</script>';

		<?php
	}

	/**
	 * Ensure 'up_button' fills in missing button on newer CMB2.
	 *
	 * @param null            $override_value Sanitization override value to return.
	 * @param mixed           $value         The actual field value.
	 * @param mixed           $object_id      The object id.
	 * @param string          $object_type    The type of object being handled.
	 * @param \CMB2_Sanitizer $sanitizer     Sanitizer's instance.
	 *
	 * @return  mixed
	 * @since   1.15.1
	 * @static
	 */
	public static function sanitize_cmb2_button_field( $override_value, $value, $object_id, $object_type, $sanitizer ) {   }

	/**
	 * Render a modified 'text_date_timestamp' that will always use
	 * its date's time being as 12:00:00 AM.
	 *
	 * @param \CMB2_Field $field      The current CMB2_Field object.
	 * @param string      $value      The field value passed through the escaping filter.
	 * @param mixed       $object_id   The object id.
	 * @param string      $object_type The type of object being handled.
	 * @param \CMB2_Types $field_type  Instance of the correspondent CMB2_Types object.
	 *
	 * @since   1.15.1
	 * @static
	 */
	public static function render_cmb2_timestamp_field( $field, $value, $object_id, $object_type, $field_type ) {
		$allowed_html_tags = array(
			'input' => array(
				'type'            => array(),
				'class'           => array(),
				'name'            => array(),
				'id'              => array(),
				'value'           => array(),
				'data-hash'       => array(),
				'data-disabled'   => array(),
				'data-datepicker' => array(),
				'data-owner'      => array(),
			),
		);

		echo wp_kses( $field_type->text_date_timestamp(), $allowed_html_tags );
	}

	/**
	 * Ensure 'up_timestamp' fields date's time are set to 12:00:00 AM before it is stored AS GMT/UTC.
	 *
	 * @param null            $override_value Sanitization override value to return.
	 * @param mixed           $value         The actual field value.
	 * @param mixed           $object_id      The object id.
	 * @param string          $field_args    The type of object being handled.
	 * @param \CMB2_Sanitizer $sanitizer     Sanitizer's instance.
	 *
	 * @return  mixed
	 * @since   1.15.1
	 * @static
	 */
	public static function sanitize_cmb2_timestamp_field( $override_value, $value, $object_id, $field_args, $sanitizer ) {
		$value = trim( (string) $value );

		if ( strlen( $value ) > 0 ) {
			try {
				$date = DateTime::createFromFormat( $field_args['date_format'], $value );

				if ( false !== $date ) {
					$date->setTime( 0, 0, 0 );
					$value          = (string) $date->format( 'U' );
					$override_value = $value;
				} else {
					$value = '';
				}
			} catch ( \Exception $e ) {
				$value = '';
			}
		}

		return $value;
	}

	/**
	 * Escape Cmb2 Timestamp Field
	 *
	 * @param  mixed $value Value.
	 * @param  mixed $args Args.
	 * @param  mixed $field Field.
	 */
	public static function escape_cmb2_timestamp_field( $value, $args, $field ) {
		$value = (int) $value;
		if ( $value > 0 ) {
			$date = new \DateTime( 'now' );
			$date->setTimestamp( $value );

			$value = $date->format( $args['date_format'] );
		}

		return $value;
	}

	/**
	 * Save Additional User Fields
	 *
	 * @param  mixed $user_id User Id.
	 */
	public static function save_additional_user_fields( $user_id ) {
		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$nonce     = isset( $post_data['_wpnonce'] ) ? $post_data['_wpnonce'] : null;

		if ( ! current_user_can( 'edit_user', $user_id ) || ! isset( $post_data['upstream'] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, 'update-user_' . $user_id ) ) {
			return;
		}

		$crn = isset( $post_data['upstream']['comment_replies_notification'] ) ? sanitize_text_field( $post_data['upstream']['comment_replies_notification'] ) : '';

		if ( $crn ) {
			$receive_notifications = 'no' !== $crn;
			update_user_meta( $user_id, 'upstream_comment_replies_notification', $receive_notifications ? 'yes' : 'no' );
			unset( $receive_notifications );
		}
	}

	/**
	 * Render Additional User Fields
	 *
	 * @param  mixed $user User.
	 * @return void
	 */
	public static function render_additional_user_fields( $user ) {
		$receive_notifications = upstream_user_can_receive_comment_replies_notification( $user->ID );
		?>
		<h2><?php esc_html_e( 'UpStream', 'upstream' ); ?></h2>
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="upstream_comment_reply_notifications">
					<?php
					esc_html_e(
						'Comment reply notifications',
						'upstream'
					);
					?>
					</label>
				</th>
				<td>
					<div>
						<label>
							<?php esc_html_e( 'Yes', 'upstream' ); ?>
							<input type="radio" name="upstream[comment_replies_notification]"
								value="1"<?php echo $receive_notifications ? ' checked' : ''; ?>>
						</label>
						<label>
							<?php esc_html_e( 'No', 'upstream' ); ?>
							<input type="radio" name="upstream[comment_replies_notification]"
								value="no"<?php echo $receive_notifications ? '' : ' checked'; ?>>
						</label>
					</div>
					<p class="description">
					<?php
					printf(
						// translators: %s: upstream_project_label_plural.
						esc_html__( 'Whether to be notified when someone reply to your comments within %s.', 'upstream' ),
						esc_html( upstream_project_label_plural( true ) )
					);
					?>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Create id for newly added project/bugs/tasks statuses.
	 * This method is called right before field data is saved to db.
	 *
	 * @param array       $value Array of the new data set.
	 * @param array       $args  Field arguments.
	 * @param \CMB2_Field $field The field object.
	 *
	 * @return  array           $value
	 * @since   1.17.0
	 * @static
	 */
	public static function on_before_save( $value, $args, $field ) {
		if ( is_array( $value ) ) {
			$value       = self::create_missing_ids_in_set( $value );
			$value       = self::create_missing_colors_in_set( $value );
			$i           = 0;
			$count_value = is_array( $value ) ? count( $value ) : 0;

			while ( $i < $count_value ) {
				if ( ! isset( $value[ $i ]['name'] ) ) {
					array_splice( $value, $i, 1 );
				} else {
					$i++;
				}
			}
		}

		return $value;
	}

	/**
	 * Create missing id in a rowset.
	 *
	 * @param array $rowset Data array.
	 *
	 * @return  array
	 * @since   1.17.0
	 * @static
	 */
	public static function create_missing_ids_in_set( $rowset ) {
		if ( ! is_array( $rowset ) ) {
			return false;
		}

		if ( count( $rowset ) > 0 ) {
			$indexes_missing_id = array();
			$ids_map            = array();

			foreach ( $rowset as $row_index => $row ) {
				if ( ! isset( $row['id'] )
				|| empty( $row['id'] )
				) {
					$indexes_missing_id[] = $row_index;
				} else {
					$ids_map[ $row['id'] ] = $row_index;
				}
			}

			if ( count( $indexes_missing_id ) > 0 ) {
				$new_ids_length     = 5;
				$new_ids_chars_pool = 'abcdefghijklmnopqrstuvwxyz0123456789';

				foreach ( $indexes_missing_id as $row_index ) {
					do {
						$id = upstream_generate_random_string( $new_ids_length, $new_ids_chars_pool );
					} while ( isset( $ids_map[ $id ] ) );

					$rowset[ $row_index ]['id'] = $id;
					$ids_map[ $id ]             = $row_index;
				}
			}
		}

		return $rowset;
	}

	/**
	 * Create missing color in a rowset.
	 *
	 * @param array $rowset Data array.
	 *
	 * @return  array
	 * @since   2.0.0
	 * @static
	 */
	public static function create_missing_colors_in_set( $rowset ) {
		if ( ! is_array( $rowset ) ) {
			return false;
		}

		if ( count( $rowset ) > 0 ) {
			foreach ( $rowset as $row_index => $row ) {
				if ( ! isset( $row['color'] )
				|| empty( $row['color'] )
				|| '#' === $row['color']
				) {
					$rowset[ $row_index ]['color'] = '#F0F0F1';
				}
			}
		}

		return $rowset;
	}

	/**
	 * Init the dependencies.
	 */
	public function init() {
		do_action( 'alex_enable_module_upgrade', 'https://upstreamplugin.com/pricing/' );
	}

	/**
	 * Limit Up Stream User Access
	 *
	 * @return void
	 */
	public function limit_up_stream_user_access() {
		if ( empty( $_GET ) || ! is_admin() ) {
			return;
		}

		$user                   = wp_get_current_user();
		$user_is_up_stream_user = count(
			array_intersect(
				(array) $user->roles,
				array( 'administrator', 'upstream_manager' )
			)
		) === 0;

		if ( $user_is_up_stream_user ) {
			global $pagenow;

			$should_redirect = false;
			$get_data        = isset( $_GET ) ? wp_unslash( $_GET ) : array();

			// this is checked against a known list of post types later.
			$post_type            = isset( $get_data['post_type'] ) ? sanitize_text_field( $get_data['post_type'] ) : '';
			$is_post_type_project = 'project' === $post_type;

			if ( 'edit-tags.php' === $pagenow ) {
				if ( isset( $get_data['taxonomy'] )
					&& 'project_category' === sanitize_text_field( $get_data['taxonomy'] )
					&& $is_post_type_project
				) {
					$should_redirect = true;
				}
			} elseif ( 'post.php' === $pagenow
				&& $is_post_type_project
			) {
				$project_members_list = (array) get_post_meta( (int) $get_data['post'], '_upstream_project_members', true );
				// Since he's not and Administrator nor an UpStream Manager, we need to check if he's participating in the project.
				if ( ! in_array( $user->ID, $project_members_list ) ) {
					$should_redirect = true;
				}
			} elseif ( 'post-new.php' === $pagenow
				&& $is_post_type_project
			) {
				$should_redirect = true;
			} elseif ( 'edit.php' === $pagenow
				&& 'client' === $post_type
			) {
				$should_redirect = true;
			}

			if ( $should_redirect ) {
				// Redirect the user to the projects list page.
				// $pagenow = 'edit.php';.
				wp_redirect( admin_url( '/edit.php?post_type=project' ) );
				exit;
			}
		}
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// option pages.
		include_once 'class-upstream-admin-options.php';
		include_once 'options/option-functions.php';

		// metaboxes.
		include_once 'class-upstream-admin-metaboxes.php';
		include_once 'metaboxes/metabox-functions.php';

		include_once 'up-enqueues.php';
		include_once 'class-upstream-admin-projects-menu.php';
		include_once 'class-upstream-admin-project-columns.php';
		include_once 'class-upstream-admin-client-columns.php';
		include_once 'class-upstream-admin-pointers.php';
	}

	/**
	 * Adds one or more classes to the body tag in the dashboard.
	 *
	 * @param String $classes Current body classes.
	 *
	 * @return String          Altered body classes.
	 */
	public function admin_body_class( $classes ) {
		$screen = get_current_screen();

		if ( in_array(
			$screen->id,
			array(
				'client',
				'edit-client',
				'project',
				'edit-project',
				'edit-project_category',
				'project_page_tasks',
				'project_page_bugs',
				'toplevel_page_upstream_general',
				'upstream_page_upstream_bugs',
				'upstream_page_upstream_tasks',
				'upstream_page_upstream_milestones',
				'upstream_page_upstream_clients',
				'upstream_page_upstream_projects',
			)
		) ) {
			return "$classes upstream";
		}

		return $classes;
	}

	/**
	 * Only show current users media items
	 *
	 * @param  mixed $query Query.
	 */
	public function filter_user_attachments( $query = array() ) {
		$user  = wp_get_current_user();
		$roles = upstream_media_unrestricted_roles();

		// Get the user's role.
		$match = array_intersect( $user->roles, $roles );

		// If the user's has a role selected as unrestricted, we do not filter the attachments.
		if ( ! empty( $match ) ) {
			return $query;
		}

		// The user should only see its own attachments.
		if ( is_object( $user ) && isset( $user->ID ) && ! empty( $user->ID ) ) {
			$query['author'] = $user->ID;
		}

		return $query;
	}

	/**
	 * Override the media_comment_images option based on the current capabilities.
	 *
	 * @param string $test Test.
	 * @param mixed  $default Default.
	 * @param mixed  $instance Instance.
	 *
	 * @return array
	 */
	public function filter_override_option_get_upstream_general( $test, $default, $instance ) {
		// Identify roles that has the upstream_comment_images capability.
		$roles         = array_keys( get_editable_roles() );
		$capable_roles = array();
		foreach ( $roles as $role_id ) {
			$role = get_role( $role_id );
			if ( $role->has_cap( 'upstream_comment_images' ) ) {
				$capable_roles[] = $role_id;
			}
		}

		$options = get_option( 'upstream_general' );

		$options['media_comment_images'] = $capable_roles;

		return $options;
	}
}

return new UpStream_Admin();
