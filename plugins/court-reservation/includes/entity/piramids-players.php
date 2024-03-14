<?php
/**
 * The core plugin class.
 *
 * @since      1.5.0
 * @package    Piramid
 * @subpackage Courtres/includes
 * @author
 */

class Courtres_Entity_Piramids_Players extends Courtres_Entity_Base {

	public static $table_name = 'courtres_piramids_players';

	static function get_db_fields() {
		$db_fields = array(
			'id'         => array(
				'code'          => 'id',
				'title'         => 'id',
				'show_in_admin' => false,
				'default_value' => false,
			),
			'piramid_id' => array(
				'code'          => 'piramid_id',
				'title'         => __( 'Piramid id', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'player_id'  => array(
				'code'          => 'player_id',
				'title'         => __( 'Player id', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'sort'       => array(
				'code'          => 'sort',
				'title'         => __( 'Position', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 0,
			),
			'is_active'  => array(
				'code'          => 'is_active',
				'title'         => __( 'Active', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => true,
			),
		);
		return $db_fields;
	}


	static function create_table() {
		global $wpdb;
		$sql = sprintf(
			"CREATE TABLE IF NOT EXISTS `%1\$s` (
				`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`piramid_id` int unsigned NOT NULL,
				`player_id` bigint(20) NOT NULL,
				`sort` tinyint unsigned NOT NULL DEFAULT 1,
				`is_active` boolean  DEFAULT 1,
				FOREIGN KEY (`piramid_id`) REFERENCES `%2\$s` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				) 
				ENGINE='InnoDB' %3\$s AUTO_INCREMENT=1;",
			self::get_table_name(),
			Courtres_Entity_Piramid::get_table_name(),
			self::get_charset_collate()
		);
		$wpdb->query( $sql );
	}


	/**
	 * @return array of players or empty array
	 */
	static function get_by_piramid_id( int $piramid_id ) {
		$items = self::get_list(
			array(
				'where' => array(
					'conditions' => array( '`piramid_id` = ' . $piramid_id, '`is_active` = 1' ),
				),
				'sort'  => self::get_table_name() . '.sort ASC',
			)
		);
		if ( $items ) {
			foreach ( $items as &$item ) {
				$user                 = get_user_by( 'id', $item['player_id'] );
				$item['display_name'] = $user->display_name;
			}
		}
		return $items;
	}


	/**
	 * Challenger wins.
	 * So he takes the place of the challenged. The challenged moves down 1 field.
	 *
	 * @param  int $piramid_id
	 * @param  int $challenged_id loser
	 * @param  int $challenger_id winner
	 * @return true | false
	 */
	static function reorder( int $piramid_id, int $challenged_id, int $challenger_id ) {
		global $wpdb;
		$players = self::get_by_piramid_id( $piramid_id );

		$challenged_index = array_search( $challenged_id, array_column( $players, 'player_id' ) );
		$challenger_index = array_search( $challenger_id, array_column( $players, 'player_id' ) );

		if ( $challenged_index && $challenger_index ) {
			$challenged_sort = $players[ $challenged_index ]['sort'];
			$challenger_sort = $players[ $challenger_index ]['sort'];

			$new_players = $players;
			for ( $sort = $challenged_sort; $sort < $challenger_sort; $sort ++ ) {
				$new_players[ $sort ]['sort']++;
			}
			$new_players[ $challenger_sort ]['sort'] = $challenged_sort;
			foreach ( $new_players as $key => $player ) {
				$res = $wpdb->query(
					$wpdb->prepare(
						'UPDATE `' . self::get_table_name() . '` SET `sort` = %d WHERE `id` = %d AND `piramid_id` = %d',
						$player['sort'],
						$player['id'],
						$piramid_id
					)
				);
			}
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}
}
