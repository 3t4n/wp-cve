<?php
/**
 * Handle table functions
 *
 * @package UpStream\Frontend
 */

namespace UpStream\Frontend;

use UpStream\Exception;
use UpStream\Factory;
use UpStream\Milestones;
use UpStream_View;

/**
 * Array To Attrs
 *
 * @param  mixed $data Data.
 */
function upstream_array_to_attrs( $data ) {
	$attrs = array();

	foreach ( $data as $attr_key => $attr_value ) {
		printf( '%s="%s" ', esc_attr( $attr_key ), esc_attr( $attr_value ) );
	}
}

/**
 * Get Milestones Fields
 *
 * @param  mixed $are_comments_enabled Are Comments Enabled.
 */
function upstream_get_milestones_fields( $are_comments_enabled = null ) {
	$schema = array(
		'milestone'   => array(
			'type'        => 'raw',
			'isOrderable' => true,
			'label'       => upstream_milestone_label(),

		),
		'assigned_to' => array(
			'type'        => 'user',
			'isOrderable' => true,
			'label'       => __( 'Assigned To', 'upstream' ),
		),
		'tasks'       => array(
			'type'           => 'custom',
			'label'          => upstream_task_label_plural(),
			'isEditable'     => false,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) {
				$tasks_open_count = isset( $row['task_open'] ) ? (int) $row['task_open'] : 0;
				$tasks_count      = isset( $row['task_count'] ) ? (int) $row['task_count'] : 0;

				return sprintf(
					'%d %s / %d %s',
					$tasks_open_count,
					_x( 'Open', 'Open Tasks', 'upstream' ),
					$tasks_count,
					_x( 'Total', 'Total number of Tasks', 'upstream' )
				);
			},
		),
		'progress'    => array(
			'type'        => 'percentage',
			'isOrderable' => true,
			'label'       => __( 'Progress', 'upstream' ),
			'isEditable'  => false,
		),
		'start_date'  => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'Start Date', 'upstream' ),
		),
		'end_date'    => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'End Date', 'upstream' ),
		),
		'color'       => array(
			'type'     => 'colorpicker',
			'label'    => __( 'Color', 'upstream' ),
			'isHidden' => true,
		),
		'notes'       => array(
			'type'     => 'wysiwyg',
			'label'    => __( 'Notes', 'upstream' ),
			'isHidden' => true,
		),
		'comments'    => array(
			'type'       => 'comments',
			'label'      => __( 'Comments' ),
			'isHidden'   => true,
			'isEditable' => false,
		),
	);

	if ( ! upstream_disable_milestone_categories() ) {
		$schema['categories'] = array(
			'type'           => 'taxonomies',
			'isOrderable'    => true,
			'label'          => upstream_milestone_category_label_plural(),
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) {
				if ( empty( $column_value ) ) {
					if ( ! is_array( $column_value ) ) {
						$column_value = array( $column_value );
					}

					foreach ( $column_value as &$value ) {
						$term = get_term( (int) $value );

						if ( ! is_wp_error( $term ) ) {
							$value = $term->name;
						}
					}

					$column_value = implode( ',', $column_value );
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		);
	}

	if ( null === $are_comments_enabled ) {
		$are_comments_enabled = upstream_are_comments_enabled_on_milestones();
	}

	if ( ! $are_comments_enabled ) {
		unset( $schema['comments'] );
	}

	return apply_filters( 'upstream:project.milestones.fields', $schema );
}

/**
 * Get Tasks Fields
 *
 * @param  array $statuses Statuses.
 * @param  array $milestones Milestones.
 * @param  mixed $are_milestones_enabled Are Milestones Enabled.
 * @param  mixed $are_comments_enabled Are Comments Enabled.
 */
function upstream_get_tasks_fields( $statuses = array(), $milestones = array(), $are_milestones_enabled = null, $are_comments_enabled = null ) {
	if ( null === $are_milestones_enabled ) {
		$are_milestones_enabled = ! upstream_are_milestones_disabled() && ! upstream_disable_milestones();
	}

	$statuses = empty( $statuses ) ? upstream_get_tasks_statuses() : $statuses;
	$options  = array();

	$schema = array(
		'title'       => array(
			'type'        => 'raw',
			'isOrderable' => true,
			'label'       => __( 'Title', 'upstream' ),
		),
		'assigned_to' => array(
			'type'        => 'user',
			'isOrderable' => true,
			'label'       => __( 'Assigned To', 'upstream' ),
		),
		'status'      => array(
			'type'           => 'custom',
			'label'          => __( 'Status', 'upstream' ),
			'isOrderable'    => true,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) use (
				&
				$statuses,
				&$options
			) {
				if ( strlen( $column_value ) > 0 ) {
					if ( isset( $statuses[ $column_value ] ) ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							isset( $statuses[ $column_value ]['color'] ) ? esc_attr( $statuses[ $column_value ]['color'] ) : '',
							esc_html( $statuses[ $column_value ]['name'] )
						);
					} else {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Status doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'progress'    => array(
			'type'        => 'percentage',
			'isOrderable' => true,
			'label'       => __( 'Progress', 'upstream' ),
		),
		'milestone'   => array(
			'type'           => 'custom',
			'isOrderable'    => true,
			'label'          => upstream_milestone_label(),
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) {

				if ( ! empty( $column_value ) ) {
					try {
						$milestone = Factory::get_milestone( $column_value );

						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							esc_attr( $milestone->getColor() ),
							esc_html( $milestone->getName() )
						);
					} catch ( Exception $e ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Milestone doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'start_date'  => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'Start Date', 'upstream' ),
		),
		'end_date'    => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'End Date', 'upstream' ),
		),
		'notes'       => array(
			'type'     => 'wysiwyg',
			'label'    => __( 'Notes', 'upstream' ),
			'isHidden' => true,
		),
		'comments'    => array(
			'type'       => 'comments',
			'label'      => __( 'Comments' ),
			'isHidden'   => true,
			'isEditable' => false,
		),
	);

	if ( null === $are_comments_enabled ) {
		$are_comments_enabled = upstream_are_comments_enabled_on_tasks();
	}

	if ( false === $are_milestones_enabled ) {
		unset( $schema['milestone'] );
	}

	if ( ! $are_comments_enabled ) {
		unset( $schema['comments'] );
	}

	return apply_filters( 'upstream:project.tasks.fields', $schema );
}

/**
 * Get Bugs Fields
 *
 * @param  array $severities Severities.
 * @param  array $statuses Statuses.
 * @param  mixed $are_comments_enabled Are Comments Enabled.
 */
function upstream_get_bugs_fields( $severities = array(), $statuses = array(), $are_comments_enabled = null ) {
	if ( empty( $severities ) ) {
		$severities = upstream_get_bugs_severities();
	}

	if ( empty( $statuses ) ) {
		$statuses = upstream_get_bugs_statuses();
	}

	$options = null;

	$schema = array(
		'title'       => array(
			'type'        => 'raw',
			'isOrderable' => true,
			'label'       => __( 'Title', 'upstream' ),
		),
		'assigned_to' => array(
			'type'        => 'user',
			'isOrderable' => true,
			'label'       => __( 'Assigned To', 'upstream' ),
		),
		'severity'    => array(
			'type'           => 'custom',
			'label'          => __( 'Severity', 'upstream' ),
			'isOrderable'    => true,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) use (
				&
				$severities,
				&$options
			) {
				if ( strlen( $column_value ) > 0 ) {
					if ( isset( $severities[ $column_value ] ) ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							esc_attr( $severities[ $column_value ]['color'] ),
							esc_html( $severities[ $column_value ]['name'] )
						);
					} else {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Severity doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'status'      => array(
			'type'           => 'custom',
			'label'          => __( 'Status', 'upstream' ),
			'isOrderable'    => true,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) use (
				&
				$statuses,
				&$options
			) {
				if ( strlen( $column_value ) > 0 ) {
					if ( isset( $statuses[ $column_value ] ) ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							esc_attr( $statuses[ $column_value ]['color'] ),
							esc_html( $statuses[ $column_value ]['name'] )
						);
					} else {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Status doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'due_date'    => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'Due Date', 'upstream' ),
		),
		'file'        => array(
			'type'        => 'file',
			'isOrderable' => false,
			'label'       => __( 'File', 'upstream' ),
		),
		'description' => array(
			'type'     => 'wysiwyg',
			'label'    => __( 'Description', 'upstream' ),
			'isHidden' => true,
		),
		'comments'    => array(
			'type'       => 'comments',
			'label'      => __( 'Comments' ),
			'isHidden'   => true,
			'isEditable' => false,
		),
	);

	if ( null === $are_comments_enabled ) {
		$are_comments_enabled = upstream_are_comments_enabled_on_bugs();
	}

	if ( ! $are_comments_enabled ) {
		unset( $schema['comments'] );
	}

	return apply_filters( 'upstream:project.bugs.fields', $schema );
}

/**
 * Get Files Fields
 *
 * @param  mixed $are_comments_enabled Are Comments Enabled.
 */
function upstream_get_files_fields( $are_comments_enabled = null ) {
	$schema = array(
		'title'       => array(
			'type'        => 'raw',
			'isOrderable' => true,
			'label'       => __( 'Title', 'upstream' ),
		),
		'created_by'  => array(
			'type'        => 'user',
			'isOrderable' => true,
			'label'       => __( 'Uploaded by', 'upstream' ),
			'isEditable'  => false,
		),
		'created_at'  => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'Upload Date', 'upstream' ),
			'isEditable'  => false,
		),
		'assigned_to' => array(
			'type'        => 'user',
			'isOrderable' => false,
			'label'       => __( 'Assigned To', 'upstream' ),
		),
		'file'        => array(
			'type'        => 'file',
			'isOrderable' => false,
			'label'       => __( 'File', 'upstream' ),
		),
		'description' => array(
			'type'     => 'wysiwyg',
			'label'    => __( 'Description', 'upstream' ),
			'isHidden' => true,
		),
		'comments'    => array(
			'type'       => 'comments',
			'label'      => __( 'Comments' ),
			'isHidden'   => true,
			'isEditable' => false,
		),
	);

	if ( null === $are_comments_enabled ) {
		$are_comments_enabled = upstream_are_comments_enabled_on_files();
	}

	if ( ! $are_comments_enabled ) {
		unset( $schema['comments'] );
	}

	return apply_filters( 'upstream:project.files.fields', $schema );
}

/**
 * Get Project Fields
 *
 * @param  array $statuses Statuses.
 */
function upstream_get_project_fields( $statuses = array() ) {
	if ( empty( $statuses ) ) {
		$statuses = upstream_get_all_project_statuses();
	}

	$options = null;

	$schema = array(
		'title'        => array(
			'type'        => 'raw',
			'isOrderable' => true,
			'label'       => __( 'Title', 'upstream' ),
		),
		'status'       => array(
			'type'           => 'custom',
			'label'          => __( 'Status', 'upstream' ),
			'isOrderable'    => true,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) use (
				&
				$statuses,
				&$options
			) {
				if ( strlen( $column_value ) > 0 ) {
					if ( isset( $statuses[ $column_value ] ) ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							esc_attr( $statuses[ $column_value ]['color'] ),
							esc_html( $statuses[ $column_value ]['name'] )
						);
					} else {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Status doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'owner'        => array(
			'type'        => 'user',
			'isOrderable' => true,
			'label'       => __( 'Owner', 'upstream' ),
		),
		'client'       => array(
			'type'        => 'custom',
			'isOrderable' => true,
			'label'       => upstream_client_label(),
		),
		'client_users' => array(
			'type'           => 'array',
			'label'          => __( 'Client users', 'upstream' ),
			'isOrderable'    => true,
			'renderCallback' => function ( $column_name, $column_value, $column, $row, $row_type, $project_id ) use (
				&
				$statuses,
				&$options
			) {
				if ( strlen( $column_value ) > 0 ) {
					if ( isset( $statuses[ $column_value ] ) ) {
						$column_value = sprintf(
							'<span class="badge up-o-label" style="background-color: %s;">%s</span>',
							esc_attr( $statuses[ $column_value ]['color'] ),
							esc_html( $statuses[ $column_value ]['name'] )
						);
					} else {
						$column_value = sprintf(
							'<span class="badge up-o-label" title="%s" style="background-color: %s;">%s <i class="fa fa-ban"></i></span>',
							__( "This Status doesn't exist anymore.", 'upstream' ),
							'#bdc3c7',
							esc_html( $column_value )
						);
					}
				} else {
					$column_value = sprintf( '<i class="s-text-color-gray">%s</i>', __( 'none', 'upstream' ) );
				}

				return $column_value;
			},
		),
		'start'        => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'Start', 'upstream' ),
		),
		'end'          => array(
			'type'        => 'date',
			'isOrderable' => true,
			'label'       => __( 'End', 'upstream' ),
		),

		'description'  => array(
			'type'     => 'wysiwyg',
			'label'    => __( 'Description', 'upstream' ),
			'isHidden' => true,
		),
	);

	return apply_filters( 'upstream:project.fields', $schema );
}

/**
 * Render Table Header Column
 *
 * @param  mixed $identifier Identifier.
 * @param  mixed $data Data.
 * @return void
 */
function upstream_render_table_header_column( $identifier, $data ) {
	$attrs = array(
		'data-column' => $identifier,
		'class'       => isset( $data['class'] ) ? ( is_array( $data['class'] ) ? implode(
			' ',
			$data['class']
		) : $data['class'] ) : '',
	);

	$is_hidden = isset( $data['isHidden'] ) && (bool) $data['isHidden'];
	if ( $is_hidden ) {
		return;
	}

	$is_orderable = isset( $data['isOrderable'] ) && (bool) $data['isOrderable'];
	if ( $is_orderable ) {
		$attrs['class'] .= ' is-clickable is-orderable';
		$attrs['role']   = 'button';
		$attrs['scope']  = 'col';
	} ?>
	<th <?php upstream_array_to_attrs( $attrs ); ?>>
		<?php echo isset( $data['label'] ) ? esc_html( $data['label'] ) : ''; ?>
		<?php if ( $is_orderable ) : ?>
			<span class="pull-right o-order-direction">
			<i class="fa fa-sort"></i>
		</span>
		<?php endif; ?>
	</th>
	<?php
}

/**
 * Render Table Header
 *
 * @param  array $columns Columns.
 * @param  mixed $item_type Item Type.
 * @return void
 */
function upstream_render_table_header( $columns = array(), $item_type = null ) {
	if ( is_null( $item_type ) ) {
		return;
	}

	?>
	<thead>
	<?php if ( ! empty( $columns ) ) : ?>
		<tr scope="row">
			<?php
			foreach ( $columns as $column_identifier => $column ) {
				upstream_render_table_header_column( $column_identifier, $column );
			}
			?>

			<?php do_action( 'upstream_table_columns_header', array( 'type' => $item_type ), $columns ); ?>
		</tr>
	<?php endif; ?>
	</thead>
	<?php
}

/**
 * Render Table Column Value
 *
 * @param  mixed $column_name Column Name.
 * @param  mixed $column_value Column Value.
 * @param  mixed $column Column.
 * @param  mixed $row Row.
 * @param  mixed $row_type Row Type.
 * @param  mixed $project_id Project Id.
 * @return void
 */
function upstream_render_table_column_value( $column_name, $column_value, $column, $row, $row_type, $project_id ) {
	$is_hidden = isset( $column['isHidden'] ) && true === (bool) $column['isHidden'];

	$viewable = upstream_override_access_field( true, $row_type, $row['id'], UPSTREAM_ITEM_TYPE_PROJECT, $project_id, $column_name, UPSTREAM_PERMISSIONS_ACTION_VIEW );

	if ( $viewable ) {

		$html        = sprintf( '<i class="s-text-color-gray">%s</i>', esc_html__( 'none', 'upstream' ) );
		$column_type = isset( $column['type'] ) ? $column['type'] : 'raw';

		// Detect color values.
		if ( 'raw' === $column_type && preg_match( '/^(#[0-9a-f]+|rgba?\()/i', $column_value ) ) {
			$column_type = 'colorpicker';
		}

		if ( 'user' === $column_type ) {
			if ( ! is_array( $column_value ) ) {
				$column_value = (array) $column_value;
			}

			$names = upstream_get_users_display_name( $column_value );

			// RSD: for some reason upstream_get_users_display_name returns 0 when there's nothign to show
			// this fixes the display.
			$html = ( '0' !== $names ) ? $names : $html;
		} elseif ( 'taxonomies' === $column_type ) {
			if ( ! is_array( $column_value ) ) {
				$column_value = (array) $column_value;
			}

			$html = '';

			if ( ! empty( $column_value ) ) {
				$names = array();

				foreach ( $column_value as $value ) {
					if ( is_numeric( $value ) ) {
						$term = get_term( (int) $value );

						$names[] = $term->name;
					} else {
						$names[] = $value;
					}
				}

				$html = esc_html( implode( ', ', $names ) );
			}
		} elseif ( 'percentage' === $column_type ) {
			$html = esc_html( sprintf( '%d%%', (int) $column_value ) );
		} elseif ( 'date' === $column_type ) {

			if ( isset( $row[ $column_name . '.YMD' ] ) && $row[ $column_name . '.YMD' ] ) {
				$ts   = date_create_from_format( 'Y-m-d', $row[ $column_name . '.YMD' ] )->getTimestamp();
				$html = esc_html( date_i18n( get_option( 'date_format' ), $ts ) );

			} else {
				$column_value = (int) $column_value;

				if ( $column_value > 0 ) {
					// RSD: timezone offset is here to ensure compatibility with previous wrong data
					// TODO: should remove offset at some point.
					$html = esc_html( upstream_format_date( $column_value + UpStream_View::get_time_zone_offset() ) );
				}

				$offset = get_option( 'gmt_offset' );
			}
		} elseif ( 'wysiwyg' === $column_type ) {
			// replace newlines if not HTML.
			if ( ! stristr( $column_value, '&lt;' ) ) {
				$column_value = preg_replace( '/(?!>[\s]*).\r?\n(?![\s]*<)/', '$0<br />', trim( (string) $column_value ) );
			}
			if ( strlen( $column_value ) > 0 ) {
				$html = upstream_esc_w( sprintf( '<blockquote>%s</blockquote>', html_entity_decode( $column_value ) ) );
			} else {
				$html = '<br>' . $html;
			}
		} elseif ( 'comments' === $column_type ) {
			$html = upstream_render_comments_box( $row['id'], $row_type, $project_id, false, true );
		} elseif ( 'custom' === $column_type ) {
			if ( isset( $column['renderCallback'] ) && is_callable( $column['renderCallback'] ) ) {
				$html = call_user_func(
					$column['renderCallback'],
					$column_name,
					$column_value,
					$column,
					$row,
					$row_type,
					$project_id
				);
			}
		} elseif ( 'file' === $column_type ) {

			if ( is_array( $column_value ) && count( $column_value ) > 0 ) {
				$column_value = $column_value[0];
			}

			if ( strlen( $column_value ) > 0 ) {
				if ( upstream_filesytem_enabled() ) {
					$file = upstream_upfs_info( $column_value );
					if ( $file ) {
						$html = sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_url( upstream_upfs_get_file_url( $column_value ) ),
							esc_html( $file->orig_filename )
						);
					}
				} else {
					if ( stristr( $column_value, '_upfs_' ) ) {
						$column_value = '';
					}

					if ( @is_array( getimagesize( $column_value ) ) ) {
						$html = sprintf(
							'<a href="%s" target="_blank">
                <img class="avatar itemfile" width="32" height="32" src="%1$s">
              </a>',
							esc_url( $column_value )
						);
					} else {
						$html = sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_url( $column_value ),
							esc_html( basename( $column_value ) )
						);
					}
				}
			} elseif ( $is_hidden ) {
				$html = '<br>' . $html;
			}
		} elseif ( 'array' === $column_type ) {
			$column_value = array_filter( (array) $column_value );

			if ( isset( $column['options'] ) ) {
				$values = array();

				if ( is_array( $column['options'] ) ) {
					foreach ( $column_value as $value ) {
						if ( isset( $column['options'][ $value ] ) ) {
							$values[] = $column['options'][ $value ];
						}
					}
				}

				$values = implode( ', ', $values );
			} elseif ( ! empty( $column_value ) ) {
				$values = implode( ', ', $column_value );
			}

			if ( ! empty( $values ) ) {
				if ( $is_hidden ) {
					$html = '<br><span data-value="' . esc_attr( implode( ',', $column_value ) ) . '">' . esc_html( $values ) . '</span>';
				} else {
					$html = '<br><span>' . esc_html( implode( ',', $column_value ) ) . '</span>';
				}
			} else {
				$html = '<br>' . $html;
			}
		} elseif ( 'colorpicker' === $column_type ) {
			$column_value = trim( (string) $column_value );
			if ( strlen( $column_value ) > 0 ) {
				$html  = '<br><div class="up-c-color-square has-tooltip" data-toggle="tooltip" title="' . esc_attr( $column_value ) . '">';
				$html .= '<div style="background-color: ' . esc_attr( $column_value ) . '"></div>';
				$html .= '</div>';
			}

			if ( $is_hidden ) {
				$html = '<span data-value="' . esc_attr( $column_value ) . '">' . $html . '</span>';
			}
		} elseif ( 'radio' === $column_type ) {
			if ( is_array( $column_value ) ) {
				$column_value = $column_value[0];
			}

			$column_value = trim( (string) $column_value );

			if ( strlen( $column_value ) > 0 ) {
				if ( '__none__' === $column_value ) {
					$html = '<i class="s-text-color-gray">' . esc_html__( 'none', 'upstream' ) . '</i>';
				} else {
					$html = esc_html( $column_value );
				}
			}

			$html = '<br>' . $html;

			if ( $is_hidden ) {
				$html = '<span data-value="' . esc_attr( $column_value ) . '">' . $html . '</span>';
			}
		} else {
			if ( is_array( $column_value ) ) {
				if ( isset( $column_value[0] ) ) {
					$column_value = $column_value[0];
				} else {
					$column_value = '';
				}
			}

			$column_value = trim( (string) $column_value );
			if ( strlen( $column_value ) > 0 ) {
				$html = esc_html( $column_value );
			}

			if ( $is_hidden ) {
				$html = '<span data-value="' . esc_attr( $column_value ) . '">' . $html . '</span>';
			}
		}
	} else {
		$html = '<span class="badge up-o-label" style="background-color:#666;color:#fff">(hidden)</span>';
	}

	$html = apply_filters(
		'upstream:frontend:project.table.body.td_value',
		$html,
		$column_name,
		$column_value,
		$column,
		$row,
		$row_type,
		$project_id
	);

	echo wp_kses_post( $html );
}

/**
 * Render Table Body
 *
 * @param  mixed $data Data.
 * @param  mixed $visible_columns_schema Visible Columns Schema.
 * @param  mixed $hidden_columns_schema Hidden Columns Schema.
 * @param  mixed $row_type Row Type.
 * @param  mixed $project_id Project Id.
 * @param  mixed $table_settings Table Settings.
 * @return void
 */
function render_table_body( $data, $visible_columns_schema, $hidden_columns_schema, $row_type, $project_id, $table_settings = array() ) {
	$visible_columns_schema_count = count( $visible_columns_schema );

	?>
	<tbody>
	<?php
	if ( count( $data ) > 0 ) :
		$is_row_index_odd = true;
		?>
		<?php
		foreach ( $data as $id => $row ) :
			$row_attrs = array(
				'class'   => 'is-filtered t-row-' . ( $is_row_index_odd ? 'odd' : 'even' ),
				'data-id' => $id,
			);

			if ( ! empty( $hidden_columns_schema ) ) {
				$row_attrs['class']        .= ' is-expandable';
				$row_attrs['aria-expanded'] = 'false';
			}

			$is_first = true;
			?>
		<tr <?php upstream_array_to_attrs( $row_attrs ); ?>>
			<?php
			foreach ( $visible_columns_schema as $column_name => $column ) :
				$column_value = isset( $row[ $column_name ] ) ? $row[ $column_name ] : null;

				if ( in_array( $column['type'], array( 'user', 'array' ), true ) ) {
					if ( ! is_array( $column_value ) ) {
						$column_value = array( (int) $column_value );
					}
				}

				if ( 'taxonomies' === $column['type'] && is_array( $column_value ) ) {
					$column_value = Milestones::getInstance()->get_categories_names( $column_value );
				}

				$column_attrs = array(
					'data-column' => $column_name,
					'data-value'  => is_array( $column_value ) ? implode( ', ', $column_value ) : $column_value,
					'data-type'   => $column['type'],
				);

				// Check if we have an specific value in the column, for ordering.
				$column_attrs['data-order'] = $column_attrs['data-value'];
				if ( isset( $row[ $column_name . '_order' ] ) ) {
					$column_attrs['data-order'] = $row[ $column_name . '_order' ];
				}

				$viewable = upstream_override_access_field( true, $row_type, $row['id'], UPSTREAM_ITEM_TYPE_PROJECT, $project_id, $column_name, UPSTREAM_PERMISSIONS_ACTION_VIEW );
				if ( ! $viewable ) {
					$column_attrs['data-value'] = '0';
					$column_attrs['data-order'] = '(hidden)';
				}

				if ( $is_first ) {
					$column_attrs['class'] = 'is-clickable';
					$column_attrs['role']  = 'button';
				}
				?>
				<td <?php upstream_array_to_attrs( $column_attrs ); ?>>
					<?php if ( $is_first ) : ?>
						<i class="fa fa-angle-right"></i>&nbsp;
					<?php endif; ?>

					<?php upstream_render_table_column_value( $column_name, $column_value, $column, $row, $row_type, $project_id ); ?>
				</td>


				<?php $is_first = false; ?>
			<?php endforeach; ?>

			<?php do_action( 'upstream_table_columns_data', $table_settings, $visible_columns_schema, $project_id, $row ); ?>
		</tr>

			<?php if ( ! empty( $hidden_columns_schema ) ) : ?>
		<tr data-parent="<?php echo esc_attr( $id ); ?>" aria-expanded="false" style="display: none;">
			<td colspan="<?php echo esc_attr( $visible_columns_schema_count ); ?>">
				<div>
					<?php
					foreach ( $hidden_columns_schema as $column_name => $column ) :
						$column_value = isset( $row[ $column_name ] ) ? $row[ $column_name ] : null;
						?>
						<div class="form-group" data-column="<?php echo esc_attr( $column_name ); ?>">
							<label><?php echo isset( $column['label'] ) ? esc_html( $column['label'] ) : ''; ?></label>
							<?php
							upstream_render_table_column_value(
								$column_name,
								$column_value,
								$column,
								$row,
								$row_type,
								$project_id
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</td>
		</tr>
				<?php
	endif;
			$is_row_index_odd = ! $is_row_index_odd;
			?>
	<?php endforeach; ?>
	<?php else : ?>
		<tr data-empty>
			<td colspan="<?php echo esc_attr( $visible_columns_schema_count ); ?>">
				<?php esc_html_e( 'No results found.', 'upstream' ); ?>
			</td>
		</tr>
	<?php endif; ?>
	</tbody>
	<?php
}

/**
 * Render Table
 *
 * @param  array  $table_attrs Table Attrs.
 * @param  array  $columns_schema Columns Schema.
 * @param  array  $data Data.
 * @param  string $item_type Item Type.
 * @param  int    $project_id Project Id.
 * @return void
 */
function upstream_render_table( $table_attrs = array(), $columns_schema = array(), $data = array(), $item_type = '', $project_id = 0 ) {
	$table_attrs['class'] = array_filter(
		isset( $table_attrs['class'] ) ? ( ! is_array( $table_attrs['class'] ) ? explode(
			' ',
			$table_attrs['class']
		) : (array) $table_attrs['class'] ) : array()
	);
	$table_attrs['class'] = array_unique(
		array_merge(
			$table_attrs['class'],
			array(
				'o-data-table',
				'table',
				'table-bordered',
				'table-responsive',
				'table-hover',
				'is-orderable',
			)
		)
	);

	$table_attrs['cellspacing'] = 0;
	$table_attrs['width']       = '100%';

	$visible_columns_schema = array();
	$hidden_columns_schema  = array();

	foreach ( $columns_schema as $column_name => $column_args ) {
		if ( isset( $column_args['isHidden'] ) && true === (bool) $column_args['isHidden'] ) {
			$hidden_columns_schema[ $column_name ] = $column_args;
		} else {
			$visible_columns_schema[ $column_name ] = $column_args;
		}
	}

	// Get the table ordering, if set.
	$table_id = array_key_exists( 'id', $table_attrs ) ? $table_attrs['id'] : '';

	if ( ! empty( $table_id ) ) {
		$ordering = upstream_get_table_order( $table_id );

		if ( ! empty( $ordering ) ) {
			$table_attrs['data-ordered-by'] = $ordering['column'];
			$table_attrs['data-order-dir']  = $ordering['orderDir'];
		}
	}

	$table_attrs['class'] = implode( ' ', $table_attrs['class'] );
	?>
	<table <?php upstream_array_to_attrs( $table_attrs ); ?>>
		<?php upstream_render_table_header( $visible_columns_schema, $item_type ); ?>
		<?php
		render_table_body(
			$data,
			$visible_columns_schema,
			$hidden_columns_schema,
			$item_type,
			$project_id,
			$table_attrs
		);
		?>
	</table>
	<?php
	$opt_arr     = array(
		'milestone' => upstream_milestone_label_plural(),
		'task'      => upstream_task_label_plural(),
		'bug'       => upstream_bug_label_plural(),
		'file'      => upstream_file_label_plural(),
	);
	$count_value = count( $data ) > 0 ? count( $data ) : '';
	echo "<span class='sub_count p_count' id='" . esc_attr( $item_type ) . "_count'>" . esc_html( $count_value ) . '</span>';
	?>
	<span class="p_count">
		<?php
		if ( count( $data ) > 0 ) {
			echo esc_html(
				sprintf(
					// translators: %s: Item name.
					__( ' %s found', 'upstream' ),
					$opt_arr[ $item_type ]
				)
			);
		}
		?>
	</span>
	<?php
}

/**
 * Render Table Filter
 *
 * @param  mixed $filter_type Filter Type.
 * @param  mixed $column_name Column Name.
 * @param  mixed $args Args.
 * @param  mixed $render_form_group Render Form Group.
 */
function upstream_render_table_filter( $filter_type, $column_name, $args = array(), $render_form_group = true ) {
	if ( ! in_array( $filter_type, array( 'search', 'select' ), true )
		|| empty( $column_name )
	) {
		return false;
	}

	$render_form_group = (bool) $render_form_group;

	$is_hidden = ! isset( $args['hidden'] ) || ( isset( $args['hidden'] ) && true === (bool) $args['hidden'] );

	if ( $render_form_group ) {
		echo '<div class="form-group">';
	}

	if ( 'search' === $filter_type ) {
		$input_attrs = array(
			'type'                  => 'search',
			'class'                 => 'form-control',
			'data-column'           => $column_name,
			'data-compare-operator' => isset( $args['operator'] ) ? $args['operator'] : 'contains',
		);

		if ( isset( $args['attrs'] ) && ! empty( $args['attrs'] ) ) {
			$input_attrs = array_merge( $args['attrs'], $input_attrs );
		}
		?>
		<div class="input-group">
			<div class="input-group-text">
				<i class="fa fa-search"></i>
			</div>
			<input <?php upstream_array_to_attrs( $input_attrs ); ?>>
		</div>
		<?php
	} elseif ( 'select' === $filter_type ) {
		$input_attrs = array(
			'class'                 => 'form-control o-select2',
			'data-column'           => $column_name,
			'multiple'              => 'multiple',
			'data-compare-operator' => isset( $args['operator'] ) ? $args['operator'] : 'contains',
		);

		if ( isset( $args['attrs'] ) && ! empty( $args['attrs'] ) ) {
			$input_attrs = array_merge( $args['attrs'], $input_attrs );
		}

		$has_icon = isset( $args['icon'] ) && ! empty( $args['icon'] );
		if ( $has_icon ) :
			?>
			<div class="input-group">
			<div class="input-group-text">
				<i class="fa fa-filter"></i>
			</div>
			<?php endif; ?>

		<select <?php upstream_array_to_attrs( $input_attrs ); ?>>
			<option value></option>
			<option value="__none__"><?php esc_html_e( 'None', 'upstream' ); ?></option>
		<?php
		if ( isset( $args['options'] ) && is_array( $args['options'] ) && count( $args['options'] ) ) :
			?>
			<?php foreach ( $args['options'] as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( (string) $option_value ); ?>"><?php echo esc_html( $option_label ); ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>

			<?php if ( $has_icon ) : ?>
			</div>
				<?php
			endif;
	}

	if ( $render_form_group ) {
		echo '</div>';
	}
}

/**
 * Get Table Order Option
 *
 * @param int $table_id Table Id.
 *
 * @return string
 */
function upstream_get_table_order_option( $table_id ) {
	$user_id = get_current_user_id();

	return 'upstream_ordering_' . $user_id . '_' . $table_id;
}

/**
 * Update Table Order
 *
 * @param mixed $table_id Table Id.
 * @param mixed $column Column.
 * @param mixed $dir Dir.
 */
function upstream_update_table_order( $table_id, $column, $dir ) {
	// Update the ordering data for the table.
	$data = maybe_serialize(
		array(
			'column'   => $column,
			'orderDir' => $dir,
		)
	);

	$option = upstream_get_table_order_option( $table_id );

	update_option( $option, $data );
}

/**
 * Get Table Order
 *
 * @param int $table_id Table Id.
 *
 * @return array
 */
function upstream_get_table_order( $table_id ) {
	$option = upstream_get_table_order_option( $table_id );

	$value = maybe_unserialize( get_option( $option ) );

	if ( ! is_array( $value ) || ! array_key_exists( 'column', $value ) || ! array_key_exists( 'orderDir', $value ) ) {
		$value = false;
	}

	return $value;
}

/**
 * Get Section Collapse State Option
 *
 * @param  mixed $section Section.
 */
function upstream_get_section_collapse_state_option( $section ) {
	$user_id = get_current_user_id();

	return 'upstream_collapse_state_' . $user_id . '_' . $section;
}

/**
 * Update Section Collapse State
 *
 * @param  mixed $section Section.
 * @param  mixed $state State.
 * @return void
 */
function upstream_update_section_collapse_state( $section, $state ) {
	$option = upstream_get_section_collapse_state_option( $section );

	$state = sanitize_text_field( $state );

	update_option( $option, $state );
}

/**
 * Get Section Collapse State
 *
 * @param  mixed $section Section.
 */
function upstream_get_section_collapse_state( $section ) {
	$option = upstream_get_section_collapse_state_option( $section );

	$value = get_option( $option );

	if ( empty( $value ) ) {
		$value = false;
	}

	return $value;
}

/**
 * Update Panel Order
 *
 * @param mixed $rows Rows.
 */
function upstream_update_panel_order( $rows ) {
	$option = 'upstream_panel_order';

	$value = array();

	foreach ( $rows as $row ) {
		$row = sanitize_text_field( $row );
		$row = str_replace( 'project-section-', '', $row );

		if ( ! empty( $row ) ) {
			$value[] = $row;
		}
	}

	update_option( $option, $value );
}

/**
 * Get Panel Order
 *
 * @return array
 */
function upstream_get_panel_order() {
	return get_option( 'upstream_panel_order' );
}
