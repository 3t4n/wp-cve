<?php

 class Xoo_Wl_DB{

 	protected static $_instance = null;
	public $waitlist_table, $waitlist_meta_table;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){

		global $wpdb;

		$this->waitlist_table		= $wpdb->prefix . 'xoo_wl_list';
		$this->waitlist_meta_table = $wpdb->prefix . 'xoo_wl_list_meta';

		$this->hooks();

	}


	public function hooks(){
		add_action( 'wp_loaded', array( $this, 'create_table' ), 15 );
		add_action( 'plugins_loaded', array( $this, 'register_meta_table' ), 20 );
	}

	/* Inserts new Row if does not exist
	   Updates if row duplication is not allowed
	*/
	public function update_waitlist_row( $data = array() ){

		global $wpdb;

		$defaults = array(
			'product_id' 	=> 0,
			'join_date' 	=> current_time( 'mysql' ),
			'email'			=> null,
			'quantity' 		=> 1,
			'user_id'		=> get_current_user_id(),
		);

		$data = wp_parse_args( $data, $defaults );


		if( !$data['product_id'] || !$data['email'] ){
			return new WP_Error( "Product ID/ Email missing" );
		}

		$meta_data = array();

		$data = wp_unslash( apply_filters( 'xoo_wl_before_inserting_waitlist_row', $data ) );

		if( isset( $data['meta'] ) ){
			$meta_data = $data[ 'meta' ];
			unset( $data['meta'] );
		}

		//Remove other keys
		foreach ( $data as $key => $value ) {
			if( !array_key_exists( $key, $defaults ) ){
				unset( $data[ $key ] );
			}
		}

		$allow_duplicate_email = apply_filters( 'xoo_wl_allow_duplicate_emails', false );
		$user_row_id = false;

		//Search if email id already exists
		if( !$allow_duplicate_email ){
			$user_exists =  $this->get_waitlist_rows_by_product( $data['product_id'], $data['email'] );
			if( !empty( $user_exists ) ){
				$user_row_id = $user_exists[0]->xoo_wl_id;
			}
		}
		

		//If user already exists & duplication is not allowed, update the row
		if( !$allow_duplicate_email && $user_row_id ){
			$action = $wpdb->update( $this->waitlist_table, $data, array(
				'xoo_wl_id' => $user_row_id
			) );
		}
		else{
			$action = $wpdb->insert( $this->waitlist_table, $data );
			$user_row_id = $wpdb->insert_id;
		}

		if( false === $action ){
			return new WP_Error( $wpdb->last_error );
		}

		foreach ( $meta_data as $meta_key => $meta_value ) {
			$this->update_waitlist_meta( $user_row_id, $meta_key, $meta_value );
		}
		
		return true;

	}

	public function update_waitlist_meta( $xoo_wl_id, $meta_key, $meta_value ){

		update_metadata( 'xoo_wl', $xoo_wl_id, $meta_key, $meta_value );

	}

	public function get_waitlist_meta( $xoo_wl_id, $meta_key = '', $single = true ){
		$meta_value = get_metadata( 'xoo_wl', $xoo_wl_id, $meta_key, $single  );
		if( !$meta_key && is_array( $meta_value ) ){
			foreach ( $meta_value as $key => $value ) {
				$meta_value[ $key ] = maybe_unserialize( $value[0] );
			}
		}
		return $meta_value;
	}




	private function get_placeholder( $input ){

		$type = gettype( $input );

		if( $type === "integer" ){
			return '%d';
		}elseif( $type === "float" ){
			return '%f';
		}
		else{
			return '%s';
		}

	}


	public function get_products_waitlist( $args = array(), $output = OBJECT ){

		global $wpdb;

		$defaults = array(
			'limit' 	=> -1,
			'offset' 	=> 0
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$values = array();

		$query = "
		SELECT product_id, SUM(quantity) AS quantity, COUNT(*) AS entries FROM {$this->waitlist_table}
		WHERE 1 = %d
		GROUP BY product_id
		";

		$values[] = 1;

		if( $limit !== -1 ){

			$query .= " LIMIT {$this->get_placeholder( $limit )}";
			$values[] = $limit;

			if( $offset ){
				$query .= " OFFSET {$this->get_placeholder( $offset )}";
				$values[] = $offset;
			}

		}

		$page_results = $wpdb->get_results( 
			$wpdb->prepare(
				$query,
				$values
			),
			$output
		);

	
		return $page_results;

	}


	public function get_waitlisted_count( $product_id = false ){

		global $wpdb;

		$query = "
		SELECT COUNT(DISTINCT {$this->waitlist_table}.product_id) AS productsCount, COUNT(*) AS rowsCount, SUM(quantity) AS totalQuantity FROM {$this->waitlist_table}
		";

		$values = array();

		$query .= " WHERE";

		if( $product_id ){
			$query .= " product_id = %d";
			$values[] = $product_id;
		}
		else{
			$query .= " 1 = %d";
			$values[] = 1;
		}

		$results = $wpdb->get_row(
			$wpdb->prepare( 
				$query,
				$values
			),
			ARRAY_A
		);

		return array(
			'rowsCount' 	=> $results['rowsCount'],
			'productsCount' => $results['productsCount'],
			'totalQuantity' => $results['totalQuantity']
		);

	}


	public function get_waitlist_rows_by_product( $product_id, $user_email = false, $args = array() ){
		
		$defaults = array(
			'limit' 	=> -1,
			'offset' 	=> 0
		);

		$args = wp_parse_args( $args, $defaults );

		$args['where'][] = array(
			'key' 		=> 'product_id',
			'value' 	=> (int) $product_id,
			'compare' 	=> '='
		);


		if( $user_email ){
			$args['where'][] = array(
				'key' 		=> 'email',
				'value' 	=> $user_email,
				'compare' 	=> '='
			);
		}

		$rows = $this->get_waitlist_rows( $args );
		
		return $rows;
	}


	public function get_waitlist_row( $row_id, $output = OBJECT ){

		$args['where'][] = array(
			'key' 		=> 'xoo_wl_id',
			'value' 	=> $row_id,
			'compare' 	=> '='
		);

		$rows = $this->get_waitlist_rows( $args, $output );

		if( !empty( $rows ) ){
			return $rows[0];
		}

		return false;
	}


	public function get_waitlist_rows( $args = array(), $output = OBJECT ){
		
		global $wpdb;

		$defaults = array(
			'limit' 		=> -1,
			'offset' 		=> 0,
			'where' 		=> array(),
			'meta_query' 	=> array(),
			'relation' 		=> 'AND'
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$values = array();

		$query = "SELECT * FROM {$this->waitlist_table}";

		/*if( $with_meta ){
			$query .= " LEFT JOIN {$this->waitlist_meta_table} ON {$this->waitlist_table}.xoo_wl_id = {$this->waitlist_meta_table}.xoo_wl_id";
		}*/

		$query .= " WHERE";

		if( !empty( $where ) ){
			
			$i = 0;
			foreach ( $where as $index => $whereData ) {
				if( $i > 0 ){
					$query .= " {$relation}";
				}
				$query .= " {$whereData['key']}";
				$compare = isset( $whereData['compare'] ) ? $whereData['compare'] : '=';
				$query .= " {$compare}";
				$query .= " {$this->get_placeholder( $whereData['value'] )}";
				$values[] = $whereData['value'];
				$i++;
			}
		}
		else{
			$query .= " 1 = %d";
			$values[] = 1;
		}


		if( $limit !== -1 ){

			$query .= " LIMIT {$this->get_placeholder( $limit )}";
			$values[] = $limit;

			if( $offset ){
				$query .= " OFFSET {$this->get_placeholder( $offset )}";
				$values[] = $offset;
			}

		}


		$results = $wpdb->get_results( 
			$wpdb->prepare(
				$query,
				$values
			),
			$output 
		);


		return $results;
		

	}

	/* $where contains key value pair of column and value */
	public function delete_waitlist_row( $where = array() ){

		global $wpdb;

		if( empty( $where ) ) return;

		$where = wp_unslash( $where );

		return $wpdb->delete(
			$this->waitlist_table,
			$where
		);
	}


	public function delete_waitlist_by_product( $product_id ){

		global $wpdb;

		$product_id = (int) $product_id;


		$users =  $this->get_waitlist_rows_by_product( $product_id );

		if( empty( $users ) ) return;

		$row_ids = array();

		foreach ( $users as $user_row ) {
			$row_ids[] = $user_row->xoo_wl_id;
		}

		$delete_meta = $this->delete_waitlist_meta_by_row_id( $row_ids );

		if( false === $delete_meta ){
			return new WP_Error( $wpdb->last_error );
		}

		$delete_row = $this->delete_waitlist_row(
			array(
				'product_id' => $product_id
			)
		);


		if( false === $delete_row ){
			return new WP_Error( $wpdb->last_error );
		}

		return true;
	}



	public function delete_waitlist_row_by_id( $row_id ){

		global $wpdb;

		$delete_meta = $this->delete_waitlist_meta_by_row_id( array(
			$row_id
		) );

		if( false === $delete_meta ){
			return new WP_Error( $wpdb->last_error );
		}

		$delete_row = $this->delete_waitlist_row(
			array(
				'xoo_wl_id' => $row_id
			)
		);

		if( false === $delete_row ){
			return new WP_Error( $wpdb->last_error );
		}


	}

	public function delete_waitlist_meta_by_row_id( $row_ids ){

		global $wpdb;

		if( empty( $row_ids ) ) return;

		$row_ids = wp_unslash( $row_ids );

		$placeholder =  implode( ",", array_fill( 0, count($row_ids), '%d' ) );

		$query = "DELETE FROM {$this->waitlist_meta_table} WHERE xoo_wl_id IN ({$placeholder})";

		return $wpdb->query(
			$wpdb->prepare(
				$query,
				$row_ids
			)
		);
	}



	public function create_table(){

		global $wpdb;

		$version_option = 'xoo-wl-db-version';
		$db_version 	= get_option( $version_option );

		if( version_compare( $db_version, '1.0', '=' ) ) return;

		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE {$this->waitlist_table} (
			xoo_wl_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
			product_id BIGINT(20) UNSIGNED NOT NULL,
			email VARCHAR(100) NOT NULL,
			quantity FLOAT(20) UNSIGNED NOT NULL,
			join_date DATETIME NOT NULL,
			user_id BIGINT(20) UNSIGNED NOT NULL,
			INDEX product_id (product_id),
			PRIMARY KEY  (xoo_wl_id)
			) $charset_collate;";


		$sql .= "CREATE TABLE {$this->waitlist_meta_table} (
			meta_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
			xoo_wl_id BIGINT(20) UNSIGNED NOT NULL,
			meta_key VARCHAR(255),
			meta_value LONGTEXT,
			INDEX meta_key (meta_key),
			INDEX xoo_wl_id (xoo_wl_id),
			PRIMARY KEY  (meta_id)
			) $charset_collate;";

		dbDelta( $sql );

		update_option( $version_option, '1.0' );

	}


	public function register_meta_table(){
		global $wpdb;
		$wpdb->xoo_wlmeta = $this->waitlist_meta_table;
	}

}


function xoo_wl_db(){
	return Xoo_Wl_DB::get_instance();
}
xoo_wl_db();