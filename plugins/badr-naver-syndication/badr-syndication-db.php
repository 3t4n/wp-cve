<?php

class badrSyndicationDB{

	private static $_instance = null;
	private $table = array();

	private final function __construct(){
		$this->table['index_table']['name'] = 'badr_syndication_indexed';
		$this->table['index_table']['query'] = 'link varchar(255),is_deleted ENUM(  \'N\',  \'Y\' ) NOT NULL DEFAULT  \'N\'';
		$this->table['log_table']['name'] = 'badr_syndication_log';
		$this->table['log_table']['query'] = 'url varchar(255) NOT NULL,date TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
	}

	public static function &getInstance() {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	function isTable( $table ) {
		global $wpdb;
		if( $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix.$table."'") != $wpdb->prefix.$table ) return false;
		return true;
	}
	
	public function createTable(){
		global $wpdb;
		$aSql = array();
		foreach($this->table as $table):
		if( !$this->isTable( $table['name'] ) ) {
			$aSql[$table['name']] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.$table['name']."(
				id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),".$table['query'].")
				CHARACTER SET=utf8 COLLATE utf8_general_ci";
		}
		endforeach;
		if( count($aSql) > 0 ){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			foreach( $aSql as $sql){
				dbDelta( $sql );
			}
		}
	}
	
	public function deleteTable(){
		
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		foreach($this->table as $table):
			if( $this->isTable($table['name']) ) $wpdb->query("DROP TABLE ".$wpdb->prefix.$table['name']);
		endforeach;
	}
	
	function insertLog($arr){
		global $wpdb;
		$wpdb->insert( $wpdb->prefix.$this->table['log_table']['name'] , array('url' => $arr['url']));
	}

	function indexedLog( $arr ){
		//ns_log($arr,1,0);
		global $wpdb;
		foreach( $arr as $link ) {
			$wpdb->insert( $wpdb->prefix.$this->table['index_table']['name'] , array('link' => $link));
		}
	}
	function getIndexedLog( $arr ) {
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->table['index_table']['name']." limit ".$arr['start'].", 100", ARRAY_A);
		//ChromePhp::log($result);
		return $result;
		
	}
	function emptyIndexedLog(){
		global $wpdb;
		$wpdb->query( 'TRUNCATE TABLE '.$wpdb->prefix.$this->table['index_table']['name'] );
	}
	
}