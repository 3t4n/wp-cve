<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Admin_Stadiums extends Football_Pool_Admin {
	public function __construct() {}
	
	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can add, change or delete venues.</p>', 'football-pool' )
					),
				);
		$help_sidebar = '';
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}

	/** @noinspection PhpMissingBreakStatementInspection */
	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( __( 'Venues', 'football-pool' ), $subtitle, 'add new' );
		
		$venue_id = Football_Pool_Utils::request_int( 'item_id', 0 );
		$bulk_ids = Football_Pool_Utils::post_int_array( 'itemcheck' );
		$action = Football_Pool_Utils::request_string( 'action', 'list' );
		
		if ( count( $bulk_ids ) > 0 && $action == '-1' )
			$action = Football_Pool_Utils::request_string( 'action2', 'list' );

		switch ( $action ) {
			case 'save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				// new or updated venue
				$venue_id = self::update( $venue_id );
				self::notice( __( 'Venue saved.', 'football-pool' ) );
				if ( Football_Pool_Utils::post_str( 'submit') == __( 'Save & Close', 'football-pool' ) ) {
					self::view();
					break;
				}
			case 'edit':
				self::edit( $venue_id );
				break;
			case 'delete':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $venue_id > 0 ) {
					self::delete( $venue_id );
					self::notice( sprintf( __( 'Venue id:%s deleted.', 'football-pool' ), $venue_id ) );
				}
				if ( count( $bulk_ids) > 0 ) {
					self::delete( $bulk_ids );
					self::notice( sprintf( __( '%s venues deleted.', 'football-pool' ), count( $bulk_ids ) ) );
				}
			default:
				self::view();
		}
		
		self::admin_footer();
	}
	
	private static function edit( $id ) {
		$values = array(
						'name' => '',
						'photo' => '',
						'comments' => '',
					);
		
		$venue = self::get_venue( $id );
		if ( is_array( $venue ) && $id > 0 ) {
			$values = $venue;
		}
		$cols = array(
					array( 'text', __( 'name', 'football-pool' ), 'name', $values['name'], '' ),
					array( 'image', __( 'photo', 'football-pool' ), 'photo', $values['photo'], sprintf( __( 'Image path must be a full URL to the image. Or a path relative to the football pool upload directory (%s)', 'football-pool' ), trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'stadiums' ) ) ),
					array( 'multiline', __( 'comments', 'football-pool' ), 'comments', $values['comments'], __( 'An optional text with extra information about the venue that is displayed on the venue\'s page.', 'football-pool' ) ),
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
	
	private static function get_venue( $id ) {
		$output = null;
		if ( $id > 0 ) {
			$venue = new Football_Pool_Stadium( $id );
			if ( isset( $venue->id ) ) {
				$output = array(
								'name' => $venue->name,
								'photo' => $venue->photo,
								'comments' => $venue->comments,
							);
			}
		}
		
		return $output;
	}
	
	private static function get_venues() {
		$venues = new Football_Pool_Stadiums;
		$venues = $venues->get_stadiums();
		$output = array();
		foreach ( $venues as $venue ) {
			$output[] = array(
							'id' => $venue->id, 
							'name' => $venue->name, 
							'photo' => $venue->photo,
							'comments' => $venue->comments,
						);
		}
		return $output;
	}
	
	private static function view() {
		$items = self::get_venues();

		// search in name or comments
		$search = Football_Pool_Utils::request_string( 's' );
		if ( $search !== '' ) {
			$items = array_filter( $items, function( $v ) use ( $search ) {
				return stripos( $v['name'], $search ) !== false || stripos( $v['comments'], $search ) !== false;
			} );
		}

		$cols = array(
					array( 'text', __( 'venue', 'football-pool' ), 'venue', '' ),
					array( 'text', __( 'photo', 'football-pool' ), 'photo', '' ),
					array( 'text', __( 'venue nr', 'football-pool' ), 'nr', '' ),
				);
		
		$rows = array();
		foreach( $items as $item ) {
			$rows[] = array(
						$item['name'], 
						$item['photo'], 
						$item['id'], 
						$item['id'],
					);
		}


		$search_box = array(
			'text' => __( 'Search', 'football-pool' ),
			'value' => $search,
		);
		$bulkactions[] = array( 'delete', __( 'Delete' ), __( 'You are about to delete one or more venues.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to delete, `Cancel` to stop.', 'football-pool' ) );
		self::list_table( $cols, $rows, $bulkactions, null, false, $search_box );
	}
	
	private static function update( $item_id ) {
		$item = array(
						$item_id,
						Football_Pool_Utils::post_string( 'name' ),
						Football_Pool_Utils::post_string( 'photo' ),
						Football_Pool_Utils::post_string( 'comments' ),
					);

		return self::update_item( $item );
	}
	
	private static function delete( $item_id ) {
		if ( is_array( $item_id ) ) {
			foreach ( $item_id as $id ) self::delete_item( $id );
		} else {
			self::delete_item( $item_id );
		}
	}
	
	private static function delete_item( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		do_action( 'footballpool_admin_stadium_delete', $id );
		
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}stadiums WHERE id = %d", $id );
		$wpdb->query( $sql );
	}
	
	private static function update_item( $input ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		list ( $id, $name, $photo, $comments ) = $input;
		
		if ( $id == 0 ) {
			$sql = $wpdb->prepare( "INSERT INTO {$prefix}stadiums ( name, photo, comments )
									VALUES ( %s, %s, %s )",
									$name, $photo, $comments
								);
		} else {
			$sql = $wpdb->prepare( "UPDATE {$prefix}stadiums SET
										name = %s,
										photo = %s,
										comments = %s
									WHERE id = %d",
									$name, $photo, $comments, $id
								);
		}
		
		$wpdb->query( $sql );
		
		return ( $id == 0 ) ? $wpdb->insert_id : $id;
	}

}
