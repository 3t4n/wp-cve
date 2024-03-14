<?php

class SrizonMortgageDB{
	static function createDBTables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$t_albums = $wpdb->prefix . 'srzmrt_instances';
		$sql      = '
CREATE TABLE ' . $t_albums . ' (
  id int(11) NOT NULL AUTO_INCREMENT,
  title text,
  options text,
  PRIMARY KEY (id)
) '.$charset_collate.';
';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	static function saveInstance( $payload ) {
		global $wpdb;
		$table                   = $wpdb->prefix . 'srzmrt_instances';
		$data['title']           = $payload['title'];
		$data['options']         = $payload['options'];

		$wpdb->insert( $table, $data );

		return $wpdb->insert_id;
	}

	static function updateInstanceSettings( $id, $payload ) {
		global $wpdb;
		$table = $wpdb->prefix . 'srzmrt_instances';

		$data['title']   = $payload->title;
		$data['options'] = maybe_serialize( $payload );

		$wpdb->update( $table, $data, [ 'id' => $id ] );
	}

	static function deleteInstance( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'srzmrt_instances';
		$q     = $wpdb->prepare( "delete from $table where id = %d", $id );
		$wpdb->query( $q );
		self::DeleteAlbumCache( $id );
	}
	

	static function getInstance( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'srzmrt_instances';
		$q     = $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id );
		$album = $wpdb->get_row( $q );
		if ( $album ) {
			$album->options = array_merge( srizon_mortgage_global_defaults(), (array) maybe_unserialize( $album->options ) );
		}

		return $album;
	}

	static function getAllInstances() {
		global $wpdb;
		$table  = $wpdb->prefix . 'srzmrt_instances';
		$albums = $wpdb->get_results( "SELECT * FROM $table order by id desc" );
		foreach ( $albums as $album ) {
			$album->options = array_merge( srizon_mortgage_global_defaults(), (array) maybe_unserialize( $album->options ) );
		}

		return $albums;
	}
	
}