<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Stadiums {
	public function get_stadiums() {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = "SELECT id, name, photo, comments FROM {$prefix}stadiums ORDER BY name ASC";
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		
		$stadiums = array();
		foreach ( $rows as $row ) {
			$stadiums[] = new Football_Pool_Stadium($row);
		}
		return $stadiums;
	}
	
	public function print_lines( $stadiums ) {
		$thumbs_in_listing = Football_Pool_Utils::get_fp_option( 'listing_show_venue_thumb', 1, 'int' ) === 1;
		$comments_in_listing = Football_Pool_Utils::get_fp_option( 'listing_show_venue_comments', 1, 'int' ) === 1;
		$output = '';
		while ( $stadium = array_shift( $stadiums ) ) {
			$photo = ( $thumbs_in_listing && $stadium->photo != '' ) ? $stadium->HTML_image( 'thumb' ) : '';
			$comments = ( $comments_in_listing ) ? $stadium->comments : '';
			$line = sprintf( '<div><a href="%1$s">%2$s</a><h2><a href="%1$s">%3$s</a></h2><p>%4$s</p></div>'
								, esc_url( add_query_arg( array( 'stadium' => $stadium->id ) ) )
								, $photo
								, Football_Pool_Utils::xssafe( $stadium->name )
								, Football_Pool_Utils::xssafe( $comments )
							);
			$output .= apply_filters( 'footballpool_stadiums_print_line', $line, $stadium );
		}
		return $output;
	}
	
	public function get_stadium_by_id( $id ) {
		if ( ! is_numeric( $id ) ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT id, name, photo, comments FROM {$prefix}stadiums WHERE id = %d", $id );
		$row = $wpdb->get_row( $sql, ARRAY_A );
		
		return ( $row ) ? new Football_Pool_Stadium( $row ) : null;
	}
	
	// returns object
	public function get_stadium_by_name( $name, $addnew = 'no', $extra_data = '' ) {
		if ( $name == '' ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT id, name, photo, comments
								FROM {$prefix}stadiums WHERE name = %s", $name );
		$result = $wpdb->get_row( $sql );
		
		if ( $addnew == 'addnew' && $result == null ) {
			$photo = $comments = '';
			
			if ( is_array( $extra_data ) ) {
				$photo    = $extra_data['photo'];
				$comments = isset( $extra_data['comments'] ) ? $extra_data['comments'] : '';
			}
			
			$sql = $wpdb->prepare( 
							"INSERT INTO {$prefix}stadiums ( name, photo, comments ) 
							 VALUES ( %s, %s, %s )"
							, $name, $photo, $comments
					);
			$wpdb->query( $sql );
			$id = $wpdb->insert_id;
			$result = (object) array( 
									'id'       => $id, 
									'name'     => $name,
									'photo'    => $photo,
									'comments'    => $comments,
									'inserted' => true
								);
		}
		
		return $result;
	}
}
