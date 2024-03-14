<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Teams {
	public $teams;
	public $team_types;
	public $team_names;
	public $team_flags;
	public $team_info;
	public $show_team_links;
	public $page;
	
	public function __construct() {
		$this->teams = $this->get_teams();
		// get the team info for all teams
		$this->team_info = $this->get_team_info();
		// get the team_types
		$this->team_types = $this->get_team_types();
		// get the team_names
		$this->team_names = $this->get_team_names();
		// get the flags
		$this->team_flags = $this->get_team_flags();
		// show links?
		$this->show_team_links = Football_Pool_Utils::get_fp_option( 'show_team_link', true );
		
		$this->page = Football_Pool::get_page_link( 'teams' );
	}
	
	// returns array
	public function get_team_by_id( $id ) {
		if ( ! is_numeric( $id ) ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT 
									t.id, t.name, t.photo, t.flag, t.link, g.id AS group_id, 
									g.name AS group_name, t.group_order, 
									t.is_real, t.is_active, t.is_favorite, t.comments
								FROM {$prefix}teams t
								LEFT OUTER JOIN {$prefix}groups g ON t.group_id = g.id
								WHERE t.id = %d",
								$id
							);
		$row = $wpdb->get_row( $sql, ARRAY_A );
		
		return ( $row ) ? new Football_Pool_Team( $row ) : null;
	}
	
	// returns object
	public function get_team_by_name( $name, $addnew = 'no', $extra_data = '' ) {
		if ( $name === '' ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT 
									id, name, photo, flag, link, group_id, 
									group_order, is_real, is_active, is_favorite, comments
								FROM {$prefix}teams WHERE name = %s", $name );
		$result = $wpdb->get_row( $sql );
		
		if ( $addnew === 'addnew' && $result === null ) {
			$photo = $flag = $link = $comments = '';
			$group_id = $group_order = 0;
			$is_active = $is_real = 1;
			$is_favorite = 0;

			if ( is_array( $extra_data ) ) {
				$photo       = $extra_data['photo'] ?? $photo;
				$flag        = $extra_data['flag'] ?? $flag;
				$link        = $extra_data['link'] ?? $link;
				$group_id    = $extra_data['group_id'];
				$group_order = $extra_data['group_order'] ?? $group_order;
				$is_real     = $extra_data['is_real'] ?? $is_real;
				$is_active   = $extra_data['is_active'] ?? $is_active;
				$is_favorite = $extra_data['is_favorite'] ?? $is_favorite;
				$comments    = $extra_data['comments'] ?? $comments;
			}
			
			$sql = $wpdb->prepare( 
							"INSERT INTO {$prefix}teams 
								( name, photo, flag, link, group_id, group_order, 
								 is_real, is_active, is_favorite, comments ) 
							 VALUES 
								( %s, %s, %s, %s, %d, %d, %d, %d, %d, %s )",
							$name, $photo, $flag, $link, $group_id, $group_order,
							$is_real, $is_active, $is_favorite, $comments
					);
			$wpdb->query( $sql );
			$id = $wpdb->insert_id;
			$result = (object) array( 
				'id'          => $id,
				'name'        => $name,
				'photo'       => $photo,
				'flag'        => $flag,
				'link'        => $link,
				'group_id'    => $group_id,
				'group_order' => $group_order,
				'is_real'     => $is_real,
				'is_active'   => $is_active,
				'is_favorite' => $is_favorite,
				'comments'    => $comments,
				'inserted'    => true
			);
			// clear the cache
			wp_cache_delete( FOOTBALLPOOL_CACHE_TEAMS, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		}
		
		return $result;
	}
	
	public function get_group_order( $team ) {
		if ( ! is_numeric( $team ) ) return 0;
		
		$cache_key = 'fp_group_order_' . $team;
		$group_order = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		
		if ( $group_order === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$sql = $wpdb->prepare( "SELECT group_order FROM {$prefix}teams WHERE id = %d", $team );
			$group_order = $wpdb->get_var( $sql );
			wp_cache_set( $cache_key, $group_order, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		}
		
		return ( $group_order ) ? (integer) $group_order : 0;
	}
	
	public function print_lines( $teams ) {
		$thumbs_in_listing = Football_Pool_Utils::get_fp_option( 'listing_show_team_thumb', 1, 'int' ) === 1;
		$comments_in_listing = Football_Pool_Utils::get_fp_option( 'listing_show_team_comments', 1, 'int' ) === 1;
		$output = '';
		while ( $team = array_shift( $teams ) ) {
			if ( $team->is_real == 1 && $team->is_active == 1 ) {
				$photo = ( $thumbs_in_listing && $team->photo !== '' ) ? $team->HTML_thumb( 'thumb' ) : '';
				$comments = ( $comments_in_listing ) ? $team->comments : '';
				$class = ( $team->is_favorite === 1 ) ? 'favorite' : '';
				$line = sprintf(
					'<li class="%5$s"><a href="%1$s">%2$s%3$s</a><br>%4$s</li>',
					esc_url( add_query_arg( array( 'team' => $team->id ) ) ),
					$photo,
					Football_Pool_Utils::xssafe( $team->name ),
					Football_Pool_Utils::xssafe( $comments ),
					$class
				);
				$output .= apply_filters( 'footballpool_teams_print_line', $line, $team );
			}
		}
		return $output;
	}
	
	public function update_teams() {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		foreach ( $this->team_names as $team => $name ) {
			if ( Football_Pool_Utils::post_string( '_name_' . $team) !== '' ) {
				$name = Football_Pool_Utils::post_string( '_name_' . $team, $name );
				$order = Football_Pool_Utils::post_integer( '_order_' . $team );
				
				$sql = $wpdb->prepare(
					"UPDATE {$prefix}teams SET name = %s, group_order = %d WHERE id = %d",
					$name, $order, $team
				);
				$wpdb->query( $sql );
			}
		}
	}
	
	public function get_teams() {
		$teams = wp_cache_get( FOOTBALLPOOL_CACHE_TEAMS, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		
		if ( $teams === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$sql = "SELECT 
						t.id, t.name, t.photo, t.flag, t.link, g.id AS group_id, g.name as group_name,
						t.is_real, t.is_active, t.is_favorite, t.group_order, t.comments
					FROM {$prefix}teams t
					LEFT OUTER JOIN {$prefix}groups g ON t.group_id = g.id 
					ORDER BY t.name ASC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			$teams = [];
			foreach ( $rows as $row ) {
				$teams[(int)$row['id']] = new Football_Pool_Team( $row );
			}
			wp_cache_set( FOOTBALLPOOL_CACHE_TEAMS, $teams, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		}
		
		return $teams;
	}

	/* get an array containing all the team types (real or not) */
	private function get_team_types() {
		$team_types = [];
		foreach( $this->team_info as $team ) {
			$team_types[$team['id']] = $team['type'];
		}
		return $team_types;
	}
	
	/* get an array containing all the team names (those that are real and active) */
	private function get_team_names() {
		$team_names = [];
		foreach( $this->team_info as $team ) {
			$team_names[$team['id']] = $team['team_name'];
		}
		return $team_names;
	}
	
	/* get an array with all the team_flags (for real and active teams) */
	private function get_team_flags() {
		$flags = [];
		foreach( $this->team_info as $team ) {
			$flags[$team['id']] = $team['team_flag'];
		}
		return $flags;
	}
	
	/* get an array with the team info (for real and active teams) */
	private function get_team_info() {
		$team_info = [];
		foreach( $this->teams as $team ) {
			$team_info[(int) $team->id] = [
				'id' => (int) $team->id,
				'type' => ( $team->is_real == 1 ),
				'team_name' => $team->name,
				'team_flag' => $team->flag,
				'group_id' => (int) $team->group_id,
				'group_name' => $team->group_name,
				'team_url' => $team->link,
				'is_favorite' => $team->is_favorite,
			];
		}
		return apply_filters( 'footballpool_teams', $team_info );
	}
	
	/* return IMG tag for team flag or logo */
	public function flag_image( $id ): string
	{
		if ( is_array( $this->team_flags ) && isset( $this->team_flags[$id] ) && $this->team_flags[$id] != '' ) {
			$flag = $this->team_flags[$id];
			$team_name = esc_attr( Football_Pool_Utils::xssafe( $this->team_names[$id] ) );
			
			if ( stripos( $flag, 'http://' ) === false && stripos( $flag, 'https://' ) === false ) {
				$flag = trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'flags' ) . $flag;
			}
			
			return sprintf(
				'<img src="%s" title="%s" alt="%s" class="flag">',
				esc_attr( $flag ),
				$team_name,
				$team_name
			);
		} else {
			return '';
		}
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function is_favorite( int $id ): bool
	{
		return ( isset( $this->teams[$id] ) && $this->teams[$id]->is_favorite === 1 );
	}
}
