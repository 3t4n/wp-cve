<?php
require_once( 'FullUtf8.php' );
require_once( 'Ando/Utf8.php' );

/**
 * Full UTF-8 Wordpress Database Class 
 * by {@link http://andowebsit.es/blog/noteslog.com Andrea Ercolino}
 *
 */
class FullUtf8_wpdb extends wpdb 
{
	/**
	 * Perform a MySQL database query, using current database connection.
	 *
	 * More information can be found on the codex page.
	 *
	 * @since 0.71
	 *
	 * @param string $query Database query
	 * @return int|false Number of rows affected/selected or false on error
	 */
	public function query( $query ) {
		if ( ! $this->ready )
			return false;

		// Only use this technique for functions with same parameters.
		$i = $this->use_mysqli ? 'i' : '';
		$mysql_errno         = "mysql{$i}_errno";
		$mysql_error         = "mysql{$i}_error";
		$mysql_affected_rows = "mysql{$i}_affected_rows";
		$mysql_insert_id     = "mysql{$i}_insert_id";
		$mysql_fetch_assoc   = "mysql{$i}_fetch_assoc";

		// The following code is the same found in WordPress 4.0
		// except for using the above technique (variable functions)
		// and calling FullUtf8::escape/unescape where appropriate.

		/**
		 * Filter the database query.
		 *
		 * Some queries are made before the plugins have been loaded,
		 * and thus cannot be filtered with this method.
		 *
		 * @since 2.1.0
		 *
		 * @param string $query Database query.
		 */
		$query = apply_filters( 'query', $query );

		$this->flush();

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug..
		$this->last_query = $query;

		FullUtf8::escape($query);
		$this->_do_query( $query );

		// MySQL server has gone away, try to reconnect
		$errno = 0;
		if ( ! empty( $this->dbh ) ) {
			$errno = $mysql_errno( $this->dbh );
		}

		if ( empty( $this->dbh ) || 2006 == $errno ) {
			if ( $this->check_connection() ) {
				$this->_do_query( $query );
			} else {
				$this->insert_id = 0;
				return false;
			}
		}

		// If there is an error then take note of it..
		$this->last_error = $mysql_error( $this->dbh );

		if ( $this->last_error ) {
			// Clear insert_id on a subsequent failed insert.
			if ( $this->insert_id && preg_match( '/^\s*(insert|replace)\s/i', $query ) )
				$this->insert_id = 0;

			$this->print_error();
			return false;
		}

		if ( preg_match( '/^\s*(create|alter|truncate|drop)\s/i', $query ) ) {
			$return_val = $this->result;
		} elseif ( preg_match( '/^\s*(insert|delete|update|replace)\s/i', $query ) ) {
			$this->rows_affected = $mysql_affected_rows( $this->dbh );
			// Take note of the insert_id
			if ( preg_match( '/^\s*(insert|replace)\s/i', $query ) ) {
				$this->insert_id = $mysql_insert_id( $this->dbh );
			}
			// Return number of rows affected
			$return_val = $this->rows_affected;
		} else {
			$num_rows = 0;
			while ( $row = @$mysql_fetch_assoc( $this->result ) ) {
				array_walk($row, array('FullUtf8', 'unescape'));
				$this->last_result[$num_rows] = (object) $row;
				$num_rows++;
			}

			// Log number of rows the query returned
			// and return number of rows selected
			$this->num_rows = $num_rows;
			$return_val     = $num_rows;
		}

		return $return_val;
	}

	// Unfortunately I had to add the following function too,
	// because they made it private in the parent class..

	/**
	 * Internal function to perform the mysql_query() call.
	 *
	 * @since 3.9.0
	 *
	 * @access private
	 * @see wpdb::query()
	 *
	 * @param string $query The query to run.
	 */
	private function _do_query( $query ) {
		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
			$this->timer_start();
		}

		if ( $this->use_mysqli ) {
			$this->result = @mysqli_query( $this->dbh, $query );
		} else {
			$this->result = @mysql_query( $query, $this->dbh );
		}
		$this->num_queries++;

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
			$this->queries[] = array( $query, $this->timer_stop(), $this->get_caller() );
		}
	}

}
