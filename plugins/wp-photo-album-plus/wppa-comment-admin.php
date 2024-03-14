<?php
/* wppa-commentadmin.php
* Package: wp-photo-album-plus
*
* manage all comments
* Version: 8.6.03.004
*
*/


// LOAD THE BASE CLASS
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// CREATE A PACKAGE CLASS *****************************
class WPPA_Comment_table extends WPPA_List_Table {

	var $data;

	function __construct() {
		global $status, $page;

		// Set parent defaults
		parent::__construct( array(
			'singular'  => 'commentids',
			'plural'    => 'comments',
			'ajax'      => false        //does this table support ajax?
		) );
	}

	// Extra table navigation
	function extra_tablenav( $which ) {

		global $wpdb;

		// Filter
		if ( 'top' === $which ) {
			$comment_show = wppa_get_cookie( 'comadmin-show', 'all' );
			wppa_echo( '
			<div class="alignleft actions">
				<select id="wppa_comadmin_show" name="wppa_comadmin_show" onchange="">
					<option value="all"' . ( $comment_show == 'all' ? ' selected' : '' ) . '>' . __( 'all', 'wp-photo-album-plus' ) . '</option>
					<option value="pending"' . ( $comment_show == 'pending' ? ' selected' : '' ) . '>' . __( 'pending', 'wp-photo-album-plus' ) . '</option>
					<option value="approved"' . ( $comment_show == 'approved' ? ' selected' : '' ) . '>' . __( 'approved', 'wp-photo-album-plus' ) . '</option>
					<option value="spam"' . ( $comment_show == 'spam' ? ' selected' : '' ) . '>' . __( 'spam', 'wp-photo-album-plus' ) . '</option>
				</select>
				<input
					type="button"
					class="button"
					style="margin: 1px 8px 0 0;"
					onclick="wppa_setCookie(\'comadmin-show\', jQuery( \'#wppa_comadmin_show\' ).val(), \'365\'); document.location.reload(true);"
					value="' . esc_attr( __( 'Filter', 'wp-photo-album-plus' ) ) . '"
				/>
			</div>' );
		}

		// Pagination
		$parms 		= wppa_get_paging_parms( 'comment_admin' );
		$reload_url = get_admin_url() . 'admin.php?page=wppa_manage_comments';
		$total 		= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments " . wppa_get_comadmin_sel_filter() );
		wppa_admin_pagination( $parms['pagesize'], $parms['page'], $total, $reload_url,'top' );
	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'id':
			case 'user':
			case 'ip':
			case 'status':
				return $item[$column_name];
			default:
				return print_r($item,true); //Show the whole array for troubleshooting purposes
		}
	}

	function column_user( $item ) {

		return stripslashes( $item['user'] ) . '<br>(' . ( $item['userid'] == -1 ? 'Loggedout' : $item['userid'] ) . ')<br>' . $item['ip'];
	}

	function column_timestamp( $item ) {

		return wppa_local_date( false, $item['timestamp'] );
	}

	function column_photo( $item ) {

		$photo 	= $item['photo'];
		$src 	= wppa_get_thumb_url( $photo );
		$title 	= esc_attr( wppa_get_photo_name( $photo ) ) . ' (' . wppa_get_album_name( wppa_get_photo_item( $photo, 'album' ) ) . ')';
		if ( wppa_is_video( $photo ) && ! wppa_is_file( wppa_get_thumb_path( $photo ) ) ) {
			$result =
			wppa_get_video_html( array (
				'id'			=> $photo,
				'tagid' 		=> 'video-' . $item['id'],
				'controls' 		=> false,
				'title' 		=> $title,
				'style' 		=> 'width:170px;',
			) ) .
			'<br>' .
			$item['id'] . ' ' .
			__( 'Video', 'wp-photo-album-plus' ) . ': ' . $item['photo'];
		}
		else {
		$result = '
			<img
				src="' . $src . '"
				style="width:170px;"
				title="' . $title . '"
			>
			<br>' .
			$item['id'] . ' ' .
			__( 'Photo', 'wp-photo-album-plus' ) . ': ' . $item['photo'];
		}

		return wppa_compress_html( $result );
	}

	function column_email( $item ) {

		return make_clickable( $item['email'] );
	}

	function column_commenttext( $item ) {

		$action = '
		<a
			id="href-' . $item['id'] . '"
			style="display:none;"
			href="?page=wppa_manage_comments&commentids=' . $item['id'] . '&action=editsingle&commenttext=' . urlencode( $item['comment'] ) . '"
			>' .
			__( 'Update', 'wp-photo-album-plus' ) . '
		</a>';

		$actions = array(
			'editsingle' 	=> $action,
		);

		$result =
		'<textarea' .
			' id="commenttext-' . $item['id'] . '"' .
			' style="width:98%;"' .
			' onchange="wppaCommentAdminUpdateHref(\'' . $item['id'] . '\')"' .
			' >' .
			stripslashes( $item['comment'] ) .
		'</textarea>' .
		$this->row_actions( $actions );

		return $result;
	}

	function column_status( $item ) {

		$p1 = '<a href="?page=wppa_manage_comments&commentids=' . $item['id'] . '&paged=' . wppa_get( 'paged', '1', 'int' );
		$actions = array(
			'approvesingle' 	=> $p1 . '&action=approvesingle" >' . __( 'Approve', 'wp-photo-album-plus' ) . '</a>',
			'pendingsingle' 	=> $p1 . '&action=pendingsingle" >' . __( 'Pending', 'wp-photo-album-plus' ) . '</a>',
			'spamsingle'    	=> $p1 . '&action=spamsingle" >' . 	  __( 'Spam', 'wp-photo-album-plus' ) . '</a>',
			'deletesingle' 		=> $p1 . '&action=deletesingle" >' .  __( 'Delete', 'wp-photo-album-plus' ) . '</a>',
		);

		switch( $item['status'] ) {
			case 'pending':
				$status = __( 'Pending', 'wp-photo-album-plus' );
				$color 	= 'red';
				unset( $actions['pendingsingle'] );
				break;
			case 'approved':
				$status = __( 'Approved', 'wp-photo-album-plus' );
				$color 	= 'black';
				unset( $actions['approvesingle'] );
				break;
			case 'spam':
				$status = __( 'Spam', 'wp-photo-album-plus' );
				$color 	= 'red';
				unset( $actions['spamsingle'] );
				break;
			default:
				$status = '';
				$color 	= 'red';
		}
		$result = '<span id="status-' . $item['id'] . '" style="color:' . $color . '">' . $status . '</span>';
		$result .= $this->row_actions( $actions );

		return $result;
	}

	function column_cb( $item ){

		$result =
		'<input type="checkbox" name="' . $this->_args['singular'] . '[]" value="' . $item['id'] . '" />';

		return $result;
	}

	function get_columns() {

		switch ( wppa_opt( 'comment_email_required' ) ) {
			case 'required':
				$email_header = __( 'User email', 'wp-photo-album-plus' );
				break;
			case 'optional':
				$email_header = __( 'User email / IP', 'wp-photo-album-plus' );
				break;
			default: // case 'none':
				$email_header = __( 'User login / IP', 'wp-photo-album-plus' );
				break;
		}

		$columns = array(
			'cb'       		=> '<input type="checkbox" />', //Render a checkbox instead of text
			'photo' 		=> __( 'Photo', 'wp-photo-album-plus' ),
			'user' 			=> __( 'User', 'wp-photo-album-plus' ),
			'email' 		=> $email_header,
			'timestamp' 	=> __( 'Timestamp', 'wp-photo-album-plus' ),
			'status' 		=> __( 'Status', 'wp-photo-album-plus' ),
			'commenttext' 	=> __( 'Comment', 'wp-photo-album-plus' ),
		);
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'     	=> array( 'id', false ),     //true means it's already sorted
			'timestamp' => array( 'timestamp', false ),
			'photo'  	=> array( 'photo', false ),
			'user' 		=> array( 'user', false ),
		);
		return $sortable_columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'approve' 	=> __( 'Approve', 'wp-photo-album-plus' ),
			'delete'    => __( 'Delete', 'wp-photo-album-plus' ),
		);
		return $actions;
	}

	// process_bulk_action also processes single actions as long as the query arg &action= exists
	function process_bulk_action() {
		global $wpdb;

		$ids = (array) wppa_get( 'commentids' );

		$current_action = $this->current_action();

		if ( $current_action && $ids ) {

			foreach( $ids as $id ) {

				$photo = $wpdb->get_var( $wpdb->prepare( "SELECT photo FROM $wpdb->wppa_comments WHERE id = %s", $id ) );

				// Delete
				if ( 'delete' === $current_action || 'deletesingle' === $current_action ) {
					wppa_del_row( WPPA_COMMENTS, 'id', $id );
				}

				// Approve
				if ( 'approve' === $current_action || 'approvesingle' === $current_action ) {
					$iret = wppa_update_comment( $id, ['status' => 'approved'] );
					if ( $iret ) {
						wppa_schedule_mailinglist( 'commentapproved', 0, 0, $id );
						wppa_add_credit_points( wppa_opt( 'cp_points_comment_appr' ), __( 'Photo comment approved' , 'wp-photo-album-plus' ), $photo, '', wppa_get_photo_item( $photo, 'owner' )	);
					}
				}

				// Spam
				if ( 'spam' === $current_action || 'spamsingle' === $current_action ) {
					$iret = wppa_update_comment( $id, ['status' => 'spam'] );
				}

				// Pending
				if ( 'pending' === $current_action || 'pendingsingle' === $current_action ) {
					$iret = wppa_update_comment( $id, ['status' => 'pending'] );
				}

				// Edit, exists single only
				if ( 'editsingle' === $current_action ) {
					$commenttext = wppa_get( 'commenttext' );
					$id = wppa_get( 'commentids' );
					wppa_update_comment( $id, ['comment' => $commenttext] );
				}

				// Update index in the near future
				if ( wppa_switch( 'search_comments' ) ) {
					wppa_index_update( 'photo', $photo );
				}

				// Clear cache
				wppa_clear_cache( array( 'photo' => $photo ) );
			}
		}

		// Clear cache
		wppa_clear_cache( array( 'other' => 'C' ) );
	}

	function prepare_items() {
		global $wpdb;

		$parms 		= wppa_get_paging_parms( 'comment_admin' );
		$per_page 	= $parms['pagesize'];
		$columns 	= $this->get_columns();
		$hidden 	= array();
		$sortable 	= $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();

		$filter 	= wppa_get_comadmin_sel_filter();

		$total_items 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments " . $filter );
		$limit 			= ( $parms['page'] - 1 ) * $parms['pagesize'] . "," . $parms['pagesize'];
		$query 			=  "SELECT * FROM $wpdb->wppa_comments " . $filter . " ORDER BY " . $parms['order'] . " " . $parms['dir'] . " LIMIT " . $limit;
		$data 			= $wpdb->get_results( $query, ARRAY_A );
		$this->items 	= $data;

		// Disable wp pagination, we do our selbes
		$this->set_pagination_args( array() );
	}
}

// The comment admin page
function _wppa_comment_admin( $page_1 = false ) {
	global $wpdb;

	$parms 		= wppa_get_paging_parms( 'comment_admin', $page_1 );
	$wppa_page  = $parms['page'];

	// Create an instance of our package class...
	$testListTable = new WPPA_Comment_table();

	// Fetch, prepare, sort, and filter our data...
	$testListTable->prepare_items();

	// Moderate single only?
	$moderating = wppa_get( 'commentid' );

	// Open page
	wppa_echo( '
	<div class="wrap">
		<h1 class="wp-heading-inline">' .
			get_admin_page_title() . '
		</h1>' );
		if ( $moderating ) {
			$status_show = array( 'pending', 'spam' );
		}
		else {

			// Statistics
			$t_to_txt = array( 	'none' 		=> false,
								'600' 		=> sprintf( _n('%d minute', '%d minutes', '10', 'wp-photo-album-plus' ), '10'),
								'1800' 		=> sprintf( _n('%d minute', '%d minutes', '30', 'wp-photo-album-plus' ), '30'),
								'3600' 		=> sprintf( _n('%d hour', '%d hours', '1', 'wp-photo-album-plus' ), '1'),
								'86400' 	=> sprintf( _n('%d day', '%d days', '1', 'wp-photo-album-plus' ), '1'),
								'604800' 	=> sprintf( _n('%d week', '%d weeks', '1', 'wp-photo-album-plus' ), '1'),
							);
			$spamtime = $t_to_txt[wppa_opt( 'spam_maxage' )];

			wppa_echo( '
			<table>
				<tbody>
					<tr>
						<td style="margin:0;font-weight:bold;color:#777777">' . __( 'Total:', 'wp-photo-album-plus' ) . '</td>
						<td style="margin:0;font-weight:bold">' . $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments" ) . '</td>
						<td></td>
					</tr>
					<tr>
						<td style="margin:0;font-weight:bold;color:green">' . __( 'Approved:', 'wp-photo-album-plus' ) . '</td>
						<td style="margin:0;font-weight:bold">' . $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE status = 'approved'" ) . '</td>
						<td></td>
					</tr>
					<tr>
						<td style="margin:0;font-weight:bold;color:#e66f00">' . __( 'Pending:', 'wp-photo-album-plus' ) . '</td>
						<td style="margin:0;font-weight:bold">' . $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE status = 'pending' OR status = ''" ) . '</td>
						<td></td>
					</tr>
					<tr>
						<td style="margin:0;font-weight:bold;color:red">' . __( 'Spam:', 'wp-photo-album-plus' ) . '</td>
						<td style="margin:0;font-weight:bold">' . $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE status = 'spam'" ) . '</td>
						<td></td>
					</tr>' );
					if ( $spamtime ) {
						wppa_echo( '
						<tr>
							<td style="margin:0;font-weight:bold;color:red">' . __( 'Auto deleted spam:', 'wp-photo-album-plus' ) . '</td>
							<td style="margin:0;font-weight:bold">' . wppa_get_option( 'wppa_spam_auto_delcount', '0' ) . '</td>
							<td>' . sprintf( __( 'Comments marked as spam will be deleted when they are entered longer than %s ago.', 'wp-photo-album-plus' ), $spamtime ) . '</td>
						</tr>' );
					}
				wppa_echo( '
				</tbody>
			</table>' );
		}

		wppa_echo( '
		<form id="wppa-comment-form" method="GET" >
			<input type="hidden" name="page" value="wppa_manage_comments" />' );
			$testListTable->display();
			wppa_echo( '
		</form>
	</div>' );
}

function wppa_get_comadmin_sel_filter() {

	// default
	$filter = '';

	// Moderate single only?
	if ( wppa_get( 'commentid' ) ) {
		$filter = "WHERE id = " . wppa_get( 'commentid' );
	}

	// Normal use
	else {
		switch( wppa_get_cookie( 'comadmin-show' ) ) {
			case 'all':
				break;
			case 'spam':
				$filter = "WHERE status = 'spam'";
				break;
			case 'pending':
				$filter = "WHERE status = 'pending' OR status = ''";
				break;
			case 'approved':
				$filter = "WHERE status = 'approved'";
				break;
			default:
				break;
		}
	}

	return $filter;
}