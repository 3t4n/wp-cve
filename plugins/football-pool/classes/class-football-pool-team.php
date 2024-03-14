<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */ /** @noinspection HtmlUnknownTarget */

/** @noinspection SqlResolve */

class Football_Pool_Team extends Football_Pool_Teams {
	public $id = 0;
	public $name = '';
	public $photo = '';
	public $flag = '';
	public $link = '';
	public $group_id = 0;
	public $group_name = '';
	public $group_order;
	public $is_real = 1;
	public $is_active = 1;
	public $is_favorite = 0;
	public $page;
	public $comments = '';
	
	public function __construct( $team = 0 ) {
		if ( is_int( $team ) && $team !== 0 ) {
			$t = $this->get_team_by_id( $team );
			if ( is_object( $t ) ) {
				$this->id = $t->id;
				$this->name = $t->name;
				$this->photo = $t->photo;
				$this->flag = $t->flag;
				$this->link = $t->link;
				$this->group_id = $t->group_id;
				$this->group_name = $t->group_name;
				$this->group_order = $t->group_order;
				$this->is_real = $t->is_real;
				$this->is_active = $t->is_active;
				$this->is_favorite = $t->is_favorite;
				$this->comments = $t->comments;
			}
		} elseif ( is_array( $team ) ) {
			$this->id = (int) $team['id'];
			$this->name = $team['name'];
			$this->photo = $team['photo'];
			$this->flag = $team['flag'];
			$this->link = $team['link'];
			$this->group_id = (int) $team['group_id'];
			$this->group_name = $team['group_name'];
			$this->group_order = (int) $team['group_order'];
			$this->is_real = (int) $team['is_real'];
			$this->is_active = (int) $team['is_active'];
			$this->is_favorite = (int) $team['is_favorite'];
			$this->comments = $team['comments'];
		}
		
		$this->page = Football_Pool::get_page_link( 'teams' );
	}
	
	public function get_team_link( $name_only = false ) {
		if ( $this->name == '' ) return '';
		
		if ( $this->show_team_links && ! $name_only ) {
			$team_name = sprintf( '<a href="%s">%s</a>'
									, esc_url( 
											add_query_arg( 
												array( 'team' => $this->id ), 
												$this->page 
											) 
										)
									, Football_Pool_Utils::xssafe( $this->name )
							);
		} else {
			$team_name = Football_Pool_Utils::xssafe( $this->name );
		}
		
		return $team_name;
	}
	
	private function get_photo_url( $photo ) {
		$path = '';
		if ( stripos( $photo, 'http://' ) !== 0 && stripos( $photo, 'https://' ) !== 0 ) {
			$path = trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'teams' );
		}
		
		return $path . $photo;
	}
	
	public function HTML_thumb( $return = 'all' ) {
		if ( $this->photo !== '' ) {
			$photo = $this->get_photo_url( $this->photo );
			if ( $return === 'thumb' ) {
				$thumb = sprintf( '<img src="%s" title="%s" alt="%s %s" class="team-photo thumb team-list">'
									, esc_attr( $photo )
									, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
									, esc_attr( __( 'team photo for', 'football-pool' ) )
									, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
								);
			} else {
				$thumb = sprintf( '<a class="thumb fp-lightbox" href="%s"><img src="%s" title="%s %s" alt="%s %s" 
										class="team-photo thumb"></a>'
									, esc_attr( $photo )
									, esc_attr( $photo )
									, esc_attr( __( 'Click to enlarge:', 'football-pool' ) )
									, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
									, esc_attr( __( 'team photo for', 'football-pool' ) )
									, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
								);
			}
		} else {
			$thumb = '';
		}
		return $thumb;
	}
	
	public function HTML_image() {
		return sprintf( '<img src="%s" title="%s" alt="%s" class="team-photo">'
						, esc_attr( str_replace( '_t', '', $this->get_photo_url( $this->photo ) ) )
						, esc_attr( __( 'close', 'football-pool' ) )
						, esc_attr( __( 'team photo for', 'football-pool' ) . ' ' . Football_Pool_Utils::xssafe( $this->name ) )
					);
	}
	
	public function get_plays() {
		global $pool;
		$matches = $pool->matches->matches;
		
		$plays = [];
		foreach ( $matches as $match ) {
			if ( $match['home_team_id'] == $this->id || $match['away_team_id'] == $this->id ) $plays[] = $match;
		}
		
		return $plays;
	}
	
	public function get_stadiums() {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT DISTINCT s.id, s.name, s.photo, s.comments
								FROM {$prefix}stadiums s, {$prefix}matches m 
								WHERE s.id = m.stadium_id 
									AND ( m.home_team_id = %d OR away_team_id = %d )
								ORDER BY s.name ASC",
								$this->id,
								$this->id
							);
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		
		$stadiums = [];
		foreach ( $rows as $row ) {
			$stadiums[] = new Football_Pool_Stadium( $row );
		}
		
		return $stadiums;
	}
}
