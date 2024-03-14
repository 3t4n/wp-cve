<?php

if (!class_exists('WP_FFPC_Backend_memcache')):

class WP_FFPC_Backend_memcache extends WP_FFPC_Backend {
	/**
	 * init memcache backend
	 */
	protected  function _init () {
		/* Memcached class does not exist, Memcache extension is not available */
		if (!class_exists('Memcache')) {
			$this->log (  'PHP Memcache extension missing', LOG_WARNING );
			return false;
		}

		/* check for existing server list, otherwise we cannot add backends */
		if ( empty ( $this->options['servers'] ) && ! $this->alive ) {
			$this->log (  "servers list is empty, init failed", LOG_WARNING );
			return false;
		}

		/* check is there's no backend connection yet */
		if ( $this->connection === NULL )
			$this->connection = new Memcache();

		/* check if initialization was success or not */
		if ( $this->connection === NULL ) {
			$this->log ( 'error initializing Memcache PHP extension, exiting' );
			return false;
		}

		/* adding servers */
		foreach ( $this->options['servers'] as $server_id => $server ) {
				/* in case of unix socket */
			if ( $server['port'] === 0 )
				$this->status[$server_id] = $this->connection->connect ( 'unix:/' . $server['host'] );
			else
				$this->status[$server_id] = $this->connection->connect ( $server['host'] , $server['port'] );

			$this->log ( sprintf( '%s added',  $server_id ) );
		}

		/* backend is now alive */
		$this->alive = true;
		$this->_status();
	}

	/**
	 * check current backend alive status for Memcached
	 *
	 */
	protected  function _status () {
		/* server status will be calculated by getting server stats */
		$this->log ( "checking server statuses" );
		/* get servers statistic from connection */
		foreach ( $this->options['servers'] as $server_id => $server ) {
			if ( $server['port'] === 0 )
				$this->status[$server_id] = $this->connection->getServerStatus( $server['host'], 11211 );
			else
				$this->status[$server_id] = $this->connection->getServerStatus( $server['host'], $server['port'] );
			if ( $this->status[$server_id] == 0 )
				$this->log ( sprintf( '%s server is down',  $server_id ) );
			else
				$this->log ( sprintf( '%s server is up & running',  $server_id ) );
		}
	}

	/**
	 * get function for Memcached backend
	 *
	 * @param string $key Key to get values for
	 *
	*/
	protected  function _get ( &$key ) {
		return $this->connection->get($key);
	}

	/**
	 * Set function for Memcached backend
	 *
	 * @param string $key Key to set with
	 * @param mixed $data Data to set
	 *
	 */
	protected  function _set ( &$key, &$data, &$expire ) {
		$result = $this->connection->set ( $key, $data , 0 , $expire );
		return $result;
	}

	/**
	 *
	 * Flush memcached entries
	 */
	protected  function _flush ( ) {
		return $this->connection->flush();
	}


	/**
	 * Removes entry from Memcached or flushes Memcached storage
	 *
	 * @param mixed $keys String / array of string of keys to delete entries with
	*/
	protected  function _clear ( &$keys ) {
		/* make an array if only one string is present, easier processing */
		if ( !is_array ( $keys ) )
			$keys = array ( $keys => true );

		foreach ( $keys as $key => $dummy ) {
			$kresult = $this->connection->delete( $key );

			if ( $kresult === false ) {
				$this->log ( sprintf( 'unable to delete entry: %s',  $key ) );
			}
			else {
				$this->log ( sprintf( 'entry deleted: %s',  $key ) );
			}
		}
	}
}

endif;
