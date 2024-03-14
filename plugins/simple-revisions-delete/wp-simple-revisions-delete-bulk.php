<?php
/**
 * Add a bulk action
 * Source: http://www.skyverge.com/blog/add-custom-bulk-action/
 * Source: jetpack/modules/custom-post-types/comics.php
 */

/**
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}

/**
 * Add the purge option in the bulk select
 */
add_action( 'admin_footer-edit.php', 'wpsrd_purge_select_bulk_action' );
function wpsrd_purge_select_bulk_action() {
	global $post_type;

	$post_type_list = wpsrd_post_types_default();

	if ( in_array( $post_type, $post_type_list ) ) {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('<option>').val('wpsrd-purge').text('<?php _e( 'Purge revisions', 'simple-revisions-delete' ); ?>').appendTo("select[name='action'], select[name='action2']");
			});
		</script>
		<?php
	}
}

/**
 * Add the purge option in the bulk select
 */
add_action( 'load-edit.php', 'wpsrd_purge_bulk_action' );
function wpsrd_purge_bulk_action() {

	if ( empty( $_REQUEST['post'] ) ) {
		return;
	}

	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	$action        = $wp_list_table->current_action();

	if ( 'wpsrd-purge' == $action ) {

		// Security check
		check_admin_referer( 'bulk-posts' );

		$revisions_count = 0;
		$post_ids        = array_map( 'intval', $_REQUEST['post'] );

		foreach ( $post_ids as $post_id ) {

			$post_type_list = wpsrd_post_types_default();
			$user_cap       = apply_filters( 'wpsrd_capability', 'delete_post' );

			$extra_notice = '';
			if ( $user_cap == 'delete_post' ) {
				$extra_notice = '&nbsp;&nbsp;&nbsp;<i style="font-weight:normal">' . __( 'Note: You can only purge revisions for the posts you\'re allowed to delete', 'simple-revisions-delete' ) . '</i>';
			}

			if ( current_user_can( $user_cap, $post_id ) && in_array( get_post_type( $post_id ), $post_type_list ) ) {

				$revisions = wp_get_post_revisions( $post_id );

				//Check revisions & delete them
				if ( isset( $revisions ) && ! empty( $revisions ) ) {

					foreach ( $revisions as $revision ) {
						$rev_delete = wp_delete_post_revision( $revision );

						if ( is_wp_error( $rev_delete ) ) {
							//Extra error notice if WP error return something
							$output_wp_error = $rev_delete->get_error_message();
						} else {
							$revisions_count++;

							$output = array(
								'success' => 'success',
								'data'    => sprintf( _n( '1 revision has been deleted', '%s revisions have been deleted', $revisions_count, 'simple-revisions-delete' ), $revisions_count ) . $extra_notice,
							);
						}
					}
				}
			}
		}

		if ( $revisions_count == 0 ) {
			$output = array(
				'success' => 'error',
				'data'    => __( 'No revision to delete', 'simple-revisions-delete' ) . $extra_notice,
			);
		}

		//Prepare the WP ERROR notice
		if ( isset( $output_wp_error ) && ! empty( $output_wp_error ) ) {
			add_settings_error(
				'wpsrd-admin-notice',
				'wpsrd_notice_WP_error',
				$output_wp_error,
				'error'
			);
		}

		//Prepare the default notice
		if ( ! empty( $output ) ) {
			add_settings_error(
				'wpsrd-admin-notice',
				'wpsrd_notice',
				$output['data'],
				( $output['success'] == 'success' ? 'updated' : 'error' )
			);
		}

		//Store the notice(s) for the redirection
		set_transient( 'wpsrd_settings_errors', get_settings_errors(), 30 );

		//cleanup the arguments
		$sendback = remove_query_arg( array( 'exported', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );

		if ( ! $sendback ) {
			$sendback = add_query_arg( array( 'post_type', get_post_type() ), admin_url( 'edit.php' ) );
		}

		//retrieve the pagination
		$pagenum  = $wp_list_table->get_pagenum();
		$sendback = add_query_arg(
			array(
				'paged'      => $pagenum,
				'rev_purged' => $revisions_count,
			),
			$sendback
		);

		wp_safe_redirect( $sendback );
		exit();

	}

}
