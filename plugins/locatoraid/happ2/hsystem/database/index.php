<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/query_builder.php' );
include_once( dirname(__FILE__) . '/db_forge.php' );

if( ! interface_exists('HC_Database_Engine_Interface') ){
interface HC_Database_Engine_Interface {
	public function query( $sql );
	public function insert_id();
	public function queries();
	public function get_error();
}
}

if( ! class_exists('HC_Database') ){
class HC_Database {
	private $engine = NULL;
	private $prefix = '';

	public function __construct( $engine, $prefix = NULL )
	{
		$this->engine = $engine;
		$this->set_prefix( $prefix );
	}

	public function engine()
	{
		return $this->engine;
	}

// factory
	public function query_builder()
	{
		$prefix = $this->prefix();
		$return = new HC_Database_Query_Builder;
		$return->set_prefix( $prefix );
		return $return;
	}
	public function dbforge()
	{
		$return = new HC_Database_Forge( $this->engine(), $this->query_builder() );
		return $return;
	}

// proxy to engine
	public function query( $sql )
	{
		return $this->engine->query( $sql );
	}
	public function insert_id()
	{
		return $this->engine->insert_id();
	}
	public function queries()
	{
		return $this->engine->queries();
	}
	public function results()
	{
		return $this->engine->results();
	}
	public function get_error()
	{
		return $this->engine->get_error();
	}

	public function prefix()
	{
		return $this->prefix;
	}
	public function set_prefix( $prefix )
	{
		$this->prefix = $prefix;
		return $this;
	}

	public function table_exists( $table_name )
	{
		$current_tables = $this->list_tables();

		$prefix = $this->prefix();
		if( substr($table_name, 0, strlen($prefix)) != $prefix ){
			$table_name = $prefix . $table_name;
		}

		$return = ( in_array($table_name, $current_tables) ) ? TRUE : FALSE;
		return $return;
	}

	public function list_tables()
	{
		$return = array();
		$sql = 'SHOW tables';

		$results = $this->query( $sql );
		foreach( $results as $r ){
			$return[] = array_shift( $r );
		}

		return $return;
	}

	public function field_exists( $field_name, $table_name )
	{
		$current_fields = $this->list_fields($table_name);
		$return = ( in_array($field_name, $current_fields) ) ? TRUE : FALSE;
		return $return;
	}

	public function list_fields( $table_name )
	{
		$return = array();
		$sql = 'SHOW COLUMNS FROM ' . $this->prefix() . $table_name;

		$results = $this->query( $sql );
		foreach( $results as $r ){
			$return[] = array_shift( $r );
		}

		return $return;
	}
}
}