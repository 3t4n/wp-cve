<?php
namespace MultiFeedReader\Models;

abstract class Base
{
	/**
	 * Property dictionary for all tables
	 */
	private static $properties = array();
	
	private $is_new = true;
	
	/**
	 * Contains property values
	 */
	private $data = array();
	
	public function __set( $name, $value ) {
		if ( self::has_property( $name ) ) {
			$this->set_property( $name, $value );
		} else {
			$this->$name = $value;
		}
	}
	
	private function set_property( $name, $value ) {
		$this->data[ $name ] = $value;
	}
	
	public function __get( $name ) {
		if ( self::has_property( $name ) ) {
			return $this->get_property( $name );
		} else {
			return $this->$name;
		}
	}
	
	private function get_property( $name ) {
		if ( isset( $this->data[ $name ] ) ) {
			return $this->data[ $name ];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Define a property with name and type.
	 * 
	 * Currently only supports basics.
	 * @todo enable additional options like NOT NULL, DEFAULT etc.
	 * 
	 * @param string $name Name of the property / column
	 * @param string $type mySQL column type 
	 */
	public static function property( $name, $type ) {
		$class = get_called_class();
		
		if ( ! isset( self::$properties[ $class ] ) ) {
			self::$properties[ $class ] = array();
		}
		
		self::$properties[ $class ][] = array(
			'name' => $name,
			'type' => $type
		);
	}
	
	/**
	 * Return a list of property dictionaries.
	 * 
	 * @return array property list
	 */
	private static function properties() {
		$class = get_called_class();
		
		if ( ! isset( self::$properties[ $class ] ) ) {
			self::$properties[ $class ] = array();
		}
		
		return self::$properties[ $class ];
	}
	
	/**
	 * Does the given property exist?
	 * 
	 * @param string $name name of the property to test
	 * @return bool True if the property exists, else false.
	 */
	public static function has_property( $name ) {
		return in_array( $name, self::property_names() );
	}
	
	/**
	 * Return a list of property names.
	 * 
	 * @return array property names
	 */
	public static function property_names() {
		return array_map( function ( $p ) { return $p[ 'name' ]; }, self::properties() );
	}
	
	/**
	 * Does the table have any entries?
	 * 
	 * @return bool True if there is at least one entry, else false.
	 */
	public static function has_entries() {
		return self::count() > 0;
	}
	
	/**
	 * Return number of rows in the table.
	 * 
	 * @return int number of rows
	 */
	public static function count() {
		global $wpdb;
		
		$sql = 'SELECT COUNT(*) FROM ' . self::table_name();
		return (int) $wpdb->get_var( $sql );
	}
	
	public static function find_by_id( $id ) {
		global $wpdb;
		
		$class = get_called_class();
		$model = new $class();
		$model->flag_as_not_new();
		
		$row = $wpdb->get_row( 'SELECT * FROM ' . self::table_name() . ' WHERE id = ' . (int) $id );
		
		if ( ! $row ) {
			return NULL;
		}
		
		foreach ( $row as $property => $value ) {
			$model->$property = $value;
		}
		
		return $model;
	}

	public static function find_one_by_property( $property, $value ) {
		global $wpdb;
		
		$class = get_called_class();
		$model = new $class();
		$model->flag_as_not_new();
		
		$row = $wpdb->get_row(
			'SELECT * FROM ' . self::table_name() . ' WHERE ' . $property .  ' = \'' . esc_sql( $value ) . '\' LIMIT 0,1'
		);
		
		if ( ! $row ) {
			return NULL;
		}
		
		foreach ( $row as $property => $value ) {
			$model->$property = $value;
		}
		
		return $model;
	}

	// mimic ::find_by_<property>
	public static function __callStatic( $name, $arguments ) {
		
		$property = preg_replace_callback(
			"/^find_one_by_(\w+)$/",
			function ( $property ) { return $property[1]; },
			$name
		);
		
		if ( $property !== $name ) {
			return self::find_one_by_property( $property, $arguments[0] );
		} else {
			throw new \Exception("Fatal Error: Call to unknown static method $name.");
		}
  }
	
	/**
	 * Retrieve first item from the table.
	 * 
	 * @return model object
	 */
	public static function first() {
		global $wpdb;
		
		$class = get_called_class();
		$model = new $class();
		$model->flag_as_not_new();
		
		$row = $wpdb->get_row( 'SELECT * FROM ' . self::table_name() . ' LIMIT 0,1' );
		
		if ( ! $row ) {
			return NULL;
		}
		
		foreach ( $row as $property => $value ) {
			$model->$property = $value;
		}

		return $model;
	}
	
	/**
	 * Retrieve all entries from the table.
	 * 
	 * @return array list of model objects
	 */
	public static function all() {
		global $wpdb;
		
		$class = get_called_class();
		$models = array();
		
		$rows = $wpdb->get_results( 'SELECT * FROM ' . self::table_name() );
		foreach ( $rows as $row ) {
			$model = new $class();
			$model->flag_as_not_new();
			foreach ( $row as $property => $value ) {
				$model->$property = $value;
			}
			$models[] = $model;
		}
		
		return $models;
	}
	
	/**
	 * True if not yet saved to database. Else false.
	 */
	public function is_new() {
		return $this->is_new;
	}
	
	public function flag_as_not_new() {
		$this->is_new = false;
	}
	
	/**
	 * Saves changes to database.
	 * 
	 * @todo use wpdb::insert()
	 */
	public function save() {
		global $wpdb;
		
		if ( $this->is_new() ) {
			$sql = 'INSERT INTO '
			     . self::table_name()
			     . ' ( '
			     . implode( ',', self::property_names() )
			     . ' ) '
			     . 'VALUES'
			     . ' ( '
			     . implode( ',', array_map( array( $this, 'property_name_to_sql_value' ), self::property_names() ) )
			     . ' );'
			;
			$success = $wpdb->query( $sql );
			if ( $success ) {
				$this->id = $wpdb->insert_id;
			}
		} else {
			$sql = 'UPDATE ' . self::table_name()
			     . ' SET '
			     . implode( ',', array_map( array( $this, 'property_name_to_sql_update_statement' ), self::property_names() ) )
			     . ' WHERE id = ' . (int) $this->id
			;
			$success = $wpdb->query( $sql );
		}
		
		$this->is_new = false;
		
		return $success;
	}
	
	public function delete() {
		global $wpdb;
		
		$sql = 'DELETE FROM '
		     . self::table_name()
		     . ' WHERE id = ' . (int) $this->id;

		return $wpdb->query( $sql );
	}

	private function property_name_to_sql_update_statement( $p ) {
		if ( $this->$p ) {
			return "$p = '{$this->$p}'";
		} else {
			return "$p = NULL";
		}
	}
	
	private function property_name_to_sql_value( $p ) {
		if ( $this->$p ) {
			return "'{$this->$p}'";
		} else {
			return 'NULL';
		}
	}
	
	/**
	 * Create database table based on defined properties.
	 * 
	 * Automatically includes an id column as auto incrementing primary key.
	 * @todo allow model changes
	 */
	public static function build() {
		global $wpdb;
		
		$property_sql = array();
		$properties = self::properties();
		foreach ( $properties as $property ) {
			$property_sql[] = "`{$property['name']}` {$property['type']}";
		}
		
		$sql = 'CREATE TABLE IF NOT EXISTS '
		     . self::table_name()
		     . ' ('
		     . implode( ',', $property_sql )
		     . ' );'
		;
		
		$wpdb->query( $sql );
	}
	
	/**
	 * Retrieves the database table name.
	 * 
	 * The name is derived from the namespace an class name. Additionally, it
	 * is prefixed with the global WordPress database table prefix.
	 * @todo cache
	 * 
	 * @return string database table name
	 */
	public static function table_name() {
		global $wpdb;
		
		// get name of implementing class
		$table_name = get_called_class();
		// replace backslashes from namespace by underscores
		$table_name = str_replace( '\\', '_', $table_name );
		// remove Models subnamespace from name
		$table_name = str_replace( 'Models_', '', $table_name );
		// all lowercase
		$table_name = strtolower( $table_name );
		// prefix with $wpdb prefix
		return $wpdb->prefix . $table_name;
	}
	
	public static function destroy() {
		global $wpdb;
		$wpdb->query( 'DROP TABLE ' . self::table_name() );
	}
}
