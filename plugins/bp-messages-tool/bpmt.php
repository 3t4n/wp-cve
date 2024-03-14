<?php

if ( !defined( 'ABSPATH' ) ) exit;

function bpmt_menu() {

	add_submenu_page( 'tools.php', 'BP Messages', 'BP Messages', 'manage_options', 'bp-messages-tool', 'bpmt_screen', 99 );
}
add_action( 'admin_menu', 'bpmt_menu' );


function bpmt_screen() {
?>
	<div class="wrap">
		<h2><?php _e( 'BP Messages Tool', 'bpmt' )?></h2>

		<?php bpmt_form(); ?>

		<?php
		if ( is_super_admin() && isset( $_GET['action'] ) ) {

			switch( $_GET['action'] ) {

				case 'select-member':
					bpmt_get_member();
					break;

				case 'member-threads':
					bpmt_get_member_page();
					break;

				case 'view-thread':
					bpmt_get_thread_view();
					break;

				case 'delete-thread':
					bpmt_delete_thread();
					break;

				case 'bulk-delete':
					bpmt_bulk_delete();
					break;

			}
		}
		elseif( is_super_admin() ) {

			bpmt_last_ten();
		}
		?>
	</div>
<?php
}

function bpmt_last_ten() {
	global $wpdb;

	//$last_ten = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bp_messages_messages ORDER BY date_sent DESC LIMIT 10" );

	$last_ten = $wpdb->get_results(
		"SELECT m.thread_id, m.sender_id, m.subject, m.message, m.date_sent, r.user_id AS receip_id
		FROM {$wpdb->prefix}bp_messages_messages AS m
		INNER JOIN {$wpdb->prefix}bp_messages_recipients AS r ON m.thread_id = r.thread_id
		WHERE r.sender_only = 0
		ORDER BY m.date_sent DESC LIMIT 10"
		);

	if ( $last_ten ) {

		include_once( dirname( 	__FILE__ ) . '/templates/bpmt-messages-last-ten.php' );

	} else {

		_e( 'No private BuddyPress messages were found', 'bpmt' );

	}

}

function bpmt_form() {
?>
	<p>
	<div class="wrap">
		<form action="<?php echo site_url(); ?>/wp-admin/tools.php?page=bp-messages-tool&action=select-member" name="bpmt-form" id="bpmt-form"  method="post" class="standard-form">

			<?php wp_nonce_field('bpmt-member-action', 'bpmt-member-field'); ?>

			<?php _e("Enter a Member's login name or user id: ", 'bpmt'); ?>

			<br><br>

			<input type="text" name="bpmt-user" id="bpmt" maxlength="50" />

			<br><br>

			<input type="radio" name="bpmt-box" value="inbox" checked><?php _e("Inbox", 'bpmt'); ?> &nbsp; <input type="radio" name="bpmt-box" value="sentbox"><?php _e("Sent", 'bpmt'); ?><br><br>

			<input type="submit" name="bpmt-submit"  id=""bpmt-submit" class="button button-primary" value="<?php _e('Get Messages', 'bpmt'); ?>">

		</form>
	</p>
<?php
}


function bpmt_get_member() {
	global $bpmt_user_data;

	if( isset( $_POST['bpmt-user'] ) ) {

		if( !wp_verify_nonce($_POST['bpmt-member-field'],'bpmt-member-action') )
			die('Security Check - Failed');

		if( ! empty( $_POST['bpmt-user'] ) )
			$bpmt_user = $_POST['bpmt-user'];
		else {
			_e("<div class='error below-h2'>ERROR -  Please enter a Member's login name or user id.</div>", 'bpmt');
			return;
		}
	}

	elseif( isset( $_GET['user_id'] ) )
		$bpmt_user = intval( $_GET['user_id'] );

	else {
		_e("<div class='error below-h2'>ERROR - There was a problem.</div>", 'bpmt');
		return;
	}

	$bpmt_user_data = bpmt_get_user_data( $bpmt_user );


	if( $bpmt_user_data != NULL ) {

		if( ( isset( $_POST['bpmt-box'] ) && $_POST['bpmt-box'] == 'sentbox' ) || ( isset( $_GET['box'] ) && $_GET['box'] == 'sentbox' ) )
			$bpmt_user_data->box = 'sentbox';
		else
			$bpmt_user_data->box = 'inbox';

		bpmt_display_user_info();

		include_once( dirname( 	__FILE__ ) . '/templates/bpmt-messages-loop.php' );

	}
	else
		echo sprintf( _x( '<div class="error below-h2">ERROR - Member could not be found for: %s </div>', 'bpmt'),  $bpmt_user );

}


// if clicking on pagination
function bpmt_get_member_page() {
	global $bpmt_user_data;

	$bpmt_user_data = bpmt_get_user_data( $_GET['user_id'] );

	if( $bpmt_user_data != NULL ) {

		if( $_GET['box'] == 'sentbox' )
			$bpmt_user_data->box = 'sentbox';
		else
			$bpmt_user_data->box = 'inbox';

		bpmt_display_user_info();

		include_once( dirname( 	__FILE__ ) . '/templates/bpmt-messages-loop.php' );
	}
	else
		_e("<div class='error below-h2'>ERROR -  Member could not be found via pagination.</div>", 'bpmt');

}


function bpmt_get_thread_view() {
	global $bpmt_user_data;

	$bpmt_user_data = bpmt_get_user_data( $_GET['user_id'] );

	if( $bpmt_user_data != NULL ) {

		if( $_GET['box'] == 'sentbox' )
			$bpmt_user_data->box = 'sentbox';
		else
			$bpmt_user_data->box = 'inbox';

		$thread_id = $_GET['thread_id'];

		bpmt_display_user_info();

		include_once( dirname( 	__FILE__ ) . '/templates/bpmt-messages-thread.php' );

	}
	else
		_e("<div class='error below-h2'>ERROR - Message Thread could not be found.</div>", 'bpmt');

}


function bpmt_delete_thread() {
	global $wpdb;

	if( ! is_super_admin() )
		return false;

	if( ! check_admin_referer( 'bpmt_delete_thread' ) )
		return false;

	/**
	  * Unfortunately, we can't use messages_delete_thread( $id )
	  * because BP_Messages_Thread::delete  is hardcoded to bp_loggedin_user_id()
	  * Core devs made a todo note in BP_Messages_Thread::delete
	  * So we have to roll our own delete calls
	  */

	$thread_id = intval( $_GET['thread_id'] );
	$user_id = intval( $_GET['user_id'] );

/*
	// only deletes one side of conversation

	$deletion = messages_delete_thread( $thread_id, $user_id );

	if ( $deletion ) {
		_e("<div class='updated below-h2'>Message Thread was deleted.</div>", 'bpmt');
	} else {
		_e("<div class='error below-h2'>ERROR - There was a problem deleting that Message Thread.</div>", 'bpmt');
	}
*/


	$bp = buddypress();

	$wpdb->query( $wpdb->prepare( "UPDATE {$bp->messages->table_name_recipients} SET is_deleted = 1 WHERE thread_id = %d", $thread_id ) );

	$message_ids = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

	$recipients = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d AND is_deleted = 0", $thread_id ) );

	if ( empty( $recipients ) ) {

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

		foreach ( $message_ids as $message_id ) {

			bp_messages_delete_meta( $message_id );

		}

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d", $thread_id ) );

		_e("<div class='updated below-h2'>Message Thread was deleted.</div>", 'bpmt');
	}
	else
		_e("<div class='error below-h2'>ERROR - There was a problem deleting that Message Thread.</div>", 'bpmt');



	// do_action( 'messages_delete_thread', $thread_id, $user_id );


	bpmt_get_member();
}

function bpmt_bulk_delete() {
	global $wpdb;

	if( ! is_super_admin() )
		return false;

	if( ! check_admin_referer( 'bpmt_bulk_delete' ) )
		return false;

	$user_id = intval( $_GET['user_id'] );

	//echo '<pre>'; var_dump( $_POST["message_ids"] ); echo '</pre>';

	$bulk_thread_ids = $_POST["message_ids"];

	$bp = buddypress();

	$success = 0;
	$failure = 0;


	foreach ( $bulk_thread_ids AS $thread_id ) {

		$thread_id = intval( $thread_id );

		$wpdb->query( $wpdb->prepare( "UPDATE {$bp->messages->table_name_recipients} SET is_deleted = 1 WHERE thread_id = %d", $thread_id ) );

		$message_ids = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

		$recipients = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d AND is_deleted = 0", $thread_id ) );

		if ( empty( $recipients ) ) {

			$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_messages} WHERE thread_id = %d", $thread_id ) );

			foreach ( $message_ids as $message_id ) {

				bp_messages_delete_meta( $message_id );

			}

			$wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->messages->table_name_recipients} WHERE thread_id = %d", $thread_id ) );

			$success++;

		} else {
			$failure++;
		}

	}

	if ( $success > 0 ) {

		if ( $success == 1 ) {

			_e("<div class='updated below-h2'> 1 Message Thread was deleted.</div>", 'bpmt');

		} else {

			printf( __( "<div class='updated below-h2'> %s Message Threads were deleted.</div>", 'bpmt' ), $success );

		}

	}

	if ( $failure > 0 ) {

		if ( $failure == 1 ) {

			_e("<div class='updated below-h2'>ERROR - There was a problem deleting a Message Thread.</div>", 'bpmt');

		} else {

			printf( __( "<div class='error below-h2'>ERROR - There was a problem deleting 1% Message Threads.</div>", 'bpmt' ), $failure );

		}

	}


	bpmt_get_member();

}

function bpmt_get_user_data( $user ) {
	global $wpdb;

	if( is_numeric( $user ) )
		$sql = "SELECT * FROM $wpdb->users WHERE ID = $user ";
	else
		$sql = "SELECT * FROM $wpdb->users WHERE user_login = '$user' ";

	$bpmt_user_data = $wpdb->get_row( $sql );

	return $bpmt_user_data;

}


function bpmt_display_user_info() {
	global $bpmt_user_data;
	?>

	<table id="display-user" cellspacing="10" width="25%">

		<tr>
			<td align="right"><em>Display Name:</em></td>
			<td><?php echo $bpmt_user_data->display_name; ?></td>
		</tr>

		<tr>
			<td align="right"><em>Login Name:</em></td>
			<td><?php echo $bpmt_user_data->user_login; ?></td>
		</tr>

		<tr>
			<td align="right"><em>ID:</em></td>
			<td><?php echo $bpmt_user_data->ID; ?></td>
		</tr>

		<tr>
			<td align="right"><em>Box:</em></td>
			<td><?php echo ucfirst( $bpmt_user_data->box ); ?></td>
		</tr>

	</table>

	<br>

	<?php

}

// create links for View Thread, Delete Thread, Back to Messages Loop
function bpmt_view_delete_back_link( $type ) {
	global $messages_template, $bpmt_user_data;

	$mpage = '';
	if( isset( $_GET['mpage'] ) )
		$mpage = '&mpage=' . $_GET['mpage'];
	else
		$mpage = '&mpage=1';

	$user_id = '&user_id=' . $bpmt_user_data->ID;

	$box = '&box=' . $bpmt_user_data->box;

	switch( $type ) {

		case 'view':
			$thread_id = '&thread_id=' . $messages_template->thread->thread_id;
			$link = site_url() . '/wp-admin/tools.php?page=bp-messages-tool&action=view-thread' . $mpage . $user_id . $thread_id . $box;
			break;

		case 'delete':
			$thread_id = '&thread_id=' . $messages_template->thread->thread_id;
			$link = wp_nonce_url( site_url() . '/wp-admin/tools.php?page=bp-messages-tool&action=delete-thread' . $mpage . $user_id . $thread_id . $box, 'bpmt_delete_thread' );
			break;

		case 'bulk-delete':
			$link = wp_nonce_url( site_url() . '/wp-admin/tools.php?page=bp-messages-tool&action=bulk-delete' . $mpage . $user_id . $box, 'bpmt_bulk_delete' );
			break;

		case 'back':
			$link = site_url() . '/wp-admin/tools.php?page=bp-messages-tool&action=member-threads' . $mpage . $user_id . $box;
			break;

		default:
			$link = '';
			break;

	}

	return $link;
}


function bpmt_pagination ( $pag_links ) {
	global $bpmt_user_data;

	if( $_GET['page'] = 'bp-messages-tool' ) {

		$rep = 'member-threads&user_id=' . $bpmt_user_data->ID . '&box=' . $bpmt_user_data->box;

		$pag_links = str_replace( 'select-member', $rep, $pag_links );

	}

	return $pag_links;
}
add_filter( 'bp_get_messages_pagination', 'bpmt_pagination', 25 );


function bpmt_message_thread_last_post_date_raw( $date ){

	if( $_GET['page'] = 'bp-messages-tool' )
		$date = current( explode( " ", $date) );

	return $date;
}
add_filter('bp_get_message_thread_last_message_date', 'bpmt_message_thread_last_post_date_raw', 25 );

