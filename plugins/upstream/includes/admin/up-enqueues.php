<?php
/**
 * Handle admin scripts and styles enqueues
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues the required admin scripts.
 *
 * @param  mixed $hook Hook.
 * @return void
 */
function upstream_load_admin_scripts( $hook ) {
	$is_admin = is_admin();
	if ( ! $is_admin ) {
		return;
	}

	$get_data = isset( $_GET ) ? wp_unslash( $_GET ) : array();

	$post_type = get_post_type();
	if ( empty( $post_type ) ) {
		// checked later if in array of valid post types.
		$post_type = isset( $get_data['post_type'] ) ? sanitize_text_field( $get_data['post_type'] ) : '';
	}

	$assets_dir         = UPSTREAM_PLUGIN_URL . 'includes/admin/assets/';
	$global_assets_path = UPSTREAM_PLUGIN_URL . 'templates/assets/';
	$admin_deps         = array( 'jquery', 'cmb2-scripts', 'allex', 'jquery-ui-datepicker' );

	global $pagenow;

	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style(
		'jquery-ui-datepicker',
		$assets_dir . '/css/jquery.ui.datepicker.css',
		array( 'wp-jquery-ui-dialog' ),
		UPSTREAM_VERSION,
		'screen'
	);
	wp_enqueue_style(
		'jquery-ui-theme',
		$assets_dir . '/css/jquery.ui.theme.css',
		false,
		UPSTREAM_VERSION,
		'screen'
	);

	wp_enqueue_script(
		'terminal',
		$assets_dir . 'js/jquery.terminal.min.js',
		array( 'jquery' ),
		UPSTREAM_VERSION,
		false
	);

	wp_enqueue_script(
		'upstream-admin',
		$assets_dir . 'js/admin.js',
		array( 'jquery', 'wp-color-picker', 'allex', 'terminal' ),
		UPSTREAM_VERSION,
		false
	);

	wp_localize_script(
		'upstream-admin',
		'upstreamAdmin',
		array(
			'LB_RESETTING'                      => __( 'Resetting...', 'upstream' ),
			'LB_REFRESHING'                     => __( 'Refreshing...', 'upstream' ),
			'MSG_CONFIRM_RESET_CAPABILITIES'    => __( 'Are you sure you want to reset the capabilities?', 'upstream' ),
			'MSG_CONFIRM_REFRESH_PROJECTS_META' => __(
				'Are you sure you want to refresh the projects meta data?',
				'upstream'
			),
			'MSG_CONFIRM_CLEANUP_UPDATE_CACHE'  => __(
				'Are you sure you want to cleanup the cached data about updates?',
				'upstream'
			),
			'MSG_CONFIRM_IMPORT'                => __( 'Are you sure you want to perform an import? MAKE SURE YOU HAVE BACKED UP YOUR DATA FIRST!', 'upstream' ),
			'MSG_CAPABILITIES_RESETED'          => __( 'Success!', 'upstream' ),
			'MSG_CAPABILITIES_ERROR'            => __( 'Error!', 'upstream' ),
			'MSG_PROJECTS_SUCCESS'              => __( 'Success!', 'upstream' ),
			'MSG_PROJECTS_META_ERROR'           => __( 'Error!', 'upstream' ),
			'MSG_CLEANUP_UPDATE_DATA_ERROR'     => __( 'Error cleaning up the cached data!', 'upstream' ),
			'MSG_IMPORT_ERROR'                  => __( 'Error importing data!', 'upstream' ),
			'datepickerDateFormat'              => upstream_get_date_format_for_js_datepicker(),
		)
	);

	if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ), true ) ) {

		$milestone_instance  = \UpStream\Milestones::getInstance();
		$milestone_post_type = $milestone_instance->get_post_type();
		$valid_post_types    = apply_filters( 'upstream_admin_script_valid_post_types', array( 'project', 'upst_milestone' ) );

		wp_enqueue_style(
			'up-select2',
			$global_assets_path . 'css/vendor/select2.min.css',
			array(),
			UPSTREAM_VERSION,
			'all'
		);
		wp_enqueue_script(
			'up-select2',
			$global_assets_path . 'js/vendor/select2.full.min.js',
			array(),
			UPSTREAM_VERSION,
			true
		);
		unset( $global_assets_path );

		if ( in_array( $post_type, $valid_post_types, true ) ) {
			global $post_type_object;

			wp_register_script(
				'upstream-project',
				$assets_dir . 'js/edit-project.js',
				array_merge( $admin_deps, array( 'up-select2' ) ),
				UPSTREAM_VERSION,
				false
			);

			wp_enqueue_script( 'upstream-project' );
			wp_localize_script(
				'upstream-project',
				'upstream_project',
				apply_filters(
					'upstream_project_script_vars',
					array(
						'version' => UPSTREAM_VERSION,
						'user'    => upstream_current_user_id(),
						'slugBox' => ! ( get_post_status() === 'pending' && ! current_user_can( $post_type_object->cap->publish_posts ) ),
						'l'       => array(
							'LB_CANCEL'               => __( 'Cancel' ),
							'LB_SEND_REPLY'           => __( 'Add Reply', 'upstream' ),
							'LB_REPLY'                => __( 'Reply' ),
							'LB_ADD_COMMENT'          => __( 'Add Comment', 'upstream' ),
							'LB_ADD_NEW_COMMENT'      => __( 'Add new Comment' ),
							'LB_ADD_NEW_REPLY'        => __( 'Add Comment Reply', 'upstream' ),
							'LB_ADDING'               => __( 'Adding...', 'upstream' ),
							'LB_REPLYING'             => __( 'Replying...', 'upstream' ),
							'LB_DELETE'               => __( 'Delete', 'upstream' ),
							'LB_DELETING'             => __( 'Deleting...', 'upstream' ),
							'LB_UNAPPROVE'            => __( 'Unapprove' ),
							'LB_UNAPPROVING'          => __( 'Unapproving...', 'upstream' ),
							'LB_APPROVE'              => __( 'Approve', 'upstream' ),
							'LB_APPROVING'            => __( 'Approving...', 'upstream' ),
							'MSG_ARE_YOU_SURE'        => __(
								'Are you sure? This action cannot be undone.',
								'upstream'
							),
							'MSG_COMMENT_NOT_VIS'     => __(
								'This comment is not visible by regular users.',
								'upstream'
							),
							'LB_ASSIGNED_TO'          => __( 'Assigned To', 'upstream' ),
							'MSG_TITLE_CANT_BE_EMPTY' => __( 'Title can\'t be empty', 'upstream' ),
							'MSG_INVALID_INTERVAL_BETWEEN_DATE' => __( 'Invalid interval between dates.', 'upstream' ),
							'MSG_NO_CLIENT_SELECTED'  => __( 'No client selected', 'upstream' ),
							'MSG_NO_RESULTS'          => __( 'No results', 'upstream' ),
						),
					)
				)
			);

			if ( 'upst_milestone' === $post_type ) {
				wp_enqueue_script(
					'upstream-milestone',
					$assets_dir . 'js/edit-milestone.js',
					array( 'jquery', 'up-select2' ),
					UPSTREAM_VERSION,
					false
				);
			}
		} elseif ( 'client' === $post_type ) {
			wp_enqueue_script(
				'up-metabox-client',
				$assets_dir . 'js/metabox-client.js',
				$admin_deps,
				UPSTREAM_VERSION,
				true
			);
			wp_localize_script(
				'up-metabox-client',
				'upstreamMetaboxClientLangStrings',
				array(
					'ERR_JQUERY_NOT_FOUND'     => __( 'UpStream requires jQuery.', 'upstream' ),
					'MSG_NO_ASSIGNED_USERS'    => __( "There's no users assigned yet.", 'upstream' ),
					'MSG_NO_USER_SELECTED'     => __( 'Please, select at least one user', 'upstream' ),
					'MSG_ADD_ONE_USER'         => __( 'Add 1 User', 'upstream' ),
					// translators: %d: user count.
					'MSG_ADD_MULTIPLE_USERS'   => __( 'Add %d Users', 'upstream' ),
					'MSG_NO_USERS_FOUND'       => __( 'No users found.', 'upstream' ),
					'LB_ADDING_USERS'          => __( 'Adding...', 'upstream' ),
					'MSG_ARE_YOU_SURE'         => __( 'Are you sure? This action cannot be undone.', 'upstream' ),
					'MSG_FETCHING_DATA'        => __( 'Fetching data...', 'upstream' ),
					'MSG_NO_DATA_FOUND'        => __( 'No data found.', 'upstream' ),
					// translators: %s: user label.
					'MSG_MANAGING_PERMISSIONS' => __( "Managing %s\'s Permissions", 'upstream' ),
				)
			);
		} elseif ( $post_type === $milestone_post_type ) {
			$global_assets_path = UPSTREAM_PLUGIN_URL . 'templates/assets/';
			wp_enqueue_style(
				'up-select2',
				$global_assets_path . 'css/vendor/select2.min.css',
				array(),
				UPSTREAM_VERSION,
				'all'
			);
			wp_enqueue_script(
				'up-select2',
				$global_assets_path . 'js/vendor/select2.full.min.js',
				array(),
				UPSTREAM_VERSION,
				true
			);
			unset( $global_assets_path );
		}

		$milestone_instance  = \UpStream\Milestones::getInstance();
		$post_types_using_cmb2 = apply_filters(
			'upstream:post_types_using_cmb2',
			array( 'project', 'client', $milestone_instance->get_post_type() )
		);

		if ( in_array( $post_type, $post_types_using_cmb2 ) ) {
			wp_enqueue_style( 'upstream-admin', $assets_dir . 'css/upstream.css', array(), UPSTREAM_VERSION );
		}
	} elseif ( 'admin.php' === $pagenow
			&& isset( $get_data['page'] )
			&& preg_match( '/^upstream_/i', sanitize_text_field( $get_data['page'] ) )
	) {
		wp_enqueue_style( 'upstream-admin', $assets_dir . 'css/upstream.css', array(), UPSTREAM_VERSION );
	}

	wp_enqueue_style( 'terminal', $assets_dir . 'css/jquery.terminal.min.css', array(), UPSTREAM_VERSION );
	wp_enqueue_style( 'upstream-admin-icon', $assets_dir . 'css/admin-upstream-icon.css', array(), UPSTREAM_VERSION );
	wp_enqueue_style( 'upstream-admin-style', $assets_dir . 'css/admin.css', array( 'allex' ), UPSTREAM_VERSION );
	wp_enqueue_style(
		'up-fontawesome',
		UPSTREAM_PLUGIN_URL . 'templates/assets/css/fontawesome.min.css',
		array(),
		UPSTREAM_VERSION,
		'all'
	);
}

add_action( 'admin_enqueue_scripts', 'upstream_load_admin_scripts', 100 );

add_filter(
	'cmb2_script_dependencies',
	function( $a ) {
		$a['cmb2-wysiwyg'] = 'cmb2-wysiwyg';
		return $a;
	}
);

do_action( 'upstream_admin_enqueue' );
