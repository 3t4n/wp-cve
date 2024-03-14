<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class conditionController {

	protected $db;
	public function __construct() {
		global $wpdb;
		$this->db = $wpdb;
	}
	public function __destruct() {
		// $this->db->close();
	}

	/**
	 * @param $element_id
	 * @param $condition_element_id
	 * @param $op
	 * @param $elementitem_id
	 * @return $id of created element
	 */
	function create( array $values ) {

		add_filter( 'query', 'scc_replace_string_null' );
		if ( ! function_exists( 'scc_replace_string_null' ) ) {
			function scc_replace_string_null( $query ) {
				return str_ireplace( "'##NULL'", 'NULL', $query );
			}
		}

		( isset( $values['element_id'] ) ) ? $element_id                     = $values['element_id'] : $element_id = '##NULL';
		( isset( $values['condition_element_id'] ) ) ? $condition_element_id = $values['condition_element_id'] : $condition_element_id = '##NULL';
		( isset( $values['op'] ) ) ? $op                                     = $values['op'] : $op = '##NULL';
		( isset( $values['elementitem_id'] ) ) ? $elementitem_id             = $values['elementitem_id'] : $elementitem_id = '##NULL';
		if ( isset( $values['value'] ) ) {
			$value = $values['value'];
		} elseif ( isset( $values['number'] ) ) {
			$value = $values['number'];
		} else {
			$value = '##NULL';
		}

		$condition_set = isset( $values['condition_set'] ) ? $values['condition_set'] : 1;

		$query  = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_conditions (element_id,condition_element_id,op,elementitem_id,`value`, condition_set) VALUES (%s,%s,%s,%s,%s,%d) ;",
			$element_id,
			$condition_element_id,
			$op,
			$elementitem_id,
			$value,
			$condition_set
		);
		$result = $this->db->query( $query );
		$id     = $this->db->insert_id;
		remove_filter( 'query', 'scc_replace_string_null' );
		if ( $result ) {
			return $id;
		} else {
			$this->db->last_error;
		}
	}

	/**
	 * @param $id if no param returns all conditions
	 * @return object returns one condition
	 * @return array returns all conditions
	 *
	 */
	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_conditions" ) ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_conditions WHERE id =%d", $id ) );
		return $result;
	}
	/**
	 * @param integer $id_form if of the form
	 * todo: use to load first select for condition in elements
	 * @return array of all element for conditions
	 */

	function readOfForm( int $form_id ) {
		$request1 = $this->db->get_results(
			$this->db->prepare(
				"SELECT {$this->db->prefix}df_scc_elements.id AS element_id, {$this->db->prefix}df_scc_elements.titleElement 
        AS element_title, {$this->db->prefix}df_scc_elements.type AS element_type FROM `{$this->db->prefix}df_scc_elements`
        INNER JOIN {$this->db->prefix}df_scc_subsections ON {$this->db->prefix}df_scc_subsections.id = {$this->db->prefix}df_scc_elements.subsection_id
        INNER JOIN {$this->db->prefix}df_scc_sections ON {$this->db->prefix}df_scc_sections.id = {$this->db->prefix}df_scc_subsections.section_id
        INNER JOIN {$this->db->prefix}df_scc_forms ON {$this->db->prefix}df_scc_forms.id = {$this->db->prefix}df_scc_sections.form_id
        WHERE {$this->db->prefix}df_scc_forms.id =%d AND NOT {$this->db->prefix}df_scc_elements.type = 'checkbox' AND NOT {$this->db->prefix}df_scc_elements.type = 'comment box' ;",
				$form_id
			)
		);

		$request2 = $this->db->get_results(
			$this->db->prepare(
				"SELECT {$this->db->prefix}df_scc_elementitems.id AS element_item_id, {$this->db->prefix}df_scc_elementitems.name AS element_item_name,
        {$this->db->prefix}df_scc_elements.type AS element_item_type FROM `{$this->db->prefix}df_scc_elements` INNER JOIN {$this->db->prefix}df_scc_elementitems 
        ON {$this->db->prefix}df_scc_elementitems.element_id = {$this->db->prefix}df_scc_elements.id INNER JOIN {$this->db->prefix}df_scc_subsections ON 
        {$this->db->prefix}df_scc_subsections.id = {$this->db->prefix}df_scc_elements.subsection_id INNER JOIN {$this->db->prefix}df_scc_sections ON 
        {$this->db->prefix}df_scc_sections.id = {$this->db->prefix}df_scc_subsections.section_id INNER JOIN {$this->db->prefix}df_scc_forms ON {$this->db->prefix}df_scc_forms.id = 
        {$this->db->prefix}df_scc_sections.form_id WHERE {$this->db->prefix}df_scc_forms.id =%d AND {$this->db->prefix}df_scc_elements.type = 'checkbox' ;",
				$form_id
			)
		);

		$request = array_merge( $request1, $request2 );

		return $request;
	}

	/**
	 * @param integer $element_id
	 * todo: loads condition row with id of element
	 * ?use in duplicate feature duplicate element
	 */
	function readOfElement( int $element_id ) {
		return $this->db->get_results( $this->db->prepare( "SELECT * FROM `{$this->db->prefix}df_scc_conditions` WHERE element_id = %d ;", $element_id ) );
	}

	/**
	 * ?its not implemented in views
	 */
	function update() {
	}

	/**
	 * @param integer $id
	 * @return bool true or false
	 */
	function delete( int $id ) {
		$query    = $this->db->prepare( "DELETE FROM {$this->db->prefix}df_scc_conditions WHERE id = %d", $id );
		$response = $this->db->query( $query );
		if ( $response ) {
			return true;
		} else {
			return false;
		}
	}
}
