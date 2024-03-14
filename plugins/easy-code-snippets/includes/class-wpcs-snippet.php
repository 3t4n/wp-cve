<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Snippet Class
 *
 * Handles snippet functionailties
 */
class ECSnippets_Snippet {

	public $table;

	public function __construct() {

		global $wpdb;
		$this->wpdb			= $wpdb;
		$this->table		= $wpdb->prefix.'ecs_snippets';
	}

	/**
	 * ECSnippets_Snippet The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main ECSnippets_Snippet Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Save Snippet
	 */
	public function save_snippet( $snippet_id = '', $args = array() ) {

		// if no argument then return
		if( empty($args) ) return;

		if( !empty($snippet_id) ) {
			$update = $this->wpdb->update( $this->table, $args, array('ID' => $snippet_id) );
			if( $update === false ) return false;
		} else {
			$this->wpdb->insert( $this->wpdb->prefix.'ecs_snippets', $args );
			$snippet_id = $this->wpdb->insert_id;
		}

		return $snippet_id;
	}

	/**
	 * Get snippet
	 */
	public function get_snippet( $id ) {

		if( empty($id) ) return false;
		return $this->wpdb->get_row( "SELECT * FROM {$this->table} WHERE ID = {$id}", ARRAY_A );
	}

	/**
	 * Return snippet title
	 */
	public function get_snippet_title( $id ) {
		if( empty($id) ) return false;
		$result = $this->wpdb->get_row( "SELECT title FROM {$this->table} WHERE ID = {$id}", ARRAY_A );
		return isset( $result['title'] ) ? $result['title'] : false;
	}
}