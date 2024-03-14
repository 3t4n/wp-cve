<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class sectionController {

	protected $db;

	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	/**
	 * @param string $name not null
	 * @param string $description not null
	 * @param string $order not null
	 * @param string $accordion (false, true) not null
	 * @param string $showSectionTotal (false, true) not null
	 * @param integer $form_id foreign key not null
	 */
	function create( array $values ) {
		( isset( $values['name'] ) ) ? $name                         = $values['name'] : $name = 'Section title';
		( isset( $values['description'] ) ) ? $description           = $values['description'] : $description = 'Section description';
		( isset( $values['order'] ) ) ? $order                       = $values['order'] : $order = '0';
		( isset( $values['accordion'] ) ) ? $accordion               = $values['accordion'] : $accordion = 'false';
		( isset( $values['showSectionTotal'] ) ) ? $showSectionTotal = $values['showSectionTotal'] : $showSectionTotal = 'false';
		( isset( $values['form_id'] ) ) ? $form_id                   = $values['form_id'] : $form_id = '';
		$query = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_sections (`name`,`description`,`order`,`accordion`,`showSectionTotal`,`form_id`) VALUES (%s,%s,%d,%s,%s,%d);",
			$name,
			$description,
			$order,
			$accordion,
			$showSectionTotal,
			$form_id
		);

		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		if ( $result ) {
			return $id;
		} else {
			return $this->db->last_error;
		}
	}
	/**
	 * @param integer $id
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_sections" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_sections WHERE id =%d", $id ) );
		return $result;
	}
	/**
	 * @param string $name
	 * @param string $description
	 * @param string $order
	 * @param string $accordion
	 * @param string $showSectionTotal true or false
	 * @param integer $form_id foreign key
	 */
	function update( array $values ) {
		$i                                   = new self();
		$id                                  = $values['id'];
		$todo                                = $i->read( $id );
		( isset( $values['name'] ) ) ? $name = $values['name'] : $name = $todo->name;
		( isset( $values['description'] ) ) ? $description                     = $values['description'] : $description = $todo->description;
		( isset( $values['order'] ) ) ? $order                                 = $values['order'] : $order = $todo->order;
		( isset( $values['accordion'] ) ) ? $accordion                         = $values['accordion'] : $accordion = $todo->accordion;
		( isset( $values['showSectionTotal'] ) ) ? $showSectionTotal           = $values['showSectionTotal'] : $showSectionTotal = $todo->showSectionTotal;
		( isset( $values['showSectionTotalOnPdf'] ) ) ? $showSectionTotalOnPdf = $values['showSectionTotalOnPdf'] : $showSectionTotalOnPdf = $todo->showSectionTotalOnPdf;
		( isset( $values['form_id'] ) ) ? $form_id                             = $values['form_id'] : $form_id = $todo->form_id;
		$query  = $this->db->prepare(
			"UPDATE {$this->db->prefix}df_scc_sections SET  `name`=%s,`description`=%s,`order`=%s,`accordion`=%s,`showSectionTotal`=%s,`showSectionTotalOnPdf`=%s,`form_id`=%s WHERE id =%d;",
			$name,
			$description,
			$order,
			$accordion,
			$showSectionTotal,
			$showSectionTotalOnPdf,
			$form_id,
			$id
		);
		$result = $this->db->query( $query );
		if ( $result ) {
			return true;
		} else {
			return false;
		}
	}
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_sections WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
}
