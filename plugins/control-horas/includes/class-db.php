<?php
/**
 * Control de horas Db.
 *
 * @since   0.0.0
 * @package Control_Horas
 */

/**
 * Control de horas Db.
 *
 * @since 0.0.0
 */
class CH_Db {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param object $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Create tables.
	 * InnoDB engine is required and table users must be of Inoodb type.
	 *
	 * @since  0.0.0
	 */
	public function create_tables() {
		global $wpdb;
		$prefix = $wpdb->get_blog_prefix();

		/*$create_query = 'CREATE TABLE IF NOT EXISTS ' .
			$prefix . 'ch_fichajes (
			`id` INT NOT NULL AUTO_INCREMENT,
			`user_id` BIGINT(20) UNSIGNED,
			`start` TIMESTAMP NULL DEFAULT NULL,
			`end` 	TIMESTAMP NULL DEFAULT NULL,
			`note` 	text,
			`ip` 	varchar(15),
			`ua`	text,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`user_id`)
				REFERENCES `' . $prefix . 'users` (`ID`)
				ON UPDATE CASCADE
			) ENGINE=INNODB;'; */

		$create_query = 'CREATE TABLE IF NOT EXISTS ' .
			$prefix . 'ch_fichajes (
			`id` INT NOT NULL AUTO_INCREMENT,
			`user_id` BIGINT(20) UNSIGNED,
			`start` TIMESTAMP NULL DEFAULT NULL,
			`end` 	TIMESTAMP NULL DEFAULT NULL,
			`note` 	text,
			`ip` 	varchar(15),
			`ua`	text,
			PRIMARY KEY (`id`) )';
		$result = $wpdb->query( $create_query );
		if ( false === $result ) {
			return $result;
		}

		/*$create_query = 'CREATE TABLE IF NOT EXISTS ' .
			$prefix . 'ch_pausas (
			`id` INT NOT NULL AUTO_INCREMENT,
			`fichaje_id` INT,
			`start` TIMESTAMP NULL DEFAULT NULL,
			`end` 	TIMESTAMP NULL DEFAULT NULL,
			`note` 	text,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`fichaje_id`)
				REFERENCES `' . $prefix . 'ch_fichajes`(`id`)
				ON UPDATE CASCADE
				ON DELETE CASCADE
			) ENGINE=INNODB;'; */

		$create_query = 'CREATE TABLE IF NOT EXISTS ' .
			$prefix . 'ch_pausas (
			`id` INT NOT NULL AUTO_INCREMENT,
			`fichaje_id` INT,
			`start` TIMESTAMP NULL DEFAULT NULL,
			`end` 	TIMESTAMP NULL DEFAULT NULL,
			`note` 	text,
			PRIMARY KEY (`id`) ) ';
		$result = $wpdb->query( $create_query );
		return $result;
	}

	/**
	 * Start current shift.
	 *
	 * @since  1.0.0
	 *
	 * @param int    $user_id is the id of wp_users.
	 * @param string $ip is the user IP address.
	 * @param string $ua is the user agent of user.
	 * @param string $note is a note.
	 */
	public function start_shift( $user_id, $ip, $ua, $note ) {
		if ( ! is_numeric( $user_id ) ) {
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'ch_fichajes';
		$data = array(
			'user_id' => $user_id,
			'start'   => current_time( 'mysql' ),
			'ip'      => $ip,
			'ua'      => $ua,
			'note'    => $note,
		);
		$format = array( '%d', '%s', '%s', '%s', '%s' );
		return $wpdb->insert( $table, $data, $format );
	}

	/**
	 * Stop current shift.
	 *
	 * @param int    $user_id is the id of wp_users.
	 * @param string $ip is the user IP address.
	 * @param string $ua is the user agent of user.
	 * @since  1.0.0
	 */
	public function stop_shift( $user_id, $ip, $ua ) {
		if ( ! is_numeric( $user_id ) ) {
			return;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;
		$table = $prefix . 'ch_fichajes';

		// get shift id where end is null.
		$query = 'SELECT id FROM ' . $wpdb->prefix . 'ch_fichajes
					WHERE user_id=%d 
					AND end IS NULL 
					ORDER BY start DESC
					LIMIT 1;
					';
		$data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
		if ( ! $data ) {
			return false;
		}
		$id = $data->id;

		// close shift.
		$data  = array( 'end' => current_time( 'mysql' ) );
		$where = array(
			'user_id' => $user_id,
			'id'      => $id,
		);
		return $wpdb->update( $table, $data, $where );
	}


	/**
	 * Update note on shift.
	 *
	 * @param int    $id is the shift id.
	 * @param string $note is a note.
	 * @since  1.0.0
	 */
	public function update_shift( $id, $note ) {
		if ( ! is_numeric( $id ) ) {
			return;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;
		$table = $prefix . 'ch_fichajes';

		// update shift.
		$data  = array( 'note' => $note );
		$where = array(
			'id'      => $id,
		);
		return $wpdb->update( $table, $data, $where );
	}


	/**
	 * Get current shift status.
	 *
	 * @param int $user_id is the id of wp_users.
	 * @since  1.0.0
	 */
	public function status_shift( $user_id ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table = $prefix . 'ch_fichajes';
		$query = 'SELECT count(*) AS active FROM ' .
			$prefix . 'ch_fichajes
			WHERE user_id=%d 
			AND end IS NULL 
			ORDER BY start DESC
			LIMIT 1
			;';
		$data = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );
		if ( ! $data ) {
			return false;
		}
		return ( 1 == $data->active ) ? true : false;
	}

	/**
	 * Get all shifts array.
	 *
	 * @since  1.0.0
	 */
	public function all_shifts() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table = $prefix . 'ch_fichajes';
		$query = ' SELECT 			
				user.display_name AS name, 
				fichaje.start AS start, 
				fichaje.end AS end,
				TIMEDIFF(fichaje.end, fichaje.start) AS diff,
				fichaje.ip AS ip,
				fichaje.note AS note,
				fichaje.id AS id
			FROM ' . $prefix . 'ch_fichajes AS fichaje
			INNER JOIN ' . $prefix . 'users AS user ' .
			'ON fichaje.user_id = user.ID
			ORDER BY fichaje.start DESC
			;';

		$data = $wpdb->get_results( $query );
		if ( ! $data ) {
			$data = array();
		}
		$result = (object) [ 'data' => $data ];
		return $result;
	}

	/**
	 * Shift by id
	 *
	 * @since  1.0.2
	 *
	 * @param int $id is the shift id.
	 */
	public function shift_by_id( $id ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table = $prefix . 'ch_fichajes';
		$query = ' SELECT 			
				user.display_name AS name, 
				fichaje.start AS start, 
				fichaje.end AS end,
				TIMEDIFF(fichaje.end, fichaje.start) AS diff,
				fichaje.ip AS ip,
				fichaje.ua AS ua,
				fichaje.note AS note,
				fichaje.id AS id
			FROM ' . $prefix . 'ch_fichajes AS fichaje
			INNER JOIN ' . $prefix . 'users AS user ' .
			'ON fichaje.user_id = user.ID
			WHERE fichaje.id = ' . $id . '
			ORDER BY fichaje.start DESC
			;';

		$data = $wpdb->get_row( $query, ARRAY_A );
		return $data;
	}

}
