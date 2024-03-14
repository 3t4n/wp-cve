<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class subsectionController {

	protected $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	function __destruct() {
		// $this->db->close();
	}
	/**
	 * @param string $order
	 * @param integer $section_id foreign key
	 * @return integer $id id of created element
	 */

	function create( array $values ) {
		( isset( $values['order'] ) ) ? $order           = $values['order'] : $order = '0';
		( isset( $values['section_id'] ) ) ? $section_id = $values['section_id'] : $section_id = '';
		$query  = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_subsections (`order`,`section_id`) VALUES (%s,%d);",
			$order,
			$section_id
		);
		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return 0;
		}
	}
	/**
	 * @param integer $id id of subsection
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_subsections" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_subsections WHERE id =%d", $id ) );
		return $result;
	}
	/**
	 * @param string $order
	 * @param integer $section_id foreign key
	 */
	function update( array $values ) {
		$i                                     = new self();
		$id                                    = $values['id'];
		$todo                                  = $i->read( $id );
		( isset( $values['order'] ) ) ? $order = $values['order'] : $order = $todo->order;
		( isset( $values['section_id'] ) ) ? $section_id = $values['section_id'] : $section_id = $todo->section_id;
		$query  = $this->db->prepare(
			"UPDATE {$this->db->prefix}df_scc_subsections SET `order`=%s,`section_id`=%d WHERE id =%d;",
			$order,
			$section_id,
			$id
		);
		$result = $this->db->query( $query );
		if ( $result ) {
			return true;
		} else {
			return $this->db->last_error;
		}
	}

	/**
	 * @param integer $id
	 */
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_subsections WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
}
