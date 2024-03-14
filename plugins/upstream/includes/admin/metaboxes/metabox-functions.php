<?php
/**
 * Handle metaboxes functions
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
======================================================================================
										METABOX FIELD VALIDATION
======================================================================================
*/

/*
 * CMB2 js validation for "required" fields
 * Uses js to validate CMB2 fields that have the 'data-validation' attribute set to 'required'
 */

/**
 * Documentation in the wiki:
 *
 * @link https://github.com/WebDevStudios/CMB2/wiki/Plugin-code-to-add-JS-validation-of-%22required%22-fields
 * @param  mixed $post_id Post Id.
 * @param  mixed $cmb Cmb.
 * @return void
 */
function upstream_form_do_js_validation( $post_id, $cmb ) {
	static $added = false;
	// Only add this to the page once (not for every metabox).
	if ( $added ) {
		return;
	}
	$added = true; ?>

	<script type="text/javascript">

		jQuery(document).ready(function ($) {

			$form = $(document.getElementById('post'));
			$htmlbody = $('html, body');
			$toValidate = $('[data-validation]');

			if (!$toValidate.length) {
				return;
			}

			function checkValidation (evt) {

				var labels = [];
				var $first_error_row = null;
				var $row = null;

				function add_required ($row, $this) {

					setTimeout(function () {
						$row.css({
							'box-shadow': '0 0 2px #dc3232',
							'border-right': '4px solid #dc3232'
						});
						$this.css({'border-color': '#dc3232'});
					}, 500);

					$first_error_row = $first_error_row ? $first_error_row : $this;

					// if it has been deleted dynamically
					if ($(document).find($first_error_row).length == 0) {
						$first_error_row = null;
					}

				}

				function remove_required ($row, $this) {
					$row.css({background: ''});
				}

				$toValidate.each(function () {

					var $this = $(this);
					var val = $this.val();

					if ($this.parents('.cmb-repeatable-grouping')) {
						$item = $this.parents('.cmb-repeatable-grouping');
						$row = $item.find('.cmb-group-title');

						if ($item.is(':hidden')) {
							return true;
						}
					}

					if ($this.is('[type="button"]') || $this.is('.cmb2-upload-file-id')) {
						return true;
					}

					if ('required' === $this.data('validation')) {

						if ($row.is('.cmb-type-file-list')) {
							var has_LIs = $row.find('ul.cmb-attach-list li').length > 0;
							if (!has_LIs) {
								add_required($row, $this);
							} else {
								remove_required($row, $this);
							}
						} else {
							if (!val) {
								add_required($row, $this);
							} else {
								remove_required($row, $this);
							}
						}
					}

				});

				if ($first_error_row) {
					evt.preventDefault();

					<?php
					printf( 'let notice = "%s";', esc_html__( 'Missing some required fields', 'upstream' ) );
					?>

					$('#major-publishing-actions .notice').remove();
					$('#major-publishing-actions').append($('<div class="notice notice-error">' + notice + '</div>').hide().fadeIn(500));

					$htmlbody.delay(500).animate({
						scrollTop: ($first_error_row.offset().top - 100)
					}, 500);
				} else {
					$form.find('input, textarea, button, select').prop({'disabled': false, 'readonly': false});
				}

			}

			$form.on('submit', checkValidation);

		});
	</script>

	<?php
}
add_action( 'cmb2_after_form', 'upstream_form_do_js_validation', 10, 2 );

/*
======================================================================================
										OVERVIEW
======================================================================================
*/

/**
 * Returns data for the overview section.
 *
 * @param  mixed $field_args Field Args.
 * @param  mixed $field Field.
 * @return void
 */
function upstream_output_overview_counts( $field_args, $field ) {
	$project_id            = $field->object_id ? (int) $field->object_id : upstream_post_id();
	$user_id               = (int) get_current_user_id();
	$item_type_meta_prefix = '_upstream_project_';
	$item_type             = str_replace( $item_type_meta_prefix, '', $field_args['id'] );

	$is_disabled = (string) get_post_meta( $project_id, $item_type_meta_prefix . 'disable_' . $item_type, true );
	if ( 'on' === $is_disabled ) {
		return;
	}

	$count_mine = 0;
	$count_open = 0;

	$counter = new Upstream_Counter( $project_id );

	$rowset = $counter->get_items_of_type( $item_type );

	if ( 'milestones' === $item_type ) {
		if ( ! empty( $rowset ) ) {
			foreach ( $rowset as $row ) {
				if ( isset( $row['assigned_to'] ) ) {
					$assigned_to = $row['assigned_to'];

					if (
						( is_array( $assigned_to ) && in_array( $user_id, $assigned_to ) )
						|| ( ! is_array( $assigned_to ) && (int) $row['assigned_to'] === $user_id )
					) {
						$count_mine++;
					}
				}
			}
		}

		$count_open = count( (array) $rowset );
	} elseif ( is_array( $rowset ) && count( $rowset ) > 0 ) {
		$options  = get_option( 'upstream_' . $item_type );
		$statuses = isset( $options['statuses'] ) ? $options['statuses'] : array();

		$types = array();
		foreach ( $statuses as $s ) {
			if ( isset( $s['id'] ) && isset( $s['type'] ) ) {
				$types[ $s['id'] ] = $s['type'];
			}
		}

		$statuses = $types;

		foreach ( $rowset as $row ) {
			if ( isset( $row['assigned_to'] ) ) {
				$assigned_to = $row['assigned_to'];

				if (
					( is_array( $assigned_to ) && in_array( $user_id, $assigned_to ) )
					|| ( ! is_array( $assigned_to ) && (int) $row['assigned_to'] === $user_id )
				) {
					$count_mine++;
				}
			}

			if (
				! isset( $row['status'] )
				|| empty( $row['status'] )
				|| (
					isset( $statuses[ $row['status'] ] ) && 'open' === $statuses[ $row['status'] ]
				)
			) {
				$count_open++;
			}
		}
	}
	?>
	<div class="counts <?php echo esc_attr( $item_type ); ?>">
		<h4>
			<span class="count open total"><?php echo esc_html( $count_open ); ?></span> <?php esc_html_e( 'Open', 'upstream' ); ?>
		</h4>
		<h4>
			<span class="count open<?php echo esc_attr( $count_mine > 0 ? ' mine' : '' ); ?>"><?php echo (int) $count_mine; ?></span> 
			<?php
			esc_html_e(
				'Mine',
				'upstream'
			);
			?>
		</h4>
	</div>
	<?php
}

/*
======================================================================================
										ACTIVITY
======================================================================================
*/

/**
 * Returns the buttons for the activity section
 *
 * @param  mixed $field_args Field Args.
 * @param  mixed $field Field.
 */
function upstream_activity_buttons( $field_args, $field ) {
	$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

	// active class.
	$class = ' button-primary';
	$_10   = '';
	$_20   = '';
	$_all  = '';

	if ( ! isset( $get_data['activity_items'] ) || ( isset( $get_data['activity_items'] ) && sanitize_text_field( $get_data['activity_items'] ) == '10' ) ) {
		$_10 = $class;
	}
	if ( isset( $get_data['activity_items'] ) && sanitize_text_field( $get_data['activity_items'] ) == '20' ) {
		$_20 = $class;
	}
	if ( isset( $get_data['activity_items'] ) && sanitize_text_field( $get_data['activity_items'] ) == 'all' ) {
		$_all = $class;
	}

	$edit_buttons  = '<div class="button-wrap">';
	$edit_buttons .= '<a class="button button-small' . esc_attr( $_10 ) . '" href="' . esc_url(
		add_query_arg(
			'activity_items',
			'10'
		)
	) . '" >' . __( 'Last 10', 'upstream' ) . '</a> ';
	$edit_buttons .= '<a class="button button-small' . esc_attr( $_20 ) . '" href="' . esc_url(
		add_query_arg(
			'activity_items',
			'20'
		)
	) . '" >' . __( 'Last 20', 'upstream' ) . '</a> ';
	$edit_buttons .= '<a class="button button-small' . esc_attr( $_all ) . '" href="' . esc_url(
		add_query_arg(
			'activity_items',
			'all'
		)
	) . '" >' . __( 'View All', 'upstream' ) . '</a> ';
	$edit_buttons .= '</div>';

	return $edit_buttons;
}

/**
 * Returns data for the activity section.
 *
 * @param  mixed $field_args Field Args.
 * @param  mixed $field Field.
 */
function upstream_output_activity( $field_args, $field ) {
	$activity = \UpStream\Factory::get_activity();

	return $activity->get_activity( $field->object_id );
}

/*
======================================================================================
										MILESTONES
======================================================================================
*/

/**
 * Outputs some hidden data in the metabox so we can use it dynamically
 *
 * @param  mixed $field_args Field Args.
 * @param  mixed $field Field.
 * @return void
 */
function upstream_admin_output_milestone_hidden_data( $field_args, $field ) {
	global $post;

	// get the current saved milestones.
	$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $post->ID );

	echo '<ul class="hidden milestones">';
	foreach ( $milestones as $milestone ) {
		$milestone = \UpStream\Factory::get_milestone( $milestone );

		echo '<li>
            <span class="title">' . esc_html( $milestone->getName() ) . '</span>
            <span class="color">' . esc_html( $milestone->getColor() ) . '</span>';

		$progress = $milestone->getProgress();
		if ( ! empty( $progress ) ) {
			// if we have progress.
			echo '<span class="m-progress">' . esc_html( $progress ) . '</span>';
		}
		echo '</li>';

		unset( $milestone );
	}
	echo '</ul>';
}

/**
 * Returns the current saved milestones.
 * For use in dropdowns.
 *
 * @param object $field Field.
 *
 * @return array
 */
function upstream_admin_get_project_milestones( $field ) {
	$project_milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $field->object_id );

	$data = array();

	if ( count( $project_milestones ) > 0 ) {
		foreach ( $project_milestones as $milestone ) {
			$milestone = \UpStream\Factory::get_milestone( $milestone );

			$data[ $milestone->getId() ] = $milestone->getName();

			unset( $milestone );
		}
	}

	return $data;
}

/*
======================================================================================
										TASKS
======================================================================================
*/

/**
 * Returns the task status names as set in the options.
 * Used in the Status dropdown within a task.
 *
 * @return array
 */
function upstream_admin_get_task_statuses() {
	$option   = get_option( 'upstream_tasks' );
	$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
	$array    = array();
	if ( $statuses ) {
		foreach ( $statuses as $status ) {
			if ( isset( $status['name'] ) ) {
				$array[ $status['id'] ] = $status['name'];
			}
		}
	}

	return $array;
}

/**
 * Outputs some hidden data so we can use it dynamically
 */
function upstream_admin_output_task_hidden_data() {
	$option  = get_option( 'upstream_tasks' );
	$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
	if ( $statuses ) {
		echo '<ul class="hidden statuses">';
		foreach ( $statuses as $status ) {
			echo '<li>
                <span class="status">' . esc_html( $status['name'] ) . '</span>
                <span class="color">' . esc_html( isset( $status['color'] ) ? $status['color'] : '' ) . '</span>
                </li>';
		}
		echo '</ul>';
	}
}


/*
======================================================================================
										BUGS
======================================================================================
*/

/**
 * Returns the bug status names as set in the options.
 * Used in the Status dropdown within a bug.
 */
function upstream_admin_get_bug_statuses() {
	$option   = get_option( 'upstream_bugs' );
	$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
	$array    = array();
	if ( $statuses ) {
		foreach ( $statuses as $status ) {
			if ( isset( $status['name'] ) ) {
				$array[ $status['id'] ] = $status['name'];
			}
		}
	}

	return $array;
}

/**
 * Returns the bug severity names as set in the options.
 * Used in the Severity dropdown within a bug.
 */
function upstream_admin_get_bug_severities() {
	$option     = get_option( 'upstream_bugs' );
	$severities = isset( $option['severities'] ) ? $option['severities'] : '';
	$array      = array();
	if ( $severities ) {
		foreach ( $severities as $severity ) {
			if ( isset( $severity['name'] ) ) {
				$array[ $severity['id'] ] = $severity['name'];
			}
		}
	}

	return $array;
}

/**
 * Outputs some hidden data in the metabox so we can use it dynamically
 */
function upstream_admin_output_bug_hidden_data() {
	$option     = get_option( 'upstream_bugs' );
	$statuses   = isset( $option['statuses'] ) ? $option['statuses'] : '';
	$severities = isset( $option['severities'] ) ? $option['severities'] : '';
	if ( $statuses ) {
		echo '<ul class="hidden statuses">';
		foreach ( $statuses as $status ) {
			echo '<li>
                <span class="status">' . esc_html( $status['name'] ) . '</span>
                <span class="color">' . esc_html( $status['color'] ) . '</span>
            </li>';
		}
		echo '</ul>';
	}
	if ( $severities ) {
		echo '<ul class="hidden severities">';
		foreach ( $severities as $severity ) {
			echo '<li>
                <span class="severity">' . esc_html( $severity['name'] ) . '</span>
                <span class="color">' . esc_html( $severity['color'] ) . '</span>
            </li>';
		}
		echo '</ul>';
	}
}

/*
======================================================================================
										DISCUSSION
======================================================================================
*/

/**
 * Upstream Render Comments Box
 *
 * @param  mixed $item_id Item Id.
 * @param  mixed $item_type Item Type.
 * @param  mixed $project_id Project Id.
 * @param  mixed $render_controls Render Controls.
 * @param  mixed $return_as_html Return As Html.
 * @return void
 */
function upstream_render_comments_box( $item_id = '', $item_type = 'project', $project_id = 0, $render_controls = true, $return_as_html = false ) {
	$project_id = (int) $project_id;
	if ( $project_id <= 0 ) {
		$project_id = upstream_post_id();
		if ( $project_id <= 0 ) {
			return;
		}
	}

	if ( is_object( $item_type ) ) {
		$item_type = 'project';
	}

	$item_type = trim( strtolower( $item_type ) );
	if (
		! in_array( $item_type, array( 'project', 'milestone', 'task', 'bug', 'file' ) )
		|| ( 'project' !== $item_type && empty( $item_id ) )
	) {
		return;
	}

	if ( 'project' === $item_type ) {
		$item_id = $project_id;
	}

	$rowset_users = get_users();
	$users        = array();
	foreach ( $rowset_users as $user ) {
		$users[ (int) $user->ID ] = (object) array(
			'id'     => (int) $user->ID,
			'name'   => $user->display_name,
			'avatar' => upstream_get_user_avatar_url( $user->ID ),
		);
	}
	unset( $rowset_users );

	$user                       = wp_get_current_user();
	$user_has_admin_capabilities = upstream_is_user_either_manager_or_admin();
	$user_can_comment            = ! $user_has_admin_capabilities ? user_can( $user, 'publish_project_discussion' ) : true;

	$user_can_comment = upstream_override_access_field( $user_can_comment, $item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_EDIT );

	$user_can_moderate = ! $user_has_admin_capabilities ? user_can( $user, 'moderate_comments' ) : true;
	$user_can_delete   = ! $user_has_admin_capabilities ? ( $user_can_moderate || user_can(
		$user,
		'delete_project_discussion'
	) ) : true;

	$user_can_delete = upstream_override_access_field( $user_can_delete, $item_type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project_id, 'comments', UPSTREAM_PERMISSIONS_ACTION_DELETE );

	$comments_statuses = array( 'approve' );
	if ( $user_has_admin_capabilities || $user_can_moderate ) {
		$comments_statuses[] = 'hold';
	}

	$query_params = array(
		'post_id' => $project_id,
		'orderby' => 'comment_date_gmt',
		'order'   => 'DESC',
		'type'    => '',
		'status'  => $comments_statuses,
	);

	if ( 'project' === $item_type ) {
		$query_params['meta_key']   = 'type';
		$query_params['meta_value'] = $item_type;
	} else {
		$query_params['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'   => 'type',
				'value' => $item_type,
			),
			array(
				'key'   => 'id',
				'value' => $item_id,
			),
		);
	}

	$rowset = (array) get_comments( $query_params );

	$comments_cache = array();
	if ( count( $rowset ) > 0 ) {
		$date_format          = get_option( 'date_format' );
		$time_format          = get_option( 'time_format' );
		$the_date_time_format = $date_format . ' ' . $time_format;
		$current_timestamp    = time();

		foreach ( $rowset as $row ) {
			$author = isset( $users[ (int) $row->user_id ] ) ? $users[ (int) $row->user_id ] : $row->comment_author;

			$date           = DateTime::createFromFormat( 'Y-m-d H:i:s', $row->comment_date_gmt );
			$date_timestamp = $date->getTimestamp();

			$comment = json_decode(
				json_encode(
					array(
						'id'               => (int) $row->comment_ID,
						'parent_id'        => (int) $row->comment_parent,
						'content'          => $row->comment_content,
						'state'            => (int) $row->comment_approved,
						'replies'          => array(),
						'created_by'       => $author,
						'created_at'       => array(
							'timestamp' => $date_timestamp,
							'utc'       => $row->comment_date_gmt,
							'localized' => $date->format( $the_date_time_format ),
							'humanized' => sprintf(
								// translators: %s : human-readable time difference.
								_x( '%s ago', '%s = human-readable time difference', 'upstream' ),
								human_time_diff( $date_timestamp, $current_timestamp )
							),
						),
						'current_user_cap' => array(
							'can_reply'    => $user_can_comment,
							'can_moderate' => $user_can_moderate,
							'can_delete'   => $user_can_delete,
						),
					)
				)
			);

			if ( isset( $author->id ) && $author->id == $user->ID ) {
				$comment->current_user_cap->can_delete = true;
			}

			$comments_cache[ $comment->id ] = $comment;
		}

		foreach ( $comments_cache as $comment ) {
			if ( $comment->parent_id > 0 ) {
				if ( isset( $comments_cache[ $comment->parent_id ] ) ) {
					$comments_cache[ $comment->parent_id ]->replies[] = $comment;
				} else {
					unset( $comments_cache[ $comment->id ] );
				}
			}
		}
	}

	if ( $return_as_html ) {
		ob_start();
	}

	$comments_cache_count = count( $comments_cache );

	if ( 0 === $comments_cache_count
		&& ! is_admin()
	) {
		printf( '<p data-empty><i class="s-text-color-gray">%s</i></p>', esc_html__( 'none', 'upstream' ) );
	}
	?>

	<div class="c-comments" data-type="<?php echo esc_attr( $item_type ); ?>" <?php echo $render_controls ? 'data-nonce' : ''; ?>>
		<?php
		if ( $comments_cache_count > 0 ) {
			if ( is_admin() ) {
				foreach ( $comments_cache as $comment ) {
					if ( 0 === $comment->parent_id ) {
						upstream_admin_display_message_item( $comment, $comments_cache, $render_controls );
					}
				}
			} else {
				foreach ( $comments_cache as $comment ) {
					if ( 0 === $comment->parent_id ) {
						upstream_display_message_item( $comment, $comments_cache, $render_controls );
					}
				}
			}
		}
		?>
	</div>
	<?php

	if ( $return_as_html ) {
		$content_html = ob_get_contents();
		ob_end_clean();

		return $content_html;
	}
}

/**
 * Upstream Admin Display Message Item
 *
 * @param  mixed $comment Comment.
 * @param  mixed $comments Comments.
 * @param  mixed $render_controls Render Controls.
 * @return void
 */
function upstream_admin_display_message_item( $comment, $comments = array(), $render_controls = true ) {
	global $wp_embed;

	$is_approved               = 1 === (int) $comment->state;
	$current_user_capabilities = (object) array(
		'can_reply'    => isset( $comment->current_user_cap->can_reply ) ? (bool) $comment->current_user_cap->can_reply : false,
		'can_moderate' => isset( $comment->current_user_cap->can_moderate ) ? (bool) $comment->current_user_cap->can_moderate : false,
		'can_delete'   => isset( $comment->current_user_cap->can_delete ) ? (bool) $comment->current_user_cap->can_delete : false,
	);
	?>
	<div class="o-comment s-status-<?php echo $is_approved ? 'approved' : 'unapproved'; ?>"
		id="comment-<?php echo esc_html( $comment->id ); ?>" data-id="<?php echo esc_html( $comment->id ); ?>">
		<div class="o-comment__body">
			<div class="o-comment__body__left">

				<?php if ( isset( $comment->created_by->avatar ) ) { ?>
				<img class="o-comment__user_photo" src="<?php echo esc_attr( $comment->created_by->avatar ); ?>" width="30">
				<?php } ?>
				<?php if ( ! $is_approved && $current_user_capabilities->can_moderate ) : ?>
					<div class="u-text-center">
						<i class="fa fa-eye-slash u-color-gray"
							title="
							<?php
							esc_attr_e(
								'This comment and its replies are not visible by regular users.',
								'upstream'
							);
							?>
						" style="margin-top: 2px;"></i>
					</div>
				<?php endif; ?>
			</div>
			<div class="o-comment__body__right">
				<div class="o-comment__body__head">
					<div class="o-comment__user_name"><?php echo isset( $comment->created_by->name ) ? esc_html( $comment->created_by->name ) : ''; ?></div>
					<div class="o-comment__reply_info"></div>
					<div class="o-comment__date"><?php echo esc_attr( $comment->created_at->humanized ); ?>&nbsp;<small>
							(<?php echo esc_attr( $comment->created_at->localized ); ?>)
						</small>
					</div>
				</div>
				<div class="o-comment__content"><?php echo wp_kses_post( $wp_embed->autoembed( wpautop( $comment->content ) ) ); ?></div>
				<div class="o-comment__body__footer">
			<?php
			if ( $render_controls ) {
				$controls = array();
				if ( $current_user_capabilities->can_moderate ) {
					if ( $is_approved ) {
						$controls[0] = array(
							'action' => 'unapprove',
							'nonce'  => 'unapprove_comment',
							'label'  => __( 'Unapprove' ),
						);
					} else {
						$controls[2] = array(
							'action' => 'approve',
							'nonce'  => 'approve_comment',
							'label'  => __( 'Approve' ),
						);
					}
				}

				if ( $current_user_capabilities->can_reply ) {
					$controls[1] = array(
						'action' => 'reply',
						'nonce'  => 'add_comment_reply',
						'label'  => __( 'Reply' ),
					);
				}

				if ( $current_user_capabilities->can_delete ) {
					$controls[] = array(
						'action' => 'trash',
						'nonce'  => 'trash_comment',
						'label'  => __( 'Delete' ),
					);
				}

				if ( count( $controls ) > 0 ) {
					foreach ( $controls as $control ) {
						printf(
							'<a href="#" class="o-comment-control" data-action="comment.%s" data-nonce="%s">%s</a>',
							esc_attr( $control['action'] ),
							esc_attr( wp_create_nonce( 'upstream:project.' . $control['nonce'] . ':' . $comment->id ) ),
							esc_html( $control['label'] )
						);
					}
				}
			}
			?>
			</div>
		</div>
		</div>
		<div class="o-comment-replies">
		<?php if ( isset( $comment->replies ) && count( $comment->replies ) > 0 ) : ?>
			<?php foreach ( $comment->replies as $comment_reply ) : ?>
				<?php upstream_admin_display_message_item( $comment_reply, $comments, $render_controls ); ?>
		<?php endforeach; ?>
		<?php endif; ?>
	  </div>
	</div>
	<?php
}

/**
 * Upstream Display Message Item
 *
 * @param  mixed $comment comment.
 * @param  mixed $comments comments.
 * @param  mixed $render_controls render_controls.
 * @return void
 */
function upstream_display_message_item( $comment, $comments = array(), $render_controls = true ) {
	global $wp_embed;

	$is_approved              = 1 === (int) $comment->state;
	$current_user_capabilities = (object) array(
		'can_reply'    => isset( $comment->current_user_cap->can_reply ) ? (bool) $comment->current_user_cap->can_reply : false,
		'can_moderate' => isset( $comment->current_user_cap->can_moderate ) ? (bool) $comment->current_user_cap->can_moderate : false,
		'can_delete'   => isset( $comment->current_user_cap->can_delete ) ? (bool) $comment->current_user_cap->can_delete : false,
	);
	?>
	<div class="o-comment s-status-<?php echo $is_approved ? 'approved' : 'unapproved'; ?>"
		id="comment-<?php echo esc_attr( $comment->id ); ?>" data-id="<?php echo esc_attr( $comment->id ); ?>">
		<div class="o-comment__body">
			<div class="o-comment__body__left">
				<?php if ( isset( $comment->created_by->avatar ) ) { ?>
				<img class="o-comment__user_photo" src="<?php echo esc_url( $comment->created_by->avatar ); ?>" width="30">
				<?php } ?>
				<?php if ( ! $is_approved && $current_user_capabilities->can_moderate ) : ?>
					<div class="u-text-center">
						<i class="fa fa-eye-slash u-color-gray" data-toggle="tooltip"
							title="
							<?php
							esc_attr_e(
								'This comment and its replies are not visible by regular users.',
								'upstream'
							);
							?>
						" style="margin-top: 2px;"></i>
					</div>
				<?php endif; ?>
			</div>
			<div class="o-comment__body__right">
				<div class="o-comment__body__head">
					<div class="o-comment__user_name"><?php echo isset( $comment->created_by->name ) ? esc_html( $comment->created_by->name ) : ''; ?></div>
					<div class="o-comment__reply_info"></div>
					<div class="o-comment__date" data-toggle="tooltip"
						title="<?php echo esc_attr( $comment->created_at->localized ); ?>"><?php echo esc_html( $comment->created_at->humanized ); ?></div>
				</div>
				<div
						class="o-comment__content"><?php echo wp_kses_post( $wp_embed->autoembed( wpautop( $comment->content ) ) ); ?></div>
				<div class="o-comment__body__footer">
			<?php
			if ( $render_controls ) {
				do_action( 'upstream:project.comments.comment_controls', $comment );
			}
			?>
			</div>
			</div>
		</div>
		<div class="o-comment-replies">
		<?php if ( isset( $comment->replies ) && count( $comment->replies ) > 0 ) : ?>
			<?php foreach ( $comment->replies as $comment_reply ) : ?>
				<?php upstream_display_message_item( $comment_reply, $comments, $render_controls ); ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
	</div>
	<?php
}

/*
======================================================================================
										GENERAL
======================================================================================
*/

/**
 * Adds field attributes, and permissions data (mainly) depending on users capabilities.
 * Used heavily in JS to enable/disable fields, groups and delete buttons.
 * Also used to add Avatars to group items.
 *
 * @param  mixed $args Args.
 * @param  mixed $field Field.
 * @return void
 */
function upstream_add_field_attributes( $args, $field ) {
	/*
	 * Add the disabled/readonly attributes to the field
	 * if the user does not have permission for that field
	 */
	if ( isset( $args['permissions'] ) ) {
		if ( ! upstream_admin_permissions( $args['permissions'] ) ) {
			$field->args['attributes']['disabled']      = 'disabled';
			$field->args['attributes']['readonly']      = 'readonly';
			$field->args['attributes']['data-disabled'] = 'true';
		} else {
			$field->args['attributes']['data-disabled'] = 'false';
		}
	}

	/*
	 * Adding/removing attributes for repeatable groups.
	 */
	if ( isset( $field->group->args['repeatable'] ) && '1' == $field->group->args['repeatable'] ) :

		$i          = filter_var( $field->args['id'], FILTER_SANITIZE_NUMBER_INT );
		$created_by = isset( $field->group->value[ $i ]['created_by'] ) ? (int) $field->group->value[ $i ]['created_by'] : 0;
		$assignees  = isset( $field->group->value[ $i ]['assigned_to'] ) ? $field->group->value[ $i ]['assigned_to'] : array();
		if ( ! is_array( $assignees ) ) {
			$assignees = (array) $assignees;
		}

		$assignees = array_map( 'intval', array_unique( array_filter( $assignees ) ) );

		$current_user_id = (int) upstream_current_user_id();
		// if the user is assigned to or item is created by.
		if ( $created_by === $current_user_id
			|| in_array( $current_user_id, $assignees )
		) {
			// clear the disabled attributes.
			unset( $field->args['attributes']['disabled'] );
			unset( $field->args['attributes']['readonly'] );
			$field->args['attributes']['data-disabled'] = 'false';

			// data-owner attribute is used for the delete button.
			if ( 'id' == $field->args['_id'] ) {
				$field->args['attributes']['data-owner'] = 'true';
			}
		}
		// to ensure admin and managers can delete anything.
		if ( upstream_admin_permissions() ) {
			$field->args['attributes']['data-owner'] = 'true';
		}

		// add users avatars.
		$user_createdby = upstream_user_data( $created_by, true );
		if ( 'id' == $field->args['_id'] ) {
			$field->args['attributes']['data-user_created_by']   = $user_createdby['full_name'];
			$field->args['attributes']['data-avatar_created_by'] = $user_createdby['avatar'];

			$field->args['attributes']['data-user_assigned']   = '';
			$field->args['attributes']['data-avatar_assigned'] = '';
			if ( count( $assignees ) > 0 ) {
				$users_data = array();
				foreach ( $assignees as $user_id ) {
					$user_data = upstream_user_data( $user_id, true );

					$users_data[] = array(
						'name'   => $user_data['full_name'],
						'avatar' => $user_data['avatar'],
					);
				}

				$field->args['attributes']['data-assignees'] = json_encode( array( 'data' => $users_data ) );
			}
		}

	endif;
}

/**
 * Check if a group is empty.
 *
 * @param  mixed $type Type.
 */
function upstream_empty_group( $type ) {
	$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

	if ( isset( $get_data['post_type'] ) && sanitize_text_field( $get_data['post_type'] ) != 'project' ) {
		return '';
	}

	$meta = get_post_meta( upstream_post_id(), "_upstream_project_{$type}", true );
	if ( null == $meta || empty( $meta ) || empty( $meta[0] ) ) {
		return '1';
	} else {
		return '';
	}
}

/**
 * Returns the project status names as set in the options.
 * Used in the Status dropdown for the project.
 */
function upstream_admin_get_project_statuses() {
	$option   = get_option( 'upstream_projects' );
	$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
	$array    = array();
	if ( $statuses ) {
		foreach ( $statuses as $status ) {
			if ( isset( $status['type'] ) ) {
				$array[ $status['id'] ] = $status['name'];
			}
		}
	}

	return $array;
}

/**
 * Return the array of user roles
 *
 * @return array
 */
function upstream_get_project_roles() {
	 $options = (array) get_option( 'upstream_general' );

	if ( ! isset( $options['project_user_roles'] ) || empty( $options['project_user_roles'] ) ) {
		$roles = array(
			'upstream_manager',
			'upstream_user',
			'administrator',
		);
	} else {
		$roles = (array) $options['project_user_roles'];
	}

	$roles = apply_filters( 'upstream_user_roles_for_projects', $roles );

	return $roles;
}



/**
 * Returns all users with select roles.
 * For use in dropdowns.
 */
function upstream_admin_get_all_project_users_uncached() {
	$project_client_users = array();
	$project_id          = upstream_post_id();
	if ( $project_id > 0 ) {
		$project_client_id = (int) get_post_meta( $project_id, '_upstream_project_client', true );
		if ( $project_client_id > 0 ) {
			$project_client_users_ids = array_filter(
				array_map(
					'intval',
					(array) get_post_meta( $project_id, '_upstream_project_client_users', true )
				)
			);
			if ( count( $project_client_users_ids ) > 0 ) {
				$project_client_users = (array) get_users(
					array(
						'include' => $project_client_users_ids,
						'fields'  => array( 'ID', 'display_name' ),
					)
				);
			}
		}
	}

	$roles = upstream_get_project_roles();

	$args = array(
		'fields'   => array( 'ID', 'display_name' ),
		'role__in' => $roles,
	);

	$system_users = get_users( $args );

	$users = array();

	$rowset = array_merge( $system_users, $project_client_users );
	if ( count( $rowset ) > 0 ) {
		foreach ( $rowset as $user ) {
			$users[ (int) $user->ID ] = $user->display_name;
		}
	}

	return $users;
}

/**
 * Upstream Admin Get All Project Users
 */
function upstream_admin_get_all_project_users() {
	$key = 'upstream_admin_get_all_project_users';

	$users = Upstream_Cache::get_instance()->get( $key );
	if ( false === $users ) {
		$users = upstream_admin_get_all_project_users_uncached();
		Upstream_Cache::get_instance()->set( $key, $users );
	}

	return $users;
}

/**
 * Upstream Get Viewable Users
 */
function upstream_get_viewable_users() {
	$key = 'upstream_get_viewable_users';

	$users = Upstream_Cache::get_instance()->get( $key );
	if ( false === $users ) {

		$results            = array();
		$uid_to_name        = array();
		$cid_to_client_name = array();

		$user_client_ids = array();
		if ( current_user_can( 'upstream_client_user' ) ) {
			$user_client_ids = upstream_get_users_client_ids( upstream_current_user_id() );
		}

		$args    = array(
			'post_type'      => 'client',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true, // for performance.
		);
		$clients = get_posts( $args );
		if ( $clients ) {
			foreach ( $clients as $client ) {

				if ( current_user_can( 'upstream_manager' ) ||
					current_user_can( 'administrator' ) ||
					in_array( $client->ID, $user_client_ids ) ) {
					$cu = upstream_get_all_client_users( $client->ID );

					$usrs = array();
					foreach ( $cu as $usr ) {
						$usrs[]                    = $usr['id'];
						$uid_to_name[ $usr['id'] ] = $usr['display_name'];
					}

					$results[ $client->ID ]            = $usrs;
					$cid_to_client_name[ $client->ID ] = $client->post_title;
				}
			}
		}

		$roles = upstream_get_project_roles();

		$args = array(
			'fields'   => array( 'ID', 'display_name' ),
			'role__in' => $roles,
		);

		$system_users = get_users( $args );
		$usrs        = array();

		foreach ( $system_users as $su ) {
			if ( ! isset( $uid_to_name[ $su->ID ] ) ) {
				$usrs[]                 = $su->ID;
				$uid_to_name[ $su->ID ] = $su->display_name;
			}
		}

		$results[0] = $usrs;

		uasort(
			$uid_to_name,
			function( $a, $b ) {
				return strcasecmp( $a, $b );
			}
		);

		$users = array(
			'by_client'   => $results,
			'by_uid'      => $uid_to_name,
			'cid_to_name' => $cid_to_client_name,
		);
		Upstream_Cache::get_instance()->set( $key, $users );
	}

	return $users;

}


/**
 * Returns array of all clients.
 * For use in dropdowns.
 */
function upstream_admin_get_all_clients() {
	$args    = array(
		'post_type'      => 'client',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'no_found_rows'  => true, // for performance.
	);
	$clients = get_posts( $args );
	$array   = array( '' => __( 'Not Assigned', 'upstream' ) );
	if ( $clients ) {
		foreach ( $clients as $client ) {
			$array[ $client->ID ] = $client->post_title;
		}
	}

	return $array;
}

/**
 * Returns the current saved clients users.
 * For use in dropdowns.
 *
 * @param  mixed $field Field.
 * @param  mixed $client_id Client Id.
 */
function upstream_admin_get_all_clients_users( $field, $client_id = 0 ) {
	// Get the currently selected client id.
	if ( empty( $client_id ) || $client_id < 0 ) {
		$client_id = (int) get_post_meta( $field->object_id, '_upstream_project_client', true );
	}

	if ( $client_id > 0 ) {
		$users_list            = array();
		$client_users_list     = array_filter( (array) get_post_meta( $client_id, '_upstream_new_client_users', true ) );
		$client_users_ids_list = array();

		foreach ( $client_users_list as $client_user ) {
			if ( ! empty( $client_user ) ) {
				$client_users_ids_list[] = $client_user['user_id'];
			}
		}

		if ( count( $client_users_ids_list ) > 0 ) {
			$rowset = (array) get_users(
				array(
					'fields'  => array( 'ID', 'display_name', 'user_email' ),
					'include' => $client_users_ids_list,
				)
			);

			foreach ( $rowset as $user ) {
				$users_list[ (int) $user->ID ] = $user->display_name . ' <a href="mailto:' . esc_html( $user->user_email ) . '" target="_blank"><span class="dashicons dashicons-email-alt"></span></a>';
			}

			return $users_list;
		}
	}

	return array();
}

/**
 * Returns the current saved clients users as an array.
 *
 * @param  mixed $client_id Client Id.
 */
function upstream_get_all_client_users( $client_id = 0 ) {
	// Get the currently selected client id.
	if ( empty( $client_id ) || $client_id < 0 ) {
		/* TODO ??????????? WHAT IS THIS */
		$client_id = (int) get_post_meta( $field->object_id, '_upstream_project_client', true );
	}

	if ( $client_id > 0 ) {
		$users_list       = array();
		$client_users_list = array_filter( (array) get_post_meta( $client_id, '_upstream_new_client_users', true ) );

		$client_users_ids_list = array();
		foreach ( $client_users_list as $client_user ) {
			if ( ! empty( $client_user ) ) {
				$client_users_ids_list[] = $client_user['user_id'];
			}
		}

		if ( count( $client_users_ids_list ) > 0 ) {
			$rowset = (array) get_users(
				array(
					'fields'  => array( 'ID', 'display_name', 'user_email' ),
					'include' => $client_users_ids_list,
				)
			);

			foreach ( $rowset as $user ) {
				$users_list[] = array(
					'id'           => $user->ID,
					'display_name' => $user->display_name,
					'email'        => esc_html( $user->user_email ),
				);
			}

			return $users_list;
		}
	}

	return array();
}

/**
 * AJAX function to return all selected clients users.
 * For use in dropdowns.
 */
function upstream_admin_ajax_get_clients_users() {
	$project_id = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : 0;
	$client_id  = isset( $_POST['client_id'] ) ? absint( $_POST['client_id'] ) : 0;

	check_ajax_referer( 'upstream_admin_project_form', 'nonce' );

	if ( $project_id <= 0 ) {
		wp_send_json_error(
			array(
				'msg' => __( 'No project selected', 'upstream' ),
			)
		);
	} elseif ( $client_id <= 0 ) {
		wp_send_json_error(
			array(
				'msg' => __( 'No client selected', 'upstream' ),
			)
		);
	} else {
		$field            = new stdClass();
		$field->object_id = $project_id;

		$data = upstream_admin_get_all_clients_users( $field, $client_id );

		if ( count( $data ) === 0 ) {
			wp_send_json_error(
				array(
					'msg' => __( 'No users found', 'upstream' ),
				)
			);
		} else {
			$output = '';

			$current_project_client_users = (array) get_post_meta( $project_id, '_upstream_project_client_users' );
			$current_project_client_users = ! empty( $current_project_client_users ) ? $current_project_client_users[0] : array();

			// Check if the users should be pre-selected by default.

			$user_index = 0;
			foreach ( $data as $user_id => $user_name ) {
				$checked = upstream_select_users_by_default() || in_array( $user_id, $current_project_client_users );

				$output .= sprintf(
					'<li><input type="checkbox" value="%s" id="_upstream_project_client_users%d" name="_upstream_project_client_users[]" class="cmb2-option"%s> <label for="_upstream_project_client_users%2$d">%4$s</label></li>',
					$user_id,
					$user_index,
					( $checked ? ' checked' : '' ),
					$user_name
				);
				$user_index++;
			}

			wp_send_json_success( $output );
		}
	}
}
add_action( 'wp_ajax_upstream_admin_ajax_get_clients_users', 'upstream_admin_ajax_get_clients_users' );

/**
 * Upstream Wp Get Clients
 */
function upstream_wp_get_clients() {
	global $wpdb;

	$rowset = $wpdb->get_results(
		sprintf(
			'
        SELECT `ID`, `post_title`
        FROM `%s`
        WHERE `post_type` = "client"
        AND `post_status` = "publish"',
			$wpdb->prefix . 'posts'
		)
	);

	$data = array();

	foreach ( $rowset as $row ) {
		$data[ $row->ID ] = $row->post_title;
	}

	return $data;
}

/**
 * Upstream Admin Get Milestone Categories
 *
 * @param  mixed $args Args.
 * @throws \UpStream\Exception UpStream Exception.
 */
function upstream_admin_get_milestone_categories( $args = array() ) {
	$default = array(
		'taxonomy'   => 'upst_milestone_category',
		'fields'     => 'all',
		'hide_empty' => false,
	);

	$args = wp_parse_args( $args, $default );

	$categories = array();
	$terms      = get_terms( $args );

	// RSD: hopefully this will work to stop the errors here.
	if ( isset( $terms->errors ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'upst_milestone_category',
				'hide_empty' => false,
			)
		);
	}

	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$categories[ $term->term_id ] = $term->name;
		}
	}

	return $categories;
}
