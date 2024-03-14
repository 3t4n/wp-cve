<?php
/**
 * The core plugin class.
 *
 * @since      1.5.0
 * @package    Piramid
 * @subpackage Courtres/includes
 * @author
 */

class Courtres_Entity_Piramid extends Courtres_Entity_Base {

	public static $table_name = 'courtres_piramids';
	const MIN_PLAYERS         = 3;
	const MAX_PLAYERS         = 45;

	static function get_db_fields() {
		$db_fields = array(
			'id'          => array(
				'code'          => 'id',
				'title'         => 'id',
				'show_in_admin' => true,
				'default_value' => false,
			),
			'name'        => array(
				'code'          => 'name',
				'title'         => __( 'Name', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 'Pyramid',
			),
			'mode'        => array(
				'code'          => 'mode',
				'title'         => __( 'Mode', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 'One Set',
			),
			'duration_ts' => array(
				'code'          => 'duration_ts',
				'title'         => __( 'Challenges duration', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 0,
			),
			'lifetime_ts' => array(
				'code'          => 'lifetime_ts',
				'title'         => __( 'Challenges life time', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 86400, // sec, 24h
			),
			'locktime_ts' => array(
				'code'          => 'locktime_ts',
				'title'         => __( 'Challenges lock time', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 0,
			),
			'is_active'   => array(
				'code'          => 'is_active',
				'title'         => __( 'Active', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => true,
			),
			'design'      => array(
				'code'          => 'design',
				'title'         => __( 'Design', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => array(
					'btn_txt'    => array(
						'color'       => '#FFF',
						'size'        => '12px',
						'line_height' => '16px',
					),
					'btn_colors' => array(
						'enabled'      => '#AAA',
						'disabled'     => '#cac5c5',
						'hover'        => '#bbb7b7',
						'current'      => '#bbb7b7',
						'border_color' => '#000',
					),
					'btn_border' => array(
						'color' => '#000',
						'width' => '0px',
					),
					'btn_sizes'  => array(
						'width'  => '110px',
						'height' => '40px',
					),
					'viewport'   => array(
						'max_width' => '576px',
					),
				),
			),
			// "created_dt" => array(
			// "code" => "created_dt",
			// "title" => __("Created date", "courtres"),
			// "show_in_admin" => false,
			// "default_value" =>  false
			// ),
			// "modified_dt" => array(
			// "code" => "modified_dt",
			// "title" => __("Modified date", "courtres"),
			// "show_in_admin" => false,
			// "default_value" =>  false
			// ),
		);
		return $db_fields;
	}


	static function create_table() {
		global $wpdb;
		$sql = sprintf(
			"CREATE TABLE IF NOT EXISTS `%1\$s` (
				`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`name` varchar(128) NOT NULL,
				`duration_ts` int unsigned NOT NULL,
				`lifetime_ts` int unsigned NOT NULL,
				`locktime_ts` int unsigned NOT NULL,
				`mode` ENUM ('One Set', 'Best Of Three') NOT NULL DEFAULT 'One Set',
				`is_active` boolean  DEFAULT 1,
				`created_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`modified_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE='InnoDB' %2\$s AUTO_INCREMENT=1;",
			$wpdb->prefix . static::$table_name,
			self::get_charset_collate()
		);
		$wpdb->query( $sql );
	}

	/**
	 * @return string name of the piramid
	 */
	public function get_name() {
		$piramid = self::get_by_id( $this->get_id() );
		return $piramid['name'];
	}


	/**
	 * @param array  with keys: id, sort
	 * @return true|false
	 */
	public function save_players( array $players ) {
		$result = false;
		foreach ( $players as $key => $player ) {
			if ( isset( $player['player_id'] ) && isset( $player['sort'] ) ) {
				$row = array(
					'piramid_id' => $this->get_id(),
					'player_id'  => $player['player_id'],
					'sort'       => $player['sort'],
				);

				$cnt = Courtres_Entity_Piramids_Players::count(
					array(
						'conditions' => array(
							'`piramid_id` = ' . $row['piramid_id'],
							'`player_id` = ' . $row['player_id'],
							'`sort` = ' . $row['sort'],
						),
					)
				);
				if ( ! $cnt ) {
					$res = Courtres_Entity_Piramids_Players::insert( $row );
					if ( $res ) {
						$result = true;
					}
				}
			}
		}
		return $result;
	}


	/**
	 * @param arrays with keys: id, sort
	 * @return true|false
	 */
	public function update_players( array $players ) {
		$result = false;
		$res    = Courtres_Entity_Piramids_Players::delete( array( 'piramid_id' => $this->get_id() ) );
		$result = $this->save_players( $players );
		return $result;
	}


	/**
	 * @param array  with keys: id, sort
	 * @return array of players or empty array
	 */
	public function get_players() {
		$items = Courtres_Entity_Piramids_Players::get_by_piramid_id( $this->id );
		return $items ? $items : array();
	}


	/**
	 * Get one post with last ID with shortcode [courtpyramid id="#id#" courts="1,2..."]
	 *
	 * @param  int $piramid_id
	 * @return
	 */
	static function get_post_with_shortcode( int $piramid_id ) {
		global $wpdb;
		$sql    = sprintf(
			"SELECT ID, post_title, post_name, guid FROM %1\$s 
			WHERE `post_content` REGEXP '\\\[courtpyramid.*id=[\', \"]?%2\$d[\', \"]?.*\\\]' AND `post_type` != 'revision'
			ORDER BY `ID` DESC LIMIT 1",
			$wpdb->posts,
			$piramid_id
		);
		$result = $wpdb->get_row( $sql );
		return $result;
	}
}
