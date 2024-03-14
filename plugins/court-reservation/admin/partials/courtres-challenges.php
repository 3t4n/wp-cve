<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

if ( ! current_user_can( 'list_users' ) ) {
	wp_die(
		'<h1>' . esc_html__( 'You need a higher level of permission.', 'court-reservation' ) . '</h1>' .
		'<p>' . esc_html__( 'Sorry, you are not allowed to list users.', 'court-reservation' ) . '</p>',
		403
	);
}

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	// deleting the challenge
if ( isset( $_POST['id'] ) && isset( $_POST['delete'] ) ) {
	$challenge_class = new Courtres_Entity_Challenges( intval( $_POST['id'] ) );
	// first delete linked challenge event
	global $wpdb;
	$res = $wpdb->delete( $this->getTable( 'events' ), array( 'id' => $challenge_class->get_event_id() ) );
	// then delete the challenge
	$challenge_class->delete_by_id();
}
	// <

	$columns           = Courtres_Entity_Challenges::get_admin_table_columns();
	$columns['action'] = array(
		'code'  => 'action',
		'title' => __( 'Action', 'court-reservation' ),
	); // Added action to head

	$column_titles    = wp_list_pluck( $columns, 'title' );
	$sortable_columns = array(
		'id'         => array( 'id', false ),
		'piramid_id' => array( 'piramid_id', false ),
		'status'     => array( 'status', false ),
		'start_ts'   => array( 'start_ts', false ),
		'end_ts'     => array( 'end_ts', false ),
	);

	$is_view_expired = isset( $_REQUEST['view-expired'] ) && $_REQUEST['view-expired'] == 1;
	if ( $is_view_expired ) {
		$challenges = Courtres_Entity_Challenges::get_expired();
	} else {
		$challenges = Courtres_Entity_Challenges::get_challenges();
	}

	// prepare some data to display
	foreach ( $challenges as &$challenge ) {
		$challenge['challenger_id'] = $challenge['challenger_id'] ? $challenge['challenger']['wp_user']->display_name : false;
		$challenge['challenged_id'] = $challenge['challenged_id'] ? $challenge['challenged']['wp_user']->display_name : false;
		$challenge['winner_id']     = $challenge['winner_id'] ? $challenge['winner']['wp_user']->display_name : false;
		$challenge['start_ts']      = $challenge['start_ts'] ? date_i18n( $date_format, $challenge['start_ts'] ) . ' ' . date_i18n( $time_format, $challenge['start_ts'] ) : false;
		$challenge['end_ts']        = $challenge['end_ts'] ? date_i18n( $date_format, $challenge['end_ts'] ) . ' ' . date_i18n( $time_format, $challenge['end_ts'] ) : false;

		$challenge = array_intersect_key( $challenge, $columns );

		$challenge['action'] = ( $challenge['status'] == 'accepted' || $challenge['status'] == 'scheduled' ) ? sprintf( '<form method="POST"><input type="hidden" name="id" value="%d"><input class="button" type="submit" name="delete" value="%s"></form>', $challenge['id'], __( 'Delete', 'court-reservation' ) ) : false;  // Added delete action to accepted challenges only
	}

	$challengesListTable = new Courtres_Base_List_Table();
	$challengesListTable->set_columns( $column_titles );
	$challengesListTable->set_sortable_columns( $sortable_columns );
	$challengesListTable->set_table_data( $challenges );
	$challengesListTable->set_limit( 20 );
	$challengesListTable->prepare_items();
	?>

<?php require 'courtres-notice-upgrade.php'; ?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html__( 'Challenges', 'court-reservation' ); ?></h1>
	<div class="cr-head-right">
		<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-challenges&view-expired=1' )); ?>" class="button button-secondary action<?php echo ( $is_view_expired ? ' active' : '' ); ?>"><?php esc_html_e( 'View Expired', 'courtres' ); ?></a>
		<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-challenges' )); ?>" class="button button-secondary action"><span class="dashicons dashicons-no-alt" style="vertical-align: -5px;"></span><?php esc_html_e( 'Clear Filter', 'courtres' ); ?></a>
		<form method="post" action="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>">
		   <?php wp_nonce_field( 'export_expired', 'export_expired_nonce' ); ?>
			<input type="hidden" name="target" value="challenges" />
			<input type="hidden" name="action" value="download_csv" />
		   <?php submit_button( __( 'Export Expired', 'court-reservation' ) ); ?>
		</form>
	</div>
	<hr class="wp-header-end">
	<?php $challengesListTable->display(); ?>
</div>


