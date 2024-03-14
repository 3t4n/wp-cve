<?php
/**
 * Handle label functions
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get Default Labels
 *
 * @return array $defaults Default labels
 * @since 1.0.0
 */
function upstream_get_default_labels() {
	$option = get_option( 'upstream_general' );

	$defaults = array(
		'projects'             => array(
			'singular' => isset( $option['project']['single'] ) ? $option['project']['single'] : __(
				'Project',
				'upstream'
			),
			'plural'   => isset( $option['project']['plural'] ) ? $option['project']['plural'] : __(
				'Projects',
				'upstream'
			),
		),
		'clients'              => array(
			'singular' => isset( $option['client']['single'] ) ? $option['client']['single'] : __(
				'Client',
				'upstream'
			),
			'plural'   => isset( $option['client']['plural'] ) ? $option['client']['plural'] : __(
				'Clients',
				'upstream'
			),
		),
		'milestones'           => array(
			'singular' => isset( $option['milestone']['single'] ) ? $option['milestone']['single'] : __(
				'Milestone',
				'upstream'
			),
			'plural'   => isset( $option['milestone']['plural'] ) ? $option['milestone']['plural'] : __(
				'Milestones',
				'upstream'
			),
		),
		'milestone_categories' => array(
			'singular' => isset( $option['milestone_categories']['single'] ) ? $option['milestone_categories']['single'] : __(
				'Milestone Category',
				'upstream'
			),
			'plural'   => isset( $option['milestone_categories']['plural'] ) ? $option['milestone_categories']['plural'] : __(
				'Milestone Categories',
				'upstream'
			),
		),
		'tasks'                => array(
			'singular' => isset( $option['task']['single'] ) ? $option['task']['single'] : __( 'Task', 'upstream' ),
			'plural'   => isset( $option['task']['plural'] ) ? $option['task']['plural'] : __( 'Tasks', 'upstream' ),
		),
		'bugs'                 => array(
			'singular' => isset( $option['bug']['single'] ) ? $option['bug']['single'] : __( 'Bug', 'upstream' ),
			'plural'   => isset( $option['bug']['plural'] ) ? $option['bug']['plural'] : __( 'Bugs', 'upstream' ),
		),
		'files'                => array(
			'singular' => isset( $option['file']['single'] ) ? $option['file']['single'] : __( 'File', 'upstream' ),
			'plural'   => isset( $option['file']['plural'] ) ? $option['file']['plural'] : __( 'Files', 'upstream' ),
		),
		'discussion'           => array(
			'singular' => isset( $option['discussion']['single'] ) ? $option['discussion']['single'] : __(
				'Discussion',
				'upstream'
			),
			'plural'   => isset( $option['discussion']['plural'] ) ? $option['discussion']['plural'] : __(
				'Discussions',
				'upstream'
			),
		),
	);

	return apply_filters( 'upstream_default_labels', $defaults );
}

/**
 * Get Project Labels
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_project_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['projects']['singular'] ) : $defaults['projects']['singular'];

	return $label;
}

/**
 * Upstream_project_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_project_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['projects']['plural'] ) : $defaults['projects']['plural'];

	return $label;
}

/**
 * Get Client Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_client_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['clients']['singular'] ) : $defaults['clients']['singular'];

	return $label;
}

/**
 * Upstream_client_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_client_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['clients']['plural'] ) : $defaults['clients']['plural'];

	return $label;
}

/**
 * Get Milestone Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_milestone_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['milestones']['singular'] ) : $defaults['milestones']['singular'];

	return $label;
}

/**
 * Upstream_milestone_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_milestone_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['milestones']['plural'] ) : $defaults['milestones']['plural'];

	return $label;
}

/**
 * Get Milestone Category Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_milestone_category_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['milestone_categories']['singular'] ) : $defaults['milestone_categories']['singular'];

	return $label;
}

/**
 * Upstream_milestone_category_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_milestone_category_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['milestone_categories']['plural'] ) : $defaults['milestone_categories']['plural'];

	return $label;
}

/**
 * Get Task Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_task_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['tasks']['singular'] ) : $defaults['tasks']['singular'];

	return $label;
}

/**
 * Upstream_task_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_task_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['tasks']['plural'] ) : $defaults['tasks']['plural'];

	return $label;
}

/**
 * Get Bug Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_bug_label( $lowercase = false ) {
	 $defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['bugs']['singular'] ) : $defaults['bugs']['singular'];

	return $label;
}

/**
 * Upstream_bug_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_bug_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['bugs']['plural'] ) : $defaults['bugs']['plural'];

	return $label;
}

/**
 * Get file Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_file_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['files']['singular'] ) : $defaults['files']['singular'];

	return $label;
}

/**
 * Upstream_file_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_file_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['files']['plural'] ) : $defaults['files']['plural'];

	return $label;
}

/**
 * Get discussion Label
 *
 * @param bool $lowercase Set to lowercase.
 *
 * @return string $defaults['singular'] Singular label
 * @since 1.0.0
 */
function upstream_discussion_label( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['discussion']['singular'] ) : $defaults['discussion']['singular'];

	return $label;
}

/**
 * Upstream_discussion_label_plural
 *
 * @param bool $lowercase Set to lowercase.
 */
function upstream_discussion_label_plural( $lowercase = false ) {
	$defaults = upstream_get_default_labels();

	$label = ( $lowercase ) ? strtolower( $defaults['discussion']['plural'] ) : $defaults['discussion']['plural'];

	return $label;
}

/**
 * Change default "Enter title here" input
 *
 * @param string $title Default title placeholder text.
 *
 * @return string $title New placeholder text
 * @since 1.0.0
 */
function upstream_change_default_title( $title ) {
	$screen = get_current_screen();

	switch ( $screen->post_type ) {
		case 'project':
			$label = upstream_project_label();
			$title = sprintf(
				// translators: %s: label.
				__( 'Enter %s name here', 'upstream' ),
				$label
			);
			break;
		case 'client':
			$label = upstream_client_label();
			$title = sprintf(
				// translators: %s: label.
				__( 'Enter %s name here', 'upstream' ),
				$label
			);
			break;

	}

	return $title;
}
add_filter( 'enter_title_here', 'upstream_change_default_title' );

/**
 * Get the singular and plural labels for a project taxonomy
 *
 * @param string $taxonomy The Taxonomy to get labels for.
 *
 * @return array            Associative array of labels (name = plural)
 * @since  1.0.0
 */
function upstream_get_taxonomy_labels( $taxonomy = 'project_category' ) {
	$allowed_taxonomies = apply_filters( 'upstream_allowed_project_taxonomies', array( 'project_category' ) );

	if ( ! in_array( $taxonomy, $allowed_taxonomies, true ) ) {
		return false;
	}

	$labels   = array();
	$taxonomy = get_taxonomy( $taxonomy );

	if ( false !== $taxonomy ) {
		$singular = $taxonomy->labels->singular_name;
		$name     = $taxonomy->labels->name;

		$labels = array(
			'name'          => $name,
			'singular_name' => $singular,
		);
	}

	return apply_filters( 'upstream_get_taxonomy_labels', $labels, $taxonomy );
}


/**
 * Updated Messages
 *
 * Returns an array of with all updated messages.
 *
 * @param array $messages Post updated message.
 *
 * @return  array $messages New post updated messages
 * @since   1.0.0
 */
function upstream_updated_messages( $messages ) {
	global $post_ID;

	$post_url           = get_permalink( $post_ID );
	$anchor_tag_opening = '<a href="' . esc_attr( $post_url ) . '" target="_blank" rel="noopener noreferrer">';
	$anchor_tag_closing = '</a>';

	$post_type_label_project = upstream_project_label();
	$post_type_label_client  = upstream_client_label();

	$messages['project'] = array(
		1 => sprintf(
			// translators: %1$s: anchor_tag_opening.
			// translators: %2$s: post_type_label_project.
			// translators: %3$s: anchor_tag_closing.
			__( '%2$s updated. %1$sView %2$s%3$s', 'upstream' ),
			$anchor_tag_opening,
			$post_type_label_project,
			$anchor_tag_closing
		),
		4 => sprintf(
			// translators: %1$s: anchor_tag_opening.
			// translators: %2$s: post_type_label_project.
			// translators: %3$s: anchor_tag_closing.
			__( '%2$s updated. %1$sView %2$s%3$s', 'upstream' ),
			$anchor_tag_opening,
			$post_type_label_project,
			$anchor_tag_closing
		),
		6 => sprintf(
			// translators: %1$s: anchor_tag_opening.
			// translators: %2$s: post_type_label_project.
			// translators: %3$s: anchor_tag_closing.
			__( '%2$s published. %1$sView %2$s%3$s', 'upstream' ),
			$anchor_tag_opening,
			$post_type_label_project,
			$anchor_tag_closing
		),
		7 => sprintf(
			// translators: %1$s: anchor_tag_opening.
			// translators: %2$s: post_type_label_project.
			// translators: %3$s: anchor_tag_closing.
			__( '%2$s saved. %1$sView %2$s%3$s', 'upstream' ),
			$anchor_tag_opening,
			$post_type_label_project,
			$anchor_tag_closing
		),
		8 => sprintf(
			// translators: %1$s: anchor_tag_opening.
			// translators: %2$s: post_type_label_project.
			// translators: %3$s: anchor_tag_closing.
			__( '%2$s submitted. %1$sView %2$s%3$s', 'upstream' ),
			$anchor_tag_opening,
			$post_type_label_project,
			$anchor_tag_closing
		),
	);

	$messages['client'] = array(
		1 => sprintf(
			// translators: %1$s: post_type_label_client.
			__( '%1$s updated.', 'upstream' ),
			$post_type_label_client
		),
		4 => sprintf(
			// translators: %1$s: post_type_label_client.
			__( '%1$s updated.', 'upstream' ),
			$post_type_label_client
		),
		6 => sprintf(
			// translators: %1$s: post_type_label_client.
			__( '%1$s published.', 'upstream' ),
			$post_type_label_client
		),
		7 => sprintf(
			// translators: %1$s: post_type_label_client.
			__( '%1$s saved.', 'upstream' ),
			$post_type_label_client
		),
		8 => sprintf(
			// translators: %1$s: post_type_label_client.
			__( '%1$s submitted.', 'upstream' ),
			$post_type_label_client
		),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'upstream_updated_messages' );

/**
 * Updated bulk messages
 *
 * @param array $bulk_messages Post updated messages.
 * @param array $bulk_counts   Post counts.
 *
 * @return  array $bulk_messages New post updated messages
 * @since 2.3
 */
function upstream_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	$items_updated_count   = (int) $bulk_counts['updated'];
	$items_locked_count    = (int) $bulk_counts['locked'];
	$items_deleted_count   = (int) $bulk_counts['deleted'];
	$items_trashed_count   = (int) $bulk_counts['trashed'];
	$items_untrashed_count = (int) $bulk_counts['untrashed'];

	$post_type_client_label_singular = upstream_client_label();
	$post_type_client_label_plural   = upstream_client_label_plural();

	$post_type_project_label_singular = upstream_project_label();
	$post_type_project_label_plural   = upstream_project_label_plural();

	$bulk_messages['client'] = array(
		'updated'   => sprintf(
			// translators: %1$s: items_updated_count.
			// translators: %2$s: post_type_client_label_singular.
			// translators: %3$s: post_type_client_label_plural.
			_n(
				'%1$s %2$s updated.',
				'%1$s %3$s updated.',
				$items_updated_count,
				'upstream'
			),
			$items_updated_count,
			$post_type_client_label_singular,
			$post_type_client_label_plural
		),
		'locked'    => sprintf(
			// translators: %1$s: items_locked_count.
			// translators: %2$s: post_type_client_label_singular.
			// translators: %3$s: post_type_client_label_plural.
			_n(
				'%1$s %2$s not updated, somebody is editing it.',
				'%1$s %3$s not updated, somebody is editing them.',
				$items_locked_count,
				'upstream'
			),
			$items_locked_count,
			$post_type_client_label_singular,
			$post_type_client_label_plural
		),
		'deleted'   => sprintf(
			// translators: %1$s: items_deleted_count.
			// translators: %2$s: post_type_client_label_singular.
			// translators: %3$s: post_type_client_label_plural.
			_n(
				'%1$s %2$s permanently deleted.',
				'%1$s %3$s permanently deleted.',
				$items_deleted_count,
				'upstream'
			),
			$items_deleted_count,
			$post_type_client_label_singular,
			$post_type_client_label_plural
		),
		'trashed'   => sprintf(
			// translators: %1$s: items_trashed_count.
			// translators: %2$s: post_type_client_label_singular.
			// translators: %3$s: post_type_client_label_plural.
			_n(
				'%1$s %2$s moved to the Trash.',
				'%1$s %3$s moved to the Trash.',
				$items_trashed_count,
				'upstream'
			),
			$items_trashed_count,
			$post_type_client_label_singular,
			$post_type_client_label_plural
		),
		'untrashed' => sprintf(
			// translators: %1$s: items_untrashed_count.
			// translators: %2$s: post_type_client_label_singular.
			// translators: %3$s: post_type_client_label_plural.
			_n(
				'%1$s %2$s restored from the Trash.',
				'%1$s %3$s restored from the Trash.',
				$items_untrashed_count,
				'upstream'
			),
			$items_untrashed_count,
			$post_type_client_label_singular,
			$post_type_client_label_plural
		),
	);

	$bulk_messages['project'] = array(
		'updated'   => sprintf(
			// translators: %1$s : items_updated_count.
			// translators: %2$s : post_type_project_label_singular.
			// translators: %3$s : post_type_project_label_plural.
			_n(
				'%1$s %2$s updated.',
				'%1$s %3$s updated.',
				$items_updated_count,
				'upstream'
			),
			$items_updated_count,
			$post_type_project_label_singular,
			$post_type_project_label_plural
		),
		'locked'    => sprintf(
			// translators: %1$s : items_locked_count.
			// translators: %2$s : post_type_project_label_singular.
			// translators: %3$s : post_type_project_label_plural.
			_n(
				'%1$s %2$s not updated, somebody is editing it.',
				'%1$s %3$s not updated, somebody is editing them.',
				$items_locked_count,
				'upstream'
			),
			$items_locked_count,
			$post_type_project_label_singular,
			$post_type_project_label_plural
		),
		'deleted'   => sprintf(
			// translators: %1$s : items_deleted_count.
			// translators: %2$s : post_type_project_label_singular.
			// translators: %3$s : post_type_project_label_plural.
			_n(
				'%1$s %2$s permanently deleted.',
				'%1$s %3$s permanently deleted.',
				$items_deleted_count,
				'upstream'
			),
			$items_deleted_count,
			$post_type_project_label_singular,
			$post_type_project_label_plural
		),
		'trashed'   => sprintf(
			// translators: %1$s : items_trashed_count.
			// translators: %2$s : post_type_project_label_singular.
			// translators: %3$s : post_type_project_label_plural.
			_n(
				'%1$s %2$s moved to the Trash.',
				'%1$s %3$s moved to the Trash.',
				$items_trashed_count,
				'upstream'
			),
			$items_trashed_count,
			$post_type_project_label_singular,
			$post_type_project_label_plural
		),
		'untrashed' => sprintf(
			// translators: %1$s : items_untrashed_count.
			// translators: %2$s : post_type_project_label_singular.
			// translators: %3$s : post_type_project_label_plural.
			_n(
				'%1$s %2$s restored from the Trash.',
				'%1$s %3$s restored from the Trash.',
				$items_untrashed_count,
				'upstream'
			),
			$items_untrashed_count,
			$post_type_project_label_singular,
			$post_type_project_label_plural
		),
	);

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'upstream_bulk_updated_messages', 10, 2 );

/**
 * Display UpStream notices-errors near the top of admin pages.
 *
 * @since   1.9.0
 */
function upstream_admin_notices_errors() {
	$errors = get_transient( 'upstream_errors' );

	if ( ! empty( $errors ) ) : ?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php echo esc_html( $errors ); ?>
			</p>
		</div>
		<?php
		delete_transient( 'upstream_errors' );
	endif;
}
add_filter( 'admin_notices', 'upstream_admin_notices_errors' );
