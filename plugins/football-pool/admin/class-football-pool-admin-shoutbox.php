<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Admin_Shoutbox extends Football_Pool_Admin {
	public function __construct() {}
	
	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can add, change or delete shoutbox messages. Shoutbox messages are displayed in the plugin\'s Shoutbox widget.</p>', 'football-pool' )
					),
				);
		$help_sidebar = '';
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}

	/** @noinspection PhpMissingBreakStatementInspection */
	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( __( 'Shoutbox', 'football-pool' ), $subtitle, 'add new' );
		
		$shout_id = Football_Pool_Utils::request_int( 'item_id', 0 );
		$bulk_ids = Football_Pool_Utils::post_int_array( 'itemcheck' );
		$action = Football_Pool_Utils::request_string( 'action', 'list' );
		
		if ( count( $bulk_ids ) > 0 && $action == '-1' )
			$action = Football_Pool_Utils::request_string( 'action2', 'list' );

		switch ( $action ) {
			case 'save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				// new or updated message
				$shout_id = self::update( $shout_id );
				self::notice( __( "Message saved.", 'football-pool' ) );
				if ( Football_Pool_Utils::post_str( 'submit' ) == __( 'Save & Close', 'football-pool' ) ) {
					self::view();
					break;
				}
			case 'edit':
				self::edit( $shout_id );
				break;
			case 'delete':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $shout_id > 0 ) {
					self::delete( $shout_id );
					self::notice( sprintf( __("Message id:%s deleted.", 'football-pool' ), $shout_id ) );
				}
				if ( count( $bulk_ids ) > 0 ) {
					self::delete( $bulk_ids );
					self::notice( sprintf( __( '%s messages deleted.', 'football-pool' ), count( $bulk_ids ) ) );
				}
			default:
				self::view();
		}
		
		self::admin_footer();
	}
	
	private static function edit( $id ) {
		global $current_user;
		
		$values = array(
						'user_name' => $current_user->display_name,
						'shout_text' => '',
						'shout_date' => __( 'now', 'football-pool' )
						);
		
		$message = self::get_message( $id );
		if ( $message && $id > 0 ) {
			$values = $message;
			$values['shout_date'] = Football_Pool_Utils::date_from_gmt( $values['shout_date'] );
		}
		$cols = array(
					array( 'no_input', __( 'name', 'football-pool' ), 'user_name', $values['user_name'], '' ),
					array( 'text', __( 'message', 'football-pool' ), 'message', $values['shout_text'], '' ),
					array( 'no_input', __( 'time', 'football-pool' ), 'time', $values['shout_date'], '' ),
					array( 'hidden', '', 'item_id', $id ),
					array( 'hidden', '', 'action', 'save' )
				);
		self::value_form( $cols );
		echo '<p class="submit">';
		submit_button( __( 'Save & Close', 'football-pool' ), 'primary', 'submit', false );
		submit_button( null, 'secondary', 'save', false );
		self::cancel_button();
		echo '</p>';
	}
	
	private static function get_message( $id ) {
		$shoutbox = new Football_Pool_Shoutbox();
		return $shoutbox->get_message( $id );
	}
	
	private static function view() {
		$shoutbox = new Football_Pool_Shoutbox;
		$messages = $shoutbox->get_messages();

		// search in name or comments
		$search = Football_Pool_Utils::request_string( 's' );
		if ( $search !== '' ) {
			$messages = array_filter( $messages, function( $v ) use ( $search ) {
				return stripos( $v['user_name'], $search ) !== false || stripos( $v['shout_text'], $search ) !== false;
			} );
		}

		$cols = array(
					array( 'text', __( 'name', 'football-pool' ), 'name', '' ),
					array( 'text', __( 'message', 'football-pool' ), 'shouttext', '' ),
					array( 'text', __( 'time', 'football-pool' ), 'time', '' )
				);
		
		$rows = array();
		foreach( $messages as $message ) {
			$rows[] = array(
						$message['user_name'],
						Football_Pool_Utils::xssafe( $message['shout_text'] ),
						Football_Pool_Utils::date_from_gmt( $message['shout_date'] ),
						$message['id']
					);
		}

		$search_box = array(
			'text' => __( 'Search', 'football-pool' ),
			'value' => $search,
		);
		$bulkactions[] = array( 'delete', __( 'Delete' ), __( 'You are about to delete one or more shoutbox messages.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to delete, `Cancel` to stop.', 'football-pool' ) );
		self::list_table( $cols, $rows, $bulkactions, null, false, $search_box );
	}
	
	private static function update( $shout_id ) {
		$message = array(
						$shout_id,
						Football_Pool_Utils::post_string( 'message' )
					);

		return self::update_message( $message );
	}
	
	private static function delete( $shout_id ) {
		if ( is_array( $shout_id ) ) {
			foreach ( $shout_id as $id ) self::delete_shout( $id );
		} else {
			self::delete_shout( $shout_id );
		}
	}
	
	private static function delete_shout( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		do_action( 'footballpool_admin_shout_delete', $id );
		
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}shoutbox WHERE id = %d", $id );
		$wpdb->query( $sql );
	}
	
	private static function update_message( $input ) {
		global $wpdb, $current_user;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$id = $input[0];
		$message = $input[1];
		
		$shoutbox = new Football_Pool_Shoutbox;
		
		if ( $id == 0 ) {
			$shoutbox->save_shout( $message, $current_user->ID, 150 );
		} else {
			$sql = $wpdb->prepare( "UPDATE {$prefix}shoutbox SET shout_text = %s WHERE id = %d", $message, $id );
			$wpdb->query( $sql );
		}
		
		return ( $id == 0 ) ? $wpdb->insert_id : $id;
	}

}
